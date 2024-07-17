<style>
    .hidden {
        display: none;
    }

    .active a {
        color: #ff0000;
    }
    .list-item-pointer{
        cursor: pointer;
    }
    li.selected {background:#ccc;}
    .select2-container--default .select2-selection--single,
    .select2-container--default .select2-selection--multiple {
        border-radius: 4px !important;
    }

    .form-control, .form-select {
        border-radius: 4px !important;
    }

    textarea.form-control {
        height: 50px;
    }

    .error {
        color: #dc3545;
    }

    .edit {
        --bs-table-accent-bg: var(--bs-table-hover-bg) !important;
        color: var(--bs-table-hover-color) !important;
    }

    /* share link */
    #social-links ul {
        padding: 0px 20px;
        display: grid;
        grid-template-columns: auto auto auto auto auto;
    }

    #social-links ul li {
        padding: 5px 20px;
        list-style: none;
    }

    #social-links ul li a {
        padding: 6px;
        border-radius: 5px;
        margin: 1px;
        font-size: 36px;
    }

    #social-links .fa-facebook {
        color: #0d6efd;
    }

    #social-links .fa-twitter {
        color: deepskyblue;
    }

    #social-links .fa-linkedin {
        color: #0e76a8;
    }

    #social-links .fa-whatsapp {
        color: #25D366
    }

    #social-links .fa-reddit {
        color: #FF4500;;
    }

    #social-links .fa-telegram {
        color: #0088cc;
    }

    /* share link */

    select[readonly] {
        background: #e9ecef !important;
        pointer-events: none;
        touch-action: none;
    }

    select[readonly].select2-hidden-accessible + .select2-container {
        pointer-events: none;
        touch-action: none;
    }

</style>

