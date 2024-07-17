@extends('admin/adminLayout')
@section('content')
    <div class="col-12 grid-margin  h-100">

        <div class=" row">
            <div class="col-md-12 mb-3 d-flex align-items-center">
                <h1 class="mb-0 h3">{{$loanApply->loan_number}}</h1>
                <a href="{{route('loans-index')}}" class=" backurl btn-close mx-3 ms-auto"></a>
            </div>
            <div class="col-12 mb-2">
                <ul class="nav nav-tabs bg-white newversiontabs ps-3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link px-0 active" id="loan-tab" data-bs-toggle="tab"
                           href="#home-loan" role="tab" aria-controls="home-loan"
                           aria-selected="true">{{__('admin.loan')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-0 ms-5" id="transactions-tab" data-bs-toggle="tab"
                           href="#home-transactions" role="tab" aria-controls="home-transactions"
                           aria-selected="false">{{__('admin.transactions')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-0 ms-5 d-none" data-rfqid="1193" id="profile-tab"
                           data-bs-toggle="tab" href="#profile-2" role="tab" aria-controls="profile-2"
                           aria-selected="false">Activities</a>
                    </li>
                    @if($loanCancelBtnVisible == true)
                    <li class="nav-item ms-auto me-3 mt-2">
                        <a id="cancelLoanApp" class="btn btn-danger btn-sm text-white" href="javascript:void(0);" role="button">{{__('admin.cancel_application')}}</a>
                    </li>
                    @endif
                </ul>
                <div class="tab-content pb-0 pt-3">
                    <div class="tab-pane fade active show" id="home-loan" role="tabpanel" aria-labelledby="loan-tab">
                        <form class="" id="editLoanform" method="POST" enctype="multipart/form-data"
                              action="#" data-parsley-validate="" novalidate="">
                            @csrf
                            <input type="hidden" name="id" value="{{ $loanApply->id }}">
                            <div class="row">
                                <div class=" col-md-12 mb-2">
                                    <section id="contact_detail">
                                        <div class="card">
                                            <div class="card-header d-flex align-items-center">
                                                <h5 class="mb-0">
                                                    <img height="20px" src="{{URL::asset('assets/icons/comment-alt-edit.png')}}" alt="Charges" class="pe-2"> <span>{{__('admin.loan_info')}}</span>
                                                </h5>
                                            </div>
                                            <div class="card-body p-3 pb-1 my-2">
                                                <div class="creditpage row g-3">
                                                    <div class="col-md-4">
                                                        <label for="" class="text-dark">{{__('admin.koinworks_loan_id')}}</label>
                                                        <div>{{$loanApply->provider_loan_id}}</div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="" class="text-dark">{{__('admin.user_id')}}</label>
                                                        <div>{{$loanApply->provider_user_id}}</div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="" class=" text-dark">{{__('admin.order_number')}}</label>
                                                        <div>{{$loanApply->orders->order_number}}</div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="" class=" text-dark">{{__('admin.limit_amount')}}</label>
                                                        <div> {{'Rp '.number_format($loanApply->loanApplications->senctioned_amount,2)}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="" class="text-dark">{{__('admin.loan_amount')}}</label>
                                                        <div>{{'Rp '.number_format($loanApply->loan_confirm_amount,2)}}</div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="" class="text-dark">{{__('admin.available_amount')}}</label>
                                                        <div>{{'Rp '.number_format($loanApply->loanApplications->remaining_amount,2)}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>

                                <div class=" col-md-12 mb-2">
                                    <section id="contact_detail">
                                        <div class="card">
                                            <div class="card-header d-flex align-items-center">
                                                <h5 class="mb-0"><img height="20px"
                                                                      src="{{URL::asset('assets/icons/person-dolly-1.png')}}"
                                                                      alt="Buyer Details"
                                                                      class="pe-2">
                                                    <span>{{ __('admin.buyer_detail') }}</span>
                                                </h5>
                                            </div>
                                            <div class="card-body p-3 pb-1">

                                                <div class="row">
                                                    <div class="col-md-3 pb-2">
                                                        <label class="form-label">{{ __('admin.company_name') }}: </label>
                                                        <div class="text-dark">{{$loanApply->companies->name}}</div>
                                                    </div>
                                                    <div class="col-md-2 pb-2">
                                                        <label class="form-label">{{ __('admin.customer_name') }}:</label>
                                                        <div class="text-dark">{{$loanApply->loanApplicants->first_name .' '.$loanApply->loanApplicants->last_name}}</div>
                                                    </div>
                                                    <div class="col-md-2 pb-2">
                                                        <label class="form-label">{{ __('admin.customer_phone') }}:</label>
                                                        <div class="text-dark text-nowrap"> +62 {{$loanApply->loanApplicants->loanApplicantBusiness->phone_number}}</div>
                                                    </div>
                                                    <div class="col-md-3 flex-fill pb-2">
                                                        <label class="form-label">{{ __('admin.customer_email') }}:</label>
                                                        <div class="text-dark">{{$loanApply->loanApplicants->loanApplicantBusiness->email}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                                <div class=" col-md-12 mb-2">
                                    <section id="product_detail">
                                        <div class="card">
                                            <div class="card-header d-flex align-items-center">
                                                <h5 class="mb-0"><img
                                                        src="{{URL::asset('assets/icons/credit-card.png')}}"
                                                        alt="Payment Detail"
                                                        class="pe-2">
                                                    <span>{{ __('admin.payment_detail') }}</span>
                                                </h5>
                                                <span class="ms-auto fw-bold">{{__('admin.loan_terms')}}:</span>
                                                <span class="ms-2 fw-bold">30 Days</span>


                                            </div>
                                            <div class="card-body p-3 pb-1">
                                                <div class="row g-4 mb-4">

                                                    <div class="table-responsive">
                                                        <table class="table text-dark table-striped">
                                                            <tbody>
                                                            <tr class="bg-light">
                                                                <th>{{__('admin.item_number')}}</th>
                                                                <th>{{__('admin.description')}}</th>
                                                                <th>{{__('admin.price')}}</th>
                                                                <th>{{__('admin.qty')}}</th>
                                                                <th align="right" class="text-end">{{__('admin.amount')}}</th>
                                                            </tr>
                                                        {{--start items--}}
                                                            @foreach ($loanApply->orderItems as $orderItems)
                                                                @php
                                                                    $unit = get_unit_name($orderItems->quoteItem->price_unit);
                                                                @endphp
                                                                <tr>
                                                                    <td>{{$orderItems->order_item_number}}</td>
                                                                    <td>{{ get_product_name_by_id($orderItems->rfq_product_id,1) }}</td>
                                                                    <td>Rp {{number_format($orderItems->quoteItem->product_price_per_unit,2)}} per {{$unit}}</td>
                                                                    <td>{{$orderItems->quoteItem->product_quantity}} {{$unit}}</td>
                                                                    <td align="right">Rp {{number_format($orderItems->quoteItem->product_amount,2)}}</td>
                                                                </tr>
                                                            @endforeach

                                                        {{--end items--}}
                                                            @foreach ($amountDetails as $charges)
                                                                <tr>
                                                                    @if ($charges->type == 0)
                                                                        <td colspan="4">{{ $charges->charge_name . ' ' . $charges->charge_value }}</td>
                                                                    @else
                                                                        <td colspan="4">{{ $charges->charge_name }}</td>
                                                                    @endif
                                                                    <td align="right">
                                                                        {{ $charges->addition_substraction == 0 ? '- ' : '+ ' }}{{ 'Rp ' . number_format($charges->charge_amount, 2) }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            <tr>
                                                                <td colspan="4">{{ __('admin.tax') }} {{ $quote->tax .'%'}}</td>
                                                                <td align="right">{{number_format($quote->tax_value, 2)}}</td>
                                                            </tr>
                                                            <tr class="bg-secondary text-white">
                                                                <td colspan="4" class="text-white fw-bold">{{__('admin.total_amount')}}</td>
                                                                <td align="right" class="text-white fw-bold">Rp {{number_format($quote->final_amount, 2)}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="4">2% Interest for 30 Days</td>
                                                                <td align="right">+ Rp {{number_format($loanIntrestCal['interest_amount'],2)}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="4">{{__('admin.repayment_charges')}}</td>
                                                                <td align="right">+ Rp {{number_format($loanIntrestCal['repayment_charges'],2)}}</td>
                                                            </tr>
                                                            @if($loanIntrestCal['internal_transfer_charge_count'])
                                                                <tr>
                                                                    <td colspan="4">{{$loanIntrestCal['internal_transfer_charge_count'] .' '. __('admin.internal_transfer_charge')}}</td>
                                                                    <td align="right">+ Rp {{number_format($loanIntrestCal['total_internal_transfer_charge'],2)}}</td>
                                                                </tr>
                                                            @endif
                                                            @if($loanIntrestCal['origination_charge_count'])
                                                                <tr>
                                                                    <td colspan="4">{{$loanIntrestCal['origination_charge_count'] .' '. __('admin.origination_charge')}}</td>
                                                                    <td align="right">+ Rp {{number_format($loanIntrestCal['total_origination_charge'],2)}}</td>
                                                                </tr>
                                                            @endif
                                                            @if($loanIntrestCal['vat'])
                                                                <tr>
                                                                    <td colspan="4">{{$loanIntrestCal['vat'] .'% '. __('admin.vat')}}</td>
                                                                    <td align="right">+ Rp {{number_format($loanIntrestCal['total_vat'],2)}}</td>
                                                                </tr>
                                                            @endif
                                                            @if($loanIntrestCal['late_fee_count'])
                                                                <tr>
                                                                    <td colspan="4">{{$loanIntrestCal['late_fee_count'] .' '. __('admin.late_fee')}}</td>
                                                                    <td align="right">+ Rp {{number_format($loanIntrestCal['total_late_fee'],2)}}</td>
                                                                </tr>
                                                            @endif
                                                            <tr class="bg-secondary text-white">
                                                                <td colspan="4" class="text-white fw-bold">{{__('admin.payable_amount')}}</td>
                                                                <td align="right" class="text-white fw-bold">Rp {{number_format($loanIntrestCal['payable_amount'],2)}}</td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>

                                <div class="col-md-12 mb-2 d-none">
                                    <section id="payment_details">
                                        <div class="card">
                                            <div class="card-header d-flex align-items-center">
                                                <h5 class="mb-0"><img height="20px"
                                                                      src="{{URL::asset('assets/icons/credit-card.png')}}"
                                                                      alt="Payment Details" class="pe-2">
                                                    <span>Invoice Policy</span>
                                                </h5>
                                            </div>
                                            <div class="card-body p-3 pb-1">
                                                <div class="row g-4 pt-2">
                                                    <div class="col-md-3 mb-3">
                                                        <label for="" class="form-label">Invoice</label>
                                                        <div class="d-flex py-2">
                                                                            <span class="">
                                                                                <input type="file" name="attached_document" class="form-control" id="attached_document" accept=".jpg,.png,.jpeg,.pdf" onchange="showFile(this)" hidden="">
                                                                                <label id="upload_btn" for="attached_document">Browse</label>
                                                                            </span>
                                                            <div id="file-attached_document" class="d-flex align-items-center">
                                                                <input type="hidden" class="form-control" id="old_attachment_file" name="old_attachment_file" value="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                                <div class="col-md-12 bg-white py-3 d-flex justify-content-end">
                                    @if($loanApply->status_id == 16)
                                    <button type="button" class="btn btn-primary checkRepayAmountBtn" id="check_repay_amount" data-id="{{$loanApply->id}}">{{__('admin.check_repay_amount')}}</button>
                                    @endif
                                    <a href="{{route('loans-index')}}" style="float: right;" class=" ms-3 btn btn-cancel"> Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade activityopen" id="home-transactions" role="tabpanel" aria-labelledby="transactions-tab" data-rfqid="1193">
                        <div class="card mb-3">
                            <div class="card-header d-flex align-items-center">
                                <h5 class="mb-0">
                                    <img height="20px" src="{{URL::asset('assets/icons/total.png')}}" alt="Charges" class="pe-2"> <span>{{__('admin.transactions')}}</span>
                                </h5>
                            </div>
                            <div class="card-body p-3 pb-2">
                                <div class="row creditpage gy-3 gx-2">
                                    <div class="col-md-12 ">
                                        <table class="table border">
                                            <thead>
                                                <tr class="bg-light">
                                                    <th>No.</th>
                                                    <th>{{__('admin.Date')}}</th>
                                                    <th>{{__('admin.transaction_ac_type')}}</th>
                                                    <th>{{__('admin.loan_status')}}</th>
                                                    <th>{{__('admin.transaction_type')}}</th>
                                                    <th>{{__('admin.amount')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($loanTransaction as $loanTransaction)
                                                    <tr>
                                                        <td>{{$loanTransaction->id}}</td>
                                                        <td>{{\Carbon\Carbon::parse($loanTransaction->created_at)->format('d-m-Y')}}</td>
                                                        <td>{{$loanTransaction->transaction_ac_type == 1?'Credit':'Debit' }}</td>
                                                        <td>{{$loanTransaction->loanStatus->status_display_name}}</td>
                                                        <td>{{$loanTransaction->transactionsType->name}}</td>
                                                        <td>{{'Rp '.number_format($loanTransaction->transaction_amount,2)}}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Activites -->
                    <div class="tab-pane fade activityopen" id="profile-2" role="tabpanel"
                         aria-labelledby="profile-tab" data-rfqid="1193">
                        <div class="card mb-3">
                            <div class="card-header d-flex align-items-center">
                                <h5 class="mb-0"><img height="20px"
                                                      src="{{URL::asset('assets/icons/icon_update_activities.png')}}"
                                                      alt="Charges" class="pe-2"> <span>Recent Activities</span></h5>
                            </div>
                            <div class="card-body p-3 pb-1">
                                <ul class="bullet-line-list">

                                    <li class="pb-3">


                                        <p class="h6">Rizko Siregar created BRFQ-1193 </p>
                                        <small class="d-flex align-items-top">
                                            <i class="mdi mdi-clock-outline me-1"></i>
                                            30-06-2022 09:28:17
                                        </small>
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- </div> -->
            </div>
            <div class="col-md-12 d-none" id="repayment_calculation_div" >

            </div>
    </div>

<script type="text/javascript">
    $(document).ready(function () {
        tinymce.activeEditor.mode.set("readonly");
        setTimeout(function () {
            $('iframe#description_ifr').css('background','#e9ecef');
        },1000);
    });

    //On click show repay amount calculation (Ekta P)
    $(document).on('click', '.checkRepayAmountBtn', function(e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        if (id) {
            $.ajax({
                url: "{{ route('check-loan-repay-calculation-ajax', '') }}" + "/" + id,
                type: 'GET',
                success: function(successData) {
                    $('#repayment_calculation_div').html(successData.html);
                    $('#repayment_calculation_div').removeClass('d-none');
                },
                error: function() {
                    console.log('error');
                }
            });
        }
    });

    $(document).on('click', '#transactions-tab', function(e) {
        e.preventDefault();
        $('#repayment_calculation_div').removeClass('d-none');
        $('#repayment_calculation_div').html('');
    });

    $(document).on('click', '#cancelLoanApp', function(e) {
        e.preventDefault();
        // Begin: popup
        swal({
            title: "{{ __('admin.delete_sure_alert') }}",
            text: "{{ __('admin.loan_cancel_text') }}",
            icon: "/assets/images/warn.png",
            buttons: ["{{ __('admin.cancel') }}", "{{ __('admin.ok') }}"],
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $('#cancelLoanApp').prop("disabled", true);
                loanCancel("{{$providerUserId}}", "{{$providerLoanId}}", "{{$orderId}}");
            }
        });
        // End: popup
    });

    function loanCancel(userId, loanId, orderId){
        let data = {
            "_token": "{{ csrf_token() }}",
            "userId": userId,
            'loanId': loanId,
            'orderId': orderId
        };

        $.ajax({
            url: "{{ route('admin.credit.loan.cancel') }}",
            type: 'POST',
            data: data,
            success: function(successData) {
                if(successData.status == true){
                    $.toast({
                        heading: "{{__('admin.success')}}",
                        text: successData.message,
                        showHideTransition: "slide",
                        icon: "success",
                        loaderBg: "#f96868",
                        position: "top-right",
                    });

                    setTimeout(function(){window.location.reload() } , 3000);
                }
            },
            error: function() {
                console.log('error');
            }
        });
    }

</script>
@stop
