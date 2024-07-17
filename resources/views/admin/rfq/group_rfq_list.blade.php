@extends('admin/adminLayout')
@section('content')
<link href="{{ URL::asset('/assets/css/admin/filter.css') }}" rel="stylesheet">
<style>
	.settings-panel .tab-content .tab-pane.scroll-wrapper {
    max-height: inherit;
    height: calc(100vh - 125px);
    padding-bottom: 5px !important;
}

.settings-panel {
    height: inherit !important;
    min-height: inherit !important;
}

 .ps__rail-y>.ps__thumb-y,
 .ps__rail-y:hover>.ps__thumb-y,
 .ps__rail-y.ps--clicking .ps__thumb-y {
    background-color: #999;
    width: 7px;
}

 .ps:hover>.ps__rail-y {
    opacity: 1;
}

 .ps__rail-y {
    width: 8px;
}

 .ps__thumb-y {
    right: 0;
}
</style>
<div class="card h-100 bg-transparent newtable_v2">
    <div class="card-body p-0">
        <div class="d-flex align-items-center \\">
            <h4 class="card-title pt-3">{{ __('admin.group_rfq') }}</h4>
            <div class="dropdown ms-auto me-1">
                <div class="pe-1 mb-3 clearfix d-flex align-items-center">
                    <button class="btn btn-warning btn-sm dropdown-toggle  ms-1"
                        style="padding: 0.25rem 0.5rem;" type="button" id="dropdownMenuButton1"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        {{ __('admin.Export') }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end p-0 shadow-lg"
                        aria-labelledby="dropdownMenuButton1">
                        <li class="border-bottom">
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
                                    <img src="{{ URL::asset('front-assets/images/icons/excel-file.png') }}"
                                        class="me-1" alt="" height="14px" srcset="">
                                    </span>
                                {{ __('admin.rfq_quotes') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <input type="hidden" value="{!! csrf_token() !!}" name="_token">
        <div class="row clearfix">
            <div class="col-12">
                <div class="table-responsive">
                    <table id="rfqTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th class="hidden">rfqId</th>
                                <th>{{ __('admin.rfq_number') }}</th>
                                @if(count($groupCount) > 0)
                                    <th>{{ __('admin.group_number') }}</th>
                                @endif
                                <th>{{ __('admin.payment_term') }}</th>
                                <th>{{ __('admin.user_type') }}</th>
                                <th>{{ __('admin.name') }}</th>
                                <th>{{ __('admin.category') }}</th>{{--.' / '. __('admin.sub_category')--}}
                                <th>{{ __('admin.product_name') }}</th>
                                {{-- <th>{{ __('admin.product_description') }}</th> --}}
                                <th>{{ __('admin.date') }}</th>
                                <th>{{ __('admin.status') }}</th>
                                <th>{{ __('admin.action') }}</th>
                            </tr>
                        </thead>
                        <tbody id="rfqData">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- RFQ Modal -->
<div class="modal version2 fade" id="viewRfqModal" tabindex="-1" role="dialog" aria-labelledby="viewRfqModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        </div>
    </div>
</div>
<!-- End -->

<!-- Group view Model-->
<div class="modal version2 fade" id="viewGroupModal" tabindex="-1" role="dialog" aria-labelledby="viewGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal version2 fade" id="viewGroupModal" tabindex="-1" role="dialog" aria-labelledby="viewGroupModalLabel" aria-hidden="true"></div>
        </div>
    </div>
</div>
<!-- Group view Model End-->
<!-- ----------------Feedback Modal------------------ -->
<div class="modal version2 fade newtable_v2" id="viewFeedbackModal" tabindex="-1" role="dialog" aria-labelledby="viewFeedbackLabel" aria-modal="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <style>
                .modal.version2 .table,
                .modal.version2 .table td,
                .modal.version2 .table th {
                    border: 0px solid #c7ceeb;
                }
            </style>
            <div class="modal-header py-3">
                <h5 class="modal-title d-flex align-items-center" id="staticBackdropLabel">
                    <img class="pe-2" height="24px" src="{{ URL::asset('front-assets/images/icons/order_detail_title.png') }}" alt="View RFQ"> {{ __('admin.feedback') }}
                </h5>
                <button type="button" class="btn-close ms-0 d-flex" data-bs-dismiss="modal" aria-label="Close">
                    <img src="{{ URL::asset('front-assets/images/icons/times.png') }}" alt="Close">
                </button>
            </div>
            <div class="modal-body p-3">
                <div id="viewFeedbackBlock">
                    <div class="row align-items-stretch">
                        <div class="col-md-12 pb-2">
                            <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <h5><img src="{{ URL::asset('front-assets/images/icons/comment-alt-edit.png') }}" alt="View Comments" class="pe-2">{{ __('admin.view_feedback') }} </h5>
                                </div>
                                <div class="card-body p-3 pb-1">
                                    <div class="row rfqform_view bg-white">
                                        <form id="feedback_form" data-parsley-validate>
                                            @csrf
                                            <input type="hidden" name="id" id="feedback_id" value="">
                                            <div class="col-md-12 mb-2">

                                                <div class=" p-1 text-end bg-transparent">
                                                    <select class="form-select " name="comment" id="comment" required data-parsley-errors-container="#feeback_error">
                                                        <option value="">{{ __('admin.select_feedback') }}</option>
                                                        @if(sizeof($feedbackReasions) > 0)
                                                            @foreach($feedbackReasions as $feedbackReasion)
                                                                <option class="select_{{ $feedbackReasion->id }}" value="{{ $feedbackReasion->id }}">{{ $feedbackReasion->reasons }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <span id="feeback_error" ></span>
                                                    <a href="javascript:void(0)" class="btn btn-cancel btn-sm rounded-1 comment-btn mt-2" id="clear_feedback" onclick="clearFeedback()">{{ __('admin.clear') }}</a>
                                                    <a href="javascript:void(0)" class="btn btn-primary btn-sm rounded-1 comment-btn mt-2" id="submit_feedback">{{ __('admin.submit') }}</a>
                                                </div>
                                            </div>

                                        </form>
                                        <hr class="my-1">
                                        <div class="comment-section col-md-12 my-2" id="feedbackHtml"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer px-3 ">
                <a class="btn btn-cancel" data-bs-dismiss="modal">Cancel</a>
            </div>
        </div>
    </div>
</div>
<!-- ----------------Feedback Modal Ends------------------ -->
<script>
    var GlobalFeedbackId = '';
    $(document).ready(function() {
        var rfqDataHtml = @json($rfqDataHtml);
		$('#rfqData').html(rfqDataHtml);

        var rfqDataTable = $('#rfqTable').DataTable({
            "order": [
                [0, "desc"]
            ],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "iDisplayLength": 10,
            "columnDefs": [{
                "targets": [0],
                "visible": false,
                "searchable": false
            }, ]
        });

        //Show RFQ details on click of view button
        $(document).on('click', '.viewRfqDetail', function(e) {
            $('#callRFQHistory').html('');
            $('#message').html('');
            e.preventDefault();
            var id = $(this).attr('data-id');
            if (id) {
                $.ajax({
                    url: "{{ route('rfq-detail', '') }}" + "/" + id,
                    type: 'GET',
                    success: function(successData) {
                        $("#viewRfqModal").find(".modal-content").html(successData.rfqview);
                        $('#viewRfqModal').modal('show');
                    },
                    error: function() {
                        console.log('error');
                    }
                });
            }
        });

        //Show Group details on click of view button
        $(document).on('click', '.viewGroupDetail', function(e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            if (id) {
                $.ajax({
                    url: "{{ route('group-detail', '') }}" + "/" + id,
                    type: 'GET',
                    success: function(successData) {
                        $('#viewGroupModal').find('.modal-content').html(successData.groupview);
                        $('#viewGroupModal').modal('show');
                    },
                    error: function() {
                        console.log('error');
                    }
                });
            }
        });
        $(document).on('click', '.viewFeedback', function(e) {
            e.preventDefault();
            $('#feedback_id').val('');
            var Id = $(this).attr('data-id');
            GlobalFeedbackId = Id;
            var url = "{{ route('get-feedback-ajax', [':id', ':type']) }}";
            url = url.replace(":id", Id);
            url = url.replace(":type", 'group_rfq');
            $.ajax({
                url: url,
                type: 'GET',
                success: function(successData) {
                    $("#viewFeedbackModal").find("#feedbackHtml").html('');
                    $("#viewFeedbackModal").find("#feedbackHtml").html(successData.feedbackHtml);
                    $('#viewFeedbackModal').modal('show');
                },
                error: function() {
                    console.log('error');
                }
            });
        });
        $(document).on('click', '#submit_feedback', function(e) {
            var formData = new FormData($("#feedback_form")[0]);
            formData.append('feedback_id', GlobalFeedbackId);
            formData.append('feedback_type', 'group_rfq');
            if ($('#feedback_form').parsley().validate()){
                $.ajax({
                    url:  "{{ route('store-edit-feedback') }}",
                    type: 'POST',
                    data: formData,
                    enctype: 'multipart/form-data',
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function(successData) {
                        $('#comment').val('');
                        $('#feedback_id').val('');
                        $('#feedbackHtml').html('');
                        $('#feedbackHtml').html(successData.feedbackHtml);
                        $('#comment').find('option').removeClass('bg-light');
                        var textval = '';
                        if ($('.viewFeedback').attr('data-id') == ''){
                            textval = '{{  __('admin.add_feedback_success') }}';
                        } else {
                            textval = '{{  __('admin.update_feedback_success') }}';
                        }
                        $.toast({
                            heading: 'Success',
                            text: textval,
                            icon: 'success',
                            loaderBg: '#f96868',
                            position: 'top-right'
                        })

                    },
                    error: function() {
                        console.log('error');
                    }
                });
            }
        });
        $('#viewFeedbackModal').on('hidden.bs.modal', function () {
            clearFeedback();
            GlobalFeedbackId = '';
        });
    });

    function feedbackEdit($id, $reasonId, $this) {
        $('#comment').find('option').removeClass('bg-light')
        $('#feedback_id').val($id);
        $('#comment').val($reasonId).change().focus();
        $('.select_'+$reasonId).addClass('bg-light');
    }
    function feedbackDelete($id, $reasonId,$feedbackId,$feedbackType, $this) {
        var token = '{{ csrf_token() }}';
        swal({
            text: "{{  __('admin.delete_feedback') }}",
            icon: "/assets/images/warn.png",
            buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: "{{ route('delete-feedback-ajax') }}",
                    data: {'id':$id,'feedback_id':$feedbackId, 'feedback_type': $feedbackType, '_token': token},
                    type: 'POST',
                    responseType: 'json',
                    success: function(successData) {
                        $('#comment').val('').change();
                        $('#feedback_id').val('');
                        $('#feedbackHtml').html('');
                        $('#feedbackHtml').html(successData.feedbackHtml);
                        if (successData.success==true) {
                            $.toast({
                                heading: 'Success',
                                text: "{{ __('admin.delete_feedback_success') }}",
                                icon: 'success',
                                loaderBg: '#f96868',
                                position: 'top-right'
                            })
                        }
                    }
                });
            } else {
                return false;
            }
        });
    }
    function clearFeedback() {
        $('#viewFeedbackModal').find('form').trigger('reset');
        $('#feedback_id').val('').trigger('reset');
        $('#comment').find('option').removeClass('bg-light')
    }
</script>
@stop
