@extends('layouts.app')

@section('content')
<style>
    .table-transaction > tbody > tr:nth-of-type(odd) {
        --bs-table-accent-bg: #fff !important;
    }
    .table-transaction th,
    .table-transaction td {
        padding: 0.625rem 1.5rem .25rem !important;
        color: #000 !important;
    }
    .table > :not(caption) > tr > th {
        padding: 0.625rem 1.5rem .25rem !important;
        background-color: #6a6e51 !important;
    }
    .table-bordered > :not(caption) > * > * {
        border-width: inherit;
        line-height: 32px;
        font-size: 14px;
        border: 1px solid #e1e1e1;
        vertical-align: middle;
    }
    .table-striped .image {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 50px;
        height: 50px;
        flex-shrink: 0;
        border-radius: 10px;
        overflow: hidden;
    }
    .table-striped td:nth-child(1) {
        min-width: 250px;
        padding-bottom: 7px;
    }
    .pname {
        display: flex;
        gap: 13px;
    }
    .table-bordered > :not(caption) > tr > th,
    .table-bordered > :not(caption) > tr > td {
        border-width: 1px 1px;
        border-color: #6a6e51;
    }
</style>

<main class="pt-90">
    <section class="my-account container">
        <h2 class="page-title">Bestelldetails</h2>
        <div class="row">
            <div class="col-lg-2">
                @include('user.account-nav')
            </div>
            <div class="col-lg-10">

                {{-- Session-Status --}}
                @if(session('status'))
                    <p class="alert alert-success">{{ session('status') }}</p>
                @endif

                {{-- Ordered Details --}}
                <div class="wg-box mt-5 mb-5">
                    <div class="row">
                        <div class="col-6">
                            <h5>Bestelldetails</h5>
                        </div>
                        <div class="col-6 text-end">
                            <a class="btn btn-sm btn-danger" href="{{ route('user.account.orders') }}">Zurück</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-transaction">
                            <tr>
                                <th>Bestell-Nr.</th>
                                <td>{{ '1' . str_pad($transaction->order->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <th>Telefon</th>
                                <td>{{ $transaction->order->phone }}</td>
                                <th>Postleitzahl</th>
                                <td>{{ $transaction->order->zip }}</td>
                            </tr>
                            <tr>
                                <th>Bestelldatum</th>
                                <td>{{ $transaction->order->created_at }}</td>
                                <th>Zustellungsdatum</th>
                                <td>{{ $transaction->order->delivered_date }}</td>
                                <th>Stornierungsdatum</th>
                                <td>{{ $transaction->order->canceled_date }}</td>
                            </tr>
                            <tr>
                                <th>Bestellstatus</th>
                                <td colspan="5">
                                    @php $st = $transaction->order->status; @endphp
                                    @if($st == 'delivered')
                                        <span class="badge bg-success">Zugestellt</span>
                                    @elseif($st == 'canceled')
                                        <span class="badge bg-danger">Storniert</span>
                                    @else
                                        <span class="badge bg-warning">Bestellt</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Ordered Items --}}
                <div class="wg-box wg-table table-all-user">
                    <div class="row">
                        <div class="col-6">
                            <h5>Bestellte Artikel</h5>
                        </div>
                        <div class="col-6 text-end">
                            {{-- optional Toolbar --}}
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th class="text-center">Preis</th>
                                    <th class="text-center">Menge</th>
                                    <th class="text-center">SKU</th>
                                    <th class="text-center">Kategorie</th>
                                    <th class="text-center">Marke</th>
                                    <th class="text-center">Optionen</th>
                                    <th class="text-center">Rückgabe-Status</th>
                                    <th class="text-center">Aktion</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orderItems as $orderitem)
                                    <tr>
                                        <td class="pname">
                                            <div class="image">
                                                <img src="{{ asset('uploads/products/thumbnails/' . $orderitem->product->image) }}" alt="">
                                            </div>
                                            <div class="name">
                                                <a href="{{ route('shop.product.details', ['product_slug' => $orderitem->product->slug]) }}" target="_blank" class="body-title-2">
                                                    {{ $orderitem->product->name }}
                                                </a>
                                            </div>
                                        </td>
                                        <td class="text-center">${{ $orderitem->price }}</td>
                                        <td class="text-center">{{ $orderitem->quantity }}</td>
                                        <td class="text-center">{{ $orderitem->product->SKU }}</td>
                                        <td class="text-center">{{ $orderitem->product->category->name }}</td>
                                        <td class="text-center">{{ $orderitem->product->brand->name }}</td>
                                        <td class="text-center">{{ $orderitem->options }}</td>
                                        <td class="text-center">{{ $orderitem->rstatus == 0 ? 'Nein' : 'Ja' }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('shop.product.details', ['product_slug' => $orderitem->product->slug]) }}" target="_blank">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination mt-3">
                            {{ $orderItems->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>

                <div class="divider"></div>

                {{-- Shipping Address --}}
                <div class="wg-box mt-5">
                    <h5>Lieferadresse</h5>
                    <div class="my-account__address-item col-md-6">
                        <div class="my-account__address-item__detail">
                            <p>{{ $transaction->order->name }}</p>
                            <p>{{ $transaction->order->address }}</p>
                            <p>{{ $transaction->order->locality }}</p>
                            <p>{{ $transaction->order->city }}, {{ $transaction->order->country }}</p>
                            <p>{{ $transaction->order->landmark }}</p>
                            <p>{{ $transaction->order->zip }}</p>
                            <br />
                            <p>Telefon: {{ $transaction->order->phone }}</p>
                        </div>
                    </div>
                </div>

                {{-- Transactions --}}
                <div class="wg-box mt-5">
                    <h5>Transaktionen</h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-transaction">
                            <tr>
                                <th>Zwischensumme</th>
                                <td>${{ $transaction->order->subtotal }}</td>
                                <th>Steuer</th>
                                <td>${{ $transaction->order->tax }}</td>
                                <th>Rabatt</th>
                                <td>${{ $transaction->order->discount }}</td>
                            </tr>
                            <tr>
                                <th>Gesamt</th>
                                <td>${{ $transaction->order->total }}</td>
                                <th>Zahlungsart</th>
                                <td>{{ ucfirst($transaction->mode) }}</td>
                                <th>Status</th>
                                <td>
                                    @if($transaction->status == 'approved')
                                        <span class="badge bg-success">Genehmigt</span>
                                    @elseif($transaction->status == 'declined')
                                        <span class="badge bg-danger">Abgelehnt</span>
                                    @elseif($transaction->status == 'refunded')
                                        <span class="badge bg-secondary">Erstattet</span>
                                    @else
                                        <span class="badge bg-warning">Ausstehend</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Cancel Order Button --}}
                @if($transaction->order->status === 'ordered')
                <div class="wg-box mt-5 text-end">
                    <form id="cancelOrderForm" action="{{ route('user.order.cancel') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="order_id" value="{{ $transaction->order->id }}">
                        <button type="button" class="btn btn-danger cancel-order">
                            Bestellung stornieren
                        </button>
                    </form>
                </div>
                @endif

            </div>
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script>
$(document).on('click', '.cancel-order', function(e) {
    e.preventDefault();
    const form = $(this).closest('form');

    swal({
        title: 'Bist du sicher?',
        text: 'Du möchtest diese Bestellung abbrechen?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ja, abbrechen!',
        cancelButtonText: 'Nein, behalten'
    },
    function(isConfirm) {
        if (isConfirm) {
            form[0].submit();
        }
    });
});
</script>
@endpush
