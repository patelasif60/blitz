@extends('admin/adminLayout')

@section('content')
<style>
ul.searchResult, ul.descriptionSearchResult {
    list-style: none;
    margin: 0;
    padding: 0;
    width: auto;
    border: 1px solid silver;
    max-height: 150px ;
    overflow: auto;
    cursor: pointer;
}
ul.searchResult li, ul.descriptionSearchResult li {
    border-bottom: 1px solid #eaeaea;
    padding: 5px 0px 5px 10px;
    font-size: .95rem;
}
.suplier-succ{
    color:green;
    font-size:20px;
}
.newtable_v2 .fa-pencil{ background-color: #f6f6f6;  width: 20px; height: 20px; text-align: center; border-radius: 4px; line-height: 20px;}
.edit{
    --bs-table-accent-bg: var(--bs-table-hover-bg) !important;
    color: var(--bs-table-hover-color) !important;
}

</style>
<div class="col-12 grid-margin  h-100">

        <div class=" row">
            <div class="col-md-12 mb-3 d-flex align-items-center">
                <h1 class="mb-0 h3">{{$rfq->reference_number ?? ''}}</h1>
                @if(isset($rfq->group_id) && !empty($rfq->group_id))
                    <span>
                        <button type="button" class="btn btn-info btn-sm ms-2 text-white" style="border-radius: 20px">BGRP-{{ $rfq->group_id }}</button>
                    </span>
                    <a href="{{ route('group-rfq-list') }}" class="mb-2 backurl ms-auto btn-close"></a>
                @else
                    <a href="{{ route('rfq-list') }}" class="mb-2 backurl ms-auto btn-close"></a>
                @endif
            </div>
            <div class="col-12">
            <ul class="nav nav-tabs bg-white newversiontabs ps-3" role="tablist">
                <li class="nav-item ">
                    <a class="nav-link px-0 active" id="home-tab" data-bs-toggle="tab" href="#home-1" role="tab" aria-controls="home-1" aria-selected="false">{{ __('admin.edit') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-0 ms-5" data-rfqid="{{$rfq->id}}" id="profile-tab" data-bs-toggle="tab" href="#profile-1" role="tab" aria-controls="profile-1" aria-selected="false">{{ __('admin.activities') }}</a>
                </li>

            </ul>
            @if (Session::has('status'))

                <p class="alert suplier-succ"> {{ Session::get('status') }}</p>
            @endif
            <div class="tab-content pb-0 pt-3">
                <div class="tab-pane fade active show" id="home-1" role="tabpanel" aria-labelledby="home-tab">
                    <form class="" id="editRfqform" method="POST" enctype="multipart/form-data" action="{{ route('rfq-update') }}" data-parsley-validate id="editrfqForm">
                        @csrf
                        <input type="hidden" name="id" value="{{ $rfq->id }}">
                        <div class="row">
                            <div class=" col-md-12 mb-2">
                                <section id="contact_detail">
                                    <div class="card">
                                        <div class="card-header d-flex align-items-center">
                                            <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/comment-alt-edit.png')}}" alt="Charges" class="pe-2"> <span>{{ __('admin.contact_details') }}</span></h5>
                                        </div>
                                        <div class="card-body p-3 pb-1">

                                            <div class="row">
                                                <div class="col-md-6 mb-3">

                                                    <input type="hidden" id="target_quantity" value="{{$rfqGroup->target_quantity ?? null}}">
                                                    <input type="hidden" id="achieved_quantity" value="{{$rfqGroup->achieved_quantity ?? null }}">

                                                    <input type="hidden" id="groupId" name="groupId" value="{{$rfq->group_id ?? null}}">
                                                    <label for="firstname" class="form-label">{{ __('admin.firstname') }}<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="firstname" name="firstname" required value="{{ $rfq->firstname }}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="lastname" class="form-label">{{ __('admin.lastname') }}<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="lastname" name="lastname" required value="{{ $rfq->lastname }}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="email" class="form-label">{{ __('admin.email') }}<span class="text-danger">*</span></label>
                                                    <input type="email" class="form-control" id="email" name="email" required value="{{ $rfq->email }}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="mobile" class="form-label">{{ __('admin.mobile') }} <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="mobile" name="mobile" required value="{{ $rfq->mobile }}">
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
                                            <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/boxes.png')}}" alt="Product Details" class="pe-2"> <span>{{ __('admin.product_details') }}</span></h5>
                                        </div>
                                        <div class="card-body p-3 pb-1">
                                            <div class="row mb-3 align-items-center">
                                                <div class="col-md-6">
                                                    <div class="search_rfq_edit position-relative">
                                                        <label class="form-label" for="Search_category">{{__('admin.search_product')}}</label>
                                                        <input type="text"   name="tags" id="tags"class="form-control categorysearch" id="" type="search" placeholder="{{__('admin.search_product')}}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div> <small class="count fw-bold text-muted mt-3" style="display: none"></small></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="d-none" id="edit_id" data-id=""></div>

                                                <div class="col-md-6 mb-3" id="product_category_block">
                                                    <label class="form-label" for="product_category">{{ __('admin.product_category_text') }}<span class="text-danger">*</span></label>
                                                    <select id='product_category' name="product_category" onchange="changeCategory(this)" data-id=""  class="form-select" required>
                                                        @php $other = 1; @endphp
                                                        <option selected disabled>{{ __('dashboard.select_product_category') }}</option>
                                                        @foreach ($category as $item)
                                                            <option data-category-id="{{$item->id}}" value="{{ $item->name }}" {{ $item->name == $rfqProduct[0]->category ? 'selected' : '' }}>{{ $item->name }}</option>
                                                            @if($item->name == $rfqProduct[0]->category)
                                                                @php $other = 0; @endphp
                                                            @endif
                                                        @endforeach
                                                        @if($other == 1)
                                                            <option data-category-id="0" value="Other" selected>Other</option>
                                                        @else
                                                            <option data-category-id="0" value="Other" >Other</option>
                                                        @endif

                                                    </select>
                                                    <input type="hidden" class="form-control" id="category_id" name="category_id" value="">
                                                    <div class="d-none" id="show_change_cat_validation" data-value="true"></div>
                                                </div>
                                               <!--- <div class="col-md-6 mb-3 d-none position-relative" id="productCategoryOtherDiv">
                                                    <label>{{ __('admin.other_product_category') }}</label>
                                                    <input type="text" class="form-control" id="othercategory" name="othercategory">
                                                </div>-->
                                                <div class="col-md-6 mb-3">
                                                    <label for="product_sub_category" class="form-label">{{ __('admin.product_sub_category') }}<span class="text-danger">*</span></label>
                                                    <select id="product_sub_category" onchange="changeSubCategory(this)" name="product_sub_category" class="form-select">
                                                        <option selected disabled>{{ __('dashboard.Select Product Sub Category') }}</option>
                                                    </select>
                                                    <input type="hidden" class="form-control" id="product_sub_category_id" name="product_sub_category_id">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label" for="product_name">{{ __('admin.product_name') }}<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="product_name" name="product_name" onkeyup="searchProductFullForm(this.value)" onclick="searchProductFullForm(this.value)" required>
                                                    <input type="hidden" class="form-control" id="product_id" name="product_id">
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="quantity" class="form-label ">{{ __('admin.quantity') }}<span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" min="1" id="quantity" name="quantity" required >
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="unit" class="form-label">{{ __('admin.unit') }}<span class="text-danger">*</span></label>
                                                    <select class="form-select" id="unit" name="unit" required>
                                                         <option selected disabled>Select Unit</option>
                                                        @foreach ($units as $unit)
                                                            <option value="{{ $unit->id }}" >{{ $unit->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label" for="product_description">{{ __('admin.product_description') }}<span class="text-danger">*</span></label>
                                                    <textarea class="form-control" placeholder="@if(in_array($rfqProduct[0]->category_id,\App\Models\Category::SERVICES_CATEGORY_IDS)){{ __('dashboard.Service_Product_Description_Placeholder') }} @else{{ __('dashboard.Product_Description_Placeholder') }}@endif" rows="4" cols="50" name="product_description" id="product_description" required></textarea>
                                                </div>

                                                <div class="col-md-12 newtable_v2 mb-3">
                                                    <div class="py-2 text-end  mb-3">
                                                        <button type="button" onclick="AddProduct()" id="add_product_btn" class="btn btn-success btn-sm"><span id="add_edit_name_change">{{ __('admin.add') }}</span></button>
                                                        <button type="button" onclick="CancelsProduct()" id="cancel_product_btn" class="btn btn-cancel btn-sm"><span>{{ __("admin.cancel") }}</span></button>
                                                    </div>
                                                    <div class="text-center text-danger" id="five_ptoduct_validation_msg"></div>
                                                    <div class="div" style="max-height: 300px; overflow-y: scroll;">
                                                        <table id="rfq_table" class="table table-hover border">
                                                            <thead style="position: sticky; top: -2px;">
                                                            <tr>
                                                                <th>{{ __('admin.product_name') }}</th>
                                                                <th>{{ __('admin.description') }}</th>
                                                                <th>{{ __('admin.sub_category') }}</th>
                                                                <th>{{ __('admin.quantity') }}</th>
                                                                <th></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @if(!empty($productRfq))
                                                                @foreach($productRfq as $product_rfq)
                                                                    <tr id="edit_{{ $product_rfq->id }}">
                                                                        <td>{{ $product_rfq->product_name }}</td>
                                                                        <td>{{ $product_rfq->product_description }}</td>
                                                                        <td>{{ $product_rfq->product_sub_category }}</td>
                                                                        <td>{{$product_rfq->quantity .' '. $product_rfq->unit_name }}</td>
                                                                        <td>
                                                                            <a href="javascript:void(0);" class="p-1 mx-1 text-primary" onclick="editProduct('{{ $product_rfq->id }}')"><i class="fa fa-pencil"></i></a>
                                                                            <a href="javascript:void(0);" class="p-1 mx-1 text-primary deleteProduct" id="deleteProduct_{{ $product_rfq->id }}" onclick="deleteProduct('{{ $product_rfq->id }}')"><i class="fa fa-trash"></i></a>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @else
                                                                <tr id="no_data">
                                                                    <td>No Product Added</td>
                                                                </tr>
                                                            @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">{{ __('admin.comment') }}</label>
                                                    <textarea class="form-control" placeholder="Comment" rows="4" cols="50" name="comment" id="comment">{{ $rfqProduct[0]->comment }}</textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3 mb-3">
                                                    <label for="" class="form-label">{{ __('rfqs.upload_attachment') }}</label>
                                                    <div class="d-flex py-2">
                                                        <span class="">
                                                            <input type="file" name="attached_document[]" class="form-control" id="attached_document" accept=".jpg,.png,.jpeg,.pdf" onchange="showFile(this)" hidden="" multiple>
                                                            <label id="upload_btn" for="attached_document">{{ __('profile.browse') }}</label>
                                                        </span>
                                                        <div id="file-attached_document" class="d-flex align-items-center">
                                                            <input type="hidden" class="form-control" id="old_attachment_file" name="old_attachment_file" value="{{ Str::substr($rfq->attached_document,17) }}">
                                                            @if(isset($rfq_attachments) && count($rfq_attachments)>=1)
                                                                @php
                                                                    $rfq_file_name = '';
                                                                    if(count($rfq_attachments)>1){
                                                                        $rfq_file_name = count($rfq_attachments).' Files';
                                                                        $downloadAttachment = "downloadAttachment(".$rfq->id.", 'attached_document','".$rfq->reference_number."')";
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
                                                                <input type="hidden" class="form-control" id="oldrfq_file" name="oldrfq_file" value="{{ $rfq_file_name }}">
                                                                <span class="ms-2" id="RfqAfterRemove">
                                                                    <a href="javascript:void(0);" id="RfqFileDownload" onclick="{{$downloadAttachment}}" title="{{ $rfq_file_name }}" style="text-decoration: none;"> {{ $rfq_file_name }}</a>
                                                                </span>
                                                                <span style="@if(count($rfq_attachments)>1) display:block @else display:none @endif" class="btnlistuploaded">
                                                                    <a href="javascript:void(0);" title="{{ __('rfqs.upload_attachment') }}">
                                                                        <img src="{{URL::asset('assets/icons/uploadlist.png')}}" alt="CLose button" class="ms-2">
                                                                    </a>
                                                                <!-- hide section -->
                                                                    <div class="listofuploadedfiles p-1">
                                                                        <!-- repeat div section -->
                                                                        @php
                                                                            foreach($rfq_attachments as $rfqAttach){
                                                                               $rfqFileTitle = Str::substr($rfqAttach->attached_document,44);
                                                                               $extension_rfq_file = getFileExtension($rfqFileTitle);
                                                                               $rfq_file_filename = getFileName($rfqFileTitle);
                                                                               if(strlen($rfq_file_filename) > 10){
                                                                                  $rfq_file_name = substr($rfq_file_filename,0,10).'...'.$extension_rfq_file;
                                                                               } else {
                                                                                  $rfq_file_name = $rfq_file_filename.$extension_rfq_file;
                                                                               }
                                                                        @endphp
                                                                            <div class="d-flex align-items-center border-bottom pb-1 mb-1" id="RFQAttachment{{$rfqAttach->id}}">
                                                                                <span class="ms-2  flex-grow-1">
                                                                                    <a href="javascript:void(0);" id="RfqFileDownload" onclick="downloadimg('{{ $rfqAttach->id }}', 'attached_document', '{{ $rfq_file_name }}')" title="{{ $rfqFileTitle }}" style="text-decoration: none;"> {{ $rfq_file_name }}</a>
                                                                                </span>
                                                                                <span class="removeRFQFile" id="attached_document" data-id="{{ $rfqAttach->id }}" data-rfq_id="{{ $rfqAttach->rfq_id }}" data-reference="{{$rfq->reference_number}}" data-name="attached_document">
                                                                                    <a href="javascript:void(0);" title="{{ __('profile.remove_file') }}"> <img src="{{URL::asset('assets/icons/times-circle copy.png')}}" alt="CLose button" class="ms-2"></a>
                                                                                </span>
                                                                                <span class="ms-2">
                                                                                    <a class="rfq_file" href="javascript:void(0);" title="{{ __('profile.download_file') }}" onclick="downloadimg('{{ $rfqAttach->id }}', 'attached_document', '{{ $rfq_file_name }}')"  style="text-decoration: none;"><img src="{{URL::asset('front-assets/images/icons/icon_download.png')}}" width="14px"></a>
                                                                                </span>
                                                                            </div>
                                                                        @php
                                                                            }
                                                                        @endphp
                                                                    <!-- repeat div section -->
                                                                    </div>
                                                                <!-- end hide section -->
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="" class="form-label">{{ __('admin.commercial_tc') }}</label>
                                                    <div class="d-flex py-2">
                                                        <span class="">
                                                            <input type="file" name="termsconditions_file" class="form-control" id="termsconditions_file" accept=".jpg,.png,.jpeg,.pdf" data-id="{{ $rfq->id }}" onchange="showFile(this)" hidden="">
                                                            <label id="upload_btn" for="termsconditions_file">{{ __('profile.browse') }}</label>
                                                        </span>
                                                            @if(isset($rfq->termsconditions_file) && !empty($rfq->termsconditions_file))
                                                                @php
                                                                    $termsconditionsFileTitle = Str::substr($rfq->termsconditions_file,stripos($rfq->termsconditions_file, "termsconditions_file_") + 21);
                                                                    $extension_termsconditions_file = getFileExtension($termsconditionsFileTitle);
                                                                    $termsconditions_file_filename = getFileName($termsconditionsFileTitle);
                                                                    if(strlen($termsconditions_file_filename) > 10){
                                                                        $termsconditions_file_name = substr($termsconditions_file_filename,0,10).'...'.$extension_termsconditions_file;
                                                                    } else {
                                                                        $termsconditions_file_name = $termsconditions_file_filename.$extension_termsconditions_file;
                                                                    }
                                                                @endphp
                                                                <input type="hidden" class="form-control" id="old_termsconditions_file" name="old_termsconditions_file" value="{{ $termsconditions_file_name }}">
                                                                <input type="hidden" class="form-control" id="oldtermsconditions_file" name="oldtermsconditions_file" value="{{ $rfq->termsconditions_file }}">
                                                                <div id="file-termsconditions_file" class="d-flex align-items-center">
                                                                    <span class="ms-2">
                                                                        <a href="javascript:void(0);" id="TermsconditionsFileDownload" onclick="downloadimg('{{ $rfq->id }}', 'termsconditions_file', '{{ $termsconditions_file_name }}')"  title="{{ $termsconditionsFileTitle }}" style="text-decoration: none;"> {{ $termsconditions_file_name }}</a>
                                                                    </span>
                                                                    <span class="ms-2">
                                                                        <a class="termsconditions_file" href="javascript:void(0);" title="{{ __('profile.download_file') }}" onclick="downloadimg('{{ $rfq->id }}', 'termsconditions_file', '{{ $termsconditions_file_name }}')"  style="text-decoration: none;"><img src="{{URL::asset('front-assets/images/icons/icon_download.png')}}" width="14px"></a>
                                                                    </span>
                                                                </div>
                                                            @else
                                                                <div id="file-termsconditions_file" class="d-flex align-items-center"></div>
                                                            @endif
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </section>
                            </div>
                       <div class="col-md-12 mb-2">
                        <section id="delivery_detail">
                        <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/truck.png')}}" alt="Charges" class="pe-2"> <span id="rfq_address">@if(in_array($rfqProduct[0]->category_id,\App\Models\Category::SERVICES_CATEGORY_IDS)) {{ __('dashboard.pickup_details') }} @else {{ __('admin.delivery_detail') }} @endif</span></h5>
                                </div>
                                <div class="card-body p-3 pb-1">
                                <div class="row ">

									<div class="col-md-6" id="address_block">
										<label class="form-label">{{ __('rfqs.select_address') }}<span class="text-danger">*</span></label>
										<select class="form-select" id="useraddress_id" name="useraddress_id" required>
											<option disabled selected>{{ __('rfqs.select_delivery_address') }}</option>
											@foreach ($userAddress as $item)
												<option data-address_name="{{$item->address_name}}" data-address_line_2="{{$item->address_line_2}}" data-address_line_1="{{$item->address_line_1}}" data-sub_district="{{$item->sub_district}}" data-district="{{$item->district}}" data-city="{{$item->city}}" data-state="{{$item->state}}" data-state-id="{{$item->state_id ?? \App\Models\UserAddresse::OtherState}}" data-city-id="{{$item->city_id ?? \App\Models\UserAddresse::OtherCity}}" data-pincode="{{$item->pincode}}" value="{{ $item->id }}"
												{{ ($item->address_name==$rfq->address_name && $item->address_line_1==$rfq->address_line_1 && $item->address_line_2==$rfq->address_line_2) ? 'selected' : '' }}>
													{{ $item->address_name }}
												</option>
											@endforeach
											<option data-address-id="0" data-city-id="0" data-state-id="0" value="0">Other</option>
										</select>
									</div>

                                    <div class="col-md-6 mb-3">
                                        <label for="address_line_1" class="form-label">{{ __('rfqs.address_name') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="address_name" id="address_name" value="{{ $rfq->address_name }}" required>
                                    </div>
									<div class="col-md-6 mb-3">
                                        <label for="address_line_1" class="form-label">{{ __('admin.address_line') }} 1<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="address_line_1" id="address_line_1" value="{{ $rfq->address_line_1 }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="address_line_2" class="form-label">{{ __('admin.address_line') }} 2<span class="text-danger"></span></label>
                                        <input type="text" class="form-control" name="address_line_2" id="address_line_2" value="{{ $rfq->address_line_2 }}">
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="sub_district" class="form-label">{{ __('admin.sub_district') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="sub_district" id="sub_district" value="{{ $rfq->sub_district }}" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="district" class="form-label">{{ __('admin.district') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="district" id="district" value="{{ $rfq->district }}" required>
                                    </div>

                                    <div class="col-md-6 mb-3 select2-block" id="stateId_block">
                                        <label for="stateId" class="form-label">{{ __('admin.provinces') }}<span class="text-danger">*</span></label>
                                        <select class="form-select select2-custom" id="stateId" name="stateId" data-placeholder="{{ __('admin.select_province') }}" required data-parsley-errors-container="#user_provinces">
                                            <option value="" >{{ __('admin.select_province') }}</option>
                                            @foreach ($states as $state)
                                                <option value="{{ $state->id }}" @if($rfq->state_id == $state->id) selected @endif >{{ $state->name }}</option>
                                            @endforeach
                                            <option value="-1">Other</option>
                                        </select>
                                        <div id="user_provinces"></div>
                                    </div>

                                    <div class="col-md-3 mb-3 hide" id="state_block">
                                        <label for="state" class="form-label">{{ __('admin.other_provinces') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="state" id="state" value="{{ $rfq->state }}" required>
                                    </div>

                                    <div class="col-md-6 mb-3 select2-block" id="cityId_block">
                                        <label for="cityId" class="form-label">{{ __('admin.city') }}<span class="text-danger">*</span></label>
                                        <select class="form-select select2-custom" id="cityId" name="cityId" data-placeholder="{{ __('admin.select_city') }}" data-selected-city="{{ $rfq->city_id }}" required data-parsley-errors-container="#user_city">
                                            <option value="">{{ __('admin.select_city') }}</option>
                                            <option value="-1">Other</option>
                                        </select>
                                        <div id="user_city"></div>

                                    </div>

                                    <div class="col-md-3 mb-3 hide" id="city_block">
                                        <label for="city" class="form-label">{{ __('admin.other_city') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="city" id="city" value="{{ $rfq->city }}" >
                                    </div>

                                <div class="col-md-3 mb-3">
                                    <label for="pincode" class="form-label">{{ __('admin.delivery_pincode') }}<span class="text-danger">*</span></label>
                                    <!-- pattern="[1-9][0-9]{5}" -->
                                    <input type="text" pattern=".{5,}"  oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\.*?)\..*/g, '$1');"  class="form-control" id="pincode" name="pincode" required value="{{ $rfq->pincode }}" >
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="expected_date" class="form-label">{{ __('admin.expected_delivery_date') }}
                                    <span class="text-danger">*</span></label>
                                    <div id="date" class="input-group date datepicker">
                                        <input type="text" readonly id="expected_date" name="expected_date" class="form-control"
                                            style="border: 1px solid #dee2e6;background-color:white;" required value="{{ \Carbon\Carbon::parse($rfqProduct[0]->expected_date)->format('d-m-Y')}}">
                                        <span class="input-group-addon input-group-append border-left">
                                            <span class="mdi mdi-calendar input-group-text"
                                                style="border: 1px solid #dee2e6;"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="status_id" class="form-label ">{{ __('admin.status') }}</label>
                                    <input type="text" class="form-control" disabled id="status_id" value="{{$status_name!=''?$status_name:$rfq->rfqStatus->backofflice_name}}">
                                </div>
                                    <div class="col-md-9 mb-3 d-flex align-items-center">
                                        <div class="col-md-auto mt-3">
                                            <input type="checkbox" class="form-check-input" value="1" name="rental_forklift" id="rental_forklift" {{ $rfq->rental_forklift == 1 ? 'checked' : ''}}>
                                            <label class="form-check-label ps-1" for="need_rental_forklift">{{ __('admin.need_rental_forklift') }}</label>
                                        </div>
                                        <div class="col-md-auto ps-3 mt-3" >
                                            <input  type="checkbox" class="form-check-input" value="1" name="unloading_services" id="unloading_services" {{ $rfq->unloading_services == 1 ? 'checked' : ''}}>
                                            <label class="form-check-label ps-1" for="need_unloading_services">{{ __('admin.need_uploding_services') }}</label>
                                        </div>
                                    </div>
                            </div>
                            <div class="row d-none">
                                <div class="col-md-4 mb-3">
                                    <label for="payment_terms" class="form-label d-block">{{ __('admin.payment_terms') }}</label>
                                    @if($rfq->is_require_credit)
                                        <span class="badge badge-danger pull-left ">{{ __('admin.credit') }}</span>
                                    @else
                                        <span class="badge badge-success pull-left ">{{ __('admin.advance') }}</span>
                                    @endif
                                </div>
                            </div>
                                </div>
                        </div>
                        </section>
                        </div>
                        <div class="col-md-12 mb-2">
                        <section id="payment_details">
                        <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/credit-card.png')}}" alt="Payment Details" class="pe-2"> <span>{{ __('admin.payment_details') }}</span></h5>
                                </div>
                                <div class="card-body p-3 pb-1">
                                    <div class="row">
                                        <div class="col-md-3 ps-3" style="margin-top: 5px;">
                                            <div class="form-check form-switch mt-0">
                                                <input class="form-check-input" style="margin-left: 0em;" name="is_require_credit" value="1" type="checkbox" role="switch" id="creditSwitchCheckDefault" {{$rfq->is_require_credit == 1 ? 'checked' : ''}} >
                                                <label class="ms-3" for="creditSwitchCheckDefault">{{ __('admin.require_credit') }}</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-2 credit-type-div {{$rfq->is_require_credit == 1 ? '' : 'hide'}}">
                                            <label>{{ __('rfqs.credit_type') }}:</label>
                                            <select class="form-select form-select-sm p-2 credit_type" id="credit_days_id" name="credit_days_id">
                                                <option value="0">{{ __('admin.select_credit_type') }}</option>
                                                <option {{$rfq->payment_type == 3 ?'selected' :''}} value="lc">{{ __('admin.lcdropdwn') }}</option>
                                                <option {{$rfq->payment_type == 4 ?'selected' :''}} value="skbdn">{{ __('admin.skbdn') }}</option>
                                                    @foreach($creditDays as $i=>$creditDay)
                                                        @php
                                                            $credit_name = sprintf(__('rfqs.credit_type_name'),trim($creditDay->days));
                                                        @endphp
                                                            <option {{$rfq->credit_days == $creditDay->days?'selected' :''}} value="{{$creditDay->days}}">{{$credit_name}}</option>
                                                    @endforeach
                                            </select>
                                        </div>

                                    </div>
                                </section>
                            </div>
                            <div class="col-md-12 bg-white py-3 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary" id="submiteditrfq" >{{ __('admin.update') }}</button>
                                <a href="{{ (isset($rfq->group_id) && !empty($rfq->group_id)) ? route('group-rfq-list') : route('rfq-list') }}" style="float: right;" class=" ms-3 btn btn-cancel"> {{ __('admin.cancel') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Activites -->
                <div class="tab-pane fade activityopen" id="profile-1" role="tabpanel" aria-labelledby="profile-tab" data-rfqid="{{$rfq->id}}">
                </div>
            </div>
            <!-- </div> -->
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    var max_product = '{{ $max_product??5 }}';
    var max_attachments = '{{ $max_attachments??0 }}';
    var last_category = -1;
    $(function() {
        $('#profile-tab').click( function() {
            var rfqId = $(this).attr('data-rfqid');
            $.ajax({
                url: "{{ route('admin-get-rfq-activity-ajax', '') }}" + "/" + rfqId,
                type: 'GET',
                success: function(successData) {
                    if (successData.activityhtml) {
                        $('.activityopen').html(successData.activityhtml);
                    }
                },
                error: function() {
                    console.log('error');
                }
            });
        });
    });
    var change = false;
    $("#editRfqform").on('submit',function(event){
        // alert('hey');
        // alert($('#product_category').val());
        // return false;
        event.preventDefault();
        var product = JSON.parse(localStorage.getItem("product_edit")) || [];
        if(product.length != 0){
            removeOrAddValidation(false);
        }
        var formData = new FormData($("#editRfqform")[0]);

        formData.append("product_details", JSON.stringify(product));
        if ($('#editRfqform').parsley().validate()) {
            if($('#creditSwitchCheckDefault').prop('checked'))
            {
                if($('#credit_days_id').val() == 0){
                     swal({
                        title: "",
                        text: "{{ __('dashboard.credittype') }}",
                        icon: "/assets/images/warn.png",
                        buttons: '{{ __('admin.ok') }}',
                        dangerMode: true,
                    });
                    return false;
                }
            }
            /*
            if($('#groupId').val()){
                let rfq_qty = product[0].quantity;
                let achieved_qty = $('#achieved_quantity').val();
                let target_qty = $('#target_quantity').val();
                let totalQty = parseInt(rfq_qty) + parseInt(achieved_qty);
                let remainingQty = parseInt(target_qty) - parseInt(achieved_qty);
                if(totalQty > target_qty) {
                    let text = "{{__('admin.rfq_quantity_should_not_be_greater')}} " + remainingQty + ' ' + product[0].unit_name;
                    swal({
                        text: text,
                        icon: "warning",
                        // buttons: ["{{__('admin.no')}}", "{{__('admin.yes')}}"],
                        // buttons: ["{{__('admin.ok')}}"],
                        button: {
                            text: "{{__('admin.ok')}}"
                        }
                    });
                    return false;
                }

            }
            */
            // return;

            if(change == true){
                swal({
                    text: "{{ __('admin.update_rfq_product_related_details') }}",
                    buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
                })
                .then((willDelete) => {
                    if (willDelete) {
                        saveDataToDatabase(formData, $(this).attr('action'), $(this).attr('method'));
                    } else {
                        return false;
                    }
                });
            }else{
                saveDataToDatabase(formData, $(this).attr('action'), $(this).attr('method'));
            }
        }
    });

    function saveDataToDatabase(formData, action, type){
        $.ajax({
            url: action,
            type: type,
            data : formData,
            contentType: false,
            processData: false,
            success: function (r) {
                if(r.success == true){
                    resetToastPosition();
                    $.toast({
                        heading: "{{__('admin.success')}}",
                        text: "{{__('admin.rfq_updated_successfully')}}",
                        showHideTransition: "slide",
                        icon: "success",
                        loaderBg: "#f96868",
                        position: "top-right",
                    });
                    if(r.groupId){
                        window.location = '/admin/group-rfq';
                    }else{
                        window.location = '/admin/rfq';
                    }
                }
            },
            error: function (xhr) {
                alert('{{__('admin.error_while_selecting_list')}}');
            }
        });
    }

    $(document).ready(function() {
        localStorage.clear();
        var product = @json($productRfq);
        window.localStorage.setItem('product_edit', JSON.stringify(product));
        $('#product_category').select2({
            dropdownParent: $('#product_category_block'),
        });
        $('#product_category').on('select2:selecting', function (evt) {
            last_category = $('#product_category').val();
        });
        var date = new Date();
        date.setDate(date.getDate());
        $('#date').datepicker({
            startDate: date,
            format: 'dd-mm-yyyy'
        });
        $('#date').on('changeDate', function(ev) {
            $(this).datepicker('hide');
        });

        var categoryID = $('#product_category option:selected').attr('data-category-id');
        var onload = true;
        $('#category_id').val(categoryID);
        getSubCategory(categoryID,onload);
        if (product.length == max_product){
            disableAllFields(true);
        }
    });

    $(document).on('change','#useraddress_id',function(){
        let selected_option = $('option:selected', this);
        bindAddressFields(selected_option);
    });

    function bindAddressFields(selected_option){
        $("#address_name").val(selected_option.attr('data-address_name'));
        $("#address_line_1").val(selected_option.attr('data-address_line_1'));
        $("#address_line_2").val(selected_option.attr('data-address_line_2'));
        $("#sub_district").val(selected_option.attr('data-sub_district'));
        $("#district").val(selected_option.attr('data-district'));
        $("#city").val(selected_option.attr('data-city'));
        $("#state").val(selected_option.attr('data-state'));
        $("#pincode").val(selected_option.attr('data-pincode'));

        $("#stateId").val(selected_option.attr('data-state-id')).trigger('change');

    }

    function changeCategory(data){
        var cat_id = $(data).find(':selected').attr('data-category-id');
        var cat_name = $(data).find(':selected').val();
        var product = JSON.parse(localStorage.getItem("product_edit")) || [];

        const servicesCategoryIds = {{ json_encode(\App\Models\Category::SERVICES_CATEGORY_IDS) }};
        let categoryIsService = false;

        for (i=0;i<servicesCategoryIds.length;i++){
            if(servicesCategoryIds[i] == cat_id){
                categoryIsService = true;
            }
        }

        if (product.length == 0) {
            if (cat_id == 0) {
                $('#category_id').val(cat_id);
                $('#product_name').val(null);
                $('#productSearchResult').remove();
                $('#productCategoryOtherDiv').removeClass('d-none');
                $('#productSubCategoryOtherDiv').removeClass('d-none');
                $('#cloneProduct').html('');
                $('#other_category').val('');
                $('#product_name_change').attr('id', 'product_name_change').html('Product');
                var options = '<option selected disabled>Select Product Sub Category</option>';
                options += '<option  data-sub-category-id="0" value="Other">Other</option>';
                $('#product_sub_category').empty().append(options);
                $("#product_sub_category").val(0);
            } else {
                $('#category_id').val(cat_id);
                $('#productCategoryOtherDiv').addClass('d-none');
                $('#productSubCategoryOtherDiv').addClass('d-none');
                $('#product_name_change').html('Product');
                $('#product_name').attr('id', 'product_name').val('');
                $('#product_sub_category').attr('id', 'product_sub_category');
                $('#quantity').attr('id', 'quantity').val('');
                $("#unit").val($("#unit option:first").val());
                $('#product_description').attr('id', 'product_description').val('');
                $('#cloneProduct').html('')
                $.ajax({
                    url: "{{ route('get-subcategory-ajax', '') }}" + "/" + cat_id,
                    type: 'GET',
                    success: function (successData) {
                        var options = '<option selected disabled>Select Product Sub Category</option>';
                        if (successData.subCategory.length) {
                            successData.subCategory.forEach(function (data) {
                                options += '<option data-sub-category-id="' + data.id + '" value="' + data.name + '" data-text="' + data.name + '">' + data.name + '</option>';
                            });
                        }
                        options += '<option data-sub-category-id="0" value="Other">Other</option>';

                        $('#product_sub_category').empty().append(options);
                    },
                    error: function () {
                        console.log('error');
                    }
                });
            }
        } else {
            var validates = $('#show_change_cat_validation').attr('data-value');
            if(validates === 'true'){
                if($('#product_category').val() != last_category) {
                    alertMessage('{{ __("admin.alert_change_category") }}', true, 'change_cat', cat_id)
                    resetData();
                    $('#category_id').val(cat_id);
                }
            }
        }
        if (categoryIsService) {
            $('#rfq_address').text('{{ __('dashboard.pickup_details') }}');
            $('#product_description').attr('placeholder', '{{ __('dashboard.Service_Product_Description_Placeholder') }}');
        } else {
            $('#rfq_address').text('{{ __('dashboard.Delivery_Details') }}');
            $('#product_description').attr('placeholder', '{{ __('dashboard.Product_Description_Placeholder') }}');
        }
    }
    function getSubCategory(categoryID,onload){
        var rfqdata = @json($rfqProduct);

        cat_ajax(categoryID)

    }

    function cat_ajax(categoryID,sub_cat_id=null){
        $.ajax({
            url: '/getSub/'+categoryID,
            type: "GET",
            data : {"_token":"{{ csrf_token() }}"},
            dataType: "json",
            success:function(data)
            {
                if(data){

                if(sub_cat_id!=null || sub_cat_id!=0)
                {
                    var select='selected="selected"';
                }
                    $('#product_sub_category').empty();
                    var options = '<option selected disabled>Select Product Sub Category</option>';
                    if (data.length) {

                        var other  = 1;
                        data.forEach(function(data) {
                        if(data.id==sub_cat_id){
                            var selected=select;
                        }
                        else{
                            selected='';
                        }
                            options += '<option data-sub-category-id="' + data.id + '" value="' + data.name + '" data-text="' + data.name + '"'+selected+'>' + data.name + '</option>';
                        });
                    }
                    var selectOther='';
                if(sub_cat_id=='0')
                {
                    selectOther='selected="selected"';
                }
                    if(other == 1){
                        options += '<option data-sub-category-id="0" value="Other">Other</option>';
                    }else{
                        options += '<option data-sub-category-id="0" value="Other"'+selectOther+'>Other</option>';
                    }
                    $('#product_sub_category').empty().append(options);
                }else{
                    $('#product_sub_category').empty();
                }
            }
        });
    }

    $(document).on('mousedown', '.searchProduct', function(e) {
        $('#product_name').val($(this).attr('data-value'));
        $('#product_name').attr('data-id', $(this).attr('data-id'));
        $('#product_id').val($(this).attr('data-id'))
        $('#productSearchResult').addClass('d-none');
        if ($(this).attr('data-sub-cat-id')) {

            var selectedSubCatValue = $('#product_sub_category').find('option[data-sub_category_id="' + $(this).attr('data-sub-cat-id') + '"]').val();
            //$('#product_sub_category').val(selectedSubCatValue).trigger('change');
            // $('#editrfqForm #othersubcategory').val('');
        }
    });

    function searchProductPost(text) {
        var text = text.trim();
        var CategoryId = $('#category option:selected').attr('data-category-id');
        var subcategoryId = $("#product_sub_category option:selected").attr('data-sub_category_id');

        var data = {
            product: text,
            subCategoryId: subcategoryId,
            categoryId: CategoryId,
            _token: "{{ csrf_token() }}"
        }
        if (CategoryId && CategoryId != 0) {
            $.ajax({
                url: '{{ route('admin-search-product-ajax') }}',
                data: data,
                type: 'POST',
                success: function(successData) {
                    $('#productSearchResult').remove();
                    var searchData = '<ul id="productSearchResult" class="searchResult">';
                    var dataArray = [];
                    if (successData.filterData.length) {
                        successData.filterData.forEach(function(data) {
                            if (!dataArray.includes(data.name)) {
                                dataArray.push(data.name);
                                searchData += '<li data-sub-cat-id="' + data.subcategory_id +
                                    '" data-id="' + data.id + '" data-value="' +
                                    data.name + '" class="searchProduct">' +
                                    data.name + '</li>';
                            }
                        });
                    }
                    searchData += '</ul>'
                    $('#product_name').after(searchData);

                },
            });
        }

    }
    /** multiple attachments
     *   Vrutika - 28/07/2022
     */
    //Attachment Document
    function showFile(input) {
        var rdt = cdt = new DataTransfer();
        var fileName = '';
        var oldFilename = $('#old_termsconditions_file').val();
        var existingFile = {{count($rfq_attachments)}};
        var totalFiles = parseInt(input.files.length) + parseInt(existingFile);
        if(input.id == 'attached_document' && totalFiles > max_attachments){
            swal({
                icon: 'error',
                title: '',
                text: '{{ sprintf(__('admin.multiple_attachment_add'),$max_attachments??0) }}'
            })

        }else{
            var checkFileSizeCount = 0;
            let files = input.files;
            let allowed_extensions = new Array("jpg", "png", "jpeg", "pdf");
            let text = '{{ __('profile.plz_upload_file') }}';
            let image_path = '{{URL::asset("assets/icons/times-circle copy.png")}}';
            for (var j = 0; j < files.length; j++) {
                fileName = files[j].name;
                let file_extension = fileName.split('.').pop();
                let file_name_without_extension = fileName.replace(/\.[^/.]+$/, '');
                var fileSize = Math.round((files[j].size / 1024));
                if (fileSize <= 3000) {
                    for (let i = 0; i < allowed_extensions.length; i++) {
                        if (allowed_extensions[i] == file_extension) {
                            rdt.items.add(files[j]);
                            var download_function = "'" + input.name + "', " + "'" + fileName + "'";
                            if (file_name_without_extension.length >= 10) {
                                fileName = file_name_without_extension.substring(0, 10) + '....' + file_extension;
                            }
                            if (rdt.files.length > 1) {
                                fileName = rdt.files.length + ' Files';
                            }
                        }
                    }
                } else {
                    checkFileSizeCount = checkFileSizeCount + 1;
                }
            }

            $('#file-' + input.id).html('');
            $('#file-' + input.id).append('<span class="ms-2"><a href="javascript:void(0);" id="' + input.id + 'Download " style="text-decoration: none">' + fileName + '</a></span><span class="ms-2"><a class="' + input.id + ' downloadbtn hidden" href="javascript:void(0);" title="{{ __('profile.download_file') }}" onclick="downloadimg(' + download_function + ')" style="text-decoration: none;"><img src="{{URL::asset('front-assets/images/icons/icon_download.png')}}" width="14px"></a></span>');

            if (checkFileSizeCount != 0){
                swal({
                    text: checkFileSizeCount+"File you tried adding is larger then the 3MB",
                    icon: "/assets/images/info.png",
                    buttons: 'ok',
                    dangerMode: true,
                }).then((changeit) => {
                    if(input.id == 'termsconditions_file'){
                        var rfq_id = $('#'+input.id).data('id');
                        var download_function = "'" + rfq_id + "', " +"'" + input.name + "', " + "'" + oldFilename + "'";
                        $('#file-' + input.id).html('');
                        $('#file-' + input.id).append('<span class="ms-2"><a href="javascript:void(0);" id="' + input.id + 'Download " style="text-decoration: none" onclick="downloadimg(' + download_function + ')">' + oldFilename + '</a></span><span class="ms-2"><a class="' + input.id + ' downloadbtn" href="javascript:void(0);" title="{{ __('profile.download_file') }}" onclick="downloadimg(' + download_function + ')" style="text-decoration: none;"><img src="{{URL::asset('front-assets/images/icons/icon_download.png')}}" width="14px"></a></span>');
                    } else {
                        $('#rfq_attachment_doc')[0].files = rdt.files;
                    }

                    checkFileSizeCount = 0;
                });
            }
        }

    }

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

    //Delete RFQ attachment file
    $(document).on('click', '.removeRFQFile', function (e) {
        e.preventDefault();
        var element = $(this);
        var id = $(this).attr("data-id");
        var dataName = $(this).attr("data-name");
        var rfqId = $(this).attr("data-rfq_id");
        var referenceId = $(this).attr("data-reference");
        var data = {
            id: id,
            rfqId:rfqId,
            _token: "{{ csrf_token() }}"
        };
        swal({
            title: "{{ __('admin.delete_sure_alert') }}",
            icon: "warning",
            buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
            dangerMode: false,
        }).then((changeit) => {
            if (changeit) {
                $.ajax({
                    url: "{{ route('rfq-document-delete-ajax') }}",
                    data: data,
                    type: "POST",
                    success: function (successData) {
                        $('#RfqFileDownload').html(successData.rfqAttachments);
                        if(successData.countAttachments == 1){
                            $('.btnlistuploaded').hide();
                            let download_function = "" + successData.rfqAttachmentsId + ", " + "'" + dataName + "', " + "'" + successData.rfqAttachments + "'";
                            $('#RfqAfterRemove').html('<a href="javascript:void(0);" id="RfqFileDownload" onclick="downloadimg(' + download_function +')" title="'+ successData.rfqAttachments +'" style="text-decoration: none;"> '+ successData.rfqAttachments +'</a>');
                        }else{
                            let download_function = "'" + rfqId + "', " + "'" + dataName + "', " + "'" + referenceId + "'";
                            $('#RfqAfterRemove').html('<a href="javascript:void(0);" id="RfqFileDownload" onclick="downloadAttachment(' + download_function +')" title="'+ successData.countAttachments +' Files" style="text-decoration: none;"> '+ successData.countAttachments +' Files</a>');
                        }
                        $('#RFQAttachment'+id).remove();
                        $.toast({
                            heading: '{{ __('admin.success') }}',
                            text: '{{ __('admin.document_removed_successfully') }}',
                            showHideTransition: 'slide',
                            icon: 'success',
                            loaderBg: '#f96868',
                            position: 'top-right'
                        })
                    },
                    error: function () {
                        console.log("error");
                        $.toast({
                            heading: '{{ __('admin.success') }}',
                            text: '{{ __('admin.error_while_removing_document') }}',
                            showHideTransition: 'slide',
                            icon: 'success',
                            loaderBg: '#f96868',
                            position: 'top-right'
                        })

                    },
                });
            }
        });
    });
    //------- End --------//
    function changeSubCategory(data) {
        var text = data.id;
        $('#product_sub_category_id').val($('#product_sub_category option:selected').attr('data-sub-category-id'));
        $('#product_name').val(null);
        $('#product_id').val('');
        $('#productSearchResult').remove();
    }

    function searchProductFullForm(text) {
        var text = text.trim();
        var subCategoryId = $('#product_sub_category_id').val();
        var categoryId = $("#product_category").find("option:selected").attr('data-category-id');
        var data = {
            product: text,
            subCategoryId: subCategoryId,
            categoryId: categoryId,
            _token: '{{ csrf_token() }}'
        }
        if (categoryId && categoryId != 0) {
            $.ajax({
                url: '{{ route('search-product-ajax') }}',
                data: data,
                type: 'POST',
                success: function(successData) {
                    $('#productSearchResult').remove();
                    var searchData = '<ul id="productSearchResult" class="searchResult">';
                    var dataArray = [];
                    if (successData.filterData.length) {
                        successData.filterData.forEach(function(data) {
                            if (!dataArray.includes(data.name)) {
                                dataArray.push(data.name);
                                searchData += '<li data-sub-cat-id="' + data.subcategory_id + '" data-id="' + data.id + '" data-value="' + data.name + '" class="searchProduct">' + data.name + '</li>';
                            }
                        });
                    }
                    searchData += '</ul>'
                    $('#product_name').after(searchData);

                    var dbProduct = [];
                    $('#productSearchResult li').each(function() {
                        dbProduct.push($(this).text().toLowerCase());
                    });
                },
            });
        }

    }

    function productSearch(data){
        var data_id = data.id;
        var id = data_id.replace('product_name', '');
        if(id != undefined && id != 0){
            $('#productSearchResult'+id).removeClass('hidden');
        } else {
            $('#productSearchResult').removeClass('hidden');
        }
    }

    function alertMessage(text, checkFromCategory, key, cat_id) {
        var button = "{{ __('admin.ok') }}"
        if(key == 'change_cat'){
            button = ["{{ __('admin.cancel') }}", "{{ __('admin.ok') }}"]
        }
        swal({
            title: "",
            text: text,
            icon: "/assets/images/warn.png",
            buttons: button,
        }).then((changeit) => {
            if (changeit==true) {
                if (checkFromCategory==true) {
                    removeOrAddValidation(true);
                    if (key == 'change_cat'){
                        resetData();
                        cat_ajax(cat_id)
                    }
                    localStorage.clear();
                    $('#rfq_table tbody').html('');
                    $('#rfq_table > tbody:last-child').append(
                        '<tr id="no_data">'
                        +'<td>{{ __("admin.no_product_added") }}</td>'
                        +'</tr>');
                }
            } else {
                if (key == 'change_cat') {
                    last_category = $('#product_category').val();
                    $('#product_category').val(last_category).trigger("change");
                }
            }
        });
    }

    function AddProduct(){

        $("#editRfqform #searchProductCategory").val('');
        $('#searchGroup').html('');
        var edit_id = $('#edit_id').attr('data-id');
        var product = JSON.parse(localStorage.getItem("product_edit")) || [];
        if($('#product_sub_category').val() != null && $('#product_name').val() != '' && $('#quantity').val() != '' && $('#quantity').val() > 0 && $('#unit').val() != null && $('#product_description').val() != '' && product.length != max_product) {
            if(edit_id == ''){
                localstoreProduct();
            } else {
                upadteProduct(edit_id);
            }
        } else if(edit_id != '' && product.length == max_product){
            upadteProduct(edit_id);
        } else {
            if($('#product_sub_category').val() == null && $('#product_category').val() == null){
                alertMessage('{{ __("admin.alert_product_cat_sub_cat") }}');
            } else if($('#product_sub_category').val() == null) {
                alertMessage('{{ __("admin.alert_product_sub_cat") }}');
            } else if($('#product_name').val() == '' || $('#quantity').val() == '' || $('#unit').val() == '' || $('#product_description').val() == '' || $('#quantity').val() <= 0){
                if ($('#product_name').val() == ''){ alertMessage('{{ __("admin.alert_product_name_error") }}');
                } else if ($('#quantity').val() == ''){ alertMessage('{{ __("admin.alert_product_qty") }}');
                } else if ($('#quantity').val() <= 0){ alertMessage('{{ __("admin.alert_product_unit_zero") }}');
                } else if ($('#unit').val() == null){ alertMessage('{{ __("admin.alert_product_unit") }}');
                } else if ($('#product_description').val() == ''){ alertMessage('{{ __("admin.alert_product_description") }}'); }
            } else if(product.length == max_product){
                alertMessage('{{ sprintf(__('admin.multiple_product_add'),$max_product??5) }}')
            }
        }
    }

    function localstoreProduct() {
        var product = JSON.parse(localStorage.getItem("product_edit")) || [];
        var last_id = 0;
        if(product.length == 0){
            last_id = 1;
        } else {
            last_id = parseInt(product.slice(-1)[0]['id']) + 1;
        }
        product.push({
            id: last_id,
            product_sub_category: $('#product_sub_category').val(),
            unit: $('#unit').val(),
            product_name: $('#product_name').val(),
            quantity: $('#quantity').val(),
            product_description: $('#product_description').val(),
            product_sub_category_id: $('#product_sub_category_id').val(),
            product_id: $('#product_id').val(),
            custom_add: 1,
        });

        window.localStorage.setItem('product_edit', JSON.stringify(product));
        $('#no_data').remove();
        //remove required validation
        removeOrAddValidation(false);
        var buttons = '<a href="javascript:void(0);" class="p-1 mx-1 text-primary" onclick="editProduct('+last_id+')"><i class="fa fa-pencil"></i></a><a href="javascript:void(0);" class="p-1 mx-1 text-primary deleteProduct" id="deleteProduct_'+last_id+'" onclick="deleteProduct('+last_id+')"><i class="fa fa-trash"></i></a>';
        var edit_id = 'edit_'+last_id;
        $('#rfq_table > tbody:last-child').append(
            '<tr id="'+edit_id+'">'
            +'<td>'+$("#product_name").val()+'</td>'
            +'<td>'+$("#product_description").val()+'</td>'
            +'<td>'+$("#product_sub_category").val()+'</td>'
            +'<td>'+$('#quantity').val()+' '+ $('#unit').find(":selected").text() +'</td>'
            +'<td class="text-nowrap">'+buttons+'</td>'
            +'</tr>');

        resetData();
        if (product.length == max_product){
            disableAllFields(true);
        }
    }

    function disableAllFields(val) {
        if(val){
            $('#five_ptoduct_validation_msg').html('<small>{{ sprintf(__('admin.multiple_product_add'),$max_product??5)}}</small>');
        } else {
            $('#five_ptoduct_validation_msg').html('');
        }
        $("#product_sub_category").attr("disabled", val);
        $("#product_name").attr("disabled", val);
        $("#quantity").attr("disabled", val);
        $("#unit").attr("disabled", val);
        $("#product_description").attr("disabled", val);
        $("#add_product_btn").attr("disabled", val);
        $("#cancel_product_btn").attr("disabled", val);

    }

    function upadteProduct(id) {
        var product = JSON.parse(localStorage.getItem("product_edit")) || [];
        product[id]['category'] = $('#product_category').val();
        product[id]['category_id'] = $('#category_id').val();
        product[id]['product_sub_category'] = $('#product_sub_category').val();
        product[id]['unit'] = $('#unit').val();
        product[id]['product_name'] = $('#product_name').val();
        product[id]['quantity'] = $('#quantity').val();
        product[id]['product_description'] = $('#product_description').val();
        product[id]['product_sub_category_id'] = $('#product_sub_category_id').val();
        product[id]['product_id'] = $('#product_id').val();
        window.localStorage.setItem('product_edit', JSON.stringify(product));
        var data_id = product[id]['id'];
        var dispaly_edit = 'edit_'+data_id;

        //var buttons = '<a href="javascript:void(0);" class="p-1 mx-1 text-primary" onclick="editProduct('+data_id+')"><i class="fa fa-pencil"></i></a>';
        var buttons = '<a href="javascript:void(0);" class="p-1 mx-1 text-primary" onclick="editProduct('+data_id+')"><i class="fa fa-pencil"></i></a><a href="javascript:void(0);" class="p-1 mx-1 text-primary deleteProduct" id="deleteProduct_'+data_id+'" onclick="deleteProduct('+data_id+')"><i class="fa fa-trash"></i></a>';
        $('#'+dispaly_edit).html('');
        $('#'+dispaly_edit).append('<td>'+$("#product_name").val()+'</td>'
            +'<td>'+$("#product_description").val()+'</td>'
            +'<td>'+$("#product_sub_category").val()+'</td>'
            +'<td>'+$('#quantity').val()+' '+ $('#unit').find(":selected").text() +'</td>'
            +'<td class="text-nowrap">'+buttons+'</td>');
        change = true;
        resetData();
        if (product.length == max_product){
            disableAllFields(true);
        }
    }

    function CancelsProduct(){
        if($('#product_sub_category').val() != null || $('#product_name').val() != '' || $('#quantity').val() != '' || $('#unit').val() != null || $('#product_description').val() != '') {
            swal({
                title: "{{ __('dashboard.are_you_sure') }}?",
                text: "{{ __('admin.reset_data_cancel') }}",
                icon: "/assets/images/bin.png",
                buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
                dangerMode: true,
            }).then((willChange) => {
                if (willChange) {
                    resetData();
                    var product = JSON.parse(localStorage.getItem("product_edit")) || [];
                    if (product.length == max_product){
                        disableAllFields(true);
                    }
                }
            });
        }
    }

    function removeOrAddValidation(value) {
        $("#product_sub_category").attr("required", value);
        $("#product_name").attr("required", value);
        $("#quantity").attr("required", value);
        $("#unit").attr("required", value);
        $("#product_description").attr("required", value);
    }

    function resetData(){
        $('#edit_id').attr('data-id', '');
        $('#productSearchResult').html('');
        $('#product_sub_category option:first').prop("disabled",false);
        $('#product_sub_category').val($("#product_sub_category option:first").val());
        $('#product_sub_category option:first').prop("disabled",true);
        $('#unit option:first').prop("disabled",false);
        $('#unit').val($("#unit option:first").val());
        $('#unit option:first').prop("disabled",true);
        $('#product_name').val('');
        $('#quantity').val('');
        $('#product_description').val('');
        $('#product_id').val('');
        $('#product_sub_category_id').val('');
        $('#rfq_table').find('.edit').removeClass('edit');
        $('#add_edit_name_change').text('{{ __("admin.add") }}')
        $('.deleteProduct').removeClass('d-none')
    }

    function deleteProduct(id){
        swal({
            title: "{{ __('dashboard.are_you_sure') }}?",
            text: "{{ __('dashboard.delete_warning') }}",
            icon: "/assets/images/bin.png",
            buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
            dangerMode: true,
        }).then((willChange) => {
            if (willChange) {
                var product = JSON.parse(localStorage.getItem("product_edit")) || [];
                $('#edit_'+id).remove();
                for (let i = 0; i < product.length; i++) {
                    if(product[i]['id'] == id){
                        product.splice(i, 1);
                    }
                }
                window.localStorage.setItem('product_edit', JSON.stringify(product));
                if(product.length == 0){
                    resetData();
                    removeOrAddValidation(true)
                    $('#rfq_table > tbody:last-child').append(
                        '<tr id="no_data">'
                        +'<td>{{ __("admin.no_product_added") }}</td>'
                        +'</tr>');
                }
                if (product.length != max_product){
                    disableAllFields(false);
                }
            }
        });
    }

    function editProduct(id){
        $('.deleteProduct').removeClass('d-none')
        var product = JSON.parse(localStorage.getItem("product_edit")) || [];
	    if(product.length == max_product){
            disableAllFields(false);
        }
        for (let i = 0; i < product.length; i++) {
            if(product[i]['id'] == id){
                $('#edit_id').attr('data-id', i)
                $('#edit_'+id).addClass('edit');
                $('#product_sub_category').val(product[i]['product_sub_category']);
                $('#product_sub_category_id').val(product[i]['product_sub_category_id'])
                $('#product_id').val(product[i]['product_id'])
                $('#product_name').val(product[i]['product_name']);
                $('#quantity').val(product[i]['quantity']);
                $('#unit').val(product[i]['unit']);
                $('#product_description').val(product[i]['product_description']);
                $('#add_edit_name_change').text('{{ __("admin.rfq_save") }}')
                $('.edit #deleteProduct_'+id).addClass('d-none')
            } else {
                //$('#add_edit_name_change').text('{{ __("admin.add") }}')
                $('#edit_'+product[i]['id']).removeClass('edit');
            }
        }
    }
    /*****begin: RFQ Edit Delivery Detail******/
    var SnippetEditDeliveryDetail = function(){

        var selectStateGetCity = function(){

            $('#stateId').on('change',function(){

                let state = $(this).val();
                let targetUrl = "{{ route('admin.city.by.state',":id") }}";
                targetUrl = targetUrl.replace(':id', state);
                var newOption = '';

                // Add Remove Other State filed
                if (state == -1) {
                    $('#stateId_block').removeClass('col-md-6');
                    $('#stateId_block').addClass('col-md-3');

                    $('#state_block').removeClass('hide');
                    $('#state').attr('required','required');

                    $('#cityId_block').removeClass('col-md-6');
                    $('#cityId_block').addClass('col-md-3');

                    $('#cityId').empty();

                    //set default options on other state mode
                    newOption = new Option('{{ __('admin.select_city') }}','', true, true);
                    $('#cityId').append(newOption).trigger('change');

                    newOption = new Option('Other','-1', true, true);
                    $('#cityId').append(newOption).trigger('change');


                } else {
                    $('#stateId_block').removeClass('col-md-3');
                    $('#stateId_block').addClass('col-md-6');

                    $('#state_block').addClass('hide');
                    $('#state').removeAttr('required','required');

                    $('#cityId_block').removeClass('col-md-3');
                    $('#cityId_block').addClass('col-md-6');

                    $('#city_block').addClass('hide');
                    $('#city').removeAttr('required','required');

                    //Fetch cities by state
                    if (state != '' && state != null) {
                        $.ajax({
                            url: targetUrl,
                            type: 'POST',
                            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},

                            success: function (response) {

                                if (response.success) {

                                    $('#cityId').empty();

                                    newOption = new Option('{{ __('admin.select_city') }}', '', true, true);
                                    $('#cityId').append(newOption).trigger('change');

                                    for (let i = 0; i < response.data.length; i++) {
                                        newOption = new Option(response.data[i].name, response.data[i].id, true, true);
                                        $('#cityId').append(newOption).trigger('change');
                                    }

                                    newOption = new Option('Other', '-1', true, true);
                                    $('#cityId').append(newOption).trigger('change');

                                    /*******begin:Add and remove last null option for no conflict*******/
                                    newOption = new Option('0', '0', true, true);
                                    $('#cityId').append(newOption).trigger('change');
                                    $('#cityId').each(function () {
                                        $(this).find("option:last").remove();
                                    });
                                    /*******end:Add and remove last null option for no conflict*******/

                                    let selectedAddressCity = $('#useraddress_id option:selected').attr('data-city-id');
                                    if (selectedAddressCity != null && selectedAddressCity != '') {
                                        $('#cityId').val(selectedAddressCity).trigger('change');
                                    } else {
                                        $('#cityId').val(null).trigger('change');
                                    }

                                }

                            },
                            error: function () {

                            }
                        });
                    } else {
                        $('#cityId').empty();

                        //set default options on other state mode
                        newOption = new Option('{{ __('admin.select_city') }}','', true, true);
                        $('#cityId').append(newOption).trigger('change');

                        newOption = new Option('Other','-1', true, true);
                        $('#cityId').append(newOption).trigger('change');

                        $('#cityId').val(null).trigger('change');
                    }

                }

            });

        },

        selectCitySetOtherCity = function(){

            $('#cityId').on('change',function(){

                let city = $(this).val();

                // Add Remove Other City filed
                if (city == -1) {
                    $('#cityId_block').removeClass('col-md-6');
                    $('#cityId_block').addClass('col-md-3');

                    $('#city_block').removeClass('hide');
                    $('#city').attr('required','required');

                    if ($('#stateId').val()>0) {
                        $('#stateId_block').removeClass('col-md-6');
                        $('#stateId_block').addClass('col-md-6');
                    }

                } else {
                    $('#cityId_block').removeClass('col-md-3');
                    $('#cityId_block').addClass('col-md-6');

                    $('#city_block').addClass('hide');
                    $('#city').removeAttr('required','required');

                }

            });

        },

        initiateCityState = function(){

            let state               =   $('#state').val();
            let selectedState       =   $('#stateId').val();

            if (state != null && state !='') {
                $('#stateId').val('-1').trigger('change');
            }

            if (selectedState !='' && selectedState != null) {
                $('#stateId').val(selectedState).trigger('change');
            }

        };

        return {
            init:function(){
                selectStateGetCity(),
                selectCitySetOtherCity(),
                initiateCityState()
            }
        }

    }(1);jQuery(document).ready(function(){
        SnippetEditDeliveryDetail.init();
    });
    /*****end: RFQ Edit Delivery Detail******/
    $("#creditSwitchCheckDefault").on('change',function(){
        $(".credit-type-div").toggleClass('hide')
    })

    /****************************************************Search for Product*************************************/
    var SnippetGetProductCategoryDetails = function(){

var getProductList = function(){
 $(document).on('keyup','#searchProductCategory',function(){
    if ($(this).val().length >= 3) {
        $.ajax({
            url: "/search-product",
            method:'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {'data' : $(this).val()},
            success : function(response){

                if (response.success) {
                     setResult(response.data);
                } else {
                    resetResult()
                }

            },
        });
    } else {
        resetResult();
    }
    });

},
setResult = function(data){
    var searchResult = '';
    data.forEach(function (data) {
                    searchResult += '<li  data-categoryName="'+ data.categoryName+ '"data-subcategoryName="'+ data.subcategoryName+ '"data-productTextName="'+ data.productTextName+ '"data-value="'+ data.productName+ '"data-product-id="' + data.productId +'" data-category-id="' + data.categoryId + '" data-subcategory-id="' + data.subcategoryId + '" ' +
                        ' class="list-group-item list-item-pointer listProductCat">'+
                        data.productName+'</li>';
    });
    $('#searchGroup').html(searchResult);
    selectProductList();
},

resetResult = function(){
    $('#searchGroup').html('');
},

 selectProductList = function(){

    $(document).on('mousedown', '.listProductCat', function (e) {

    var txt = $(this).parent().attr('id');
    var str =$(this).attr('data-value');
    const prodcutArray = str.split("-");
    $('#product_category').on('select2:selecting', function (evt) {
        last_category = $('#product_category').val();
    });
    var product = JSON.parse(localStorage.getItem("product_edit")) || [];
    if (product.length != 0 && $(this).attr('data-categoryName')!= $('#editRfqform #select2-product_category-container').html() && $('#editRfqform #product_category').val().toLowerCase()!='other') {
    var validates = $('#show_change_cat_validation').attr('data-value');
    if (validates === 'true') {
    if ($(this).attr('data-categoryName')!= $('#editRfqform #select2-product_category-container').html()) {

        var button = "{{ __('admin.ok') }}";
        var text = '{{ __("admin.alert_change_category") }}';
        var mailText = '';
        var checkFromCategory = true;
        var key = 'change_cat';
        var cat_id = $(this).attr('data-category-id');
        if (key == 'change_cat') {
            button = ["{{ __('admin.cancel') }}", "{{ __('admin.ok') }}"]
            mailText = '{{ __('dashboard.are_you_sure') }}'
        }

        swal({
            title: mailText,
            text: text,
            icon: "/assets/images/warn.png",
            buttons: button,
        }).then((changeit) => {
            if (changeit) {

                if (checkFromCategory!== null) {
                    removeOrAddValidation(true);
                    if (key == 'change_cat') {
                        cat_ajax(cat_id)
                    }
                    localStorage.clear();
                    $('#rfq_table tbody').html('');
                    $('#rfq_table > tbody:last-child').append(
                        '<tr id="no_data">'
                        + '<td>{{ __("admin.no_product_added") }}</td>'
                        + '</tr>');
                }
                disableAllFields(false);
                if($('#editRfqform #product_category').val().toLowerCase()==='other'){
                    $('#productCategoryOtherDiv').addClass('d-none');
                    //$('#othercategory').attr("required", false);
                    $('#product_sub_category').parent().removeClass('d-none');
                }
                $('#editRfqform #product_category').attr('value',$(this).attr('data-categoryName'));
                $('#editRfqform #select2-product_category-container').val($(this).attr('data-categoryName'));
                $('#editRfqform #select2-product_category-container').html($(this).attr('data-categoryName'));
                $("#editRfqform #select2-product_category-container").attr('title',$(this).attr('data-categoryName'));
                cat_ajax($(this).attr('data-category-id'),$(this).attr('data-subcategory-id'));
                $("#editRfqform #searchProductCategory").val($("<b>").html($(this).attr('data-value')).text());
                $("#editRfqform #category_id").val($(this).attr('data-category-id'));
                $("#editRfqform #product_sub_category_id").val($(this).attr('data-subcategory-id'));
                $("#editRfqform #product_id").val($(this).attr('data-product-id'));
                $('#editRfqform #product_sub_category option[value="'+$(this).attr('data-subcategoryName')+'"]').attr('selected','selected');
                $('#editRfqform #product_name').val($(this).attr('data-productTextName'));
                $('#editRfqform #product_category option[value="'+$(this).attr('data-categoryName')+'"]').attr('selected','selected');
            } else {

                if (key == 'change_cat') {
                    last_category = $('#product_category').val();
                    $('#product_category').val(last_category).trigger("change");
                }
                resetData();
                resetResult();
            }
        });

        $("#editRfqform #searchProductCategory").val('');

    } } } else{
        if($('#editRfqform #product_category').val().toLowerCase()==='other'){
        $('#productCategoryOtherDiv').addClass('d-none');
        $('#othercategory').attr("required", false);
        $('#product_sub_category').parent().removeClass('d-none');
        }
        $('#editRfqform #product_category').attr('value',$(this).attr('data-categoryName'));
        $('#editRfqform #select2-product_category-container').val($(this).attr('data-categoryName'));
        $('#editRfqform #select2-product_category-container').html($(this).attr('data-categoryName'));
        $("#editRfqform #select2-product_category-container").attr('title',$(this).attr('data-categoryName'));
        cat_ajax($(this).attr('data-category-id'),$(this).attr('data-subcategory-id'));
        $("#editRfqform #searchProductCategory").val($("<b>").html($(this).attr('data-value')).text());
        $("#editRfqform #category_id").val($(this).attr('data-category-id'));
        $("#editRfqform #product_sub_category_id").val($(this).attr('data-subcategory-id'));
        $("#editRfqform #product_id").val($(this).attr('data-product-id'));
        $('#editRfqform #product_sub_category option[value="'+$(this).attr('data-subcategoryName')+'"]').attr('selected','selected');
        $('#editRfqform #product_name').val($(this).attr('data-productTextName'));
       $('#editRfqform #product_category option[value="'+$(this).attr('data-categoryName')+'"]').attr('selected','selected');
       resetResult();

    }
    });

};

return {
    init: function () {
        getProductList()
       // selectProductList()
    }
}


}(1);

jQuery(document).ready(function(){
    SnippetGetProductCategoryDetails.init();
});
</script>

<script>
    // CSRF Token
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $(document).ready(function(){
        $( "#tags" ).bind("keyup.autocomplete", function(event) {
            if(event.keyCode == $.ui.keyCode.BACKSPACE || event.keyCode == $.ui.keyCode.DELETE) {
                $(".count").text('0 Result Found');
                event.stopPropagation();
            }
        });
        $( "#tags" ).autocomplete({
            minLength:3,
            source: function( request, response ) {
                // Fetch data
                $.ajax({
                    url: "/search-product",
                    method:'POST',
                    dataType: "json",
                    data: {
                        _token: CSRF_TOKEN,
                        data: escape(request.term)
                    },
                    success: function( data ) {
                        $(".count").text(data.cnt+' Result Found');
                        $(".count").css("display", "block");
                        $('#editRfqform #product_name').removeClass('blink');
                        $('#editRfqform #product_sub_category').removeClass('blink');
                        $("#editRfqform .select2.select2-container.select2-container--default").removeClass('blink');
                        response( $.map( data.data, function( item ) {
                            return {
                                label: item.productName,
                                categoryName: item.categoryName,
                                product_name:item.productTextName,
                                productId:item.productId,
                                categoryId:item.categoryId,
                                subcategoryId:item.subcategoryId,
                                subcategoryName:item.subcategoryName,
                            }
                        }));
                    },

                });
            },
            select: function (event, ui) {

                var productName = ui.item.label;
                var product_name = ui.item.product_name;
                var categoryName = ui.item.categoryName;
                var categoryId = ui.item.categoryId;
                var productId = ui.item.productId;
                var subcategoryId = ui.item.subcategoryId;
                var subcategoryName = ui.item.subcategoryName;
                const prodcutArray = productName.split("-");
                var product = JSON.parse(localStorage.getItem("product_edit")) || [];

            if(productName!='No data available'){
                if (product.length != 0 && categoryName != $('#editRfqform #select2-product_category-container').html() && $('#editRfqform #product_category').val().toLowerCase() != 'other') {
                    var validates = $('#show_change_cat_validation').attr('data-value');
                    if (validates === 'true') {
                        if (categoryName != $('#editRfqform #select2-product_category-container').html()) {

                            var button = "{{ __('admin.ok') }}";
                            var text = '{{ __("admin.alert_change_category") }}';
                            var mailText = '';
                            var checkFromCategory = true;
                            var key = 'change_cat';
                            var cat_id = $(this).attr('data-category-id');
                            if (key == 'change_cat') {
                                button = ["{{ __('admin.cancel') }}", "{{ __('admin.ok') }}"]
                                mailText = '{{ __('dashboard.are_you_sure') }}'
                            }

                            swal({
                                title: mailText,
                                text: text,
                                icon: "/assets/images/warn.png",
                                buttons: button,
                            }).then((changeit) => {
                                if (changeit) {

                                    if (checkFromCategory !== null) {
                                        removeOrAddValidation(true);
                                        if (key == 'change_cat') {
                                            cat_ajax(cat_id)
                                        }
                                        localStorage.clear();
                                        $('#rfq_table tbody').html('');
                                        $('#rfq_table > tbody:last-child').append(
                                            '<tr id="no_data">'
                                            + '<td>{{ __("admin.no_product_added") }}</td>'
                                            + '</tr>');
                                    }
                                    disableAllFields(false);
                                    if ($.trim(prodcutArray[0]).toLowerCase() === 'other') {
                                        $('#editRfqform #product_category').attr('value', $.trim(prodcutArray[0]));
                                        $("#editRfqform #select2-product_category-container").attr('title', $.trim(prodcutArray[0]));
                                        $('#editRfqform #product_category option[value="' + $.trim(prodcutArray[0]) + '"]').attr('selected', 'selected');
                                        $('#editRfqform #product_name').val($.trim(prodcutArray[2]));
                                        $('#editRfqform #select2-product_category-container').val($.trim(prodcutArray[0]));
                                        $('#editRfqform #select2-product_category-container').html($.trim(prodcutArray[0]));
                                    } else {
                                        $("#editRfqform #select2-product_category-container").attr('title', categoryName);
                                        $('#editRfqform #product_category option[value="' + categoryName + '"]').attr('selected', 'selected');
                                        $('#editRfqform #product_name').val(product_name);
                                        $('#editRfqform #product_category').attr('value', categoryName);
                                        $('#editRfqform #select2-product_category-container').val(categoryName);
                                        $('#editRfqform #select2-product_category-container').html(categoryName);
                                    }
                                    cat_ajax(ui.item.categoryId, ui.item.subcategoryId);
                                    $("#editRfqform #tags").val('');
                                    $("#editRfqform #category_id").val(ui.item.categoryId);
                                    $("#editRfqform #product_sub_category_id").val(ui.item.subcategoryId);
                                    $("#editRfqform #product_id").val(ui.item.productId);
                                    $('#editRfqform #product_sub_category option[value="' + ui.item.subcategoryName + '"]').attr('selected', 'selected');
                                    $('#editRfqform #product_category option[value="' + categoryName + '"]').attr('selected', 'selected');
                                    $('#editRfqform #product_name').addClass('blink');
                                    $('#editRfqform #product_sub_category').addClass('blink');
                                    $("#editRfqform .select2.select2-container.select2-container--default").addClass('blink');

                                } else {

                                    if (key == 'change_cat') {
                                        last_category = $('#product_category').val();
                                        $('#product_category').val(last_category).trigger("change");
                                    }
                                    // resetData();
                                    resetResult();
                                }
                            });

                        }
                    }
                } else {

                    if ($.trim(prodcutArray[0]).toLowerCase() === 'other') {
                        $('#editRfqform #product_category').attr('value', $.trim(prodcutArray[0]));
                        $("#editRfqform #select2-product_category-container").attr('title', $.trim(prodcutArray[0]));
                        $('#editRfqform #product_category option[value="' + $.trim(prodcutArray[0]) + '"]').attr('selected', 'selected');
                        if ($.trim(prodcutArray[2]) != 'undefined') {
                            $('#editRfqform #product_name').val($.trim(prodcutArray[2]));
                        } else {
                            $('#editRfqform #product_name').val('');
                        }
                        $('#editRfqform #select2-product_category-container').val($.trim(prodcutArray[0]));
                        $('#editRfqform #select2-product_category-container').html($.trim(prodcutArray[0]));
                    } else {
                        $("#editRfqform #select2-product_category-container").attr('title', categoryName);
                        $('#editRfqform #product_category option[value="' + categoryName + '"]').attr('selected', 'selected');
                        $('#editRfqform #product_name').val(product_name);
                        $('#editRfqform #product_category').attr('value', categoryName);
                        $('#editRfqform #select2-product_category-container').val(categoryName);
                        $('#editRfqform #select2-product_category-container').html(categoryName);
                    }
                    cat_ajax(ui.item.categoryId, ui.item.subcategoryId);
                    $("#editRfqform #tags").val('');
                    $("#editRfqform #category_id").val(ui.item.categoryId);
                    $("#editRfqform #product_sub_category_id").val(ui.item.subcategoryId);
                    $("#editRfqform #product_id").val(ui.item.productId);
                    $('#editRfqform #product_sub_category option[value="' + ui.item.subcategoryName + '"]').attr('selected', 'selected');
                    $('#editRfqform #product_category option[value="' + categoryName + '"]').attr('selected', 'selected');
                    $('#editRfqform #product_name').addClass('blink');
                    $('#editRfqform #product_sub_category').addClass('blink');
                    $("#editRfqform .select2.select2-container.select2-container--default").addClass('blink');
                    $(".count").text('0 Result Found');
                }
            }
                return false;
            }
        })

    });
</script>
@endsection
