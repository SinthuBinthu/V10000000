<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Validation\Rule;
use Surfsidemedia\Shoppingcart\Facades\Cart;
use Darryldecode\Cart\Facades\CartFacade;
use Surfsidemedia\Shoppingcart\ShoppingcartServiceProvider;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use App\Models\Contact;
use App\Mail\ContactQuickReply;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ContactRequest;
use App\Models\Slide;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{
    /**
     * Dashboard anzeigen
     */
    public function index()
    {
        $orders = Order::orderBy('created_at', 'DESC')->get()->take(10);

        $dashboardDatas = DB::select("
            SELECT 
                sum(if(status = 'ordered', total, 0)) AS TotalAmount,
                sum(if(status = 'ordered', total, 0)) AS TotalOrderedAmount,
                sum(if(status = 'delivered', total, 0)) AS TotalDeliveredAmount,
                sum(if(status = 'canceled', total, 0)) AS TotalCanceledAmount,
                count(*) AS Total,
                sum(if(status = 'ordered', 1, 0)) AS TotalOrdered,
                sum(if(status = 'delivered', 1, 0)) AS TotalDelivered,
                sum(if(status = 'canceled', 1, 0)) AS TotalCanceled
            FROM orders
        ");

        $monthlyData = DB::select("
        SELECT M.id AS MonthNo, M.name AS MonthName,
            IFNULL(D.TotalAmount, 0) AS TotalAmount,
            IFNULL(D.TotalOrderedAmount, 0) AS TotalOrderedAmount,
            IFNULL(D.TotalDeliveredAmount, 0) AS TotalDeliveredAmount,
            IFNULL(D.TotalCanceledAmount, 0) AS TotalCanceledAmount
        FROM month_names M
        LEFT JOIN (
            SELECT 
                DATE_FORMAT(created_at, '%b') AS MonthName,
                MONTH(created_at) AS MonthNo,
                sum(total) AS TotalAmount,
                sum(IF(status = 'ordered', total, 0)) AS TotalOrderedAmount,
                sum(IF(status = 'delivered', total, 0)) AS TotalDeliveredAmount,
                sum(IF(status = 'canceled', total, 0)) AS TotalCanceledAmount
            FROM orders 
            WHERE YEAR(created_at) = YEAR(NOW()) 
            GROUP BY YEAR(created_at), MONTH(created_at), DATE_FORMAT(created_at, '%b')
            ORDER BY MONTH(created_at)
        ) D ON D.MonthNo = M.id
    ");

        $AmountM = implode(',', collect($monthlyData)->pluck('TotalAmount')->toArray());
        $OrderedAmountM = implode(',', collect($monthlyData)->pluck('TotalOrderedAmount')->toArray());
        $DeliveredAmountM = implode(',', collect($monthlyData)->pluck('TotalDeliveredAmount')->toArray());
        $CanceledAmountM = implode(',', collect($monthlyData)->pluck('TotalCanceledAmount')->toArray());

        $TotalAmount = collect($monthlyData)->sum('TotalAmount');
        $TotalOrderedAmount = collect($monthlyData)->sum('TotalOrderedAmount');
        $TotalDeliveredAmount = collect($monthlyData)->sum('TotalDeliveredAmount');
        $TotalCanceledAmount = collect($monthlyData)->sum('TotalCanceledAmount');

        return view('admin.index', compact(
            'orders',
            'dashboardDatas',
            'AmountM',
            'OrderedAmountM',
            'DeliveredAmountM',
            'CanceledAmountM',
            'TotalAmount',
            'TotalOrderedAmount',
            'TotalDeliveredAmount',
            'TotalCanceledAmount'
        ));
    }



    /**
     * Alle Marken auflisten
     */
    public function brands()
    {
        $brands = Brand::orderBy('id', 'DESC')->paginate(10);
        return view('admin.brands', compact('brands'));
    }

    /**
     * Formular zum Anlegen einer neuen Marke
     */
    public function add_brand()
    {
        return view('admin.brand-add');
    }

    /**
     * Neue Marke speichern
     */
    public function brand_store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'slug'  => 'required|unique:brands,slug,'.$request->id,
            'image' => 'nullable|mimes:png,jpg,jpeg|max:2048',
        ]);

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $image    = $request->file('image');
            $ext      = $image->extension();
            $fileName = Carbon::now()->timestamp . '.' . $ext;
            $this->generateBrandThumbnailImage($image, $fileName);
            $brand->image = $fileName;
        }

        $brand->save();

        return redirect()
            ->route('admin.brands')
            ->with('status', 'Record has been added successfully!');
    }

    /**
     * Formular zum Bearbeiten einer Marke
     */
    public function brand_edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.brand-edit', compact('brand'));
    }

    /**
     * Bestehende Marke aktualisieren
     */
    public function brand_update(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'slug'  => [
                'required',
                Rule::unique('brands', 'slug')->ignore($request->id),
            ],
            'image' => 'nullable|mimes:png,jpg,jpeg|max:2048',
        ]);

        $brand = Brand::findOrFail($request->id);
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);

        if ($request->hasFile('image')) {
            // altes Bild löschen
            if (File::exists(public_path('uploads/brands/' . $brand->image))) {
                File::delete(public_path('uploads/brands/' . $brand->image));
            }
            $image    = $request->file('image');
            $ext      = $image->extension();
            $fileName = Carbon::now()->timestamp . '.' . $ext;
            $this->generateBrandThumbnailImage($image, $fileName);
            $brand->image = $fileName;
        }

        $brand->save();

        return redirect()
            ->route('admin.brands')
            ->with('status', 'Brand has been updated successfully!');
    }

    /**
     * Thumbnail für Marke erzeugen
     */
    protected function generateBrandThumbnailImage($image, string $imageName): void
    {
        $destinationPath = public_path('uploads/brands');

        $img = Image::make($image->getRealPath());
        $img->fit(124, 124, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        }, 'top')
        ->save($destinationPath . '/' . $imageName);
    }

    public function brand_delete($id)
    {
        $brand = Brand::find($id);
        if (File::exists(public_path('uploads/brands/' . $brand->image))) {
            File::delete(public_path('uploads/brands/' . $brand->image));
        }
        $brand->delete();

        return redirect()
            ->route('admin.brands')
            ->with('status', 'Brand has been deleted successfully!');
    }

    public function categories()
    {
           $categories = Category::orderBy('id','DESC')->paginate(10);
           return view("admin.categories",compact('categories'));
    }
    
    public function add_category()
    {
        return view('admin.category-add');
    }
    
    public function add_category_store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'slug'  => 'required|unique:categories,slug',
            'image' => 'nullable|mimes:png,jpg,jpeg|max:2048',
        ]);
    
        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
    
        // Nur wenn ein Bild hochgeladen wurde
        if ($request->hasFile('image')) {
            $image     = $request->file('image');
            $extension = $image->extension();
            $fileName  = Carbon::now()->timestamp . '.' . $extension;
    
            $this->GenerateCategoryThumbailImage($image, $fileName);
            $category->image = $fileName;
        }
    
        $category->save();
    
        return redirect()
            ->route('admin.categories')
            ->with('status', 'Record has been added successfully !');
    }
    
    public function GenerateCategoryThumbailImage($image, string $imageName): void
    {
        $destinationPath = public_path('uploads/categories');
        $img = Image::make($image->getRealPath());
        $img->fit(124, 124, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        }, 'top')
        ->save($destinationPath . '/' . $imageName);
    }

    public function edit_category($id)
    {
        $category = Category::find($id);
        return view('admin.category-edit',compact('category'));
    }

    public function update_category(Request $request)
{
    $request->validate([
        'name' => 'required',
        'slug' => 'required|unique:categories,slug,'.$request->id,
        'image' => 'mimes:png,jpg,jpeg|max:2048'
    ]);
    $category = Category::find($request->id);
    $category->name = $request->name;
    $category->slug = $request->slug;
    if($request->hasFile('image'))
    {            
        if (File::exists(public_path('uploads/categories').'/'.$category->image)) {
            File::delete(public_path('uploads/categories').'/'.$category->image);
        }
        $image = $request->file('image');
        $file_extention = $request->file('file')->extension();
        $file_name = Carbon::now()->timestamp . '.' . $file_extention;
        $this->GenerateCategoryThumbailImage($image,$file_name);   
        $category->image = $file_name;
    }        
    $category->save();    
    return redirect()->route('admin.categories')->with('status','Record has been updated successfully !');
}

