<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS</title>
    <style>


        body {
            -webkit-print-color-adjust: exact;
            background-color: #F6F6F6;
            margin: 0;
            padding: 0;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin: 0;
            padding: 0;
        }

        p {
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            margin-right: auto;
            margin-left: auto;
        }

        .brand-section {
            background-color: #f36b2c;
            padding: 10px 40px;
        }

        .logo {
            width: 50%;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
        }

        .col-6 {
            width: 50%;
            flex: 0 0 auto;
        }

        .text-white {
            color: #fff;
        }

        .company-details {
            float: right;
            text-align: right;
        }

        .body-section {
            padding: 16px;
            border-left: 2px solid #f36b2c;
            border-right: 2px solid #f36b2c;

        }

        .body-section1 {
            background-color: #f36b2c;
            color: white;
            border-radius: 4px;
        }

        .heading {
            font-size: 20px;
            margin-bottom: 08px;
        }

        .sub-heading {
            color: #262626;
            margin-bottom: 05px;
        }

        table {
            background-color: #fff;
            width: 100%;
            border-collapse: collapse;
        }

        table thead tr {
            border: 1px solid #111;
            background-color: #f2f2f2;
        }

        table td {
            vertical-align: middle !important;
            text-align: center;
        }

        table th,
        table td {
            padding-top: 08px;
            padding-bottom: 08px;
        }

        .table-bordered {
            box-shadow: 0px 0px 5px 0.5px gray;
        }

        .table-bordered td,
        .table-bordered th {
            border: 1px solid #dee2e6;
        }

        .text-right {
            text-align: end;
            padding-right: 3px;
            ;
        }

        .w-20 {
            width: 10%;
        }

        .w-15 {
            width: 22%;
        }

        .w-5 {
            width: 5%;
        }

        .w-10 {
            width: 18%;
        }

        .float-right {
            float: right;
        }

        .container1 {
            border: 2px solid rgb(184, 0, 0);
            color: #ffffff;
            height: 90px;
            border-radius: 6px;
        }

        .sub-container {
            background-color: #f36b2c;
            margin: 5px;
            padding-bottom: 2px;
            display: flex;
            height: 78px;
            border-radius: 6px;
        }

        .m-query1 {
            font-size: 22px;
        }

        .m-query2 {
            font-size: 11px;
        }

        img {
            margin-top: -36px;
            padding: 2px;
            width: 92%;
            height: 148px;
            margin-left: 2px;
        }

        .text1 {
            text-align: center;
            width: 70%;
            padding-top: 11px;
        }

        .qoute {
            width: 21%;
            margin: auto;
            text-align: center;
            background-color: #f36b2c;
            color: white;
            border-radius: 5px;
            font-size: 12px;
        }

        @media screen and (max-width: 1014px) {
            .m-query1 {
                margin-top: 6PX;
                font-size: 28px;
            }

            .m-query2 {
                font-size: 11px;
            }
        }

        @media screen and (max-width: 900px) {
            .m-query1 {
                font-size: 24px;
            }

            .m-query2 {
                font-size: 14px;
            }

            img {
                width: 99%;
                height: 171%;
                margin-top: -50px;
                margin-left: 8px;
            }
        }
        #myDiv {
            width: 128px;
            font-size: 18px;
            margin-top: 19px;
        }
        .dot {
            height: 60px;
            width: 65px;
            background-color: #f36b2c;
            color: white;
            /* color: #f36b2c; */
            border-radius: 50%;
            display: inline-block;
            border: 5px solid white;
            margin: -14px;
            margin-left: 7px;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="container1">
            <div style="text-align: center;">
                <img style="width:100%;margin:0 auto;height:100px;" src="{{ asset('assets/images/header.jpg') }}" alt="">
             </div>
        </div>

        <div class="body-section">
            <div class="row">
                <div class="qoute">
                    <h2 style="text-align: center;">INVOICE# &nbsp; {{ $invoice->id }}</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <!-- <h2 class="heading">Invoice No.: 001</h2> -->
                    <h3 class="sub-heading">Invoice to:
                        @if (@$invoice->customer_account->title)
                            {{ @$invoice->customer_account->title }} ({{ @$invoice->customer_account->type }})
                        @else
                            {{ $invoice->walking }} (Walk In)

                        @endif
                    </h3>
                </div>
                <div class="col-6">
                    <div class="company-details">
                        <h3 class="text-dark">Date: {{ date('d M Y', strtotime($invoice->date)) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="body-section">
            <!-- <h3 class="heading">Ordered Items</h3>
            <br> -->
            <table class="table-bordered">
                <thead>
                    <tr>
                        <th class="w-5">#</th>
                        <th class="w-15">Item</th>
                        <th class="w-10">Price</th>
                        <th class="w-10">Quantity</th>
                        <th class="w-10">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total = 0;
                        $ser = 0;
                    @endphp

                    @foreach ($details as $item)
                        @php
                            $ser += 1;
                        @endphp
                        <tr>
                            <th scope="row">{{ $ser }}</th>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ number_format($item->price,0) }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>{{ number_format($item->price * $item->qty,0) }}</td>
                        </tr>
                        @php
                            $total += $item->price * $item->qty;
                        @endphp
                    @endforeach

                    <tr>
                        <td colspan="4" class="text-right">
                            <strong>Total</strong>
                        </td>
                        <td>
                            <strong>{{ $total}}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right">
                            <strong>Transport</strong>
                        </td>
                        <td>
                            <strong>{{ $invoice->transport}}</strong>
                        </td>
                    </tr>
                    @if($invoice->discount > 0)
                    <tr>
                        <td colspan="4" class="text-right">
                            <strong>Discount</strong>
                        </td>
                        <td>
                            <strong>{{ $invoice->discount == 0 ? 0 : $invoice->discount}}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right">
                            <strong>Net Total</strong>
                        </td>
                        <td>
                            <strong>{{ $total - $invoice->discount + $invoice->transport }}</strong>
                        </td>
                    </tr>
                    @endif

                    @if (@$invoice->customer_account->title)
                    <tr>
                        @php
                        $paidAmount = $invoice->amount ;
                        if(!$invoice->paidIn){
                             $paidAmount = 0;
                        }
                        else{

                            if($invoice->amount == 0){
                                $paidAmount = $total - ($invoice->amount + $invoice->discount - $invoice->transport);
                            }
                        }

                        @endphp
                        <td colspan="4" class="text-right">
                            <strong>Paid Amount</strong>
                        </td>
                        <td>
                            <strong>{{ $paidAmount }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right">
                            <strong>Remaining</strong>
                        </td>
                        <td>
                            <h3> {{ $total - $paidAmount - $invoice->discount + $invoice->transport}}</h3>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
            <br>
            <div class="row">
                <div class="col-6">
                    <table style="width:500px;">
                        <tr>
                            <td style="text-align: left; width:40%;"> <strong>Payment type:</strong> </td>
                            <td style="text-align: left">{{$invoice->account->title ?? "Unpaid"}}</td>
                        </tr>
                        <tr>
                            <td style="text-align: left; width:40%;"> <strong>Details:</strong> </td>
                            <td style="text-align: left">{{$invoice->desc}}</td>
                        </tr>
                        @if (@$invoice->customer_account->title)
                        <tr>
                            <td style="text-align: left; width:40%;"> <strong>Previous Balance:</strong> </td>
                            <td style="text-align: left">{{$prev_balance ?? 0}}</td>
                        </tr>
                        <tr>
                            <td style="text-align: left; width:40%;"> <strong>Current Balance:</strong> </td>
                            <td style="text-align: left">{{ $total - $paidAmount - $invoice->discount + $invoice->transport}}</td>
                        </tr>
                        <tr>
                            <td style="text-align: left; width:40%;"> <strong>Total Balance:</strong> </td>
                            <td style="text-align: left">{{ $cur_balance }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
               
            </div>

            <br><br>
            <h4 class="">Authorize Signature ___________________</h4>
           {{--  <p style="text-align:right;margin-right:2px;">superupscenter@gmail.com</p> --}}
            <br>
        </div>

        <div class="body-section body-section1">
            <p style="text-align: center;">Thank You For Your Business
            </p>
        </div>
    </div>
    <div style="text-align: right">
        <div class="mt-2" style="font-size: 10px">Powered by Diamond Software 03202565919</p>
        </div>
</body>

</html>
<script>
    window.print();

        setTimeout(function() {
        window.location.href = "{{ url('/sale/history')}}";
    }, 1000);

</script>
