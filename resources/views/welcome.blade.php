<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <script src="{{ asset('js/app.js') }}" defer></script>
        <script src="{{ asset('js/jquery.min.js') }}"></script>
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

      <script>
      // {{ url("/api/media") }}
$( document ).ready(function() {
  //function loadMedias(){
    $.getJSON( "{{ url('/api/media') }}", function( data ) {
      var items = "";
      var indicators = "";
      var first=true;
      $.each( data.data, function( key, value ) {
        val1 = value;
        items += '<div style="min-width: 300px;" class="col-lg-4 col-md-4 col-xs-6 card"><a href="{{ url("/media/") }}/'+val1.title+'" class="d-block h-100"><img class="card-img-top" src="'+ val1.poster_source + '" alt=""><div class="card-img-overlay"><h4 class="card-title bg-secondary text-info" style="opacity: 0.9;">'+val1.title+'</h4></div></a></div></div></div>';
      });
      $("#carouselBody").html(items);
  });
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
                </div>
            @endif
            <div class="container col-lg-9 mt-2" style="max-height:90%; margin-top: 10%; overflow-y: auto;" id="content">

                <h1 class="my-4 text-center text-lg-left">{{ config('app.name', 'Laravel') }}</h1>
                <div class="content">
                  <div class="row text-center text-lg-left" id="carouselBody" >
                  </div>
                </div>
              </div>
            </div>

    </body>
</html>
