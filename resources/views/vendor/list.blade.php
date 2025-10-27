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
                <h4>{{ __('lang.Vendors') }}</h4>
                <div class="d-flex justify-content-end">
                <button class="btn btn-success mr-2" data-toggle="modal" data-target="#modal">{{ __('lang.CreateNew') }}</button>
                <button class="btn btn-primary" data-toggle="modal" data-target="#importModal">Import</button>
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
                                <th class="border-top-0">{{ __('lang.Ser') }}</th>
                                <th class="border-top-0">{{ __('lang.Title') }}</th>
                                <th class="border-top-0">{{ __('lang.PhoneNumber') }}</th>
                                <th class="border-top-0">{{ __('lang.Address') }}</th>
                                <th class="border-top-0">{{ __('lang.Balance') }}</th>
                                <th>{{ __('lang.Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $ser = 0;
                            @endphp
                            @foreach ($accounts as $account)
                            @php
                            $ser += 1;
                            @endphp
                            <tr>
                                <td> {{ $ser }} </td>
                                <td>{{ $account->title }}</td>
                                <td>{{ $account->phone }}</td>
                                <td>{{ $account->address }}</td>
                                <td> {{ getAccountBalance($account->id) }}</td>
                                <td class="text-left">
                                    <button onclick='edit_cat({{ $account->id }}, "{{ $account->title }}", "{{ $account->phone }}", "{{ $account->address  }}")' class="btn btn-primary">{{ __('lang.Edit') }}</button>
                                    <a href="{{ url('accounts/statement/') }}/{{ $account->id }}" class="btn btn-info">{{ __('lang.ViewStatement') }}</a>
                                    {{-- @if(getAccountBalance($account->id) == 0)
                                    <a href="{{ url('/account/delete/') }}/{{ $account->id }}" class="btn btn-danger confirmation">Delete</a>
                                    @endif --}}
                                        <button class="btn btn-success" onclick="tran({{ $account->id }})">{{ __('lang.Transfer') }}</button>
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
<div class="modal" id="importModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Vendors</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" enctype="multipart/form-data" action="{{ url('/account/vendor/import') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file">Select Excel File | <a href="{{url('assets/accounts_sample.xlsx')}}">Download sample file </a></label>
                        <input type="file" required name="file" id="file" accept=".xlsx" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('lang.Close') }}</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Model Starts Here --}}
<div class="modal" id="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('lang.AddNewVendor') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action={{ url('/accounts/Vendor') }}>
                @csrf
                <div class="modal-body">

                    <div class="form-group">
                        <label for="title">{{ __('lang.Title') }}</label>
                        <input type="text" required name="title" id="title" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="phone">{{ __('lang.PhoneNumber') }}</label>
                        <input type="text" name="phone" id="phone" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="address">{{ __('lang.Address') }}</label>
                        <input type="text" name="address" id="address" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="amount">{{ __('lang.InitialAmount') }}</label>
                        <input type="number" required min="0" name="amount" value="0" id="amount" class="form-control">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('lang.Create') }}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('lang.Close') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Model Starts Here --}}
<div class="modal" id="edit" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('lang.EditVendor') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

                <div class="modal-body">
                    <form action="{{ url('/account/edit/Vendor') }}" method="post">
                        @csrf
                    <div class="form-group">
                        <label for="edit_title">{{ __('lang.Title') }}</label>
                        <input type="text" required id="edit_title"  name="title" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="edit_phone">{{ __('lang.PhoneNumber') }}</label>
                        <input type="text" id="edit_phone"  name="phone" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="edit_address">{{ __('lang.Address') }}</label>
                        <input type="text" id="edit_address"  name="address" class="form-control">
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

{{-- Transfer Model Starts Here --}}
<div class="modal" id="tran_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('lang.CreateTransfer') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{ url('/transfer') }}">
                @csrf
                <div class="modal-body">

                        <input type="hidden" name="to" id="to" value="" >

                    <div class="form-group">
                        <label for="from">{{ __('lang.From') }}</label>
                        <select name="from" id="from" class="select2" required>
                            <option value=""></option>
                            @foreach ($to_accounts as $account)
                               <option value="{{ $account->id }}">{{ $account->title }} ({{ $account->type }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="cat">{{ __('lang.Amount') }}</label>
                       <input type="number" required name="amount" id="amount" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="date">{{ __('lang.Date') }}</label>
                       <input type="datetime-local" name="date" id="date" value="{{ now() }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="desc">{{ __('lang.Desc') }}</label>
                        <textarea name="desc" id="desc" class="form-control"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('lang.Transfer') }}</button>
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

    function edit_cat(id, title, phone, address) {
        $('#edit_title').val(title);
        $('#edit_phone').val(phone);
        $('#edit_address').val(address);
        $('#edit_id').val(id);
        $('#edit').modal('show');
    }

    function tran(id) {
        $('#to').val(id);
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
