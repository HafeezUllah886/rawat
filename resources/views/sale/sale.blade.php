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

    function price1(){

       var id = $('#product').find(":selected").val();
        $.ajax({
            method: 'get',
            url: "{{ url('/sale/getPrice/') }}/"+id,
            success: function(data){
               $('#stock').val(data.balance);
               $('#price').val(data.price);
               $('#qty').attr('max', data.balance);
               $('#qty').val('');
            }
        });
    }

    function walkIn1(){

        var customer = $("#customer").find(':selected').val();

        if(customer == 0)
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
                <h4>{{__('lang.Sale')}}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white m-b-30">
            <div class="card-body">
                <form id="pro_form">
                <div class="row ">
                    <div class="col-md-3 p-1">
                        <div class="form-group">
                            <label for="product">{{__('lang.SelectProduct')}}</label>
                            <select name="product" required id="product" onchange="price1()" class="selectize">
                                <option value=""></option>
                                @foreach ($products as $pro)
                                @if($pro->stock > 0)
                                    <option value="{{ $pro->id }}" data-price="{{ $pro->price }}"> {{$pro->code}} | {{ $pro->name }} | {{$pro->bike}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 p-1">
                        <div class="form-group">
                            <label for="stock">{{__('lang.AvailableStock')}}</label>
                            <input type="number" disabled name="stock" id="stock" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-2 p-1">
                        <div class="form-group">
                            <label for="qty">{{__('lang.Quantity')}}</label>
                            <input type="number" required name="qty" min="1" id="qty" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-2 p-1">
                        <div class="form-group">
                            <label for="price1">{{__('lang.SalePrice')}}</label>
                            <input type="number" required name="price" id="price" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-2 p-1">
                        <div class="form-group">
                            <label for="discount">Discount</label>
                            <input type="number" id="discount" name="discount" value="0" required min="0" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-1 p-1">
                        <button type="submit" class="btn btn-info" style="margin-top: 30px">{{__('lang.Add')}}</button>
                    </div>
                </div>
            </form>
                <div class="mt-3">
                    <table class="table table-bordered table-striped table-hover text-center mb-0" id="datatable1">
                        <thead class="th-color">
                            <tr>
                                <th class="border-top-0">{{__('lang.Ser')}}</th>
                                <th class="border-top-0">Code</th>
                                <th class="border-top-0">{{ __('lang.Product') }}</th>
                                <th class="border-top-0">Bike</th>
                                <th class="border-top-0">Model</th>
                                <th class="border-top-0">Rak No</th>
                                <th class="border-top-0">{{__('lang.Quantity')}}</th>
                                <th class="border-top-0">{{__('lang.Price')}}</th>
                                <th class="border-top-0">Discount</th>
                                <th class="border-top-0">{{__('lang.Amount')}}</th>
                                <th>{{__('lang.Action')}}</th>
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
                                    <label for="date">{{__('lang.Date')}}</label>
                                    <input type="datetime-local" name="date" value="{{ now() }}" id="date" class="form-control">
                                    @error('date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="customer">{{__('lang.SelectCustomer')}}</label>
                                    <select name="customer" id="customer" onchange="walkIn1()" class="select2">
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->title }} ({{ $customer->type }})</option>
                                        @endforeach
                                    </select>
                                    @error('customer')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2" id="walkIn_box">
                                <div class="form-group">
                                    <label for="">{{__('lang.CustomerName')}}</label>
                                    <input type="text" name="walkIn" class="form-control">
                                    @error('walkIn')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2" id="isPaid_box">
                                <div class="form-group">
                                    <label for="isPaid">{{__('lang.IsPaid')}}</label>
                                    <select name="isPaid" id="isPaid" onchange="abc()" class="form-control">
                                        <option value="Yes">{{__('lang.Yes')}}</option>
                                        <option Value="No">{{__('lang.No')}}</option>
                                        <option Value="Partial">{{__('lang.Partial')}}</option>
                                    </select>
                                    @error('isPaid')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2" id="amount_box">
                                <div class="form-group">
                                    <label for="amount">{{__('lang.Amount')}}</label>
                                    <input type="number" name="amount" id="amount" class="form-control">
                                    @error('amount')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3" id="paidIn_box">
                                <div class="form-group">
                                    <label for="paidIn">{{__('lang.PaidIn')}}</label>
                                    <select name="paidIn" id="paidIn" class=" select2">
                                        @foreach ($paidIns as $acct)
                                            <option value="{{ $acct->id }}">{{ $acct->title }}</option>
                                        @endforeach

                                    </select>
                                    @error('paidIn')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2" >
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="select2">
                                        <option value="Delivered">Delivered</option>
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
                                    <label for="desc">{{__('lang.Desc')}}</label>
                                    <textarea name="desc" id="desc" class="form-control"></textarea>
                                    @error('amount')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 ">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label for="discount">{{__('lang.Discount')}}</label>
                                                <input type="number" name="discount" id="discount" class="form-control">
                                                @error('discount')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label for="transport">Transport</label>
                                                <input type="number" name="transport" id="transport" class="form-control">
                                                @error('transport')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label for="dc">Delivery Charges</label>
                                                <input type="number" name="dc" id="dc" class="form-control">
                                                @error('dc')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-success btn-lg" style="margin-top: 30px">{{__('lang.Save')}}</button>
                                    </div>
                                </div>
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

           var selectize =  $('.selectize').selectize({
            onInitialize: function() {
                this.focus();
            },
            onType: function(str) {
                if (str.slice(-1) === ' ' || str.slice(-1) === '\n') {
                if (this.currentResults.items.length === 1) {
                    var value = this.currentResults.items[0].id;
                    this.addItem(value);
                }
            }
            },
            onChange: function(value) {
                if (!value.length) return;
               $("#qty").focus(); // call the function with the selected value
            },
        });
$('#pro_form').submit(function(e){
    e.preventDefault();
    var data = $('#pro_form').serialize();
    $.ajax({
        method: 'get',
        url: "{{url('/sale/store')}}",
        data: data,
        success: function(abc){
            selectize[0].selectize.clear();
            selectize[0].selectize.focus();
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
        url: "{{url('/sale/draft/items')}}",
        success: function(respose){
            $("#items").html(respose);
        }
    });
}

function qty(id){
    var val = $("#qty"+id).val();
    $.ajax({
        method: "GET",
        url: "{{url('/sale/update/draft/qty/')}}/"+id+"/"+val,
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
        url: "{{url('/sale/update/draft/rate/')}}/"+id+"/"+val,
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
        url: "{{url('/sale/update/draft/discount/')}}/"+id+"/"+val,
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
function deleteDraft(id){
    $.ajax({
        method: "GET",
        url: "{{url('/sale/draft/delete/')}}/"+id,
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
