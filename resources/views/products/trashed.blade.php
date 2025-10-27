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
                <h4>{{__('lang.DeletedProducts')}}</h4>
                <div class="d-flex justify-content-end">
                    <a href="{{ url('/products') }}" class="btn btn-dark mr-2" >{{__('lang.GoBack')}}</a>
                </div>

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
                                <th class="border-top-0">{{__('lang.Ser')}}</th>
                                <th class="border-top-0">{{__('lang.Product')}}</th>
                                <th class="border-top-0">{{__('lang.Size')}}</th>
                                <th>{{__('lang.Action')}}</th>
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
                                <td>{{ $pro->name }}</td>
                                <td>{{ $pro->size }}</td>

                                <td>
                                    <a href="{{ url('/product/restore/') }}/{{ $pro->id }}" class="btn btn-success">Restore</a>
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
    $('#datatable1').DataTable({
        "bSort": true
        , "bLengthChange": true
        , "bPaginate": true
        , "bFilter": true
        , "bInfo": true,

    });

</script>
@endsection
