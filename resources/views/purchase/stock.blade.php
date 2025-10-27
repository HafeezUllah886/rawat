@extends('layout.dashboard')

@section('content')
@php
        App::setLocale(auth()->user()->lang);
    @endphp
<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div class="col-md-9">
                    <h4>{{ __('lang.AvailableStock') }}</h4>
                </div>
                <div class="col-md-3">
                   @if (auth()->user()->role == 1)
                   <select name="warehouse" id="warehouse" onchange="update()" class="form-control">
                    <option value="0">All</option>
                    @foreach ($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}" {{ $warehouse->id == $ware ? "Selected" : ""}}>{{ $warehouse->name }}</option>
                    @endforeach
                    </select>
                   @else
                   <select name="warehouse" id="warehouse" onchange="update()" class="form-control">
                    <option value="{{auth()->user()->warehouseID}}">{{auth()->user()->warehouse->name}}</option>
                    </select>
                   @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white m-b-30">
            <div class="card-body new-user">
                    <table class="table table-bordered table-striped table-hover text-center mb-0" id="datatable">
                        <thead class="th-color">
                            <tr>
                                <th class="border-top-0">{{ __('lang.Ser') }}</th>
                                <th class="border-top-0">{{ __('lang.Product') }}</th>
                                <th class="border-top-0">Code</th>
                                <th class="border-top-0">Bike</th>
                                <th class="border-top-0">Model</th>
                                <th class="border-top-0">Rak No</th>
                                <th class="border-top-0">Avail Stock</th>
                                @if(auth()->user()->role == 1)
                                <th class="border-top-0">Purchase</th>
                                @endif
                                <th class="border-top-0">Retail</th>
                                @if(auth()->user()->role == 1)
                                <th class="border-top-0">Wholesale</th>
                                <th class="border-top-0">{{ __('lang.StockValue') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $ser = 0;
                            $total = 0;
                            @endphp
                            @foreach ($data as $item)
                            @php
                                $ser += 1;
                                $total += $item['value'];
                            @endphp
                            <tr>
                                <td> {{ $ser }} </td>
                                <td>{{$item['product']}}</td>
                                <td>{{$item['code']}}</td>
                                <td>{{$item['bike']}}</td>
                                <td>{{$item['model']}}</td>
                                <td>{{$item['brand']}}</td>
                                <td>{{$item['balance']}}</td>
                                @if(auth()->user()->role == 1)
                                <td>{{$item['price']}}</td>
                                @endif
                                <td>{{$item['retail']}}</td>
                                @if(auth()->user()->role == 1)
                                <td>{{$item['wholesale']}}</td>
                                <td>{{$item['value']}}</td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                        @if(auth()->user()->role == 1)
                            <tfoot>
                                <tr>
                                    <td colspan="10" style="text-align: right;"> <strong>Total</strong> </td>
                                    <td style="text-align: center;"> <strong>{{ $total }}</strong> </td>
                                </tr>
                            </tfoot>
                        @endif
                        
                    </table>
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

   function update()
   {
    var warehouse = $("#warehouse").find(":selected").val();
    window.open("{{ url('/stock/') }}/"+warehouse, "_self");
   }
</script>

@endsection
