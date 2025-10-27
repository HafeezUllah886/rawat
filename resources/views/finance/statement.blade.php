@extends('layout.dashboard')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@section('content')
@php
        App::setLocale(auth()->user()->lang);
    @endphp
<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>{{ __('lang.AccountStatement') }} ({{ $account->title }} - {{ $account->type == "Vendor" ? "Vendor" : $account->type }})</h4>
                <button id="download" class="btn btn-success">PDF</button>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="from">{{ __('lang.FromDate') }}</label>
                            <input type="date" name="from" id="from" onchange="get_items()" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="to">{{ __('lang.ToDate') }}</label>
                            <input type="date" name="to" id="to" onchange="get_items()" class="form-control">
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white m-b-30" id="items">

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
        get_items();
    });
    $('#datatable1').DataTable({
        "bSort": true
        , "bLengthChange": true
        , "bPaginate": true
        , "bFilter": true
        , "bInfo": true,

    });


    var date = new Date();
        var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
        var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);

        var f2 = flatpickr(document.getElementById('from'), {
    dateFormat: "d-m-Y",
    defaultDate: firstDay
    });

    var f2 = flatpickr(document.getElementById('to'), {
    dateFormat: "d-m-Y",
    defaultDate: lastDay
    });


    var elems = document.getElementsByClassName('confirmation');
    var confirmIt = function (e) {
        if (!confirm('Are you sure to delete account?')) e.preventDefault();
    };
    for (var i = 0, l = elems.length; i < l; i++) {
        elems[i].addEventListener('click', confirmIt, false);
    }

    function get_items(){
    var from = $('#from').val();
    var to = $('#to').val();
    $.ajax({
    method: "get",
    url: "{{url('/accounts/details/')}}/"+{{$account->id}}+"/"+from+"/"+to,
    success: function(result){

        $("#items").html(result);
    }
    });
    }

    $('#download').click(function (){
        var from = $('#from').val();
        var to = $('#to').val();
        window.open("{{ url('/account/statement/pdf/') }}/"+{{$account->id}}+"/"+from+"/"+to,"_self");
    });
</script>
@endsection
