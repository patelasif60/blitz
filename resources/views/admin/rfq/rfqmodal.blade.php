<style>
    .modal.version2 .table, .modal.version2 .table td, .modal.version2 .table th {
        border: 0px solid #c7ceeb;
    }
</style>
<div class="modal-header py-3">
    <h5 class="modal-title d-flex align-items-center" id="staticBackdropLabel"><img class="pe-2" height="24px" src="{{URL::asset('front-assets/images/icons/order_detail_title.png')}}" alt="View RFQ"> {{$data->reference_number ?? ''}}
    </h5>
    @if(isset($group->group_id))
        <span>
            <button type="button" class="btn btn-info btn-sm ms-2 text-white" style="border-radius: 20px">BGRP-{{$group->group_id}}</button>
        </span>

        <span class="btn rounded-pill printicon ms-auto d-none" data-toggle="tooltip" data-placement="bottom" title="Edit RFQ">
            <a href="{{ route('rfq-edit', ['id' => Crypt::encrypt($data->id)]) }}" target="_blank" ><i class="fa fa-edit"></i></a>
        </span>
    @endif

    <span class="btn btn-warning rounded-pill printicon ms-auto" data-toggle="tooltip" data-placement="bottom" title="{{ __('admin.print') }}"><a  target="_blank" href="{{ route('dashboard-get-rfq-print',Crypt::encrypt($data->id)) }}"><i class="fa fa-print"></i></a></span>


    <button type="button" class="btn-close ms-0 d-flex" data-bs-dismiss="modal" aria-label="Close">
        <img src="{{URL::asset('front-assets/images/icons/times.png')}}" alt="Close">
    </button>
