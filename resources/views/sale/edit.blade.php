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
                <h4>{{ __('lang.EditSale') }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white m-b-30">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-12">
                        <table class="table">
                            <tr>
                                <td>{{ __('lang.InvoiceNo') }}. <strong>{{ $bill->id }}</strong></td>
                                <td>{{ __('lang.Date') }}:  (<strong>{{ date('d M Y', strtotime($bill->date)) }}</strong>)</td>
                                <td>{{ __('lang.Customer') }}: <strong>@if (@$bill->customer_account->title)
                                    {{ @$bill->customer_account->title }} ({{  @$bill->customer_account->type }})

                                @else
                                {{$bill->walking}} (Walk In)

                                @endif</strong> </td>

                            </tr>
                        </table>
                    </div>
                </div>
                <form id="pro_form">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="product">{{ __('lang.SelectProduct') }}</label>
                            <select name="product" required id="product" onchange="price1()" class="select2">
                                <option value=""></option>
                                @foreach ($products as $pro)
                                @if($pro->stock > 0)
                                    <option value="{{ $pro->id }}">{{ $pro->name }} | {{$pro->code}} | {{$pro->bike}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="qty">{{ __('lang.Quantity') }}</label>
                            <input type="number" required name="qty" id="qty" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="price">{{ __('lang.SalePrice') }}</label>
                            <input type="number" required name="price" id="price" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="discount">Discount</label>
                            <input type="number" value="0" name="discount" id="discount" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-info" style="margin-top: 30px">{{ __('lang.AddProduct') }}</button>
                    </div>
                </div>
            </form>
                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-striped table-hover text-center mb-0" id="datatable1">
                        <thead class="th-color">
                            <tr>
                                <th class="border-top-0">{{ __('lang.Ser') }}</th>
                                <th class="border-top-0">Code</th>
                                <th class="border-top-0">{{ __('lang.Product') }}</th>
                                <th class="border-top-0">Bike</th>
                                <th class="border-top-0">Model</th>
                                <th class="border-top-0">Brand</th>
                                <th class="border-top-0">{{ __('lang.Quantity') }}</th>
                                <th class="border-top-0">{{ __('lang.Price') }}</th>
                                <th class="border-top-0">Discount</th>
                                <th class="border-top-0">{{ __('lang.Amount') }}</th>
                                <th>{{ __('lang.Action') }}</th>
                            </tr>
                        </thead>
                        <tbody id="items">

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
get_items();
$('#pro_form').submit(function(e){
    e.preventDefault();
    var data = $('#pro_form').serialize();
    $.ajax({
        method: 'get',
        url: "{{url('/sale/edit/store/')}}/{{$bill->id}}",
        data: data,
        success: function(abc){
            get_items();
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
        url: "{{url('/sale/edit/items/')}}/{{ $bill->id }}",
        success: function(respose){
            $("#items").html(respose);
        }
    });
}

function qty(id){
    var val = $("#qty"+id).val();
    $.ajax({
        method: "GET",
        url: "{{url('/sale/update/edit/qty/')}}/"+id+"/"+val,
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


function get_items(){
    $.ajax({
        method: "GET",
        url: "{{url('/sale/edit/items/')}}/{{ $bill->id }}",
        success: function(respose){
            $("#items").html(respose);
        }
    });
}

function updateDiscount(id){
    console.log("updateDiscount");
    var val = $("#discount").val();
    $.ajax({
        method: "GET",
        url: "{{url('/sale/update/discount/')}}/"+id+"/"+val,
        success: function(respose){
            get_items();
            Snackbar.show({
            text: "Discount Updated",
            duration: 3000,
            /* actionTextColor: '#fff',
            backgroundColor: '#e7515a' */
            actionTextColor: '#fff',
            backgroundColor: '#00ab55'
            });
        }
    });
}

function price(id){
    var val = $("#price"+id).val();
    $.ajax({
        method: "GET",
        url: "{{url('/sale/update/edit/price/')}}/"+id+"/"+val,
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

function discount(id){
    var val = $("#discount"+id).val();
    $.ajax({
        method: "GET",
        url: "{{url('/sale/update/edit/discount/')}}/"+id+"/"+val,
        success: function(respose){
            get_items();
            Snackbar.show({
            text: "Discount Updated",
            duration: 3000,
            actionTextColor: '#fff',
            backgroundColor: '#00ab55'
            });
        }
    });
}

function deleteEdit(id){
    $.ajax({
        method: "GET",
        url: "{{url('/sale/edit/delete/')}}/"+id,
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

function price1(){

var id = $('#product').find(":selected").val();
 $.ajax({
     method: 'get',
     url: "{{ url('/sale/getPrice/') }}/"+id,
     success: function(data){
        console.log(data);
         $("#price").val(data.price);
         $("#qty").attr("max", data.balance);

     }
 });
}
</script>
@endsection
