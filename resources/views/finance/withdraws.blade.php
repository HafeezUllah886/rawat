@extends('layout.dashboard')
@php
        App::setLocale(auth()->user()->lang);
    @endphp
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>{{ __('lang.Withdraw') }}</h4>
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
                                <th class="border-top-0">{{ __('lang.Ref') }}</th>
                                <th class="border-top-0">{{ __('lang.Date') }}</th>
                                <th class="border-top-0">{{ __('lang.Account') }}</th>
                                <th class="border-top-0">{{ __('lang.Desc') }}</th>
                                <th class="border-top-0">{{ __('lang.Amount') }}</th>
                                <th>{{ __('lang.Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($withdraws as $dep)
                            <tr>
                                <td> {{ $dep->ref }} </td>
                                <td>{{ $dep->date}}</td>
                                <td>{{ $dep->account->title }} ({{ $dep->account->type }})</td>

                                <td>{{ $dep->desc}}</td>
                                <td>{{ $dep->amount}}</td>
                                <td>
                                    <a href="{{ url('withdraw/delete/') }}/{{ $dep->ref }}" class="btn btn-danger">Delete</a>
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
                <h5 class="modal-title">{{ __('lang.Withdraw') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post">
                @csrf
                <div class="modal-body">

                    <div class="form-group">
                        <label for="account">{{ __('lang.SelectAccount') }}</label>
                        <select name="account" id="account" class="select2" required id="">
                            <option value=""></option>
                            @foreach ($accounts as $account)
                               <option value="{{ $account->id }}">{{ $account->title }} ({{ $account->type }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="cat">{{ __('lang.Amount') }}</label>
                       <input type="number" required name="amount" id="amount" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="date">{{ __('lang.Date') }}</label>
                       <input type="datetime-local" name="date" id="date" value="{{ now() }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="desc">{{ __('lang.Desc') }}</label>
                        <textarea name="desc" id="desc" class="form-control"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('lang.Create') }}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('lang.Close') }}</button>
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
