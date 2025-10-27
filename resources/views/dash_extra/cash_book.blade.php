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
                <h4>{{ __('lang.DailyCashBook') }}</h4>
                <div class="w-40">
                    <input type="date" class="form-control" onchange="abc()" value="{{ $date }}" name="date" id="date">
                </div>

            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6">
                <div class="card bg-white m-b-30">
                    <div class="card-header">
                        <h5>{{ __('lang.CashInflow') }}</h5>
                    </div>
                    <table class="table table-hover" id="datatable1">
                        <thead class="th-color">
                            <th>{{ __('lang.Ser') }}</th>
                            <th>{{ __('lang.Account') }}</th>
                            <th>{{ __('lang.Desc') }}</th>
                            <th>{{ __('lang.Customer') }}</th>
                            <th>{{ __('lang.Amount') }}</th>
                        </thead>
                        <tbody>
                            @php

                                $total_cr = 0;
                            @endphp
                            @foreach ($in as $trans)
                            @php
                                $total_cr += $trans->cr;
                            @endphp
                                <tr @if($trans->db > 0) style="background:#FFC4C9;" @endif>
                                    <td>{{ $trans->id }}</td>
                                    <td>{{ $trans->account->title }}</td>

                                    <td>{!! $trans->desc !!}</td>
                                    <td>
                                        @if ($trans->type == 'Sale')
                                        @php
                                            $data = \App\Models\sale::with('customer_account')->where('ref', $trans->ref)->first();

                                            if (@$data->walking != null)
                                            {
                                                echo $data->walking." (Walk-in)";

                                            }
                                            else
                                            {
                                                echo @$data->customer_account->title;
                                            }

                                        @endphp
                                       @endif
                                    </td>
                                    <td>{{ round($trans->cr,0) }}</td>
                                </tr>
                            @endforeach
                            <tr class="th-color">
                                <td colspan="4" class="text-right">{{ __('lang.Total') }}: &nbsp;
                                </td>
                                <td>{{ $total_cr }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-white m-b-30">
                    <div class="card-header">
                        <h5>{{ __('lang.CashOutflow') }}</h5>
                    </div>
                    <table class="table table-hover" id="datatable1">
                        <thead class="th-color">
                            <th>{{ __('lang.Ser') }}</th>
                            <th>{{ __('lang.Account') }}</th>
                            <th>{{ __('lang.Desc') }}</th>
                            <th>{{ __('lang.Vendor') }}</th>
                            <th>{{ __('lang.Amount') }}</th>
                        </thead>
                        <tbody>
                            @php

                                $total_db = 0;
                            @endphp
                            @foreach ($out as $tran1)
                            @php

                                $total_db += $tran1->db;

                            @endphp
                                <tr @if($tran1->db > 0) style="background:#;" @endif>
                                    <td>{{ $tran1->id }}</td>
                                    <td>{{ $tran1->account->title }}</td>

                                    <td>{!! $tran1->desc !!}</td>
                                    <td>
                                        @if ($tran1->type == 'Purchase')
                                        @php
                                            $data = \App\Models\purchase::with('vendor_account')->where('ref', $tran1->ref)->first();

                                            if (@$data->walking != null)
                                            {
                                                echo $data->walking." (Walk-in)";

                                            }
                                            else
                                            {
                                                echo @$data->vendor_account->title;
                                            }

                                        @endphp
                                       @endif
                                    </td>
                                    <td>{{ round($tran1->db,0) }}</td>
                                </tr>
                            @endforeach
                            <tr class="th-color">
                                <td colspan="4" class="text-right">{{ __('lang.Total') }}: &nbsp;
                                </td>
                                <td>{{ $total_db }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ __('lang.Summary') }}</h5>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <td> <h5>{{ __('lang.PreviousBalance') }}</h5></td>
                                <td><h5>{{ previousCash($date) }}</h5></td>
                            </tr>
                            <tr>
                                <td><h5>{{ __('lang.TodayCredit') }}</h5></td>
                                <td><h5>{{ $total_cr }}</h5></td>
                            </tr>
                            <tr>
                                <td><h5>{{ __('lang.TodayDebit') }}</h5></td>
                                <td><h5>{{ $total_db }}</h5></td>
                            </tr>
                            <tr>
                                <td><h5>{{ __('lang.TodayBalance') }}</h5></td>
                                <td><h5>{{ $total_cr - $total_db }}</h5></td>
                            </tr>
                            <tr>
                                <td><h5>{{ __('lang.NetBalance') }}</h5></td>
                                <td><h5>{{ previousCash($date) + ($total_cr - $total_db) }}</h5></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
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
        "bSort": true,
        "bLengthChange": true,
        "bPaginate": true,
        "bFilter": true,
        "bInfo": true,
        "order": [[0, 'asc']],
    });

    function abc(){
        var date = $('#date').val();
        console.log(date);
    window.open("{{ url('/dashboard/CashBook/') }}/"+date, '_self');
    }

</script>
@endsection
