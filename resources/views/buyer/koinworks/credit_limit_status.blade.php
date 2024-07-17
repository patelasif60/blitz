@extends('buyer/layouts/backend/backend_single_layout')
@section('content')
<div class="main_section position-relative profile_section">

    <div class="container py-3 Loanapplication">
        <div class="row">
            <div class="header_top koin d-flex align-items-center px-3 pt-0 pb-5">
                <h1 class="mb-0">{{__('dashboard.credit_limit')}}</h1>
                <a href="{{ route('dashboard') }}" class="btn btn-warning ms-auto btn-sm py-1" id="backBtn" style="padding-top: .1rem;padding-bottom: .1rem;" >
                    <img src="{{ URL::asset('front-assets/images/icons/angle-left.png') }}" alt="">
                    {{ __('profile.back') }}
                </a>
            </div>
            <div class="col-md-12 mt-lg-3">
                <div class="text-center">
                <p class="mb-lg-5 mb-3 fw-bold" style=" font-size: 36px; color: #00AE5D;">{{__('dashboard.your_credit_application')}} {{$existingApplication->loan_application_number}} {{__('dashboard.has_been_received')}}.</p>
                    <p class="my-lg-5 my-3 fw-bold" style=" font-size: 18px;">{{__('dashboard.credit_limit')}}: Rp {{number_format($existingApplication->loan_limit, 0)}}</p>
                    <img class="mw-100 mt-3" src="{{URL::asset('front-assets/images/front/StrategiesforSuccess.gif')}}" alt="" width="150px">
                    <p class="my-lg-5 my-3 fw-bold" style=" font-size: 18px;">{{__('admin.please_wait')}}</p>
                    <div class="my-lg-5 my-3 d-none">
                        <a id="checkStatus" class="btn btn-primary bluecolornext text-white px-5" href="javascript:void(0);" role="button">
                            <img class="me-1" src="{{URL::asset('front-assets/images/front/pageend_submit.png')}}" alt="arrow">
                            Check Status
                        </a>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-6 alert alert-primary" role="alert">
                            <strong>
                                <label id="current_status">{{$limitStatus}}</label>
                            </strong>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>
@endsection
@section('custom-script')
    <script type="text/javascript">

    var applicationId ="{{ request()->route('id') }}";

    /*$(document).ready(function(){
        getLimitAPI(applicationId);
    });*/

    setInterval(function () {
        getLimitAPI(applicationId)
    },10000);

    function getLimitAPI(applicationId){
        $.ajax({
            url:"{{ route('settings.get-mylimit', '') }}" +"/" +applicationId,
            type: "GET",
            success: function (successData) {
                if (successData.message) {
                    new PNotify({
                        text: successData.message,
                        type: successData.msgType,
                        styling: 'bootstrap3',
                        animateSpeed: 'fast',
                        delay: 2000
                    });
                }
                if(successData.success){
                    $('#current_status').text(successData.limit_status);
                    if (successData.url){
                        location.href = successData.url;
                    }
                }
            },
            error: function () {
                console.log("error");
            },
        });
    }

    </script>
@endsection

