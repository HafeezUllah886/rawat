@php
        App::setLocale(auth()->user()->lang);
    @endphp
@extends('layout.dashboard')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>{{ __('lang.SaleReturns') }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white">
            <div class="card-body ">
                <div class="card-header">
                    <h5>{{ __('lang.InvoiceDetails') }}</h5>
                </div>
                <div class="">
                    <table class="table table-bordered table-striped table-hover text-center mb-0">
                        <thead class="th-color">
                            <tr>
                                <th class="border-top-0">{{ __('lang.InvoiceNo') }}</th>
                                <th class="border-top-0">{{ __('lang.Customer') }}</th>
                                <th class="border-top-0">{{ __('lang.Date') }}</th>
                                <th class="border-top-0">{{ __('lang.Discount') }}</th>
                                <th class="border-top-0">{{ __('lang.Amount') }}</th>
                                <th class="border-top-0">{{ __('lang.IsPaid') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td> {{ $bill->id}} </td>
                                <td>@if (@$bill->customer_account->title)
                                    {{ @$bill->customer_account->title }}
                                @else
                                {{$bill->walking}} (Walk In)

                                @endif</td>
                                <td>{{ $bill->date }}</td>
                                <td>{{ $bill->discount ?? "0" }}</td>
                                <td id="billAmount">{{ $total }}</td>
                                <td>{{ $bill->isPaid }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white">
            <div class="card-body ">
                <div class="card-header">
                    <h5>{{ __('lang.ProductDetails') }}</h5>
                </div>
            <form method="post" action="{{url('/return/save/')}}/{{$bill->id}}">
                @csrf
                <div class="">
                    <table class="table table-bordered table-striped table-hover text-center mb-0">
                        <thead class="th-color">
                            <tr>
                                <th class="border-top-0">{{ __('lang.Product') }}</th>
                                <th class="border-top-0">{{ __('lang.Size') }}</th>
                                <th class="border-top-0">{{ __('lang.Price') }}</th>
                                <th class="border-top-0">{{ __('lang.SoldQty') }}</th>
                                <th class="border-top-0">{{ __('lang.ReturnQty') }}</th>
                                <th class="border-top-0">{{ __('lang.ReturnAmount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $ser = 0;
                            @endphp
                            @foreach ($bill->details as $products)
                            @php
                            $ser += 1;
                            @endphp
                            <tr>
                                <td> <input type="hidden" value="{{$products->product_id}}" name="id[]">{{ $products->product->name}} </td>
                                <td> <input type="hidden" value="{{$products->size}}" name="size">{{ $products->product->size}} </td>
                                <td> <input type="number" readonly class="form-control  text-center" name="price[]" value="{{$products->price - $products->discount}}" id="price{{ $ser }}"> </td>
                                <td> <input type="number" readonly class="form-control text-center" value="{{$products->qty}}" id="qty{{ $ser }}"> </td>
                                <td><input type="number" class="form-control text-center" onchange="updateAmount({{ $ser }}, {{ $products->price - $products->discount }})"  min="0" name="returnQty[]" id="returnQty{{ $ser }}" value="0" max="{{$products->qty}}"></td>
                                <td> <input type="number" class="form-control text-center" readonly id="amount{{ $ser }}" name="amount[]" required> </td>
                            </tr>

                            @endforeach
                            <tr>
                                <td colspan="4" class="text-right">Total</td>
                                <td colspan="4" class="text-center" id="totalAmount">0</td>
                            </tr>

                        </tbody>
                    </table>

                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="deduction">{{ __('lang.Deduction') }}</label>
                            <input type="number" name="deduction" value="{{ $bill->discount ?? "0" }}" onchange="updateAmount()" min="0" id="deduction" class="form-control">
                            @error('deduction')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="payable">{{ __('lang.PayableAmount') }}</label>
                            <input type="number" readonly name="payable" id="payable" class="form-control">
                            @error('payable')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="netAmount">{{ __('lang.ReturnAmount') }}</label>
                            <input type="number" {{ @$bill->customer_account->title != "" ? "" : "readonly" }} name="amount" value="0" min="0" id="netAmount" class="form-control">
                            @error('amount')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="paidFrom">{{ __('lang.PaidBy') }}</label>
                                <select name="paidFrom" id="paidFrom" class="form-control">
                                    <option value="">{{ __('lang.SelectAccount') }}</option>
                                    @foreach ($paidFroms as $acct)
                                        <option value="{{ $acct->id }}">{{ $acct->title }}</option>
                                    @endforeach
                                </select>
                                @error('paidFrom')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="date">{{ __('lang.ReturnDate') }}</label>
                            <input type="datetime-local" name="date" value="{{now()}}" class="form-control">
                            @error('date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-2">
                       <button type="submit" class="btn btn-success mt-4">{{ __('lang.Save') }}</button>
                    </div>
                </div>
            </form>
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
   /*  $(document).ready(function(){
    var total = 0;
    var count = 1;
    var priceInput, qtyInput;

    while ($('#price' + count).length > 0 && $('#qty' + count).length > 0) {
      priceInput = parseFloat($('#price' + count).val());
      qtyInput = parseFloat($('#qty' + count).val());

      if (!isNaN(priceInput) && !isNaN(qtyInput)) {
        total += priceInput * qtyInput;
      }

      count++;
    }
        $("#billAmount").html(total);
    }); */
   function updateAmount(id, price){
        var returnQty = $("#returnQty"+id).val();

        var qty = $("#qty"+id).val();
        console.log(qty);
        console.log(returnQty);
       /*  if(returnQty > qty){
            $("#returnQty"+id).val(qty);
            returnQty = qty;
        } */
        var amount = returnQty * price;
        $("#amount"+id).val(amount);
        var sum = 0;
        $('input[id^="amount"]').each(function() {
        var value = parseFloat($(this).val());
        if (!isNaN(value)) {
            sum += value;
        }
        });
        $("#totalAmount").html(sum);
        var netAmount = sum;
        var deduction = $('#deduction').val();
        $("#payable").val(netAmount - deduction);
        $("#netAmount").val(netAmount - deduction);
        $("#netAmount").attr("max", netAmount - deduction);

    }

</script>

@endsection
