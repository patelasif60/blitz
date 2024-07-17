<head>
    @if (config('app.env') == "live")
        <script type="text/javascript">
            window._mfq = window._mfq || [];
            (function() {
            var mf = document.createElement("script");
            mf.type = "text/javascript"; mf.defer = true;
            mf.src = "//cdn.mouseflow.com/projects/e23b2581-094b-4fe3-a8d3-149417b0fbc5.js";
            document.getElementsByTagName("head")[0].appendChild(mf);
            })();
      </script>
    @endif

    <!-- Required meta tags -->
    <meta charset="utf-8">
      {{--  <meta property="og:image" content="{{ URL::asset('front-assets/images/front/img_1.jpg') }}"/> --}}
      <meta property="og:image" content="{{ URL::asset('front-assets/images/front/ogimage.png') }}"/>
      <meta name="description" content="blitznet merupakan Super Platform yang aman dan terpercaya untuk UMKM yang berbisnis bahan baku, dimana mempermudah pembeli dan pemasok untuk melakukan perdagangan, mendapatkan akses modal usaha dan mengautomatiskan rantai pasokan" />
      @if (Route::currentRouteName() == 'buyers')
            <meta name="description" content="Tunggu apalagi, mari temukan produk yang Anda butuhkan di sini! Tingkatkan daya tawar dan efisiensikan pengadaan dengan ikut bergabung bersama grup pembeli Temukan sumber pembiayaan untuk Purchase Order Anda melalui solusi keuangan kami.Kami akan membantu Anda untuk tetap terhubung dengan industri dan berita terkini" />
      @elseif (Route::currentRouteName() == 'suppliers')
          <meta name="description" content="Dapatkan seluruh informasi yang dibutuhkan: RFQ, pengumpulan pembayaran, logistik dan lainnya. Jangan khawatirkan penagihan pembayaran Anda. Atur risiko produksi Anda. Buat pekerjaan administrasi semudah mungkin" />
      @endif
      <meta name="keywords" content="Raw Materials, Suppliers, Buyers, Supply Chain, Business Development, Loans, Logistics, What is, Management, Procurement, e-Procurement, e-Purchasing, SaaS, Purchase process" />
      <meta id="metaTitleNew" property="og:title" content=""/>
      <meta id="metaImageNew" property="og:image" content=""/>
      <meta property="og:image:width" content="950"/>
      <meta property="og:image:height" content="950"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> --}}
    <link rel="icon" type="image/png" href="{{ URL::asset('home_design/images/favicon.png') }}">
    <meta id="metaDescriptionNew" name="description" content="">
    <meta id="metaAuthorNew" name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <link rel="apple-touch-icon" sizes="57x57" href="{{ URL::asset('front-assets/images/favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ URL::asset('front-assets/images/favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ URL::asset('front-assets/images/favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ URL::asset('front-assets/images/favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ URL::asset('front-assets/images/favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ URL::asset('front-assets/images/favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ URL::asset('front-assets/images/favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ URL::asset('front-assets/images/favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ URL::asset('front-assets/images/favicon/apple-icon-180x180.png') }}">
    <link rel="manifest" href="{{ URL::asset('front-assets/images/favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ URL::asset('front-assets/images/favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">
    <meta name="csrf-token" content="{!! csrf_token() !!}">
    <title>blitznet</title>

    <!-- CSS only -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href=" {{ asset('/images/favicon.png') }}">
    <!-- Bootstrap CSS -->
    <link href="{{ asset('home_design/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('home_design/css/aos.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('home_design/css/blitnet_style.css') }}">--}}
    <script src="//run.louassist.com/v2.5.1-m?id=653988744203"></script>
    <script src="{{ URL::asset('home_design/js/jquery-3.6.0.min.js') }}"></script>
    <link href="{{ URL::asset('home_design/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/style.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/calender.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.buttons.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/library/pnotify/dist/pnotify.nonblock.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/smart_wizard_all.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('front-assets/css/front/animate.css') }}" />
    <link href="{{ URL::asset('home_design/css/aos.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('front-assets/css/front/owl.carousel.min.css') }}">
    <link href="{{ URL::asset('home_design/css/blitnet_style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('home_design/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('home_design/css/owl.theme.default.min.css') }}">
    <link href="{{ URL::asset('home_design/css/supplier_profile.css') }}" rel="stylesheet">
    <title>blitznet</title>

    @include('home.tracking.headBottom')

</head>
