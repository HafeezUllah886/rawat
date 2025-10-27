@php
        App::setLocale(auth()->user()->lang);
    @endphp
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5>{{ __('lang.PreviousBalance') }}</h5>
                <h4>{{ $p_balance }}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5>{{ __('lang.CurrentBalance') }}</h5>
                <h4>{{ getAccountBalance($id) }}</h4>
            </div>
        </div>
    </div>
</div>
<div class="card-body">

    <div >
        <table class="table table-bordered table-striped table-hover text-center mb-0 display" id="datatable1">
            <thead>
                <th>{{ __('lang.Ref') }}</th>
                <th>{{ __('lang.Date') }}</th>
                <th>{{ __('lang.Desc') }}</th>
                @if ($account->type != 'Business')
                    <th>{{ __('lang.Details') }}</th>
                @endif
                <th class="text-end">{{ __('lang.Credit') }}</th>
                <th class="text-end">{{ __('lang.Debit') }}</th>
                <th class="text-end">{{ __('lang.Balance') }}</th>
            </thead>
            <tbody >
                @php
                    $total_cr = 0;
                    $total_db = 0;
                    $balance = $p_balance;
                @endphp
                @foreach ($items as $item)
                @php
                    $total_cr += $item->cr;
                    $total_db += $item->db;
                    $balance -= $item->db;
                    $balance += $item->cr;
                @endphp
                <tr>
                <td>{{ $item->ref }}</td>
                <td>{{ $item->date }}</td>
                <td>{!! $item->desc !!}</td>
                @if ($account->type != 'Business')
                   <td>
                    @if ($item->type == 'Sale')
                        @php
                            $data = \App\Models\sale_details::with('product')->where('ref', $item->ref)->get();
                            $subTotal = 0;
                        @endphp
                        <table class="table">
                            <th>{{ __('lang.Product') }}</th>
                            <th>{{ __('lang.Size') }}</th>
                            <th>{{ __('lang.Qty') }}</th>
                            <th>{{ __('lang.Price') }}</th>
                            <th>{{ __('lang.Amount') }}</th>
                            @foreach ($data as $data1)
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
                        <strong>Discount: </strong>{{@$data[0]->bill->discount}}
                        <strong>Discount: </strong>{{@$data[0]->bill->transport}}
                    @endif
                    @if ($item->type == 'Sale Return')
                        @php
                            $data = \App\Models\saleReturnDetails::with('product')->where('ref', $item->ref)->get();
                            $subTotal = 0;
                        @endphp
                        <table class="table">
                            <th>{{ __('lang.Product') }}</th>
                            <th>{{ __('lang.Size') }}</th>
                            <th>{{ __('lang.Qty') }}</th>
                            <th>{{ __('lang.Price') }}</th>
                            <th>{{ __('lang.Amount') }}</th>
                            @foreach ($data as $data1)
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
                        <strong>Deduction: </strong>{{@$data[0]->returnBill->deduction}}
                    @endif
                    @if ($item->type == 'Purchase')
                    @php
                        $data = \App\Models\purchase_details::with('product')->where('ref', $item->ref)->get();
                        $subTotal = 0;
                    @endphp
                    <table class="table">
                        <th>{{ __('lang.Product') }}</th>
                        <th>{{ __('lang.Size') }}</th>
                            <th>{{ __('lang.Qty') }}</th>
                            <th>{{ __('lang.Price') }}</th>
                            <th>{{ __('lang.Amount') }}</th>
                        @foreach ($data as $data1)
                        @php
                            $subTotal = $data1->qty * $data1->rate;
                        @endphp
                            <tr>
                                <td>{{$data1->product->name}}</td>
                                <td>{{$data1->product->size}}</td>
                                <td>{{$data1->qty}}</td>
                                <td>{{round($data1->rate,2)}}</td>
                                <td>{{$subTotal}}</td>
                            </tr>
                        @endforeach
                    </table>
                    @endif
                    </td>
                @endif
                <td class="text-end">{{ $item->cr == null ? '-' : round($item->cr,2)}}</td>
                <td class="text-end">{{ $item->db == null ? '-' : round($item->db,2)}}</td>
                <td class="text-end">{{ round($balance,2) }}</td>
                </tr>
            @endforeach
        </tbody>
        </table>
    </div>
</div>
<script>
      $('table.display').DataTable({
        "bSort": true,
        "bLengthChange": true,
        "bPaginate": true,
        "bFilter": true,
        "bInfo": true,
       "order": [[1, 'desc']],

    });
</script>
