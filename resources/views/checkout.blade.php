{{-- resources/views/checkout.blade.php --}}
@extends('layouts.app')

@section('content')
<style>
    .cart-total th,
    .cart-total td {
        color: green;
        font-weight: bold;
        font-size: 21px !important;
    }
</style>

<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="shop-checkout container">
        <h2 class="page-title">Versand und Bezahlung</h2>

        {{-- Checkout Steps --}}
        <div class="checkout-steps">
            <a href="{{ route('cart.index') }}" class="checkout-steps__item active">
                <span class="checkout-steps__item-number">01</span>
                <span class="checkout-steps__item-title">
                    <span>Einkaufstasche</span>
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
            <a href="{{ route('cart.confirmation') }}"
               class="checkout-steps__item">
                <span class="checkout-steps__item-number">03</span>
                <span class="checkout-steps__item-title">
                    <span>Bestätigung</span>
                    <em>Bestellbestätigung</em>
                </span>
            </a>
        </div>

        {{-- Place Order Form --}}
        <form name="checkout-form"
              action="{{ route('cart.place.order') }}"
              method="POST">
            @csrf

            <div class="checkout-form">
                {{-- SHIPPING DETAILS --}}
                <div class="billing-info__wrapper">
                    <div class="row">
                        <div class="col-6">
                            <h4>VERSANDDETAILS</h4>
                        </div>
                        <div class="col-6">
                            @if($address)
                                <a href="javascript:void(0)"
                                   class="btn btn-info btn-sm float-right">Adresse ändern</a>
                                <a href="javascript:void(0)"
                                   class="btn btn-warning btn-sm float-right mr-3">Adresse bearbeiten</a>
                            @endif
                        </div>
                    </div>

                    @if($address)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="my-account__address-list">
                                    <div class="my-account__address-item">
                                        <div class="my-account__address-item__detail">
                                            <p>{{ $address->name }}</p>
                                            <p>{{ $address->address }}</p>
                                            <p>{{ $address->landmark }}</p>
                                            <p>
                                                {{ $address->city }},
                                                {{ $address->state }},
                                                {{ $address->country }}
                                            </p>
                                            <p>{{ $address->zip }}</p>
                                            <p>Telefon: {{ $address->phone }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row mt-5">
                            <div class="col-md-6">
                                <div class="form-floating my-3">
                                    <input type="text"
                                           class="form-control"
                                           name="name"
                                           value="{{ old('name') }}">
                                    <label for="name">Vollständiger Name *</label>
                                    <span class="text-danger">
                                        @error('name'){{ $message }}@enderror
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating my-3">
                                    <input type="text"
                                           class="form-control"
                                           name="phone"
                                           value="{{ old('phone') }}">
                                    <label for="phone">Telefonnummer *</label>
                                    <span class="text-danger">
                                        @error('phone'){{ $message }}@enderror
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating my-3">
                                    <input type="text"
                                           class="form-control"
                                           name="zip"
                                           value="{{ old('zip') }}">
                                    <label for="zip">Postleitzahl *</label>
                                    <span class="text-danger">
                                        @error('zip'){{ $message }}@enderror
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating my-3">
                                    <input type="text"
                                           class="form-control"
                                           name="state"
                                           value="{{ old('state') }}">
                                    <label for="state">Bundesland *</label>
                                    <span class="text-danger">
                                        @error('state'){{ $message }}@enderror
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating my-3">
                                    <input type="text"
                                           class="form-control"
                                           name="city"
                                           value="{{ old('city') }}">
                                    <label for="city">Stadt / Ort *</label>
                                    <span class="text-danger">
                                        @error('city'){{ $message }}@enderror
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating my-3">
                                    <input type="text"
                                           class="form-control"
                                           name="address"
                                           value="{{ old('address') }}">
                                    <label for="address">Hausnummer, Gebäudename *</label>
                                    <span class="text-danger">
                                        @error('address'){{ $message }}@enderror
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating my-3">
                                    <input type="text"
                                           class="form-control"
                                           name="locality"
                                           value="{{ old('locality') }}">
                                    <label for="locality">Straße, Gebiet, Kolonie *</label>
                                    <span class="text-danger">
                                        @error('locality'){{ $message }}@enderror
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating my-3">
                                    <input type="text"
                                           class="form-control"
                                           name="landmark"
                                           value="{{ old('landmark') }}">
                                    <label for="landmark">Orientierungspunkt *</label>
                                    <span class="text-danger">
                                        @error('landmark'){{ $message }}@enderror
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- ORDER SUMMARY --}}
                <div class="checkout__totals-wrapper">
                    <div class="sticky-content">
                        <div class="checkout__totals">
                            <h3>Ihre Bestellung</h3>

                            {{-- Cart Items --}}
                            <table class="checkout-cart-items">
                                <thead>
                                    <tr>
                                        <th>PRODUKT</th>
                                        <th class="text-right">ZWISCHENSUMME</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(Cart::instance('cart')->content() as $item)
                                        <tr>
                                            <td>{{ $item->name }} x {{ $item->qty }}</td>
                                            <td class="text-right">
                                                ${{ $item->subtotal(2, '.', '') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {{-- Totals with/without Discount --}}
                            @if(session()->has('discounts'))
                                <table class="checkout-totals">
                                    <tbody>
                                        <tr>
                                            <th>Zwischensumme</th>
                                            <td class="text-right">
                                                ${{ Cart::instance('cart')->subtotal(2, '.', '') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Rabatt ({{ session('coupon.code') }})</th>
                                            <td class="text-right">
                                                -${{ session('discounts.discount') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Zwischensumme nach Rabatt</th>
                                            <td class="text-right">
                                                ${{ session('discounts.subtotal') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Versand</th>
                                            <td class="text-right">Kostenlos</td>
                                        </tr>
                                        <tr>
                                            <th>MwSt.</th>
                                            <td class="text-right">
                                                ${{ session('discounts.tax') }}
                                            </td>
                                        </tr>
                                        <tr class="cart-total">
                                            <th>Gesamt</th>
                                            <td class="text-right">
                                                ${{ session('discounts.total') }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            @else
                                <table class="checkout-totals">
                                    <tbody>
                                        <tr>
                                            <th>Zwischensumme</th>
                                            <td class="text-right">
                                                ${{ Cart::instance('cart')->subtotal(2, '.', '') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Versand</th>
                                            <td class="text-right">Kostenlos</td>
                                        </tr>
                                        <tr>
                                            <th>MwSt.</th>
                                            <td class="text-right">
                                                ${{ Cart::instance('cart')->tax(2, '.', '') }}
                                            </td>
                                        </tr>
                                        <tr class="cart-total">
                                            <th>Gesamt</th>
                                            <td class="text-right">
                                                ${{ Cart::instance('cart')->total(2, '.', '') }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            @endif
                        </div>

                        {{-- PAYMENT METHODS --}}
                        <div class="checkout__payment-methods">
                            <div class="form-check">
                                <input class="form-check-input"
                                       type="radio"
                                       name="mode"
                                       value="card">
                                <label class="form-check-label">
                                    Debit- oder Kreditkarte
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input"
                                       type="radio"
                                       name="mode"
                                       value="paypal">
                                <label class="form-check-label">Paypal</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input"
                                       type="radio"
                                       name="mode"
                                       value="cod"
                                       checked>
                                <label class="form-check-label">
                                    Nachnahme
                                </label>
                            </div>
                            <div class="policy-text">
                                Ihre persönlichen Daten werden verwendet, um Ihre Bestellung zu verarbeiten,
                                Ihre Erfahrung auf dieser Website zu unterstützen und für andere in unserer
                                <a href="terms.html" target="_blank">
                                    Datenschutzerklärung
                                </a> beschriebenen Zwecke.
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            BESTELLEN
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </section>
</main>
@endsection
