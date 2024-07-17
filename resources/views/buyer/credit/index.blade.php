@extends('buyer.layouts.frontend.frontend_layout')

@section('content')
    <div class="col-lg-8 col-xl-9 py-2" id="mainContentSection">
        <div class="header_top d-flex align-items-center">
            <h1 class="mb-0">{{ __('dashboard.credit') }}</h1>
            <a href="{{route('credit.wallet.transactions')}}" class="btn btn-warning ms-auto btn-sm" style="padding-top: .1rem; padding-bottom: .1rem;">
                <img src="{{asset('assets/images/credit_transaction.png')}}"  alt=""> {{ __('admin.transactions') }}
            </a>
        </div>
        <div class="row creditpage gy-3 gx-2">
            <div class="col-md-12">
                <div class="card h-100">
                    <div class="card-header bg-white d-flex">
                        <div class="fw-bold" style="font-size: 15px;">{{ __('dashboard.credit_info') }} - {{ $creditInfoDetail->loan_application_number }}</div>
                        @if (!empty($creditInfoDetail->expire_date))
                            <div class="col-md-3 ms-auto align-items-center d-flex ">
                                <label class="me-2 ms-auto">{{__('admin.expiry_date')}}:</label>
                                <small>{{!empty($creditInfoDetail->expire_date) ? \Carbon\Carbon::parse($creditInfoDetail->expire_date)->format('d-m-Y') : '-'}}</small>
                            </div>
                        @endif
                    </div>
                    @php
                        if(!empty($creditInfoDetail->senctioned_amount)){
                            if($creditInfoDetail->remaining_amount!='')
                            $usedLimit = $creditInfoDetail->senctioned_amount - $creditInfoDetail->remaining_amount;
                            else
                            $usedLimit = $creditInfoDetail->senctioned_amount - 0;
                            $progress = ($usedLimit * 100) / $creditInfoDetail->senctioned_amount ;
                            $unusedLimit = $creditInfoDetail->senctioned_amount- $usedLimit;
                        }
                    @endphp
                    <div class="card-body">
                        <div class="creditpage row g-3 mx-lg-5">
                            <div class="creditlimit">
                                <div class="mx-5">
                                    <div class="limit d-flex justify-content-between">
                                        <div class="text" style="font-size: 13px">{{ __('dashboard.total_credit_limit') }}</div>
                                        <div class="ms-auto fw-bold number" style="font-size: 13px">Rp {{ isset($creditInfoDetail->senctioned_amount) ? number_format($creditInfoDetail->senctioned_amount) : 0 }}</div>
                                    </div>
                                    <div class="progress my-2" style="height: 8px !important;">
                                        <div class="progress-bar" role="progressbar"  style="width: {{$progress??0}}%" aria-valuenow="{{$progress??0}}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>

                                <div class="limit d-flex justify-content-between">
                                    <div class="text">
                                        <div>{{ __('dashboard.used_credit_limit') }}</div>
                                        <div class="fw-bold">Rp {{ !empty($usedLimit) ? number_format($usedLimit) : 0.00  }}</div>
                                    </div>
                                    <div class="ms-auto" >
                                        <div>{{ __('dashboard.unused_credit_limit') }}</div>
                                        <div class="fw-bold">Rp {{ !empty($unusedLimit) ? number_format($unusedLimit) :0.00 }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
        @livewire('buyer.credit.credit-list')

    </div>
    @livewireScripts
    <script type="text/javascript">
        $(document).ready(function(){
            SnippetCreditList.loadMore()
        });
    </script>

@endsection
@section('script')
    <script type="text/javascript">
        var SnippetCreditList = function () {

            var getPayLink = function () {
                $(document).on('click','.generate-payment-link',function(){

                    var paymentObj = $(this);

                    paymentObj.children('span.generate-payment-link-text').addClass('text-vertical-center');
                    paymentObj.addClass('custom-disable');
                    paymentObj.children('div.spinner-border').removeClass('d-none');

                    $.ajax({
                        url:'{{ route('credit.wallet.payment.link.build') }}',
                        method:'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: {'id' : $(this).attr('data-id')},
                        success : function(data){

                            if (data.success) {
                                $('.repayment[data-id="'+paymentObj.attr('data-id')+'"]').removeClass('d-none');

                                paymentObj.addClass('d-none');
                                paymentObj.closest('a').next('.payment-link').attr('href',data.data);
                                paymentObj.closest('a').next('.payment-link').removeClass('d-none');

                            } else {
                                new PNotify({
                                    text: data.message,
                                    type: 'error',
                                    styling: 'bootstrap3',
                                    animateSpeed: 'fast',
                                    delay: 3000
                                });
                                paymentObj.children('span.generate-payment-link-text').removeClass('text-vertical-center');
                                paymentObj.removeClass('custom-disable');
                                paymentObj.children('div.spinner-border').addClass('d-none');
                            }

                        },

                        error : function () {
                            new PNotify({
                                text: "{{__('admin.something_went_wrong')}}",
                                type: 'error',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 3000
                            });
                            paymentObj.children('span.generate-payment-link-text').removeClass('text-vertical-center');
                            paymentObj.removeClass('custom-disable');
                            paymentObj.children('div.spinner-border').addClass('d-none');
                        }
                    });
                });
            };

            return {
                init: function () {
                    getPayLink()
                },
                // Credit List Load More Data
                loadMore: function() {
                    document.onscroll = function (ev) {
                        let loading = $('#load-more').val();
                        if (((window.innerHeight + window.scrollY) >= document.body.offsetHeight) && loading==1) {
                            window.livewire.emit('load-list');
                        }
                    };
                }
            }
        }(1);

        jQuery(document).ready(function(){
            SnippetCreditList.init()
        });
    </script>
@endsection

