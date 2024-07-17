@extends('admin/adminLayout')
@push('bottom_head')
    <meta name="csrf-token" content="{!! csrf_token() !!}">
    <link href="{{ URL::asset('/assets/css/admin/filter.css') }}" rel="stylesheet">
@endpush
@section('content')
    <div class="card h-100 bg-transparent newtable_v2">
        <div class="card-body p-0">
            <div class="d-flex align-items-center pb-3">
                <h4 class="card-title">{{ __('admin.quotes') }}</h4>
				<div class="dropdown ms-auto me-1">
					<div class="pe-1 mb-3 clearfix d-flex align-items-center">
						<span class="text-muted me-2" id="filter_count_one" style="font-size: 0.7rem; padding: 0.25rem 0.5rem;">
							0 {{__('admin.filter_applied')}}
						</span>
						<div class="nav-item nav-settings" >
							<a class="btn btn-dark btn-sm" style="padding: 0.25rem 0.5rem;" href="javascript:void(0)">{{__('admin.filters')}}</a>
						</div>

						<!-- <a href="{{ route('export-excel-ajax') }}" class="btn btn-warning btn-sm ms-1" style="padding: 0.25rem 0.5rem;" type="button" id="dropdownMenuButton1" aria-expanded="false">
							{{ __('admin.Export') }}
						</a> -->
					</div>
                </div>
            </div>


            <!-- ---Filter---- -->
				<div id="right-sidebar" class="settings-panel filter shadow-lg" style="z-index: 5;">
					<div class="d-flex p-2 align-items-center position-sticky shadow">
						<h4 class="mt-2 fw-bold">{{__('admin.filters')}}</h4>
						<span class="badge badge-outline-primary ms-2" id="filter_count_two" style="padding: 0.125em 0.25em;">0</span>
						<span class="ms-auto">
							<a href="javascript:void(0)" id="clear_btn" class="btn btn-sm btn-dark  p-1 pe-1" style="font-size: 0.8em; margin-right: 0.2rem;" type="button" role="button">
								{{__('admin.clear')}}
							</a>
							<a id="apply_btn" href="javascript:void(0)" class="btn btn-primary btn-sm  p-1" style="font-size: 0.7em; margin-right: 1.75rem;" role="button">
								{{__('admin.apply')}}
							</a>
						</span>
						<a id="filter_close"><i class="settings-close mdi mdi-close"></i></a>
					</div>

					<div class="tab-content h-auto" id="setting-content" style="padding-top: 10px; ">
						<div class="tab-pane fade show active scroll-wrapper" id="todo-section"
							role="tabpanel" aria-labelledby="todo-section">
							<div class="d-flex px-3 mb-0">
								<form class="form w-100 d-none">
									<div class="form-group d-flex ">
										<input type="text" class="form-control todo-list-input" placeholder="{{__('admin.search')}}">
										<button type="submit" class="btn btn-primary btn-sm">
										<i class="fa fa-search"></i></button>
									</div>
								</form>
							</div>
							<div class="list-wrapper px-2 py-2" style="overflow-y: auto;">
								<div class="accordion accordion-solid-header" id="accordion-4"
									role="tablist">
									<div class="card filter-card">
										<div class="card-header" role="tab" id="heading-1">
											<h6 class="mb-0">
												<a data-bs-toggle="collapse" href="#collapse-1"
													aria-expanded="false" aria-controls="collapse-1"
													class="collapsed">
													{{__('admin.rfq_number')}}
												</a>
											</h6>
										</div>
										<div id="collapse-1" class="collapse" role="tabpanel"
											aria-labelledby="heading-1" style="">
											<div class="card-body py-1 pe-1">
												<input class="form-control form-control-sm mb-2 filtersearch" id="rfqnmbr_filtersearch" type="text" placeholder="{{__('admin.search')}}..">
												<div id="rfqnmbr_content">
												@if(sizeof($rfq_numbers) > 0)
													@foreach($rfq_numbers as $key => $value)
														<div class="form-check d-flex align-items-center rfqnmbr_content">
															<input type="checkbox" class="form-check-input rfq_number_checkbox" id="rfqCheck{{$key}}" data-id="{{$key}}">
															<label class="form-check-label" for="rfqCheck{{$key}}">{{$value}}</label>
														</div>
													@endforeach
												@endif
												</div>
											</div>
										</div>
									</div>
									<div class="card filter-card">
										<div class=" card-header" role="tab" id="heading-2">
											<h6 class="mb-0">
												<a class="collapsed" data-bs-toggle="collapse"
													href="#collapse-2" aria-expanded="false"
													aria-controls="collapse-2">
													{{__('admin.quote_number')}}
												</a>
											</h6>
										</div>
										<div id="collapse-2" class="collapse" role="tabpanel"
											aria-labelledby="heading-2">
											<div class="card-body py-1 pe-1">
												<input class="form-control form-control-sm mb-2 filtersearch"
													id="quote_filtersearch" type="text" placeholder="{{__('admin.search')}}..">
                                                <div id="quote_content">
												@if(sizeof($quotes_numbers) > 0)
													@foreach($quotes_numbers as $key => $value)
														<div class="form-check d-flex align-items-center">
															<input type="checkbox" class="form-check-input quote_number_checkbox" id="quoteCheck{{$value}}" data-id="{{$value}}">
															<label class="form-check-label" for="quoteCheck{{$key}}">{{$value}}</label>
														</div>
													@endforeach
												@endif
												</div>
											</div>
										</div>
									</div>
                                    <div class="card filter-card">
                                        <div class=" card-header" role="tab" id="heading-11">
                                            <h6 class="mb-0">
                                                <a class="collapsed" data-bs-toggle="collapse"
                                                   href="#collapse-11" aria-expanded="false"
                                                   aria-controls="collapse-4">
                                                    {{__('admin.buyer_company_info')}}
                                                </a>
                                            </h6>
                                        </div>

                                        <div id="collapse-11" class="collapse" role="tabpanel"
                                             aria-labelledby="heading-11">
                                            <div class="card-body py-1 pe-1">
                                                <input class="form-control form-control-sm mb-2 filtersearch" id="buyer_company_filtersearch" type="text" placeholder="{{__('admin.search')}}..">
                                                <div id="buyer_company_content">
                                                    @if(sizeof($companies) > 0)
                                                        @foreach($companies as $company)
                                                            @if(!empty($company->rfq->companyDetails))
                                                                <div class="form-check d-flex align-items-center">
                                                                    <input type="checkbox" class="form-check-input company_checkbox" id="company{{$company->rfq->companyDetails->id}}" data-id="{{$company->rfq->companyDetails->id}}">
                                                                    <label class="form-check-label" for="customer{{$company->rfq->companyDetails->id}}"> {{$company->rfq->companyDetails->name}} </label>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if(auth()->user()->role_id == 1)
									<div class="card filter-card">
										<div class=" card-header" role="tab" id="heading-4">
											<h6 class="mb-0">
												<a class="collapsed" data-bs-toggle="collapse"
													href="#collapse-4" aria-expanded="false"
													aria-controls="collapse-4">
													{{__('admin.supplier_company_name')}}
												</a>
											</h6>

										</div>

										<div id="collapse-4" class="collapse" role="tabpanel"
											aria-labelledby="heading-4">
											<div class="card-body py-1 pe-1">
												<input class="form-control form-control-sm mb-2 filtersearch" id="supp_company_filtersearch" type="text" placeholder="{{__('admin.search')}}..">
												<div id="supp_company_content">
													@if(sizeof($supplier_companies) > 0)
														@foreach($supplier_companies as $key => $value)
															<div class="form-check d-flex align-items-center">
																<input type="checkbox" class="form-check-input supp_company_checkbox" id="suppCompany{{$value->id}}" data-id="{{$value->id}}">
																<label class="form-check-label" for="suppCompany{{$value->id}}"> {{$value->name}} </label>
															</div>
														@endforeach
													@endif
												</div>
											</div>
										</div>
									</div>
									@endif

									<div class="card filter-card">
										<div class=" card-header" role="tab" id="heading-6">
											<h6 class="mb-0">
												<a class="collapsed" data-bs-toggle="collapse"
													href="#collapse-6" aria-expanded="false"
													aria-controls="collapse-6">
													{{__('admin.quote_sent_date')}}
												</a>
											</h6>
										</div>
										<div id="collapse-6" class="collapse" role="tabpanel"
											aria-labelledby="heading-6">
											<div class="card-body py-2">
												<div class="input-group input-daterange">
													<input type="text" class="form-control ps-2" value="" readonly id="start_date">
													<div class="input-group-addon ps-2 pe-2">to</div>
													<input type="text" class="form-control pe-2" value="" readonly id="end_date">
												</div>
												<!-- <div class="content mt-1">
													<div class="form-check d-flex align-items-center">
														<input type="checkbox" class="form-check-input"
															id="exampleCheck10">
														<label class="form-check-label"
															for="exampleCheck10">11-03-2022 16:36
														</label>
													</div>
												</div> -->
											</div>
										</div>
									</div>
									{{-- <div class="card filter-card">
										<div class=" card-header" role="tab" id="heading-7">
											<h6 class="mb-0">
												<a class="collapsed" data-bs-toggle="collapse"
													href="#collapse-7" aria-expanded="false"
													aria-controls="collapse-7">
													Customer Company Name
												</a>
											</h6>
										</div>
										<div id="collapse-7" class="collapse" role="tabpanel"
											aria-labelledby="heading-7">
											<div class="card-body py-2">
												<input class="form-control form-control-sm mb-2 filtersearch"
													id="filtersearch" type="text" placeholder="Search..">
												<div class="content pt-2">
													@if(sizeof($customer_companies) > 0)
														@foreach($customer_companies as $key => $value)
															<div class="form-check d-flex align-items-center">
																<input type="checkbox" class="form-check-input cust_company_checkbox" id="custCompany{{$value->id}}" data-id="{{$value->id}}">
																<label class="form-check-label" for="custCompany{{$value->id}}"> {{$value->name}} </label>
															</div>
														@endforeach
													@endif
												</div>
											</div>
										</div>
									</div> --}}
									<div class="card filter-card">
										<div class=" card-header" role="tab" id="heading-8">
											<h6 class="mb-0">
												<a class="collapsed" data-bs-toggle="collapse"
													href="#collapse-8" aria-expanded="false"
													aria-controls="collapse-8">
													{{__('admin.category')}}
												</a>
											</h6>
										</div>
										<div id="collapse-8" class="collapse" role="tabpanel"
											aria-labelledby="heading-8">
											<div class="card-body py-2">
												<input class="form-control form-control-sm mb-2 filtersearch"
													id="category_filtersearch" type="text" placeholder="{{__('admin.search')}}..">
												<div id="category_content">
													@if(sizeof($categories) > 0)
														@foreach($categories as $key => $value)
															<div class="form-check d-flex align-items-center">
																<input type="checkbox" class="form-check-input category_checkbox" id="category{{$value->id}}" data-id="{{$value->id}}" data-name="{{$value->name}}">
																<label class="form-check-label" for="category{{$value->id}}"> {{$value->name}} </label>
															</div>
														@endforeach
													@endif
												</div>
											</div>
										</div>
									</div>
									<div class="card filter-card">
										<div class=" card-header" role="tab" id="heading-9">
											<h6 class="mb-0">
												<a class="collapsed" data-bs-toggle="collapse"
													href="#collapse-9" aria-expanded="false"
													aria-controls="collapse-9">
													{{__('admin.payment_terms')}}
												</a>
											</h6>
										</div>
										<div id="collapse-9" class="collapse" role="tabpanel"
											aria-labelledby="heading-9">
											<div class="card-body py-2">
												<div class="form-check d-flex align-items-center">
													<input type="checkbox" class="form-check-input payment_checkbox" id="advance_payment"  data-value="0" value="0">
													<label class="form-check-label" for="advance_payment">{{__('admin.advanced')}}</label>
												</div>
												<div class="form-check d-flex align-items-center">
													<input type="checkbox" class="form-check-input payment_checkbox" id="credit_payment" data-value="1"  value="1">
													<label class="form-check-label" for="credit_payment">{{__('admin.credit')}}</label>
												</div>
											</div>
										</div>
									</div>
									<div class="card filter-card">
										<div class=" card-header" role="tab" id="heading-10">
											<h6 class="mb-0">
												<a class="collapsed" data-bs-toggle="collapse"
													href="#collapse-10" aria-expanded="false"
													aria-controls="collapse-10">
													{{__('admin.status')}}
												</a>
											</h6>
										</div>
										<div id="collapse-10" class="collapse" role="tabpanel"
											aria-labelledby="heading-10">
											<div class="card-body py-2">
												<input class="form-control form-control-sm mb-2 filtersearch"
													id="status_filtersearch" type="text" placeholder="{{__('admin.search')}}..">
                                                <div id="status_content">
													@if(sizeof($status) > 0)
														@foreach($status as $key => $value)
															<div class="form-check d-flex align-items-center">
																<input type="checkbox" class="form-check-input status_checkbox" id="status{{$value['id']}}" data-id="{{$value['id']}}">
                                                                @if(auth()->user()->role_id == 1)
                                                                    <!-- <label class="form-check-label" for="status{{$value['id']}}"> {{$value['backofflice_name']}} </label> -->
                                                                    @if($value['id'] == 1)
                                                                        <label class="form-check-label text-start" for="status{{$value['id']}}">{{ __('rfqs.'.$value['backofflice_name']) }} </label>
                                                                    @elseif($value['id'] == 5)
                                                                        <label class="form-check-label text-start" for="status{{$value['id']}}"> {{__('rfqs.partial_quotation_received')}} </label>
                                                                    @else
                                                                        <label class="form-check-label text-start" for="status{{$value['id']}}"> {{ __('rfqs.'.$value['name']) }} </label>
                                                                    @endif
                                                                @else
                                                                    <label class="form-check-label text-start" for="status{{$value['id']}}"> {{ __('rfqs.'.$value['name']) }} </label>
                                                                @endif
															</div>
														@endforeach
													@endif
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<!-- ---Filter---- -->
            <input type="hidden" value="{!! csrf_token() !!}" name="_token">
            <div class="row clearfix">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="quoteTable" class="table table-hover">
                            <tbody id="quoteData">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="modal fade" id="viewRfqModal" tabindex="-1" role="dialog" aria-labelledby="viewRfqModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewRfqModalLabel">View Quote</h5>
                    {{-- <button href="javascript:void(0);" class="ms-auto btn btn-sm px-2 mx-1 me-2 text-dark"><i class="fa fa-print"></i></button> --}}
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="viewQuoteDetailBlock">
                    </div>
                    <div style="text-align: -webkit-right" id="viewRfqButtons"></div>

                    <div id="callRFQ" style="display:none">
                        <form class="form-group row g-3" id="rfqCallComment" method="POST"
                            action="{{ route('rfq-call-comment') }}" data-parsley-validate>
                            @csrf

                            <div class="col-12">
                                <label for="comment" class="form-label">Call Comments <span class="text-danger">*</span></label>
                                <textarea name="comment" class="form-control" id="comment" cols="30" rows="3"
                                    required></textarea>
                            </div>
                            <input type="hidden" id="call_rfq_id" name="rfq_id" />

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                        <div id="message"></div>
                    </div>
                    <div id="callRFQHistory"></div>

                </div>
                <div class="modal-footer">
                    {{-- <button type="button" class="btn btn-success">Send Quote</button> --}}
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div> -->
    <div class="modal version2 fade" id="viewRfqModalnew" tabindex="-1" role="dialog"
    aria-labelledby="viewRfqModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        </div>
    </div>
    </div>
    <div class="modal version2 fade" id="viewRfqModal" tabindex="-1" role="dialog"
         aria-labelledby="viewRfqModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>
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
	quoteDatatableDraw();
	$(document).on('click','#clear_btn', function(){
		resetFilters();
        let emptyData = {};
        $('#quoteTable').DataTable().destroy();
		quoteDatatableDraw();
	});
	function resetFilters(){

		/* clear search input and reset div elements */
		$("#rfqnmbr_filtersearch").val("");
		searchFilterContent($("#rfqnmbr_filtersearch"), '#rfqnmbr_content div');

		$("#quote_filtersearch").val("");
		searchFilterContent($("#quote_filtersearch"), '#quote_content div');

		$("#product_filtersearch").val("");
		searchFilterContent($("#product_filtersearch"), '#product_content div');

		$("#supp_company_filtersearch").val("");
		searchFilterContent($("#supp_company_filtersearch"), '#supp_company_content div');

        $("#buyer_company_filtersearch").val("");
        searchFilterContent($("#buyer_company_filtersearch"), '#buyer_company_content div');

		$("#category_filtersearch").val("");
		searchFilterContent($("#category_filtersearch"), '#category_content div');

		$("#status_filtersearch").val("");
		searchFilterContent($("#status_filtersearch"), '#status_content div');
		/* clear search input and reset div elements */

		$(".rfq_number_checkbox").prop( "checked", false);
		$(".quote_number_checkbox").prop( "checked", false);
		$(".supp_company_checkbox").prop( "checked", false);
        $(".company_checkbox").prop( "checked", false);
		$(".product_checkbox").prop( "checked", false);
		// $("#start_date").val("");
		// $("#end_date").val("");
		$(".cust_company_checkbox").prop( "checked", false);
		$(".category_checkbox").prop( "checked", false);
		$(".payment_checkbox").prop( "checked", false);
		$(".status_checkbox").prop( "checked", false);
        $('#filter_count_one').text("0 {{__('admin.filter_applied')}}");
        $('#filter_count_two').text(0);

		/* clear date and reset datepicker */
		$("#start_date").val("");
		$("#end_date").val("");
		$('.input-daterange .form-control').datepicker('clearDates');
		/* clear date and reset datepicker */
	}
    $(document).on('click','#apply_btn', function(){
		let data = getOrderRequestData();
		$('#quoteTable').DataTable().destroy();
		quoteDatatableDraw();
	});

    function searchFilterContent(currentElement, ChildElement){
        // Retrieve the input field text and reset the count to zero
        var filter = currentElement.val(), count = 0;
        // Loop through the comment list
        $(ChildElement).each(function() {
            // If the list item does not contain the text phrase fade it out
            if ($(this).find("label").text().search(new RegExp(filter, "i")) < 0) {
                $(this).addClass('d-none');
                $(this).removeClass('d-flex');
                $(this).find("label").addClass('d-flex')
            // Show the list item if the phrase matches and increase the count by 1
            } else {
                $(this).removeClass('d-none');
                $(this).addClass('d-flex');
                $(this).find("label").removeClass('d-flex')
                count++;
            }
        });
    }

    $("#rfqnmbr_filtersearch").keyup(function() {
        searchFilterContent($(this), '#rfqnmbr_content div');
    });

    $("#quote_filtersearch").keyup(function() {
        searchFilterContent($(this), '#quote_content div');
    });

    $("#order_filtersearch").keyup(function() {
        searchFilterContent($(this), '#order_content div');
    });

    $("#supp_company_filtersearch").keyup(function() {
        searchFilterContent($(this), '#supp_company_content div');
    });

    $("#buyer_company_filtersearch").keyup(function() {
        searchFilterContent($(this), '#buyer_company_content div');
    });


    $("#product_filtersearch").keyup(function() {
        searchFilterContent($(this), '#product_content div');
    });

    $("#cust_company_filtersearch").keyup(function() {
        searchFilterContent($(this), '#cust_company_content div');
    });

    $("#category_filtersearch").keyup(function() {
        searchFilterContent($(this), '#category_content div');
    });

    $("#status_filtersearch").keyup(function() {
        searchFilterContent($(this), '#status_content div');
    });
    /* filter by ronak bhabhor */

    $(document).on('click', '.vieQuoteDetail', function(e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        if (id) {
            $.ajax({
                url: "{{ route('quote-detail', '') }}" + "/" + id,
                type: 'GET',
                success: function(successData) {
                    $('#viewRfqModalnew').find('.modal-content').html(successData.quoteHTML);
                    $('#viewRfqModalnew').modal('show');
                },
                error: function() {
                    console.log('error');
                }
            });
        }
    });

    $(document).on('click', '.viewRfqDetail', function(e) {
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
    $(document).on('click', '.viewFeedback', function(e) {
        e.preventDefault();
        $('#feedback_id').val('');
        var Id = $(this).attr('data-id');
        GlobalFeedbackId = Id;
        var url = "{{ route('get-feedback-ajax', [':id', ':type']) }}";
        url = url.replace(":id", Id);
        url = url.replace(":type", 'quote');
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
        formData.append('feedback_type', 'quote');
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

    $(document).on('click', '#submit_feedback1', function(e) {
        var formData = new FormData($("#feedback_form")[0]);
        formData.append('feedback_id', GlobalFeedbackId);
        formData.append('feedback_type', 'quote');
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
                    $('#viewFeedbackModal').modal('hide');
                    $('#comment').val('');
                    $('#feedback_id').val('');
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
    })
});
function downloadCertificate(id, fieldName, name) {
    event.preventDefault();
    var data = {
        id: id,
        fieldName: fieldName
    }
    $.ajax({
        url: "{{ route('quote-download-certificate-ajax') }}",
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
/*  Download Product certificate in ZIP */
function downloadProductCertificateZip(id,fieldName,ref_no){
    event.preventDefault();
    var data = {
        quote_id:id,
        fieldName: fieldName,
        ref_no: ref_no,
    }
    $.ajax({
        url: "{{ route('quote-product-certificate-zip-download-ajax') }}",
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
function feedbackEdit($id, $reasonId, $this) {
    $('#comment').find('option').removeClass('bg-light');
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
        }
        else {
            return false;
    	}
    });
}
function getOrderRequestData(){
	let data = {};
	let rfq_ids = [];
    let filterCount = 0;
	let checkedRfq = $('.rfq_number_checkbox:checkbox:checked');
	$.each(checkedRfq, function(key, value){
		rfq_ids.push($(value).attr('data-id'));
	});
    if(rfq_ids.length > 0){
        data.rfq_ids = rfq_ids;
        filterCount++;
    }

    let quote_ids = [];
	let checkedQuote = $('.quote_number_checkbox:checkbox:checked');
	$.each(checkedQuote, function(key, value){
		quote_ids.push($(value).attr('data-id'));
	});
    if(quote_ids.length > 0){
        data.quote_ids = quote_ids;
        filterCount++;
    }

    /* let order_ids = [];
	let checkedOrder = $('.order_number_checkbox:checkbox:checked');
	$.each(checkedOrder, function(key, value){
		order_ids.push($(value).attr('data-id'));
	});
    if(order_ids.length > 0){
        data.order_ids = order_ids;
    } */

    let supp_company_ids = [];
	let checkedSuppCompany = $('.supp_company_checkbox:checkbox:checked');
	$.each(checkedSuppCompany, function(key, value){
		supp_company_ids.push($(value).attr('data-id'));
	});
    if(supp_company_ids.length > 0){
        data.supp_company_ids = supp_company_ids;
        filterCount++;
    }

    let product_ids = [];
	let checkedProduct = $('.product_checkbox:checkbox:checked');
	$.each(checkedProduct, function(key, value){
		product_ids.push($(value).attr('data-id'));
	});
    if(product_ids.length > 0){
        data.product_ids = product_ids;
        filterCount++;
    }

    let cust_company_ids = [];
	let checkedCustCompany = $('.cust_company_checkbox:checkbox:checked');
	$.each(checkedCustCompany, function(key, value){
		cust_company_ids.push($(value).attr('data-id'));
	});
    if(cust_company_ids.length > 0){
        data.cust_company_ids = cust_company_ids;
        filterCount++;
    }

    let category_ids = [];
    let category_names = [];
	let checkedCategories = $('.category_checkbox:checkbox:checked');
	$.each(checkedCategories, function(key, value){
		category_ids.push($(value).attr('data-id'));
        category_names.push($(value).attr('data-name'));
	});
    if(category_ids.length > 0){
        data.category_ids = category_ids;
        filterCount++;
    }
    if(category_names.length > 0){
        data.category_names = category_names;
    }

    let payment = [];
	let checkedPayment = $('.payment_checkbox:checkbox:checked');
	$.each(checkedPayment, function(key, value){
		payment.push($(value).attr('data-value'));
	});
    if(payment.length > 0){
        data.payment = payment;
        filterCount++;
    }

    let status_ids = [];
	let checkedStatus = $('.status_checkbox:checkbox:checked');
	$.each(checkedStatus, function(key, value){
		status_ids.push($(value).attr('data-id'));
	});
    if(status_ids.length > 0){
        data.status_ids = status_ids;
        filterCount++;
    }

	let min_price = $('#min_price').val();
	let max_price = $('#max_price').val();
	let price_range = [];
	if(min_price && max_price){
		data.min_price = min_price;
		data.max_price = max_price;
        filterCount++;
	}

	let start_date = $('#start_date').val();
	let end_date = $('#end_date').val();
	let date_range = [];
	if(start_date && end_date){
		data.start_date = start_date;
		data.end_date = end_date;
        filterCount++;
	}

    let buyercompany_ids = [];
    let checkedCompanies = $('.company_checkbox:checkbox:checked');
    $.each(checkedCompanies, function(key, value){
        buyercompany_ids.push($(value).attr('data-id'));
    });
    if(buyercompany_ids.length > 0){
        data.company_ids = buyercompany_ids;
        filterCount++;
    }


    $('#filter_count_one').text(filterCount+" {{__('admin.filter_applied')}}");
    $('#filter_count_two').text(filterCount);
	return data;
}
quoteDatatableDraw = () => {
	$('#quoteTable').DataTable({
		serverSide: !0,
        paginate: !0,
        processing: !0,
        lengthMenu: [
            [10, 100, 200,500, -1],
			[10, 100, 200,500, "All"]
        ],
        footer:!1,
		ajax: {
            url     : "{{route('quotes-list')}}",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data : function (data){
                 filterData = getOrderRequestData();
                 data.filterData = filterData;
            },
            method  : "GET",
        },
		columns: [
            {data: "id", class:"d-none"},
            {data: "quote_number", title: "{{__('admin.quote_number')}}"}, // loan_number key
            {data: "rfq_number", title: "{{__('admin.rfq_number')}}"}, // loan_number key
            {data: "name", title: "{{ __('admin.name')}}"},
            {data: "buyer_company_name", title: "{{ __('admin.buyer_company_info')}}"}, // buyer company name
            {data: "category", title: "{{ __('admin.category') }}"},
            {data: "product_name", title: "{{ __('admin.product_name') }}"},
            {data: "createDate", title: "{{ __('admin.date') }}"},
            {data: "validTill", title: "{{ __('admin.valid_till') }}" , class: "text-nowrap"},
            {data: "statusId", title: "{{ __('admin.status') }}"},
            @canany(['publish quotes', 'edit quotes'])
            {data: "actions", title: "{{ __('admin.actions') }}" , class: "text-nowrap text-end"}
            @endcanany
        ],
        aoColumnDefs: [
            { "bSortable": true, "aTargets": [0,1,2,3,4,5,6,7,8] },
            { "bSortable": false, "aTargets": [10] }
        ],
        language: {
            search: "{{__('admin.search')}}",
            loadingRecords: "{{__('admin.please_wait_loading')}}",
            processing: "<i class='fa fa-spinner fa-spin fa-2x fa-fw'></i><span> {{__('admin.loading')}}..</span>"
        },
        order: [[0, 'desc']],
	}).draw();
}
function clearFeedback() {
    $('#viewFeedbackModal').find('form').trigger('reset');
    $('#feedback_id').val('').trigger('reset');
    $('#comment').find('option').removeClass('bg-light')
}
</script>
@stop
