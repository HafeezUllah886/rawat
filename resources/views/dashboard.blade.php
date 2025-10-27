@extends('layout.dashboard')

@section('content')
<style>
    td {
        font-size: 15px !important
    }
    table-responsive {
        height: 600px ! important overflow:scroll;
    }
</style>

<div class="card">
    {{-- Top card section --}}
    <div class="card-body">
        <div class="row">
            <div class="col-xl-3 col-md-3 mt-3">
                <div class="card border-left-info shadow  py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1"> <a class="text-danger" href="{{ url('/dashboard/customer_dues') }}">{{ __('lang.CustomerDues') }}</a> </div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="info_label h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                            {{ customerDues() }}
                                        </div>

                                    </div>

                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fa fa-users fa-2x text-red text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-3 mt-3">
                <div class="card border-left-info shadow  py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1"><a class="text-warning" href="{{ url('/dashboard/CashBook/') }}/{{ date('Y-m-d') }}">{{ __('lang.DailyCashBook') }}</a></div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="info_label h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                            {{ totalCash() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fa fa-user fa-2x text-warning text-red-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-3 mt-3">
                <div class="card border-left-info shadow  py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1"><a class="text-success" href="{{ url('/dashboard/today_sale') }}"> {{ __('lang.TodaySale') }}</a></div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div class="info_label h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                {{ todaySale(); }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa fa-list-alt fa-2x text-success"></i>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-3 mt-3">
                <div class="card border-left-info shadow  py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1"><a class="text-danger" href="{{ url('/dashboard/today_expense') }}">{{ __('lang.TodayExpense') }}</a></div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div class="info_label h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                {{ todayExpense() }}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa fa-usd fa-2x text-danger"></i>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-3 mt-3">
                <div class="card border-left-info shadow  py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1"><a class="text-info" href="{{ url('/dashboard/total_cash') }}">{{ __('lang.TotalCash') }}</a></div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div class="info_label h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                {{ totalCash() }}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa fa-money fa-2x text-red text-info"></i>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-3 mt-3">
                <div class="card border-left-info shadow  py-2">

                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1"><a class="text-info" href="{{ url('/dashboard/today_cash') }}">{{ __('lang.TodayCash') }}</a></div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div class="info_label h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                {{ todayCash() }}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i style="color: purple" class="fa fa-money fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-3 mt-3">
                <div class="card border-left-info shadow  py-2">

                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1"><a class="text-info" href="{{ url('/dashboard/total_bank') }}">{{ __('lang.TotalBank') }}</a></div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div class="info_label h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                {{ totalBank() }}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i style="color: blue" class="fa fa-university fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>

                </div>
            </div>
            <div class="col-xl-3 col-md-3 mt-3">
                <div class="card border-left-info shadow  py-2">

                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1"><a class="text-info" href="{{ url('/dashboard/today_bank') }}">{{ __('lang.TodayBank') }}</a></div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div class="info_label h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                {{ todayBank() }}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i style="color: green" class="fa fa-university fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>

                </div>
            </div>

        </div>
    </div>

    {{-- End Top card section --}}

    <div class="container-fluid">
        <div class="row">

            <div class="col-md-6">
                <h5 class="text-danger">{{ __('lang.LedgerDetails')}}</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover text-center" id="datatable1">
                        <thead class="th-color">
                                <th>{{ __('lang.Ser') }}</th>
                                <th>{{ __('lang.Date') }}</th>
                                <th>{{ __('lang.Head') }}</th>
                                <th>{{ __('lang.PaymentType') }}</th>
                                <th>{{ __('lang.Details') }}</th>
                                <th>{{ __('lang.Amount') }}</th>
                        </thead>
                        <tbody>
                           @foreach ($ledger as $item)
                                <tr>
                                    <td>{{ $item->id}}</td>
                                    <td>{{ $item->date }}</td>
                                    <td>{{ $item->head }}</td>
                                    <td>{{ $item->type }}</td>
                                    <td>{{ $item->details }}</td>
                                    <td>{{ $item->amount }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <a href="{{ url('/dashboard/ledgerDetails') }}" class="btn btn-success">{{ __('lang.Details') }}</a>
                </div>
                {{--
                    <h5 class="text-danger">Cash Ledger</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover text-center" id="datatable1">
                        <thead class="th-color">
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Account</th>
                                <th>Description</th>
                                <th>Credit</th>
                                <th>Debit</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $cash_balance = 0;
                            @endphp
                            @foreach ($cashs as $cash)
                            @php
                                $cash_balance += $cash->cr;
                                $cash_balance -= $cash->db;
                            @endphp
                                <tr>
                                    <td>{{ $cash->id}}</td>
                                    <td>{{ $cash->date }}</td>
                                    <td>{{ $cash->account->title }}</td>
                                    <td>{!! $cash->desc !!}</td>
                                    <td>{{ round($cash->cr,0) }}</td>
                                    <td>{{ round($cash->db,0) }}</td>
                                    <td>{{ round($cash_balance,0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>  --}}
            </div>

            <div class="col-md-6">
                <h5 class="text-danger">{{ __('lang.Income&ExpenseDetails') }}</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover text-center" id="datatable2">
                        <thead class="th-color">
                            <tr>
                                <th>{{ __('lang.Ser') }}</th>
                                <th>{{ __('lang.Date') }}</th>
                                <th>{{ __('lang.Account') }}</th>
                                <th>{{ __('lang.Desc') }}</th>
                                <th>{{ __('lang.Credit') }}</th>
                                <th>{{ __('lang.Debit') }}</th>
                                <th>{{ __('lang.Balance') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $balance = 0;
                            @endphp
                            @foreach ($trans1 as $tran)
                            @php
                                $balance += $tran->cr;
                                $balance -= $tran->db;
                            @endphp
                                <tr>
                                    <td>{{ $tran->id}}</td>
                                    <td>{{ $tran->date }}</td>
                                    <td>{{ $tran->account->title }}</td>
                                    <td>{!! $tran->desc !!}</td>
                                    <td>{{ round($tran->cr,0) }}</td>
                                    <td>{{ round($tran->db,0) }}</td>
                                    <td>{{ round($balance,0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                    <a href="{{ url('/dashboard/incomeExpenseDetails') }}" class="btn btn-success">{{ __('lang.Details') }}</a>
                </div>
            </div>
        </div>
        <br>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $('#datatable1').DataTable({
        "bSort": true,
        /* "bLengthChange": true,
        "bPaginate": true,
        "bFilter": true,
        "bInfo": true, */
        "order": [[0, 'desc']],
        'columnDefs': [
                { 'sortable': false, 'searchable': false, 'visible': false, 'type': 'num', 'targets': [0] }
                ],
    });
    $('#datatable2').DataTable({
        "bSort": true,
        /* "bLengthChange": true,
        "bPaginate": true,
        "bFilter": true,
        "bInfo": true, */
        "order": [[0, 'desc']],
        'columnDefs': [
                { 'sortable': false, 'searchable': false, 'visible': false, 'type': 'num', 'targets': [0] }
                ],
    });
    $("th").removeClass('sorting');
    $("th").removeClass('sorting_desc');

</script>

@endsection
