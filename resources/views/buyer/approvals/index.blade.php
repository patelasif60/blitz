@extends('buyer.layouts.frontend.frontend_layout')
<style>
    .QC-font {
        font-size: 13px;
        vertical-align: middle;
    }
    .datehide span {
       display:none;
    }
    /* .compare-quote {
        overflow-x: hidden;
    } */
    .progresscard {
        left: -130px !important;
        bottom: 28px !important;
    }
    .actionquotebtns .btn-box {
        width: 26px;
        height: 26px;
        padding: 4px;
        margin: 2px;
        text-align: center;
        line-height: 1;
    }
    .actionquotebtns .btn-box img {
        max-width: 90%;
        max-height: 90%;
    }
    .progresscard::after {
        content: '';
        left: 58%;
    }
    .progresscard::before {
        content: '';
        left: 58%;
    }
    .btn-outline-primary:hover{
        background-color: transparent !important;
    }
    table.dataTable.no-footer {
    border-bottom: inherit;
}
table.table-bordered.dataTable th:first-child, table.table-bordered.dataTable td:first-child {
    border-left-width: 1px;
}
table.table-bordered.dataTable thead th{border-top-width: 1px;}
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
@section('content')
    <div class="col-lg-8 col-xl-9 py-2" id="mainContentSection">
        <div class="header_top d-flex align-items-center">
            <h1 class="mb-0">{{ __('profile.approvals_tab') }}</h1>
        </div>
        <div class="row gy-3 gx-4">
            <div class="col-md-12">
                <div class="h-100">

                    <!-- RFQ accordion -->
                    <div class="accordion" id="Rfq_accordian">
                        @if (count($rfqWithQuoteApproval) > 0)
                            @livewire('buyer.approvals.approval-list')
                        @endif

                    </div>
                    <!-- /RFQ -->
                    @livewireScripts
                    <script type="text/javascript">
                        $(document).ready(function(){
                            SnippetApprovalList.loadMore()
                            SnippetApprovalList.searchData()
                            SnippetApprovalList.clearSearchData()
                            SnippetApprovalList.approvalQuoteStatus()
                        });
                    </script>

                </div>
            </div>
        </div>

    </div>
<!-- Quote view Popup start-->
    <div class="modal fade" id="rfqQuoteDetailsModal" data-bs-backdrop="true" tabindex="-1"
         aria-labelledby="rfqQuoteDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content radius_1 shadow-lg" id="rfqQuoteDetailsModalBlock">
                {{-- here quote detail shown from js --}}
            </div>
        </div>
    </div>
<!-- Quote view popup end-->
<!-- RFQ View Popup start-->
    <div class="modal fade" id="rfqDetailsModal" data-bs-backdrop="true" tabindex="-1"
         aria-labelledby="rfqDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content radius_1 shadow-lg" id="rfqDetailsModalBlock"></div>
        </div>
    </div>
