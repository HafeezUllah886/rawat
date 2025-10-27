@php
        App::setLocale(auth()->user()->lang);
    @endphp
@extends('layout.dashboard')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>{{ __('lang.LedgerDetails') }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white m-b-30">
            <table class="table table-bordered table-striped table-hover text-center" id="datatable1">
                <thead class="th-color">
                    <th>{{ __('lang.Ser') }}</th>
                    <th>{{ __('lang.Date') }}</th>
                    <th>{{ __('lang.Head') }}</th>
                    <th>{{ __('lang.PaymentType') }}</th>
                    <th>{{ __('lang.Desc') }}</th>
                    <th>{{ __('lang.Details') }}</th>
                    <th>{{ __('lang.Amount') }}</th>
                </thead>
                <tbody>
                    @foreach ($ledger as $item)
                    <tr>
                        <td>{{ $item->id}}</td>
                        <td>{{ $item->date }}</td>
                        <td>{{ $item->head }}</td>
                        <td>{{ $item->type }}</td>
                        <td>{{ $item->details }}</td>
                        <td>
                            @if ($item->details == 'Stock Purchased')
                                @php
                                $data = \App\Models\purchase_details::with('product')->where('ref', $item->ref)->get();
                                $subTotal = 0;
                                @endphp
                                <table class="table">
                                    <th>Product</th>
                                    <th>Size</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Amount</th>
                                    @foreach ($data as $data1)
                                    @php
                                    $subTotal = $data1->qty * $data1->rate;
                                    @endphp
                                    <tr>
                                        <td>{{$data1->product->name}}</td>
                                        <td>{{$data1->product->size}}</td>
                                        <td>{{$data1->qty}}</td>
                                        <td>{{round($data1->rate,2)}}</td>
                                        <td>{{$subTotal}}</td>
                                    </tr>
                                    @endforeach

                                </table>
                            @endif
                            @if($item->details == "Products Sold")
                                @php
                                $data = \App\Models\sale_details::with('product')->where('ref', $item->ref)->get();
                                $subTotal = 0;
                                @endphp
                                <table class="table">
                                    <th>{{ __('lang.Product') }}</th>
                                    <th>{{ __('lang.Size') }}</th>
                                    <th>{{ __('lang.Qty') }}</th>
                                    <th>{{ __('lang.Price') }}</th>
                                    <th>{{ __('lang.Amount') }}</th>
                                    @foreach ($data as $data1)
                                    @php
                                    $subTotal = $data1->qty * $data1->price;
                                    @endphp
                                    <tr>
                                        <td>{{$data1->product->name}}</td>
                                        <td>{{$data1->product->size}}</td>
                                        <td>{{$data1->qty}}</td>
                                        <td>{{round($data1->price,0)}}</td>
                                        <td>{{$subTotal}}</td>
                                    </tr>
                                    @endforeach

                                </table>
                                <strong>{{ __('lang.Discount') }}: </strong>{{$data[0]->bill->discount ?? '0'}}
                            @endif
                            @if ($item->details == 'Sale Return')
                            @php
                            $data = \App\Models\saleReturnDetails::with('product')->where('ref', $item->ref)->get();
                            $subTotal = 0;
                            @endphp
                            <table class="table">
                                <th>{{ __('lang.Product') }}</th>
                                <th>{{ __('lang.Size') }}</th>
                                <th>{{ __('lang.Qty') }}</th>
                                <th>{{ __('lang.Price') }}</th>
                                <th>{{ __('lang.Amount') }}</th>
                                @foreach ($data as $data1)
                                @php
                                $subTotal = $data1->qty * $data1->price;
                                @endphp
                                <tr>
                                    <td>{{$data1->product->name}}</td>
                                    <td>{{$data1->product->size}}</td>
                                    <td>{{$data1->qty}}</td>
                                    <td>{{round($data1->price,2)}}</td>
                                    <td>{{$subTotal}}</td>
                                </tr>
                                @endforeach

                            </table>
                        @endif
                        </td>
                        <td>{{ $item->amount }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<style>
    .dataTables_paginate {
        display: block
    }

</style>
<script>
    $(document).ready(function() {

    });
    $('#datatable1').DataTable({
        "bSort": true
        , "bLengthChange": true
        , "bPaginate": true
        , "bFilter": true
        , "bInfo": true
        , "order": [
            [0, 'desc']
        ]
    , });

</script>
@endsection
