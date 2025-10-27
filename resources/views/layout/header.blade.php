<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title>POS</title>
        <meta content="Admin Dashboard" name="description" />
        <meta content="Mannatthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <link rel="shortcut icon" href= {{ asset("assets/images/rlogo.png" ) }}>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

        <!-- jvectormap -->
        <link href= {{ asset("assets/plugins/jvectormap/jquery-jvectormap-2.0.2.css") }} rel="stylesheet">

        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script defer src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script defer src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script defer src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

        <link href= {{ asset("assets/css/bootstrap.min.css") }} rel="stylesheet" type="text/css">
        <link href= {{ asset("assets/css/icons.css") }} rel="stylesheet" type="text/css">
        <link href= {{ asset("assets/css/style.css") }} rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.default.min.css" integrity="sha512-pTaEn+6gF1IeWv3W1+7X7eM60TFu/agjgoHmYhAfLEU8Phuf6JKiiE8YmsNC0aCgQv4192s4Vai8YZ6VNM6vyQ=="crossorigin="anonymous" referrerpolicy="no-referrer"/>

        {{-- data table --}}
        <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="{{ asset('assets/plugins/notification/snackbar/snackbar.min.css') }}">
        {{-- data table --}}
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia">
        @if(Config::get('app.locale') == 'ur')
        <style>
          body {
            font-family: "Noto Nastaliq Urdu";
            font-weight: 900;
          }
          </style>
        @endif
        <style>

          td{
              margin:0px !important;
              padding: 0px !important;
          }
          thead{
            position: -webkit-sticky;
            position: sticky;
            top: 0;
            z-index: 1;
          }
          .table-responsive
          {
            height: 70vh !important;
            /* overflow:scroll; */
          }
          .expBtn{
            margin-left: 20px;
            border:none !important;
            background:#3cab94 !important;
            padding: 2px;
            color: #fff;
          }


      </style>

<style>
  .page-item.active .page-link{
      background-color: #ea0a0a ;
      border-color: #ea0a0a ;
  }

  .page-link{
      color: #ea0a0a ;
  }
  /* .dataTables_paginate {
      display: none
  } */
</style>
    </head>

    @php
        App::setLocale(auth()->user()->lang);
    @endphp
    <body class="fixed-left">

        <!-- Loader -->
        <div id="preloader"><div id="status"><div class="spinner"></div></div></div>
        <nav style="background: linear-gradient(to bottom, #17aee1 0%, #003f7f 100%) !important" class="navbar navbar-expand-lg navbar-light bg-light">
            <a style="width: 17%;" class="navbar-brand" href="#">
              <img class="logo-nav" style="width:150px !important; height:100px ; margin: -18px 0px !important;" src="{{ asset("assets/images/rlogo.png") }}"/>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
              <ul class="navbar-nav">
                @if(auth()->user()->role == 1)
                  <li class="nav-item active">
                  <a class="nav-link" href="{{url('/dashboard')}}">{{ __('lang.Home') }} <span class="sr-only">(current)</span></a>
                </li>
                @endif

                @if (auth()->user()->role != '3')
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                    {{ __('lang.Sale') }}
                  </a>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ url('/sale') }}">{{ __('lang.CreateSale') }}</a>
                    <a class="dropdown-item" href="{{ url('/sale/history') }}">{{ __('lang.SaleHistory') }}</a>
                    <a class="dropdown-item" href="{{ url('/quotation') }}">{{ __('lang.Quotation') }}</a>
                    <a class="dropdown-item" href="{{ url('/sale/returns') }}">Returns</a>
                  </div>
                </li>

                @endif
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                        {{ __('lang.Stock') }}
                    </a>
                    <div class="dropdown-menu">

                            <a class="dropdown-item" href="{{ url('/purchase') }}">{{ __('lang.CreatePurchase') }}</a>
                            <a class="dropdown-item" href="{{url('/purchase/history')}}">{{ __('lang.PurchaseHistory') }}</a>
                            <a class="dropdown-item" href="{{route('purchasePendings')}}">Pending Purchases</a>


                      <a class="dropdown-item" href="{{ url('/stock') }}">{{ __('lang.StockDetail') }}</a>
                      <a class="dropdown-item" href="{{ url('/stocktransfer') }}">Stock Transfer</a>
                    </div>
                  </li>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                    {{ __('lang.Vendors/Customers') }}
                  </a>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ url('/vendors') }}">{{ __('lang.Vendors') }}</a>
                    <a class="dropdown-item" href="{{ '/customers' }}">{{ __('lang.Customers') }}</a>
                  </div>
                </li>

                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                    {{ __('lang.Finance') }}
                  </a>
                  <div class="dropdown-menu">
                    @if (auth()->user()->role != 3)
                    <a class="dropdown-item" href="{{ url('/accounts') }}">{{ __('lang.Accounts') }}</a>
                    @endif
                    @if (auth()->user()->role == 1)
                    <a class="dropdown-item" href="{{ url('/deposit') }}">{{ __('lang.Deposit') }}</a>
                    <a class="dropdown-item" href="{{ url('/withdraw') }}">{{ __('lang.Withdraw') }}</a>
                    @endif
                    @if (auth()->user()->role != 3)
                    <a class="dropdown-item" href="{{ url('/transfer') }}">{{ __('lang.Transfer') }}</a>
                    <a class="dropdown-item" href="{{ url('/expense') }}">{{ __('lang.Expense') }}</a>
                    @endif
                  </div>
                </li>
                  <li class="nav-item dropdown">
                    <a class="nav-link" href="{{ url('/products') }}" id="navbardrop">
                        {{ __('lang.Products') }}
                    </a>
                  </li>
                   <li class="nav-item dropdown">
                    <a class="nav-link" href="{{ url('/stockAlert') }}" id="navbardrop">
                        Stock Alert
                    </a>
                  </li>
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                      Others
                    </a>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="{{ url('/settings') }}">Settings</a>
                      @if (auth()->user()->role == 1)
                      <a class="dropdown-item" href="{{ url('/warehouses') }}">Warehosues</a>
                      <a class="dropdown-item" href="{{ url('/users') }}">Users</a>
                      @endif
                    </div>
                  </li>

              </ul>
              @if (auth()->user()->role != 3 )
              <a class="btn btn-success" href="{{ url('/pos') }}">POS</a>
              @endif
                   <a class="btn btn-primary ml-4" href="{{ url('/logout') }}" >
                    <i class="fa fa-power-off" aria-hidden="true"></i>
                      {{-- <i class="la la-caret-right"></i>   &nbsp; <span> {{ __('Log Out') }} </span> --}}
                   </a>
                   <span class="ml-2" style="color:#fff;">
                    {{auth()->user()->name}}<br>
                    {{auth()->user()->warehouse->name}}
                   </span>

            </div>
          </nav>

