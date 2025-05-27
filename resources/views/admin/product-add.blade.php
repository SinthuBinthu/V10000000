@extends('layouts.admin')
@section('content')

<div class="main-content-inner">
    <!-- main-content-wrap -->
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Produkt hinzufügen</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li>
                    <a href="{{ route('admin.products') }}">
                        <div class="text-tiny">Produkte</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Produkt hinzufügen</div></li>
            </ul>
        </div>

        <!-- form-add-product -->
        <form class="tf-section-2 form-add-product"
              method="POST"
              action="{{ route('admin.product.store') }}"
              enctype="multipart/form-data">
            @csrf

            <div class="wg-box">
                <fieldset class="name">
                    <div class="body-title mb-10">
                        Produktname <span class="tf-color-1">*</span>
                    </div>
                    <input type="text"
                           name="name"
                           placeholder="Produktnamen eingeben"
                           class="mb-10"
                           value="{{ old('name') }}"
                           required>
                    <div class="text-tiny">
                        Bitte nicht mehr als 100 Zeichen eingeben.
                    </div>
                </fieldset>
                @error('name')
                    <span class="alert alert-danger text-center">{{ $message }}</span>
                @enderror

                <fieldset class="name">
                    <div class="body-title mb-10">
                        Slug <span class="tf-color-1">*</span>
                    </div>
                    <input type="text"
                           name="slug"
                           placeholder="Slug eingeben"
                           class="mb-10"
                           value="{{ old('slug') }}"
                           required>
                </fieldset>
                @error('slug')
                    <span class="alert alert-danger text-center">{{ $message }}</span>
                @enderror

                <div class="gap22 cols">
                    <fieldset class="category">
                        <div class="body-title mb-10">
                            Kategorie <span class="tf-color-1">*</span>
                        </div>
                        <div class="select mb-10">
                            <select name="category_id" required>
                                <option value="">Kategorie auswählen</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </fieldset>
                    @error('category_id')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <fieldset class="brand">
                        <div class="body-title mb-10">
                            Marke <span class="tf-color-1">*</span>
                        </div>
                        <div class="select mb-10">
                            <select name="brand_id" required>
                                <option value="">Marke auswählen</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}"
                                        {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </fieldset>
                    @error('brand_id')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                </div>

                <fieldset class="shortdescription">
                    <div class="body-title mb-10">
                        Kurzbeschreibung <span class="tf-color-1">*</span>
                    </div>
                    <textarea name="short_description"
                              class="mb-10 ht-150"
                              required>{{ old('short_description') }}</textarea>
                </fieldset>
                @error('short_description')
                    <span class="alert alert-danger text-center">{{ $message }}</span>
                @enderror

                <fieldset class="description">
                    <div class="body-title mb-10">
                        Beschreibung <span class="tf-color-1">*</span>
                    </div>
                    <textarea name="description"
                              class="mb-10"
                              required>{{ old('description') }}</textarea>
                </fieldset>
                @error('description')
                    <span class="alert alert-danger text-center">{{ $message }}</span>
                @enderror
            </div>

            <div class="wg-box">
                <fieldset>
                    <div class="body-title">
                        Hauptbild hochladen <span class="tf-color-1">*</span>
                    </div>
                    <div class="upload-image flex-grow">
                        <div class="item" id="imgpreview" style="display:none">
                            <img src="#" class="effect8" alt="Vorschau">
                        </div>
                        <div id="upload-file" class="item up-load">
                            <label class="uploadfile" for="myFile">
                                <span class="icon"><i class="icon-upload-cloud"></i></span>
                                <span class="body-text">
                                    Ziehen Sie Ihre Bilder hierher oder
                                    <span class="tf-color">klicken Sie zum Durchsuchen</span>
                                </span>
                                <input type="file"
                                       id="myFile"
                                       name="image"
                                       accept="image/*"
                                       required>
                            </label>
                        </div>
                    </div>
                </fieldset>
                @error('image')
                    <span class="alert alert-danger text-center">{{ $message }}</span>
                @enderror

                <fieldset>
                    <div class="body-title mb-10">Galeriebilder hochladen</div>
                    <div class="upload-image mb-16">
                        <div id="galUpload" class="item up-load">
                            <label class="uploadfile" for="gFile">
                                <span class="icon"><i class="icon-upload-cloud"></i></span>
                                <span class="text-tiny">
                                    Ziehen Sie Ihre Bilder hierher oder
                                    <span class="tf-color">klicken Sie zum Durchsuchen</span>
                                </span>
                                <input type="file"
                                       id="gFile"
                                       name="images[]"
                                       accept="image/*"
                                       multiple>
                            </label>
                        </div>
                    </div>
                </fieldset>
                @error('images')
                    <span class="alert alert-danger text-center">{{ $message }}</span>
                @enderror

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">
                            Regulärer Preis <span class="tf-color-1">*</span>
                        </div>
                        <input type="text"
                               name="regular_price"
                               placeholder="Regulären Preis eingeben"
                               class="mb-10"
                               value="{{ old('regular_price') }}"
                               required>
                    </fieldset>
                    @error('regular_price')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <fieldset class="name">
                        <div class="body-title mb-10">
                            Verkaufspreis <span class="tf-color-1">*</span>
                        </div>
                        <input type="text"
                               name="sale_price"
                               placeholder="Verkaufspreis eingeben"
                               class="mb-10"
                               value="{{ old('sale_price') }}"
                               required>
                    </fieldset>
                    @error('sale_price')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                </div>

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">
                            SKU <span class="tf-color-1">*</span>
                        </div>
                        <input type="text"
                               name="SKU"
                               placeholder="SKU eingeben"
                               class="mb-10"
                               value="{{ old('SKU') }}"
                               required>
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title mb-10">
                            Menge <span class="tf-color-1">*</span>
                        </div>
                        <input type="text"
                               name="quantity"
                               placeholder="Menge eingeben"
                               class="mb-10"
                               value="{{ old('quantity') }}"
                               required>
                    </fieldset>
                    @error('quantity')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                </div>

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">
                            Lagerstatus <span class="tf-color-1">*</span>
                        </div>
                        <div class="select mb-10">
                            <select name="stock_status" required>
                                <option value="instock" {{ old('stock_status')=='instock'? 'selected':'' }}>
                                    Auf Lager
                                </option>
                                <option value="outofstock" {{ old('stock_status')=='outofstock'? 'selected':'' }}>
                                    Nicht auf Lager
                                </option>
                            </select>
                        </div>
                    </fieldset>
                    @error('stock_status')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <fieldset class="name">
                        <div class="body-title mb-10">
                            Empfohlen <span class="tf-color-1">*</span>
                        </div>
                        <div class="select mb-10">
                            <select name="featured" required>
                                <option value="0" {{ old('featured')=='0'? 'selected':'' }}>Nein</option>
                                <option value="1" {{ old('featured')=='1'? 'selected':'' }}>Ja</option>
                            </select>
                        </div>
                    </fieldset>
                    @error('featured')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                </div>

                <div class="cols gap10">
                    <button type="submit" class="tf-button w-full">Produkt hinzufügen</button>
                </div>
            </div>
        </form>
        <!-- /form-add-product -->
    </div>
    <!-- /main-content-wrap -->
</div>

@endsection

@push('scripts')
<script>
    $(function() {
        // Vorschau Hauptbild
        $('#myFile').on('change', function() {
            const [file] = this.files;
            if (file) {
                $('#imgpreview img').attr('src', URL.createObjectURL(file));
                $('#imgpreview').show();
            }
        });

        // Vorschau Galerie
        $('#gFile').on('change', function() {
            $('.gitems').remove();
            const files = this.files;
            $.each(files, function(key, val) {
                $('#galUpload').prepend(
                    `<div class="item gitems"><img src="${URL.createObjectURL(val)}" alt=""></div>`
                );
            });
        });

        // Slug-Generierung
        $("input[name='name']").on('input', function() {
            $("input[name='slug']").val(StringToSlug(this.value));
        });

        function StringToSlug(Text) {
            return Text.toLowerCase()
                .replace(/[^\w ]+/g, '')
                .replace(/ +/g, '-');
        }
    });
</script>
@endpush
