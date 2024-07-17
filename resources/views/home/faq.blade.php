@extends('home/homeLayout2')

@section('content')

<div id="carouselCaptions" class="carousel slide bannersection" data-bs-ride="carousel" data-aos="fade-down">
</div>
<div class="faq-page mx-5 my-5">
    <div class="row d-block pt-5">
        <div class="col-md-12 text-center pt-4">
            <h1 class="heading p-4">{{ __('home_latest.question_and_answer') }}</h1>
        </div>
        <div class="col-md-12 d-flex">
            <div class="col-md-2"></div>
            <div class="col-md-8 my-4">
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingSeven">
                            <button class="accordion-button text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="true" aria-controls="collapseOne">
                                <h6 class="fw-semibold mb-0">
                                    {{ __('home_latest.faq_question_7') }}
                                </h6>
                            </button>
                        </h2>
                        <div id="collapseSeven" class="accordion-collapse collapse show" aria-labelledby="headingSeven"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                {!! __('home_latest.faq_answer_7') !!}
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingEight">
                            <button class="accordion-button text-dark collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                                <h6 class="fw-semibold mb-0">
                                    {{ __('home_latest.faq_question_8') }}
                                </h6>
                            </button>
                        </h2>
                        <div id="collapseEight" class="accordion-collapse collapse" aria-labelledby="headingEight" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                {!! __('home_latest.faq_answer_8') !!}
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingNine">
                            <button class="accordion-button text-dark collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                                <h6 class="fw-semibold mb-0">
                                    {{ __('home_latest.faq_question_9') }}
                                </h6>
                            </button>
                        </h2>
                        <div id="collapseNine" class="accordion-collapse collapse" aria-labelledby="headingNine"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                {!! __('home_latest.faq_answer_9') !!}
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTen">
                            <button class="accordion-button text-dark collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
                                <h6 class="fw-semibold mb-0">
                                    {{ __('home_latest.faq_question_10') }}
                                </h6>
                            </button>
                        </h2>
                        <div id="collapseTen" class="accordion-collapse collapse" aria-labelledby="headingTen"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                {!! __('home_latest.faq_answer_10') !!}
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingEleven">
                            <button class="accordion-button text-dark collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseEleven" aria-expanded="false" aria-controls="collapseEleven">
                                <h6 class="fw-semibold mb-0">
                                    {{ __('home_latest.faq_question_11') }}
                                </h6>
                            </button>
                        </h2>
                        <div id="collapseEleven" class="accordion-collapse collapse" aria-labelledby="headingEleven"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                {!! __('home_latest.faq_answer_11') !!}
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwelve">
                            <button class="accordion-button text-dark collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTwelve" aria-expanded="false" aria-controls="collapseTwelve">
                                <h6 class="fw-semibold mb-0">
                                    {{ __('home_latest.faq_question_12') }}
                                </h6>
                            </button>
                        </h2>
                        <div id="collapseTwelve" class="accordion-collapse collapse" aria-labelledby="headingTwelve"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                {!! __('home_latest.faq_answer_12') !!}
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThriteen">
                            <button class="accordion-button text-dark collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseThriteen" aria-expanded="false" aria-controls="collapseThriteen">
                                <h6 class="fw-semibold mb-0">
                                    {{ __('home_latest.faq_question_13') }}
                                </h6>
                            </button>
                        </h2>
                        <div id="collapseThriteen" class="accordion-collapse collapse" aria-labelledby="headingThriteen"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                {!! __('home_latest.faq_answer_13') !!}
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingForteen">
                            <button class="accordion-button text-dark collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseForteen" aria-expanded="false" aria-controls="collapseForteen">
                                <h6 class="fw-semibold mb-0">
                                    {{ __('home_latest.faq_question_14') }}
                                </h6>
                            </button>
                        </h2>
                        <div id="collapseForteen" class="accordion-collapse collapse" aria-labelledby="headingForteen"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                {!! __('home_latest.faq_answer_14') !!}
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFifteen">
                            <button class="accordion-button text-dark collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseFifteen" aria-expanded="false" aria-controls="collapseFifteen">
                                <h6 class="fw-semibold mb-0">
                                    {{ __('home_latest.faq_question_15') }}
                                </h6>
                            </button>
                        </h2>
                        <div id="collapseFifteen" class="accordion-collapse collapse" aria-labelledby="headingFifteen"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                {!! __('home_latest.faq_answer_15') !!}
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingSixteen">
                            <button class="accordion-button text-dark collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseSixteen" aria-expanded="false" aria-controls="collapseSixteen">
                                <h6 class="fw-semibold mb-0">
                                    {{ __('home_latest.faq_question_16') }}
                                </h6>
                            </button>
                        </h2>
                        <div id="collapseSixteen" class="accordion-collapse collapse" aria-labelledby="headingSixteen"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                {!! __('home_latest.faq_answer_16') !!}
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingSeventeen">
                            <button class="accordion-button text-dark collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseSeventeen" aria-expanded="false" aria-controls="collapseSeventeen">
                                <h6 class="fw-semibold mb-0">
                                    {{ __('home_latest.faq_question_17') }}
                                </h6>
                            </button>
                        </h2>
                        <div id="collapseSeventeen" class="accordion-collapse collapse" aria-labelledby="headingSeventeen"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                {!! __('home_latest.faq_answer_17') !!}
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingEighteen">
                            <button class="accordion-button text-dark collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseEighteen" aria-expanded="false" aria-controls="collapseEighteen">
                                <h6 class="fw-semibold mb-0">
                                    {{ __('home_latest.faq_question_18') }}
                                </h6>
                            </button>
                        </h2>
                        <div id="collapseEighteen" class="accordion-collapse collapse" aria-labelledby="headingEighteen"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                {!! __('home_latest.faq_answer_18') !!}
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingNineteen">
                            <button class="accordion-button text-dark collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseNineteen" aria-expanded="false" aria-controls="collapseNineteen">
                                <h6 class="fw-semibold mb-0">
                                    {{ __('home_latest.faq_question_19') }}
                                </h6>
                            </button>
                        </h2>
                        <div id="collapseNineteen" class="accordion-collapse collapse" aria-labelledby="headingNineteen"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                {!! __('home_latest.faq_answer_19') !!}
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwenteen">
                            <button class="accordion-button text-dark collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTwenteen" aria-expanded="false" aria-controls="collapseTwenteen">
                                <h6 class="fw-semibold mb-0">
                                    {{ __('home_latest.faq_question_20') }}
                                </h6>
                            </button>
                        </h2>
                        <div id="collapseTwenteen" class="accordion-collapse collapse" aria-labelledby="headingTwenteen"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                {!! __('home_latest.faq_answer_20') !!}
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button text-dark collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                <h6 class="fw-semibold mb-0">
                                    {{ __('home_latest.faq_question_1') }}
                                </h6>
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                {!! __('home_latest.faq_answer_1') !!}
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed text-dark" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                <h6 class="fw-semibold  mb-0">
                                    {!! __('home_latest.faq_question_2') !!}
                                </h6>
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                {!! __('home_latest.faq_answer_2') !!}
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed text-dark" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                <h6 class="fw-semibold  mb-0">
                                    {!! __('home_latest.faq_question_3') !!}
                                </h6>
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                            data-bs-parent="#accordionExample">
                            <div class="according-body">
                                {!! __('home_latest.faq_answer_3') !!}
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed text-dark" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                <h6 class="fw-semibold  mb-0">
                                    {!! __('home_latest.faq_question_4') !!}
                                </h6>
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                {!! __('home_latest.faq_answer_4') !!}
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFive">
                            <button class="accordion-button collapsed text-dark" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                <h6 class="fw-semibold  mb-0">
                                    {!! __('home_latest.faq_question_5') !!}
                                </h6>
                            </button>
                        </h2>
                        <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                {!! __('home_latest.faq_answer_5') !!}
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingSix">
                            <button class="accordion-button collapsed text-dark" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                <h6 class="fw-semibold  mb-0">
                                    {!! __('home_latest.faq_question_6') !!}
                                </h6>
                            </button>
                        </h2>
                        <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                {!! __('home_latest.faq_answer_6') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2"></div>
        </div>
        <div class="col-md-12"></div>
    </div>
</div>
@stop
