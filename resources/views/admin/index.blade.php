@extends('layouts.admin')
@section('content')

<div class="main-content-inner">

    <div class="main-content-wrap">
        <div class="tf-section-2 mb-30">
            <div class="flex gap20 flex-wrap-mobile">
                <div class="w-half">

                    <div class="wg-chart-default mb-20">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap14">
                                <div class="image ic-bg">
                                    <i class="icon-shopping-bag"></i>
                                </div>
                                <div>
                                    <div class="body-text mb-2">Gesamte Bestellungen</div>
                                    <h4>{{$dashboardDatas[0]->Total}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="wg-chart-default mb-20">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap14">
                                <div class="image ic-bg">
                                    <i class="icon-dollar-sign"></i>
                                </div>
                                <div>
                                    <div class="body-text mb-2">Gesamtbetrag</div>
                                    <h4>{{$dashboardDatas[0]->TotalAmount}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="wg-chart-default mb-20">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap14">
                                <div class="image ic-bg">
                                    <i class="icon-shopping-bag"></i>
                                </div>
                                <div>
                                    <div class="body-text mb-2">Ausstehende Bestellungen</div>
                                    <h4>{{$dashboardDatas[0]->TotalOrdered}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="wg-chart-default">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap14">
                                <div class="image ic-bg">
                                    <i class="icon-dollar-sign"></i>
                                </div>
                                <div>
                                    <div class="body-text mb-2">Betrag ausstehender Bestellungen</div>
                                    <h4>{{$dashboardDatas[0]->TotalOrderedAmount}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="w-half">

                    <div class="wg-chart-default mb-20">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap14">
                                <div class="image ic-bg">
                                    <i class="icon-shopping-bag"></i>
                                </div>
                                <div>
                                    <div class="body-text mb-2">Gelieferte Bestellungen</div>
                                    <h4>{{$dashboardDatas[0]->TotalDelivered}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="wg-chart-default mb-20">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap14">
                                <div class="image ic-bg">
                                    <i class="icon-dollar-sign"></i>
                                </div>
                                <div>
                                    <div class="body-text mb-2">Betrag gelieferter Bestellungen</div>
                                    <h4>{{$dashboardDatas[0]->TotalDeliveredAmount}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="wg-chart-default mb-20">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap14">
                                <div class="image ic-bg">
                                    <i class="icon-shopping-bag"></i>
                                </div>
                                <div>
                                    <div class="body-text mb-2">Stornierte Bestellungen</div>
                                    <h4>{{$dashboardDatas[0]->TotalCanceled}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="wg-chart-default">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap14">
                                <div class="image ic-bg">
                                    <i class="icon-dollar-sign"></i>
                                </div>
                                <div>
                                    <div class="body-text mb-2">Betrag stornierter Bestellungen</div>
                                    <h4>{{$dashboardDatas[0]->TotalCanceledAmount}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <div class="wg-box">
                <div class="flex items-center justify-between">
                    <h5>Monatliche Einnahmen</h5>
                    
                </div>
                <div class="flex flex-wrap gap40">
                    <div>
                        <div class="mb-2">
                            <div class="block-legend">
                                <div class="dot t1"></div>
                                <div class="text-tiny">Totaler Umsatz</div>
                            </div>
                        </div>
                        <div class="flex items-center gap10">
                            <h4>{{$TotalAmount}}</h4>
                            
                        </div>
                    </div>
                    <div>
                        <div class="mb-2">
                            <div class="block-legend">
                                <div class="dot t2"></div>
                                <div class="text-tiny">Bestellungen</div>
                            </div>
                        </div>
                        <div class="flex items-center gap10">
                            <h4>{{$TotalOrderedAmount}}</h4>
                        </div>
                    </div>

                    <div>
                        <div class="mb-2">
                            <div class="block-legend">
                                <div class="dot t2"></div>
                                <div class="text-tiny">Zugestellt</div>
                            </div>
                        </div>
                        <div class="flex items-center gap10">
                            <h4>{{$TotalDeliveredAmount}}</h4>
                        </div>
                    </div>

                    <div>
                        <div class="mb-2">
                            <div class="block-legend">
                                <div class="dot t2"></div>
                                <div class="text-tiny">Storniert</div>
                            </div>
                        </div>
                        <div class="flex items-center gap10">
                            <h4>{{$TotalCanceledAmount}}</h4>
                        </div>
                    </div>

                </div>
                <div id="line-chart-8"></div>
            </div>

        </div>
        <div class="tf-section mb-30">

            <div class="wg-box">
                <div class="flex items-center justify-between">
                    <h5>Letzte Bestellungen</h5>
                    <div class="dropdown default">
                        <a class="btn btn-secondary dropdown-toggle" href="{{route('admin.orders')}}">
                            <span class="view-all">Alle anzeigen</span>
                        </a>
                    </div>
                </div>
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
            </div>

        </div>
    </div>

</div>
@endsection

@push('scripts')

<script>
    (function ($) {

        var tfLineChart = (function () {

            var chartBar = function () {

                var options = {
                    series: [
                        {
                            name: 'Total',
                            data: [{{$AmountM}}]
                        },
                        {
                            name: 'Pending',
                            data: [{{$OrderedAmountM}}]
                        },
                        {
                            name: 'Delivered',
                            data: [{{$DeliveredAmountM}}]
                        },
                        {
                            name: 'Canceled',
                            data: [{{$CanceledAmountM}}]
                        }
                    ],
                    chart: {
                        type: 'bar',
                        height: 325,
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '10px',
                            endingShape: 'rounded'
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    legend: {
                        show: false
                    },
                    colors: ['#2377FC', '#FFA500', '#078407', '#FF0000'],
                    stroke: {
                        show: false
                    },
                    xaxis: {
                        labels: {
                            style: {
                                colors: '#212529'
                            }
                        },
                        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                    },
                    yaxis: {
                        show: false
                    },
                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return "$ " + val;
                            }
                        }
                    }
                };

                var chart = new ApexCharts(document.querySelector("#line-chart-8"), options);
                if ($("#line-chart-8").length > 0) {
                    chart.render();
                }
            };

            return {
                init: function () { },
                load: function () {
                    chartBar();
                },
                resize: function () { }
            };

        })();

        jQuery(document).ready(function () { });

        jQuery(window).on("load", function () {
            tfLineChart.load();
        });

        jQuery(window).on("resize", function () { });

    })(jQuery);
</script>
@endpush
