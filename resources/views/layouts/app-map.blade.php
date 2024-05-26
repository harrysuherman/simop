
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Peta {{ setting('site.title') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    {{-- <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.1/dist/leaflet.css" />
    <!--[if lte IE 8]><link rel="stylesheet" href="https://cdn.leafletjs.com/leaflet-0.7.2/leaflet.ie.css" /><![endif]-->
    <link rel="stylesheet" href="{{ asset('vendors/L.Control.BetterScale.css') }}">
    <link rel="stylesheet" href="{{ asset('css/L.Control.Sidebar.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.css">
    <link rel="stylesheet" href="{{ asset('css/leaflet.mouseCoordinate.css') }}">
    <link href="https://cdn.materialdesignicons.com/1.3.41/css/materialdesignicons.min.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('vendors/leaflet-beautify-marker-icon.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/leaflet.legend.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/lightbox/dist/css/lightbox.min.css') }}">
    <style>
        body {
            padding: 0;
            margin: 0;
        }

        html, body, #map {
            height: 90vh;
            font: 10pt "Helvetica Neue", Arial, Helvetica, sans-serif;
        }

        .lorem {
            font-style: italic;
            color: #AAA;
        }
        .logo{
            position: absolute;
            top: 55px;
            left: 100px;
            z-index: 1000;
        }
        .leaflet-control-layers {
            /* box-shadow: 0 1px 5px rgb(0 0 0 / 40%);
            background: #fff;
            border-radius: 5px; */
            width: 100%;
        }

    </style>
    @stack('style')
    @livewireStyles
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-warning text-white">
        <div class="container-fluid">
          <a class="navbar-brand text-white" href="#">
            <img src="{{ asset('img/logo-pu.png') }}" alt="Logo" style="height: 40px"> BWS Kalimantan IV
          </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link text-white" aria-current="page" href="{{ url('/') }}"><strong>Home</strong></a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-white" aria-current="page" href="{{ url('/') }}"><strong>Profil</strong></a>
              </li>
              {{-- <li class="nav-item">
                <a class="nav-link text-white" href="#"><strong>Link</strong></a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <strong>Dropdown</strong>
                </a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#">Action</a></li>
                  <li><a class="dropdown-item" href="#">Another action</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
              </li> --}}
            </ul>
            <form class="d-flex" role="search">
              <button class="btn btn-primary" type="button"> <i class="fa fa-user"></i> Login </button>
            </form>
          </div>
        </div>
      </nav>
    @yield('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <script src="{{ asset('vendors/lightbox/dist/js/lightbox.min.js') }}"></script>
    <script src="https://unpkg.com/leaflet@1.0.1/dist/leaflet.js"></script>
    <script src="{{ asset('vendors/Leaflet.spin/example/spin/spin.js') }}"></script>
    <script src="{{ asset('vendors/Leaflet.spin/example/leaflet.spin.min.js') }}"></script>
    <script src="{{ asset('js/L.Control.Sidebar.js') }}"></script>
    {{-- <script src="{{ asset('vendors/leaflet-geoserver-request/src/L.Geoserver.js') }}"></script> --}}
    <script src="{{ asset('vendors/L.Control.BetterScale.js') }}"></script>
    <script src="{{ asset('vendors/L.TileLayer.BetterWMS_non_popup.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.js"></script>
    <script src="{{ asset('js/leaflet.mouseCoordinate.js') }}"></script>
    <script src="{{ asset('js/utm.js') }}"></script>
    <script src="{{ asset('js/utmref.js') }}"></script>
    <script src="{{ asset('vendors/terraformer.js') }}"></script>
    <script src="{{ asset('vendors/terraformer-wkt-parser.js') }}"></script>
    <script src="{{ asset('vendors/Leaflet.Icon.Glyph.js') }}"></script>
    <script src="{{ asset('vendors/Leaflet.Icon.Glyph.js') }}"></script>
    <script src="{{ asset('vendors/leaflet-beautify-marker-icon.js') }}"></script>
    <script src="{{ asset('vendors/leaflet.legend.js') }}"></script>
@stack('javascript')
@livewireScripts
</body>
</html>

