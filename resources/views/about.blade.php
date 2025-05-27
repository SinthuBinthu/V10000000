@extends('layouts.app')

@section('content')

<main class="pt-90">
    <div class="mb-4 pb-4"></div>

    <section class="contact-us container">
        <div class="mw-930 text-center mb-5">
            <h2 class="page-title">Über uns</h2>
            <p class="fs-6">
                Dieser Shop wurde von einer Einzelperson gegründet – nicht aus einer Garage, aber aus einer Idee, die viele für verrückt hielten.
                Gegen alle Zweifel entstand mit viel Eigeninitiative und Ausdauer dieses Projekt. Heute zeigt es: Man muss nicht perfekt starten, sondern einfach anfangen.
            </p>
        </div>

        <!-- Newsletter Bereich -->
        <section class="newsletter my-5">
            <div class="container text-center">
                <h3 class="mb-3">Newsletter abonnieren</h3>
                <form id="newsletter-form">
                    <input
                        type="email"
                        name="user_email"
                        placeholder="Deine E-Mail-Adresse"
                        required
                        class="form-control w-50 mx-auto mb-3"
                    />
                    <button type="submit" class="btn btn-primary">Jetzt abonnieren und meine Story Sehen</button>
                </form>
                <div id="newsletter-status" class="mt-2"></div>
            </div>
        </section>
    </section>
</main>

<!-- EmailJS CDN -->
@push('scripts')
<script src="https://cdn.emailjs.com/dist/email.min.js"></script>
<script>
    (function () {
        emailjs.init("8OgqSX8IjgxaO9ZZo"); // ✅ DEIN Public Key
    })();

    document.getElementById("newsletter-form").addEventListener("submit", function (e) {
        e.preventDefault();

        const form = this;

        emailjs.sendForm("service_g0a82p1", "template_nwzmy5e", form)
            .then(function () {
                document.getElementById("newsletter-status").innerHTML =
                    "<span class='text-success'>Danke fürs Abonnieren! Du hast soeben eine Bestätigung per E-Mail erhalten.</span>";
                form.reset();
            })
            .catch(function (error) {
                document.getElementById("newsletter-status").innerHTML =
                    "<span class='text-danger'>Fehler beim Senden. Bitte versuch es später erneut.</span>";
                console.error("EmailJS-Fehler:", error);
                alert("Fehlerdetails: " + JSON.stringify(error)); // zum Debuggen
            });
    });
</script>
@endpush

@endsection





