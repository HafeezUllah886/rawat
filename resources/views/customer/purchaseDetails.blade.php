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
                <h4>{{ __('lang.PurchaseDetails') }}</h4>
                <a href="{{ url('/customer/purchaseDetails/pdf/') }}/{{ $invoices[0]->customer }}" class="btn btn-success">PDF</a>
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
                                <th class="border-top-0">{{ __('lang.Ser') }}</th>
                                <th class="border-top-0">{{ __('lang.Date') }}</th>
                                <th class="border-top-0">{{ __('lang.Product') }}</th>
                                <th class="border-top-0">{{ __('lang.Price') }}</th>
                                <th class="border-top-0">{{ __('lang.Quantity') }}</th>
                                <th class="border-top-0">{{ __('lang.Amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $ser = 0;
                            $amount = 0;
                            @endphp
                            @foreach ($invoices as $item)
                                @foreach ($item->details as $product)
                                @php
                                $ser += 1;
                                $amount = $product->price * $product->qty;
                                @endphp
                                <tr>
                                    <td> {{ $ser }} </td>
                                    <td>{{ date("d M Y", strtotime($item->date)) }}</td>
                                    <td>{{ $product->product->name }}</td>
                                    <td>{{ $product->price }}</td>
                                    <td>{{ $product->qty }}</td>
                                    <td>{{ $amount }}</td>
                                </tr>
                                @endforeach
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

    function edit_cat(id, title, phone, address) {
        $('#edit_title').val(title);
        $('#edit_phone').val(phone);
        $('#edit_address').val(address);
        $('#edit_id').val(id);
        $('#edit').modal('show');
    }

    function tran(id) {
        $('#from').val(id);
        $('#tran_modal').modal('show');
    }


    var elems = document.getElementsByClassName('confirmation');
    var confirmIt = function (e) {
        if (!confirm('Are you sure to delete account?')) e.preventDefault();
    };
    for (var i = 0, l = elems.length; i < l; i++) {
        elems[i].addEventListener('click', confirmIt, false);
    }

    /* function save_edit(){
        var id = $('#edit_id').val();
        var cat = $('#edit_cat').val();

        $.ajax({
            'method': 'get',
            'url': '{{ url("/category/edit/") }}/'+id+'/'+cat,
            'success' : function(data){

            }
        });
    } */
</script>
@endsection
