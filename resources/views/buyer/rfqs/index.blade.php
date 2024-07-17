@extends('buyer.layouts.frontend.frontend_layout')
@section('content')
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
</style>
<div class="col-lg-8 col-xl-9 py-2" id="rfqspagination">
    <div class="header_top d-flex align-items-center">
        <h1 class="mb-0">{{ __('dashboard.my_rfqs') }}</h1>
        <div class="dropdown ms-auto favbtn">
            <a href="javascript:void(0);" class="favorite_0 btn btn-outline-danger py-1 me-1 js-favoriteRfq"  role="button" data-isFavRfqSectionClicked = "1" style="padding: 0.125rem 0.4rem; font-size: .8rem;">
                <span>
                    <i class="addtofavouriteRfq_0 fa fa-star-o  me-1"></i>
                </span>
                {{ __('dashboard.favourite') }}
            </a>
            <input type="hidden" name="is_favouriteRfq" id="is_favouriteRfq_0" value="1">
            <input type="hidden" name="new_fev" id="new_fev" value="0">
            <button class="btn btn-warning btn-sm dropdown-toggle" style="padding: 0.125rem 0.4rem;" type="button"
                id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"> {{ __('admin.Export') }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end p-0 shadow-lg" aria-labelledby="dropdownMenuButton1">
                <li class="border-bottom ">
                    <a class="dropdown-item text-dark p-1 ps-2" style="font-size: 0.9em;" href="{{ route('export-excel-rfq-ajax') }}">
                        <span>
                            <img src="{{ URL::asset('front-assets/images/icons/excel-file.png') }}" class="me-1" alt="" height="14px" srcset="">
                        </span> 
                        {{ __('dashboard.my_rfqs') }}
                    </a>
                </li>
                <li class="">
                    <a class="dropdown-item text-dark p-1 ps-2" style="font-size: 0.9em;" href="{{ route('export-excel-rfq-quotes-ajax') }}">
                        <span>
                            <img src="{{ URL::asset('front-assets/images/icons/excel-file.png') }}" class="me-1" alt="" height="14px" srcset="">
                        </span>
                        {{ __('admin.rfq_quotes') }}
                    </a>
                </li>

            </ul>
        </div>
    </div>
    <div class="accordion" id="Rfq_accordian">
        <div class="col-md-12 mb-3 d-flex">
            <div class="col-md-3">
                <select class="form-select py-2"  name="customStatusSearch" id="customStatusSearch">
                    <option value="all">{{ __('rfqs.all') }}</option>
                    @if(count($rfqStatus))
                        @foreach($rfqStatus as $value)
                            <option value="{{$value->id}}">{{ __('rfqs.' . $value->name) }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="ms-auto d-flex align-items-center">
                <small class="text-nowrap me-2 mt-2 showSearchData"><span id="searchResultCount">0</span> {{ __('rfqs.result_found') }}</small>
                <div id="grouptrading_search" class="input-group mb-0">
                    <input class="form-control form-control-sm" type="search" name="customSearch" placeholder="{{ __('admin.search') }}" value="{{!empty($customSearch) ? $customSearch : '' }}" id="customSearch"  aria-label="{{ __('admin.search') }}">
                    <button class="btn bg-light text-white btn-sm search border customSearch" type="button">
                        <img src="{{ URL::asset('assets/images/icon_search_b.png') }}" alt="{{ __('admin.search') }}">
                    </button>
                </div>

                <div class="text-end">
                    <small class="text-danger" id="searchTextErr"></small>
                </div>
            </div>
        </div>
        <livewire:buyer.rfq.rfq-list perPage="10" page="1" favrfq="0" falg=1/>
    </div>
</div>

<div class="col-lg-8 col-xl-9 py-2 hide" id="repeatRfqform">
    {{-- here repete content are showing --}}
</div>

<div class="modal fade" id="rfqQuoteDetailsModal" data-bs-backdrop="true" tabindex="-1"
    aria-labelledby="rfqQuoteDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content radius_1 shadow-lg" id="rfqQuoteDetailsModalBlock">
            {{-- here quote detail shown from js --}}
        </div>
    </div>
</div>
<div class="modal right fade editmodal" id="editModal" data-bs-backdrop="static"  data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">

        <div class="modal-content showeditmodal">

        </div>
    </div>
</div>
<div class="modal fade" id="placeOrderModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="placeOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content radius_1 shadow-lg error_res" id="placeOrderModalBlock">

        </div>
    </div>
</div>
<div class="modal fade" id="placeOrderNextModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="placeOrderNextModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content radius_1 shadow-lg error_res" id="placeOrderNextModalBlock">

        </div>
    </div>
</div>

<!-- Customer Reference Id -->
<div class="modal fade" id="customerRefIdModel" aria-hidden="true" aria-labelledby="customerRefIdModelLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header d-none">
                <h5 class="modal-title" id="customerRefIdModelLabel">Add Customer Reference ID</h5>
                <button type="button" class="btn-close" data-bs-target="#placeOrderModal" data-bs-toggle="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <img src="{{ URL::asset('front-assets/images/icons/ref_id_1.png') }}" alt="Customer Reference ID">
                <h5 class="px-4 py-4">{{ __ ('dashboard.customer_ref_warning') }}</h5>
                <button class="btn btn-secondary" id="orderPlaceBtn" data-bs-dismiss="modal" aria-label="Close">
                    <img src="{{ URL::asset('front-assets/images/icons/icon_placeorder.png') }}" alt="Place order" class="pe-1"> {{ __('admin.no') }}
                </button>
                <button class="btn btn-primary" id="addCustIdBtn" data-bs-target="#placeOrderModal" data-bs-toggle="modal">
                    <img src="{{ URL::asset('front-assets/images/icons/icon_post_require.png') }}" alt="Yes" class="pe-1"> {{ __('admin.yes') }}
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Customer Reference Id End -->
<input type="hidden" name="user_nib" id="user_nib" value="{{ $companyData->registrantion_NIB ?? null }}" />
<input type="hidden" name="user_npwp" id="user_npwp" value="{{ $companyData->npwp ?? null }}" />



<!-- NIB and NPWP modal -->
<div class="modal fade" id="NibNpwpModal" aria-hidden="true" aria-labelledby="NibNpwpModalLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header d-none">
                <h5 class="modal-title" id="NibNpwpModalLabel">{{ __('rfqs.Add NIP and NPWP') }}</h5>
                <button type="button" class="btn-close" data-bs-toggle="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <img src="{{ URL::asset('front-assets/images/icons/ref_id_1.png') }}" alt="NIP and NPWP">
                @if($isOwner || Auth::user()->hasPermissionTo('publish buyer company info'))
                    <h5 class="px-4 py-4">{{ __('rfqs.nip_npwp_msg') }}</h5>
                @else
                    <h5 class="px-4 py-4">{{ __('rfqs.buyeradmin_nip_npwp_msg') }}</h5>
                @endif
                <button class="btn btn-secondary" id="notNowBtn" data-bs-dismiss="modal" aria-label="Close">
                    <img src="{{ URL::asset('front-assets/images/icons/icon_placeorder.png') }}" alt="Cancel" class="pe-1"> {{ __('admin.cancel') }}
                </button>
                @if($isOwner || Auth::user()->hasPermissionTo('publish buyer company info'))
                <button type="button" class="btn btn-primary" id="addNibNpwpBtn" onclick="redirectToProfile()">
                @else
                <button type="button" class="btn btn-primary" id="addNibNpwpBtn" data-bs-dismiss="modal" aria-label="Close">
                @endif
                    <img src="{{ URL::asset('front-assets/images/icons/icon_post_require.png') }}" alt="Ok" class="pe-1">
                    {{ __('admin.ok') }}
                </button>
            </div>
        </div>
    </div>
</div>
<!-- NIB and NPWP modal end -->

<div class="modal fade" id="rfqDetailsModal" data-bs-backdrop="true" tabindex="-1"
    aria-labelledby="rfqDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content radius_1 shadow-lg" id="rfqDetailsModalBlock"></div>
    </div>
</div>
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
    <!-- Modal for get Approval feedback -->
    <div class="modal fade" id="getApprovalFeedbackModal" tabindex="-1" aria-labelledby="getApprovalFeedbackModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
        <form id="getApprovalFeedbackForm" method="POST" action="{{ route('approvals.insertApprovalFeedback') }}" autocomplete="off" enctype="multipart/form-data">
        @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="getApprovalFeedbackModalLabel">{{ __('rfqs.get_approval') }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row floatlables ">
                            <div class="col-md-12">
                                <label class="form-label" for="comment">{{ __('rfqs.comment') }}<span class="text-danger">*</span></label>
                                <textarea class="form-control" placeholder="Please give reason to get approval" name="approval_comment" id="approval_comment" onkeypress="errorRemove(this)"></textarea>
                                <span id="approval_commentError" class="text-danger"></span>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" value="" name="reason_quoteId" id="reason_quoteId">

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" id="cancelFeedbackBtn">{{ __('rfqs.cancel') }}</button>
                        <button type="button" class="btn btn-primary btn-sm" id="postFeedbackBtn">{{ __('rfqs.get_approval') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- End -->
    {{--chat history model --}}
        <div class="modal right fade historymodal" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content showhistorymodal">

                </div>
            </div>
        </div>
 @livewireScripts

@endsection

@section('script')
<script src="{{ URL::asset('assets/js/custom/permissions.js') }}"></script>
<script src="{{ URL::asset('assets/js/custom/app.js') }}"></script>
<script type="text/javascript">
    jQuery(document).ready(function(){
        RfqList.init();
    });
     var RfqList = function () {
        //Hide approval result data
        $(".showSearchData").hide();
        // Credit List Load More Data
        favoriteRfq = function() {
            $(document).on('click', '.js-favoriteRfq', function(e) {
                favoriteRfq = $('#is_favouriteRfq_0').val();
                newFavoriteRfq = $('#new_fev').val();
                let customSearch = $('#customSearch').val();
                let customStatusSearch = $('#customStatusSearch').val();
                $(this).addClass('btnactive');
                if(favoriteRfq == 1){
                    $('.favorite_0').addClass('btn-danger');
                    $('.favorite_0').addClass('text-white');
                    $('#is_favouriteRfq_0').val(0);
                }else{
                    $('.favorite_0').removeClass('btn-danger');
                    $('.favorite_0').removeClass('text-white');
                    $('#is_favouriteRfq_0').val(1);
                }
                $('#new_fev').val(0);
                if(newFavoriteRfq == 0)
                {
                    $('#new_fev').val(1);
                }
                window.livewire.emit('favrfq',favoriteRfq,customSearch, customStatusSearch);                
            });
        },
        // Input box search with custom text
        searchData = function() {
            $('.customSearch').click(function() {
                favoriteRfq = $('#new_fev').val();
                let customSearch = $('#customSearch').val();
                let customStatusSearch = $('#customStatusSearch').val();
                if(customSearch || customStatusSearch) {
                    window.livewire.emit('search',customSearch, customStatusSearch,favoriteRfq);
                    $("#searchTextErr").html("");
                } else {
                    $("#searchTextErr").html("Please enter valid keyword");
                    return false;
                }
            });
            $('#customSearch').keypress(function(e) {
                $("#searchTextErr").html("");
                if(e.keyCode == 13) {
                    $('.customSearch').click();       //Trigger search button click event
                }
            });
        },
        //End
        //On clear searched text (cancel icon), we will get all the data
        clearSearchData = function() {
            $('input[type=search]').on('search', function () {
                $('.customSearch').click();
            });
        },
        //Search by quote status dropdown
        customStatusSearch = function() {
            $('#customStatusSearch').change(function() {
                let customStatusSearch = $('#customStatusSearch').val();
                //Call "searchData" function on change of quote status
                $('.customSearch').click();
            });
        };
        return {
            init: function () {
                favoriteRfq(),
                searchData(),
                clearSearchData(),
                customStatusSearch()
            },
        }
    }(1);
    $('body').on('click', '.js-accordian', function(e) {
        return false;
        var rfqId = $(this).attr('data-id');
        var customSearchVal =  '';
        if($('#collapse' + rfqId).hasClass('show'))
        $('#collapse' + rfqId).collapse("hide")
        else
        $.ajax({
            url: "{{ route('dashboard-get-rfq-quotes-ajax', '') }}",
            data: { rfqId : rfqId,customSearch : customSearchVal},
            type: 'GET',
            success: function(successData) {
                if (successData.rfqhtml) {
                    $('#collapse' + rfqId + ' .rfqform_view').html(successData.rfqhtml);
                }
                if (successData.html) {
                    $('#collapse' + rfqId + ' .quoteDetails').html(successData.html);
                }
                if (successData.returnCompareHTML){
                    if(successData.flagCount > 0){
                        $('#collapse' + rfqId + ' .compare_rfq_quote').html(successData.returnCompareHTML);
                        $('#qouteListing'+rfqId).dataTable({searching: false, paging: false,"order": [],columnDefs: [{orderable: false,targets: "no-sort"}]});
                    }
                }
                $('#collapse' + rfqId).collapse("show")
            },
            error: function() {
                console.log('error');
            }
        });
    });
    $(document).on('click','.openeditmodal',function(){
        var rfqId = $(this).attr('data-rfq_id');
        $.ajax({
            url: "{{ route('dashboard-get-rfq-editmodal-ajax', '') }}" + "/" + rfqId,
            type: 'GET',
            success: function(successData) {
                if (successData.html) {
                    $(".showeditmodal").html(successData.html);
                    $(".rfqCollapse").collapse("hide");
                    $(".editmodal").modal('show');
                }
            }
        });
    });
    $(document).on('click','.cancelrfq',function(){
        var rfqId = $(this).attr('data-rfq_id'), derfqId = $(this).attr('data-id');
        if(rfqId){
            swal({
                text: "{{  __('admin.cancel_rfq') }}",
                icon: "/assets/images/warn.png",
                buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
                dangerMode: true,
            })
            .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: "{{ route('cancel-rfq-ajax') }}",
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            data: {'rfqId':rfqId},
                            type: 'POST',
                            responseType: 'json',
                            success: function(successData) {
                                if (successData.success==true) {
                                    $('#badgeColorSpan' + derfqId).removeClass('bg-primary');
                                    $('#badgeColorSpan' + derfqId).addClass('bg-danger');
                                    $('#badgeColorSpan' + derfqId).html(successData.rfqStatus);
                                    $('#cancelrfq_remove' + derfqId).html('');
                                    new PNotify({
                                        text: "{{ __('admin.rfq_cancelled_successfully') }}",
                                        type: 'danger',
                                        styling: 'bootstrap3',
                                        animateSpeed: 'fast',
                                        delay: 1000
                                    });
                                }
                            }
                        });
                    } else {
                        return false;
                    }
                });
        }
    });
    $(document).on('click','#addCustIdBtn',function(){
        $("#cust_ref_id").css({'border-color':'#86b7fe','outline': '0','box-shadow': '0 0 0 0.25rem rgb(13 110 253 / 25%)'});
    });
    $(document).on('show.bs.modal','.editmodal', function (event) {
        $(this).find('.nav a:first').tab('show');
    });
    $(document).on('click', '.showQuoteModal', function() {
            var quoteId = $(this).attr('data-id');
            $.ajax({
                url: "{{ route('dashboard-get-rfq-quotes-details-ajax', '') }}" + "/" +
                    quoteId,
                type: 'GET',
                success: function(successData) {
                    if (successData.html) {
                        $('#rfqQuoteDetailsModalBlock').html(successData.html);
                        $('#rfqQuoteDetailsModal').modal('show');
                    }
                },
                error: function() {
                    console.log('error');
                }
            });
    });
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
    $('#useraddress_id').select2({
        dropdownParent: $('#address_block'),
    });
    $(document).on('change','#useraddress_id',function(){
        let is_edit = $(this).attr('data-isEdit');
        let selected_option = $('option:selected', this);
        if (is_edit==1){
            $(".address_name").val(selected_option.attr('data-address_name'));
            $(".addressLine1").val(selected_option.attr('data-address_line_1'));
            $(".addressLine2").val(selected_option.attr('data-address_line_2'));
            $(".sub_district").val(selected_option.attr('data-sub_district'));
            $(".district").val(selected_option.attr('data-district'));
            $(".city").val(selected_option.attr('data-city'));
            $(".state").val(selected_option.attr('data-state'));
            $(".pincode").val(selected_option.attr('data-pincode'));
            $("#stateEditId").val(selected_option.attr('data-state-id')).trigger('change');
        }
    });
    $(document).on('click', '.showPlaceOrderModal', function() {
            var user_nib = $("#user_nib").val();
            var user_npwp = $("#user_npwp").val();
            if(user_nib.length == '' || user_npwp.length == '' ) {
                $("#NibNpwpModal").modal('show');
                $('#rfqQuoteDetailsModal').modal('hide');
            } else {
                $('#rfqQuoteDetailsModal').modal('hide');
                var quoteId = $(this).attr('data-id');
                $.ajax({
                    url: "{{ route('dashboard-get-place-order-details-ajax', '') }}" + "/" +
                        quoteId,
                    type: 'GET',
                    success: function(successData) {
                        if (successData.html) {
                            $('#placeOrderModalBlock').html(successData.html);
                            $('#placeOrderModal').modal('show');
                            setPaymentType();
                        }
                    },
                    error: function() {
                        console.log('error');
                    }
                });
            }
    });
    $(document).on('click', '.nextOrderPage', function() {
        $('.address').show();
        $('.loan_div').hide();
        $('.payment-type').hide();
        $('.my-3').hide();
        $('.loan_main_div').show();
        $('.terms').hide();
        $('.rfqform_view1').show();
        $('#main_div').hide();
        $('.terms').show();
        $('.loan_order').hide();
        $('.loan_otp').show();
        $('.otp_div').hide();
        $("#paybalAMount").html($('#paybal_amount').val());
        $("#placeOrderBtnOtp").prop('disabled', false);
    });
    $(document).on('click', '.placeOrderBtnOtpback', function() {
        $('.address').hide();
        $('.loan_div').show();
        $('.payment-type').show();
        $('.my-3').show();
        $('.loan_main_div').show();
        $('.rfqform_view1').hide();
        $('#main_div').show();
        $('.terms').hide();
        $('.loan_order').show();
        $('.loan_otp').hide();
        $('.otp_div').hide();
    });
    $(document).on('click', '#placeOrderBtnOtp', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        if ($('#placeOrderAddressForm').parsley().validate()) {
            $("#placeOrderBtnOtp").prop('disabled', true);
            var ajaxLoading = false;
            var formData = {
                quoteId:$('#quote_id').val(),
                amount:$('#amountSent').val()
            }
            if(!ajaxLoading) {
                ajaxLoading = true;
        $.ajax({
                    url: "{{ route('settings.credit.loan.create.json') }}",
                    type: 'POST',
                    data:formData,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function(response) {
                        ajaxLoading = false;
                        if (response.success) {
                            $('.address').hide();
                            $('.loan_div').hide();
                            $('.payment-type').hide();
                            $('.my-3').hide();
                            $('.loan_main_div').hide();
                            $('.terms').hide();
                            $('.rfqform_view1').hide();
                            $('#main_div').hide();
                            $('.terms').hide();
                            $('.loan_order').hide();
                            $('.loan_otp').hide();
                            $('.otp_div').show();
                            $("#totalAmountOtp").html('Rp.'+$('#amountSent').val());
                        }
                        new PNotify({
                            text: response.message,
                            type: 'success',
                            styling: 'bootstrap3',
                            animateSpeed: 'fast',
                            delay: 2000
                        });
                    },
                    error: function() {
                        new PNotify({
                            text: "{{ __('profile.something_went_wrong') }}",
                            type: 'error',
                            styling: 'bootstrap3',
                            animateSpeed: 'fast',
                            delay: 2000
                        });
                    }
                });
            }
        }
    });
    function setPaymentType(){
        $('.exist_order').show();
        $('.loan_order').hide();
      //  $('#final_amount').show();
        let selector = $('#is_credit');
        if(selector.val()=='1'){
            selector.closest('.row.payment-type').find('.credit-type-div').show();
            selector.closest('.row.payment-type').find('.credit_type').prop('disabled', false);
            selector.closest('.row.payment-type').find('.credit-type-div_2').hide();
            selector.closest('.row.payment-type').find('.credit_type_2').prop('disabled', false);
            $('.address').show();
            $('.loan_div').hide();
            $('.exist_order').show();
            $('.loan_order').hide();
            $('.terms').show();
            $('.otp_div').hide();
            $('.credit-type-div_2').hide();
        }else if(selector.val()=='2'){
            $('.address').hide();
            $('.loan_div').show();
            $('.exist_order').hide();
            $('.loan_order').show();
            $('.terms').hide();
            $('.otp_div').hide();
            $('.credit-type-div_2').show();
            selector.closest('.row.payment-type').find('.credit-type-div').hide();
            selector.closest('.row.payment-type').find('.credit_type').prop('disabled', false);
            selector.closest('.row.payment-type').find('.credit-type-div_2').show();
            selector.closest('.row.payment-type').find('.credit_type_2').prop('disabled', true);
            var quote_id=$('#quote_id').val();
            var amount=$('#amount_rfq').val();
            $.ajax({
                url: "{{ route('settings.credit.loan.apply.calculation.json','') }}" + "/" +
                quote_id ,
                type: 'GET',
                dataType: 'json',
                success: function(successData) {
                    $('#calc').empty();
                    $.each(successData, function(index, value) {
                      if(index=='Total Amount')
                      {
                        $('#amountSent').val(value.replace('Rp',''));
                      }
                      if(index=='Product')
                      {
                        value='Rp'+' '+amount;
                      }
                      if(index=='Paybal Amount'){
                            $('#paybal_amount').val(value);
                        }
                     if(index=='Total Amount' || index=='Paybal Amount')
                      {
                        var style="style='padding: 0.5rem 0.5rem;background-color:#e5e5e5;font-weight: bold;'";
                      }
                      else{
                        var style="class='fw-normal' style='padding: 0.5rem 0.5rem;background-color: var(--bs-table-bg);'";
                      }
                       var data="<tr><th colspan='12' "+style+">"+index+"</th><th scope='col' class='text-end fw-normal'"+style+">"+value+"</th></tr>";
                        $('#calc').append(data);
                        });
                },
                error: function() {
                    console.log('error');
                }
            });
        }
        else{
            selector.closest('.row.payment-type').find('.credit-type-div').hide();
            selector.closest('.row.payment-type').find('.credit_type').prop('disabled', true);
            selector.closest('.row.payment-type').find('.credit-type-div_2').hide();
            selector.closest('.row.payment-type').find('.credit_type_2').prop('disabled', true);
            $('.address').show();
            $('.loan_div').hide();
            $('.exist_order').show();
            $('.loan_order').hide();
            $('.terms').show();
            $('.otp_div').hide();
            $('.credit-type-div_2').hide();
        }
    }
    $(document).on('click', '.OtpVerify', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        if ($('#placeOrderAddressForm').parsley().validate()) {
            $("#OtpVerify").prop('disabled', true);
            let codes = [];
            for (let i = 1; i < 7; i++) {
                    codes.push($("#digit-"+i).val());
                }
            var formData = {
                addressLine1:$('#addressLine1').val(),
                address_name:$('#address_name').val(),
                addressLine2:$('#addressLine2').val(),
                district:$('#district').val(),
                sub_district:$('#sub_district').val(),
                stateId:$('#stateId').val(),
                state:$('#state').val(),
                cityId:$('#cityId').val(),
                city:$('#city').val(),
                pincode:$('#pincode').val(),
                quoteId:$('#quote_id').val(),
                addressLine1:$('#addressLine1').val(),
                code:codes,
                is_credit:$('#is_credit').val(),
                cust_ref_id:$('#cust_ref_id').val(),
                comment:$('#comment').val(),
            }
            $.ajax({
                url: "{{ route('settings.credit.loan.verify.otp.json') }}",
                type: 'POST',
                data: formData,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(response) {
                    if (response.success) {
                        new PNotify({
                        text: "{{ __('dashboard.order_place_success_message') }}",
                            type: 'success',
                            styling: 'bootstrap3',
                            animateSpeed: 'fast',
                            delay: 1000
                        });
                        $("#placeOrderAddressForm")[0].reset();
                        $('#placeOrderModal').modal('hide');
                        $('.showPlaceOrderModal' + $('#quote_id').val()).remove();
                        $('#myOrderCount').html(response.ordersCount);
                        $('#mainContentSection').html(response.html);
                        $('html, body').animate({
                            scrollTop: $("#mainContentSection").offset().top
                        }, 50);
                        $('#collapse' + response.lastOrderId).collapse('show');
                        $('#rfqSection').removeClass('btnactive');
                        $('#addressSection').removeClass('btnactive');
                        $('#postRequirementSection').removeClass('btnactive');
                        $('#orderSection').addClass('btnactive');
                    }else{
                        new PNotify({
                        text: response.message,
                        type: 'warning',
                        styling: 'bootstrap3',
                        animateSpeed: 'fast',
                        delay: 1000
                     });
                     for (let i = 1; i < 7; i++) {
                        $("#digit-"+i).val('');
                    }
                     $("#OtpVerify").prop('disabled', false);
                    }
                },
                error: function() {
                    new PNotify({
                        text: "{{ __('profile.something_went_wrong') }}",
                        type: 'error',
                        styling: 'bootstrap3',
                        animateSpeed: 'fast',
                        delay: 2000
                    });
                    $("#OtpVerify").prop('disabled', false);
                }
            });
        }
    });
    $(document).on('click', '#postRequirementSection', function (e) {   
        sessionStorage.setItem("dashboard-lastlocation", "postRequirementSection");
        $('button.dash-menus').removeClass('btnactive');
        // $(this).addClass('btnactive');
        e.stopImmediatePropagation();
        $('#isRepeatRfq').val(localStorage.getItem("dashboard-isRepeatRfq"));
        let isRepeatRfqId = '';
        if(localStorage.getItem("dashboard-repeatRfqId") != ''){
            isRepeatRfqId = localStorage.getItem("dashboard-repeatRfqId");
        }
        localStorage.clear();
        $.ajax({
            url: '{{ route('dashboard-default-ajax') }}',
            type: 'GET',
            data:{'isRepeatRfqId':isRepeatRfqId},
            success: function (successData) {
                $('#RepeatrfqModal').modal('hide');
                defaultData = successData.html;
                $('#rfqspagination').addClass('hide')
                $('#repeatRfqform').removeClass('hide')
                $('#repeatRfqform').html(successData.html);
                $('#userActivitySection').html(successData.userActivityHtml);
                $('#myRfqCount').html(successData.rfqCount);
                $('#myOrderCount').html(successData.ordersCount);
                $('#myAddressCount').html(successData.addressCount);
                $('#myPaymentCount').html(successData.supplierPaymentCount);
                $('#myGroupsCount').html(successData.buyerGroupsCount);
                $('#myApprovalCount').html(successData.approvalTabCount);

            },
            error: function (error) {
                console.log(error);
            }
        });
    });
    $(document).on('click', '#placeOrderBtn', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        var user_nib = $("#user_nib").val();
        var user_npwp = $("#user_npwp").val();
        var custRefId = $("#cust_ref_id").val();
        if(user_nib.length == '' || user_npwp.length == '' ) {
            $('#rfqQuoteDetailsModal').modal('hide');
            $("#NibNpwpModal").modal('show');
        } else {
            if ($('#placeOrderAddressForm').parsley().validate()) {
                //address missing in form need to add
                if($("#cust_ref_id").val() == 0) {
                    $('#placeOrderModal').modal('hide');
                    $('#customerRefIdModel').modal('show');
                } else {
                    if(custRefId != null) {
                        $.ajax({
                            url: "{{ route('check-customer-refId-ajax') }}",
                            type:"POST",
                            data:{
                                "custRefId":custRefId,
                                _token: "{{ csrf_token() }}"
                            },
                            success:function(response){
                                if(response.ref_exist == true) {
                                    $("#ref_msg").css("color","red").html('PO customer reference id is already exist');
                                    $("#placeOrderBtn").prop('disabled', true);
                                } else {
                                    $("#placeOrderBtn").prop('disabled', true);
                                    var formData = new FormData($('#placeOrderAddressForm')[0]);
                                    var quoteId = $('#placeOrderAddressForm input[name="quoteId"]').val();
                                    let is_group_rfq = $('#placeOrderAddressForm').attr('data-is_group_rfq');
                                    let url = "{{ route('dashboard-place-order-ajax') }}";
                                    if (is_group_rfq==1){
                                        if (!SnippetAppPermission.hasPermission('create buyer payments')) {
                                            SnippetApp.swal.info("{{ __('admin.access_denied') }}","{{ __('admin.user_not_has_permission') }}","{{ __('admin.ok') }}");
                                            return false;
                                        }
                                        url = "{{ route('dashboard-group-place-order-ajax') }}"
                                    }
                                    $.ajax({
                                        url: url,
                                        data: formData,
                                        type: 'POST',
                                        contentType: false,
                                        processData: false,
                                        success: function(successData) {
                                            $('#placeOrderBtn').attr('disabled', false);
                                             $('.showPlaceOrderModal' + quoteId).remove();
                                            if (successData.success) {
                                                if (is_group_rfq==1){
                                                    location.href = successData.invoice_url;
                                                    return;
                                                }
                                                new PNotify({
                                                    text: "{{ __('dashboard.order_place_success_message') }}",
                                                    type: 'success',
                                                    styling: 'bootstrap3',
                                                    animateSpeed: 'fast',
                                                    delay: 1000
                                                });
                                                sessionStorage.setItem("placeorderlivewire",successData.lastOrderId);
                                                window.location.href = "{{ route('order-ls') }}"
                                            }else{
                                                if (is_group_rfq==1 || successData.message) {
                                                    new PNotify({
                                                        text: successData.message,
                                                        type: 'warning',
                                                        styling: 'bootstrap3',
                                                        animateSpeed: 'fast',
                                                        delay: 3000
                                                    });
                                                }else{
                                                    new PNotify({
                                                        text: "{{ __('admin.something_went_wrong') }}",
                                                        type: 'warning',
                                                        styling: 'bootstrap3',
                                                        animateSpeed: 'fast',
                                                        delay: 3000
                                                    });
                                                }
                                            }
                                        },
                                        error: function() {
                                            $('#placeOrderBtn').attr('disabled', false);
                                            console.log('error');
                                        }
                                    });
                                }
                            },
                            error: function(error) {
                                console.log(error);
                            }
                        });
                    } else {
                        $(this).attr('disabled', true);
                        $("#placeOrderBtn").prop('disabled', true);
                        var formData = new FormData($('#placeOrderAddressForm')[0]);
                        var quoteId = $('#placeOrderAddressForm input[name="quoteId"]').val();
                        let is_group_rfq = $('#placeOrderAddressForm').attr('data-is_group_rfq');
                        let url = "{{ route('dashboard-place-order-ajax') }}";
                        if (is_group_rfq==1){
                            if (!SnippetAppPermission.hasPermission('create buyer payments')) {
                                SnippetApp.swal.info("{{ __('admin.access_denied') }}","{{ __('admin.user_not_has_permission') }}","{{ __('admin.ok') }}");
                                return false;
                            }
                            url = "{{ route('dashboard-group-place-order-ajax') }}"
                        }
                        $.ajax({
                            url: url,
                            data: formData,
                            type: 'POST',
                            contentType: false,
                            processData: false,
                            success: function(successData) {
                                $('#placeOrderBtn').attr('disabled', false);
                                 $('.showPlaceOrderModal' + quoteId).remove();
                                if (successData.success) {
                                    if (is_group_rfq==1){
                                        location.href = successData.invoice_url;
                                        return;
                                    }
                                    new PNotify({
                                        text: "{{ __('dashboard.order_place_success_message') }}",
                                        type: 'success',
                                        styling: 'bootstrap3',
                                        animateSpeed: 'fast',
                                        delay: 1000
                                    });
                                    sessionStorage.setItem("placeorderlivewire",successData.lastOrderId);
                                    window.location.href = "{{ route('order-ls') }}"
                                }else{
                                    if (is_group_rfq==1 || successData.message) {
                                        new PNotify({
                                            text: successData.message,
                                            type: 'warning',
                                            styling: 'bootstrap3',
                                            animateSpeed: 'fast',
                                            delay: 3000
                                        });
                                    }else{
                                        new PNotify({
                                            text: "{{ __('admin.something_went_wrong') }}",
                                            type: 'warning',
                                            styling: 'bootstrap3',
                                            animateSpeed: 'fast',
                                            delay: 3000
                                        });
                                    }
                                }
                            },
                            error: function() {
                                $('#placeOrderBtn').attr('disabled', false);
                                console.log('error');
                            }
                        });
                    }
                }
            }
        }
    });
    $(document).on('click', '#orderPlaceBtn', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        if ($('#placeOrderAddressForm').parsley().validate()) {
            var quoteId = $('#placeOrderAddressForm input[name="quoteId"]').val();
            let that = $(this);
            that.attr('disabled', true);
            $("#placeOrderBtn").prop('disabled', true);
            $('.showPlaceOrderModal'+quoteId).hide();
            $('#orderplaceloader'+quoteId).removeClass('hidden');
            var formData = new FormData($('#placeOrderAddressForm')[0]);
            var quoteId = $('#placeOrderAddressForm input[name="quoteId"]').val();
            let is_group_rfq = $('#placeOrderAddressForm').attr('data-is_group_rfq');
            let url = "{{ route('dashboard-place-order-ajax') }}";
            if (is_group_rfq==1){
                if (!SnippetAppPermission.hasPermission('create buyer payments')) {
                    SnippetApp.swal.info("{{ __('admin.access_denied') }}","{{ __('admin.user_not_has_permission') }}","{{ __('admin.ok') }}");
                    return false;
                }
                url = "{{ route('dashboard-group-place-order-ajax') }}"
            }
            $.ajax({
                url: url,
                data: formData,
                type: 'POST',
                contentType: false,
                processData: false,
                success: function(successData) {
                    
                    $('#placeOrderBtn').attr('disabled', false);
                    $('.showPlaceOrderModal'+quoteId).show();
                    $('#orderplaceloader'+quoteId).addClass('hidden');
                    that.attr('disabled', false);
                    $('.showPlaceOrderModal' + quoteId).remove();
                    if (successData.success) {
                        if (is_group_rfq==1){
                            location.href = successData.invoice_url;
                            return;
                        }
                        new PNotify({
                            text: "{{ __('dashboard.order_place_success_message') }}",
                            type: 'success',
                            styling: 'bootstrap3',
                            animateSpeed: 'fast',
                            delay: 1000
                        });
                        sessionStorage.setItem("placeorderlivewire",successData.lastOrderId);
                        window.location.href = "{{ route('order-ls') }}"
                    }else{
                        if (is_group_rfq==1 || successData.message) {
                            new PNotify({
                                text: successData.message,
                                type: 'warning',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 3000
                            });
                        }else{
                            new PNotify({
                                text: "{{ __('admin.something_went_wrong') }}",
                                type: 'warning',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 3000
                            });
                        }
                    }
                },
                error: function() {
                    that.attr('disabled', false);
                    $('#placeOrderBtn').attr('disabled', false);
                    $('.showPlaceOrderModal'+quoteId).show();
                    $('#orderplaceloader'+quoteId).addClass('hidden');
                    console.log('error');
                }
            });
        }
    });
    function redirectToProfile() {
        sessionStorage.clear();
        sessionStorage.setItem("profile-lastlocation", JSON.stringify({
            mainTab: "company-tab",
            secondTab: "change_company_info"
        }));
        window.location='{{ route("profile") }}'
    }
    $(document).ready(function () {
       // SnippetSearchList.init()
        $('.editmenu_section .btn-group').hover(function () {
            $(this).find('.dropdown-menu').first().stop(true, true).slideDown(150);
        }, function () {
            $(this).find('.dropdown-menu').first().stop(true, true).slideUp(105)
        });
    });
    $(document).on('click', '#addPreferredSupplierChk', function (e) {
        let checkBoxValue = $(this).val();
        let supplierId = $(this).attr('data-supplier-id');
        let rfqId = $(this).attr('data-rfq-id');
        var data = {
            checkBoxValue : checkBoxValue,
            supplierId : supplierId,
            _token: $('meta[name="csrf-token"]').attr("content"),
        };
        swal({
            title: "{{ __('admin.delete_sure_alert') }}",
            text: "{{ __('admin.added_as_preferred_supplier') }}?",
            icon: "/assets/images/info.png",
            buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
            dangerMode: false,
        }).then((changeit) => {
            if (changeit) {
                $.ajax({
                    url: "{{ route('add-preferred-supplier-ajax') }}",
                    data: data,
                    type: "POST",
                    success: function (successData) {
                        new PNotify({
                            text: successData.response,
                            type: 'success',
                            styling: 'bootstrap3',
                            animateSpeed: 'fast',
                            delay: 2000
                        });
                        setTimeout(function() {
                            $(".Rfq_Id"+rfqId).trigger('click');
                        }, 2000);
                    },
                    error: function () {
                        console.log("error");
                    },
                });
                $(".Rfq_Id"+rfqId).trigger('click');
            } else {
                $(this).prop('checked', false);
            }
        });
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
    $('body').on('click', '.getApprovalCompare', function() {
        $(this).attr("disabled",true)
    });
    /** make rfq favourite or not
     *   Vrutika - 18/11/2022
     */
    $(document).on('click', '.addtofavRfq', function () {
        var rfqId = $(this).attr('data-rfq_id');
        if(rfqId){
            var isFavouriteRfq = $('#is_favouriteRfq_'+rfqId).val();
            var title = isFavouriteRfq==0 ? '{{__('dashboard.add_favourite_alert')}}' : '{{__('dashboard.remove_favourite_alert')}}';
            swal({
                title: "{{ __('dashboard.are_you_sure') }}?",
                text: title,
                icon: "/assets/images/info.png",
                buttons: ['{{ __('admin.no') }}', '{{ __('admin.yes') }}'],
                dangerMode: true,
            })
            .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: "{{ route('favourite-rfq-ajax') }}",
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            data: {'rfqId':rfqId,'isFavouriteRfq':isFavouriteRfq},
                            type: 'POST',
                            responseType: 'json',
                            success: function(successData) {
                                if (successData.success==true) {
                                    $(".addtofavouriteRfq_"+rfqId).toggleClass("fa-star-o fa-star");
                                    $(".favorite_"+rfqId).toggleClass("btn-outline-danger btn-danger");
                                   if(isFavouriteRfq==0){
                                       title = '{{__('dashboard.remove_favourite')}}';
                                       $('#is_favouriteRfq_'+rfqId).val('1');
                                       $('#showFavourite_'+rfqId).removeClass('d-none');
                                       $('.favorite_'+rfqId).attr("title",title);
                                   }else{
                                       title = '{{__('dashboard.add_favourite')}}';
                                       $('#is_favouriteRfq_'+rfqId).val('0');
                                       $('#showFavourite_'+rfqId).addClass('d-none');
                                       $('.favorite_'+rfqId).attr("title",title);
                                   }
                               }
                            }
                        });
                    } else {
                        return false;
                    }
                });
        }else{
            var isFavouriteRfq = $('#is_favouriteRfq_0').val();
            $(".addtofavouriteRfq_0").toggleClass("fa-star-o fa-star");
            $(".favorite_0").toggleClass("btn-outline-danger btn-danger");
            if(isFavouriteRfq==1){
                localStorage.setItem("dashboard-is_favouriteRfq",'0');
            }else{
                localStorage.setItem("dashboard-is_favouriteRfq",'1');
            }
            $('#rfqSection').trigger('click');
        }
   });
    /** Repeat Rfq
     * *   Vrutika - 23/11/2022
     * */
    $(document).on('click', '.repeatRfq', function (e) {
        var isRepeatOrder = $(this).attr('data-isRepeatOrder');
        var title;
        isRepeatOrder==1 ? title = '{{__('dashboard.repeat_order_alert')}}' : title = '{{__('dashboard.repeat_rfq_alert')}}';
        swal({
                title: "{{ __('dashboard.are_you_sure') }}?",
                text: title,
                icon: "/assets/images/info.png",
                buttons: ['{{ __('admin.no') }}', '{{ __('admin.yes') }}'],
                dangerMode: true,
            })
            .then((willDelete) => {
                    if (willDelete) {
                        let repeatRfqId = $(this).attr('data-rfq_id');
                        localStorage.setItem("dashboard-repeatRfqId",repeatRfqId);
                        localStorage.setItem("dashboard-isRepeatRfq",'1');
                        $('#postRequirementSection').trigger('click');
                    }else {
                        return false;
                    }
            });
    });
