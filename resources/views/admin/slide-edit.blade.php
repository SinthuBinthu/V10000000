@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Folie bearbeiten</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li>
                    <a href="{{ route('admin.slides') }}">
                        <div class="text-tiny">Folien</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Folie bearbeiten</div></li>
            </ul>
        </div>

        <div class="wg-box">
            <form class="form-new-product form-style-1" action="{{ route('admin.slide.update', $slide->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $slide->id }}">

                <fieldset class="name">
                    <div class="body-title">Slogan <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" name="tagline" value="{{ old('tagline', $slide->tagline) }}" required>
                </fieldset>

                <fieldset class="name">
                    <div class="body-title">Titel <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" name="title" value="{{ old('title', $slide->title) }}" required>
                </fieldset>

                <fieldset class="name">
                    <div class="body-title">Untertitel <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" name="subtitle" value="{{ old('subtitle', $slide->subtitle) }}" required>
                </fieldset>

                <fieldset class="name">
                    <div class="body-title">Link <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" name="link" value="{{ old('link', $slide->link) }}" required>
                </fieldset>

                <fieldset>
                    <div class="body-title">Bilder hochladen</div>
                    <div class="upload-image flex-grow">
                        @if($slide->image)
                            <div class="image-preview" id="imgpreview" style="display: block;">
                                <img src="{{ asset('uploads/slides/' . $slide->image) }}" alt="Aktuelles Bild" class="img-preview" style="max-height: 150px;">
                            </div>
                        @else
                            <div class="image-preview" id="imgpreview" style="display: none;">
                                <img src="" alt="Vorschau" class="img-preview">
                            </div>
                        @endif
                        <div class="item up-load">
                            <label class="uploadfile" for="myFile">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="body-text">Neues Bild hochladen (optional)</span>
                                <input type="file" id="myFile" name="image">
                            </label>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="category">
                    <div class="body-title">Status <span class="tf-color-1">*</span></div>
                    <div class="select flex-grow">
                        <select name="status" required>
                            <option value="">Auswählen</option>
                            <option value="1" {{ old('status', $slide->status) == "1" ? 'selected' : '' }}>Aktiv</option>
                            <option value="0" {{ old('status', $slide->status) == "0" ? 'selected' : '' }}>Inaktiv</option>
                        </select>
                    </div>
                </fieldset>

                <div class="bot">
                    <div></div>
                    <button class="tf-button w208" type="submit">Aktualisieren</button>
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
