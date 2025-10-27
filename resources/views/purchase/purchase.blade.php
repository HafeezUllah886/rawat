@extends('layout.dashboard')
<script>
     function abc() {
        var isPaid = $('#isPaid').find(":selected").val();
        if (isPaid == 'No') {
            $('#paidIn_box').css('display', 'none');
            $("#amount_box").css('display', 'none');
        } else if (isPaid == 'Partial') {
            $("#amount_box").css('display', 'block');
            $('#paidIn_box').css('display', 'block');
        } else {
            $("#amount_box").css('display', 'none');
            $('#paidIn_box').css('display', 'block');
        }
    }

    function walkIn1(){
        console.log(vendor);
        var vendor = $("#vendor").find(':selected').val();

        if(vendor == 0)
        {
            $('#walkIn_box').css("display", "block");
            $('#isPaid').val('Yes');
            $('#isPaid_box').css('display', 'none');
            abc();
        }
        else
        {
            $('#walkIn_box').css("display", "none");
            $('#isPaid_box').css('display', 'block');
        }

    }
</script>
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
                <h4>{{ __('lang.Purchase') }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white m-b-30">
            <div class="card-body">
                <form id="pro_form">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="product">{{ __('lang.SelectProduct') }}</label>
                            <select name="product" required id="product" class="select2">
                                <option value=""></option>
                                @foreach ($products as $pro)
                                    <option value="{{ $pro->id }}"> {{$pro->code}} | {{ $pro->name }} | {{$pro->bike}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="qty">{{ __('lang.Quantity') }}</label>
                            <input type="number" required name="qty" id="qty" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="rate">{{ __('lang.PurchaseRate') }}</label>
                            <input type="number" required name="rate" id="rate" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="price">Retail</label>
                            <input type="number" required name="price" id="price" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="wholesale">Wholesale</label>
                            <input type="number" required name="wholesale" id="wholesale" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-info" style="margin-top: 30px">Add</button>
                    </div>
                </div>
            </form>
                <div class="mt-3">
                    <table class="table table-bordered table-striped table-hover text-center mb-0" id="datatable1">
                        <thead class="th-color">
                            <tr>
                                <th class="border-top-0">{{ __('lang.Ser') }}</th>
                                <th class="border-top-0">Code</th>
                                <th class="border-top-0">{{ __('lang.Product') }}</th>
                                <th class="border-top-0">Bike</th>
                                <th class="border-top-0">Model</th>
                                <th class="border-top-0">{{ __('lang.Quantity') }}</th>
                                <th class="border-top-0">{{ __('lang.Price') }}</th>
                                <th class="border-top-0">Retail</th>
                                <th class="border-top-0">Wholesale</th>
                                <th class="border-top-0">{{ __('lang.Amount') }}</th>
                                <th>{{ __('lang.Action') }}</th>
                            </tr>
                        </thead>
                        <tbody id="items">
                        </tbody>
                    </table>
                    <form method="post" class="mt-5">
                        @csrf
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="date">{{ __('lang.Date') }}</label>
                                    <input type="datetime-local" name="date" value="{{ now() }}" id="date" class="form-control">
                                    @error('date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="vendor">{{ __('lang.SelectVendor') }}</label>
                                    <select name="vendor" id="vendor" onchange="walkIn1()" class="select2">
                                        @foreach ($vendors as $vendor)
                                            <option value="{{ $vendor->id }}">{{ $vendor->title }} ({{ $vendor->type == "Vendor" ? "Vendor" : $vendor->type }})</option>
                                        @endforeach
                                    </select>
                                    @error('vendor')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2" id="walkIn_box">
                                <div class="form-group">
                                    <label for="">{{ __('lang.VendorName') }}</label>
                                    <input type="text" name="walkIn" class="form-control">
                                    @error('walkIn')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2" id="isPaid_box">
                                <div class="form-group">
                                    <label for="isPaid">{{ __('lang.IsPaid') }}</label>
                                    <select name="isPaid" id="isPaid" onchange="abc()" class="form-control">
                                        <option value="Yes">{{ __('lang.Yes') }}</option>
                                        <option value="No">{{ __('lang.No') }}</option>
                                        <option value="Partial">{{ __('lang.Partial') }}</option>
                                    </select>
                                    @error('isPaid')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2" id="amount_box">
                                <div class="form-group">
                                    <label for="amount">{{ __('lang.Amount') }}</label>
                                    <input type="number" name="amount" id="amount" class="form-control">
                                    @error('amount')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3" id="paidIn_box">
                                <div class="form-group">
                                    <label for="paidFrom">{{ __('lang.PaidBy') }}</label>
                                    <select name="paidFrom" id="paidFrom" class=" select2">
                                        <option></option>
                                        @foreach ($paidFroms as $acct)
                                            <option value="{{ $acct->id }}">{{ $acct->title }}</option>
                                        @endforeach

                                    </select>
                                    @error('paidFrom')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2" >
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="select2">
                                        <option value="Received">Received</option>
                                        <option value="Pending">Pending</option>
                                    </select>
                                    @error('paidFrom')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="desc">{{ __('lang.Desc') }}</label>
                                    <textarea name="desc" id="desc" class="form-control"></textarea>
                                    @error('amount')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3" >
                                <div class="form-group">
                                    <label for="warehouse">Warehouse</label>
                                    <select name="warehouseID" id="warehouseID" class="select2">
                                        @foreach ($warehouses as $warehouse)
                                            <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                            <div class="col-md-2" >
                                <div class="form-group">
                                    <label for="order">Order Number</label>
                                    <input type="text" name="ordernumber" id="ordernumber" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-1">
                                    <button type="submit" class="btn btn-success btn-lg" style="margin-top: 30px">{{ __('lang.Save') }}</button>
                            </div>
                        </div>
                    </form>
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
    $(document).ready(function() {
        $("#amount_box").css('display', 'none');
         $('#walkIn_box').css("display", "none");
        get_items();
    });
$('#pro_form').submit(function(e){
    e.preventDefault();
    var data = $('#pro_form').serialize();
    $.ajax({
        method: 'get',
        url: "{{url('/purchase/store')}}",
        data: data,
        success: function(abc){
            get_items();
            $("#qty").val('');
            $("#rate").val('');
            $("#price").val('');
            $("#wholesale").val('');
            if(abc == 'Existing')
            {
                Snackbar.show({
                text: "Already Added",
                duration: 3000,
                actionTextColor: '#fff',
                backgroundColor: '#e7515a'
                /* actionTextColor: '#fff',
                backgroundColor: '#00ab55' */
            });
            }
        }
    });
});
function get_items(){
    $.ajax({
        method: "GET",
        url: "{{url('/purchase/draft/items')}}",
        success: function(respose){
            $("#items").html(respose);
        }
    });
}
function qty(id){
    var val = $("#qty"+id).val();
    $.ajax({
        method: "GET",
        url: "{{url('/purchase/update/draft/qty/')}}/"+id+"/"+val,
        success: function(respose){
            get_items();
            Snackbar.show({
            text: "Quantity Updated",
            duration: 3000,
            /* actionTextColor: '#fff',
            backgroundColor: '#e7515a' */
            actionTextColor: '#fff',
            backgroundColor: '#00ab55'
            });
        }
    });
}
function rate(id){
    var val = $("#rate"+id).val();
    $.ajax({
        method: "GET",
        url: "{{url('/purchase/update/draft/rate/')}}/"+id+"/"+val,
        success: function(respose){
            get_items();
            Snackbar.show({
            text: "Rate Updated",
            duration: 3000,
            actionTextColor: '#fff',
            backgroundColor: '#00ab55'
            });
        }
    });
}
function deleteDraft(id){
    $.ajax({
        method: "GET",
        url: "{{url('/purchase/draft/delete/')}}/"+id,
        success: function(respose){
            get_items();
            Snackbar.show({
            text: "Item Deleted",
            duration: 3000,
            actionTextColor: '#fff',
            backgroundColor: '#e7515a'
            /* actionTextColor: '#fff',
            backgroundColor: '#00ab55' */
            });
        }
    });
}
</script>
@endsection
