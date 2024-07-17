<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://www.blitznet.co.id/front-assets/css/front/bootstrap.min.css" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/blitznet_user.css') }}" rel="stylesheet">

    <title></title>
    <style>
        /* .paymenttable {
      max-width: 500px;
    }

    p,
    table, h1 {
     font-family: 'europaNuova_re';
    } */
        .bg-light {
            background-color: #eef1ff !important;
        }

        small {
            color: #0067ff;
        }

        .main_section::before {
            height: 54px !important;
        }

        .ap-otp-input {
            padding: 10px;
            border: 1px solid #ccc;
            margin: 0 5px;
            width: 40px;
            font-weight: bold;
            text-align: center;
        }

        .ap-otp-input:focus {
            outline: none !important;
            border: 1px solid #1f6feb;
            transition: 0.12s ease-in;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
</head>

<body>
    <header class="dark_blue_bg">
        <div class="px-3">
            <div class="row">
                <div class="col-auto p-3 py-2">
                    <img src="https://www.blitznet.co.id/front-assets/images/header-logo.png" alt="Blitznet">
                    <button class="btn btn-primary d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#userinfo" aria-expanded="true" aria-controls="userinfo">
                        <img src="images/icons/icon_navbar.png" alt="Nav">
                    </button>
                </div>
                <div class="col-auto ms-auto p-3 py-2">
                    <div class="btn-group home_lenguage ps-2">
                        <button type="button" class="btn text-white dropdown-toggle" style="min-width: inherit;" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ strtoupper(str_replace('_', '-', app()->getLocale())) }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" style="min-width: inherit;">
                            <li><a class="dropdown-item" href="{{url('lang/id')}}">ID</a></li>
                            <li><a class="dropdown-item" href="{{url('lang/en')}}">EN</a></li>
                        </ul>
                    </div>
                    <div class="btn-group notification_head">
                        <a type="button" class="btn btn-transparent dropdown-toggle none" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <img src="https://www.blitznet.co.id/front-assets/images/icons/icon_bell.png" alt="Account Updates">
                        </a>
                    </div>

                    <a href="{{ route('logout') }}" class="btn btn-danger radius_2 btn-sm">Logout</a>
                </div>
            </div>
        </div>
    </header>
    <!-- header end -->
    <div class="main_section position-relative">
        <div class="container-fluid px-0" style="max-width: inherit;">
            <div class="row mx-lg-0  justify-content-center">

                <div class="header_top px-0 pb-0">
                    <h1 class="mb-0 text-center text-success">{{ __('profile.quote_details_accepted') }}</h1>
                    <h6 class="mt-2 text-center bg-dark text-white pb-1">{{ __('profile.feedback_already_submitted') }}.</h6>
                </div>

                <!-- left section end -->
                <!-- <div class="col-lg-6 col-xl-9 py-2">
                    <div class="header_top ">
                        <h1 class="mb-0 text-center">Thank you</h1>
                    </div>
                    <div class="card radius_1 border-0">
                        <div class="card-body text-center">
                            <div class="text-center"><img src="{{ URL::asset('front-assets/images/icons/accept_quote_img.png') }}" alt="Accept Order"></div>
                            <h5 class="mb-3">Thank you for your feedback.</h5>

                             <a href="" class="btn btn-primary btn-sm">Back To Home</a>
                        </div>
                    </div>
                </div> -->

                <div class="col-lg-6 col-xl-9 py-2">
                    <div class="card radius_1 border-0">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="text-center"><img src="{{ URL::asset('front-assets/images/icons/accept_quote_img.png') }}" alt="Accept Quote"></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="detail_1">
                                        <p class="mb-2"><small>{{ __('rfqs.rfq_number') }} :</small><br>
                                            <strong>{{ $quote->rfq_reference_number }}</strong>
                                        </p>
                                        <p class="mb-2"><small>{{ __('rfqs.quote_number') }} : </small><br>
                                            <strong>{{ $quote->quote_number }}</strong>
                                        </p>
                                        <p class="mb-2"><small>{{ __('rfqs.date') }} : </small><br>
                                            <strong>{{ changeDateTimeFormat($quote->created_at) }}</strong>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="detail_1">
                                        <p class="mb-2"><small>{{ __('rfqs.supplier_company_name') }} :</small><br>
                                            <strong>{{ $quote->supplier_company_name }}</strong>
                                        </p>
                                        <p class="mb-2"><small>{{ __('rfqs.supplier_name') }} :</small><br>
                                            <strong>{{ $quote->supplier_name }}</strong>
                                        </p>
                                        <p class="mb-2"><small>{{ __('rfqs.valid_till') }} : </small><br>
                                            <strong>{{ changeDateFormat($quote->valid_till) }}</strong>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive mt-0">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr class="bg-light">
                                            <th>{{ __('rfqs.description') }}</th>
                                            <th class="text-center">{{ __('rfqs.price') }}</th>
                                            <th class="text-center">{{ __('rfqs.QTY') }}</th>
                                            <th align="right" class="text-end">{{ __('rfqs.amount') }}</th>
                                        </tr>
                                        <tr>
                                            <td>{{ $quote->category_name . ' - ' . $quote->sub_category_name . ' - ' . $quote->product_name . ' - ' . $quote->product_description }}</td>
                                            <td class="text-center text-nowrap">{{ 'Rp ' . $quote->product_price_per_unit }} per {{ $quote->unit_name }}</td>
                                            <td class="text-center text-nowrap">{{ $quote->product_quantity . ' ' . $quote->unit_name }}</td>
                                            <td align="right" class="text-nowrap">{{ 'Rp ' . number_format($quote->product_amount, 2) }}</td>
                                        </tr>
                                        @foreach ($quotesCharges as $charges)
                                            <tr>
                                            @if ($charges->type == 0)
                                                <td colspan="3">@if($charges->custom_charge_name) {{$charges->custom_charge_name.' -'}} @endif{{ $charges->charge_name . ' ' . $charges->charge_value }} %</td>
                                            @else
                                                <td colspan="3">@if($charges->custom_charge_name) {{$charges->custom_charge_name.' -'}} @endif{{ $charges->charge_name }}</td>
                                            @endif
                                                <td align="right" class="text-nowrap">{{ $charges->addition_substraction == 0 ? '- ' : '+ ' }}{{ 'Rp ' . number_format($charges->charge_amount, 2) }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="3">{{ 'Tax ' . $quote->tax }} % </td>
                                            <td class="text-end text-nowrap">+ {{ 'Rp ' . number_format($quote->tax_value, 2) }}</td>
                                        </tr>
                                        <tr class="bg-dark text-white">
                                            <td colspan="3">{{ __('rfqs.amount_to_pay') }}</td>
                                            <td align="right" class="text-nowrap">{{ 'Rp ' . number_format($quote->final_amount, 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ URL::asset('front-assets/js/front/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ URL::asset('front-assets/js/front/bootstrap.bundle.min.js') }}"></script>
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
</body>

</html>
