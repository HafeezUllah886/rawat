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
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h4>Users</h4>
                    <button class="btn btn-success" data-toggle="modal" data-target="#modal">{{ __('lang.CreateNew') }}</button>
                </div>
            </div>
            <div class="card-body">
                <table class="table" id="datatable">
                    <thead>
                        <th>#</th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Warehouse</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @foreach ($users as $key => $user)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>@if($user->role == 1) Admin @elseif($user->role == 3) Store Keeper @else Operator @endif</td>
                                <td>{{ $user->warehouse->name }}</td>
                                <td>
                                    <button onclick="edit({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->warehouseID }}')" class="btn btn-primary">{{ __('lang.Edit') }}</button>
                                    <a href="{{url('/accounts')}}" class="btn btn-info">Accounts</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Model Starts Here --}}
<div class="modal" id="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{ url('/users/store') }}">
                @csrf
                <div class="modal-body">

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" required name="name" id="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" required name="email" id="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" required name="password" id="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select name="role" id="role" class="form-control">
                            <option value="2">Operator</option>
                            <option value="3">Store Keeper</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="warehouseID">Warehouse</label>
                        <select name="warehouseID" id="warehouseID" class="form-control">
                            @foreach ($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('lang.Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('lang.Add') }}</button>
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
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

                <div class="modal-body">
                    <form action="{{ url('/users/update') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="edit_name">Name</label>
                            <input type="text" required name="name" id="edit_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="edit_email">Email</label>
                            <input type="email" required name="email" id="edit_email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" required name="password" id="password" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="edit_warehouse">Warehouse</label>
                            <select name="warehouseID" id="edit_warehouse" class="form-control">
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                </div>
                <input type="hidden" id="edit_id" name="id">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('lang.Close') }}</button>
                    <button type="submit" class="btn btn-info">{{ __('lang.Save') }}</button>
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

    function edit(id, name, email, warehouse) {
        $('#edit_name').val(name);
        $('#edit_email').val(email);
        $('#edit_warehouse').val(warehouse);
        $('#edit_id').val(id);
        $('#edit').modal('show');
    }

</script>
@endsection
