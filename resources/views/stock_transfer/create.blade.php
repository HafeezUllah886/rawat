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
                <h4>Create Stock Transfer</h4>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white m-b-30">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 p-1">
                        <div class="form-group">
                            <label for="product">{{__('lang.SelectProduct')}}</label>
                            <select name="product" id="product" onchange="getSingleProduct()" class="select2">
                                <option value=""></option>
                                @foreach ($products as $pro)
                                    <option value="{{ $pro->id }}">
                                        {{$pro->code}} | {{ $pro->name }} | {{$pro->bike}} | {{$pro->brand}} | {{$pro->model}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <form method="post" action="{{ url('/stocktransfer/store') }}">
                        <input type="hidden" name="from" value="{{ $from }}">
                        <input type="hidden" name="to" value="{{ $to }}">
                        <input type="hidden" name="date" value="{{ $date }}">
                        @csrf
                    <table class="table table-bordered table-striped table-hover text-center mb-0" id="datatable1">
                        <thead class="th-color">
                            <tr>
                                <th class="border-top-0">Product</th>
                                <th class="border-top-0">Bike</th>
                                <th class="border-top-0">Brand</th>
                                <th class="border-top-0">Model</th>
                                <th class="border-top-0">Stock Available</th>
                                <th class="border-top-0">Quantity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="items">

                        </tbody>
                    </table>
                        <div class="row">
                            <div class="col-12 d-flex justify-content-end ">
                                <button type="submit" class="btn btn-success btn-lg" style="margin-top: 30px">{{__('lang.Save')}}</button>
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
    });

    var existingProducts = [];
    function getSingleProduct()
            {
                var id = $('#product').find(':selected').val();
                var productsListHTML = '';
                $.ajax({
                url : "{{ url('/stocktransfer/getSingleProduct/') }}/"+id+"/{{ $from }}",
                method : "GET",
                success : function (data)
                {
                    if (!existingProducts.includes(data.product.id))
                    {
                        if(data.stock !== 0)
                        {
                            productsListHTML += '<tr id="row_'+data.product.id+'">';
                                productsListHTML += '<td><p>'+data.product.name + '</p></td>';
                                productsListHTML += '<td><p>'+ data.product.bike + '</p></td>';
                                productsListHTML += '<td><p>'+ data.product.brand +'</p></td>';
                                productsListHTML += '<td><p>'+ data.product.model + '</p></td>';
                                productsListHTML += '<td><p>'+ data.stock + '</p></td>';
                                productsListHTML += '<td>';
                                    productsListHTML += '<div class="input-group mb-3">';
                                        productsListHTML += '<span class="input-group-text btn btn-danger btn-sm" onclick="decreaseQty('+data.product.id+')">-</span>';
                                        productsListHTML += '<input type="number" name="qty[]" max="'+data.stock+'" required oninput="updateQty('+data.product.id+')" id="qty_'+data.product.id+'" class="form-control form-control-sm text-center" value="1">';
                                        productsListHTML += '<span class="input-group-text btn btn-success btn-sm" onclick="increaseQty('+data.product.id+')">+</span>';
                                    productsListHTML += '</div>';
                                productsListHTML += '</td>';
                                productsListHTML += '<td><span class="btn btn-danger btn-sm" onclick="deleteRow('+data.product.id+')">X</span></td>';
                                productsListHTML += '<input type="hidden" value="'+data.product.id+'" name="id[]">';
                            productsListHTML += '</tr>';
                            $("#items").prepend(productsListHTML);
                            existingProducts.push(data.product.id);
                        }
                        else
                        {
                            alert("Stock Not Available");
                        }
                    }
                    else
                    {
                        var existingQty = $("#qty_"+id).val();
                        existingQty++;
                        $("#qty_"+id).val(existingQty);
                        updateQty(id);
                    }
                }
            });
            }

            function updateQty(id){
        $("input[id^='qty_']").each(function() {
                var $input = $(this);
                var currentValue = parseInt($input.val());
                var maxAttributeValue = parseInt($input.attr("max"));

                if (currentValue > maxAttributeValue) {
                    alert(maxAttributeValue+ " Available in stock");
                    $input.val(maxAttributeValue);
                }
                if (currentValue < 1) {
                    $input.val(1);
                }
            });
        var existingQty = $("#qty_"+id).val();
        var amount = existingQty * $("#price_"+id).val();
    }

    function increaseQty(id)
    {
        var existingQty = $("#qty_"+id).val();
        existingQty++;
        $("#qty_"+id).val(existingQty);
        updateQty(id);
    }
    function decreaseQty(id)
    {
        var existingQty = $("#qty_"+id).val();
        existingQty--;
        $("#qty_"+id).val(existingQty);
        updateQty(id);
    }

    function deleteRow(id){
        $("#row_"+id).remove();
        existingProducts = $.grep(existingProducts, function(value) {
                return value !== id;
            });
    }
</script>
@endsection
