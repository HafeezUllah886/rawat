@extends('layout.dashboard')
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
@php
        App::setLocale(auth()->user()->lang);
    @endphp
<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>{{ __('lang.QuotationDetails') }}</h4>
                <a href="{{ url('/quotation/print/') }}/{{ $quot->ref }}" class="btn btn-warning">{{ __('lang.Print') }}</a>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <tr>
                        <td> <strong>{{ __('lang.Customer') }}:</strong>  </td>
                        <td>{{$quot->customer_account->title ?? $quot->walkIn." (Walk-in)"}}</td>
                        <td><strong>{{ __('lang.PhoneNumber') }}:</strong> </td>
                        <td>{{$quot->customer_account->phone ?? $quot->phone}}</td>
                        <td><strong>{{ __('lang.Address') }}:</strong> </td>
                        <td>{{$quot->customer_account->address ?? $quot->address}}</td>
                    </tr>
                </table>
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
                            <select name="product" onchange="price1()" required id="product" class="select2">
                                <option value=""></option>
                                @foreach ($products as $pro)
                                    <option value="{{ $pro->id }}" data-price="{{ $pro->price }}">{{ $pro->code }} |{{ $pro->name }} | {{$pro->brand}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="qty">{{ __('lang.Quantity') }}</label>
                            <input type="number" required name="qty" value="1" id="qty" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="price1">{{ __('lang.Price') }}</label>
                            <input type="number" required name="price" id="price" class="form-control">
                        </div>
                        <input type="hidden" name="id" value="{{ $quot->id }}">
                        <input type="hidden" name="ref" value="{{ $quot->ref }}">
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="discount">Discount</label>
                            <input type="number" name="discount" id="discount" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-info" style="margin-top: 30px">{{ __('lang.AddProduct') }}</button>
                    </div>
                </div>
            </form>
                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-striped table-hover text-center mb-0" id="datatable1">
                        <thead class="th-color">
                            <tr>
                                <th class="border-top-0">{{ __('lang.Ser') }}</th>
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
                    <div class="col-md-3 mt-3">
                        <div class="form-group">
                            <label for="discount">{{ __('lang.Discount') }}</label>
                            <input type="number" name="discount" value="{{ $quot->discount }}" onfocusout="updateDiscount()" class="form-control " id="discount">
                        </div>
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

   get_items();
$('#pro_form').submit(function(e){
    e.preventDefault();
    var data = $('#pro_form').serialize();
    $.ajax({
        method: 'get',
        url: "{{url('/quotation/store/')}}",
        data: data,
        success: function(abc){
            get_items();
            if(abc == 'existing')
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
        url: "{{url('/quotation/detail/list/')}}/{{$quot->ref}}",
        success: function(respose){
            console.log(respose);
            $("#items").html(respose);
        }
    });
}

function updateDiscount(){
    var discount = $("#discount").val();
$.ajax({
    method: "GET",
    url: "{{url('/quotation/updateDiscount/')}}/{{$quot->ref}}/"+discount,
    success: function(respose){
        Snackbar.show({
            text: "Discount updated",
            duration: 3000,
            /* actionTextColor: '#fff',
            backgroundColor: '#e7515a' */
            actionTextColor: '#fff',
            backgroundColor: '#00ab55'
            });
    }
});
}


function deleteList(id, quot){
    $.ajax({
        method: "GET",
        url: "{{url('/quotation/details/delete')}}/"+id+"/"+quot,
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

function qty(id){
    var val = $("#qty"+id).val();
    $.ajax({
        method: "GET",
        url: "{{url('/quotation/edit/qty/')}}/"+id+"/"+val,
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
    var discount = $("#discount"+id).val();
    $.ajax({
        method: "GET",
        url: "{{url('/quotation/edit/rate/')}}/"+id+"/"+val+"/"+discount,
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

function price1(){

var id = $('#product').find(":selected").val();

 $.ajax({
     method: 'get',
     url: "{{ url('/sale/getPrice/') }}/"+id,
     success: function(data){

        $('#price').val(data.price1);

     }
 });
}
</script>
@endsection
