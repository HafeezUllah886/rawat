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
                <h4>{{ __('lang.Income&ExpenseDetails') }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white m-b-30">
            <table class="table table-bordered table-striped table-hover text-center" id="datatable2">
                <thead class="th-color">
                    <tr>
                        <th>{{ __('lang.Ser') }}</th>
                        <th>{{ __('lang.Date') }}</th>
                        <th>{{ __('lang.Account') }}</th>
                        <th>{{ __('lang.Desc') }}</th>
                        <th>{{ __('lang.Details') }}</th>
                        <th>{{ __('lang.Credit') }}</th>
                        <th>{{ __('lang.Debit') }}</th>
                        <th>{{ __('lang.Balance') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $balance = 0;
                    @endphp
                    @foreach ($trans1 as $tran)
                    @php
                        $balance += $tran->cr;
                        $balance -= $tran->db;
                    @endphp
                        <tr>
                            <td>{{ $tran->id}}</td>
                            <td>{{ $tran->date }}</td>
                            <td>{{ $tran->account->title }}</td>
                            <td>{!! $tran->desc !!}</td>
                            <td> @if ($tran->type == 'Purchase')
                                @php
                                $data = \App\Models\purchase_details::with('product')->where('ref', $tran->ref)->get();
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
                            @if($tran->type == "Sale")
                                @php
                                $data = \App\Models\sale_details::with('product')->where('ref', $tran->ref)->get();
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
                            @if ($tran->type == 'Sale Return')
                            @php
                            $data = \App\Models\saleReturnDetails::with('product')->where('ref', $tran->ref)->get();
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
                        @endif</td>
                            <td>{{ round($tran->cr,0) }}</td>
                            <td>{{ round($tran->db,0) }}</td>
                            <td>{{ round($balance,0) }}</td>
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
    $('#datatable2').DataTable({
        "bSort": true
        , "bLengthChange": true
        , "bPaginate": true
        , "bFilter": true
        , "bInfo": true
        , "order": [
            [1, 'desc']
        ]
    , });

</script>
@endsection
