@extends('dashboard/contact/layout')

@section('content')
    <style>
        @keyframes slideInFromLeft {
            0% {
                transform: translateY(-100%);
            }

            100% {
                transform: translateY(0);
            }
        }
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

        .main_section::before {
            height: 54px !important;
        }

        .ap-otp-input{
            padding: 10px;
            border: 1px solid #ccc;
            margin: 0 5px;
            width: 40px;
            font-weight: bold;
            text-align: center;
        }


        .ap-otp-input:focus{
            outline: none !important;
            border:1px solid #1f6feb;
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
        /*.securityinput{ padding: 10px 15px; border: 0px solid #ccc; margin: 0 5px; width: 260px; height: 43px; font-weight: bold; text-align: left; background-image: url(icon_securitycode.gif); background-repeat: no-repeat; background-position: center center; letter-spacing: 36px; }*/
    </style>

    <!-- left section end -->
    <div class="col-lg-6 col-xl-9 py-2">
        <div class="header_top ">
            <h1 class="mb-0 text-center">{{ __('home.order_details') }}</h1>
        </div>
        <div class="card radius_1 border-0">
            <div class="card-body">
                <!-- <div class="text-center"><img src="images/accept_order.png" alt="Accept Order"></div> -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="detail_1">
                            <p class="mb-2"><small>{{ __('home.order_number') }}: </small><br>
                                <strong>{{ $order->order_number }}</strong>
                            </p>
                            <p class="mb-2"><small>{{ __('home.quote_number') }}: </small><br>
                                <strong>{{ $order->quote_number }}</strong>
                            </p>
                            <p><small>{{ __('home.date') }}: </small><br>
                                <strong>{{ $order->created_at }}</strong>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail_1">
                            <p class="mb-2"><small>{{ __('home.supplier_company') }}: </small><br>
                                <strong>{{ $order->supplier_company_name }}</strong>
                            </p>
                            <p class="mb-2"><small>{{ __('home.supplier_name') }}: </small><br>
                                <strong>{{ $order->supplier_name }}</strong>
                            </p>
                        </div>
                    </div>
                </div>
                @php
                    $finalAmount = 0;
                    $discount = 0;
                @endphp
                <div class="table-responsive mt-0">
                    <table class="table table-bordered">
                        <tbody>
                        <tr class="bg-light">
                            <th>Description</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">QTY</th>
                            <th align="right" class="text-end">Amount</th>
                        </tr>
                        {{--<tr>
                            <td>{{ $order->category_name . ' - ' . $order->sub_category_name . ' -  ' . $order->product_name . ' - ' . $order->product_description }}</td>
                            <td class="text-center text-nowrap">{{ 'Rp ' . number_format($order->product_price_per_unit, 2) }} per {{ $order->unit_name }}</td>
                            <td class="text-center text-nowrap">{{ $order->product_quantity . ' ' . $order->unit_name }}</td>
                            <td align="right" class="text-nowrap">{{ 'Rp ' . number_format($order->product_amount, 2) }}</td>
                        </tr>--}}
                        @foreach($orderItems as $key => $value)
                            <tr>
                                <td>{{ get_product_name_by_id($value->rfq_product_id, 1) }}</td>
                                <td>{{ 'Rp ' . number_format($value->product_price_per_unit, 2) }} per {{ $order->unit_name }}</td>
                                <td>{{ $value->product_quantity??'' }} {{ $quote->unit_name??'' }}</td>
                                <td align="right">{{ 'Rp ' . number_format($value->product_amount, 2) }}</td>
                            </tr>
                            @php
                                $finalAmount += $value->product_price_per_unit * $value->product_quantity ;
                            @endphp
                        @endforeach
                        @foreach ($quotes_charges_with_amounts as $charges)
                            <tr>
                                @if ($charges->type == 0)
                                    <td colspan="3">@if($charges->custom_charge_name) {{$charges->custom_charge_name.' -'}} @endif {{ $charges->charge_name . ' ' . $charges->charge_value }} % <small class="fw-bold text-blue">({{ __('admin.supplier_other_charges') }})</small></td>
                                @else
                                    <td colspan="3">@if($charges->custom_charge_name) {{$charges->custom_charge_name.' -'}} @endif {{ $charges->charge_name }} <small class="fw-bold text-blue">({{ __('admin.supplier_other_charges') }})</small></td>
                                @endif
                                   @php
                                    if ($charges->charge_name != 'Discount') {
                                        if ($charges->addition_substraction == 0) {
                                            $finalAmount = $finalAmount - $charges->charge_amount;
                                        } else {
                                            $finalAmount = $finalAmount + $charges->charge_amount;
                                        }
                                    } else {
                                        $discount = $charges->charge_amount;
                                    }
                                @endphp

                                    <td align="right" class="text-nowrap">{{ $charges->addition_substraction == 0 ? '- ' : '+ ' }}{{ 'Rp ' . number_format($charges->charge_amount, 2) }}</td>
                                </tr>
                            @endforeach
                            @php

                                $totalAmount = $finalAmount - $discount;
                                $taxamount = ($totalAmount * $order->tax) / 100;
                                $payamount = $totalAmount + $taxamount;
                        @endphp
                        {{--<tr>
                            <td colspan="3">Total</td>
                            <td align="right" class="text-nowrap">{{ 'Rp ' . number_format($totalAmount, 2) }}</td>
                        </tr>--}}

                        <tr>
                            <td colspan="3">Tax {{ $order->tax .'%'}}</td>
                            <td align="right" class="text-nowrap"> + {{ 'Rp ' . number_format($taxamount, 2) }}</td>
                        </tr>
                        <tr class="bg-dark text-white">
                            <td colspan="3">Amount to Pay</td>
                            <td align="right" class="text-nowrap">{{ 'Rp ' . number_format($payamount, 2) }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <form method="post" action="{{ route('supplier-otp') }}" id="for_Supplier_otp">
                    @csrf
                    <input type="hidden" name="laststatus" value="1">
                    <input type="hidden" name="selectedstatus" value="2">
                    <input type="hidden" name="order_id" id="order_id" value="{{ $order->id }}">
                    <div class="row justify-content-center">
                        <div class="col-md-12 text-center"><p class="fw-bold">If you agree to accept the order please enter
                                the security code:</p></div>
                        <div class="col-auto">
                            <div class="input-group mb-3">
                                {{--<input type="text" class=""  id="otp" name="otp_supplier" aria-label="">--}}
                                {{--<input type="hidden" id="otp" name="otp_supplier" aria-label="">--}}
                                {{--<input class="securityinput" type="number" maxlength="1" data-index="0" aria-label="">
                                <input class="securityinput" type="number" maxlength="1" data-index="1" aria-label="">
                                <input class="securityinput" type="number" maxlength="1" data-index="2" aria-label="">
                                <input class="securityinput" type="number" maxlength="1" data-index="3" aria-label="">
                                <input class="securityinput" type="number" maxlength="1" data-index="4" aria-label="">
                                <input class="securityinput" type="number" maxlength="1" data-index="5" aria-label="">--}}
                                <div class="ap-otp-inputs" >
                                    <input class="ap-otp-input" type="tel" name="otp1" pattern="[0-9]" maxlength="1" data-index="0">
                                    <input class="ap-otp-input" type="tel" name="otp2" pattern="[0-9]" maxlength="1" data-index="1">
                                    <input class="ap-otp-input" type="tel" name="otp3" pattern="[0-9]" maxlength="1" data-index="2">
                                    <input class="ap-otp-input" type="tel" name="otp4" pattern="[0-9]" maxlength="1" data-index="3">
                                    <input class="ap-otp-input" type="tel" name="otp5" pattern="[0-9]" maxlength="1" data-index="4">
                                    <input class="ap-otp-input" type="tel" name="otp6" pattern="[0-9]" maxlength="1" data-index="5">
                                </div>
                                <span class="input-group-text" data-bs-toggle="tooltip" data-bs-placement="top" title="Please check mail for security code."><img src="{{ URL::asset('front-assets/images/icons/exclamation.png') }}" alt="info"></span>
                            </div>
                        </div>
                        <div class="col-md-12 text-center mb-3">
                            <a href="javascript:void(0)" id="submit_otp" data-id="{{ $order->id }}" class="btn btn-info">{{ __('home.Accept') }}</a>
                            <!-- <a href="" class="btn btn-primary btn-sm"> Back to Home</a> -->
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>

        $(document).ready(function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
            $(document).on('click', '#submit_otp', function(e) {
                e.preventDefault();

                var otp = $('input[name="otp1"]').val()+$('input[name="otp2"]').val()+$('input[name="otp3"]').val()+$('input[name="otp4"]').val()+$('input[name="otp5"]').val()+$('input[name="otp6"]').val();
                /*var formdata = new FormData();
                formdata.append("_token", $('meta[name="csrf-token"]').attr('content'));
                formdata.append("otp_supplier", otp);
                formdata.append("order_id", $("#order_id").val());
                $.ajax({
                    url: '{{ route('check-supplier-otp') }}',
                    type: 'POST',
                    data: formdata,
                    contentType: false,
                    processData: false,
                    success: function(successData) {
                        if(successData.success){
                            new PNotify({
                                text: "{{ __('Invalid Otp') }}",
                                type: 'error',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 1000
                            });
                        } else {*/
                            var newFormdata = new FormData($("#for_Supplier_otp")[0]);
                            newFormdata.append("otp_supplier", otp);
                            $.ajax({
                                url: $('#for_Supplier_otp').attr('action'),
                                type: $('#for_Supplier_otp').attr('method'),
                                data: newFormdata,
                                contentType: false,
                                processData: false,
                                success: function(successDataOTP) {
                                    if(successDataOTP.ErrorOTP){
                                        new PNotify({
                                            text: "{{ __('Invalid Otp') }}",
                                            type: 'error',
                                            styling: 'bootstrap3',
                                            animateSpeed: 'fast',
                                            delay: 1000
                                        });
                                    } else {
                                        window.location = successDataOTP.url;
                                    }

                                },
                                error: function() {
                                    console.log('error');
                                }
                            });
                        });
                    /*},
                    error: function() {
                        console.log('error');
                    }
                });*/
            /*});*/

            $('.ap-otp-input').keypress(function (e) {
                var charCode = (e.which) ? e.which : event.keyCode
                if (String.fromCharCode(charCode).match(/[^0-9]/g))
                    return false;
            });
        });

        const $inp = $(".ap-otp-input");

        $inp.on({
            paste(ev) { // Handle Pasting
                const clip = ev.originalEvent.clipboardData.getData('text').replaceAll(/\s/g,'').trim();
                // Allow numbers only
                if (!/\d{6}/.test(clip)) return ev.preventDefault(); // Invalid. Exit here
                // Split string to Array or characters
                const s = [...clip];
                // Populate inputs. Focus last input.
                $inp.val(i => s[i]).eq(5).focus();
            },
            input(ev) { // Handle typing
                const i = $inp.index(this);
                if (this.value) $inp.eq(i + 1).focus();
            },
            keydown(ev) { // Handle Deleting
                const i = $inp.index(this);
                if (!this.value && ev.key === "Backspace" && i) $inp.eq(i - 1).focus();
            }

        });






    </script>
@stop