public function delete_category($id)
{
    $category = Category::find($id);
    if (File::exists(public_path('uploads/categories').'/'.$category->image)) {
        File::delete(public_path('uploads/categories').'/'.$category->image);
    }
    $category->delete();
    return redirect()->route('admin.categories')->with('status','Record has been deleted successfully !');
}

public function products()
{
    $products = Product::orderBy('created_at', 'DESC')->paginate(10);
    return view('admin.products', compact('products'));
}

public function product_add()
{
    $categories = Category::select('id', 'name')->orderBy('name')->get();
    $brands     = Brand::select('id', 'name')->orderBy('name')->get();
    return view('admin.product-add', compact('categories', 'brands'));
}

public function product_store(Request $request)
{
    $request->validate([
        'name'               => 'required',
        'slug'               => 'required|unique:products,slug',
        'category_id'        => 'required',
        'brand_id'           => 'required',
        'short_description'  => 'required',
        'description'        => 'required',
        'regular_price'      => 'required',
        'sale_price'         => 'required',
        'SKU'                => 'required',
        'stock_status'       => 'required',
        'featured'           => 'required',
        'quantity'           => 'required',
        'image'              => 'required|mimes:png,jpg,jpeg|max:2048',
        'images.*'           => 'nullable|mimes:png,jpg,jpeg|max:2048',
    ]);

    $product = new Product();
    $product->name              = $request->name;
    $product->slug              = Str::slug($request->name);
    $product->short_description = $request->short_description;
    $product->description       = $request->description;
    $product->regular_price     = $request->regular_price;
    $product->sale_price        = $request->sale_price;
    $product->SKU               = $request->SKU;
    $product->stock_status      = $request->stock_status;
    $product->featured          = $request->featured;
    $product->quantity          = $request->quantity;
    $product->category_id       = $request->category_id;
    $product->brand_id          = $request->brand_id;

    $current_timestamp = Carbon::now()->timestamp;

    // Hauptbild hochladen + Thumbnail
    if ($request->hasFile('image')) {
        // altes Bild löschen (nur bei Update sinnvoll)
        if ($product->image && File::exists(public_path("uploads/products/{$product->image}"))) {
            File::delete(public_path("uploads/products/{$product->image}"));
            File::delete(public_path("uploads/products/thumbnails/{$product->image}"));
        }

        $file      = $request->file('image');
        $fileName  = $current_timestamp . '.' . $file->extension();
        $this->GenerateThumbnailImage($file, $fileName);
        $product->image = $fileName;
    }

    // Gallery-Bilder
    $gallery_arr = [];
    if ($request->hasFile('images')) {
        // altes Gallery löschen (falls Update)
        if ($product->images) {
            foreach (explode(',', $product->images) as $oldFile) {
                $oldFile = trim($oldFile);
                if (File::exists(public_path("uploads/products/{$oldFile}"))) {
                    File::delete(public_path("uploads/products/{$oldFile}"));
                    File::delete(public_path("uploads/products/thumbnails/{$oldFile}"));
                }
            }
        }

        $counter = 1;
        foreach ($request->file('images') as $file) {
            $ext      = $file->extension();
            $fileName = $current_timestamp . '-' . $counter++ . '.' . $ext;
            $this->GenerateThumbnailImage($file, $fileName);
            $gallery_arr[] = $fileName;
        }
    }
    $product->images = implode(',', $gallery_arr);

    $product->save();

    return redirect()
        ->route('admin.products')
        ->with('status', 'Record has been added successfully!');
}

