@extends('buyer/layouts/backend/backend_single_layout')

@section('css')
    <link href="{{ URL::asset('front-assets/js/front/crop/css/style.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/js/front/crop/css/style-example.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/js/front/crop/css/jquery.Jcrop.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/croppie.min.css') }}" rel='stylesheet' />
    <link href="{{ URL::asset('front-assets/js/datatables-bs4/dataTables.bootstrap4.css') }}" rel='stylesheet' />

@endsection

@section('content')
<div class="main_section position-relative profile_section">

<div class="container py-3 Loanapplication">
    <div class="row">
        <div class="header_top koin d-flex align-items-center pb-5 px-3 pt-xl-2 pt-0">
            <h1 class="mb-0">Credit</h1>
        </div>
        <div class="col-md-12 mt-lg-3">
            <div class="text-center">
                <p class="mb-lg-5 mb-3 fw-bold" style=" font-size: 36px; color: #AE0000">{{__('dashboard.your_credit_application')}} {{$loanApplication->loan_application_number}} {{__('dashboard.has_been_rejected')}}.</p>
                <p class="my-lg-5 my-3 fw-bold" style=" font-size: 18px;">{{__('dashboard.credit_limit')}}: Rp {{$loanApplication->loan_limit}}</p>
                <p class="mb-1" style=" font-size: 16px;">Thank you for your interest in applying for credit in Blitznet.</p>
                <p class="mb-1" style=" font-size: 16px;">You can apply credit after 6 month.</p>
                <div class="my-lg-5 my-3">
                    <a name="" id=""
                        class="btn btn-primary bluecolornext text-white px-5" href="{{route('dashboard') }}"
                        role="button"> {{__('profile.back_to_home')}}</a>
                </div>

            </div>

        </div>
    </div>

</div>

</div>
@endsection
