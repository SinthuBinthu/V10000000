{{-- resources/views/order-confirmation.blade.php --}}
@extends('layouts.app')

@section('content')
@php
    use App\Models\Order;
    // Hole die zuletzt angelegte Bestellung des eingeloggten Benutzers
    $order = Order::where('user_id', auth()->id())
                  ->latest()
                  ->first();
@endphp

<main class="pt-90">
  <div class="mb-4 pb-4"></div>
  <section class="shop-checkout container">
    <h2 class="page-title">Bestellung erhalten</h2>

    {{-- Checkout Steps --}}
    <div class="checkout-steps">
      <a href="{{ route('cart.index') }}" class="checkout-steps__item active">
        <span class="checkout-steps__item-number">01</span>
        <span class="checkout-steps__item-title">
          <span>Warenkorb</span>
          <em>Verwalte deine Artikelliste</em>
        </span>
      </a>
      <a href="{{ route('cart.checkout') }}" class="checkout-steps__item active">
        <span class="checkout-steps__item-number">02</span>
        <span class="checkout-steps__item-title">
          <span>Versand und Bezahlung</span>
          <em>Prüfe deine Artikelliste</em>
        </span>
      </a>
      <span class="checkout-steps__item active">
        <span class="checkout-steps__item-number">03</span>
        <span class="checkout-steps__item-title">
          <span>Bestätigung</span>
          <em>Überprüfung und Absenden deiner Bestellung</em>
        </span>
      </span>
    </div>

    {{-- Order Completed Message --}}
    <div class="order-complete">
      <div class="order-complete__message">
        <svg width="80" height="80" viewBox="0 0 80 80" fill="none"
             xmlns="http://www.w3.org/2000/svg">
          <circle cx="40" cy="40" r="40" fill="#B9A16B"/>
          <path d="M52.9743 35.7612C52.9743 35.3426 52.8069 34.9241 52.5056 34.6228L50.2288 32.346C49.9275 32.0446 49.5089 31.8772 49.0904 31.8772C48.6719 31.8772 48.2533 32.0446 47.952 32.346L36.9699 43.3449L32.048 38.4062C31.7467 38.1049 31.3281 37.9375 30.9096 37.9375C30.4911 37.9375 30.0725 38.1049 29.7712 38.4062L27.4944 40.683C27.1931 40.9844 27.0257 41.4029 27.0257 41.8214C27.0257 42.24 27.1931 42.6585 27.4944 42.9598L33.5547 49.0201L35.8315 51.2969C36.1328 51.5982 36.5513 51.7656 36.9699 51.7656C37.3884 51.7656 37.8069 51.5982 38.1083 51.2969L40.385 49.0201L52.5056 36.8996C52.8069 36.5982 52.9743 36.1797 52.9743 35.7612Z"
                fill="white"/>
        </svg>
        <h3>Deine Bestellung ist abgeschlossen!</h3>
        <p>Danke. Deine Bestellung wurde erhalten.</p>
      </div>

      {{-- Order Info --}}
      <div class="order-info">
        <div class="order-info__item">
          <span><strong>Bestellnummer:</strong> {{ $order->id }}</span>
        </div>
        <div class="order-info__item">
          <span><strong>Datum:</strong>
            {{ optional($order->created_at)->format('d/m/Y') ?? '–' }}</span>
        </div>
        <div class="order-info__item">
          <span><strong>Gesamt:</strong>
            ${{ number_format($order->total ?? 0, 2) }}</span>
        </div>
        <div class="order-info__item">
          <span><strong>Zahlungsmethode:</strong>
            {{ strtoupper($order->transaction?->mode ?? '–') }}</span>
        </div>
      </div>

      {{-- Order Details --}}
      <div class="checkout__totals-wrapper">
        <div class="checkout__totals">
          <h3>Bestelldetails</h3>
          <table class="checkout-cart-items">
            <thead>
              <tr>
                <th>PRODUKT</th>
                <th>ZWISCHENSUMME</th>
              </tr>
            </thead>
            <tbody>
              @forelse($order->orderItems as $item)
                <tr>
                  <td>{{ $item->product->name }} × {{ $item->quantity }}</td>
                  <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="2" class="text-center">Keine Artikel gefunden.</td>
                </tr>
              @endforelse
            </tbody>
          </table>

          <table class="checkout-totals">
            <tbody>
              <tr>
                <th>ZWISCHENSUMME</th>
                <td>${{ number_format($order->subtotal ?? 0, 2) }}</td>
              </tr>
              <tr>
                <th>VERSAND</th>
                <td>Kostenloser Versand</td>
              </tr>
              <tr>
                <th>MWST.</th>
                <td>${{ number_format($order->tax ?? 0, 2) }}</td>
              </tr>
              <tr>
                <th>GESAMT</th>
                <td>${{ number_format($order->total ?? 0, 2) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </section>
</main>
@endsection