/**
 * Speichert Originalbild und erstellt ein Thumbnail (104×138px).
 */
protected function GenerateThumbnailImage($file, string $fileName): void
{
    $destinationPath = public_path('uploads/products');
    $thumbPath       = public_path('uploads/products/thumbnails');

    // Ordner anlegen, falls nicht vorhanden
    if (!File::exists($destinationPath)) {
        File::makeDirectory($destinationPath, 0755, true);
    }
    if (!File::exists($thumbPath)) {
        File::makeDirectory($thumbPath, 0755, true);
    }

    // Original speichern
    $file->move($destinationPath, $fileName);

    // Thumbnail erzeugen und speichern
    Image::make("{$destinationPath}/{$fileName}")
        ->fit(104, 138, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })
        ->save("{$thumbPath}/{$fileName}");
}

    public function product_edit($id)
{
    $product = Product::find($id);
    $categories = Category::select('id', 'name')->orderBy('name')->get();
    $brands = Brand::select('id', 'name')->orderBy('name')->get();
    return view('admin.product-edit', compact('product', 'categories', 'brands'));
}

public function update_product(Request $request)
{
    $request->validate([
        'name' => 'required',
        'slug' => 'required|unique:products,slug,' . $request->id,
        'category_id' => 'required',
        'brand_id' => 'required',
        'short_description' => 'required',
        'description' => 'required',
        'regular_price' => 'required',
        'sale_price' => 'required',
        'SKU' => 'required',
        'stock_status' => 'required',
        'featured' => 'required',
        'quantity' => 'required',
        'image' => 'nullable|mimes:png,jpg,jpeg|max:2048'
    ]);

    $product = Product::find($request->id);
    $product->name = $request->name;
    $product->slug = Str::slug($request->name);
    $product->short_description = $request->short_description;
    $product->description = $request->description;
    $product->regular_price = $request->regular_price;
    $product->sale_price = $request->sale_price;
    $product->SKU = $request->SKU;
    $product->stock_status = $request->stock_status;
    $product->featured = $request->featured;
    $product->quantity = $request->quantity;

    $timestamp = Carbon::now()->timestamp;

    // Delete and update main image if uploaded
    if ($request->hasFile('image')) {
        if ($product->image && File::exists(public_path('uploads/products/' . $product->image))) {
            File::delete(public_path('uploads/products/' . $product->image));
        }

        $extension = $request->file('image')->extension();
        $filename = $timestamp . '.' . $extension;
        $request->file('image')->move(public_path('uploads/products'), $filename);
        $product->image = $filename;
    }

    // Delete old gallery images
    if ($product->images) {
        foreach (explode(',', $product->images) as $oldFile) {
            $oldFile = trim($oldFile);
            if (File::exists(public_path('uploads/products/' . $oldFile))) {
                File::delete(public_path('uploads/products/' . $oldFile));
            }
        }
    }

    // Store new gallery images
    $gallery_arr = [];
    if ($request->hasFile('images')) {
        $allowedExtensions = ['jpg', 'png', 'jpeg'];
        $files = $request->file('images');
        $counter = 1;

        foreach ($files as $file) {
            $ext = $file->getClientOriginalExtension();
            if (in_array($ext, $allowedExtensions)) {
                $gfilename = $timestamp . '-' . $counter . '.' . $ext;
                $file->move(public_path('uploads/products'), $gfilename);
                $gallery_arr[] = $gfilename;
                $counter++;
            }
        }
    }

    $product->images = implode(', ', $gallery_arr);
    $product->save();

    return redirect()->route('admin.products')->with('status', 'Record has been updated successfully!');
}

