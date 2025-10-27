@extends('layout.dashboard')
@php
        App::setLocale(auth()->user()->lang);
    @endphp
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>Returned Items</h4>
               
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card bg-white m-b-30">
            <div class="card-body table-responsive new-user">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover text-center mb-0" id="datatable1">
                        <thead class="th-color">
                            <tr>
                                <th class="border-top-0">#</th>
                                <th class="border-top-0">Bill#</th>
                                <th class="border-top-0">Date</th>
                                <th class="border-top-0">Product</th>
                                <th class="border-top-0">Qty</th>
                                <th class="border-top-0">Rate</th>
                                <th class="border-top-0">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($returns as $key => $return)
                            <tr>
                                <td> {{ $key+1}} </td>
                                <td> {{ $return->saleID }} </td>
                                <td> {{ date("d-m-Y", strtotime($return->date)) }} </td>
                                <td> {{ $return->product->name }} </td>
                                <td> {{ $return->qty }} </td>
                                <td> {{ $return->rate }} </td>
                                <td> {{ $return->amount }} </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <th colspan="6">Total</th>
                            <th class="text-center">{{$returns->sum('amount')}}</th>
                        </tfoot>
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
    $('#datatable1').DataTable({
        "bSort": true
        , "bLengthChange": true
        , "bPaginate": true
        , "bFilter": true
        , "bInfo": true,

    });
</script>
@endsection
