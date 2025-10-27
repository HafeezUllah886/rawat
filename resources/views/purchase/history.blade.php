@extends('layout.dashboard')
@php
        App::setLocale(auth()->user()->lang);
    @endphp
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>{{ __('lang.PurchaseHistory') }}</h4>
                <div class="w-50">
                    <div class="row">
                        <div class="col-4"> <input type="datetime-local" value="{{$from}}" onchange="refresh()" id="from" class="form-control"></div>
                        <div class="col-4"><input type="datetime-local" value="{{$to}}" onchange="refresh()" id="to" class="form-control"></div>
                        <a href="{{url('/purchase')}}" class="btn btn-success">{{ __('lang.CreateNew') }}</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white m-b-30">
            <div class="card-body table-responsive new-user">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover text-center mb-0" id="datatable">
                        <thead class="th-color">
                            <tr>
                                <th class="border-top-0">{{ __('lang.BillNo') }}</th>
                                <th class="border-top-0">{{ __('lang.Date') }}</th>
                                <th class="border-top-0">{{ __('lang.Vendor') }}</th>
                                <th class="border-top-0">Order #</th>
                                <th class="border-top-0">{{ __('lang.Details') }}</th>
                                <th class="border-top-0">{{ __('lang.Amount') }}</th>
                                <th class="border-top-0">{{ __('lang.AmountPaid') }}</th>
                                <th class="border-top-0">{{ __('lang.Payment') }}</th>
                                <th class="border-top-0">{{ __('lang.PaidBy') }}</th>
                                <th class="border-top-0">Status</th>
                                <th>{{ __('lang.Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($history as $bill)
                            <tr>
                                <td> {{ $bill->id }} </td>
                                <td>{{ $bill->date }}</td>
                                <td>@if (@$bill->vendor_account->title)
                                    {{ @$bill->vendor_account->title }} ({{  @$bill->vendor_account->type == "Vendor" ? "Vendor" : @$bill->vendor_account->type}})

                                @else
                                {{$bill->walking}} (Walk In)

                                @endif</td>
                                <td>{{$bill->order_number}}</td>

                                <td>
                                    <table class="table">
                                        <th>Code</th>
                                        <th>{{ __('lang.Product') }}</th>
                                        <th>{{ __('lang.Qty') }}</th>
                                        <th>{{ __('lang.Price') }}</th>
                                        <th>{{ __('lang.Amount') }}</th>
                                        @foreach ($bill->details as $data1)
                                        @php
                                        $subTotal = $data1->qty * $data1->rate;
                                        @endphp
                                        <tr>
                                            <td>{{@$data1->product->code}}</td>
                                            <td>{{@$data1->product->name}}</td>
                                            <td>{{@$data1->qty}}</td>
                                            <td>{{round(@$data1->rate,2)}}</td>
                                            <td>{{@$subTotal}}</td>
                                        </tr>
                                        @endforeach

                                    </table>
                                </td>
                                <td>{{ getPurchaseBillTotal($bill->id) }}</td>
                                <td>@if($bill->isPaid == 'Yes') {{ "Full Payment" }} @elseif($bill->isPaid == 'No') {{ "UnPaid" }} @else {{ $bill->amount }} @endif</td>
                                <td>{{ $bill->isPaid}}</td>
                                <td>{{ @$bill->account->title}}</td>
                                <td>
                                    @if($bill->pendings)
                                        <span class="badge badge-danger">Pending</span>
                                    @else
                                    <span class="badge badge-success">Received</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                        <div class="btn-group" role="group">
                                          <button id="btnGroupDrop1" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Action
                                          </button>
                                          <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                            @if($bill->pendings)
                                            <a class="dropdown-item text-success" href="#" data-toggle="modal" data-target="#receive_{{ $bill->id }}">Receive Products</a>
                                            @endif
                                            <a class="dropdown-item" href="{{ url('/purchase/edit/') }}/{{ $bill->id }}">Edit</a>
                                            <a class="dropdown-item text-danger" href="{{ url('/purchase/delete/') }}/{{ $bill->ref }}">Delete</a>
                                          </div>
                                        </div>
                                      </div>
                                </td>
                            </tr>
                            <!-- Modal -->
                            <div class="modal fade" id="receive_{{ $bill->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Receive Pending Products</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <form action="{{url('/purchase/receive')}}" method="post">
                                        @csrf
                                        <input type="hidden" name="purchaseID" value="{{ $bill->id }}">
                                        <div class="modal-body">
                                            <div class="row g-0">
                                                <div class="col-3"> <strong>Code</strong></div>
                                                <div class="col-4"> <strong>Product</strong></div>
                                                <div class="col-2"> <strong>Pending</strong></div>
                                                <div class="col-3"> <strong>Receive Qty</strong></div>
                                            </div>
                                           @foreach ($bill->pendings as $key => $pending)
                                           <div class="row g-0">
                                                <input type="hidden" name="product[]" value="{{ $pending['productID']}}">
                                                <input type="hidden" name="ref[]" value="{{ $pending['ref']}}">
                                                <div class="col-3">{{ $pending['code']}}</div>
                                                <div class="col-4">{{ $pending['productName']}}</div>
                                                <div class="col-2">{{ $pending['qty']}}</div>
                                                <div class="col-3"><input type="number" class="form-control" name="qty[]" value="0" min="0" max="{{ $pending['qty']}}"></div>
                                            </div>
                                           @endforeach
                                        </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Receive</button>
                                        </div>
                                    </form>
                                </div>
                                </div>
                            </div>
                            @endforeach
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
    var elems = document.getElementsByClassName('confirmation');
    var confirmIt = function (e) {
        if (!confirm('Are you sure to delete account?')) e.preventDefault();
    };
    for (var i = 0, l = elems.length; i < l; i++) {
        elems[i].addEventListener('click', confirmIt, false);
    }

    function refresh()
    {
        var from = $('#from').val();
        var to = $('#to').val();

        window.open("{{url('/purchase/history/')}}/"+from+"/"+to, "_self");
    }
</script>

@endsection
