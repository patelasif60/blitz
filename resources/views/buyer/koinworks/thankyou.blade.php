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
            <div class="row floatlables">
                <div class="header_top koin d-flex align-items-center pb-0 px-3">
                    <h1 class="mb-0">{{__('profile.loan_processed')}}</h1>
                </div>
                <div class="d-flex justify-content-center" >
                    <div class="card border-0">
                        <div class="card-body text-center">
                            <div class=""><img src="{{ URL::asset('front-assets/images/icons/loan_applied.png') }}"  alt="Loan Applied"></div>
                            <h3 class="mb-3 text-success">{{__('profile.thanks_applying_credit')}}</h3>
                            <p><strong>{{__('profile.loan_application_number')}}: </strong>{{$loanApplication->loan_application_number}}</p>
                            <p><strong>{{__('admin.approved_amount')}}: </strong>Rp {{number_format($loanApplication->senctioned_amount,2)}}</p>
                            <h5 class="mt-5"><strong>{{__('profile.back_soon')}}</strong></h5>
                            <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm">{{__('profile.back_to_home')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
