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
                <h4>{{ __('lang.Products') }}</h4>
                <div class="d-flex justify-content-end">
                    @php
                    $currentMonth = date('Y-m');
                    $firstDateOfMonth = date('Y-m-01', strtotime($currentMonth));
                    $lastDateOfMonth = date('Y-m-t', strtotime($currentMonth));
                    @endphp
                    <a href="{{ url('/profit') }}/{{ $firstDateOfMonth }}/{{ $lastDateOfMonth }}" class="btn btn-info mr-2">{{ __('lang.Profit/Loss') }}</a>
                    <a href="{{ url('/products/trashed') }}" class="btn btn-dark mr-2">{{ __('lang.Trashed') }}</a>
                    <button class="btn btn-success mr-2" data-toggle="modal" data-target="#modal">{{ __('lang.CreateNew') }}</button>
                    {{-- <button class="btn btn-primary" data-toggle="modal" data-target="#importModal">Import</button> --}}
                </div>

            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white m-b-30">
            <div class="card-body table-responsive new-user">
                <div class="">
                    <table class="table table-bordered table-striped table-hover text-center mb-0" id="datatable1">
                        <thead class="th-color">
                            <tr>
                                <th class="border-top-0">{{ __('lang.Ser') }}</th>
                                <th class="border-top-0">Image</th>
                                <th class="border-top-0">{{ __('lang.Product') }}</th>
                                <th class="border-top-0">Code</th>
                                <th class="border-top-0">Bike</th>
                                <th class="border-top-0">Model</th>
                                <th class="border-top-0">Rak No</th>
                                <th class="border-top-0">Stock</th>
                                <th class="border-top-0">Price</th>
                                <th class="border-top-0">Wholesale</th>
                                <th class="border-top-0">Alert</th>
                                <th class="border-top-0">Status</th>
                                <th>{{ __('lang.Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $ser = 0;
                            @endphp
                            @foreach ($products as $pro)
                            @php
                            $ser += 1;
                            @endphp
                            <tr>
                                <td> {{ $ser }} </td>
                                <td>
                                    <img src="{{ asset($pro->pic) }}" width="100%">
                                </td>
                                <td>{{ $pro->name }}</td>
                                <td>{{ $pro->code }}</td>
                                <td>{{ $pro->bike }}</td>
                                <td>{{ $pro->model }}</td>
                                <td>{{ $pro->brand }}</td>
                                <td>{{ $pro->stock }}</td>
                                <td>{{ $pro->price }}</td>
                                <td>{{ $pro->wholesale }}</td>
                                <td>{{ $pro->alert }}</td>
                                <td><a href="{{url('/product/status/')}}/{{$pro->id}}" class="badge {{$pro->status == "Active" ? "bg-success" : "bg-danger"}} text-white">{{$pro->status}}</a></td>
                                <td>

                                    <button onclick='edit_pro({{ $pro->id }})' class="btn btn-primary">Edit</button>
                                   {{--  <a href="{{ url('/product/delete/') }}/{{ $pro->id }}" class="btn btn-danger">Delete</a> --}}
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
<div class="modal" id="edit" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('lang.EditProduct') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('/product/edit') }}"  enctype="multipart/form-data" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name">{{ __('lang.Product') }}</label>
                                <input type="text" required id="edit_name" name="name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="code">Code</label>
                                <input type="text" name="code" id="edit_code" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bike">Bike</label>
                                <input type="text" name="bike" id="edit_bike" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="model">Model</label>
                                <input type="text" name="model" id="edit_model" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="brand">Rak No</label>
                                <input type="text" name="brand" id="edit_brand" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="alert">Alert Qty</label>
                                <input type="number" required id="edit_alert" name="alert" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="price">Price</label>
                                <input type="text" name="price" id="edit_price" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="wholesale">Wholesale</label>
                                <input type="number" required id="edit_wholesale" name="wholesale" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="file" name="image" class="form-control" id="edit_image">
                            </div>
                        </div>
                    </div>
            </div>
            <input type="hidden" id="edit_id" name="id">
            <div class="modal-footer">
                <button type="submit" class="btn btn-info">{{ __('lang.Save') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('lang.Close') }}</button>
            </div>
            </form>
        </div>
    </div>
</div>

{{-- Model Starts Here --}}
<div class="modal" id="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('lang.AddNewProduct') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post"  enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name">{{ __('lang.Product') }}</label>
                                <input type="text" required name="name" id="name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="code">Code</label>
                                <input type="text" name="code" id="code" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bike">Bike</label>
                                <input type="text" name="bike" id="bike" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="model">Model</label>
                                <input type="text" name="model" id="model" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="brand">Rak No</label>
                                <input type="text" name="brand" id="brand" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="alert">Alert Qty</label>
                                <input type="number" required name="alert" id="alert" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="file" name="image" class="form-control" id="image">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{ __('lang.Add') }}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('lang.Close') }}</button>
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
        "bSort": true
        , "bLengthChange": true
        , "bPaginate": true
        , "bFilter": true
        , "bInfo": true,

    });

    function edit_pro(id) {
        $.ajax({
            method: 'get',
            url: "{{url('/products/get_pro')}}",
            data: {
                id: id
            },
             success: function(abc) {
                $('#edit_name').val(abc.pro['name']);
                $('#edit_code').val(abc.pro['code']);
                $('#edit_bike').val(abc.pro['bike']);
                $('#edit_brand').val(abc.pro['brand']);
                $('#edit_model').val(abc.pro['model']);
                $('#edit_price').val(abc.pro['price']);
                $('#edit_wholesale').val(abc.pro['wholesale']);
                $('#edit_alert').val(abc.pro['alert']);
                $('#edit_id').val(abc.pro['id']);
                $('#edit').modal('show');
            }
        });
    }
</script>
@endsection
