<!DOCTYPE html>
<!-- saved from url=(0036)https://pos.superupscenter.com/login -->
<html lang="en">
    @php
    App::setLocale(auth()->user()->lang);
@endphp
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="Emslm8WRZAV0paIYDnCW97sjqwwDzrnMiJ0JYj6N">

    <title>POS</title>

   <link rel="stylesheet" href="{{ asset('css/app.css') }}">
   <link rel="stylesheet" href="{{ asset('assets/plugins/notification/snackbar/snackbar.min.css') }}">
    <!-- Scripts -->

</head>
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
<body>
    <div class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>

                    <img width="170px" height="150px" src="{{ asset('assets/images/rlogo.png') }}">
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                <!-- Session Status -->
                <div class="card-header text-center">{{__('lang.PasswordConfimationMsg')}}</div>
                <!-- Validation Errors -->

                <form method="POST" >
                   @csrf
                    <!-- Email Address -->

                    <!-- Password -->
                    <div class="mt-4">
                        <label class="block font-medium text-sm text-gray-700" for="password">
                            {{__('lang.Password')}}
                        </label>

                        <input
                            class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full"
                            id="password" type="password" name="password" required="required"
                            autocomplete="current-password">
                    </div>

                    <!-- Remember Me -->


                    <div class="flex items-center justify-end mt-4">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 ml-3">
                            {{__('lang.Proceed')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div></div>
    <script src= {{ asset("assets/js/jquery.min.js" ) }}></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset("assets/plugins/notification/snackbar/snackbar.min.js") }}"></script>
</body>


</html>
