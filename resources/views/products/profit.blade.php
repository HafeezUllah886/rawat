@extends('layout.dashboard')
@php
        App::setLocale(auth()->user()->lang);
    @endphp
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div class="col-md-6">
                    <h4>{{__('lang.Profit/Loss')}}</h4>
                </div>
                <div class="col-md-6">
                    <table>
                        <tr>
                            <td>From: </td>
                            <td> <input type="date" class="form-control" value="{{ $from }}" id="from"> </td>
                            <td> &nbsp; - &nbsp; </td>
                            <td> To: </td>
                            <td> <input type="date" class="form-control" value="{{ $to }}" id="to"> </td>
                            <td> &nbsp;<button class="btn btn-info" id="btn">Filter</button> </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white m-b-30">
            <div class="card-body table-responsive new-user">
                <strong>APP</strong> = Avg Purchase Price (اوسط خریدی قیمت), <strong>ASP</strong> = Avg Sale Price(اوسط فروخت کی قیمت), <strong> PPU</strong> = Profit Per Unit (قیمت فی دانا)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover text-center mb-0" id="datatable">
                        <thead class="th-color">
                            <tr>
                                <th class="border-top-0">{{__('lang.Ser')}}</th>
                                <th class="border-top-0">{{__('lang.Product')}}</th>
                                <th class="border-top-0">{{__('lang.Size')}}</th>
                                <th class="border-top-0">APP</th>
                                <th class="border-top-0">ASP</th>
                                <th class="border-top-0">PPU</th>
                                <th class="border-top-0">{{__('lang.TotalSold')}}</th>
                                <th class="border-top-0">{{__('lang.Return')}}</th>
                                <th class="border-top-0">{{__('lang.Profit')}}</th>
                                <th class="border-top-0">{{__('lang.Stock')}}</th>
                                <th class="border-top-0">{{__('lang.StockValue')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $ser = 0;
                                $total = 0;
                            @endphp

                            @foreach ($products as $product)
                            @php
                                $ser += 1;
                                $total += ($product->sale_quantity - $product->return) * $product->ppu;
                                $net_profit = 0;
                            @endphp
                            <tr>
                                <td> {{ $ser }} </td>
                                <td> {{ $product->name }} </td>
                                <td> {{ $product->size}} </td>
                                <td> {{ round($product->average_purchase_price,2)}} </td>
                                <td> {{ round($product->average_sale_price,2)}} </td>
                                <td> {{ round($product->ppu,2)}} </td>
                                <td> {{ $product->sale_quantity}} </td>
                                <td> {{ $product->return}} </td>
                                <td> {{ round(($product->sale_quantity - $product->return) * $product->ppu,2) }} </td>
                                <td> {{ $product->available_stock}} </td>
                                <td> {{ $product->available_stock * $product->price}} </td>
                            </tr>
                            @endforeach
                            <tr>
                            <td colspan="8" style="text-align: right;"> <strong>{{__('lang.Total')}}</strong> </td>
                            <td> <strong>{{ round($total,2) }}</strong> </td>
                        </tr>

                        <tr>
                            <td colspan="8" style="text-align: right;"> <strong>{{__('lang.Discount')}}</strong> </td>
                            <td> <strong>{{ round($discounts,2) }}</strong> </td>
                        </tr>
                        <tr>
                            <td colspan="8" style="text-align: right;"> <strong>{{__('lang.Expenses')}}</strong> </td>
                            <td> <strong>{{ round($expense,2) }}</strong> </td>
                        </tr>
                        <tr>
                            <td colspan="8" style="text-align: right;"> <strong>{{__('lang.NetProfit')}}</strong> </td>
                            <td> <strong>{{ round($total - $discounts - $expense,2) }}</strong> </td>
                        </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<style>
    .dataTables_paginate {
        display: block
    }

</style>
<script>
    var elems = document.getElementsByClassName('confirmation');
    var confirmIt = function (e) {
        if (!confirm('Are you sure to delete account?')) e.preventDefault();
    };
    for (var i = 0, l = elems.length; i < l; i++) {
        elems[i].addEventListener('click', confirmIt, false);
    }

    $("#btn").click(function (){
        var from = $("#from").val();
        var to = $("#to").val();

        window.open("{{ url('/profit/') }}/"+from+"/"+to, '_self');
    });
</script>

@endsection
