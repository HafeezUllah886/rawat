@extends('layout.dashboard')
@php
        App::setLocale(auth()->user()->lang);
    @endphp
@section('content')
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
@if (session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif


<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>Sale Returns</h4>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                       
                    </div>
                    <div class="card-body">
                        <form method="get" action="{{route('returnsList')}}">
                            @csrf
                            <div class="form-group">
                                <label for="from">From</label>
                                <input type="date" class="form-control" required value="{{date('Y-m-d')}}" name="from" id="">
                               
                            </div>
                            <div class="form-group">
                                <label for="to">To</label>
                                <input type="date" class="form-control" required value="{{date('Y-m-d')}}" name="to" id="">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Proceed</button>
                            </div>
                        </form>
                    </div>
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
