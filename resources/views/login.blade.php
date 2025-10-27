<!DOCTYPE html>
<!-- saved from url=(0036)https://pos.superupscenter.com/login -->
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="Emslm8WRZAV0paIYDnCW97sjqwwDzrnMiJ0JYj6N">

    <title>POS</title>

   <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- Scripts -->

</head>

<body >
    <div class="font-sans text-gray-900 antialiased" >
        <div class="flex flex-col sm:justify-center items-center">
            <div>
                    <img width="300px" src="{{ asset('assets/images/rlogo.png') }}">
            </div>

            <div class="w-full sm:max-w-md px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                <!-- Session Status -->

                <!-- Validation Errors -->

                <form method="POST" >
                   @csrf
                    <!-- Email Address -->
                    <div>
                        <label class="block font-medium text-sm text-gray-700" for="email">
                            Email
                        </label>

                        <input
                            class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full"
                            id="email" type="email" name="email" required autofocus="autofocus">
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <label class="block font-medium text-sm text-gray-700" for="password">
                            Password
                        </label>

                        <input
                            class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full"
                            id="password" type="password" name="password" required="required"
                            autocomplete="current-password">
                    </div>

                    <!-- Remember Me -->
                    <div class="block mt-4">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                name="remember">
                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a class="underline text-sm text-gray-600 hover:text-gray-900"
                            href="https://pos.superupscenter.com/forgot-password">
                            Forgot your password?
                        </a>

                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 ml-3">
                            Log in
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div></div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>


</html>
