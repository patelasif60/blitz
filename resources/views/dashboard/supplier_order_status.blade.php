@extends('dashboard/contact/layout')

@section('content')
    <style>
        p,
        table {
            font-family: 'europaNuova_re';
        }

        .bg-light {
            background-color: #eef1ff !important;
        }

        small {
            color: #0067ff;
        }

        .securityinput {
            padding: 10px;
            border: 1px solid #ccc;
            margin: 0 5px;
            width: 40px;
            font-weight: bold;
            text-align: center;
        }

        .main_section::before {
            height: 54px !important;
        }
    </style>

    <!-- left section end -->
    <div class="col-lg-6 col-xl-9 py-2">
        <div class="header_top ">
            <h1 class="mb-0 text-center"> {{ ($accepted == 1)? __('home.accepted_order') : __('home.accepted_already_order') }} </h1>
        </div>
        <div class="card radius_1 border-0">
            <div class="card-body text-center">
                <div class="text-center"><img src="{{ URL::asset('front-assets/images/accept_order.png') }}" alt="Accept Order"></div>
                <h5 class="mb-3">{{ ($accepted == 1)? __('home.accepted_order_message').'.' : __('home.accepted_already_order_message').'.' }} </h5>
                <p><strong>{{ __('home.order_number') }}: </strong>{{ $order->order_number }}</p>
                <p><strong>{{ __('home.amount') }}: </strong>{{ 'Rp ' . number_format($order->supplier_final_amount, 2) }}</p>
            </div>
        </div>
    </div>

    {{--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ URL::asset('front-assets/js/front/bootstrap.bundle.min.js') }}"></script>--}}
    <script>
        $(document).ready(function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
@stop
