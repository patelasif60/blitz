@extends('buyer.layouts.frontend.frontend_layout')
@section('content')
<div class="col-lg-8 col-xl-9 py-2" id="rfqspagination">
    <div class="header_top d-flex align-items-center">
        <h1 class="mb-0">{{ __('order.order') }}</h1>
        <a href="{{ route('export-excel-ajax') }}" class="btn btn-warning btn-sm ms-auto" style="padding: 0.125rem 0.4rem;" id="dropdownMenuButton1" aria-expanded="false">
                {{ __('admin.Export') }}
        </a>
    </div>
    <div class="accordion" id="Rfq_accordian">
        <div class="col-md-12 mb-3 d-flex">
            <div class="col-md-5">
                <select class="form-select py-2"  name="customStatusSearch" id="customStatusSearch">
                    <option value="all">{{ __('rfqs.all') }}</option>
                    @if(count($allOrderStatus))
                       @foreach($allOrderStatus as $value)
                            <option value="{{$value->id}}">{{ __('order.' . $value->name) }}</option>
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
        <livewire:buyer.order.order-list perPage="10" page="1" favrfq="2"/>
    </div>
</div>
<div class="col-lg-8 col-xl-9 py-2 hide" id="repeatRfqform">
    {{-- here repete content are showing --}}
</div>

<!-- Modal -->
<div class="modal" tabindex="-1" id="paymentDoneModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('order.payment_confirm_head') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <h6 class="mb-4">{{ __('order.confirm_message') }}</h6>
                <button type="button" class="btn btn-primary"
                    data-bs-dismiss="modal">{{ __('order.confirm') }}</button>
                <button type="button" class="btn btn-outline-secondary"
                    data-bs-dismiss="modal">{{ __('order.Close') }}</button>
            </div>

        </div>
    </div>
</div>

 {{-- show rfq details on hover --}}
<div class="modal fade" id="rfqDetailsModal" data-bs-backdrop="true" tabindex="-1"
    aria-labelledby="rfqDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content radius_1 shadow-lg" id="rfqDetailsModalBlock"></div>
    </div>
</div>

{{-- show quote details on hover --}}
<div class="modal fade" id="rfqQuoteDetailsModal" data-bs-backdrop="true" tabindex="-1"
    aria-labelledby="rfqQuoteDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content radius_1 shadow-lg" id="rfqQuoteDetailsModalBlock">
            {{-- here quote detail shown from js --}}
        </div>
    </div>
</div>
{{-- Airway Bill Model Start--}}
<div class="modal fade" id="AirwaybillModal" tabindex="-1" aria-labelledby="AirwaybillModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content" id="generateAirwaybillNumberBlock">

        </div>
    </div>
</div>
{{-- Airway Bill Model End--}}
<div class="modal fade" id="trackorderModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title flex-grow-1" id="exampleModalLabel">{{ __('order.track_order') }}</h5>
                <div id="trackorderModalOrderNumber"></div>
                <div class="refreshicon  mx-3">
                    <a href="javascript:void(0);" data-id="" class="refreshOrderStautsDataFromTrackModal"><img
                            src="{{ URL::asset('front-assets/images/icons/icon_refresh.png') }}" alt="refresh"
                            id="refreshOrderImgTrack" class="">
                    </a>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <ul class="bullet-line-list" id="orderStatusTrackBlock">

                </ul>
            </div>

        </div>
    </div>
