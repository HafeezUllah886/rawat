@extends('layout.dashboard')
@php
        App::setLocale(auth()->user()->lang);
    @endphp
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>{{ __('lang.Transfer') }}</h4>
                <button class="btn btn-success" data-toggle="modal" data-target="#modal">{{ __('lang.CreateNew') }}</button>
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
                                <th class="border-top-0">Code</th>
                                <th class="border-top-0">Product</th>
                                <th class="border-top-0">Qty</th>
                                <th class="border-top-0">Date</th>
                                <th class="border-top-0">From</th>
                                <th class="border-top-0">To</th>
                                <th class="border-top-0">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($transfers as $key => $tran)
                            <tr>
                                <td> {{ $key+1}} </td>
                                <td> {{ $tran->product->code }} </td>
                                <td> {{ $tran->product->name }} </td>
                                <td> {{ $tran->qty }} </td>
                                <td>{{ date('d M Y', strtotime($tran->date))}}</td>
                                <td> {{ $tran->from->name }} </td>
                                <td> {{ $tran->to->name }} </td>
                                <td>
                                    <a href="{{ url('/stocktransfer/delete/') }}/{{ $tran->ref }}" class="btn btn-danger">Delete</a>
                                    <a href="{{ url('/stocktransfer/print/') }}/{{ $tran->printID }}" class="btn btn-info">Print</a>
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

{{-- Model Starts Here --}}
<div class="modal" id="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('lang.Transfer') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{ url('/stocktransfer/create') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="from">{{ __('lang.From') }}</label>
                        <select name="from" id="from" class="select2" required>
                            @foreach ($warehouses as $warehouse)
                               <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="to">{{ __('lang.To') }}</label>
                        <select name="to" id="to" class="select2" required>
                            @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                         @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date">{{ __('lang.Date') }}</label>
                       <input type="date" name="date" required id="date" value="{{ date('Y-m-d') }}" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('lang.Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('lang.Transfer') }}</button>
                </div>
            </form>
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