public function delete_product($id)
{
    $product = Product::find($id);        
    $product->delete();
    return redirect()->route('admin.products')->with('status','Record has been deleted successfully !');
} 

public function coupons()
{
        $coupons = Coupon::orderBy("expiry_date","DESC")->paginate(12);
        return view("admin.coupons",compact("coupons"));
}

public function add_coupon()
{        
    return view("admin.coupon-add");
}

public function store_coupon(Request $request)
{
    $request->validate([
        'code' => 'required',
        'type' => 'required',
        'value' => 'required|numeric',
        'cart_value' => 'required|numeric',
        'expiry_date' => 'required|date'
    ]);
    $coupon = new Coupon();
    $coupon->code = $request->code;
    $coupon->type = $request->type;
    $coupon->value = $request->value;
    $coupon->cart_value = $request->cart_value;
    $coupon->expiry_date = $request->expiry_date;
    $coupon->save();
    return redirect()->route("admin.coupons")->with('status','Record has been added successfully !');
}


public function edit_coupon($id)
{
       $coupon = Coupon::find($id);
       return view('admin.coupon-edit',compact('coupon'));
}

public function update_coupon(Request $request)
{
       $request->validate([
       'code' => 'required',
       'type' => 'required',
       'value' => 'required|numeric',
       'cart_value' => 'required|numeric',
       'expiry_date' => 'required|date'
       ]);
       $coupon = Coupon::find($request->id);
       $coupon->code = $request->code;
       $coupon->type = $request->type;
       $coupon->value = $request->value;
       $coupon->cart_value = $request->cart_value;
       $coupon->expiry_date = $request->expiry_date;               
       $coupon->save();           
       return redirect()->route('admin.coupons')->with('status','Record has been updated successfully !');
}

public function delete_coupon($id)
{
        $coupon = Coupon::find($id);        
        $coupon->delete();
        return redirect()->route('admin.coupons')->with('status','Record has been deleted successfully !');
}

public function orders()
{
        $orders = Order::orderBy('created_at','DESC')->paginate(12);
        return view("admin.orders",compact('orders'));
}

public function order_items($order_id){
    $order = Order::find($order_id);
      $orderitems = OrderItem::where('order_id',$order_id)->orderBy('id')->paginate(12);
      $transaction = Transaction::where('order_id',$order_id)->first();
      return view("admin.order-details",compact('order','orderitems','transaction'));
}


