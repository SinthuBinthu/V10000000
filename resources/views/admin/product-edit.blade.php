@extends('layouts.admin')
@section('content')
    <!-- main-content-wrap -->
    <div class="main-content-inner">
        <!-- main-content-wrap -->
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Produkt bearbeiten</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="index.html"><div class="text-tiny">Dashboard</div></a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <a href="all-product.html"><div class="text-tiny">Produkte</div></a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Produkt bearbeiten</div>
                    </li>
                </ul>
            </div>
            <!-- form-add-product -->
            <form class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data" action="{{route('admin.product.update')}}" >
                <input type="hidden" name="id" value="{{$product->id}}" />
                @csrf
                @method("PUT")
                <div class="wg-box">
                    <fieldset class="name">
                        <div class="body-title mb-10">Produktname <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Produktnamen eingeben" name="name" tabindex="0" value="{{ old('name', $product->name) }}" aria-required="true" required="">
                        <div class="text-tiny">Bitte nicht mehr als 100 Zeichen eingeben.</div>
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title mb-10">Slug <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Slug eingeben" name="slug" tabindex="0" value="{{ old('slug', $product->slug) }}" aria-required="true" required="">
                        <div class="text-tiny">Bitte nicht mehr als 100 Zeichen eingeben.</div>
                    </fieldset>
                    <div class="gap22 cols">
                        <fieldset class="category">
                            <div class="body-title mb-10">Kategorie <span class="tf-color-1">*</span></div>
                            <div class="select">
                                <select class="" name="category_id">
                                    <option value="">Kategorie auswählen</option>
                                    @foreach ($categories as $category)
                                    <option value="{{$category->id}}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{$category->name}}</option>
                                    @endforeach                                                                 
                                </select>
                            </div>
                        </fieldset>
                        <fieldset class="brand">
                            <div class="body-title mb-10">Marke <span class="tf-color-1">*</span></div>
                            <div class="select">
                                <select class="" name="brand_id">
                                    <option value="">Marke auswählen</option>
                                    @foreach ($brands as $brand)
                                    <option value="{{$brand->id}}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{$brand->name}}</option>
                                    @endforeach                                      
                                </select>
                            </div>
                        </fieldset>
                    </div>
                    <fieldset class="shortdescription">
                        <div class="body-title mb-10">Kurzbeschreibung <span class="tf-color-1">*</span></div>
                        <textarea class="mb-10 ht-150" name="short_description" placeholder="Kurzbeschreibung" tabindex="0" aria-required="true" required="">{{ old('short_description', $product->short_description) }}</textarea>
                        <div class="text-tiny">Bitte nicht mehr als 100 Zeichen eingeben.</div>
                    </fieldset>
                    
                    <fieldset class="description">
                        <div class="body-title mb-10">Beschreibung <span class="tf-color-1">*</span></div>
                        <textarea class="mb-10" name="description" placeholder="Beschreibung" tabindex="0" aria-required="true" required="">{{ old('description', $product->description) }}</textarea>
                        <div class="text-tiny">Bitte nicht mehr als 100 Zeichen eingeben.</div>
                    </fieldset>
                </div>
                <div class="wg-box">
                    <fieldset>
                        <div class="body-title">Bilder hochladen <span class="tf-color-1">*</span></div>
                        <div class="upload-image flex-grow">
                            @if($product->image)
                            <div class="item" id="imgpreview">                            
                                <img src="{{asset('uploads/products')}}/{{$product->image}}" class="effect8" alt="">
                            </div>
                            @endif
                            <div id="upload-file" class="item up-load">
                                <label class="uploadfile" for="myFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="body-text">Ziehen Sie Ihre Bilder hierher oder <span class="tf-color">klicken Sie zum Durchsuchen</span></span>
                                    <input type="file" id="myFile" name="image" accept="image/*">
                                </label>
                            </div>
                        </div>
                    </fieldset> 
                    <fieldset>
                        <div class="body-title mb-10">Galeriebilder hochladen</div>
                        <div class="upload-image mb-16">                           
                            @if($product->images)
                                @foreach(explode(",",$product->images) as $img)                               
                                    <div class="item gitems">                            
                                        <img src="{{asset('uploads/products')}}/{{trim($img)}}" class="effect8" alt="">
                                    </div>
                                @endforeach
                            @endif
                            <div  id ="galUpload" class="item up-load">
                                <label class="uploadfile" for="gFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="text-tiny">Ziehen Sie Ihre Bilder hierher oder <span class="tf-color">klicken Sie zum Durchsuchen</span></span>
                                    <input type="file" id="gFile" name="images[]" accept="image/*" multiple>
                                </label>
                            </div>
                        </div>                        
                    </fieldset>
                    <div class="cols gap22">
                        <fieldset class="name">                                                
                            <div class="body-title mb-10">Regulärer Preis <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Regulären Preis eingeben" name="regular_price" tabindex="0" value="{{ old('regular_price', $product->regular_price) }}" aria-required="true" required="">                                              
                        </fieldset>
                        <fieldset class="name">
                            <div class="body-title mb-10">Verkaufspreis <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Verkaufspreis eingeben" name="sale_price" tabindex="0" value="{{ old('sale_price', $product->sale_price) }}" aria-required="true" required="">                                              
                        </fieldset>
                    </div>
                    <div class="cols gap22">
                        <fieldset class="name">                                                
                            <div class="body-title mb-10">SKU <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="SKU eingeben" name="SKU" tabindex="0" value="{{ old('SKU', $product->SKU) }}" aria-required="true" required="">                                              
                        </fieldset>
                        <fieldset class="name">
                            <div class="body-title mb-10">Menge <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Menge eingeben" name="quantity" tabindex="0" value="{{ old('quantity', $product->quantity) }}" aria-required="true" required="">                                              
                        </fieldset>
                    </div>
                    <div class="cols gap22">
                        <fieldset class="name">
                            <div class="body-title mb-10">Lagerstatus</div>
                            <div class="select mb-10">
                                <select class="" name="stock_status">
                                    <option value="instock" {{ old('stock_status', $product->stock_status) == 'instock' ? 'selected' : '' }}>Auf Lager</option>
                                    <option value="outofstock"  {{ old('stock_status', $product->stock_status) == 'outofstock' ? 'selected' : '' }}>Nicht auf Lager</option>                                                        
                                </select>
                            </div>                                                
                        </fieldset>
                        <fieldset class="name">
                            <div class="body-title mb-10">Empfohlen</div>
                            <div class="select mb-10">
                                <select class="" name="featured">
                                    <option value="0" {{ old('featured', $product->featured) == '0' ? 'selected' : '' }}>Nein</option>
                                    <option value="1" {{ old('featured', $product->featured) == '1' ? 'selected' : '' }}>Ja</option>                                                        
                                </select>
                            </div>
                        </fieldset>
                    </div>
                    <div class="cols gap10">
                        <button class="tf-button w-full" type="submit">Produkt speichern</button>                                            
                    </div>
                </div>
            </form>
            <!-- /form-add-product -->
        </div>
        <!-- /main-content-wrap -->
    </div>
    <!-- /main-content-wrap -->
@endsection

@push("scripts")
    <script>
            $(function(){
                $("#myFile").on("change",function(e){
                    const photoInp = $("#myFile");                    
                    const [file] = this.files;
                    if (file) {
                        $("#imgpreview img").attr('src',URL.createObjectURL(file));
                        $("#imgpreview").show();                        
                    }
                });
                $("#gFile").on("change",function(e){
                    $(".gitems").remove();
                    const gFile = $("gFile");
                    const gphotos = this.files;                    
                    $.each(gphotos,function(key,val){                        
                        $("#galUpload").prepend(`<div class="item gitems"><img src="${URL.createObjectURL(val)}" alt=""></div>`);                        
                    });                    
                });
                $("input[name='name']").on("change",function(){
                    $("input[name='slug']").val(StringToSlug($(this).val()));
                });
                
            });
            function StringToSlug(Text) {
                return Text.toLowerCase()
                .replace(/[^\w ]+/g, "")
                .replace(/ +/g, "-");
            }      
    </script>
@endpush
