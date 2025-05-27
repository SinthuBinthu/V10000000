@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Folie</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li>
                    <a href="{{ route('admin.slide.add') }}">
                        <div class="text-tiny">Folien</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Neue Folie</div></li>
            </ul>
        </div>

        <div class="wg-box">
            <form class="form-new-product form-style-1" action="{{ route('admin.slide.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <fieldset class="name">
                    <div class="body-title">Slogan <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Slogan" name="tagline" value="{{ old('tagline') }}" required>
                </fieldset>

                <fieldset class="name">
                    <div class="body-title">Titel <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Titel" name="title" value="{{ old('title') }}" required>
                </fieldset>

                <fieldset class="name">
                    <div class="body-title">Untertitel <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Untertitel" name="subtitle" value="{{ old('subtitle') }}" required>
                </fieldset>

                <fieldset class="name">
                    <div class="body-title">Link <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Link" name="link" value="{{ old('link') }}" required>
                </fieldset>

                <fieldset>
                    <div class="body-title">Bilder hochladen <span class="tf-color-1">*</span></div>
                    <div class="upload-image flex-grow">
                        <div class="image-preview" id="imgpreview" style="display: none;">
                            <img src="" alt="Vorschau" class="img-preview">
                        </div>
                        <div class="item up-load">
                            <label class="uploadfile" for="myFile">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="body-text">Ziehen Sie Ihre Bilder hierher oder <span class="tf-color">klicken Sie zum Durchsuchen</span></span>
                                <input type="file" id="myFile" name="image" required>
                            </label>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="category">
                    <div class="body-title">Status <span class="tf-color-1">*</span></div>
                    <div class="select flex-grow">
                        <select name="status" required>
                            <option value="">Auswählen</option>
                            <option value="1" {{ old('status') == "1" ? 'selected' : '' }}>Aktiv</option>
                            <option value="0" {{ old('status') == "0" ? 'selected' : '' }}>Inaktiv</option>
                        </select>
                    </div>
                </fieldset>

                <div class="bot">
                    <div></div>
                    <button class="tf-button w208" type="submit">Speichern</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function(){
        $("#myFile").on("change", function(){
            const [file] = this.files;
            if(file) {
                $("#imgpreview img").attr('src', URL.createObjectURL(file));
                $("#imgpreview").show();
            }
        });
    });
</script>
@endpush