public function update_order_status(Request $request)
{
    // Bestellung anhand der ID laden
    $order = Order::findOrFail($request->order_id);

    // Neuen Status setzen
    $order->status = $request->order_status;

    // delivered_date / canceled_date je nach Status befüllen
    if ($request->order_status === 'delivered') {
        $order->delivered_date = Carbon::now();
        $order->canceled_date  = null;
    } elseif ($request->order_status === 'canceled') {
        $order->canceled_date  = Carbon::now();
        $order->delivered_date = null;
    } else {
        // ordered → beide Datumsspalten zurücksetzen
        $order->delivered_date = null;
        $order->canceled_date  = null;
    }

    $order->save();

    // Wenn geliefert, Transaktion auf "approved" setzen
    if ($request->order_status === 'delivered') {
        $transaction = Transaction::where('order_id', $order->id)->first();
        if ($transaction) {
            $transaction->status = 'approved';
            $transaction->save();
        }
    }

    return back()->with('status', 'Status changed successfully!');
}

public function contacts()
{
    $contacts = Contact::orderBy('created_at', 'DESC')->paginate(10);
    return view('admin.contacts', compact('contacts'));
}
public function contact_delete($id)
{
    $contact = Contact::find($id);
    $contact->delete();
    return redirect()->route('admin.contacts')->with('status', 'Record has been deleted successfully !');
}

public function slides()
{
    $slides = Slide::orderBy('id', 'DESC')->paginate(12);
    return view('admin.slides', compact('slides'));
}

public function slide_add()
{
    return view('admin.slide-add');
}

public function slide_store(Request $request)
{
    $request->validate([
        'tagline' => 'required',
        'title' => 'required',
        'subtitle' => 'required',
        'link' => 'required',
        'status' => 'required',
        'image' => 'required|mimes:png,jpg,jpeg|max:2048'
    ]);

    $slide = new Slide();
    $slide->tagline = $request->tagline;
    $slide->title = $request->title;
    $slide->subtitle = $request->subtitle;
    $slide->link = $request->link;
    $slide->status = $request->status;

    // Bildverarbeitung
    $image = $request->file('image');
    $file_ext = $image->extension();
    $file_name = Carbon::now()->timestamp . '.' . $file_ext;

    // Zielordner prüfen/erstellen
    $destination = public_path('uploads/slides');
    if (!file_exists($destination)) {
        mkdir($destination, 0755, true);
    }

    // Bild speichern/verarbeiten
    $this->GenerateSlideThumbnailsImage($image, $file_name);
    $slide->image = $file_name;

    // Speichern und redirect
    $slide->save();

    return redirect()->route('admin.slides')->with('status', 'Slide added successfully!');
}

public function GenerateSlideThumbnailsImage($image, $imageName)
{
    $destinationPath = public_path('uploads/slides');

    $img = Image::make($image->getRealPath()); // ✅ Richtig statt read()

    $img->fit(400, 690, function($constraint) {
        $constraint->upsize(); // optional: verhindert Vergrößerung
    }, 'top')->save($destinationPath . '/' . $imageName);
}

public function slide_edit($id)
{
    $slide = Slide::find($id);
    return view('admin.slide-edit', compact('slide'));
}

public function slide_update(Request $request)
{
    $request->validate([
        'tagline' => 'required',
        'title' => 'required',
        'subtitle' => 'required',
        'link' => 'required',
        'status' => 'required',
        'image' => 'mimes:png,jpg,jpeg|max:2048'
    ]);

    $slide = Slide::find($request->id);
    $slide->tagline = $request->tagline;
    $slide->title = $request->title;
    $slide->subtitle = $request->subtitle;
    $slide->link = $request->link;
    $slide->status = $request->status;

    if ($request->hasFile('image')) {
        if (File::exists(public_path('uploads/slides') . '/' . $slide->image)) {
            File::delete(public_path('uploads/slides') . '/' . $slide->image);
        }

        $image = $request->file('image');
        $file_extention = $image->extension();
        $file_name = Carbon::now()->timestamp . '.' . $file_extention;

        $this->GenerateSlideThumbnailsImage($image, $file_name);
        $slide->image = $file_name;
    }

    $slide->save();

    return redirect()->route('admin.slides')->with("status", "Slide has been updated successfully!");
}

public function slide_delete($id)
{
    $slide = Slide::find($id);
    if(File::exists(public_path('uploads/slides').'/'.$slide->image))
    {
        File::delete(public_path('uploads/slides').'/'.$slide->image);
    }
    $slide->delete();
    return redirect()->route('admin.slides')->with("status","Slide deleted successfully!");
} 
}