</script>
<script>

    function getapprovalProcessValue() {
        var quoteId = $("#quoteIdValue").val();
        var quoteStatus = $("#quoteStatus").val();
        var approvalProcessValue = $("#appProcess").val();
        var isAuthUser = $("#isAuthUser").val();

        if(isAuthUser == 1) {
            $(".getApprovalBtnDiv, .approvalWaitingBtn, .progress, .UserCount").addClass('d-none');
            $(".card-header-1, .feedbackContent").css("display","none");
            $(".card-header-1").parent().removeClass("hide card progresscard shadow");
        } else {
            if(approvalProcessValue == 1) {
                if($("#progressPercentage"+quoteId).val() == "100%") {
                    $("#placeOrderBtnDiv"+quoteId).show();
                    $('.showPlaceOrderModal'+quoteId).removeClass('hide');
                    $('.getApprovalCompare'+quoteId).addClass('hide');
                } else {
                    $("#placeOrderBtnDiv"+quoteId).hide();
                    if($("#configUsersCount").val() == 0) {
                        $("#getApprovalBtnDiv"+quoteId).show();
                        $('.getappbtn').addClass('hide');
                    } else {
                        $("#getApprovalBtnDiv"+quoteId).show();
                        $('.getApprovalCompare'+quoteId).removeClass('hide');
                    }
                }
            } else {
                //Toggle Off (value = 0)
                $(".PlaceBtn").removeClass('hide');
                $(".getappbtn").addClass('hide');
                if($("#approvalWaitingBtn"+quoteId).hasClass('showed')) {
                    $("#placeOrderBtnDiv"+quoteId).hide();
                } else {
                    $("#placeOrderBtnDiv"+quoteId).show();
                }
                $(".getApprovalBtnDiv, .approvalWaitingBtn, .progress, .UserCount").addClass('d-none');
                if ($("#progressPercentage"+quoteId).val() == "100%") {
                    $("#myDIV"+quoteId).addClass("myDIV");
                } else {
                    $("#myDIV"+quoteId).removeClass("myDIV");
                }
            }
        }
    }

    //When click on quote section to see details
    function changeBtnName(quoteId) {
        $("#quoteIdValue").val(quoteId);                //Assign quote id
        var apprProcess = $("#appProcess").val();       //Get approval process value
        var quoteStatus = $("#quoteStatus").val();
        $.ajax({
            url: "{{ route('quote-feedback-data-ajax') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "quoteId": quoteId
            },
            type: 'POST',
            dataType: "json",

            success: function(res) {
                $("#progressPercentage"+quoteId).val(res.percCount);
                getapprovalProcessValue();
                if(res.percCount == "100%") {
                    $("#placeOrderBtnDiv"+quoteId).addClass('complete').show();
                    if(apprProcess == 0) {
                       $(".card-header, .feedbackContent").css("display","none");
                       $(".card-header").parent().removeClass("hide card progresscard shadow");
                    } else {
                        //Mouse hover
                        $("#placeOrderBtnDiv"+quoteId).mouseover(function() {
                            $("#myDIV"+quoteId).css("visibility","visible");
                            var finalHtml = '';
                            $.each(res.userData, function(index,value) {
                                if(value.quote_id) {
                                    if(value.feedback == 0) {
                                        var ConfigUser = 'configUserResendMail('+value.id+','+value.quote_id+',1)';
                                        var resendMail = '<span class="pe-2"><button type="button" class="border-0 bg-transparent" href="javascript:void(0)" onclick="'+ConfigUser+'" title="{{ __("admin.resend") }}"> <img src="{{ URL::asset("front-assets/images/resendMailBtn.png") }}" height="20px" width="20px" alt="" srcset=""></a></span>';
                                        var thumbImg = '';
                                        var pending = '<img src="{{ URL::asset("front-assets/images/pending.png") }}" height="14" width="14" alt="Pending" srcset="">';
                                    } else if(value.feedback == 1) {
                                        var resendMail = '';
                                        var thumbImg = '<img src="{{ URL::asset("front-assets/images/thumbs-up.png") }}" height="14" width="14" alt="Thumbs Up" srcset="">';
                                        var pending = '';
                                    } else {
                                        var ConfigUser = 'configUserResendMail('+value.id+','+value.quote_id+',0)';
                                        var resendMail = '<span class="pe-2"><button type="button" class="border-0 bg-transparent" href="javascript:void(0)" onclick="'+ConfigUser+'" title="{{ __("admin.resend") }}"> <img src="{{ URL::asset("front-assets/images/resendMailBtn.png") }}" height="20px" width="20px" alt="" srcset=""></a></span>';
                                        var thumbImg = '<img src="{{ URL::asset("front-assets/images/thumbs-down.png") }}" height="14" width="14" alt="Thumbs Down" srcset="">';
                                        var pending = '';
                                    }
                                    finalHtml += '<div class="col-12 border-bottom"><div class="d-flex mb-1 pt-1 align-items-center"><div class="col-md-auto" style="font-size: 12px;">'+
                                    value.email+'</div><div class="col-md-auto ms-auto">'+ resendMail + thumbImg + pending + '</div></div></div>';
                                }
                            });
                            $('#feedbackContent'+quoteId).html(finalHtml);
                            $('#feedbackContentCompare'+quoteId).html(finalHtml);
                        });
                        //Mouse End
                    }
                    $("#getApprovalBtnDiv"+quoteId).addClass('hide').hide();
                    $("#approvalWaitingBtn"+quoteId).removeClass('showed').addClass('hide').hide();
                    $("#getApprovalBtnDivCompare"+quoteId).addClass('hide').hide();
                    $("#approvalWaitingBtncompare"+quoteId).removeClass('showed').addClass('hide').hide();
                    $("#progress"+quoteId).hide();
                    $("#UserCount"+quoteId).hide();
                } else {
                    if(res.totalUser.length == 0) {
                        $("#approvalHeader").css("visibility","hidden");
                    }
                    // if(res.userData) {
                    else {
                        $("#approvalHeader").css("visibility","visible");
                        //Sow user feedback and email
                        var finalHtml = '';
                        $.each(res.userData, function(index,value) {
                            if(value.quote_id && quoteStatus <= 2) {
                                //Show waiting button and hide get approval button
                                $("#getApprovalBtnDiv"+quoteId).addClass('hide').hide();
                                $("#approvalWaitingBtn"+quoteId).removeClass('hide').addClass('showed').show().attr("disabled",true);
                                $("#getApprovalBtnDivCompare"+quoteId).addClass('hide').hide();
                                $("#approvalWaitingBtncompare"+quoteId).removeClass('hide').addClass('showed').show().attr("disabled",true);
                                $("#progress"+quoteId).show();
                                $("#UserCount"+quoteId).show();
                                $("#myDIV"+quoteId).css("visibility","visible");

                                //Set progress bar data for accepted feedback
                                $("#yesbar"+quoteId).css("width",res.percCount)
                                $("#yesProgressCount"+quoteId).text(res.percCount);
                                $("#yesbar"+quoteId).attr("aria-valuenow",$("#yesbar"+quoteId).text().replace('%', '') );
                                $("#ApprovedUsers"+quoteId).text(res.userFeedCount);
                                $("#totalNo"+quoteId).text(res.totalUser);

                                //Set progress bar data for rejecred feedback
                                $("#nobar"+quoteId).css("width",res.rejectPercCount)
                                $("#noProgressCount"+quoteId).text(res.rejectPercCount);
                                $("#nobar"+quoteId).attr("aria-valuenow",$("#nobar"+quoteId).text().replace('%', '') );

                                if(value.feedback == 0) {
                                    var ConfigUser = 'configUserResendMail('+value.id+','+value.quote_id+',1)';
                                    var resendMail = '<span class="pe-2"><button type="button" class="border-0 bg-transparent" href="javascript:void(0)" onclick="'+ConfigUser+'" title="{{ __("admin.resend") }}"> <img src="{{ URL::asset("front-assets/images/resendMailBtn.png") }}" height="20px" width="20px" alt="" srcset=""></button></span>';
                                    var thumbImg = '';
                                    var pending = '<img src="{{ URL::asset("front-assets/images/pending.png") }}" height="14" width="14" alt="Pending" srcset="">';
                                } else if(value.feedback == 1) {
                                    var resendMail = '';
                                    var thumbImg = '<img src="{{ URL::asset("front-assets/images/thumbs-up.png") }}" height="14" width="14" alt="Thumbs Up" srcset="">';
                                    var pending = '';
                                } else {
                                    var ConfigUser = 'configUserResendMail('+value.id+','+value.quote_id+',0)';
                                    var resendMail = '<span class="pe-2"><button type="button" class="border-0 bg-transparent" href="javascript:void(0)" onclick="'+ConfigUser+'" title="{{ __("admin.resend") }}"> <img src="{{ URL::asset("front-assets/images/resendMailBtn.png") }}" height="20px" width="20px" alt="" srcset=""></button></span>';
                                    var thumbImg = '<img src="{{ URL::asset("front-assets/images/thumbs-down.png") }}" height="14" width="14" alt="Thumbs Down" srcset="">';
                                    var pending = '';
                                }
                                finalHtml += '<div class="col-12 border-bottom"><div class="d-flex mb-1 pt-1 align-items-center"><div class="col-md-auto" style="font-size: 12px;">'+
                                value.email+'</div><div class="col-md-auto ms-auto">'+ resendMail + thumbImg + pending + '</div></div></div>';
                            }
                        });
                        $('#feedbackContent'+quoteId).html(finalHtml);
                        $('#feedbackContentCompare'+quoteId).html(finalHtml);
                    }
                }
            },
            error: function() {
                console.log('error');
            }
        });
    }

    $('body').on('click', '.sendQuoteApprovalBtn', function(e) {
        e.stopImmediatePropagation();
        e.preventDefault();
         $('#rfqQuoteDetailsModal').modal('hide');
         
        if($("#configUsersCount").val() == 0) {
            swal({
                text: "{{ __('profile.no_approver_validation') }}",
                icon: "/assets/images/info.png",
                buttons: '{{ __('admin.ok') }}',
                dangerMode: false,
            });
            return false;
        } else {
            $("#getApprovalFeedbackForm")[0].reset();  
            $("#approval_commentError").html('');   
            //New code (RM) - to show reason modal popup    
            var quoteId = $(this).attr('data-id');  
            $("#reason_quoteId").val(quoteId);  
            $("#getApprovalFeedbackModal").modal('show');   
            return false;   
            //New code end
            
            var quoteId = $(this).attr('data-id');
            $('#myimage').attr({src: '/images/myphoto.jpg',width: 200,height: 300});
            $("#sendQuoteApprovalBtn"+quoteId).attr('data-boolean','false');
            $("#sendQuoteApprovalBtn"+quoteId).attr("disabled",true)
            $('#loader'+quoteId).toggleClass('hidden');
            //alert(quoteId);

            $.ajax({
                url: "{{ route('send-quote-configure-users-ajax') }}",
                data: {
                "_token": "{{ csrf_token() }}",
                "quoteId": quoteId
                },
                type: 'POST',
                dataType: "json",

                success: function(successData) {
                    new PNotify({
                        text: successData.msg,
                        type: 'success',
                        styling: 'bootstrap3',
                        animateSpeed: 'fast',
                        delay: 2000
                    });

                    changeBtnName(quoteId);
                    $('#loader'+quoteId).toggleClass('hidden');
                    $('#rfqQuoteDetailsModal').modal('hide');
                },
                error: function() {
                    console.log('error');
                    $('#loader'+quoteId).toggleClass('hidden');
                }
            });
        }
    });


    //Resend Mail
    function configUserResendMail(userId,quoteId,pending_resend) {
        var url = "{{ route('configure-user-resend-mail', [':userId', ':quoteId', ':pending_resend']) }}";
        url = url.replace(':userId',userId);
        url = url.replace(':quoteId',quoteId);
        url = url.replace(':pending_resend',pending_resend);
        //$(this).prop("disabled", true);
        $.ajax({
            url: url,
            type: 'GET',
            success: function(successData) {
                changeBtnName(quoteId);
                new PNotify({
                    text: successData.msg,
                    type: 'success',
                    styling: 'bootstrap3',
                    animateSpeed: 'fast',
                    delay: 2000
                });

                //Show waiting button and hide get approval button
                $("#getApprovalBtnDiv"+quoteId).addClass('hide').hide();
                $("#approvalWaitingBtn"+quoteId).addClass('showed').show();
                $("#getApprovalBtnDivCompare"+quoteId).addClass('hide').hide();
                $("#approvalWaitingBtncompare"+quoteId).addClass('showed').show();
                $("#progress"+quoteId).show();
                $("#UserCount"+quoteId).show();
            },
            error: function() {
                console.log('error');
                //$(this).prop("disabled", false);
            }
        });
    }

    //Delete RFQ attachment file
    $(document).on('click', '.removeRFQFile', function (e) {
        e.preventDefault();
        var element = $(this);
        var id = $(this).attr("data-id");
        var fileName = $(this).attr("id");
        var dataName = $(this).attr("data-name");
        var data = {
            fileName: fileName,
            filePath: $(this).attr("file-path"),
            id: $(this).attr("data-id"),
            _token: $('meta[name="csrf-token"]').attr("content"),
        };
        swal({
            title: "{{ __('admin.delete_sure_alert') }}",
            icon: "/assets/images/bin.png",
            buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
            dangerMode: false,
        }).then((changeit) => {
            if (changeit) {
                $.ajax({
                    url: "{{ route('rfq-attachment-delete-ajax') }}",
                    data: data,
                    type: "POST",
                    success: function (successData) {
                        $("#file-"+ dataName).html('');
                    },
                    error: function () {
                        console.log("error");
                    },
                });
            }
        });
    });

    /*  Download Product certificate in ZIP */
    function downloadCertificate(id,fieldName,ref_no){
        event.preventDefault();
        var data = {
            quote_id:id,
            fieldName: fieldName,
            ref_no: ref_no,
        }
        $.ajax({
            url: "{{ route('quote-attachment-download-ajax') }}",
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
    /* End */

    /**
    * On click of cancel button, call the function  (Ronak M - 12/11/2022)
    */
    $('body').on('click', '#cancelFeedbackBtn', function(e) {
        e.stopImmediatePropagation();
        e.preventDefault();
        $("#getApprovalFeedbackModal").modal('hide');
    });
    /**
    * On click of cancel button, call the function (Ronak M - 12/11/2022)
    */
    $('body').on('click', '#postFeedbackBtn', function(e) {
        e.stopImmediatePropagation();
        e.preventDefault();
        var formData =   $('#getApprovalFeedbackForm').serializeArray();
        var method   =   $('#getApprovalFeedbackForm').attr('method');
        var action   =   $('#getApprovalFeedbackForm').attr('action');
        $("#postFeedbackBtn").prop('disabled', true);
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
                    $("#approval_comment").val('');
                    $("#getApprovalFeedbackModal").modal('hide');
                    sendMailToApprovers();
                    $("#postFeedbackBtn").prop('disabled', false);
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
                        $("#postFeedbackBtn").prop('disabled', false);
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
    /**
    * send mail to all approvers
    */
    function sendMailToApprovers() {
        var quoteId = $("#reason_quoteId").val();
        $('#myimage').attr({src: '/images/myphoto.jpg',width: 200,height: 300});
        $("#sendQuoteApprovalBtn"+quoteId).attr('data-boolean','false');
        $("#sendQuoteApprovalBtn"+quoteId).attr("disabled",true)
        $('#loader'+quoteId).toggleClass('hidden');
        $.ajax({
            url: "{{ route('send-quote-configure-users-ajax') }}",
            data: {
            "_token": "{{ csrf_token() }}",
            "quoteId": quoteId
            },
            type: 'POST',
            dataType: "json",
            success: function(successData) {
                new PNotify({
                    text: successData.msg,
                    type: 'success',
                    styling: 'bootstrap3',
                    animateSpeed: 'fast',
                    delay: 2000
                });
                changeBtnName(quoteId);
                $('#loader'+quoteId).toggleClass('hidden');
                $('#rfqQuoteDetailsModal').modal('hide');
                $("#getApprovalFeedbackModal").modal('hide');
                $("#getApprovalFeedbackModal").parsley().reset();
            },
            error: function() {
                console.log('error');
                $('#loader'+quoteId).toggleClass('hidden');
            }
        });
    }

</script>
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
</script>

@endsection