@php
    App::setLocale(auth()->user()->lang);
@endphp
@extends('layout.dashboard')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>{{ __('lang.TodaySale') }}</h4>
                <a href="{{url('/sale')}}" class="btn btn-success">{{ __('lang.CreateNew') }}</a>
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
                                <th class="border-top-0">{{ __('lang.Customer') }}</th>
                                <th class="border-top-0">{{ __('lang.TotalAmount') }}</th>
                                <th class="border-top-0">{{ __('lang.Details') }}</th>
                                <th class="border-top-0">{{ __('lang.AmountPaid') }}</th>
                                <th class="border-top-0">{{ __('lang.Payment') }}</th>
                                <th class="border-top-0">{{ __('lang.PaidIn') }}</th>
                                <th>{{ __('lang.Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($history as $bill)
                            <tr>
                                <td> {{ $bill->id }} </td>
                                <td>{{ $bill->date }}</td>
                                <td>@if (@$bill->customer_account->title)
                                    {{ @$bill->customer_account->title }} ({{  @$bill->customer_account->type }})

                                @else
                                {{$bill->walking}} (Walk In)

                                @endif</td>

                                <td>{{ getSaleBillTotal($bill->id) }}</td>
                                <td>
                                    <table class="table">
                                        <th>{{ __('lang.Product') }}</th>
                                        <th>{{ __('lang.Size') }}</th>
                                        <th>{{ __('lang.Qty') }}</th>
                                        <th>{{ __('lang.Price') }}</th>
                                        <th>{{ __('lang.Amount') }}</th>
                                        @foreach ($bill->details as $data1)
                                        @php
                                            $subTotal = $data1->qty * $data1->price;
                                        @endphp
                                            <tr>
                                                <td>{{$data1->product->name}}</td>
                                                <td>{{$data1->product->size}}</td>
                                                <td>{{$data1->qty}}</td>
                                                <td>{{round($data1->price,0)}}</td>
                                                <td>{{$subTotal}}</td>
                                            </tr>
                                        @endforeach

                                    </table>
                                    <strong>{{ __('lang.Discount') }}: </strong>{{$bill->discount ?? '0'}}

                                </td>
                                <td>@if($bill->isPaid == 'Yes') {{ "Full Payment" }} @elseif($bill->isPaid == 'No') {{ "UnPaid" }} @else {{ $bill->amount }} @endif</td>
                                <td>{{ $bill->isPaid}}</td>
                                <td>{{ @$bill->account->title}}</td>
                                <td>
                                    <a href="{{ url('/sale/print/') }}/{{ $bill->ref }}" class="text-primary"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></a>
                                    <a href="{{ url('/sale/edit/') }}/{{ $bill->id }}" class="text-info"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>
                                    <a href="{{ url('/sale/delete/') }}/{{ $bill->ref }}" class="text-danger confirmation"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>

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
</script>

@endsection
