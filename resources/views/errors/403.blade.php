<!doctype html>
<html lang="en">

<head>
    <title>403</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS v5.0.2 -->
    <link href="{{ URL::asset('front-assets/css/front/bootstrap.min.css') }}" rel="stylesheet">
</head>

<body>
    <div class="h-100 bg-transparent">
        <div class="bg-white ">
            <div class="col-md-12 text-center ">
                <img src="{{ asset('assets/images/errors/nogroup.jpg') }}" style="width: 400px; height: 250px; margin-top: 100px;" alt="No Group Available">
                <h3>Sorry, You don't have permission to access this page!</h3>

                @if(Auth::check())
                    @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 3 || Auth::user()->role_id == 4 || Auth::user()->role_id == 5)
                        <a href="{{ route('admin-dashboard') }}" class="btn btn-primary">Back to Home</a>

                    @elseif (Auth::user()->role_id == 2 || Auth::user()->role_id == 6)
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">Back to Home</a>

                    @endif
                @else
                    <a href="{{ route('home') }}" class="btn btn-primary">Back to Home</a>
                @endif

            </div>
        </div>
    </div>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="{{ URL::asset('front-assets/js/front/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
