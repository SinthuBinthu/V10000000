@extends('layouts.app')
@section('content')

<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container">
      <h2 class="page-title">Mein Konto</h2>
      <div class="row">
        <div class="col-lg-3">
            @include('user.account-nav')
        </div>
        <div class="col-lg-9">
          <div class="page-content my-account__dashboard">
            <p>Hallo <strong>Benutzer</strong></p>
            <p>In Ihrem Kontobereich können Sie Ihre <a class="unerline-link" href="account_orders.html">
              letzten Bestellungen</a>, Ihre <a class="unerline-link" href="account_edit_address.html">
                Lieferadressen verwalten</a> und Ihr <a class="unerline-link" href="account_edit.html">
                  Passwort sowie Ihre Kontodaten bearbeiten</a>.</p>
          </div>
        </div>
      </div>
    </section>
  </main>
@endsection
