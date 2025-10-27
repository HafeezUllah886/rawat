@extends('layout.dashboard')

<script>

    function walkIn1() {
        var customer = $("#customer").find(':selected').val();
        if (customer == 0) {
            $('#walkIn_box').css("display", "block");
            $('#phone_box').css("display", "block");
            $('#address_box').css("display", "block");

        } else {
            $('#walkIn_box').css("display", "none");
            $('#phone_box').css("display", "none");
            $('#address_box').css("display", "none");
        }

    }
   
</script>
@php
        App::setLocale(auth()->user()->lang);
    @endphp
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h4>{{__('lang.Quotation')}}</h4>
                    <button class="btn btn-success" data-toggle="modal" data-target="#modal">{{__('lang.CreateNew')}}</button>
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
                                    <th class="border-top-0">{{__('lang.Date')}}</th>
                                    <th class="border-top-0">{{__('lang.Customer')}}</th>
                                    <th class="border-top-0">{{__('lang.Products')}}</th>
                                    <th class="border-top-0">{{__('lang.Discount')}}</th>
                                    <th class="border-top-0">{{__('lang.ValidTill')}}</th>
                                    <th class="border-top-0">{{__('lang.Amount')}}</th>
                                    <th>{{__('lang.Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($quots as $quot)
                                    <tr>
                                        <td>{{$quot->date}}</td>
                                        <td>{{$quot->customer_account->title ?? $quot->walkIn . " (Walk-In)"}}</td>
                                        <td>
                                            <table class="w-100">
                                                <th>{{__('lang.Product')}}</th>
                                                <th>{{__('lang.Qty')}}</th>
                                                <th>{{__('lang.Price')}}</th>
                                                <th>Discount</th>
                                                <tbody>
                                                    @foreach ($quot->details as $details)
                                                    <tr>
                                                        <td>{{$details->product1->name}}</td>
                                                        <td>{{$details->qty}}</td>
                                                        <td>{{$details->price}}</td>
                                                        <td>{{$details->discount}}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </td>
                                        <td>{{ $quot->discount }}</td>
                                        <td>{{$quot->validTill}}</td>
                                        <td>
                                            @php
                                                $amount = 0;
                                                foreach ($quot->details as $detail) {
                                                    $price = $detail->price - $detail->discount;
                                                    $amount += $detail->qty * $price;
                                                }
                                                echo $amount;
                                            @endphp
                                        </td>
                                        <td>
                                            <a href="{{ url('quotation/details/') }}/{{$quot->ref}}" class="btn btn-success">{{__('lang.Details')}}</a>
                                            <a href="{{ url('quotation/delete/') }}/{{$quot->ref}}" class="btn btn-danger">Delete</a>
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
                    <h5 class="modal-title">Create Quotation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="customer">{{__('lang.Customer')}}</label>
                            <select name="customer" id="customer" onchange="walkIn1()" class="select2" required>
                                <option value="0"></option>
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->title }} ({{ $account->type }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" id="walkIn_box">
                            <label for="">{{__('lang.CustomerName')}}</label>
                            <input type="text" name="walkIn" class="form-control">
                        </div>
                        <div class="form-group" id="phone_box">
                            <label for="phone">{{__('lang.PhoneNumber')}}</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="form-group" id="address_box">
                            <label for="address">{{__('lang.Address')}}</label>
                            <input type="text" name="address" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="date">{{__('lang.Date')}}</label>
                            <input type="datetime-local" name="date" required id="date" value="{{ now() }}"
                                class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="valid">{{__('lang.ValidTill')}}</label>
                            <input type="date" name="valid" required id="valid" required class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="desc">{{__('lang.Desc')}}</label>
                            <textarea name="desc" id="desc" class="form-control"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{__('lang.Create')}}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('lang.Close')}}</button>
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
            "bSort": true,
            "bLengthChange": true,
            "bPaginate": true,
            "bFilter": true,
            "bInfo": true,
        });
        walkIn1();
    </script>
@endsection
