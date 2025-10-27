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
                <h4>{{ __('lang.EditPurchase') }}</h4>
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
                                <td>{{ __('lang.BillNo') }} <strong>{{ $bill->id }}</strong></td>
                                <td>{{ __('lang.Date') }}: (<strong>{{ date('d M Y', strtotime($bill->date)) }}</strong>)</td>
                                <td>{{ __('lang.Vendor') }}: <strong>@if (@$bill->vendor_account->title)
                                    {{ @$bill->vendor_account->title }} ({{  @$bill->vendor_account->type }})

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
                            <select name="product" required id="product" class="select2">
                                <option value=""></option>
                                @foreach ($products as $pro)
                                    <option value="{{ $pro->id }}">{{ $pro->name }} | {{$pro->code}} | {{$pro->bike}}</option>
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
                            <label for="rate">{{ __('lang.PurchasePrice') }}</label>
                            <input type="number" required name="rate" id="rate" class="form-control">
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
                                <th class="border-top-0">{{ __('lang.Product') }}</th>
                                <th class="border-top-0">{{ __('lang.Size') }}</th>
                                <th class="border-top-0">{{ __('lang.Quantity') }}</th>
                                <th class="border-top-0">{{ __('lang.Price') }}</th>
                                <th class="border-top-0">{{ __('lang.Amount') }}</th>
                                <th>{{ __('lang.Action') }}</th>
                            </tr>
                        </thead>
                        <tbody id="items">

                        </tbody>
                    </table>
                    {{-- <form method="post" class="mt-5">
                        @csrf
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input type="date" name="date" value="{{ $bill->date }}" id="date" class="form-control">
                                    @error('date')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="vendor">Select Vendor</label>
                                    <select name="vendor" id="vendor" class="select2">
                                        <option value=""></option>
                                        @foreach ($vendors as $vendor)
                                            <option value="{{ $vendor->id }}" {{ $bill->vendor_account->id == $vendor->id ? 'Selected' : ''}}>{{ $vendor->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('vendor')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="isPaid">is Paid</label>
                                    <select name="isPaid" id="isPaid" onchange="abc()" class="form-control">
                                        <option {{ $bill->isPaid == "Yes" ? 'Selected' : ''}}>Yes</option>
                                        <option {{ $bill->isPaid == "No" ? 'Selected' : ''}}>No</option>
                                        <option {{ $bill->isPaid == "Partial" ? 'Selected' : ''}}>Partial</option>
                                    </select>
                                    @error('isPaid')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2" id="amount_box">
                                <div class="form-group">
                                    <label for="amount">Amount</label>
                                    <input type="number" name="amount" value="{{ $bill->amount }}" id="amount" class="form-control">
                                    @error('amount')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3" id="paidIn_box">
                                <div class="form-group">
                                    <label for="paidFrom">Paid From</label>
                                    <select name="paidFrom" id="paidFrom" class=" select2">
                                        <option></option>
                                        @foreach ($paidFroms as $acct)
                                            <option value="{{ $acct->id }}" {{ @$bill->account->id == $acct->id ? 'Selected' : ''}}>{{ $acct->title }}</option>
                                        @endforeach

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
                                    <label for="desc">Description</label>
                                    <textarea name="desc" id="desc" class="form-control">{{ $bill->desc }}</textarea>
                                    @error('amount')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 ">
                                    <button type="submit" class="btn btn-success btn-lg" style="margin-top: 30px">Update</button>

                            </div>
                        </div>
                    </form> --}}
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
        url: "{{url('/purchase/edit/store/')}}/{{$bill->id}}",
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
        url: "{{url('/purchase/edit/items/')}}/{{ $bill->id }}",
        success: function(respose){
            $("#items").html(respose);
        }
    });
}

function qty(id){
    var val = $("#qty"+id).val();
    $.ajax({
        method: "GET",
        url: "{{url('/purchase/update/edit/qty/')}}/"+id+"/"+val,
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
        url: "{{url('/purchase/update/edit/rate/')}}/"+id+"/"+val,
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

function deleteEdit(id){
    $.ajax({
        method: "GET",
        url: "{{url('/purchase/edit/delete/')}}/"+id,
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