<!-- RFQ view popup end-->


    <!-- Chat section start -->
    <div class="randomdiv">
        <div class="chat_icon" data-bs-toggle="collapse" href="#chatPopup" role="button" aria-expanded="false" aria-controls="chatPopup" class="btn text-white dropdown-toggle" title="{{ __('admin.chat')}}"  onclick="chat.clickChatIcon('{{ route('group-chattype-count-ajax') }}' , $(this))" id="frontendChatPopupClick">
            {{--@if($dots > 0 && Auth::user()->hasAnyPermission(['list-all buyer rfqs', 'list-all buyer quotes']))
                <span class="dots" id="removeDots"></span>
            @endif--}}
        </div>

        <div class="collapse d-none" id="chatPopup">

            <!-- page1 -->
            <div class="card chat_section d-none border-0" id="chatgrouplist">

                <div class="card-header">
                    <div class="head text-white">{{ __('admin.chat')}}</div>
                </div>
                <div class="card-body">
                    <div class="content">
                        <a href="javascript:void(0)" id="rfqchatgroup" onclick="chat.getChatData('{{ route('group-chat-list-ajax') }}', '{{ Crypt::encrypt("Rfq") }}', 'Rfq')" data-name="rfq"  class="rfqchat m-2 mb-3 px-3 d-flex align-items-center">
                            <div class="text-start">
                                <div class=""><img src="{{ URL::asset('chat/images/icon_rfq.png')}}" class="icon_bg ">
                                    <span class="blue_text_color ps-2">{{ __('dashboard.my_rfqs') }}</span></div>
                            </div>
                            <div class="ms-auto main-chattype-count" id="rfq_chat_count"></div>
                        </a>
                        <a href="javascript:void(0)" id="quotechatgroup" onclick="chat.getChatData('{{ route('group-chat-list-ajax') }}', '{{ Crypt::encrypt("Quote") }}', 'Quote')" data-name="quote" class="quotechat m-2 mb-3 px-3 d-flex align-items-center">
                            <div class="text-start">
                                <div class=""><img src="{{ URL::asset('chat/images/icon_product_details.png')}}" class="icon_bg ">
                                    <span class="blue_text_color ps-2">{{ __('admin.quotes') }}</span>
                                </div>
                            </div>
                            <div class="ms-auto main-chattype-count" id="quote_chat_count"></div>
                        </a>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="card-footer_text">{{ __('dashboard.authorised_by') }} <strong><em>blitznet</em></strong>
                    </div>
                </div>
            </div>
            <!-- page1 -->
            <div class="card chat_section d-none border-0" id="allchatlist"></div>
            <!-- page3 -->
            <div class="card chat_section d-none border-0" id="chatsection"></div>
            <!-- page3 -->
            <!-- page4 -->
            <div class="card chat_section d-none  border-0"  id="rfqproductdetail">
            </div>
            <!-- page4 -->
            <!-- New user with no RFq -->
            <div class="card chat_section d-none border-0" onclick="chat.chatPreviewPage('#rfqproductdetail')" id="newuserchatsection">
            </div>
            <!-- New user with no RFq -->
            <!-- blank page with no group -->
            <div class="card chat_section d-none border-0" id="blankpage">
                <div class="card-header d-flex align-items-center">
                    <div class="head text-white chatnametitle">
                    <span class="pe-2">
                        <i class="fa fa-chevron-left text-white"></i>
                    </span>{{ __('frontend.rfq') }}
                    </div>
                    <div class="head_end_text text-white ms-auto">{{ __('admin.new_chat') }}</div>
                </div>
                <div class="card-body">
                    <div class="content ">
                        <div class="searchbar m-2 px-2 d-flex align-items-center">

                            <div class="">
                                <input class="form-control form-control-sm searchchatrfq" style="width: 288px; height: 40px;" attr-name="catSearch" type="text" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="allrfqchatlist">
                        <div class="blankchatpage">
                            <img src="{{URL::asset('chat/images/blank_image.png')}}" alt="" srcset="">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="card-footer_text">{{ __('dashboard.authorised_by') }} <strong><em>{ __('admin.blitznet') }}</em></strong>
                    </div>
                </div>
            </div>
            <!-- blank page with no group -->
        </div>
    </div>
    <!-- Chat section end -->

    <!-- Approved-Modal -->
    <form id="approvalAcceptFeedbackForm" method="POST" action="{{ route('approvals.getApprovalOtp') }}" autocomplete="off" enctype="multipart/form-data" >
        <div class="modal fade" id="approvedModal" tabindex="-1" aria-labelledby="approvedModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
            @csrf
            <div class="modal-dialog  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title d-flex w-100 align-items-center" id="approvedModalLabel">{{ __('profile.approve_quote_title') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
{{--                        <div class="fs-6 fw-bold mb-2">{{ __('profile.enter_approval_password') }}</div>--}}
                        <div class="col-md-12 form-group mb-3 position-relative">
                            <label class="form-label text-muted fw-bold">{{ __('admin.phone_number') }}<span class="text-danger">*</span></label>
                            <input value="{{Auth::user()->mobile}}" type="mobile" readonly class="form-control border-start-0" name="approveMobile" id="approveMobile" onkeypress="errorRemove(this)">
                            <div id="approveMobileError" class="col-md-12 mb-3 js-validation text-danger"></div>
                        </div>
                    </div>

                    <input type="hidden" name="accept_rfqId" id="accept_rfqId" value="" />
                    <input type="hidden" name="accept_quoteId" id="accept_quoteId" value="" />
                    <input type="hidden" name="accept_feedback" id="accept_feedback" value="" />

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary btn-sm feedbackSubmitButton" id="acceptSubmitBtn" data-modal-id="approvedModal">{{ __('dashboard.get_otp') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- Approved-Modal -->

    <!-- Rejected-Modal -->
    <div class="modal fade" id="rejectedModal" tabindex="-1" aria-labelledby="Rejected-modalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
        <form id="approvalRejectFeedbackForm" method="POST" action="{{ route('approvals.getApprovalOtp') }}" autocomplete="off" enctype="multipart/form-data" class="">
            @csrf
            <div class="modal-dialog  modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title d-flex w-100 align-items-center" id="Rejected-modalLabel">{{ __('profile.reject_quote_title') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body floatlabels">
                        <div>
                            <h6>{{ __('profile.reject_quote_reason') }}<span class="text-danger">*</span></h6>
                            <div class="py-2 row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input staticReason" type="radio" name="reasonForReject" id="priceRadioBtn" value="price" checked required>
                                        <span id="priceRadioBtnError" class="text-danger"></span>
                                        <label class="form-check-label" for="priceRadioBtn">{{ __('admin.price') }}</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input staticReason" type="radio" name="reasonForReject" id="deliveryDateRadioBtn" value="delivery_date" required>
                                        <span id="deliveryDateRadioBtnError" class="text-danger"></span>
                                        <label class="form-check-label" for="deliveryDateRadioBtn">{{ __('profile.delivery_date') }}</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input staticReason" type="radio" name="reasonForReject" id="prodAvailableRadioBtn" value="product_availability" required>
                                        <span id="prodAvailableRadioBtnError" class="text-danger"></span>
                                        <label class="form-check-label" for="prodAvailableRadioBtn">{{ __('profile.products_availability') }}</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input staticReason" type="radio" name="reasonForReject" id="otherReasonBtn" value="other_reason" required>
                                        <span id="otherReasonBtnError" class="text-danger"></span>
                                        <label class="form-check-label" for="otherReasonBtn">{{__('profile.other') }}</label>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-2 other_reason_div">
                                    <div class="">
                                        <label class="form-label text-muted fw-bold">{{ __('profile.other_reason') }}<span class="text-danger">*</span></label>
                                        <textarea class="form-control" placeholder="Please Type Reason here" name="other_reason_text" id="other_reason_text" onkeypress="errorRemove(this)"></textarea>
                                        <span id="other_reason_textError" class="text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="fs-6 fw-bold mb-2">{{ __('profile.enter_reject_password') }}</div>
                        <div class="col-md-6 form-group mb-3 position-relative">
                            <label class="form-label text-muted fw-bold">{{ __('admin.phone_number') }}<span class="text-danger">*</span></label>
                            <input value="{{Auth::user()->mobile}}" readonly type="mobile" class="form-control border-start-0" name="rejectMobile" id="rejectMobile" onkeypress="errorRemove(this)">
                            <span id="rejectMobileError" class="col-md-12 mb-3 js-validation text-danger"></span>
                        </div>
                    </div>

                    <input type="hidden" name="reject_rfqId" id="reject_rfqId" value="" />
                    <input type="hidden" name="reject_quoteId" id="reject_quoteId" value="" />
                    <input type="hidden" name="reject_feedback" id="reject_feedback" value="" />

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary btn-sm feedbackSubmitButton" id="rejectSubmitBtn" data-modal-id="rejectedModal">{{ __('dashboard.get_otp') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- OTP-Modal -->
    <div class="modal fade" id="Otpmodal" tabindex="-1" aria-labelledby="ApprovalOtpLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0 rounded-0">
                    <h5 class="modal-title d-flex w-100 align-items-center" id="ApprovalOtpLabel">{{__('admin.otp_verify')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-0" id="otpBody">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary px-3 py-1 mb-1" id="subForm">{{ __('profile.submit.title') }}</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
<script src="{{ URL::asset('front-assets/intlTelInput/js/intlTelInput.js') }}"></script>
<script src="{{ URL::asset('assets/js/custom/otp.js') }}"></script>
<script>

//Download attachment document
function downloadAttachment(rfq_id,fieldName,ref_no){
    event.preventDefault();
    var data = {
        rfq_id:rfq_id,
        fieldName: fieldName,
        ref_no: ref_no,
    }
    $.ajax({
        url: "{{ route('rfq-attachment-download-ajax') }}",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: data,
        type: 'POST',
        xhrFields: {
            responseType: 'blob'
        },
        success: function (response) {
            var binaryData = [];
            binaryData.push(response);
            var blob = new Blob(binaryData, {type: "application/zip"});
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = ref_no;
            link.click();

        },
    });
}

//Download single attachment document
function downloadimg(rfq_id,fieldName, name){
    event.preventDefault();
    var data = {
        rfq_id:rfq_id,
        fieldName: fieldName
    }
    $.ajax({
        url: "{{ route('rfq-single-attachment-document-ajax') }}",
        headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
        data: data,
        type: 'POST',
        xhrFields: {
            responseType: 'blob'
        },
        success: function (response) {
            var blob = new Blob([response]);
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = name;
            link.click();
        },
    });
}

$(document).ready(function() {
    //Hide textarea for other reason on page load and it is not required
    $(".other_reason_div").hide();
    $('#other_reason_text').prop('required',false);
    var rfqId = $('#quote_compare').attr('data-id');
    $('#quoteListing'+rfqId).dataTable({searching: false, paging: false,"order": [],columnDefs: [{orderable: false,targets: "no-sort"}]});

    var currentActiveRfq = SnippetApp.cookie.getCookie("rfqIdToggle"); // Get rfq toggle id Cookie
    $(".Rfq_Id"+currentActiveRfq).trigger('click'); // Trigger RFQ collapse when cookie set

    $(document).on('click', '.newClass', function () {
        //Hidden values for Accept feedback popup
        $("#accept_rfqId").val($(this).data("rfq-id"));
        $("#accept_quoteId").val($(this).data("quote-id"));
        $("#accept_feedback").val($(this).data("feedback-value"));

        //Hidden values for Reject feedback popup
        $("#reject_rfqId").val($(this).data("rfq-id"));
        $("#reject_quoteId").val($(this).data("quote-id"));
        $("#reject_feedback").val($(this).data("feedback-value"));

    });

    //Accept Quote Password eye functionality
    $("#toggleAcceptPassword").click(function() {
        var d1 = $("#toggleAcceptPassword").attr('data-src');
        var d2 = $("#toggleAcceptPassword").attr('src');
        $("#toggleAcceptPassword").attr('data-src', d2);
        $(this).attr('src', d1);
        var type = $("#approveMobile").attr('type');
        $("#approveMobile").attr('type', type === "mobile" ? "text" : "mobile");
    });

    //Reject Quote Password eye functionality
    $("#toggleRejectPassword").click(function() {
        var d1 = $("#toggleRejectPassword").attr('data-src');
        var d2 = $("#toggleRejectPassword").attr('src');
        $("#toggleRejectPassword").attr('data-src', d2);
        $(this).attr('src', d1);
        var type = $("#rejectMobile").attr('type');
        $("#rejectMobile").attr('type', type === "mobile" ? "text" : "mobile");
    });

    $('body').on('click', '#quote_compare', function() {
        var rfqId = $(this).attr('data-id');
        if($(this).prop('checked'))
        {
            $('#collapse' + rfqId + ' .quoteDetails').find("#subaccordion").addClass("d-none");
            $('#collapse' + rfqId + ' .compare_rfq_quote').find("#compare-quote").removeClass('d-none')
        }
        else{
            $('#collapse' + rfqId + ' .quoteDetails').find("#subaccordion").removeClass("d-none");
            $('#collapse' + rfqId + ' .compare_rfq_quote').find("#compare-quote").addClass('d-none')
        }
    });

    $('[data-bs-toggle="tooltip"]').tooltip();

});

    //Remove error message after add input value
    errorRemove = (obj) => {
        if($('#' + obj.id).val() != '') {
            $('#'+obj.id+'Error').html('');
        }
    }
    var SnippetApprovalFeedback = function() {

        //Show Accept Quote Feedback Popup
        showAcceptQuoteModal = function() {
            $(document).on('click', '.showAcceptQuoteModal', function(e) {
                $("#approvedModal").modal('show');
                $("#approvalAcceptFeedbackForm").trigger('reset');
                $("#approvalAcceptFeedbackForm #approveMobileError").html('');
                $("#acceptSubmitBtn").prop('disabled', false);
            });
        },

        //Show Reject Quote Feedback Popup
        showRejectQuoteModal = function() {
            $(document).on('click', '.showRejectQuoteModal', function(e) {
                $("#rejectedModal").modal('show');
                $("#approvalRejectFeedbackForm").trigger('reset');
                $(".other_reason_div").css('display','none');
                $("#approvalRejectFeedbackForm #rejectMobileError").html('');
                $("#rejectSubmitBtn").prop('disabled', false);
            });
        },

        //On quote collapse, call the function
        triggerQuoteCollapse = function() {
            $(document).on('click', '.approvalQuoteCollapse', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();

                var rfqId = $(this).data("rfq-id");
                var quoteId = $(this).data("quote-id");

                $.ajax({
                    url: "{{ route('approvals.triggerQuoteCollapse') }}",
                    type: 'POST',
                    data: {rfqId:rfqId, quoteId:quoteId},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function (data) {
                        if (data.success == true) {
                            if(data.feedback_exist == 1) {
                                $(".approvalprocess"+quoteId).hide();
                                $(".revokeapprovalprocess"+quoteId).show();
                            } else {
                                $(".approvalprocess"+quoteId).show();
                                $(".revokeapprovalprocess"+quoteId).hide();
                            }

                        } else {
                            new PNotify({
                                text: "{{ __('profile.something_went_wrong') }}",
                                type: 'error',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 1000
                            });
                        }
                    },
                    error: function () {
                        console.log('error');
                    }
                });

            });
        },

        //On click of Revoke button, call the function
        revokeApprovalFeedback = function() {
            $(document).on('click', '.revokeApprovalFeedback', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();

                var rfqId = $(this).data("rfq-id");
                var quoteId = $(this).data("quote-id");

                swal({
                    title: "{{ __('dashboard.are_you_sure') }}?",
                    //text: "{{ __('dashboard.delete_warning') }}",
                    text: "{{ __('frontend.revert_revoke') }}",
                    icon: "/assets/images/info.png",
                    buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.yes') }}'],
                    dangerMode: true,
                })
                .then((willConfirm) => {
                    if (willConfirm) {
                        $.ajax({
                            url: "{{ route('approvals.revokeApprovalFeedback') }}",
                            type: 'POST',
                            data: {rfqId:rfqId, quoteId:quoteId},
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            success: function (data) {
                                if (data.success) {
                                    new PNotify({
                                        text: data.message,
                                        type: 'success',
                                        styling: 'bootstrap3',
                                        animateSpeed: 'fast',
                                        delay: 2000
                                    });

                                    /*$(".newClass").prop('disabled',false);
                                    $(".revokeApprovalFeedback").prop('disabled',true);*/

                                    setTimeout(function () {
                                        SnippetApp.cookie.setCookie('rfqIdToggle',rfqId,'30'); // Set cookie value for rfq collapse
                                        location.reload();
                                    }, 1500);

                                    $("#acceptRejectStatus"+quoteId).html('');
                                    $("#feedbackStatus"+quoteId).html('');
                                    $("#pendingStatus"+quoteId).html('<span class="badge rounded-pill mt-1 bg-white border border-outline-secondary text-primary"><img src="{{URL::asset("front-assets/images/pending.png")}}" alt="{{ __('profile.pending') }}" class="pe-1" width="16">{{ __('profile.pending') }}</span>');



                                } else {
                                    new PNotify({
                                        text: "{{ __('profile.something_went_wrong') }}",
                                        type: 'error',
                                        styling: 'bootstrap3',
                                        animateSpeed: 'fast',
                                        delay: 1000
                                    });
                                }
                            },
                            error: function () {
                                console.log('error');
                            }
                        });
                    }
                });
            });
        },

        pressEnterForFeedback = function() {
            $(document).on('keypress', '#approvalAcceptFeedbackForm #approveMobile', function(e) {
                if(e.keyCode == 13) {
                    return false;
                }
            });

            $(document).on('keypress', '#approvalRejectFeedbackForm #rejectMobile', function(e) {
                if(e.keyCode == 13) {
                    return false;
                }
            });
        },

        // Get Quote details on apporval tab
         getQuoteDetailsPopup = function() {
             $(document).on('click', '.showQuoteModal', function () {
                 var quoteId = $(this).attr('data-id');
                 $.ajax({
                     url: "{{ route('approval-quotes-details', '') }}" + "/" +
                         quoteId,
                     type: 'GET',
                     success: function (successData) {
                         if (successData.html) {
                             $('#rfqQuoteDetailsModalBlock').html(successData.html);
                             $('#rfqQuoteDetailsModal').modal('show');
                         }
                     },
                     error: function () {
                         console.log('error');
                     }
                 });
             });
         },

        // Rfq view popup
        getRfqDetailsPopup = function() {
            $(document).on('click', '.showRfqModal', function() {
                $('#rfqDetailsModal').modal('hide');
                var rfqId = $(this).attr('data-id');
                $.ajax({
                    url: "{{ route('dashboard-get-rfq-details-ajax', '') }}" + "/" +
                        rfqId,
                    type: 'GET',
                    success: function(successData) {
                        if (successData.html) {
                            $('#rfqDetailsModalBlock').html(successData.html);
                            $('#rfqDetailsModal').modal('show');
                        }
                    },
                    error: function() {
                        console.log('error');
                    }
                });
            });
        },

        // RFQ / Quote Collapse closed
        rfqQuoteCloseCollapse = function() {
            $(document).on('click', '.rfqupdatecollpse', function() {
                $('.approvalQuoteCollapse').addClass('collapsed');
                $('.approvalQuoteSubCollapse').removeClass('show');
            });
        }
        //On click of "Other reason", show / hide textarea
        staticReason = function () {
            $(document).on('click', '.staticReason', function() {
                if ($("#otherReasonBtn").prop("checked")) {
                    $(".other_reason_div").show();
                    $('#other_reason_text').prop('required',true);
                } else {
                    $(".other_reason_div").hide();
                    $('#other_reason_text').prop('required',false);
                }
            });
        }

        /**
         * on click of get otp button open OTP model to enter OTP number
         * By Vrutika (29/12/2022)

         */
        getEnterOtpModel = function (){
            $(document).on('click', '.feedbackSubmitButton', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                var type;
                if($("#accept_feedback").val() == 1) {
                    var formData =   $('#approvalAcceptFeedbackForm').serializeArray();
                    var method   =   $('#approvalAcceptFeedbackForm').attr('method');
                    var action   =   $('#approvalAcceptFeedbackForm').attr('action');
                    var feedbackSubmitButton = $("#acceptSubmitBtn");
                    var feedbackModal = $(this).data('modal-id');
                    var rfqId = $("#accept_rfqId").val();
                    var quoteId = $("#accept_quoteId").val();
                    var otpType = 'AcceptApproval';
                } else {
                    var formData =   $('#approvalRejectFeedbackForm').serializeArray();
                    var method   =   $('#approvalRejectFeedbackForm').attr('method');
                    var action   =   $('#approvalRejectFeedbackForm').attr('action');
                    var feedbackSubmitButton = $("#rejectSubmitBtn");
                    var feedbackModal = $(this).data('modal-id');
                    var rfqId = $("#reject_rfqId").val();
                    var quoteId = $("#reject_quoteId").val();
                    var otpType = 'RejectApproval';
                }
                feedbackSubmitButton.prop('disabled', true);
                $.ajax({
                    url: action,
                    data: formData,
                    type: method,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function(data) {
                        if (data.success) {
                            $('#' + feedbackModal).modal('hide');
                            $('#acceptSubmitBtn').prop('disabled', true);
                            OtpApp.sendotp(type = null,otpType);
                            $("#feedbackStatus"+quoteId).html('');
                            $("#pendingStatus"+quoteId).html('');
                        }
                    },
                    error: function(data) {
                        if( data.status === 422 ) {
                            var errors = $.parseJSON(data.responseText);
                            $.each(errors, function (key, value) {
                                if($.isPlainObject(value)) {
                                    $.each(value, function (key, value) {
                                        $('#'+key+'Error').html(value);
                                    });
                                }
                                feedbackSubmitButton.prop('disabled', false);
                            });
                        } else {
                            new PNotify({
                                text: "{{ __('profile.something_went_wrong') }}",
                                type: 'error',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 1000
                            });
                        }
                    },
                });
            });
        }

        initElement = function() {

        };

        return {
            init: function () {
                getEnterOtpModel(),
                /*postFeedbackData(),*/
                triggerQuoteCollapse(),
                revokeApprovalFeedback(),
                showAcceptQuoteModal(),
                showRejectQuoteModal(),
                pressEnterForFeedback(),
                initElement(),
                getQuoteDetailsPopup(),
                getRfqDetailsPopup(),
                rfqQuoteCloseCollapse(),
                staticReason()

            }
        }

    }(1);

    jQuery(document).ready(function(){
        SnippetApprovalFeedback.init();
        SnippetApprovalList.init();
        Otp.init()
    });
    var SnippetApprovalList = function () {

        //Hide approval result data
        $(".showSearchResult").hide();

        // Credit List Load More Data
        loadMore = function() {
            $(document).on('click', '.ldBtn', function(ev) {
                let loading = $('#load-more').val();

                if(loading == 1) {
                    window.livewire.emit('load-list');
                } else {
                    $("#loadMoreBtn").css("pointer-events","none");
                }
            });
        },

        // Input box search with custom text
        searchData = function() {
            // $(document).on('click', '.approvalSearch', function(e) {
            $('.approvalSearch').click(function() {
                let approvalCustomSearch = $('#approvalCustomSearch').val();
                let approvalQuoteStatus = $('#approvalQuoteStatus').val();
                if(approvalCustomSearch || approvalQuoteStatus) {
                    window.livewire.emit('search-approval-data',approvalCustomSearch, approvalQuoteStatus);
                    $("#searchTextErr").html("");
                } else {
                    $("#searchTextErr").html("Please enter valid keyword");
                    return false;
                }
            });

            $('#approvalCustomSearch').keypress(function(e) {
                $("#searchTextErr").html("");
                //Enter key pressed
                if(e.keyCode == 13) {
                    $('.approvalSearch').click();       //Trigger search button click event
                }
            });
        },
        //End

        //On clear searched text (cancel icon), we will get all the data
        clearSearchData = function() {
            $('input[type=search]').on('search', function () {
                $('.approvalSearch').click();
            });
        },

        //Search by quote status dropdown
        approvalQuoteStatus = function() {
            $('#approvalQuoteStatus').change(function() {
                let approvalQuoteStatus = $('#approvalQuoteStatus').val();

                //Call "searchData" function on change of quote status
                $('.approvalSearch').click();

            });
        };

        return {
            init: function () {
                loadMore(),
                searchData(),
                clearSearchData(),
                approvalQuoteStatus()
            },
        }
    }(1);

    // show mobile code

    (function(){
      var mobile = document.getElementById('approveMobile');
      mobile.addEventListener('keypress',function(event){
          if(event.keyCode == 13) {
              event.preventDefault();
          }
      });
    }());
    var input = document.querySelector("#approvalAcceptFeedbackForm #approveMobile");
        var iti = window.intlTelInput(input, {
            initialCountry:"id",
            separateDialCode:true,
            dropdownContainer:null,
            preferredCountries:["id"],
            hiddenInput:"phone_code"
        });

    $("#approvalAcceptFeedbackForm #approveMobile").focusin(function(){
        let countryData = iti.getSelectedCountryData();
        $('input[name="phone_code"]').val(countryData.dialCode);
    });

    $('input[name="phone_code"]').val({{ $phoneCode }});
    iti.setCountry('{{$country}}');

    var inputRej = document.querySelector("#approvalRejectFeedbackForm #rejectMobile");
        var itiRej = window.intlTelInput(inputRej, {
            initialCountry:"id",
            separateDialCode:true,
            dropdownContainer:null,
            preferredCountries:["id"],
            hiddenInput:"phone_code"
        });

    $("#approvalRejectFeedbackForm #rejectMobile").focusin(function(){
        let countryData = itiRej.getSelectedCountryData();
        $('input[name="phone_code"]').val(countryData.dialCode);
    });

    $('input[name="phone_code"]').val({{ $phoneCode }});
    itiRej.setCountry('{{$country}}');

     var Otp = function(){
        return {
            init:function(){
               // frontMenuRedirection()
            },
            varifyOtp:function(){
                if($("#accept_feedback").val() == 1) {
                    var formData =   $('#approvalAcceptFeedbackForm').serializeArray();
                    var method   =   $('#approvalAcceptFeedbackForm').attr('method');
                    var action   =   '{{route('approvals.approvalFeedback')}}';
                    var feedbackSubmitButton = $("#acceptSubmitBtn");
                    var feedbackModal = $(this).data('modal-id');
                    var rfqId = $("#accept_rfqId").val();
                    var quoteId = $("#accept_quoteId").val();
                    feedbackSubmitButton.prop('disabled', true);
                } else {
                    var formData =   $('#approvalRejectFeedbackForm').serializeArray();
                    var method   =   $('#approvalRejectFeedbackForm').attr('method');
                    var action   =   '{{route('approvals.approvalFeedback')}}';
                    var feedbackSubmitButton = $("#rejectSubmitBtn");
                    var feedbackModal = $(this).data('modal-id');
                    var rfqId = $("#reject_rfqId").val();
                    var quoteId = $("#reject_quoteId").val();
                }
                feedbackSubmitButton.prop('disabled', true);
                $.ajax({
                    url: action,
                    data: formData,
                    type: method,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function(data) {
                        if (data.success) {
                            new PNotify({
                                text: data.message,
                                type: 'success',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 1000
                            });
                            $('#' + feedbackModal).modal('hide');
                            feedbackSubmitButton.prop('disabled', true);
                            $('#Otpmodal').modal('hide');
                            if(data.feedbackStatus == 1) {
                                $("#acceptRejectStatus"+quoteId).html('<span class="badge rounded-pill mt-1 bg-white border border-outline-success text-success"><img src="{{ URL::asset("front-assets/images/thumbs-up.png") }}" alt="{{ __('profile.approved') }}" class="pe-1" width="16">{{ __('profile.approved') }}</span>');
                            }else if(data.feedbackStatus == 2){
                                $("#acceptRejectStatus"+quoteId).html('<span class="badge rounded-pill mt-1 bg-white border border-outline-danger text-danger"><img src="{{URL::asset("front-assets/images/thumbs-down.png")}}" alt="{{ __('profile.rejected') }}" class="pe-1" width="16">{{ __('profile.rejected') }}</span>');
                            } else if(data.feedbackStatus == 0) {
                                $("#acceptRejectStatus"+quoteId).html('<span class="badge rounded-pill mt-1 bg-white border border-outline-secondary text-primary"><img src="{{URL::asset("front-assets/images/pending.png")}}" alt="{{ __('profile.pending') }}" class="pe-1" width="16">{{ __('profile.pending') }}</span>');
                            }
                            setTimeout(function () {
                                SnippetApp.cookie.setCookie('rfqIdToggle',rfqId,'30'); // Set cookie value for rfq collapse
                                location.reload();
                            }, 2000);
                            $(".newClass").hide();
                            $(".revokeApprovalFeedback").show();
                            $("#feedbackStatus"+quoteId).html('');
                            $("#pendingStatus"+quoteId).html('');
                        }
                    },
                    error: function(data) {

                        if( data.status === 422 ) {
                            var errors = $.parseJSON(data.responseText);
                            $.each(errors, function (key, value) {

                                if($.isPlainObject(value)) {
                                    $.each(value, function (key, value) {
                                        $('#'+key+'Error').html(value);
                                    });
                                }
                                feedbackSubmitButton.prop('disabled', false);
                            });
                        } else {
                            new PNotify({
                                text: "{{ __('profile.something_went_wrong') }}",
                                type: 'error',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 1000
                            });
                        }

                    },

                });
            },
        }
    }(1);

</script>

@endsection

