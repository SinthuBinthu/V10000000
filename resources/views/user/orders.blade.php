{{-- resources/views/orders.blade.php --}}
@extends('layouts.app')

@section('content')
<style>
    .table > :not(caption) > tr > th {
        padding: 0.625rem 1.5rem .625rem !important;
        background-color: #6a6e51 !important;   
    }
    .table > tr > td {
        padding: .8rem 1rem !important;
    }
    .table-bordered > :not(caption) > tr > th,
    .table-bordered > :not(caption) > tr > td {
        border-width: 1px 1px;
        border-color: #6a6e51;
    }
</style>

<main class="pt-90">
  <div class="mb-4 pb-4"></div>
  <section class="my-account container">
    <h2 class="page-title">Bestellungen</h2>
    <div class="row">
      <div class="col-lg-2">
        @include('user.account-nav')
      </div>
      <div class="col-lg-10">
        <div class="wg-table table-all-user">
          <div class="table-responsive">
            <table class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th style="width: 80px">Bestell-Nr.</th>
                  <th>Name</th>
                  <th class="text-center">Telefon</th>
                  <th class="text-center">Zwischensumme</th>
                  <th class="text-center">Steuer</th>
                  <th class="text-center">Gesamt</th>
                  <th class="text-center">Status</th>
                  <th class="text-center">Bestelldatum</th>
                  <th class="text-center">Artikel</th>
                  <th class="text-center">Zugestellt am</th>
                  <th class="text-center">Aktionen</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($orders as $order)
                  <tr>
                    <td class="text-center">
                      {{ "1" . str_pad($order->id, 4, "0", STR_PAD_LEFT) }}
                    </td>
                    <td class="text-center">{{ $order->name }}</td>
                    <td class="text-center">{{ $order->phone }}</td>
                    <td class="text-center">${{ number_format($order->subtotal, 2) }}</td>
                    <td class="text-center">${{ number_format($order->tax, 2) }}</td>
                    <td class="text-center">${{ number_format($order->total, 2) }}</td>
                    <td class="text-center">
                      @if($order->status == 'delivered')
                        <span class="badge bg-success">Zugestellt</span>
                      @elseif($order->status == 'canceled')
                        <span class="badge bg-danger">Storniert</span>
                      @else
                        <span class="badge bg-warning">Bestellt</span>
                      @endif
                    </td>
                    <td class="text-center">
                      {{ $order->created_at->format('d/m/Y') }}
                    </td>
                    <td class="text-center">
                      {{ $order->orderItems->count() }}
                    </td>
                    <td class="text-center">
                      {{ optional($order->delivered_date)->format('d/m/Y') ?? '-' }}
                    </td>

                    {{-- Neue Aktions-Spalte mit Link --}}
                    <td class="text-center">
                      <a href="{{ route('user.account.order.details', ['order' => $order->id]) }}">
                        <div class="list-icon-function view-icon">
                          <div class="item eye">
                            <i class="fa fa-eye"></i>
                          </div>
                        </div>
                      </a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

        <div class="divider"></div>
        <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
          {{ $orders->links('pagination::bootstrap-5') }}
        </div>
      </div>
    </div>
  </section>
</main>
@endsection
