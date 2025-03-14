@extends('adminlte::page')

@section('title', 'Orders')

@section('content_header')
    <h1>Orders</h1>
@stop
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header clearfix">
                            <div class="row">
                                <div class="col-9">
                                    <button onclick="printInvoice()" class="btn btn-outline-primary btn-icon">
                                        <i class="mr-1 fa fa-print text-primary-m1 text-120 w-2 me-2"></i>Print
                                    </button>

                                </div>
                                <div class="col-3 pull-right">
                                    Invoice
                                </div>
                            </div>

                        </div>
                        <div id="invoice">
                            <div class="card-body">
                                <div class="row">
                                    <div id="client" class="col-4">
                                        <div class="to">INVOICE TO: <b>{{ $order->customer->name }}</b> </div>
                                        <div class="address">{{ $order->customer->phone }}</div>
                                        <div class="email">{{ $order->customer->email }}</div>
                                    </div>
                                    <div id="client" class="col-4">
                                        <div class="to">Shipping Address:</div>
                                        <div class="address"><b>{!! $order->shipping_address !!}</b> </div>
                                    </div>
                                    <div id="invoice" class="col-4">
                                        INVOICE NO : <b>#{{ $order->id }}</b>
                                        <div class="date">Issue Date: {{ $order->created_at->format('m/d/Y') }}</div>
                                        <div class="date">Payment Type/Status: {{ ucfirst($order->payment_type) }}
                                            ({{ ucfirst($order->payment_status) }})</div>
                                        {{-- <div class="date">Vat no: {{ $order->customer->vat_no }}</div> --}}
                                    </div>
                                </div>
                                <table class="table table-responsive-lg">
                                    <thead>
                                        <tr>
                                            <th class="no">#</th>
                                            <th class="title">Name</th>
                                            <th class="qty">QUANTITY</th>
                                            <th class="rate">RATE</th>
                                            <th class="amnt">AMOUNT</th>
                                            <th class="disc">Discount</th>
                                            <th class="total">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->order_items as $item)
                                            <tr>
                                                <td class="no">{{ $loop->iteration }}</td>
                                                <td class="desc">
                                                    ({{ $item->product->name }})
                                                    {{ $item->variant ? $item->variant->sku : 'No Variant' }}
                                                </td>
                                                <td class="unit">{{ $item->quantity }}</td>
                                                <td class="rate">{{ $item->price }}</td>
                                                <td class="total">{{ $item->quantity * $item->price }}</td>
                                                <td class="disc">{{ $item->discount }}</td>
                                                <td class="total">{{ $item->quantity * $item->price - $item->discount }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3"></td>
                                            <td colspan="3" class="table-light">SUBTOTAL</td>
                                            <td class="table-light">Rs. {{ $order->total_price }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"></td>
                                            <td>DISCOUNT</td>
                                            <td colspan="2">
                                                <table>
                                                    <tr>
                                                        <td>Cpn Discount:{{ $order->coupon_discount }}</td>
                                                        <td>Cpn Used:{{ $order->coupon_code }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">Other Discount:{{ $order->other_discount ?? 0 }}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td>Rs. {{ $order->discount }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"></td>
                                            <td colspan="3">SHIPPING FEE</td>
                                            <td>Rs. {{ $order->shipping_price }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"></td>
                                            <td colspan="3" class="table-light">GRAND TOTAL</td>
                                            <td class="table-light">Rs. {{ $order->grand_total }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                            <td colspan="3">
                                                <b>Total quantity:</b> {{ $order->order_items->count() }}
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
@stop

@section('css')
    <style>
        @media print {
            title {
                display: none;
            }

            body * {
                visibility: hidden;
            }

            #invoice,
            #invoice * {
                visibility: visible;
            }
        }
    </style>

@stop

@section('js')
    <script>
        function printInvoice() {
            var originalTitle = document.title;
            document.title = 'Invoice'; // Remove the title

            window.print(); // Trigger print

            document.title = originalTitle; // Restore the original title
        }
    </script>
@stop