@can('publish buyer rfqs')
    <div class="header_top d-flex align-items-center">
        <h1 class="mb-0">{{ __('dashboard.post_a_new_requirement') }}</h1>
    </div>
    <div class="card radius_1">
        <div class="card-body">
            <form class="row g-4 floatlables" data-parsley-validate autocomplete="off" name="quickrfqPost"
                  id="quickrfqPost">
                @csrf
                <div class="col-md-12 mb-0 d-flex align-items-center">
                    <h5 class="ps-0 mt-0 text-primary mb-0" style="font-family: 'europaNuova_re';font-size: 15px;">
                        {{ __('rfqs.Product_Details') }}
                    </h5>
                    <div class="search_rfq mx-2 position-relative">
                        <label class="form-label" for="Search_category" style="left: 10px;">{{__('admin.search_product')}}</label>
                        <input type="text" name="tags" id="tags" class="form-control categorysearch" placeholder="{{__('admin.product_search')}}" />
                    </div>
                    <small class="ms-1 count" style="display: none"></small>

                    <a name="repeat_rfq" id="repeat_rfq" class="btn btn-warning btn-sm me-2 ms-auto" data-bs-toggle="modal" data-bs-target="#RepeatrfqModal" href="javascript:void(0);" role="button" onclick="getRepeatRfqList()">

                        <span class="me-2 ">
                            <img src="{{ URL::asset('front-assets/images/icons/repeat_rfq.png') }}" height="14px"
                                 alt="{{ __('dashboard.repeat_rfq') }}">
                        </span>{{ __('dashboard.repeat_rfq') }}
                    </a>
                </div>
                <div class="col-md-12" id="product_category_block{{isset($rfqs) ? $rfqs->id : ''}}">
                    <label class="form-label" for="product_category">{{ __('dashboard.select_product_label') }}<span
                            class="text-danger">*</span></label>
                    {{-- <input type="text" class="form-control " value="Product" required> --}}
                    <select id='product_category' name="product_category" onchange="changeCategory(this)" data-id=""
                            data-current-select="0" class="form-select" required>
                        <option disabled selected>{{ __('dashboard.select_product_category') }}</option>
                        @php $other = 1; @endphp
                        @foreach ($category as $item)
                            <option @if(isset($rfqs))
                                        data-rfqname="{{$rfqs->category}}"
                                        {{(strtolower($rfqs->category) == strtolower($item->name )? 'selected':'')}}

                                    @elseif(isset($rfn))
                                        data-rfqname="{{$rfn->item_category_name}}"
                                        {{(strtolower($rfn->item_category_name) == strtolower($item->name )? 'selected':'')}}
                                    @endif
                                    data-text="{{ $item->name }}"
                                    data-category-id="{{ $item->id }}" data-category="{{ $item->name }}"
                                    value="{{ $item->name }}">{{ $item->name }}</option>
                            @if(isset($rfqs) && (strtolower($rfqs->category) == strtolower($item->name )))
                                @php $other = 0; @endphp
                            @endif
                        @endforeach
                        @if(isset($rfqs))
                            @if($other == 1)
                                <option data-rfqname="{{$rfqs->category}}" data-category-id="0" value="Other" selected>
                                    Other
                                </option>
                            @else
                                <option data-rfqname="{{$rfqs->category}}" data-category-id="0" value="Other">Other
                                </option>
                            @endif
                        @else
                            <option data-category-id="0" value="Other">Other</option>
                        @endif
                    </select>
                    <input type="hidden" class="form-control" id="category_id" name="category_id">

                </div>
            <!--<div class="col-md-12 d-none position-relative"
                     id="productCategoryOtherDiv{{isset($rfqs) ? $rfqs->id : ''}}">
                    <label>{{ __('dashboard.other_product_category') }}<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="other_category{{isset($rfqs) ? $rfqs->id : ''}}"
                           name="other_category">
                </div>-->

                <div class="col-md-12">
                    <div class="multibox_border pt-3 p-2">
                        <div class="row px-0">
                            <div class="d-none" id="edit_id" data-id=""></div>
                            <div class="d-none" id="show_change_cat_validation" data-value="true"></div>
                            <div class="col-md-12 mb-4 position-relative" id="productSubCategoryDiv">
                                <div class="">
                                    <label for="product_sub_category"
                                           class="form-label mb-0 product_sub_category_for">{{ __('dashboard.Product Sub Category') }}
                                        <span class="text-danger">*</span></label>
                                    <select id='product_sub_category' name="product_sub_category"
                                            onchange="changeSubCategory(this)" data-id="" class="form-select" required>
                                        <option disabled
                                                selected>{{ __('dashboard.Select Product Sub Category') }}</option>
                                        @if(isset($subcategories) && ($rfn->item_subCategory_id))
                                            @foreach($subcategories as $subcategory)
                                                <option data-sub-category-id="{{ $subcategory->id }}" value="{{ $subcategory->name }}" data-text="{{ $subcategory->name }}" @if($rfn->item_subCategory_id == $subcategory->id) selected="" @endif>{{ $subcategory->name }}</option>
                                            @endforeach
                                        @endif


                                    </select>
                                    <input type="hidden" class="form-control" id="product_sub_category_id"
                                           name="product_sub_category_id">
                                </div>
                            </div>

                            <div class="col-md-12 col-xl-8 mb-4">
                                <label class="form-label mb-0 product_name_for"
                                       for="product_name">{{ __('dashboard.Product_Name') }}<span
                                        class="text-danger">*</span></label>
                                <input type="text" data-parsley-errors-container="#product_name_error"
                                       class="form-control product_name" id="product_name" name="product_name"
                                       onclick="productSearch(this)" onkeyup="searchProductPost(this)"
                                       onmousedown="searchProductPost(this)" required=""
                                       placeholder="{{ __('dashboard.product_name_placeholder') }}" value="@if(isset($rfn->item_name)) {{ $rfn->item_name }} @endif">
                                <input type="hidden" class="form-control" id="product_id" name="product_id" value="@if(isset($rfn->item_id)) {{ $rfn->item_id }} @endif">
                                <div id="product_name_error"></div>
                            </div>
                            <div class="col-md-6 col-xl-2 mb-4">
                                <label for="quantity" class="form-label quantity_for">{{ __('dashboard.Quantity') }}
                                    <span class="text-danger">*</span></label>
                                <input type="number" class="form-control qty_input_post" name="quantity" id="quantity" placeholder="0" required="" aria-invalid="true" value="{{isset($rfn->quantity)?$rfn->quantity:0}}">
                            </div>
                            <div class="col-md-6 col-xl-2 mb-4">
                                <label class="form-label unit_for" for="unit">{{ __('dashboard.Unit') }}<span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="unit" name="unit" required>
                                    <option selected disabled>{{ __('dashboard.Select Unit') }}</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}" @if(isset($rfn->unit_id) && $unit->id==$rfn->unit_id) selected @endif>{{ $unit->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 error_res ">
                                <label class="form-label mb-0 product_description_for"
                                       for="product_description">{{ __('dashboard.Product_Description') }}<span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control"
                                          placeholder="{{ __('dashboard.Product_Description_Placeholder') }}"
                                          name="product_description" id="product_description" required>{{ isset($rfn->item_description) ? $rfn->item_description : '' }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="py-2 text-end">

                        <button type="button" class="btn btn-secondary px-2 py-1  " style="font-size: 12px;"
                                id="cancel_product_btn" onclick="CancelsProduct()">
                            <img src="{{ URL::asset('front-assets/images/icons/cancel.png') }}" alt="Cancel"
                                 class="pe-1"
                                 style="max-height: 12px;"> <span>{{ __('admin.cancel') }}</span>
                        </button>
                        <button type="button" class="btn btn-primary px-2 py-1 ms-1" style="font-size: 12px;"
                                id="add_product_btn" onclick="AddProduct()">
                            <img src="{{ URL::asset('front-assets/images/icons/icon_post_require.png') }}" alt="Add"
                                 class="pe-1" style="max-height: 12px;"> <span
                                id="add_edit_name_change">{{ __('admin.add') }}</span>
                        </button>
                    </div>
                    <div class="text-center text-danger"
                         id="five_ptoduct_validation_msg">{{--<small>You can not add more then 5 products.</small>--}} </div>
                    <div class="bg-light multi_pro_list p-0">
                        <table class="table bg-white mb-0" id="rfq_table">
                            <thead>
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
                                        <td class="text-end text-nowrap">
                                            <a href="javascript:void(0);" class="p-1 mx-1"
                                               onclick="editProduct('{{ $product_rfq->id }}')"><img
                                                    src="{{ URL::asset("front-assets/images/icons/icon_fillinmore.png") }}"
                                                    alt="Cancel" class="align-top" style="max-height: 14px;"></a>
                                            <a href="javascript:void(0);" class="p-1 mx-1 deleteProduct"
                                               id="deleteProduct_{{ $product_rfq->id }}"
                                               onclick="deleteProduct('{{ $product_rfq->id }}')"><img
                                                    src="{{ URL::asset("front-assets/images/icons/icon_delete_add.png") }}"
                                                    alt="Cancel" class="align-top" style="max-height: 14px;"></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr id="no_data">
                                    <td>{{ __("admin.no_product_added") }}</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-12">
                    <label class="form-label" for="comment">{{ __('dashboard.Comment') }}</label>
                    <textarea class="form-control" placeholder="{{ __('dashboard.Comment_Placeholder') }}"
                              name="comment" id="comment">{{isset($rfqs) ? $rfqs->comment : (isset($rfn->comment)?$rfn->comment:'')}}</textarea>
                </div>
                <div class="col-md-6 col-xl-4">
                    <label for="" class="form-label">{{ __('rfqs.upload_attachment') }}</label>
                    <div class="d-flex form-control py-2">
                    <span class="">
                        <input type="file" name="rfq_attachment_doc[]" class="form-control" id="rfq_attachment_doc"
                               accept=".jpg,.png,.jpeg,.pdf" onchange="showFile(this)" hidden="" multiple>
                        <label id="upload_btn" for="rfq_attachment_doc">{{ __('profile.browse') }}</label>
                    </span>
                        <div id="file-rfq_attachment_doc" class="d-flex align-items-center">
                            @if (isset($rfq_attachments) && count($rfq_attachments)>=1)
                                @php
                                    $rfq_file_name = '';
                                    if(count($rfq_attachments)>1){
                                        $rfq_file_name = count($rfq_attachments).' Files';
                                        if (isset($rfqs)){
                                            $downloadAttachment = "downloadAttachment(".$rfqs->id.", 'attached_document','".$rfqs->reference_number."')";
                                        }else{
                                            $downloadAttachment = "downloadAttachment(".$rfq->id.", 'attached_document','".$rfq->reference_number."')";
                                        }
                                    }else{
                                        $rfqFileTitle = Str::substr($rfq_attachments[0]->attached_document,44);
                                        $extension_rfq_file = getFileExtension($rfqFileTitle);
                                        $rfq_file_filename = getFileName($rfqFileTitle);
                                        if(strlen($rfq_file_filename) > 10){
                                            $rfq_file_name = substr($rfq_file_filename,0,10).'...'.$extension_rfq_file;
                                        } else {
                                            $rfq_file_name = $rfq_file_filename.$extension_rfq_file;
                                        }
                                        $downloadAttachment = "downloadattach(".$rfq_attachments[0]->id.", 'attached_document', '".$rfq_file_name."')";
                                    }
                                @endphp
                                <span class="ms-2" id="RfqAfterRemove">
                                    <a href="javascript:void(0);" id="RfqFileDownload" onclick="{{$downloadAttachment}}"
                                       title="{{ $rfq_file_name }}"
                                       style="text-decoration: none;"> {{ $rfq_file_name }}</a>
                                </span>
                                <span style="@if(count($rfq_attachments)>1) display:block @else display:none @endif"
                                      class="btnlistuploaded">
                                    <a href="javascript:void(0);" title="{{ __('rfqs.upload_attachment') }}">
                                        <img src="{{URL::asset('front-assets/images/icons/uploadlist.png')}}"
                                             alt="CLose button" class="ms-2">
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
                                            <div class="d-flex align-items-center border-bottom pb-1 mb-1"
                                                 id="RFQAttachment{{$rfqAttach->id}}">
                                                <span class="ms-2  flex-grow-1">
                                                    <a href="javascript:void(0);" id="RfqFileDownload"
                                                       onclick="downloadattach('{{ $rfqAttach->id }}', 'attached_document', '{{ $rfq_file_name }}')"
                                                       title="{{ $rfqFileTitle }}"
                                                       style="text-decoration: none;"> {{ $rfq_file_name }}</a>
                                                </span>
                                                <span class="ms-2">
                                                    <a class="rfq_file" href="javascript:void(0);"
                                                       title="{{ __('profile.download_file') }}"
                                                       onclick="downloadattach('{{ $rfqAttach->id }}', 'attached_document', '{{ $rfq_file_name }}')"
                                                       style="text-decoration: none;"><img
                                                            src="{{URL::asset('front-assets/images/icons/icon_download.png')}}"
                                                            width="14px"></a>
                                                </span>
                                            </div>
                                        @php
                                            }
                                        @endphp
                                    <!-- repeat div section -->
                                    </div>
                                    <!-- end hide section -->
                                </span>
                            @else
                                <input type="hidden" class="form-control" id="old_attachment_file"
                                       name="old_attachment_file[]" multiple value="">
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <label for="" class="form-label">{{ __('admin.commercial_tc') }}</label>
                    <div class="d-flex form-control py-2">
                    <span class="">
                        <input type="file" name="termsconditions_file" class="form-control" id="termsconditions_file"
                               accept=".jpg,.png,.jpeg,.pdf" onchange="showFile(this)" hidden="">
                        <label id="upload_btn" for="termsconditions_file">{{ __('profile.browse') }}</label>
                    </span>
                        @if (isset($rfqs) && $rfqs->termsconditions_file)
                            @php
                                $termsconditionsFileTitle = Str::substr($rfqs->termsconditions_file,stripos($rfqs->termsconditions_file, "termsconditions_file_") + 21);
                                $extension_termsconditions_file = getFileExtension($termsconditionsFileTitle);
                                $termsconditions_file_filename = getFileName($termsconditionsFileTitle);
                                if(strlen($termsconditions_file_filename) > 10){
                                    $termsconditions_file_name = substr($termsconditions_file_filename,0,10).'...'.$extension_termsconditions_file;
                                } else {
                                    $termsconditions_file_name = $termsconditions_file_filename.$extension_termsconditions_file;
                                }
                            @endphp
                            <div id="file-termsconditions_file_repeat" class="d-flex align-items-center">
                                <input type="hidden" class="form-control" id="old_termsconditions_file"
                                       name="old_termsconditions_file" value="{{ $termsconditions_file_name }}">
                                <input type="hidden" class="form-control" id="oldtermsconditions_file"
                                       name="oldtermsconditions_file" value="{{ $rfqs->termsconditions_file }}">
                                <span class="ms-2">
                                        <a href="javascript:void(0);" id="TermsconditionsFileDownload"
                                           onclick="downloadattach('{{ $rfqs->id }}', 'termsconditions_file', '{{ $termsconditions_file_name }}')"
                                           title="{{ $termsconditionsFileTitle }}"
                                           style="text-decoration: none;"> {{ $termsconditions_file_name }}</a>
                                    </span>
                                <span class="ms-2">
                                        <a class="termsconditions_file" href="javascript:void(0);"
                                           title="{{ __('profile.download_file') }}"
                                           onclick="downloadattach('{{ $rfqs->id }}', 'termsconditions_file', '{{ $termsconditions_file_name }}')"
                                           style="text-decoration: none;"><img
                                                src="{{URL::asset('front-assets/images/icons/icon_download.png')}}"
                                                width="14px"></a>
                                    </span>
                            </div>
                        @elseif (!empty($userDetails) && isset($userDetails->defaultCompany->termsconditions_file) )
                            <div id="file-termsconditions_file_exist" class="d-flex align-items-center">
                                @php
                                    $termsconditionsFileTitle = empty($userDetails) && !isset($userDetails->defaultCompany->termsconditions_file) ? '' : Str::substr($userDetails->defaultCompany->termsconditions_file, stripos($userDetails->defaultCompany->termsconditions_file, "termsconditions_file_") + 21);
                                    $extension_termsconditions_file = getFileExtension($termsconditionsFileTitle);
                                    $termsconditions_file_filename = getFileName($termsconditionsFileTitle);
                                    if(strlen($termsconditions_file_filename) > 10){
                                        $termsconditions_file_name = substr($termsconditions_file_filename,0,10).'...'.$extension_termsconditions_file;
                                    } else {
                                        $termsconditions_file_name = $termsconditions_file_filename.$extension_termsconditions_file;
                                    }
                                @endphp
                                <input type="hidden" class="form-control" id="oldtermsconditions_file"
                                       name="oldtermsconditions_file"
                                       value="{{ $userDetails->defaultCompany->termsconditions_file }}">
                                <span class="ms-2">
                                        <a href="javascript:void(0);" target="_blank" id="termsconditionsFileDownload"
                                           onclick="downloadimg('{{ $userDetails->defaultCompany->id }}', 'termsconditions_file', '{{ $termsconditionsFileTitle }}')"
                                           title="{{ $termsconditionsFileTitle }}"
                                           style="text-decoration: none;"> {{ $termsconditions_file_name }}</a>
                                    </span>
                                <span class="ms-2">
                                        <a class="termsconditions_file" href="javascript:void(0);"
                                           title="{{ __('profile.download_file') }}"
                                           onclick="downloadimg('{{ $userDetails->defaultCompany->id }}', 'termsconditions_file', '{{ $termsconditionsFileTitle }}')"
                                           style="text-decoration: none;">
                                            <img src="{{URL::asset('front-assets/images/icons/icon_download.png')}}"
                                                 width="14px">
                                        </a>
                                    </span>
                            </div>
                        @endif
                        <div id="file-termsconditions_file" class="d-flex align-items-center">
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mb-0">
                    <h5 class="ps-0 mt-2 text-primary mb-0" style="font-family: 'europaNuova_re';font-size: 15px;" id="rfq_address">
                        {{ __('rfqs.delivery_details') }}
                    </h5>
                </div>
                <div class="col-md-6">
                    <div class="row me-0 ">
                        <div class="col-md-12 pe-0" id="address_block">
                            <label class="form-label">{{ __('rfqs.select_address') }}<span class="text-danger">*</span></label>
                            <select class="form-select" id="useraddress_id" name="useraddress_id" required>
                                <option disabled selected value="-1">{{ __('rfqs.select_address') }}</option>
                                @foreach ($userAddress as $item)
                                    @php
                                        if (isset($rfqs)){
                                            $addSelected = '';
                                            if ($item->address_name== $rfqs->address_name && $item->address_line_1== $rfqs->address_line_1 && $item->address_line_2==$rfqs->address_line_2){
                                                $addSelected = 'selected';
                                            }
                                        }
                                    @endphp
                                    <option data-address_name="{{$item->address_name}}"
                                            data-address_line_2="{{$item->address_line_2}}"
                                            data-address_line_1="{{$item->address_line_1}}"
                                            data-sub_district="{{$item->sub_district}}"
                                            data-district="{{$item->district}}"
                                            data-city="{{$item->city}}" data-state="{{$item->state}}"
                                            data-pincode="{{$item->pincode}}"
                                            data-state-id="{{$item->state_id > 0 ? $item->state_id : \App\Models\UserAddresse::OtherState}}"
                                            data-city-id="{{$item->city_id > 0 ? $item->city_id : \App\Models\UserAddresse::OtherCity}}"
                                            value="{{ $item->id }}"
                                    @if(isset($rfqs)) {{$addSelected}} @else {{(($defaultAddressId == $item->id) ? 'selected' : '')}} @endif>
                                        {{ $item->address_name }}
                                    </option>
                                @endforeach
                                <option data-address-id="0" value="Other">Other</option>
                            </select>
                            @if(isset($rfqs))
                                @foreach ($userAddress as $item)
                                    @if($item->address_name==$rfqs->address_name && $item->address_line_1==$rfqs->address_line_1 && $item->address_line_2==$rfqs->address_line_2)
                                        <input type="hidden" name="useraddress_id" class="form-control address_name"
                                               id="useraddress_id" value="{{$item->id}}" readonly>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="address_name" class="form-label">{{ __('rfqs.address_name') }}<span class="text-danger">*</span></label>
                    <input type="text" name="address_name" class="form-control" id="address_name"
                           value="{{isset($rfqs) ? $rfqs->address_name : ''}}" required="">
                </div>
                <div class="col-md-12">
                    <label for="addressLine1" class="form-label">{{ __('rfqs.address_line1') }}<span
                            class="text-danger">*</span></label>
                    <input type="text" name="address_line_1" class="form-control" id="addressLine1"
                           value="{{isset($rfqs) ? $rfqs->address_line_1 : ''}}" required>
                </div>
                <div class="col-md-12">
                    <label for="addressLine2" class="form-label">{{ __('rfqs.address_line2') }}</label>
                    <input type="text" class="form-control" name="address_line_2" id="addressLine2"
                           value="{{isset($rfqs) ? $rfqs->address_line_2 : ''}}">
                </div>

                <div class="col-md-3">
                    <label for="sub_district" class="form-label">{{ __('rfqs.sub_district') }}<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="sub_district" id="sub_district" required
                           value="{{isset($rfqs) ? $rfqs->sub_district : ''}}">
                </div>
                <div class="col-md-3">
                    <label for="district" class="form-label">{{ __('rfqs.district') }}<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="district" id="district" required
                           value="{{isset($rfqs) ? $rfqs->district : ''}}">
                </div>
                <div class="col-md-6" id="stateId_block_main">
                    <div class="col-md-12 select2-block" id="stateId_block">
                        <label for="stateId" class="form-label">{{ __('rfqs.provinces') }}<span
                                class="text-danger">*</span></label>
                        <select class="form-select select2-custom" id="stateId" name="stateId"
                                data-placeholder="{{ __('rfqs.select_province') }}" required>
                            <option value="">{{ __('rfqs.select_province') }}</option>
                            @foreach ($states as $state)
                                <option value="{{ $state->id }}"
                                        @if(isset($rfqs) && $rfqs->state_id == $state->id) selected @endif>{{ $state->name }}</option>
                            @endforeach
                            <option value="-1">Other</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3 hide" id="state_block">
                    <label for="state" class="form-label">{{ __('rfqs.other_provinces') }}<span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="state" id="state" required
                           value="{{ isset($rfqs) ? $rfqs->state : '' }}">
                </div>

                <div class="col-md-6" id="cityId_block_main">
                    <div class="col-md-12 select2-block" id="cityId_block">
                        <label for="cityId" class="form-label">{{ __('rfqs.city') }}<span
                                class="text-danger">*</span></label>
                        <select class="form-select select2-custom" id="cityId" name="cityId"
                                data-selected-city="{{ isset($rfqs) ? $rfqs->city_id : ''}}"
                                data-placeholder="{{ __('rfqs.select_city') }}" required>
                            <option value="">{{ __('rfqs.select_city') }}</option>
                            <option value="-1">Other</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3 hide" id="city_block">
                    <label for="city" class="form-label">{{ __('rfqs.other_city') }}<span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="city" id="city"
                           value="{{ isset($rfqs) ? $rfqs->city : ''}}">
                </div>

                <div class="col-md-3">
                    <label for="pincode"
                           class="form-label">{{ __('dashboard.Delivery_Pincode') }}<span
                            class="text-danger">*</span></label>
                    <input type="text" pattern=".{5,}"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\.*?)\..*/g, '$1');"
                           id="pincode" class="form-control" name="pincode" required data-parsley-type="number"
                           onkeypress="" data-parsley-maxlength="6" data-parsley-minlength="5"
                           value="{{isset($rfqs) ? $rfqs->pincode : ''}}">
                </div>
                <div class="col-md-3 col-xl-3">
                    <label class="form-label" for="expected_date">{{ __('dashboard.Expected_Delivery_Date') }}<span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control calendericons" name="expected_date" id="expected_date"
                           placeholder="dd-mm-yyyy" required readonly data-toggle="tooltip" data-placement="top"
                           title="{{ __('admin.expected_delivery_date') }}">
                </div>
                <div class="col-md-6 d-flex">
                    <div class="form-check me-3 pt-2">
                        <input class="form-check-input" type="checkbox" value="1" name="need_rental_forklift"
                               id="need_rental_forklift" {{isset($rfqs) && $rfqs->rental_forklift == 1 ? 'checked':''}}>
                        <label class="form-check-label" for="need_rental_forklift">
                            {{ __('dashboard.need_rental_forklift') }}
                        </label>
                    </div>
                    <div class="form-check pt-2">
                        <input class="form-check-input" type="checkbox" value="1" name="need_unloading_services"
                               id="need_unloading_services" {{isset($rfqs) && $rfqs->unloading_services == 1 ? 'checked':''}}>
                        <label class="form-check-label" for="need_unloading_services">
                            {{ __('dashboard.need_unloading_services') }}
                        </label>
                    </div>
                </div>

                <div class="col-md-12 mb-0">
                    <h5 class="ps-0 text-primary mb-0 mt-2" style="font-family: 'europaNuova_re';font-size: 15px;">
                        {{ __('rfqs.contact_details') }}
                    </h5>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="firstname">{{ __('dashboard.First_Name') }}<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="firstname" id="firstname" required
                           value="{{ isset($rfqs) ? $rfqs->firstname : Auth::user() ? Auth::user()->firstname : '' }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="lastname">{{ __('dashboard.Last_Name') }}<span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="lastname" id="lastname" required
                           value="{{ isset($rfqs) ? $rfqs->lastname : Auth::user() ? Auth::user()->lastname : '' }}">

                </div>
                <div class="col-md-6">
                    <label class="form-label" for="email">{{ __('dashboard.Email') }}<span class="text-danger">*</span></label>
                    <input type="email" class="form-control" name="email" id="email" required
                           value="{{ isset($rfqs) ? $rfqs->email : Auth::user() ? Auth::user()->email : '' }}">

                </div>
                <div class="col-md-6">
                    <label class="form-label" for="mobile_number">{{ __('dashboard.Mobile_Number') }}<span
                            class="text-danger">*</span></label>
                    <input type="tel" class="form-control" name="mobile_number" id="mobile_number" required
                           data-parsley-type="digits" data-parsley-length="[9, 16]"
                           data-parsley-length-message="It should be between 9 and 16 digit."
                           placeholder="XXXXXXXXXXX"
                           value="{{ isset($rfqs) ? $rfqs->mobile : Auth::user() ? Auth::user()->mobile : '' }}">
                </div>

                <div class="col-md-12 mb-0">
                    <h5 class="ps-0 mb-0 mt-2 text-primary " style="font-family: 'europaNuova_re';font-size: 15px;">
                        {{ __('admin.payment_term') }}
                    </h5>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-5 d-flex align-items-center" style="min-height: 40px;">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       id="creditSwitchCheckDefault" {{isset($rfqs) && $rfqs->is_require_credit == 1 ? 'checked' : ''}}>
                                <label class="form-check-label"
                                       for="creditSwitchCheckDefault">{{ __('dashboard.require_credit') }}?</label>
                            </div>
                        </div>
                        <div
                            class="col-md-7 credit-type-div {{isset($rfqs) && $rfqs->is_require_credit == 1 ? '' : 'hide'}}">
                            {{--  <label>{{ __('rfqs.credit_type') }}:</label>--}}
                            <select class="form-select form-select-sm p-2 credit_type" id="credit_days_id"
                                    name="credit_days_id">
                                <option value="0">{{ __('admin.select_credit_type') }}</option>
                                <option
                                    value="lc" {{isset($rfqs) && $rfqs->payment_type == 3 ? 'selected' : ''}}>{{ __('admin.lcdropdwn') }}</option>
                                <option
                                    value="skbdn" {{isset($rfqs) && $rfqs->payment_type == 4 ? 'selected' : ''}}>{{ __('admin.skbdn') }}</option>
                                @foreach($creditDays as $i=>$creditDay)
                                    @php
                                        $credit_name = sprintf(__('rfqs.credit_type_name'),trim($creditDay->days));
                                    @endphp
                                    <option
                                        value="{{$creditDay->days}}" {{isset($rfqs) && $creditDay->days == $rfqs->credit_days ? 'selected' : ''}}>{{$credit_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>


                <div class="col-12 mt-3 d-flex align-items-center">
                    <a href="javascript:void(0);" id="addtofav" class="addtofav btn btn-outline-danger py-1"
                       role="button">
                        <span>
                            <i class="addtofavourite fa fa-star-o me-1"></i>
                        </span>
                        {{ __('dashboard.favourite') }}
                    </a>
                    <input type="hidden" name="preferredSuppliersCounts" id="preferredSuppliersCounts"
                           value="{{ (isset($preferredSupplierCount) && $preferredSupplierCount > 0) ? $preferredSupplierCount : 0 }}">
                    <!-- New Section (Ronak M - 21/06/2022) -->
                    @if(isset($preferredSupplierCount) && $preferredSupplierCount > 0)
                        <div class="supply-selector d-flex align-items-center justify-content-end ms-auto me-2">
                            <div class="form-check form-check-inline mt-2">
                                <input class="form-check-input firstBtn" type="radio" name="is_preferred_supplier"
                                       id="inlineRadio1" value="0"
                                       @if(isset($rfqs)) {{($rfqs->is_preferred_supplier == 0) ? 'checked' : '' }} @else checked="checked" @endif>
                                <label class="form-check-label"
                                       for="inlineRadio1">{{ __('profile.all_suppliers') }}</label>
                            </div>
                            <div class="form-check form-check-inline mt-2">
                                <input class="form-check-input secondBtn" type="radio" name="is_preferred_supplier"
                                       id="inlineRadio2"
                                       value="1" {{isset($rfqs) && ($rfqs->is_preferred_supplier == 1) ? 'checked' : '' }}>
                                <label class="form-check-label"
                                       for="inlineRadio2">{{ __('profile.preferred_supplier_only') }}</label>
                            </div>

                            <div class="position-relative me-3">
                                <button type="button" class="btn btn-default icon_prefferd" id="showPreferredSuppliers"
                                        data-user-id="{{ Auth::user()->id }}" data-bs-toggle="collapse"
                                        href="#collapseExample" role="button" aria-expanded="false"
                                        aria-controls="collapseExample" data-toggle="tooltip" data-placement="top"
                                        title="{{ __('profile.preferred_suppliers') }}">
                                <!-- <img height="12px" src="{{URL::asset('front-assets/images/icons/icon_edit_add.png')}}"> -->
                                </button>

                                <div class="collapse prefsupp shadow border" id="collapseExample">
                                    <div class="row mx-0">
                                        <div class="col-12">
                                            <div class="row dark_blue_bg text-white py-1">
                                                <div
                                                    class="col-12 text-wrap d-flex justify-content-between align-items-center">
                                                    <div class="form-check ms-1 mt-1">
                                                        <input class="form-check-input" type="checkbox" value=""
                                                               id="selectAllSuppliers" checked>
                                                        <label class="form-check-label bg-transparent"
                                                               for="selectAllSuppliers">{{ __('profile.preferred_suppliers') }}
                                                            <small class="px-1 text-center text-danger"
                                                                   id="status"></small></label>
                                                    </div>
                                                <!-- <button type="button" class="btn preferbtn btn-warning" id="applySuppBtn">{{ __('admin.apply') }} Apply</button> -->
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Preferred Supplier Popup Modal -->
                                        <div class="col-12 p-0 prefer-edit" id="preferredSuppliersList">

                                        </div>

                                    {{-- @include('preferredSuppliers/preferred_suppliers_modal')
                                    <div class="col-12 p-0 prefer-edit">
                                        <ul class="list-group">
                                            @if(isset($preferredSuppliers) && sizeof($preferredSuppliers) > 0)
                                                @foreach($preferredSuppliers as $supplier)
                                                <li class="list-group-item border-0 border-bottom">
                                                    <input type="checkbox" class="form-check-input" name="supplier_chk" id="supp_{{ $supplier->preferredSuppId }}" value="{{ $supplier->preferredSuppId }}" checked>
                                                    {{  $supplier->preferredSuppId . ' - ' . $supplier->companyName }}
                                                </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div> --}}
                                    <!-- / End -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="preferredSuppliersIds" id="preferredSuppliersIds" value=""/>
                @endif
                <!-- / End -->
                    <input type="hidden" name="is_favourite" id="is_favourite" value="0"/>
                    <input type="hidden" name="isRepeatRfq" id="isRepeatRfq" value="{{isset($rfqs) ? 1 : 0}}"/>
                    <input type="hidden" name="isRepeatRfqId" id="isRepeatRfqId"
                           value="{{isset($rfqs) ? $rfqs->id : ''}}"/>
                    <button type="button"
                            class="btn btn-primary px-3 py-1 mb-1 @if($preferredSupplierCount==0) ms-auto @endif"
                            id="submitQuickRfqPost">
                        <img src="{{ URL::asset('front-assets/images/icons/icon_post_require.png') }}"
                             alt="{{ __('dashboard.post_requirement') }}"
                             class="pe-1">{{ __('dashboard.post_requirement') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endcan
<!-- RFQ Repeat modal -->
<div class="modal fade" id="RepeatrfqModal" tabindex="-1" aria-labelledby="RepeatrfqModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h5 class="modal-title" id="exampleModalLabel">{{__('dashboard.my_rfqs')}}</h5>
                <div class="ms-auto d-flex align-items-center">
                    <div class="me-2">
                        <input class="form-control form-control-sm filtersearch" style="font-size: 13px; width: 230px;"
                               attr-name="filterRFQsearch" id="filterRFQsearch" type="search"
                               placeholder="{{__('dashboard.rfq_exiting_search')}}">
                    </div>
                    <a class="btn btn-outline-primary btn-sm px-3" type="button" data-bs-toggle="collapse"
                       data-bs-target="#filterrepeat" aria-expanded="false" aria-controls="collapseExample">
                        {{__('admin.filter')}}
                    </a>
                    <a href="javascript:void(0);" id="addtofavRepeat"
                       class="addtofavRepeat btn btn-outline-danger text-decoration-none btn-sm px-2 ms-2 me-3"
                       role="button">
                        <span>
                            <i id="addtofavouriteRepeat" class="addtofavouriteRepeat fa fa-star-o"
                               style="line-height: 1.5;"></i>
                        </span>
                    </a>
                    <input type="hidden" name="isRepeatFavouriteRfq" id="isRepeatFavouriteRfq" value="0">
                    <button type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Close"
                            onclick="closeRepeatRfqModel()"></button>
                </div>
            </div>
            <div class="modal-body" style="height: 400px; overflow-y: auto;">
                <div class="collapse mb-2" id="filterrepeat">
                    <div class="card card-body border-0 px-0">
                        <form class="" data-parsley-validate autocomplete="off" name="repeatRfqList" id="repeatRfqList">
                            <div class="row g-2 floatlables">
                                <div class="col-md-4">
                                    <label class="form-label category" for="category">{{ __('admin.category') }}</label>
                                    <select class="form-select py-2 fs12" id="category" name="category"
                                            data-current-select="0" class="form-select" onchange="cat_ajax(this.value)">
                                        <option value="">{{ __('admin.select_category') }}</option>
                                        @foreach ($category as $item)
                                            <option data-text="{{ $item->name }}" data-category-id="{{ $item->id }}"
                                                    data-category="{{ $item->name }}"
                                                    value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label subcategory"
                                           for="subcategory">{{ __('admin.sub_category') }}</label>
                                    <select class="form-select py-2 fs12" id="subcategory" name="subcategory"
                                            onchange="getProductsBySubCategory(this)">
                                        <option value="">{{ __('dashboard.Select Product Sub Category') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label product" for="product">{{ __('admin.product') }}</label>
                                    <select class="form-select py-2 fs12" id="product" name="product">
                                        <option value="">{{ __('admin.select_product_name') }}</option>
                                    </select>
                                </div>

                                <div class="col-md-12 d-flex justify-content-end">
                                    <a name="clearFilter" id="clearFilter" class="btn btn-secondary mt-1 btn-sm  fs12"
                                       onclick="clearRepeatRfqModel()" href="javascript:void(0);" role="button">
                                        <img src="{{ URL::asset('front-assets/images/icons/cancel.png') }}" alt="Cancel"
                                             class="pe-1" style="max-height: 12px;"> {{ __('admin.clear') }}
                                    </a>

                                    <a name="submitFilterData" id="submitFilterData"
                                       class="btn btn-primary mt-1 btn-sm  ms-1 fs12" onclick="getRepeatRfqList()"
                                       href="javascript:void(0);" role="button">
                                        <img src="{{ URL::asset('front-assets/images/icons/icon_post_require.png') }}"
                                             alt="Add" class="pe-1" style="max-height: 12px;">
                                        {{ __('admin.apply') }}</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div id="repeatRfqMainDiv">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End RFQ Repeat modal -->
<!-- join group modal -->
<div class="modal fade" id="joinGroupModal" tabindex="-1" aria-labelledby="joinGroupModalLabel"
     data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header bg-light py-2">
                <h5 class="modal-title text-dark " id="joinGroupModalLabel">{{__('dashboard.groups')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="submit_with_group"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3" id="groupListMainDiv"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        id="submit_without_group">{{__('profile.cancel')}}</button>
            </div>
        </div>
    </div>
</div>
<!-- join group modal -->

<!-- Share on social media Modal -->

<div class="modal fade" id="shareGroupModal" tabindex="-1" aria-labelledby="shareGroupModalLabel"
     data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h5 class="modal-title" id="shareGroupModalLabel">{{__('dashboard.share_via')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6 class="text-center mb-3">{{__('dashboard.share_group_social_media')}}</h6>
                <div id="social_div">
                </div>
                <hr>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="groupLink" value="" readonly aria-label="Group Link"
                           aria-describedby="basic-addon2">
                    <button type="button" class="btn btn-primary" id=""
                            onclick="copyGroupLink()">{{__('dashboard.copy')}} <span class=""><i class="fa fa-copy ms-2"
                                                                                                 style="font-size: 16px;"></i></span>
                    </button>
                </div>
                <div class="copied"></div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->

<script src="{{ URL::asset('front-assets/intlTelInput/js/intlTelInput.js') }}"></script>
<script>

    var emailflag = '{{ $emailvarify }}';
    var last_category = -1;
    var max_product = '{{ $max_product??5 }}';
    var max_attachments = '{{ $max_attachments??0 }}';
        @if(isset($rfqs))
    var rfqdetail = @json($rfqs);
    var product = @json($productRfq);
    @endif
    //let supplierValidationMsg = "({{ __('profile.atleast_one_checkbox_checked') }})";
    $(document).ready(function () {

        //By Default Disable "showPreferredSuppliers" button (Ronak M - 22/06/2022)
        $("#showPreferredSuppliers").attr('disabled', true).css("opacity", "0.4");

        //On radio button checked, enable / disable "showPreferredSuppliers" button (Ronak M - 22/06/2022
        $("input[name='is_preferred_supplier']").change(function () {

            var categoryId = ($("#category_id").val() != '') ? $("#category_id").val() : '';

            if (!$(this).prop("checked")) {
                $("#selectAllSuppliers").prop("checked", false);
            }

            if ($(this).val() == "0") {
                $("#showPreferredSuppliers").attr('disabled', true);
                $("#preferredSuppliersIds").val("");
                $("#collapseExample").removeClass("show");
                $("#showPreferredSuppliers").attr("aria-expanded", "false").css("opacity", "0.4");
                $('#submitQuickRfqPost').prop('disabled', false);

                getPreferredSuppliers(categoryId = '');
            } else {
                $("#showPreferredSuppliers").attr('disabled', false).css("opacity", "1");
                ;
                $("input:checkbox[name=supplier_chk]").each(function () {
                    $("input:checkbox[name=supplier_chk]").prop('checked', $(this).prop("checked"));
                });
                if (categoryId != '') {
                    getPreferredSuppliers(categoryId);
                }
            }
        });

        //Get all preferred suppliers when category is not selected (Ronak M - 29/06/2022)
        getPreferredSuppliers(categoryId = '');
    });

    var addedProduct = [];

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /** get only selected suppliers id from bootstrap popup modal
     *   Ronak M - 23/06/2022
     */
    $("#selectAllSuppliers").click(function () {
        if ($('input:checkbox[name=supplier_chk]').filter(':checked').length == 1) {
            $("input:checkbox[name=supplier_chk]").prop("checked", $(this).prop("checked"));
            $("#status").text("");
        } else {
            $("#status").text("");
            $("#submitQuickRfqPost").attr('disabled', false);
            $("input:checkbox[name=supplier_chk]").prop("checked", $(this).prop("checked"));
        }
    });

    $(document).on('click', 'input:checkbox[name=supplier_chk]', function () {
        if ($('input:checkbox[name=supplier_chk]').filter(':checked').length < 1) {
            $("#status").text("({{ __('profile.atleast_one_checkbox_checked') }})");
            return false;
        } else {
            $("#status").text("");
            $("#submitQuickRfqPost").attr('disabled', false);
            if ($('.suppCheckBox:checked').length == $('.suppCheckBox').length) {
                $('#selectAllSuppliers').prop('checked', true);
            } else {
                $('#selectAllSuppliers').prop('checked', false);
            }

        }
    });

    //Get preferred suppliers listing as per selected category (Ronak M - 29/06/2022)
    $('#showPreferredSuppliers').click(function (e) {
        e.preventDefault();
        $("#status").text('');

        if ($("input[name='is_preferred_supplier']:checked").val() == 1) {
            //If no supplier is selected then popup should not be close
            if ($('input:checkbox[name=supplier_chk]').filter(':checked').length == 0) {
                $("#showPreferredSuppliers").attr("aria-expanded", "true");
                $("#collapseExample").addClass("show");
                $("#status").text("({{ __('profile.atleast_one_checkbox_checked') }})");
                $("#submitQuickRfqPost").attr('disabled', true);
                return false;
            }
            //End
        }


        if ($('.suppCheckBox:checked').length == $('.suppCheckBox').length) {
            $('#selectAllSuppliers').prop('checked', true);
        } else {
            $('#selectAllSuppliers').prop('checked', false);
        }

        let categoryId = ($("#category_id").val() != '') ? $("#category_id").val() : '';
        //getPreferredSuppliers(categoryId);
    });

    //Get preferred supplier as per selected category id (Ronak M - 29/06/2022)
    function getPreferredSuppliers(categoryId) {
        //if (categoryId) {
        $.ajax({
            url: "{{ route('get-selected-preferred-suppliers-ajax') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "categoryId": categoryId
            },
            type: 'POST',
            dataType: "json",
            success: function (successData) {
                $("#preferredSuppliersList").html(successData.preferredSupplierView);
            },
            error: function () {
                console.log('error');
            }
        });
        //}
    }

    //When user click on apply button
    // $("#applySuppBtn").click(function(e) {
    //     e.preventDefault();
    //     suppliersArray = [];
    //     $("input:checkbox[name=supplier_chk]:checked").each(function() {
    //         suppliersArray.push($(this).val());
    //     });
    //     $("#preferredSuppliersIds").val(suppliersArray.join(", "));
    //     $("#collapseExample").removeClass("show");
    //     $("#showPreferredSuppliers").attr("aria-expanded","false");
    // });
    //------- End --------//

    //Attachment Document
    function showFile(input) {
        var rdt = cdt = new DataTransfer();
        var fileName = '';
        if (input.name == 'termsconditions_file') {
            $('#file-termsconditions_file_exist').hide().removeClass('d-flex');
            $('#file-termsconditions_file_repeat').hide().removeClass('d-flex');
        }
        if (input.id == 'rfq_attachment_doc' && input.files.length > max_attachments) {
            swal({
                icon: 'error',
                title: '',
                text: '{{ sprintf(__('admin.multiple_attachment_add'),$max_attachments??0) }}'
            })
            $('#file-' + input.id).html('');
        } else {
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
            $('#file-' + input.id).append('<span class="ms-2"><a href="javascript:void(0);" id="' + input.id + 'Download " style="text-decoration: none">' + fileName + '</a></span> <span class="removeFile"  id="' + input.id + 'File" data-id="' + input.id + '" data-name="' + input.name + '"><a href="#" title="Remove" style="text-decoration: none;"><img src="' + image_path + '" alt="CLose button" class="ms-2"></a></span><span class="ms-2"><a class="' + input.id + ' downloadbtn hidden" href="javascript:void(0);" title="{{ __('profile.download_file') }}" onclick="downloadattach(' + download_function + ')" style="text-decoration: none;"><img src="{{URL::asset('front-assets/images/icons/icon_download.png')}}" width="14px"></a></span>');

            if (checkFileSizeCount != 0) {
                swal({
                    text: checkFileSizeCount + "File you tried adding is larger then the 3MB",
                    icon: "/assets/images/info.png",
                    buttons: 'ok',
                    dangerMode: true,
                }).then((changeit) => {
                    if (input.id == 'termsconditions_file') {
                        $('#file-' + input.id).html('');
                        $('#file-termsconditions_file_exist').show().addClass('d-flex');
                    } else {
                        $('#rfq_attachment_doc')[0].files = rdt.files;
                    }

                    checkFileSizeCount = 0;
                });
            }
        }

    }

    //Download attachment document
    /*function downloadimg(fileName, name){
        event.preventDefault();
        alert(name);
        var data = {
            fieldName: fileName
        }
        $.ajax({
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
    }*/

    //Download t&c document

    function downloadimg(id, fieldName, name) {
        event.preventDefault();
        var data = {
            id: id,
            fieldName: fieldName
        }
        $.ajax({
            url: "{{ route('profile-company-download-image-ajax') }}",
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

    var input = document.querySelector("#mobile_number");
    var iti = window.intlTelInput(input, {
        initialCountry: "id",
        separateDialCode: true,
        dropdownContainer: null,
        preferredCountries: ["id"],
        hiddenInput: "phone_code"
    });

    $('#useraddress_id').select2({
        dropdownParent: $('#address_block'),
    });
    $(document).on('change', '#useraddress_id', function () {
        let selected_option = $('option:selected', this);
        bindAddressFields(selected_option);
    });

    function bindAddressFields(selected_option) {
        $("#address_name").val(selected_option.attr('data-address_name'));
        $("#addressLine1").val(selected_option.attr('data-address_line_1'));
        $("#addressLine2").val(selected_option.attr('data-address_line_2'));
        $("#sub_district").val(selected_option.attr('data-sub_district'));
        $("#district").val(selected_option.attr('data-district'));
        $("#city").val(selected_option.attr('data-city'));
        $("#state").val(selected_option.attr('data-state'));
        $("#pincode").val(selected_option.attr('data-pincode'));

        $("#stateId").val(selected_option.attr('data-state-id')).trigger('change');
        setTimeout(function () {
            $("#cityId").val(selected_option.attr('data-city-id')).trigger('change')
        }, 600);
    }

    $("#mob_number_post").focusin(function () {
        let countryData = iti.getSelectedCountryData();
        $('input[name="phone_code"]').val(countryData.dialCode).attr('iso2', countryData.iso2);
    });
    $(document).ready(function () {
        @php
            $phoneCode = Auth::user()->phone_code?str_replace('+','',Auth::user()->phone_code):62;
            $country = Auth::user()->phone_code?strtolower(getRecordsByCondition('countries',['phone_code'=>$phoneCode],'iso2',1)):'id';
        @endphp
        $('input[name="phone_code"]').val({{ $phoneCode }}).attr('iso2', '{{$country}}');
        iti.setCountry('{{$country}}');

        let selected_addr_option = $('#useraddress_id option:selected');
        bindAddressFields(selected_addr_option);

    });

    function searchProductPost(data) {
        var data_id = data.id;
        var id = data_id.replace('product_name', '');
        var text = data.value.trim();
        var subCategoryId = $('#quickrfqPost #product_sub_category' + id + ' option:selected').attr('data-sub-category-id');
        var categoryId = $("#quickrfqPost #product_category option:selected").attr('data-category-id');
        if (text.length != '') {
            $('#product_name_change').html('Product : ' + text);
        } else {
            $('#product_name_change').html('Product');
        }

        var data = {
            product: text,
            subCategoryId: subCategoryId,
            categoryId: categoryId,
            _token: $('meta[name="csrf-token"]').attr('content')
        }
        if (categoryId && categoryId != 0) {
            $.ajax({
                url: '{{ route('search-product-ajax') }}',
                data: data,
                type: 'POST',
                success: function (successData) {
                    $('#quickrfqPost #productSearchResult' + id).remove();
                    var searchData = '<ul id="productSearchResult' + id + '" class="searchResult">';
                    var dataArray = [];
                    if (successData.filterData.length) {
                        successData.filterData.forEach(function (data) {
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

                    // alert(searchData);
                    $('#quickrfqPost #product_name' + id).after(searchData);
                },
            });
        }

    }

    function searchProductDescriptionPost(text) {
        var product = $('#quickrfqPost #product_name').val();
        var data = {
            productDescription: text,
            product: product,
            _token: $('meta[name="csrf-token"]').attr('content')
        }
        if (product.trim().length) {
            $.ajax({
                url: "{{ route('search-product-description-ajax') }}",
                data: data,
                type: 'POST',
                success: function (successData) {
                    $('#quickrfqPost #productDescriptionSearchResult').remove();
                    var dataArray = [];
                    var searchData =
                        '<ul id="productDescriptionSearchResult" class="descriptionSearchResult">';
                    if (successData.filterData.length) {
                        successData.filterData.forEach(function (data) {
                            if (data.description) {
                                if (!dataArray.includes(data.description)) {
                                    dataArray.push(data.description);
                                    searchData += '<li data-id="' + data.id + '" data-value="' +
                                        data.description +
                                        '" class="searchProductDescriptionPost">' +
                                        data.description + '</li>';
                                }
                            }
                        });
                    }
                    searchData += '</ul>'
                    $('#quickrfqPost #product_description').after(searchData);
                },
            });
        }
    }

    function changeCategory(data) {
        var cat_id = $(data).find(':selected').attr('data-category-id');
        var cat_name = $(data).find(':selected').attr('data-category');
        var product = JSON.parse(localStorage.getItem("product")) || [];
        const servicesCategoryIds = {{ json_encode(\App\Models\Category::SERVICES_CATEGORY_IDS) }};
        let categoryIsService = false;

        for (i=0;i<servicesCategoryIds.length;i++){
            if(servicesCategoryIds[i] == cat_id){
                categoryIsService = true;
            }
        }

        if (product.length == 0) {
            if (cat_id == 0) {
                $('#quickrfqPost #category_id').val(cat_id);
                $('#quickrfqPost #product_name').val(null);
                $('#quickrfqPost #productSearchResult').remove();
                $('#quickrfqPost #productCategoryOtherDiv').removeClass('d-none');
                $('#quickrfqPost #productSubCategoryOtherDiv').removeClass('d-none');
                $('#cloneProduct').html('');
               // $('#quickrfqPost #other_category').val('');
                $('#product_name_change').attr('id', 'product_name_change').html('Product');
                var options = '<option selected disabled>{{ __('dashboard.Select Product Sub Category') }}</option>';
                options += '<option  data-sub-category-id="0" value="Other">Other</option>';
                $('#quickrfqPost #product_sub_category').empty().append(options);
            } else {
                $('#quickrfqPost #category_id').val(cat_id);
                $('#quickrfqPost #productCategoryOtherDiv').addClass('d-none');
                $('#quickrfqPost #productSubCategoryOtherDiv').addClass('d-none');
                $('#product_name_change').html('Product');
                $('#product_name').attr('id', 'product_name').val('');
                $('#quickrfqPost #product_id').val('');
                $('#quickrfqPost #productSearchResult').remove();
                $('#quantity').attr('id', 'quantity').val('');
                $("#unit").val($("#unit option:first").val());
                $('#product_description').attr('id', 'product_description').val('');
                $('#cloneProduct').html('')
                cat_ajax(cat_id);
                //get preferred suppliers as per the selected category (Ronak M - 29/06/2022)
                getPreferredSuppByCategory(cat_id);
                $(data).parsley().reset();
            }
        } else {
            var validates = $('#show_change_cat_validation').attr('data-value');
            if (validates === 'true') {
                if ($('#product_category').val() != last_category) {
                    alertMessage('{{ __("admin.alert_change_category") }}', true, 'change_cat', cat_id);
                    resetData();
                }
            }
        }
        if (categoryIsService) {
            $('#quickrfqPost #rfq_address').html('{{ __('dashboard.pickup_details') }}');
            $('#quickrfqPost #product_description').attr('placeholder', '{{ __('dashboard.Service_Product_Description_Placeholder') }}');
        } else {
            $('#quickrfqPost #rfq_address').html('{{ __('rfqs.Delivery_Details') }}');
            $('#quickrfqPost #product_description').attr('placeholder', '{{ __('dashboard.Product_Description_Placeholder') }}');
        }
    }

    function cat_ajax(cat_id, sub_cat_id = null) {

        if(sub_cat_id==null){
            $("#quickrfqPost #tags").val('');
        }
        //alert(cat_id+''+sub_cat_id);
        $.ajax({
            url: "{{ route('get-subcategory-ajax', '') }}" + "/" + cat_id,
            type: 'GET',
            success: function (successData) {

                if (sub_cat_id != null || sub_cat_id != 0) {
                    var select = 'selected="selected"';
                }
                var options = '<option selected disabled>{{ __('dashboard.Select Product Sub Category') }}</option>';
                if (successData.subCategory.length) {
                    successData.subCategory.forEach(function (data) {
                        if (data.id == sub_cat_id) {
                            var selected = select;
                        } else {
                            selected = '';
                        }
                        options += '<option data-sub-category-id="' + data.id + '" value="' + data.name + '" data-text="' + data.name + '"' + selected + '>' + data.name + '</option>';

                    });
                }
                var selectOther = '';
                if (sub_cat_id == '0') {
                    selectOther = 'selected="selected"';
                }
                options += '<option data-sub-category-id="0" value="Other"' + selectOther + '>Other</option>';
                $('#quickrfqPost #product_sub_category').empty().append(options);
                $('#repeatRfqList #subcategory').empty().append(options);
                var optionsProduct = '<option selected>{{ __('admin.select_product_name') }}</option>';
                $('#repeatRfqList #product').empty().append(optionsProduct);
            },
            error: function () {
                console.log('error');
            }
        });
    }


    //get preferred suppliers as per the selected category (Ronak M - 29/06/2022)
    function getPreferredSuppByCategory(cat_id) {

        $("#collapseExample").removeClass("show");
        $("#showPreferredSuppliers").attr({"aria-expanded": "false", "disabled": true});
        $("#inlineRadio1").prop("checked", true);

        $.ajax({
            url: "{{ route('get-preferred-suppliers-by-category-ajax', '') }}" + "/" + cat_id,
            type: 'GET',
            success: function (successData) {
                if (successData.suppliersData.length) {
                    $(".supply-selector").removeClass('d-none');
                    $("#submitQuickRfqPost").removeClass('ms-auto');
                } else {
                    $(".supply-selector").addClass('d-none');
                    $("#submitQuickRfqPost").addClass('ms-auto');
                }
            },
            error: function () {
                console.log('error');
            }
        });
    }

    function changeSubCategory(data,product_name=null) {
        $('#productSearchResult').html('');
        var text = data.id;
        //alert(product_name);
        if(product_name==null) {
            $('#quickrfqPost #product_id').val('');
            $('#quickrfqPost #product_name').val(null);
            $('#quickrfqPost #productSearchResult').remove();
            if(text!=undefined)
            var replace_id = text.replace('product_sub_category', '');
        }else{
            //$('#quickrfqPost #product_id').val('');
            $('#quickrfqPost #product_name').val(product_name);
           // var replace_id = text.replace('product_sub_category', '');
        }

        $('#quickrfqPost #product_sub_category_id' + replace_id).val($('#quickrfqPost #product_sub_category' + replace_id + ' option:selected').attr('data-sub-category-id'));
    }

    function productSearch(data) {
        var data_id = data.id;
        var id = data_id.replace('product_name', '');
        if (id != undefined && id != 0) {
            $('#quickrfqPost #productSearchResult' + id).removeClass('hidden');
        } else {
            $('#quickrfqPost #productSearchResult').removeClass('hidden');
        }
    }

    function alertMessage(text, checkFromCategory, key, cat_id) {
        var button = "{{ __('admin.ok') }}"
        var mailText = '';
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
            } else {

                if (key == 'change_cat') {
                    last_category = $('#product_category').val();
                    $('#product_category').val(last_category).trigger("change");
                }
            }
        });
    }

    function AddProduct() {
        $("#quickrfqPost #searchProductCategory").val('');
        $('#searchGroup').html('');
        $('#searchProductCategory').val('');
        var edit_id = $('#edit_id').attr('data-id');
        var product = JSON.parse(localStorage.getItem("product")) || [];
        if ($('#product_sub_category').val() != null && $('#product_name').val() != '' && $('#quantity').val() != '' && $('#unit').val() != null && $('#product_description').val() != '' && $('#quantity').val() >= 1 && product.length != max_product) {
            if (edit_id == '') {
                localstoreProduct();
            } else {
                upadteProduct(edit_id);
            }
        } else if (edit_id != '' && product.length == max_product) {
            upadteProduct(edit_id);
        } else {
            if ($('#product_sub_category').val() == null && $('#product_category').val() == null) {
                alertMessage('{{ __("admin.alert_product_cat_sub_cat") }}');
            } else if ($('#product_sub_category').val() == null) {
                alertMessage('{{ __("admin.alert_product_sub_cat") }}');
            } else if ($('#product_name').val() == '' || $('#quantity').val() == '' || $('#unit').val() == null || $('#product_description').val() == '' || $('#quantity').val() < 1) {
                if ($('#product_name').val() == '') {
                    alertMessage('{{ __("admin.alert_product_name_error") }}');
                } else if ($('#quantity').val() == '') {
                    alertMessage('{{ __("admin.alert_product_qty") }}');
                } else if ($('#quantity').val() < 1) {
                    alertMessage('{{ __("admin.alert_product_unit_one") }}');
                } else if ($('#unit').val() == null) {
                    alertMessage('{{ __("admin.alert_product_unit") }}');
                } else if ($('#product_description').val() == '') {
                    alertMessage('{{ __("admin.alert_product_description") }}');
                }
            } else if (product.length == max_product) {
                alertMessage('{{ sprintf(__('admin.multiple_product_add'),$max_product??5) }}')
            }
        }
    }

    function localstoreProduct() {
        var product = JSON.parse(localStorage.getItem("product")) || [];
        var last_id = 0;
        if (product.length == 0) {
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
        });

        addedProduct = product; // added by ronak bhabhor

        // console.log('addedProduct');
        // console.log(addedProduct);

        window.localStorage.setItem('product', JSON.stringify(product));
        $('#no_data').remove();
        //remove required validation
        removeOrAddValidation(false);
        var buttons = '<a href="javascript:void(0);" class="p-1 mx-1" onclick="editProduct(' + last_id + ')" data-toggle="tooltip" data-placement="top" title="{{ __('admin.edit') }}"><img src="{{ URL::asset("front-assets/images/icons/icon_fillinmore.png") }}" alt="Cancel" class="align-top" style="max-height: 14px;"></a> <a href="javascript:void(0);"class="p-1 mx-1 deleteProduct" onclick="deleteProduct(' + last_id + ')" data-toggle="tooltip" data-placement="top" title="{{ __('admin.delete') }}"><img src="{{ URL::asset("front-assets/images/icons/icon_delete_add.png") }}" alt="Cancel" class="align-top" style="max-height: 14px;"></a>';
        var edit_id = 'edit_' + last_id;
        $('#rfq_table > tbody:last-child').append(
            '<tr id="' + edit_id + '">'
            + '<td>' + $("#product_name").val() + '</td>'
            + '<td>' + $("#product_description").val() + '</td>'
            + '<td>' + $("#product_sub_category").val() + '</td>'
            + '<td>' + $('#quantity').val() + ' ' + $('#unit').find(":selected").text() + '</td>'
            + '<td class="text-end text-nowrap">' + buttons + '</td>'
            + '</tr>');

        resetData();
        if (product.length == max_product) {
            disableAllFields(true);
        }
    }

    function disableAllFields(val, text) {
        if (val) {
            $('#five_ptoduct_validation_msg').html('<small>{{ sprintf(__('admin.multiple_product_add'),$max_product??5) }} </small>');
        } else {
            if (text != 'edit') {
                $('#five_ptoduct_validation_msg').html('');
            }
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
        var product = JSON.parse(localStorage.getItem("product")) || [];
        product[id]['product_sub_category'] = $('#product_sub_category').val();
        product[id]['unit'] = $('#unit').val();
        product[id]['product_name'] = $('#product_name').val();
        product[id]['quantity'] = $('#quantity').val();
        product[id]['product_description'] = $('#product_description').val();
        product[id]['product_sub_category_id'] = $('#product_sub_category_id').val();
        product[id]['product_id'] = $('#product_id').val();
        window.localStorage.setItem('product', JSON.stringify(product));
        var data_id = product[id]['id'];
        var dispaly_edit = 'edit_' + data_id;
        var buttons = '<a href="javascript:void(0);" class="p-1 mx-1" onclick="editProduct(' + data_id + ')"><img src="{{ URL::asset("front-assets/images/icons/icon_fillinmore.png") }}" alt="{{ __('admin.cancel') }}" class="align-top" style="max-height: 14px;"></a> <a href="javascript:void(0);"class="p-1 mx-1 deleteProduct" onclick="deleteProduct(' + data_id + ')"><img src="{{ URL::asset("front-assets/images/icons/icon_delete_add.png") }}" alt="{{ __('admin.delete') }}" class="align-top" style="max-height: 14px;"></a>';
        $('#' + dispaly_edit).html('');
        $('#' + dispaly_edit).append('<td>' + $("#product_name").val() + '</td>'
            + '<td>' + $("#product_description").val() + '</td>'
            + '<td>' + $("#product_sub_category").val() + '</td>'
            + '<td>' + $('#quantity').val() + ' ' + $('#unit').find(":selected").text() + '</td>'
            + '<td class="text-end text-nowrap">' + buttons + '</td>')
        resetData();

        addedProduct = product; // added by ronak bhabhor

        // console.log('updatedProduct');
        // console.log(addedProduct);
        if (product.length == max_product) {
            disableAllFields(true);
        }
    }

    function CancelsProduct() {
        if ($('#product_sub_category').val() != null || $('#product_name').val() != '' || $('#quantity').val() != '' || $('#unit').val() != null || $('#product_description').val() != '') {
            swal({
                title: "{{ __('dashboard.are_you_sure') }}?",
                text: "{{ __('admin.reset_data_cancel') }}",
                icon: "/assets/images/bin.png",
                buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
                dangerMode: true,
            }).then((willChange) => {
                if (willChange) {
                    resetData();
                    var product = JSON.parse(localStorage.getItem("product")) || [];
                    if (product.length == max_product) {
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

    function resetData() {
        $('#edit_id').attr('data-id', '');
        $('#productSearchResult').html('');
        $('#product_sub_category option:first').prop("disabled", false);
        $('#product_sub_category').val($("#product_sub_category option:first").val());
        $('#product_sub_category option:first').prop("disabled", true);
        $('#unit option:first').prop("disabled", false);
        $('#unit').val($("#unit option:first").val());
        $('#unit option:first').prop("disabled", true);
        $('#product_name').val('');
        $('#quantity').val('');
        $('#product_description').val('');
        $('#product_id').val('');
        $('#product_sub_category_id').val('');
        $('#rfq_table').find('.edit').removeClass('edit');
        $('#add_edit_name_change').text('{{ __("admin.add") }}')
    }

    function deleteProduct(id) {
        swal({
            title: "{{ __('dashboard.are_you_sure') }}?",
            text: "{{ __('dashboard.delete_warning') }}",
            icon: "/assets/images/bin.png",
            buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
            dangerMode: true,
        }).then((willChange) => {
            if (willChange) {
                var product = JSON.parse(localStorage.getItem("product")) || [];
                $('#edit_' + id).remove();
                for (let i = 0; i < product.length; i++) {
                    if (product[i]['id'] == id) {
                        product.splice(i, 1);
                    }
                }
                window.localStorage.setItem('product', JSON.stringify(product));
                if (product.length == 0) {
                    resetData();
                    removeOrAddValidation(true);
                    $('#rfq_table > tbody:last-child').append(
                        '<tr id="no_data">'
                        + '<td>{{ __("admin.no_product_added") }}</td>'
                        + '</tr>');
                }
                if (product.length != max_product) {
                    disableAllFields(false);
                }

            }
        });
    }

    function editProduct(id) {
        var product = JSON.parse(localStorage.getItem("product")) || [];
        if (product.length == max_product) {
            disableAllFields(false, 'edit');
        }
        for (let i = 0; i < product.length; i++) {
            if (product[i]['id'] == id) {
                $('#edit_id').attr('data-id', i);
                $('#edit_' + id).addClass('edit');
                $('#product_sub_category').val(product[i]['product_sub_category']);
                $('#product_sub_category_id').val(product[i]['product_sub_category_id'])
                $('#product_id').val(product[i]['product_id'])
                $('#product_name').val(product[i]['product_name']);
                $('#quantity').val(product[i]['quantity']);
                $('#unit').val(product[i]['unit']);
                $('#product_description').val(product[i]['product_description']);
                $('#add_edit_name_change').text('{{ __("admin.rfq_save") }}')
            } else {
                //$('#add_edit_name_change').text('{{ __("admin.add") }}')
                $('#edit_' + product[i]['id']).removeClass('edit');
            }

        }
    }


    /**
     * submit_without_group btn click on cancel join group popup => post new rfq without join group
     * created by ronak bhabhor 06/05/2022
     */
    // $('.share_anc').on('click', function(e) {
    $(document).on('click', '.share_anc', function (e) {
        // console.log('here');
        // console.log($(this).next().html());
        let share_group_popup_html = $(this).next().html();
        $('#social_div').html(share_group_popup_html);
        let share_group_link = $(this).attr('data-share_group_link');
        $('#groupLink').val(share_group_link);
        $('#shareGroupModal').modal('show');
    });

    //Copy group link on click of "copy button"
    function copyGroupLink() {
        var copyText = document.getElementById("groupLink");
        copyText.select();
        navigator.clipboard.writeText(copyText.value);
        $(".copied").text("Copied to clipboard").show().fadeOut(2000);
        copyText = null;
    }

    /**
     * join_group_btn btn click from popup: post new rfq with join group
     * created by ronak bhabhor 06/05/2022
     */
    $(document).on('click', '.join_group_btn', function (e) {
        e.stopImmediatePropagation();
        e.preventDefault();
        $(this).prop('disabled', true);
        $('#submitQuickRfqPost').prop('disabled', true);
        let groupId = $(this).attr('data-group_id');
        let groupAchievedQuntity = $(this).attr('data-group_achieved_quntity');
        let groupTargetQuantity = parseInt($(this).attr('data-group_target_quantity'));
        let rfqWithAchieveQty = parseInt(groupAchievedQuntity) + parseInt(addedProduct[0].quantity);
        let remainingQty = groupTargetQuantity - groupAchievedQuntity;
        /*
        if(rfqWithAchieveQty > groupTargetQuantity){
            let text = "{{__('admin.rfq_quantity_should_not_be_greater')}} "+ remainingQty +" "+$(this).attr('data-unit');
            swal({
                text: text,
                icon: "warning",
                // buttons: ["{{__('admin.no')}}", "{{__('admin.yes')}}"],
                // buttons: ["{{__('admin.ok')}}"],
                button: {
                    text: "{{__('admin.ok')}}",
                    // className: "btn btn-primary"
                }
            });
            return false;
        }
        */
        $('#joinGroupModal').modal('hide');
        let formData = getQuickRfqFormdata(groupId);
        quickRfqPostAjax(formData);
        return false;
    });

    /**
     * submit_without_group btn click on cancel join group popup => post new rfq without join group
     * created by ronak bhabhor 06/05/2022
     */
    $(document).on('click', '#submit_without_group', function (e) {
        e.stopImmediatePropagation();
        e.preventDefault();
        $('#submitQuickRfqPost').prop('disabled', true);
        $('#submit_without_group').prop('disabled', true);
        let formData = getQuickRfqFormdata(0);
        quickRfqPostAjax(formData);
    });

    /**
     * fetchGroups: fetch groups by category name and product name
     * created by ronak bhabhor 06/05/2022
     */
    function fetchGroups(category_id, sub_category_id, product_id) {
        let groupDetails = [];

        let data = {'category_id': category_id, 'sub_category_id': sub_category_id, 'product_id': product_id};
        $.ajax({
            url: "{{ route('fetch-groupby-product') }}",
            data: data,
            type: 'get',
            async: false, // for store ajax response in variable
            success: function (successData) {
                groupDetails = successData;
            },
            error: function () {
                console.log('error');
            }
        });
        return groupDetails;
    }

    /**
     * quickRfqPostAjax: call quick rfq post api call
     * created by ronak bhabhor 06/05/2022
     */
    function quickRfqPostAjax(formData) {
        $('#submitQuickRfqPost').prop('disabled', true);
        $('#addtofav').addClass("disabled");
        $.ajax({
            url: "{{ route('quick-rfq-post-ajax') }}",
            data: formData,
            type: 'POST',
            contentType: false,
            processData: false,
            success: function (successData) {
                console.log('successData');
                // console.log(successData);
                new PNotify({
                    text: "{{ __('dashboard.RFQ_Sent_successfully') }}",
                    type: 'success',
                    styling: 'bootstrap3',
                    animateSpeed: 'fast',
                    delay: 1000
                });
                $(".credit-type-div").addClass('hide')
                $('#show_change_cat_validation').attr('data-value', false);
                $('#quickrfqPost').parsley().reset();
                $('input[name="phone_code"]').val({{ $phoneCode }}).attr('iso2', '{{$country}}');
                iti.setCountry('{{$country}}');
                $("#quickrfqPost #product_category").val("{{ __('dashboard.select_product_category') }}").trigger('change');
                $('#product_name_change').html('Product');
                $('#cloneProduct').html('');
                $('#quickrfqPost #product_sub_category').empty();
                var options = '<option selected disabled>{{ __('dashboard.Select Product Sub Category') }}</option>';
                $('#quickrfqPost #product_sub_category').empty().append(options);
                $('#myRfqCount').html(successData.rfqCount);
                $('#myGroupsCount').html(successData.groupJoinedCount);
                $('#myAddressCount').html(successData.userAddressCount);
                $('#myrfnCount').html(successData.rfnCount);
                $('#file-rfq_attachment_doc').html('');
                $(".addtofavourite").removeClass("fa-star").addClass("fa-star-o");
                $(".addtofav").removeClass("btn-danger").addClass("btn-outline-danger");
                $('#is_favourite').val(0);
                $('#isRepeatRfq').val(0);
                $('#isRepeatRfqId').val('');
                $('.addtofav').removeClass('disabled');
                $('#quickrfqPost')[0].reset();
                $('#useraddress_id').val(1).trigger('change');
                window.localStorage.clear();
                removeOrAddValidation(true)
                $('#rfq_table tbody').html('');
                $('#rfq_table > tbody:last-child').append(
                    '<tr id="no_data">'
                    + '<td>{{ __("admin.no_product_added") }}</td>'
                    + '</tr>');

                $('#show_change_cat_validation').attr('data-value', true);
                $('#five_ptoduct_validation_msg').html('');
                $('#submitQuickRfqPost').prop('disabled', false);
                $('#submit_without_group').prop('disabled', false);
                $('#file-termsconditions_file').html('');
                $('#file-termsconditions_file_repeat').hide().removeClass('d-flex');
                $('#file-termsconditions_file_exist').show().addClass('d-flex');
                //Set default address
                var defaultAddress = "{{isset($rfqs) ? "" : $defaultAddressId}}";
                $("#useraddress_id").val((defaultAddress != '') ? defaultAddress : -1).trigger("change");
                //$('#useraddress_id').select2('destroy');
                //$('#useraddress_id').val("{//{$defaultAddressId}}").select2()


                let selected_addr_option = $('#useraddress_id option:selected');

                bindAddressFields(selected_addr_option);
                LOU.startTour('008443071072')
                return false;
            },
            error: function () {
                console.log('error');
                $('#submitQuickRfqPost').prop('disabled', false);
            }
        });
    }

    // alert('hey');
    // window.location.reload();
    /**
     * getQuickRfqFormdata: get quick rfq form data
     * created by ronak bhabhor 06/05/2022
     */
    function getQuickRfqFormdata(join_group) {
        var formData = new FormData($('#quickrfqPost')[0]);
        var product = localStorage.getItem("product") || [];
        var need_rental_forklift = 0;
        var need_unloading_services = 0;
        if ($('#quickrfqPost #need_rental_forklift').prop("checked")) {
            need_rental_forklift = 1;
        }
        if ($('#quickrfqPost #need_unloading_services').prop("checked")) {
            need_unloading_services = 1;
        }
        formData.append("_token", "{{ csrf_token()}}");
        if ($("#quickrfqPost #prod_cat_post").find(':selected').attr('data-category-id') == 0) {
            formData.append("category", $("#quickrfqPost #othercategory").val());
        } else {
            formData.append('category', $('#quickrfqPost #prod_cat_post option:selected').attr(
                'data-category'))
        }

        if ($("#quickrfqPost #prod_sub_cat_post").find(':selected').attr('data-sub-category-id') == 0) {
            if ($("#quickrfqPost #othersubcategory").val() != undefined && $("#quickrfqPost #othersubcategory").val().length > 0) {
                formData.append("subcategory", $("#quickrfqPost #othersubcategory").val());
            } else {
                formData.append("subcategory", 'Other');
            }
        } else {
            formData.append('subcategory', $('#quickrfqPost #prod_sub_cat_post option:selected').attr('data-text'))
        }

        formData.append('rental_forklift', need_rental_forklift);
        formData.append('unloading_services', need_unloading_services);
        formData.append('is_require_credit', $('#creditSwitchCheckDefault').prop('checked') ? 1 : 0);
        formData.append('groupId', join_group);
        formData.append("product_details", product);


         if ($("#quickrfqPost #product_category").find(':selected').attr('data-category-id') == 0) {
            formData.append("category", 'Other');
        } else {
            formData.append('category', $('#quickrfqPost #product_category option:selected').attr('data-category'))
        }
      //  formData.append('category', $('#quickrfqPost #product_category option:selected').attr('data-category'))


        return formData;
    }

    /**
     * getGroupListHtml: get html of group list
     * created by ronak bhabhor 06/05/2022
     */
    function getGroupListHtml(data) {
        let daysRemainingTrans = "{{ __('dashboard.days_remaining')}}";
        let viewDetailsTrans = "{{ __('dashboard.view_details')}}";
        let targetQuantityTrans = "{{ __('dashboard.target_quantity')}}";
        let achievedQuantityTrans = "{{ __('admin.achieved_quantity')}}";
        let expDateTrans = "{{ __('dashboard.exp_date')}}";
        let shareTrans = "{{ __('dashboard.share')}}";
        let joinGroupTrans = "{{ __('dashboard.join_group')}}";
        let companiesTrans = "{{ __('dashboard.companies')}}";
        let offTrans = "{{ __('dashboard.off')}}";
        let exclamationCircleImg = "{{ URL::asset('front-assets/images/icons/exclamation-circle.png') }}";
        let balancescaleImg = "{{ URL::asset('front-assets/images/icons/balance-scale-right.png')}}";
        let buildingImg = "{{ URL::asset('front-assets/images/icons/building_(2).png') }}";
        let calenderImg = "{{ URL::asset('front-assets/images/icons/Calendar-alt.png') }}";
        let layerGrpImg = "{{ URL::asset('front-assets/images/icons/layer-group.png') }}";
        let shareOneImg = "{{ URL::asset('front-assets/images/icons/icon_share_1.png')}}";

        let html = '';
        $.each(data, function (key, value) {
            //console.log(value);
            let groupImg = '';
            if (value.groupImg) {
                // let groupSrc = "{{url('/storage')}}"+'/'+value.groupImg;
                let groupSrc = "{{url('/storage/')}}" + '/' + value.groupImg;
                groupImg = '<img src="' + groupSrc + '" loading="lazy" class="card-img-top" alt="' + value.name + '">';
            } else {
                let groupDefaultImg = "{{ URL::asset('front-assets/images/no_group_img.jpg') }}";
                groupImg = '<img src="' + groupDefaultImg + '" loading="lazy" class="card-img-top" alt="...">';
            }
            html += '<div class="col-md-6 col-lg-6 mb-3"><div class="card blank-link"><div class="position-relative hoversection gbhoverimg"><a href="' + value.group_details_link + '" class="stretched-link" target="_blank"></a><div class="ratio ratio-21x9">' + groupImg + '</div>';
            html += '<div class=" "><div class="p-1 time_remain d-flex align-items-center"><img src="' + exclamationCircleImg + '" alt="" class="me-1" height="14px"><span class="tag_icon">' + value.remaining_days + ' ' + daysRemainingTrans + '</span></div></div><div class="d-inline-block p-1 discount">' + value.max_discount + '% ' + offTrans + '<sup style="font-size: 5px">*</sup></div><div class="viewmore_Sec d-flex align-items-center justify-content-center"><span>' + viewDetailsTrans + '</span></div></div>';
            html += '<div class="card-body"><div class="d-flex"><h6 class="card-title dark_blue w-75 mb-0" style="font-size: 20px;"><a href="' + value.share_group_link + '" target="_blank" class="text-truncate text-decoration-none d-block">' + value.name + '</a></h6></div> <h6>' + value.productName + '</h6> <h6 class="card-title text-truncate" style="font-size: .8rem;" data-bs-toggle="tooltip" data-bs-placement="top" title="' + value.product_description + '">' + value.product_description + '</h6>  <div class="card-text bg-white" style="font-size: 13px;"><div class="row"><div class="col-lg-6"> <div class="pb-2 d-flex "><div class="col-auto me-1"><img src="' + balancescaleImg + '" alt="" height="14px"></div> <div class="col-auto"><span class="fw-bold">' + value.target_quantity + ' ' + value.unit + ' </span><br><span class="text-muted fsize_10">' + targetQuantityTrans + '</span> </div> </div><div class="pb-2 d-flex"><div class="col-auto me-1"><img src="' + buildingImg + '" alt="" height="14px"></div><div class="col-auto"><span class="fw-bold"> ' + value.no_of_company + ' ' + companiesTrans + '</span> </div></div></div><div class="col-lg-6 card-accent-left"><div class="pb-2 d-flex"><div class="col-auto me-1"><img src="' + balancescaleImg + '" alt="" height="14px"></div><div class="col-auto"><span class="fw-bold">' + value.achieved_quantity + ' ' + value.unit + '</span><br><span class="text-muted fsize_10">' + achievedQuantityTrans + '</span></div></div><div class="pb-2 d-flex"><div class="col-auto me-1"><img src="' + calenderImg + '" alt="" height="14px"></div><div class=""><span class="fw-bold"> ' + expDateTrans + ': ' + value.formated_end_date + '</span></div></div></div></div></div></div>';
            html += '<div class="card-footer d-flex justify-content-center bg-transparent"><a href="javascript:void(0);"  class="btn btn-primary btn-sm me-2 join_group_btn" data-group_id="' + value.id + '" data-group_achieved_quntity="' + value.achieved_quantity + '" data-group_target_quantity="' + value.target_quantity + '" data-unit="' + value.unit + '"><img src="' + layerGrpImg + '" alt=""> ' + joinGroupTrans + '</a> <a href="javascript:void(0);" class="btn btn-warning btn-sm share_anc tooltipshare" data-share_group_link="' + value.share_group_link + '" data-socilite> <img src="' + shareOneImg + '" alt=""> ' + shareTrans + '</a> <div style="display:none" class="share_group_div">' + value.share_group_popup_html + '</div> </div></div></div>';
        });
        return html;
    }

    $(document).ready(function () {
        @if(isset($rfqs))
        window.localStorage.setItem('product', JSON.stringify(product));
        addedProduct = product;
        @php
            $phoneCode = $rfqs->phone_code?str_replace('+','',$rfqs->phone_code):62;
            $countryCode = $rfqs->phone_code?strtolower(getRecordsByCondition('countries',['phone_code'=>$phoneCode],'iso2',1)):'id';
        @endphp
        $('input[name="phone_code"]').val('{{$phoneCode}}');
        iti.setCountry('{{$countryCode}}');

        var categoryID = $("#product_category").find("option:selected").attr('data-category-id');
        var rfqname = $("#product_category").find("option:selected").attr('data-rfqname');
        var onload = true;
        $('#category_id').val(categoryID);
        cat_ajax(categoryID);
        if (product.length == max_product) {
            disableAllFields(true);
        }
        if (product.length > 0 || product.length >= max_product) {
            removeOrAddValidation(false);
        }
        @endif
        $('#product_category').select2({
            dropdownParent: $('#product_category_block'),
        });

        $('#product_category').on('select2:selecting', function (evt) {
            last_category = $('#product_category').val();
        });

        //$('#product_sub_category').select2();
        $('#expected_date').datepicker({
            onSelect: function (date) {
                $('#expected_date').parsley().reset();
            },
            dateFormat: "dd-mm-yy",
            minDate: "+1d"
            // appendText: "dd-mm-yyyy"
        });
        @if(!isset($rfqs))
        localStorage.clear();
        @endif

        $(document).on('click', '#goToFullFormBtn', function (e) {
            e.stopImmediatePropagation();
            e.preventDefault();
            localStorage.clear();
            localStorage.setItem('category', $('#quickrfqPost #product_category option:selected').attr('data-category-id'));
           // localStorage.setItem('other_category', $('#quickrfqPost #other_category').val());
            localStorage.setItem('sub_category', $('#quickrfqPost #product_sub_category option:selected').attr('data-sub-category-id'));
            //localStorage.setItem('other_subcategory', $('#quickrfqPost #other_subcategory').val());
            localStorage.setItem('product', $('#quickrfqPost #product_name').val());
            localStorage.setItem('product_description', $('#quickrfqPost #product_description').val());
            localStorage.setItem('quantity', $('#quickrfqPost #quantity').val());
            localStorage.setItem('unit', $('#quickrfqPost #unit').val());
            localStorage.setItem('pincode', $('#quickrfqPost #pincode').val());
            localStorage.setItem('firstname', $('#quickrfqPost #firstname').val());
            localStorage.setItem('lastname', $('#quickrfqPost #lastname').val());
            localStorage.setItem('email', $('#quickrfqPost #email').val());
            localStorage.setItem('phone_code', $('#quickrfqPost input[name="phone_code"]').val());
            localStorage.setItem('iso2', $('#quickrfqPost input[name="phone_code"]').attr('iso2'));
            localStorage.setItem('mob_number', $('#quickrfqPost #mob_number_post').val());
            localStorage.setItem('requirecredit', $('#quickrfqPost #creditSwitchCheckDefault').prop('checked') ? 1 : 0);
            localStorage.setItem('comment', $('#quickrfqPost #comment').val());
            localStorage.setItem('expected_date', $('#quickrfqPost #expected_date').val());

            var need_rental_forklift = 0;
            if ($('#quickrfqPost #need_rental_forklift').prop("checked")) {
                need_rental_forklift = 1;
            }
            localStorage.setItem('need_rental_forklift', need_rental_forklift);

            var need_unloading_services = 0;
            if ($('#quickrfqPost #need_unloading_services').prop("checked")) {
                need_unloading_services = 1;
            }
            localStorage.setItem('need_unloading_services', need_unloading_services);


            var url = "{{ route('get-a-quote') }}";
            window.location.replace(url);
        });

        $(document).on('click', '#submitQuickRfqPost', function (e) {
            e.stopImmediatePropagation();
            e.preventDefault();
            var categorySelected = $("#quickrfqPost #product_category").find(':selected').attr('data-category-id');

            var subCategorySelected = $("#quickrfqPost #product_sub_category").val();
            $('#quickrfqPost #productCategoryOtherDiv .error').remove()
            $('#quickrfqPost #productSubCategoryOtherDiv .error').remove();
            var formValidate = true;
           /* if (categorySelected == 0 && subCategorySelected != null) {

                var othercategory = $('#quickrfqPost #other_category').val();
                if (othercategory.trim().length == 0) {
                    $('#quickrfqPost #other_category').after('<p class="error">This value is required.</p>');
                    formValidate = false;
                } else {
                    $('#quickrfqPost #productCategoryOtherDiv .error').remove();
                }
            }*/

            /** Validation remove of state and city while onchnage - start **/
            SnippetRFQDeliveryDetailAddress.parsleyValidationRemoveForStateCity();
            /** Validation remove of state and city while onchnage - end **/
            if ($('#quickrfqPost').parsley().validate() && formValidate) {
                if ($('#creditSwitchCheckDefault').prop('checked')) {
                    if ($('#credit_days_id').val() == 0) {
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
                if (emailflag == 0) {
                    $('.js-email').addClass('show');
                    $('.js-email').removeClass('hide');
                    topFunction();
                    swal({
                        title: "",
                        text: "{{ __('profile.mailvarify') }}",
                        icon: "/assets/images/warn.png",
                        buttons: '{{ __('admin.ok') }}',
                        dangerMode: true,
                    });
                    return false;
                }
                //Show popup if product is not added in json
                var product = JSON.parse(localStorage.getItem("product")) || [];

                if (product.length == 0) {
                    swal({
                        title: "",
                        text: "{{ __('dashboard.first_add_product') }}",
                        icon: "/assets/images/warn.png",
                        buttons: '{{ __('admin.ok') }}',
                        dangerMode: true,
                    });
                    return false;
                }


                if (($("input[name='is_preferred_supplier']:checked").val() == 1) && ($("#preferredSuppliersCounts").val() > 0)) {
                    // If no supplier checkbox is selected, then show below message
                    if ($('input:checkbox[name=supplier_chk]').filter(':checked').length == 0) {
                        $("#status").text("({{ __('profile.atleast_one_checkbox_checked') }})");
                        $('#submitQuickRfqPost').prop('disabled', true);
                        return false;
                    }
                    ;

                    // Get selected preferred supplier ids (Ronak M - 30/06/2022)

                    suppliersArray = [];
                    $("input:checkbox[name=supplier_chk]:checked").each(function () {
                        suppliersArray.push($(this).val());
                    });
                    $("#preferredSuppliersIds").val(suppliersArray.join(", "));
                    $("#collapseExample").removeClass("show");
                    $("#showPreferredSuppliers").attr("aria-expanded", "false");
                    //End
                }

                if (addedProduct.length == 1 && $('#creditSwitchCheckDefault').prop('checked') == false) {
                    let category_id = $('#product_category').find(':selected').attr('data-category-id');
                    let sub_category_id = addedProduct[0].product_sub_category_id;
                    let product_id = addedProduct[0].product_id;
                    let allPreferredSupplier = $(".secondBtn").is(":checked");
                    let isBuyerJoinPermission = false;
                    if ("{{Auth::check()}}" == true && "{{Auth::user()->hasPermissionTo('create buyer join group')}}" == true) {
                        isBuyerJoinPermission = true;
                    }

                    if (isBuyerJoinPermission == true && (category_id != null || category_id != 0) && (sub_category_id != null || sub_category_id != 0) && (product_id != null || product_id != 0) && allPreferredSupplier == false) {
                        let groupData = fetchGroups(category_id, sub_category_id, product_id);
                        if (groupData.length) {
                            let groupListHtml = getGroupListHtml(groupData);
                            $('#groupListMainDiv').html(groupListHtml);
                            $('#joinGroupModal').modal('show');
                            return false;
                        }
                    }
                    let formData = getQuickRfqFormdata(0);
                    quickRfqPostAjax(formData);
                    return false;
                } else {
                    let formData = getQuickRfqFormdata(0);
                    quickRfqPostAjax(formData);
                    return false;
                }
                return false;
            }
        });

        $(document).on('mousedown', '.searchProduct', function (e) {
            var err = '<ul class="parsley-errors-list" id="parsley-id-17" aria-hidden="true"><li class="parsley-required"></li></ul>'
            $('#product_name_error').html(err);
            var txt = $(this).parent().attr('id');
            var id = txt.replace('productSearchResult', '');
            $('#quickrfqPost #product_name' + id).val($(this).attr('data-value'));
            $('#quickrfqPost #product_name' + id).attr('data-id', $(this).attr('data-id'));
            $('#quickrfqPost #product_id' + id).val($(this).attr('data-id'))
            $('#quickrfqPost #productSearchResult' + id).addClass('hidden');
            if ($(this).attr('data-sub-cat-id')) {
                //$('#quickrfqPost #product_sub_category').attr('data-sub-cat-id', $(this).attr('data-sub-cat-id'));
                //var selectedSubCatValue = $('#quickrfqPost #product_sub_category').find('option[data-sub-category-id="' + $(this).attr('data-sub-cat-id') + '"]').val();
                //$('#quickrfqPost #product_sub_category').val(selectedSubCatValue).trigger('change');
                $('#quickrfqPost #product_name_change' + id).html('Product : ' + $(this).attr('data-value'));
                //$('#quickrfqPost #other_subcategory').val('');
            }
        });

        $(document).on('focusout', '.product_name', function (e) {
            var text = this.id;
            var id = text.replace('product_name', '');
            if (id != '') {
                $('#quickrfqPost #productSearchResult' + id).addClass('hidden');
            } else {
                $('#quickrfqPost #productSearchResult').addClass('hidden');
            }
        });

        $(document).on('click', '.removeProduct', function () {
            swal({
                title: "{{ __('dashboard.are_you_sure') }}?",
                text: "{{ __('dashboard.delete_warning') }}",
                icon: "/assets/images/bin.png",
                buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
                dangerMode: true,
            }).then((willChange) => {
                if (willChange) {
                    $(this).closest('#accordion_item_clone').remove();
                    if ($.trim($("#accordion_item_clone").html()) == "") {
                        $('#no_charge').show();
                    } else {
                        $('#no_charge').hide();
                    }
                }
            });
        });

    });

    /*****begin: Post a requirement Delivery Address******/
    var SnippetRFQDeliveryDetailAddress = function () {

        var selectStateGetCity = function () {

                $('#stateId').on('change', function () {

                    let state = $(this).val();
                    let targetUrl = "{{ route('admin.city.by.state',":id") }}";
                    targetUrl = targetUrl.replace(':id', state);
                    var newOption = '';

                    // Add Remove Other State filed
                    if (state == -1) {
                        $('#stateId_block_main').removeClass('col-md-6');
                        $('#stateId_block_main').addClass('col-md-3');

                        $('#state_block').removeClass('hide');
                        $('#state').attr('required', 'required');

                        $('#cityId_block_main').removeClass('col-md-4');
                        $('#cityId_block_main').addClass('col-md-3');

                        $('#cityId').empty();

                        //set default options on other state mode
                        newOption = new Option('{{ __('admin.select_city') }}', '', true, true);
                        $('#cityId').append(newOption).trigger('change');

                        newOption = new Option('Other', '-1', true, true);
                        $('#cityId').append(newOption).trigger('change');


                    } else {
                        $('#stateId_block_main').removeClass('col-md-3');
                        $('#stateId_block_main').addClass('col-md-6');

                        $('#state_block').addClass('hide');
                        $('#state').removeAttr('required', 'required');

                        $('#city_block').addClass('hide');
                        $('#city').removeAttr('required', 'required');

                        //Fetch cities by state
                        if (state != '') {
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

                                            //Get city by selected address
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

                            $('#cityId').val(null).trigger('change');
                        }

                    }

                });

            },

            selectCitySetOtherCity = function () {

                $('#cityId').on('change', function () {

                    let city = $(this).val();

                    // Add Remove Other City filed
                    if (city == -1) {
                        $('#cityId_block_main').removeClass('col-md-6');
                        $('#cityId_block_main').addClass('col-md-3');

                        $('#city_block').removeClass('hide');
                        $('#city').attr('required', 'required');

                        $('#stateId_block_main').removeClass('col-md-6');
                        if ($('#stateId').val() > 0) {
                            $('#stateId_block_main').addClass('col-md-6');
                        } else {
                            $('#stateId_block_main').addClass('col-md-3');
                        }

                    } else {
                        $('#cityId_block_main').removeClass('col-md-3');
                        $('#cityId_block_main').addClass('col-md-6');

                        $('#city_block').addClass('hide');
                        $('#city').removeAttr('required', 'required');

                    }

                });

            },

            initiateCityState = function () {

                let state = $('#state').val();
                let selectedState = $('#stateId').val();
                let selectedCity = $("#cityId").attr('data-selected-city');

                if (state != null && state != '') {
                    $('#stateId').val('-1').trigger('change');
                }

                if (selectedState != '' && selectedState != null) {
                    $('#stateId').val(selectedState).trigger('change');
                }

            },
            select2Initiate = function () {

                $('#stateId').select2({
                    dropdownParent: $('#stateId_block_main'),
                    placeholder: $(this).attr('data-placeholder')

                });

                $('#cityId').select2({
                    dropdownParent: $('#cityId_block_main'),
                    placeholder: $(this).attr('data-placeholder')

                });

            },
            enableAddress = function () {
                $('#address_name').attr('readonly', false);
                $('#addressLine1').attr('readonly', false);
                $('#addressLine2').attr('readonly', false);
                $('#sub_district').attr('readonly', false);
                $('#district').attr('readonly', false);
                $('#stateId').attr('readonly', false);
                $('#cityId').attr('readonly', false);
                $('#state').attr('readonly', false);
                $('#city').attr('readonly', false);
                $('#pincode').attr('readonly', false);
            },
            isAddressBelongs = function (addressId) {
                $.ajax({
                    url: "{{route('address.belongs')}}",
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    type: 'POST',
                    data: {
                        id: addressId
                    },
                    success: function (data) {
                        if (!data.success) {
                            $('#address_name').attr('readonly', true);
                            $('#addressLine1').attr('readonly', true);
                            $('#addressLine2').attr('readonly', true);
                            $('#sub_district').attr('readonly', true);
                            $('#district').attr('readonly', true);
                            $('#stateId').attr('readonly', true);
                            $('#cityId').attr('readonly', true);
                            $('#state').attr('readonly', true);
                            $('#city').attr('readonly', true);
                            $('#pincode').attr('readonly', true);
                        } else {
                            enableAddress();
                        }
                    },
                    error: function () {
                    }
                });

            },

            checkAddressBelongsTo = function () {
                $('#useraddress_id').on('change', function () {
                    let addressId = $('#useraddress_id').val();

                    if (addressId != "Other") {
                        isAddressBelongs(addressId);
                    } else {
                        enableAddress();
                    }
                });

            }

        return {
            init: function () {
                selectStateGetCity(),
                    selectCitySetOtherCity(),
                    initiateCityState(),
                    select2Initiate(),
                    checkAddressBelongsTo()
            },
            parsleyValidationRemoveForStateCity: function () {
                window.Parsley.on('form:validated', function () {
                    $('select').on('select2:select', function (evt) {
                        $("#stateId").parsley().validate();
                        //$("#cityId").parsley().validate();
                    });
                });
            }
        }

    }(1);
    jQuery(document).ready(function () {
        SnippetRFQDeliveryDetailAddress.init();
    });
    /*****end: Post a requirement Delivery Address******/
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
    $(document).on("click", ".removeFile", function (e) {
        var id = $(this).attr('id');
        if (id == 'rfq_attachment_docFile') {
            $("#file-rfq_attachment_doc").html('');
            $("#rfq_attachment_doc").val('');
        }
        if (id == 'termsconditions_fileFile') {
            $("#file-termsconditions_file").html('');
            $("#termsconditions_file").val('');
        }

    });
    $('#quantity').on('keypress keyup keydown', function (evt) {
        if (evt.which == 69) {
            evt.preventDefault();
        }
    });

    $("#creditSwitchCheckDefault").on('change', function () {
        $(".credit-type-div").toggleClass('hide')
    })


    /** make rfq favourite or not while posting new RFQ
     *   Vrutika - 14/11/2022
     */
    $('.addtofav').on('click', function () {
        $(".addtofavourite").toggleClass("fa-star-o fa-star");
        $(".addtofav").toggleClass("btn-outline-danger btn-danger");
        let isFavourite = $('#is_favourite').val();
        var title;

        if (isFavourite == 0) {
            title = '{{__('dashboard.remove_favourite')}}';
            $('#is_favourite').val('1');
            $('.addtofav').attr("title", title)
        } else {
            title = '{{__('dashboard.add_favourite')}}';
            $('#is_favourite').val('0');
            $('.addtofav').attr("title", title)
        }
    });


    /** make rfq favourite or not
     *   Vrutika - 18/11/2022
     */
    $(document).on('click', '.addtofavRfq', function () {
        var rfqId = $(this).attr('data-rfq_id');
        if (rfqId) {
            var isFavouriteRfq = $('#is_favouriteRfq_' + rfqId).val();
            var title = isFavouriteRfq == 0 ? '{{__('dashboard.add_favourite_alert')}}' : '{{__('dashboard.remove_favourite_alert')}}';
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
                            data: {'rfqId': rfqId, 'isFavouriteRfq': isFavouriteRfq},
                            type: 'POST',
                            responseType: 'json',
                            success: function (successData) {
                                if (successData.success == true) {
                                    $(".addtofavouriteRfq_" + rfqId).toggleClass("fa-star-o fa-star");
                                    $(".favorite_" + rfqId).toggleClass("btn-outline-danger btn-danger");
                                    if (isFavouriteRfq == 0) {
                                        title = '{{__('dashboard.remove_favourite')}}';
                                        $('#is_favouriteRfq_' + rfqId).val('1');
                                        $('#showFavourite_' + rfqId).removeClass('d-none');
                                        $('.favorite_' + rfqId).attr("title", title);
                                    } else {
                                        title = '{{__('dashboard.add_favourite')}}';
                                        $('#is_favouriteRfq_' + rfqId).val('0');
                                        $('#showFavourite_' + rfqId).addClass('d-none');
                                        $('.favorite_' + rfqId).attr("title", title);
                                        if (localStorage.getItem("dashboard-is_favouriteRfq") == 1) {
                                            $('#rfqSection').trigger('click');
                                        }
                                    }

                                }
                            }
                        });
                    } else {
                        return false;
                    }
                });
        } else {
            var isFavouriteRfq = $('#is_favouriteRfq_0').val();
            $(".addtofavouriteRfq_0").toggleClass("fa-star-o fa-star");
            $(".favorite_0").toggleClass("btn-outline-danger btn-danger");
            if (isFavouriteRfq == 1) {
                localStorage.setItem("dashboard-is_favouriteRfq", '0');
            } else {
                localStorage.setItem("dashboard-is_favouriteRfq", '1');
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
        isRepeatOrder == 1 ? title = '{{__('dashboard.repeat_order_alert')}}' : title = '{{__('dashboard.repeat_rfq_alert')}}';
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
                    localStorage.setItem("dashboard-repeatRfqId", repeatRfqId);
                    localStorage.setItem("dashboard-isRepeatRfq", '1');
                    $('#postRequirementSection').trigger('click');
                } else {
                    return false;
                }
            });
    });

    $('.addtofavRepeat').on('click', function () {
        $(".addtofavouriteRepeat").toggleClass("fa-star-o fa-star");
        $(".addtofavRepeat").toggleClass("btn-outline-danger btn-danger");
        let isRepeatFavourite = $('#isRepeatFavouriteRfq').val();
        if (isRepeatFavourite == 0) {
            $('#isRepeatFavouriteRfq').val('1');
        } else {
            $('#isRepeatFavouriteRfq').val('0');
        }
        getRepeatRfqList();
    });

    /** download single attachment
     * *   Vrutika - 24/11/2022
     * */
    function downloadattach(rfq_id, fieldName, name) {
        event.preventDefault();
        var data = {
            rfq_id: rfq_id,
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

    /** Select rfq to repeat from RFQ list
     * *   Vrutika - 24/11/2022
     * */
    $('#filterRFQsearch').keypress(function (e) {
        if (e.keyCode == 13) {
            getRepeatRfqList();       //Trigger search button click event
        }
    });
    $('input[type=search]').on('search', function () {
        getRepeatRfqList();
    });

    function getRepeatRfqList() {
        let isFavourite = $('#isRepeatFavouriteRfq').val();
        var categoryId = $("#repeatRfqList #category option:selected").attr('data-category-id');
        var subCategoryId = $("#repeatRfqList #subcategory option:selected").attr('data-sub-category-id');
        var productId = $("#repeatRfqList #product option:selected").attr('data-product-id');
        let searchText = $('#filterRFQsearch').val();
        isFavourite == 1 ? 1 : '';
        var data = {
            productId: productId,
            subCategoryId: subCategoryId,
            categoryId: categoryId,
            isFavourite: isFavourite,
            searchText: searchText
        }
        $.ajax({
            url: "{{ route('dashboard-get-repeat-rfq-list-ajax') }}",
            type: 'GET',
            data: data,
            success: function (successData) {
                if (successData.html) {
                    $('#repeatRfqMainDiv').html(successData.html);
                    $('#RepeatrfqModal').modal('show');
                    if (isFavourite == 1) {
                        $('#no_rfq_alert').html('{{ __('rfqs.No_favourite_rfq_found') }}');
                    } else {
                        $('#no_rfq_alert').html('{{ __('rfqs.No_rfq_found') }}')
                    }
                }
            },
            error: function () {
                console.log('error');
            }
        });

    }

    function getProductsBySubCategory(subcat_id) {

        var categoryId = $("#repeatRfqList #category option:selected").attr('data-category-id');
        var subCategoryId = $("#repeatRfqList #subcategory option:selected").attr('data-sub-category-id');
        var productId = $("#repeatRfqList #product option:selected").val();
        var data = {
            productId: productId,
            subCategoryId: subCategoryId,
            categoryId: categoryId,
            _token: $('meta[name="csrf-token"]').attr('content')
        }

        if (categoryId && categoryId != 0) {
            $.ajax({
                url: "{{ route('search-product-ajax') }}",
                type: 'POST',
                data: data,
                success: function (successData) {
                    var options = '<option selected disabled>{{ __('admin.select_product_name') }}</option>';
                    if (successData.filterData.length) {
                        successData.filterData.forEach(function (data) {
                            options += '<option data-product-id="' + data.id + '" value="' + data.id + '" data-text="' + data.name + '">' + data.name + '</option>';
                        });
                    }
                    $('#repeatRfqList #product').empty().append(options);
                },
                error: function () {
                    console.log('error');
                }
            });
        }

    }

    function clearRepeatRfqModel() {
        closeAndClearRepeatRfq();
        getRepeatRfqList();
    }

    function closeRepeatRfqModel() {
        $(".addtofavouriteRepeat").removeClass("fa-star").addClass("fa-star-o");
        $(".addtofavRepeat").removeClass("btn-danger").addClass("btn-outline-danger");
        $('#RepeatrfqModal').modal('hide');
        $('#isRepeatFavouriteRfq').val('0');
        closeAndClearRepeatRfq()
    }

    function closeAndClearRepeatRfq() {
        $('#repeatRfqList')[0].reset();
        $('#filterRFQsearch').val('');
        var options = '<option selected disabled>{{ __('dashboard.Select Product Sub Category') }}</option>';
        $('#repeatRfqList #subcategory').empty().append(options);
        var optionsProduct = '<option selected disabled>{{ __('admin.select_product_name') }}</option>';
        $('#repeatRfqList #product').empty().append(optionsProduct);
    }
    /****************************************end:Buyer Backend Sidebar***************************************/
</script>
<style>
    .ui-autocomplete {
        max-height: 100px;
        overflow-y: auto;
        /* prevent horizontal scrollbar */
        overflow-x: hidden;
    }
    </style>
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
                        $('#quickrfqPost #product_name').removeClass('blink');
                        $('#quickrfqPost #product_sub_category').removeClass('blink');
                        $("#quickrfqPost #select2-product_category-container").removeClass('blink');
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
                var product = JSON.parse(localStorage.getItem("product")) || [];

                const servicesCategoryIds = {{ json_encode(\App\Models\Category::SERVICES_CATEGORY_IDS) }};
                let categoryIsService = false;
                for (i=0;i<servicesCategoryIds.length;i++){
                    if(servicesCategoryIds[i] == categoryId){
                        categoryIsService = true;
                    }
                }
                if (categoryIsService) {
                    $('#quickrfqPost #rfq_address').html('{{ __('dashboard.pickup_details') }}');
                    $('#quickrfqPost #product_description').attr('placeholder', '{{ __('dashboard.Service_Product_Description_Placeholder') }}');
                } else {
                    $('#quickrfqPost #rfq_address').html('{{ __('rfqs.Delivery_Details') }}');
                    $('#quickrfqPost #product_description').attr('placeholder', '{{ __('dashboard.Product_Description_Placeholder') }}');
                }

                if (product.length != 0 && categoryName != $('#quickrfqPost #select2-product_category-container').html() && $('#quickrfqPost #product_category').val().toLowerCase() != 'other') {
                    var validates = $('#show_change_cat_validation').attr('data-value');
                    if (validates === 'true') {
                        if (categoryName != $('#quickrfqPost #select2-product_category-container').html()) {

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
                                        $('#quickrfqPost #product_category').attr('value', $.trim(prodcutArray[0]));
                                        $("#quickrfqPost #select2-product_category-container").attr('title', $.trim(prodcutArray[0]));
                                        $('#quickrfqPost #product_category option[value="' + $.trim(prodcutArray[0]) + '"]').attr('selected', 'selected');
                                        $('#quickrfqPost #product_name').val($.trim(prodcutArray[2]));
                                        $('#quickrfqPost #select2-product_category-container').val($.trim(prodcutArray[0]));
                                        $('#quickrfqPost #select2-product_category-container').html($.trim(prodcutArray[0]));
                                    } else {
                                        $("#quickrfqPost #select2-product_category-container").attr('title', categoryName);
                                        $('#quickrfqPost #product_category option[value="' + categoryName + '"]').attr('selected', 'selected');
                                        $('#quickrfqPost #product_name').val(product_name);
                                        $('#quickrfqPost #product_category').attr('value', categoryName);
                                        $('#quickrfqPost #select2-product_category-container').val(categoryName);
                                        $('#quickrfqPost #select2-product_category-container').html(categoryName);
                                    }
                                    cat_ajax(ui.item.categoryId,ui.item.subcategoryId);
                                    changeSubCategory(ui.item.subcategoryId,$.trim(prodcutArray[2]));
                                    $("#quickrfqPost #tags").val('');
                                    $("#quickrfqPost #category_id").val(ui.item.categoryId);
                                    $("#quickrfqPost #product_sub_category_id").val(ui.item.subcategoryId);
                                    $("#quickrfqPost #product_id").val(ui.item.productId);
                                    $('#quickrfqPost #product_sub_category option[value="'+ui.item.subcategoryName+'"]').attr('selected','selected');
                                    $('#quickrfqPost #product_category option[value="'+categoryName+'"]').attr('selected','selected');
                                    $('#quickrfqPost #product_name').addClass('blink');
                                    $('#quickrfqPost #product_sub_category').addClass('blink');
                                    $("#quickrfqPost #select2-product_category-container").addClass('blink');
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
                }
                else {

                    if ($.trim(prodcutArray[0]).toLowerCase() === 'other') {
                        $('#quickrfqPost #product_category').attr('value', $.trim(prodcutArray[0]));
                        $("#quickrfqPost #select2-product_category-container").attr('title', $.trim(prodcutArray[0]));
                        $('#quickrfqPost #product_category option[value="' + $.trim(prodcutArray[0]) + '"]').attr('selected', 'selected');
                        if ($.trim(prodcutArray[2]) != 'undefined') {
                            $('#quickrfqPost #product_name').val($.trim(prodcutArray[2]));
                        } else {
                            $('#quickrfqPost #product_name').val('');
                        }
                        $('#quickrfqPost #select2-product_category-container').val($.trim(prodcutArray[0]));
                        $('#quickrfqPost #select2-product_category-container').html($.trim(prodcutArray[0]));
                        changeSubCategory(ui.item.subcategoryId,$.trim(prodcutArray[2]));
                    } else {
                        changeSubCategory(ui.item.subcategoryId,product_name);
                        $("#quickrfqPost #select2-product_category-container").attr('title', categoryName);
                        $('#quickrfqPost #product_category option[value="' + categoryName + '"]').attr('selected', 'selected');
                        $('#quickrfqPost #product_name').val(product_name);
                        $('#quickrfqPost #product_category').attr('value', categoryName);
                        $('#quickrfqPost #select2-product_category-container').val(categoryName);
                        $('#quickrfqPost #select2-product_category-container').html(categoryName);
                    }
                    //alert(product_name);
                    cat_ajax(ui.item.categoryId,ui.item.subcategoryId);
                    //changeSubCategory(ui.item.subcategoryId,product_name);
                    $("#quickrfqPost #tags").val('');
                    $(".count").text('0 Result Found');
                    $("#quickrfqPost #category_id").val(ui.item.categoryId);
                    $("#quickrfqPost #product_sub_category_id").val(ui.item.subcategoryId);
                    $("#quickrfqPost #product_id").val(ui.item.productId);
                    $('#quickrfqPost #product_sub_category option[value="'+ui.item.subcategoryName+'"]').attr('selected','selected');
                    $('#quickrfqPost #product_category option[value="'+categoryName+'"]').attr('selected','selected');
                    $('#quickrfqPost #product_name').addClass('blink');
                    $('#quickrfqPost #product_sub_category').addClass('blink');
                    $("#quickrfqPost #select2-product_category-container").addClass('blink');

                }

                return false;
            }
        })

    });
</script>