</div>
<div class="modal-body p-3">
    <div id="viewQuoteDetailBlock">
        <div class="row align-items-stretch">
            <div class="col-md-12 pb-2">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h5><img src="{{URL::asset('front-assets/images/icons/comment-alt-edit.png')}}" alt="RFQ Detail " class="pe-2"> {{ __('admin.rfq_detail') }} </h5>
                    </div>
                    <div class="card-body p-3 pb-1">
                        <div class="row rfqform_view bg-white">
                            <div class="col-md-3 pb-2">
                                <label>{{ __('admin.rfq_number') }}:</label>
                                <div>{{$data->reference_number ?? ''}}</div>
                            </div>
                            <div class="col-md-3 pb-2">
                                <label>{{ __('admin.date') }}:</label>
                                <div class="text-dark">{{changeDateTimeFormat($data->created_at)}}</div>
                            </div>
                            <div class="col-md-3 pb-2">
                                <label>{{ __('admin.status') }}:</label>
                                <div> @if($status!='') {{ $status}} @else {{$data->rfqStatus()->value('name') ? __('admin.'.trim($data->rfqStatus()->value('name')))  : ''}} @endif</div>
                            </div>
                            <div class="col-md-3 pb-2">
                                <label>{{ __('admin.payment_term') }}:</label>
                                @if($data->payment_type==1)
                                    <div class="text-dark"><span class="badge rounded-pill bg-danger">{{ __('admin.credit') }} -{{$data->credit_days}}</span></div>
                                @elseif($data->payment_type==0)
                                    <div class="text-dark"><span class="badge rounded-pill bg-success">{{ __('admin.advance') }}</span></div>
                                @elseif($data->payment_type==3)
                                    <div class="text-dark"><span class="badge rounded-pill bg-danger">{{  __('admin.lc')  }}</span></div>
                                @elseif($data->payment_type==4)
                                    <div class="text-dark"><span class="badge rounded-pill bg-danger">{{ __('admin.skbdn') }}</span></div>
                                @else
                                    <div class="text-dark"><span class="badge rounded-pill bg-danger">{{ __('admin.credit') }}</span></div>
                                @endif
                            </div>
                            <div class="col-md-3 pb-2">
                                <label>{{ __('admin.company_of_buyer') }}:</label>
                                <div class="text-dark">{{$comp}}</div>
                            </div>
                            <div class="col-md-3 pb-2">
                                <label>{{ __('admin.customer_name') }}:</label>
                                <div class="text-dark"> {{$data->firstname ??''}} {{$data->lastname ?? ''}}</div>
                            </div>
                            @if(auth()->user()->role_id != 3)
                            <div class="col-md-3 pb-2">
                                <label>{{ __('admin.customer_email') }}:</label>
                                <div class="text-dark">{{$data->email ?? ''}}</div>
                            </div>
                            <div class="col-md-3 pb-2">
                                <label>{{ __('admin.customer_phone') }}:</label>
                                <div class="text-dark"> {{$data->mobile ? countryCodeFormat($data->phone_code, $data->mobile) : ''}}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

                    <!-- Product Detail -->
                    <div class="col-md-12 pb-2">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                        <h5><img height="20px" src="{{URL::asset('front-assets/images/icons/boxes.png')}}" alt="Product Detail" class="pe-2"> {{ __('admin.product_detail') }}</h5>
                        </div>
                        @php $product = $data->rfqProduct()->first(['category','category_id','sub_category','product','quantity','product_description','comment','expected_date']) @endphp
                        <input type="hidden" name="prodCategoryId" value="{{ $product->category }}">
                        <div class="card-body p-3 pb-1">
                            <div class="row rfqform_view bg-white">
                                <div style="max-height: 250px; overflow-y:scroll;">
                                    <table class="table table-responsive border-0 ps-2 pe-2 mb-3">
                                        <thead style="position: sticky; top:-2px;">
                                            <tr class="bg-light">
                                                <th width="35%">{{ __('admin.product') }}</th>
                                                <th width="15%">{{ __('admin.qty') }}</th>
                                                <th width="50%">{{ __('admin.product_description') }}</th>
                                            </tr>
                                        </thead>
                                        @foreach($all_products as $key => $value)
                                        <tr class="border-bottom">
                                            <td>
                                                <div class="text-dark"> {{ $value->category . ' - ' . $value->sub_category . ' - ' . $value->product }}</div>
                                            </td>
                                            <td>
                                                <div class="text-dark"> {{ $value->quantity }} {{ $value->unit_name }}</div>
                                            </td>
                                            <td>
                                                <div class="text-dark"> {{ $value->product_description }}</div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </table>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label>{{ __('admin.comments') }}:</label>
                                    <div class="text-dark">{{$product->comment ?? '-'}}</div>
                                </div>
                                @if(isset($rfq_attachments) && count($rfq_attachments)>=1)
                                    @php
                                        if(count($rfq_attachments)>1){
                                            $downloadAttachment = "downloadAttachment(".$data->id.", 'attached_document','".$data->reference_number."')";
                                        }else{
                                            $rfqFileTitle = Str::substr($rfq_attachments[0]->attached_document,44);
                                            $extension_rfq_file = getFileExtension($rfqFileTitle);
                                            $rfq_file_filename = getFileName($rfqFileTitle);
                                            if(strlen($rfq_file_filename) > 10){
                                               $rfq_file_name = substr($rfq_file_filename,0,10).'...'.$extension_rfq_file;
                                            } else {
                                               $rfq_file_name = $rfq_file_filename.$extension_rfq_file;
                                            }
                                            $downloadAttachment = "downloadimg(".$rfq_attachments[0]->id.", 'attached_document', '".$rfq_file_name."')";
                                        }
                                    @endphp
                                    <div class="col-md-3 mb-3">
                                        <label for="download_attachment" class="form-label">{{ __('rfqs.attachment') }}</label>
                                        <div>
                                            <a class="text-decoration-none btn btn-primary p-1" href="javascript:void(0);" data-id="{{ $data->id }}" id="RfqFileDownload" onclick="{{$downloadAttachment}}"  title="{{ __('rfqs.rfq_attachment') }}" style="text-decoration: none;font-size: 12px;"> <svg id="Layer_1" width="12px" fill="#fff" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 383.26 408.81"><path d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z" transform="translate(-64.37 -51.59)"/><path d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z" transform="translate(-64.37 -51.59)"/></svg> {{ __('rfqs.rfq_attachment') }}</a>
                                        </div>
                                    </div>
                                @endif
                                @if(isset($data->termsconditions_file) && !empty($data->termsconditions_file))
                                    @php
                                        $termsconditionsFileTitle = Str::substr($data->termsconditions_file,stripos($data->termsconditions_file, "termsconditions_file_") + 21);
                                        $extension_termsconditions_file = getFileExtension($termsconditionsFileTitle);
                                        $termsconditions_file_filename = getFileName($termsconditionsFileTitle);
                                        if(strlen($termsconditions_file_filename) > 10){
                                            $termsconditions_file_name = substr($termsconditions_file_filename,0,13).$extension_termsconditions_file;
                                        } else {
                                            $termsconditions_file_name = $termsconditions_file_filename.$extension_termsconditions_file;
                                        }
                                    @endphp
                                    <div class="col-md-3 mb-3">
                                        <label for="termsconditions_file" class="form-label">{{ __('admin.commercial_terms') }}</label>
                                        <div>
                                            <a href="{{ $data->termsconditions_file? Storage::url($data->termsconditions_file) :'javascript:void(0);' }}" class="text-decoration-none" target="_blank" title="{{$termsconditions_file_name}}">{{$termsconditions_file_name}}</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                        <h5><img height="20px" src="{{URL::asset('front-assets/images/icons/truck.png')}}" alt="Delivery Detail " class="pe-2"> @if(in_array($product->category_id,\App\Models\Category::SERVICES_CATEGORY_IDS)) {{ __('dashboard.pickup_details') }} @else {{ __('admin.delivery_detail') }} @endif </h5>
                        </div>
                        <div class="card-body p-3 pb-1">
                            <div class="row rfqform_view bg-white">
                                <div class="col-md-4 pb-2">
                                    <label>{{ __('rfqs.address') }}:</label>
                                    <div class="text-dark">{{$data->address_line_1 ?($data->address_line_1.','):'-'}} {{$data->address_line_2 ? $data->address_line_2:''}}</div>
                                </div>
                                <div class="col-md-4 pb-2">
                                    <label>{{ __('admin.sub_district') }}:</label>
                                    <div class="text-dark">{{$data->sub_district ? $data->sub_district:'-'}}</div>
                                </div>
                                <div class="col-md-4 pb-2">
                                    <label>{{ __('admin.district') }}:</label>
                                    <div class="text-dark">{{$data->district ? $data->district:'-'}}</div>
                                </div>
                                <div class="col-md-4 pb-2">
                                    <label>{{ __('admin.city') }}:</label>
                                    <div class="text-dark">{{ $data->city_id > 0 ? $data->getCity()->name : ($data->city ? $data->city : '-' ) }}</div>
                                </div>
                                <div class="col-md-4 pb-2">
                                    <label>{{ __('admin.provinces') }}:</label>
                                    <div class="text-dark">{{ $data->state_id > 0 ? $data->getState()->name : ($data->state ? $data->state : '-' ) }}</div>
                                </div>
                                <div class="col-md-4 pb-2">
                                    <label>{{ __('admin.pin_code') }}:</label>
                                    <div class="text-dark">{{$data->pincode ? $data->pincode:'-'}}</div>
                                </div>
                                <div class="col-md-4 pb-2">
                                    <label>{{ __('admin.expected_delivery_date') }}:</label>
                                    <div class="text-dark">{{$product->expected_date?changeDateFormat($product->expected_date):''}}</div>
                                </div>
                                <div class="col-md-8 pb-2">

                                    <label>{{ __('admin.other_option') }}:</label>
                                    <div class="text-dark ps-4">
                                        <div class="form-check form-check-inline my-0">
                                            <input class="form-check-input" type="checkbox"  id="inlineCheckbox1" value="option1" disabled {{$data->unloading_services == 1 ?'checked' : ''}} style="margin-top: 3px;">
                                            <label class="form-check-label" for="inlineCheckbox1" readonly>{{ __('dashboard.need_unloading_services') }}</label>
                                            </div>
                                            <div class="form-check form-check-inline my-0">
                                            <input {{$data->rental_forklift == 1 ?'checked' : ''}} class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option2" disabled style="margin-top: 3px;">
                                            <label class="form-check-label" for="inlineCheckbox2">{{ __('admin.need_rental_forklift') }} </label>
                                            </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                @if($productExist == false || $data->status_id == 3 || $data->rfq_status_id == 4 || $orderPlaced == 1 || count($supplierDetail) == 0 )
                <div class="col-md-12 pt-2">
                        @if($productExist == false)
                        <div class="alert alert-info text-center mb-0">
                                    <label class="d-none"></label>
                                    <div class="text-dark">{{ __('admin.rfq_product_not_available') }}.</div>
                        </div>
                        @elseif($data->status_id == 3)
                        <div class="alert alert-danger text-center mb-0">
                        <label class="d-none"></label>
                        <div class="text-dark">{{ __('admin.rfq_cancel') }}</div>
                        </div>
                        @elseif($data->status_id == 4)
                        <div class="alert alert-success text-center mb-0">
                        <label class="d-none"></label>
                        <div class="text-dark">{{ __('admin.rfq_complete') }}</div>
                        </div>
                        @elseif($orderPlaced == 1)
                        <div class="alert alert-warning text-center mb-0">
                        <label class="d-none"></label>
                        <div class="text-dark">{{ __('admin.order_placed') }}</div>
                        </div>
                        @else
                            @if(!Auth::user()->hasRole('supplier'))
                                <div class="alert alert-danger text-center mb-0">
                                    <label class="d-none"></label>
                                    <div class="text-dark">{{ __('admin.supplier_not_available') }}</div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
                @endif
                @if($user_id == null)
                <div class="col-md-12 pt-2">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                        <h5><img height="20px" src="{{URL::asset('front-assets/images/icons/truck.png')}}" alt="Delivery Detail " class="pe-2">  {{ __('admin.rfq_via_guest') }} </h5>
                        </div>
                        <div class="card-body p-3 pb-1">
                            <div class="row rfqform_view bg-white">
                            <div id="callRFQ">
                                <form class="form-group row g-3" id="rfqCallComment" method="POST"
                                    action="{{ route('rfq-call-comment') }}" data-parsley-validate>
                                    @csrf

                                    <div class="col-12">
                                        <label for="comment" class="form-label">{{ __(['admin.call_comments']) }} <span class="text-danger">*</span></label>
                                        <textarea name="comment" class="form-control" id="comment" cols="30" rows="3"
                                            required></textarea>
                                    </div>
                                    <input type="hidden" id="call_rfq_id" name="rfq_id" value="{{$data->id}}" />

                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary btn-sm"><small>{{ __('admin.save') }}</small></button>
                                    </div>
                                </form>
                                <div id="message"></div>
                            </div>

                            <div id="callRFQHistory">
                            </div>

                            </div>
                        </div>
                    </div>
                </div>
                @endif
        </div>
    </div>



