@extends('layouts.app')
@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>

    <section class="contact-us container">
        <div class="mw-930">
            <h2 class="page-title">KONTAKT</h2>
        </div>
    </section>

    <hr class="mt-2 text-secondary" />
    <div class="mb-4 pb-4"></div>

    <section class="contact-us container">
        <div class="mw-930">
            <div class="contact-us__form">
                @if (Session::has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ Session::get('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- FORMULAR --}}
                <form id="contact-form" name="contact-us-form" class="needs-validation" novalidate="" action="{{ route('home.contact.store') }}" method="POST">
                    @csrf

                    <h3 class="mb-5">Schreiben Sie uns</h3>

                    <div class="form-floating my-4">
                        <input type="text" class="form-control" name="name" placeholder="Name *" required value="{{ old('name') }}">
                        <label for="contact_us_name">Name *</label>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-floating my-4">
                        <input type="text" class="form-control" name="phone" placeholder="Telefon *" required value="{{ old('phone') }}">
                        <label for="contact_us_phone">Telefon *</label>
                        @error('phone')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-floating my-4">
                        <input type="email" class="form-control" name="email" placeholder="E-Mail-Adresse *" required value="{{ old('email') }}">
                        <label for="contact_us_email">E-Mail-Adresse *</label>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="my-4">
                        <textarea class="form-control form-control_gray" name="comment" placeholder="Ihre Nachricht" cols="30" rows="8" required>{{ old('comment') }}</textarea>
                        @error('comment')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="my-4">
                        <button type="submit" class="btn btn-primary">Absenden</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>
@endsection

@push('scripts')
<!-- EmailJS SDK -->
<script src="https://cdn.emailjs.com/sdk/3.11.0/email.min.js"></script>
<script>
    (function() {
        emailjs.init("DAtnkJZOkJcez2K5rD"); // ⚠️ Ersetze mit deinem echten Public Key von EmailJS

        document.getElementById("contact-form").addEventListener("submit", function(e) {
            const form = this;
            const name = form.name.value;
            const email = form.email.value;

            // EmailJS: automatische Antwort senden
            emailjs.send("service_ag0f5uh", "template_j63bezl", {
                to_name: name,
                to_email: email
            }).then(function(response) {
                console.log("✅ EmailJS-Antwort gesendet an", email);
            }, function(error) {
                console.error("❌ EmailJS-Fehler:", error);
            });

            // danach wird das Formular ganz normal an Laravel geschickt
        });
    })();
</script>
@endpush
