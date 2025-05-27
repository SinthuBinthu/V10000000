<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

class ShopController extends Controller
{
    /**
     * Shop-Übersicht mit Pagination, Sorting und Filtern
     */
    public function index(Request $request)
{
    // 1) Page-Size (default 12)
    $size = $request->query('size', 12);

    // 2) Sorting (default 'default')
    $sorting = $request->query('sorting', 'default');

    // 3) Price-Filter (default 1–10000)
    $min_price = $request->query('min', 1);
    $max_price = $request->query('max', 10000);

    // 4) Brand- & Category-Filter (kommaseparierte IDs oder null)
    $f_brands     = $request->query('brands');
    $f_categories = $request->query('categories');

    // 5) Basis-Query mit optionalen Filtern
    $productsQuery = Product::query()
        ->whereBetween('regular_price', [$min_price, $max_price])
        ->when($f_brands, function($q) use ($f_brands) {
            $ids = explode(',', $f_brands);
            $q->whereIn('brand_id', $ids);
        })
        ->when($f_categories, function($q) use ($f_categories) {
            $ids = explode(',', $f_categories);
            $q->whereIn('category_id', $ids);
        });

    // 6) Sorting draufsetzen
    if ($sorting === 'date') {
        $productsQuery->orderBy('created_at', 'DESC');
    } elseif ($sorting === 'price') {
        $productsQuery->orderBy('regular_price', 'ASC');
    } elseif ($sorting === 'price-desc') {
        $productsQuery->orderBy('regular_price', 'DESC');
    }
    // bei 'default' bleibt die DB-Standardreihenfolge

    // 7) Pagination ausführen und alle Query-Parameter an die Links anhängen
    $products = $productsQuery
        ->paginate($size)
        ->appends($request->only(['size','sorting','min','max','brands','categories']));

    // 8) Daten für die Sidebar
    $categories = Category::orderBy('name','ASC')->get();
    $brands     = Brand::orderBy('name','ASC')->get();

    // 9) View rendern
    return view('shop', compact(
        'products',
        'size',
        'sorting',
        'min_price',
        'max_price',
        'f_brands',
        'f_categories',
        'categories',
        'brands'
    ));
}


    /**
     * Detailseite für ein Produkt
     */
    public function product_details($product_slug)
    {
        $product   = Product::where('slug', $product_slug)->firstOrFail();
        $rproducts = Product::where('slug', '<>', $product_slug)
                            ->latest()
                            ->take(8)
                            ->get();

        return view('details', compact('product', 'rproducts'));
    }
}