</div>

@php
    $xenAccountNotExist = 0;
    $authUser = auth()->user();
@endphp
@if($authUser->role_id==\App\Models\Role::SUPPLIER && empty($authUser->supplier->xen_platform_id))
    @php
        $xenAccountNotExist = 1;
    @endphp
@endif
<div class="modal-footer px-3 {{$xenAccountNotExist?'justify-content-between':''}}">
    @if($xenAccountNotExist)
        <div class="text-danger"><a href="{{ route('supplier.profile.index') }}" class="text-primary border-bottom">{{ __('admin.click_here') }}</a> {{ __('admin.to_fill_required_details_and_create_xendit_ac') }}</div>
        <div>
    @endif

    @if($user_id)
        @if(auth()->user()->role_id == 1 && $data->status_id == 1 && $orderPlaced == 0)
           <a href="javascript:void(0)" class="{{ isset($data->is_preferred_supplier) && $data->is_preferred_supplier == 1 ? '' : 'me-auto' }} btn alert btn-danger text-white cancelRfq" data-rfq_id="{{Crypt::encrypt($data->id)}}" style="padding: 0.625rem;">{{__('admin.want_to_cancel_rfq')}}</a>

           <!-- Show preferred supplier only if "is_preferred_supplier = 1" in rfqs table -->
           @if($data->is_preferred_supplier == 1)
                <div class="position-relative ms-2">
                    <a class="icon_prefferd_admin" id="preferredSuppliersCollapse" data-bs-toggle="collapse" href="#collapseExample" role="button"
                    aria-expanded="false" aria-controls="collapseExample" data-rfq-id="{{ $data->id }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('profile.preferred_suppliers') }}">
                    </a>

                    <div class="collapse prefsupp prefsupp1 shadow border" id="collapseExample">
                        <div class="row mx-0">
                            <div class="col-12">
                                <div class="row dark_blue_bg text-white py-1 px-1">
                                    <div class="col-12 text-wrap d-flex justify-content-between align-items-center ms-2">
                                        <div class="col-5">
                                            <div class="form-check ms-1 mt-1">
                                                <!-- <input class="form-check-input" type="checkbox" value="" id="flexCheckAllDefault" disabled> -->
                                                <label class="form-check-label bg-transparent mt-1" for="flexCheckAllDefault">{{ __('profile.preferred_suppliers') }}</label>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-check ms-1 mt-1">
                                                <label class="form-check-label bg-transparent mt-1 text-center" for="">{{ __('admin.dealing_with_product') }}</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-check ms-1 mt-1">
                                                <label class="form-check-label bg-transparent mt-1 text-center" for="">{{ __('admin.no_of_product') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 p-0 prefer-edit bg-white" id="preferredSuppliersList">

                        </div>

                    </div>
                </div>
            @endif

        @endif
            {{-- dont remove this commenting code --}}
{{--        @if($productExist && count($supplierDetail) > 0 && $data->status_id != 3 && $data->rfq_status_id != 4 && $orderPlaced == 0  &&  $authUserRoleId != 7)--}}
        @if($productExist && $data->status_id != 3 && $data->rfq_status_id != 4 && $orderPlaced == 0  &&  $authUserRoleId != 7)
            @if(Auth::user()->hasRole('supplier'))
                @if($productExistRFQ == 'NOT' || $productExistRFQ == 'NOT ALL PRODUCT')
                    <a href="{{route('add-supplier-product')}}" class="btn btn-primary">{{ __('admin.add_product') }}</a>
                @endif

                @if((($loginSupplierDetails->company_type == 2) || ($loginSupplierDetails->company_type == 1 && $loginSupplierDetails->pkp_file!='')) &&  $productExistRFQ != 'NOT' && Auth::user()->is_active == 1)
                    <a href="{{$xenAccountNotExist?'javascript:void(0)':route('rfq-reply',Crypt::encrypt($data->id))}}" class="btn btn-warning px-2 sendquote ms-auto"><img src="{{URL::asset('front-assets/images/icons/envelope1.png')}}" alt="Other Option" class="pe-2"> {{ __('admin.send_quote') }}</a>
                @else
                    @if($productExistRFQ == 'NOT')
                        <button class="btn btn-warning px-2 sendquotepkp ms-auto" disabled><img src="{{URL::asset('front-assets/images/icons/envelope1.png')}}" alt="Other Option" class="pe-2"> {{ __('admin.send_quote') }}</button>
                    @else
                        <button class="btn btn-warning px-2 sendquotepkp ms-auto" onclick="openPkpModel();"><img src="{{URL::asset('front-assets/images/icons/envelope1.png')}}" alt="Other Option" class="pe-2"> {{ __('admin.send_quote') }}</button>
                    @endif
                @endif
            @else
                @if(count($supplierDetail) > 0)
                <a href="{{$xenAccountNotExist?'javascript:void(0)':route('rfq-reply',Crypt::encrypt($data->id))}}" class="btn btn-warning px-2 sendquote ms-auto"><img src="{{URL::asset('front-assets/images/icons/envelope1.png')}}" alt="Other Option" class="pe-2"> {{ __('admin.send_quote') }}</a>
                @endif
            @endif

        @endif
    @else
        <button type="button" class="btn btn-success">{{ __('admin.call') }}</button>
    @endif
    <a class="btn btn-cancel" data-bs-dismiss="modal">{{ __('admin.cancel') }}</a>
    {!! $xenAccountNotExist?'</div>':'' !!}
</div>
<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip({
            trigger : 'hover'
        });
        $(document).on('click', '.cancelRfq', function () {
                var rfqId = $(this).attr('data-rfq_id');
                swal({
                    text: "{{  __('admin.cancel_rfq') }}",
                    icon: "/assets/images/warn.png",
                    buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                url: "{{ route('rfq-cancel-ajax') }}",
                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                data: {'rfqId':rfqId},
                                type: 'POST',
                                responseType: 'json',
                                success: function (successData) {
                                    resetToastPosition();
                                    $.toast({
                                        heading: "{{__('admin.success')}}",
                                        text: "{{__('admin.rfq_cancelled_successfully')}}",
                                        showHideTransition: "slide",
                                        icon: "success",
                                        loaderBg: "#f96868",
                                        position: "top-right",
                                    });
                                    setTimeout(function() {
                                        location.reload();
                                    }, 3000);
                                },
                                error: function () {
                                    console.log('error');
                                }
                            });

                        }
                    });
            });
    });
    //Download attachment document
    function downloadAttachment(rfq_id,fieldName, ref_no){
        event.preventDefault();
        var data = {
            rfq_id:rfq_id,
            fieldName: fieldName,
            ref_no: ref_no,
        }
        $.ajax({
            url: "{{ route('rfq-attachment-document-ajax') }}",
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
            url: "{{ route('download-rfq-attachment') }}",
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

    //Get preferred suppliers list for Individual RFQ (Ronak M - 28/06/2022)
    //$(document).on('click', '#preferredSuppliersCollapse', function(e) {
    $('#preferredSuppliersCollapse').click(function(e) {
        e.preventDefault();
        $("#flexCheckAllDefault").prop('checked',true);
        var rfqId = $(this).attr('data-rfq-id');
        if (rfqId) {
            $.ajax({
                url: "{{ route('get-preferred-suppliers-ajax') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "rfqId": rfqId
                },
                type: 'POST',
                dataType: "json",
                success: function(successData) {
                    $("#preferredSuppliersList").html(successData.preferredSupplierView);
                },
                error: function() {
                    console.log('error');
                }
            });
        }
    });

</script>
