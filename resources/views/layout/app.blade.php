<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Simple Stock Management System</title>
        <link rel="icon" type="image" href="{{ asset('favicon.png') }}">

        <!-- CDN -->
            <!-- JQuery -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

            <!-- Bootstrap -->
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

            <!-- JQueryUI -->
            <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
            <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

            <!-- Font Awesome 5 (haroldnasayaoforever@gmail.com) -->
            <script src="https://kit.fontawesome.com/8cd12e610d.js" crossorigin="anonymous"></script>

            <!-- Bootbox -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js" integrity="sha512-RdSPYh1WA6BF0RhpisYJVYkOyTzK4HwofJ3Q7ivt/jkpW6Vc8AurL1R+4AUcvn9IwEKAPm/fk7qFZW3OuiUDeg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

            <!-- Toastr -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
            <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>


        <!-- Custom -->
            <link rel="stylesheet" type="text/css" href="{{ asset('/css/custom.css') }}">
            <script src="{{ asset('/js/custom.js') }}"></script>
    </head>
    <body>

        <!-- loading overlay -->
        <div class="overlay"></div>
        <div class="spanner">
            <div class="loader"></div>
            <p>Please wait...</p>
        </div>

        <div class="container">
            <nav class="navbar navbar-expand-lg sticky-top navbar-light bg-light">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{asset('favicon.png')}}" height="35" class="d-inline-block align-top" alt="">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="{{ url('/') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/stocks') }}">Stocks</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: black">Transactions</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                              <a class="dropdown-item" href="{{ url('/sales') }}">Sale</a>
                              <a class="dropdown-item" href="#">-More to Come-</a>
                            </div>
                        </li>
                    </ul>
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: black">
                        User 1
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                          <a class="dropdown-item" href="#" onclick="logout()">Logout</a>
                        </div>
                    </div>
                </div>
            </nav>
            @yield('content')
            <br>
            <div class="footer">
                This is a trial project made by Harold "Eych" Nasayao.<br>
                Mail me @ <strong><a style="color: black" href="mailto:haroldnasayaoforever@gmail.com">haroldnasayaoforever@gmail.com</a></strong>
                <span class="float-right">Eych @ {{ date('Y') }}</span>
            </div>
        </div>
    </body>
</html>
