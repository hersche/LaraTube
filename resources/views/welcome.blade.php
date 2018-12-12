<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script>var baseUrl = "{{ url("/") }}/";
        var sm;
        </script>

        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('js/app.js') }}" defer></script>
        <script>

        </script>
        <title>LaraTube</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }
            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
      <div id="app">
  <exco :aSubFirst="aFirst"></exco>
    </div>
      <script>

      // {{ url("/api/media") }}
$( document ).ready(function() {
  //function loadMedias(){

  //}

});
      </script>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                    <div id="mainMenu" class="top-left links">

                    </div>
                </div>
            @endif
            <div class="container col-lg-9 mt-2" style="max-height:90%; margin-top: 10%; overflow-y: auto;" id="mainContent">

                <h1 class="my-4 text-center text-lg-left">{{ config('app.name', 'Laravel') }}</h1>
                <div class="content">
                  <h3>{{ __("Newest videos") }}</h3>
                  <div id="demo" class="carousel slide" data-ride="carousel">

                    <!-- Indicators -->
                    <ul class="carousel-indicators" id="carouselIndicatorsBody">
                    </ul>

                    <!-- The slideshow -->
                    <div class="carousel-inner" id="carouselInnerBody">
                    </div>

                    <!-- Left and right controls -->
                    <a class="carousel-control-prev" href="#demo" data-slide="prev">
                      <span class="carousel-control-prev-icon"></span>
                    </a>
                    <a class="carousel-control-next" href="#demo" data-slide="next">
                      <span class="carousel-control-next-icon"></span>
                    </a>

                  </div>

                  <div class="row text-center text-lg-left" id="galleryBody" >
                  </div>
                  <div class="row justify-content-center mt-1 mb-1" id="pagesBody" >
                  </div>
                </div>
              </div>
            </div>

    </body>
</html>