</div>
<div class="modal" tabindex="-1" id="UploadModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('admin.doc-panding') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <form name="uploadOrderDoc" data-parsley-validate id="uploadOrderDoc"autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    <h6 class="mb-4">{{ __('admin.lc-doc') }}</h6>
                    <h6 class="mb-4">{{ __('admin.qc-doc') }}</h6>
                    <div class="row mb-4 justify-content-center">
                    <div class="col-md-10">
                            <!-- <label for="" class="form-label">NIB File</label> -->
                            <div class="d-flex justify-content-center">
                                <span class="">
                                    <input type="file" data-parsley-required-message="{{ __('admin.select_file') }}" data-parsley-errors-container="#image_error" required name="orderdoc" id="orderdoc" class="form-control" accept=".jpg,.png,.gif,.jpeg,.pdf" hidden="">
                                    <label id="upload_btn"  for="orderdoc">{{ __('profile.browse') }} </label>
                                </span>
                                <div id="file-orderdoc" class="d-flex align-items-center">
                                    <span id="filenamedoc" class="ms-2"></span>
                                </div>
                            </div>
                             <div id="image_error"></div>
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <a data-boolean="false" class="btn btn-primary px-4 py-2 mb-1 saveOrderdoc" href="javascript:void(0)" id="saveOrderdoc"><img src="{{ URL::asset('front-assets/images/upload-arrow.png') }}" width="20px" alt="Post Requirement" class="pe-1">
                            {{ __('admin.Upload_doc') }}</a>
                    </div>
                    <input type="hidden" name="orderdata" id="orderdata">
                    <span id="filenamedoc1" class="ms-2 hide"></span>
                </form>
            </div>
        </div>
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
@livewireScripts
@endsection
@section('script')
<script src="{{ URL::asset('front-assets/js/parsley.min.js') }}"></script>
<script src="{{ URL::asset('front-assets/js/nextpre.js') }}" defer></script>
<script type="text/javascript">
 $(document).ready(function() {
        SnippetSearchList.init()
        if(sessionStorage.getItem("placeorderlivewire")) {
            var lastOrderId = sessionStorage.getItem("placeorderlivewire");
            if (lastOrderId != '') {
                  $('#collapse' + lastOrderId).collapse('show');
            }
            sessionStorage.setItem("placeorderlivewire", '');
        }
    });
    var SnippetSearchList = function () {
        //Show result section
        $(".showSearchData").hide();
         // Input box search with custom text
        searchData = function() {
            $('.customSearch').click(function() {
                let customSearch = $('#customSearch').val();
                let customStatusSearch = $('#customStatusSearch').val();
                if(customSearch || customStatusSearch) {
                    window.livewire.emit('search',customSearch, customStatusSearch,2);
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
                searchData(),
                clearSearchData(),
                customStatusSearch()
            },
        }
    }(1);
    function downloadimg(id, fieldName, name){
        event.preventDefault();
        var data = {
            id: id,
            fieldName: fieldName
        }
        $.ajax({
            url: "{{ route('dashboard-download-image-ajax') }}",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
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
    //open model
    $(document).on('click', '.js-openmodel', function(e) {
        id = $(this).attr('data-id');
        $('#UploadModal').modal('show')
        $('#orderdata').val(id);
        $('#filenamedoc').html('');
    })
    // save doc
    $(document).on('click', '.saveOrderdoc', function(e) {
        if ($('#uploadOrderDoc').parsley().validate()) {
            $('.saveOrderdoc').prop('disabled', true);
            var formData = new FormData($('#uploadOrderDoc')[0]);
            $.ajax({
                url: "{{ route('upload-order-doc') }}",
                data: formData,
                type: 'POST',
                contentType: false,
                processData: false,
                success: function(successData) {
                    $('#UploadModal').modal('hide')
                    $('.qcStatusUpdated').attr('data-upload-doc',1)
                    new PNotify({
                        text: '{{ __('admin.upload.doc') }}',
                        type: 'success',
                        styling: 'bootstrap3',
                        animateSpeed: 'fast',
                        delay: 1000
                    });
                    $('.saveOrderdoc').prop('disabled', false);
                    refreshAllOrderStautsData($('#orderdata').val(), e)
                    refreshOrderStautsData($('#orderdata').val(), e);
                    $('#uploadOrderDoc').trigger("reset");
                },
                error: function() {
                    console.log('error');
                }
            });
        }
    });
    $(document).on('click', '.qcStatusUpdated', function(e) {
        let orderId = $(this).attr('data-order-id');
        if($(this).attr('data-payment-type') > 2 )
        {
            if($(this).attr('data-upload-doc') == '')
            {
                $('#UploadModal').modal('show')
                $('#orderdata').val(orderId);
                $('#filenamedoc').html('');
                return false;
            }
        }
        let orderItemId =[];
         if ($(this).attr('data-is-service') == 1){
             orderItemId = orderItemId;
             var isServiceOrder = 1;
         }else{
             orderItemId = $(this).attr('data-orderitem-id');
             var isServiceOrder = 0;
         }
        let qcStatus = $(this).val();
        let icon = "front-assets/images/icon_smiley1.png";
        let title = "{{ __('order.quality_good_title') }}";
        let text = "{{ __('order.quality_confirm_message_new') }}";
        let selectedStatus = 8;
        if (qcStatus == 2) {
            selectedStatus = 7;
            icon = "front-assets/images/icon_sad1.png";
            title = "{{ __('order.quality_not_good_title') }}";
            text = "{{ __('order.quality_not_good_confirm_message_new') }}";
        }
        swal({
            title: title,
            text: text,
            icon: icon,
            buttons: ["{{ __('order.no') }}", "{{ __('order.confirm') }}"],
            dangerMode: false,
        })
            .then((changeit) => {
                if (changeit) {
                    $('#collapseExample' + orderItemId + ' #qcfail' + orderItemId).attr('disabled', true);
                    $('#collapseExample' + orderItemId + ' #qcpass' + orderItemId).attr('disabled', true);
                    var data = {
                        selectedStatusID: selectedStatus,
                        orderId: orderId,
                        orderItemId: orderItemId,
                        isServiceOrder:isServiceOrder,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    }
                    $.ajax({
                        url: "{{ route('dashboard-order-item-status-change-ajax') }}",
                        data: data,
                        type: 'POST',
                        success: function(successData) {
                            if(successData.success){
                                if (successData.is_all_item_delivered) {
                                    //getOrderStautsData(orderId);
                                }
                                new PNotify({
                                    text: "{{ __('dashboard.order_status_change_success_message') }}",
                                    type: 'success',
                                    styling: 'bootstrap3',
                                    animateSpeed: 'fast',
                                    delay: 1000
                                });
                                if (isServiceOrder != 1) {
                                    $('#collapseExample' + orderItemId).html(successData.orderItemStatusHtml);
                                    $('#orderItemStatusNameBlock' + orderItemId).html(successData.order_status_name);
                                }
                            } else {
                                new PNotify({
                                    text: "{{ __('admin.something_went_wrong') }}",
                                    type: 'warning',
                                    styling: 'bootstrap3',
                                    animateSpeed: 'fast',
                                    delay: 1000
                                });
                            }
                            if (isServiceOrder == 1) {
                                $('#orderSection').trigger('click');
                                setTimeout(function () {
                                    $('#collapse' + orderId).collapse('show')
                                }, 5000)
                            }
                        },
                    });
                } else {
                    $(this).prop('checked', false);
                }
            });
    });
    $(document).on('click', '.refreshOrderStautsData', function(e) {
        e.stopImmediatePropagation();
        e.preventDefault();
        //$('#mainContentSection').html(defaultData);
        var orderId = $(this).attr('data-id');
        $('#refreshOrderImg' + orderId).addClass('rotate');
        $.ajax({
            url: "{{ route('dashboard-refresh-order', '') }}" + "/" + orderId,
            type: 'GET',
            success: function(successData) {
                defaultData = successData.html;
                $('#orderStatusViewBlock' + orderId).html(successData.html);
                countDownTimer(orderId);
                $('#orderStatusViewBlock' + orderId + ' .qcStatusUpdated').attr('name',
                    'orderStatusViewBlockRadioBtn' + Math.random());
                $('#orderStatusNameBlock' + orderId).html(successData.order_status_name);
                $('#orderStatusNameBlock' + orderId).removeClass('bg-primary').removeClass('bg-success');
                if (successData.order_status==5) {
                    $('#orderStatusNameBlock' + orderId).addClass('bg-success');
                }else{
                    if(successData.order_status==10){
                        $('#payment_terms' + orderId).removeClass('bg-danger').addClass('bg-success').html('{{ __('order.advance') }}');
                    }
                    $('#orderStatusNameBlock' + orderId).addClass('bg-primary');
                }
                setTimeout(function() {
                    $('#refreshOrderImg' + orderId).removeClass('rotate');
                }, 500);
            },
            error: function() {
                console.log('error');
            }
        });
    });
    $(document).on('click', '.refreshOrderStautsDataFromTrackModal', function(e) {
        e.stopImmediatePropagation();
        e.preventDefault();
        var orderId = $(this).attr('data-id');
        $('#refreshOrderImgTrack').addClass('rotate');
        $.ajax({
            url: "{{ route('dashboard-refresh-order', '') }}" + "/" + orderId,
            type: 'GET',
            success: function(successData) {
                defaultData = successData.html;
                $('#orderStatusTrackBlock').html(successData.html);
                $('#orderStatusNameBlock' + orderId).html(successData.order_status_name);
                setTimeout(function() {
                    $('#refreshOrderImgTrack').removeClass('rotate');
                }, 500);
            },
            error: function() {
                console.log('error');
            }
        });
    });
    $(document).on('click', '.showTrackOrderModal', function(e) {
        e.stopImmediatePropagation();
        e.preventDefault();
        var orderId = $(this).attr('data-id');
        $('#orderStatusViewBlock' + orderId + ' .qcStatusUpdated').attr('name', 'orderStatusViewBlockRadioBtn' +
            Math.random());
        $('.refreshOrderStautsDataFromTrackModal').attr('data-id', orderId);
        $('#refreshOrderImgTrack').addClass('rotate');
        $.ajax({
            url: "{{ route('dashboard-refresh-order', '') }}" + "/" + orderId,
            type: 'GET',
            success: function(successData) {
                defaultData = successData.html;
                $('#trackorderModal #orderStatusTrackBlock').html(successData.html);
                $('#orderStatusNameBlock' + orderId).html(successData.order_status_name);
                setTimeout(function() {
                    $('#refreshOrderImgTrack').removeClass('rotate');
                }, 500);
                $('#trackorderModalOrderNumber').html(successData.order_number);
                $('#trackorderModal').modal('show');
            },
            error: function() {
                console.log('error');
            }
        });
    });
    $(document).on('click', '.generate-pay-link', function(e) {
        e.preventDefault();
        let orderId = $(this).attr('data-id');
        $(this).html('<img src="{{ URL::asset("front-assets/images/icons/icon_refresh.png") }}" alt="refresh" class="rotate">');
        let that = $(this);
        $.ajax({
            url: "{{ route('generate-pay-link', '') }}" + "/" + orderId,
            type: 'GET',
            success: function(successData) {
                if (successData.success) {
                    $('#refreshOrderImg' + orderId).trigger('click');
                }else{
                    that.html("{{ __('order.pay_generate_button') }}");
                    swal({
                        text: successData.message,
                        icon: "warning",
                        dangerMode: true,
                    });
                }
            },
            error: function() {
                that.html("{{ __('order.pay_generate_button') }}");
                console.log('error');
            }
        });
    });
    function countDownTimer(orderId)
    {
        let selector = $('#remainingTime'+orderId);
        let refreshOrder = $('#refreshOrderImg'+orderId);
        let duration = parseInt(selector.attr('data-remaining-seconds'));
        let timer = duration, hours, minutes, seconds;
        setInterval(function () {
            hours = parseInt((timer /3600)%48, 10)
            minutes = parseInt((timer / 60)%60, 10)
            seconds = parseInt(timer % 60, 10);
            hours = hours < 10 ? "0" + hours : hours;
            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;
            if (hours==0 && minutes==0 && seconds==0){
                refreshOrder.trigger('click');
            }
            selector.text(hours +":"+minutes + ":" + seconds);
            --timer;
        }, 1000);
    }
    $(function (){
        $('.remainingTime').each(function(){
            countDownTimer($(this).attr('data-id'))
        });
    });
    function refreshOrderStautsData(orderId, e) {
        e.stopImmediatePropagation();
        e.preventDefault();
        $('#refreshOrderImg' + orderId).addClass('rotate');
        $.ajax({
            url: "{{ route('dashboard-refresh-order', '') }}" + "/" + orderId,
            type: 'GET',
            success: function(successData) {
                defaultData = successData.html;
                $('#orderStatusViewBlock' + orderId).html(successData.html);
                countDownTimer(orderId);
                $('#orderStatusViewBlock' + orderId + ' .qcStatusUpdated').attr('name',
                    'orderStatusViewBlockRadioBtn' + Math.random());
                $('#orderStatusNameBlock' + orderId).html(successData.order_status_name);
                $('#orderStatusNameBlock' + orderId).removeClass('bg-primary').removeClass('bg-success');
                //$('#pay' + orderId).attr('href', 'javascript:void(0)').hide();
                if (successData.order_status==5) {
                    $('#orderStatusNameBlock' + orderId).addClass('bg-success');
                }else{
                    if(successData.order_status==10){
                        $('#payment_terms' + orderId).removeClass('bg-danger').addClass('bg-success').html('{{ __('order.advance') }}');
                    }
                    $('#orderStatusNameBlock' + orderId).addClass('bg-primary');
                }
                setTimeout(function() {
                    $('#refreshOrderImg' + orderId).removeClass('rotate');
                }, 500);
            },
            error: function() {
                console.log('error');
            }
        });
    }
    function refreshOrdersSubStatus(orderId, e) {
        e.stopImmediatePropagation();
        e.preventDefault();
        $.ajax({
            url: "{{ route('dashboard-refresh-order-sub-status', '') }}" + "/" + orderId,
            type: 'GET',
            success: function(successData) {
                if(successData.success){
                    $('#view_order_sub_status_refresh'+orderId).html('');
                    $('#view_order_sub_status_refresh'+orderId).html(successData.html);
                }
                setTimeout(function() {
                    $('#refreshOrderImg' + orderId).removeClass('rotate');
                }, 500);
            },
            error: function() {
                console.log('error');
            }
        });
    }

        /*********start: Advance/LC/SKBDN status change from buyer.**************/
    function orderStatusChange(selector, is_validate = 0) {
        let selectedStatus = selector.attr('data-status-id');
        let orderId = selector.attr('data-order-id');
        $('#orderStatusChange' + orderId).css('cursor', 'none');
        let data = {
            selectedStatusID: selectedStatus,
            orderId: orderId,
            is_validate: is_validate,
            is_backend_request: 0,
            _token: $('meta[name="csrf-token"]').attr('content')
        }
        $.ajax({
            url: "{{ route('buyer-order-status-change-ajax') }}",
            data: data,
            type: 'POST',
            dataType: 'JSON',
            success: function (successData) {
                console.log(successData);
                if (successData.success == true) {
                    if (successData.valid !== undefined && successData.valid == 1) {
                        swal({
                            title: "{{ __('admin.delete_sure_alert') }}",
                            text: "{{ __('admin.order_status_change_text') }}",
                            icon: "/assets/images/info.png",
                            buttons: ["No", "Yes"],
                            dangerMode: false,
                        }).then((isConfirm) => {
                            if (isConfirm) {
                                $('#orderStatusChange_loading' + orderId).removeClass('d-none');
                                $('#orderStatusChange_loading' + orderId).addClass('d-flex');
                                orderStatusChange(selector, successData.valid);
                            } else {
                              $('#orderStatusChange' + orderId).css('cursor', 'pointer');
                            }
                        });
                    } else {
                        $('#refreshOrderStautsData' + orderId).trigger('click');
                        new PNotify({
                            text: 'Order status changed successfully',
                            type: 'success',
                            styling: 'bootstrap3',
                            animateSpeed: 'fast',
                            delay: 1000
                        });
                    }
                } else {
                    $('#refreshOrderStautsData' + orderId).trigger('click');
                }
                $('#orderStatusChange_loading' + orderId).addClass('d-none');
                $('#orderStatusChange_loading' + orderId).removeClass('d-flex');
            },
            error: function () {
                console.log('error');
                $('#refreshOrderStautsData' + orderId).trigger('click');
                swal({
                    title: '{{ __('admin.something_error_message') }}',
                    icon: "/assets/images/info.png",
                    confirmButtonText: "{{__('admin.ok')}}",
                    dangerMode: false,
                });
            }
        });
    }
    /*********end: Advance/LC/SKBDN status change from buyer.**************/
    /*********start: Credit from buyer.**************/
    function creditOrderStatusChange(selector, is_validate = 0) {
        let selectedStatus = selector.attr('data-status-id');
        let orderId = selector.attr('data-order-id');
        $('#creditOrderStatusChange' + orderId).css('pointer-events', 'none');
        let data = {
            selectedStatusID: selectedStatus,
            orderId: orderId,
            is_validate: is_validate,
            is_backend_request: 0,
            _token: $('meta[name="csrf-token"]').attr('content')
        }
        $.ajax({
            url: "{{ route('buyer-credit-order-status-change-ajax') }}",
            data: data,
            type: 'POST',
            dataType: 'JSON',
            success: function (successData) {
                if (successData.success == true) {
                    if (successData.valid !== undefined && successData.valid == 1) {
                        swal({
                            title: "{{ __('admin.delete_sure_alert') }}",
                            text: "{{ __('admin.order_status_change_text') }}",
                            icon: "/assets/images/info.png",
                            buttons: ["No", "Yes"],
                            dangerMode: false,
                        }).then((isConfirm) => {
                            if (isConfirm) {
                                $('#creditOrderStatusChange_loading' + orderId).removeClass('d-none');
                                $('#creditOrderStatusChange_loading' + orderId).addClass('d-flex');
                                creditOrderStatusChange(selector, successData.valid);
                            } else {
                                $('#refreshOrderStautsData' + orderId).trigger('click');
                            }
                        });
                    } else {
                        $('#refreshOrderStautsData' + orderId).trigger('click');
                        new PNotify({
                            text: 'Order status changed successfully',
                            type: 'success',
                            styling: 'bootstrap3',
                            animateSpeed: 'fast',
                            delay: 1000
                        });
                    }
                } else {
                    $('#refreshOrderStautsData' + orderId).trigger('click');
                }
                $('#creditOrderStatusChange_loading' + orderId).addClass('d-none');
                $('#creditOrderStatusChange_loading' + orderId).removeClass('d-flex');
            },
            error: function () {
                console.log('error');
                $('#refreshOrderStautsData' + orderId).trigger('click');
                swal({
                    title: '{{ __('admin.something_error_message') }}',
                    icon: "/assets/images/info.png",
                    confirmButtonText: "{{__('admin.ok')}}",
                    dangerMode: false,
                });
            }
        });
    }
    /*********end: Credit status change from buyer.**************/

    function refreshAllOrderStautsData(OrderId, e) {
        refreshOrderStautsData(OrderId, e);
        refreshOrdersSubStatus(OrderId, e);
    }
    function productWiseRefresh(OrderId, QuoteItemId, e) {
        /*e.stopImmediatePropagation();
        e.preventDefault();*/
        var url = "{{ route('dashboard-refresh-single-order-item-status', [':orderId', ':quoteItemId']) }}";
        url = url.replace(':orderId',OrderId);
        url = url.replace(':quoteItemId',QuoteItemId);
        $.ajax({
            url: url,
            type: 'GET',
            success: function(successData) {
                if(successData.success){
                    $('#collapseExample'+QuoteItemId).html('');
                    $('#collapseExample'+QuoteItemId).html(successData.html);
                    $('#openProduct'+OrderId).toggleClass('tab_col');
                }
            },
            error: function() {
                console.log('error');
            }
        });
    }
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
        //name show
    $(document).on("change", "#orderdoc", function(e) {
        var $input = $(this);
        var inputFiles = this.files;
        let file = inputFiles[0];
        let size = Math.round((file.size / 1024))
        if(size > 3000){
            swal({
                icon: 'error',
                title: '',
                text: '{{ __('profile.file_size_under_3mb') }}',
            })
            return false;
        }
        let fileName = file.name;
        $('#filenamedoc').html(fileName);
    })
    //remove file
    $(document).on("click", ".removeFile", function(e) {
        e.preventDefault();
        orderId = $(this).attr("data-id");
        let name = $(this).attr("data-name");
        let type = $(this).attr("data-type");
        let data = {
            fileName: name,
            id: $(this).attr("data-id"),
            _token: $('meta[name="csrf-token"]').attr("content"),
            type : type
        };
        swal({
            title: '{{ __('profile.are_you_sure') }}',
            text: '{{ __('profile.once_deleted_you_will') }}',
            icon: "/assets/images/bin.png",
            buttons: ['{{ __('profile.change_no') }}', '{{ __('profile.delete') }}'],
            dangerMode: true,
        }).then((deleteit) => {
            if (deleteit) {
                $.ajax({
                url: "{{ route('profile-company-file-delete-ajax') }}",
                data: data,
                type: "POST",
                    success: function(successData) {
                        $("#file-"+ name).html('');
                        refreshAllOrderStautsData(orderId, e)
                        refreshOrderStautsData(orderId, e);
                    },
                    error: function() {
                        console.log("error");
                    },
                });
            }
        });
    });
     /* Show AirwayBill generate model */
    $(document).on('click', '.showAirwayBillModel', function () {
        var orderId = $(this).attr('data-id');
        $.ajax({
            url: "{{ route('dashboard-get-order-quote-details-ajax', '') }}" + "/" +
                orderId,
            type: 'GET',
            success: function (successData) {
                if (successData.html) {
                    $.getScript("{{ URL::asset('front-assets/js/nextpre.js') }}");
                    $('#generateAirwaybillNumberBlock').html(successData.html);
                    $('#pickupInfo' + orderId).addClass('js-active').trigger("click");
                    $('#pickupAddress' + orderId).addClass('js-active');
                    $('#AirwaybillModal').modal('show');
                }
            },
            error: function () {
                console.log('error');
            }
        });
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
    $(document).on('click', '.closeAirwaybillModel', function () {
        $('.showAirwayBillModel').off('click');
        $('#generateAirwaybillNumberBlock').html('');
        $('#AirwaybillModal').modal('hide');
    });
    //Create group after adding pickup date-time and address
    $(document).on('click', '.generateAirwaybill', function (e) {
        var orderId = $(this).attr('data-id');
        var supplierId = $("#supplier_id" + orderId).val();
        var order_item_ids = $('#order_item_ids' + orderId).val();
        $('#submitAirwaybillForm' + orderId).prop('disabled', true);
        $('#cancelPickupBtn' + orderId).prop('disabled', true);
            if ($('#AirwaybillForm' + orderId).parsley().validate()){
                $('#datetime_missing'+ orderId).html('');
                var formData = new FormData($('#AirwaybillForm' + orderId)[0]);
                formData.append('order_item_ids', $('#order_item_ids' + orderId).val());
                $.ajax({
                    url: "{{ route('dashboard-order-pickup-batch-ajax') }}",
                    data: formData,
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    beforeSend:function(){
                        $('#AirwaybillForm'+orderId).css('pointer-events','none');
                        $('#AirwaybillForm'+orderId).css('opacity','0.5');
                        $('#airwayBillPopupLodder'+orderId).removeClass('d-none');
                        $('#airwayBillPopupLodder'+orderId).addClass('airwayBillPopupLodder-show');
                    },
                    success: function (successBatchData) {
                        if (successBatchData.success == true) {
                            //Generate AirWayBill Number
                            $.ajax({
                                url: "{{ route('dashboard-generate-airwaybill-ajax') }}",
                                type: 'POST',
                                data: {
                                    orderId: orderId,
                                    supplierId: supplierId,
                                    batch_id: successBatchData.id,
                                    pickup_date: successBatchData.order_pickup,
                                    receiver_name: $('#receiver_name'+orderId).val(),
                                    receiver_company_name: $('#receiver_company_name'+orderId).val(),
                                    receiver_email_address: $('#receiver_email_address'+orderId).val(),
                                    receiver_pic_phone: $('#receiver_pic_phone'+orderId).val(),
                                    _token: $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function (successData) {
                                    $('#AirwaybillModal').modal('hide');
                                    $('#submitAirwaybillForm' + orderId).prop('disabled', false);
                                    $('#cancelPickupBtn' + orderId).prop('disabled', false);
                                    $('#AirwaybillForm' + orderId).parsley().reset();
                                    $('#order_item_ids').val(order_item_ids);
                                    var selectorValue = 2;
                                    var orderItemId = successBatchData.id;
                                    if (successData.success == true) {
                                        new PNotify({
                                            text: successData.msg,
                                            type: 'success',
                                            styling: 'bootstrap3',
                                            animateSpeed: 'fast',
                                            delay: 2000
                                        });
                                        orderItemStatusChange(selectorValue, orderItemId, orderId);
                                    } else {
                                        if(successData.msg == 'Charged weight should not be more than 1999 kg'){
                                            new PNotify({
                                                text: successData.msg,
                                                type: 'error',
                                                styling: 'bootstrap3',
                                                animateSpeed: 'fast',
                                                delay: 2000
                                            });
                                            new PNotify({
                                                text: "{{__('admin.batch_generated_successfully')}}",
                                                type: 'success',
                                                styling: 'bootstrap3',
                                                animateSpeed: 'fast',
                                                delay: 2000
                                            });
                                           orderItemStatusChange(selectorValue, orderItemId, orderId);
                                        }
                                    }
                                    $('#submitAirwaybillForm' + orderId).prop('disabled', false);
                                    $('#cancelPickupBtn' + orderId).prop('disabled', false);
                                    setTimeout(function(){
                                        refreshAllOrderStautsData(orderId, e)
                                        refreshOrderStautsData(orderId, e);
                                    },5000)
                                }
                            });
                        }
                    }
                });
            }else{
                var pickupDateTime = $('#pickup_date'+orderId).val();
                if(pickupDateTime == ''){
                    $('#datetime_missing'+ orderId).html('{{__('dashboard.this_field_required')}}')
                }else{
                    $('#datetime_missing'+ orderId).html('');
                }
                $('#submitAirwaybillForm' + orderId).prop('disabled', false);
                $('#cancelPickupBtn' + orderId).prop('disabled', false);
            }
    });
    function orderItemStatusChange(selector, orderItemId = 0, orderId, is_validate = 0) {
        var selectedItemIds = $('#order_item_ids' + orderId).val();
        var selectedStatus = selector;
        var batchId = orderItemId;
        var orderId = orderId;
        var data = {
            selectedStatusID: selectedStatus,
            orderId: orderId,
            batchId: batchId,
            orderItemId: [],
            selectedItemIds: selectedItemIds,
            is_validate: is_validate,
            is_backend_request: 0,
            _token: $('meta[name="csrf-token"]').attr('content')
        }
        $.ajax({
            url: "{{ route('dashboard-order-item-status-change-ajax') }}",
            data: data,
            type: 'POST',
            dataType: 'JSON',
            success: function (successData) {
                if (successData.success == true) {
                    if (successData.valid !== undefined && successData.valid == 1) {
                        orderItemStatusChange(selector, orderItemId, orderId, successData.valid);
                    } else {
                        new PNotify({
                            text: 'Order item status changed successfully',
                            type: 'success',
                            styling: 'bootstrap3',
                            animateSpeed: 'fast',
                            delay: 1000
                        });
                    }
                }
                // $('#orderSection').trigger('click');
                // setTimeout(function(){
                //     $('#collapse'+orderId).collapse('show')
                // },5000)
                $("#generateAirwayBill"+orderId).addClass('d-none');                   
                $('#submitAirwaybillForm' + orderId).prop('disabled', false);
                $('#cancelPickupBtn' + orderId).prop('disabled', false);
                $('#AirwaybillForm'+orderId).removeClass('processing-status');
                $('#airwayBillPopupLodder'+orderId).removeClass('d-none');
                $('#airwayBillPopupLodder'+orderId).addClass('airwayBillPopupLodder-show');
                $('#AirwaybillForm'+orderId).css('opacity','1');
                $('#AirwaybillForm'+orderId).css('pointer-events','all');
                refreshAllOrderStautsData(orderId, e)
            },
            error: function () {
                console.log('error');
                swal({
                    title: '{{ __('admin.something_error_message') }}',
                    icon: "/assets/images/info.png",
                    confirmButtonText: "{{__('admin.ok')}}",
                    dangerMode: false,
                });

                $('#orderSection').trigger('click');
                setTimeout(function(){
                    $('#collapse'+orderId).collapse('show')
                },5000)
                $('#submitAirwaybillForm' + orderId).prop('disabled', false);
                $('#cancelPickupBtn' + orderId).prop('disabled', false);
            }
        });
    }

    function downloadAirWayBill(airWayBillNumber) {
        let clickedLinkName = '.downloadAirWayBill' + airWayBillNumber;
        if (airWayBillNumber) {
            $.ajax({
                url: "{{ route('dashboard-get-shipping-label', '') }}" + "/" + airWayBillNumber,
                type: 'GET',
                beforeSend: function () {
                    $(clickedLinkName).addClass('add-airwaybill-download-process-cursor');
                    $('#downloadAirWayBillDiv').addClass('pointer-event-none');
                },
                success: function (response) {
                    if (response.status == true) {
                        $('#shippingLabelPreview').attr('href', response.pdfUrl);
                        $('#shippingLabelPreview')[0].click();
                    } else {
                        $.toast({
                            heading: "{{__('admin.danger')}}",
                            text: "{{__('admin.something_error_message')}}",
                            showHideTransition: "slide",
                            icon: "error",
                            loaderBg: "#f96868",
                            position: "top-right",
                        });
                    }
                    $(clickedLinkName).removeClass('add-airwaybill-download-process-cursor');
                },
                error: function () {
                    console.log('error');
                    $(clickedLinkName).removeClass('add-airwaybill-download-process-cursor');
                }
            });
        } else {
            return ''
        }
    }
</script>
@endsection