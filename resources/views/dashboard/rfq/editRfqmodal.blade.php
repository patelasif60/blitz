<style>
    .edit{
        --bs-table-accent-bg: var(--bs-table-hover-bg) !important;
        color: var(--bs-table-hover-color) !important;
    }

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
<!-- Edit Modal -->


            <div class="modal-header dark_blue_bg text-white">
                <h6 class="modal-title" id="editModalLabel">{{ __('rfqs.rfq_no') . ' ' . $rfq->reference_number }} </h6><span class="badge rounded-pill mt-1 {{ $rfq->status_name == 'RFQ Completed' ? 'bg-success' : 'bg-primary' }}">{{ __('rfqs.' . $rfq->status_name) }}</span>
                <button type="button" data-boolean="false" class="btn-close rounded-circle reserform" id="resetform_{{$rfq->id}}"  onclick="resetMainForm(this)" aria-label="Close"><img src="{{ URL::asset('front-assets/images/icons/close.png') }}" alt=""></button>
            </div>
            <div class="modal-body p-0">

                <ul class="nav nav-tabs mb-3 bg-info bg-opacity-10" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="edit-tab" data-bs-toggle="tab" data-bs-target="#edit"
                            type="button" role="tab" aria-controls="edit" aria-selected="true">{{ __('rfqs.edit') }}</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-rfqid="{{$rfq->id}}" id="activities-tab" data-bs-toggle="tab"
                            data-bs-target="#activities" type="button" role="tab" aria-controls="activities"
                            aria-selected="false">{{ __('rfqs.activity') }}</a>
                    </li>
                    @if($chatHistory->isNotEmpty())
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-rfqid="{{$rfq->id}}" id="chat-history-tab" data-bs-toggle="tab"
                           data-bs-target="#chat-history" type="button" role="tab" aria-controls="chat-history"
                           aria-selected="false">Chat History</a>
                    </li>
                    @endif

                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="edit" role="tabpanel" aria-labelledby="edit-tab">
                        <div class="card border-0">
                            <div class="card-body floatlables">
                                <div class="row" >

                                    <div class="col-md-12" id="fullRfqDataBlock">
                                        <form class="fullrfqForm{{$rfq->id}} error_res" name="fullrfqForm" id="fullrfqForm" data-parsley-validate autocomplete="off">
                                            @csrf
                                            <input type="hidden" value="{{$rfq->id}}" name="rfq_id" id="rfq_id">
                                            <input type="hidden" value="{{$rfq->status_id}}" name="rfq_status" id="rfq_status">
                                            <section id="step-2" class="mb-3">
                                                <div class="row g-3" id="fullrfq_step2" id="fullrfq_step2">
                                                    <div class="col-md-12">
                                                        <h5 id="list-item-2" class="mb-0 dark_blue text-primary">
                                                        {{ __('rfqs.Product_Details') }}
                                                        </h5>
                                                    </div>
                                                    <div class="col-md-12 productCategoryDiv" id="productCategoryDiv{{$rfq->id}}">
                                                        <select  @if($rfq->group_id !== null || $rfq->status_id !== 1) disabled @endif class=" form-select" id="product_category"
                                                                 onchange="changeCategory(this)" aria-label="Default select example" required  name="category">
                                                            <option selected disabled>
                                                            {{ __('dashboard.select_product_category') }}</option>
                                                            @php $other = 1; @endphp
                                                            @foreach ($category as $categoryItem)
                                                                <option data-rfqname="{{$rfq->category}}" data-category="{{ $categoryItem->name }}" value="{{ $categoryItem->name }}"  {{(strtolower($rfq->category) == strtolower($categoryItem->name )? 'selected':'')}} data-category-id="{{ $categoryItem->id }}"
                                                                    data-text="{{ $categoryItem->name }}">
                                                                    {{ $categoryItem->name }}</option>
                                                                @if((strtolower($rfq->category) == strtolower($categoryItem->name )))

                                                                    @php $other = 0; @endphp
                                                                @endif
                                                            @endforeach
                                                            @if($other == 1)
                                                            <option data-rfqname="{{$rfq->category}}" data-category-id="0" value="Other" selected>Other</option>
                                                            @else
                                                            <option data-rfqname="{{$rfq->category}}" data-category-id="0" value="Other" >Other</option>
                                                            @endif

                                                        </select>
                                                        <label>{{ __('dashboard.select_product_category') }}<span class="text-danger">*</span></label>
                                                        <input type="hidden" class="form-control" id="category_id" name="category_id" value="">
                                                    </div>
                                                    <div class="col-md-12 d-none" id="productCategoryOtherDiv{{$rfq->id}}">
                                                        <input  @if($rfq->status_id !== 1) disabled @endif class="form-control" id="othercategory{{$rfq->id}}" type="text"
                                                            placeholder="Other Category" name="othercategory">
                                                        <label>{{ __('dashboard.other_product_category') }}<span class="text-danger">*</span></label>
                                                    </div>


                                                    <div class="col-md-12">
                                                        <div class="d-none" id="edit_id" data-id=""></div>
                                                        <div class="d-none" id="show_change_cat_validation" data-value="true"></div>
                                                        <div class="multibox_border pt-3 p-2">
                                                            <div class="row">
                                                            <!-- group_id -->
                                                                <div class="col-md-12 mb-4" id="productSubCategoryDiv">
                                                                    <select  @if($rfq->status_id !== 1 || $rfq->group_id != null) disabled @endif class=" form-select" id="product_sub_category" onchange="changeSubCategory(this)" aria-label="Default select example"  name="product_sub_category">
                                                                        <option selected disabled>
                                                                            {{ __('dashboard.Select Product Sub Category') }}</option>
                                                                    </select>
                                                                    <label>{{ __('dashboard.Product Sub Category') }}<span class="text-danger">*</span></label>
                                                                    <input type="hidden" class="form-control" id="product_sub_category_id" name="product_sub_category_id">
                                                                </div>
                                                                <div class="col-md-12 col-xl-8 mb-4">
                                                                    <input type="text" placeholder="{{ __('dashboard.Product_Name') }}"  @if($rfq->status_id !== 1 || $rfq->group_id != null) disabled @endif class="form-control" onkeyup="searchProductFullForm(this.value)" onmousedown="searchProductFullForm(this.value)" name="product_name" id="product_name" required>
                                                                    <label>{{ __('dashboard.Product_Name') }}<span class="text-danger">*</span></label>
                                                                    <input type="hidden" class="form-control" id="product_id" name="product_id">
                                                                </div>
                                                                <div class="col-md-2  mb-4">
                                                                    <input type="number"  @if($rfq->status_id !== 1 || $rfq->group_id != null) disabled @endif class="form-control" id="quantity" name="quantity" required
                                                                           data-parsley-type="number" placeholder="0" min="1" >
                                                                    <label>{{ __('dashboard.Quantity') }}<span class="text-danger">*</span></label>
                                                                </div>

                                                                <div class="col-md-2  mb-4">
                                                                    <select class="form-select" id="unit"  @if($rfq->status_id !== 1 || $rfq->group_id != null) disabled @endif name="unit"
                                                                            aria-label="Default select example" required>
                                                                        <option selected disabled value="">{{ __('dashboard.Select Unit') }}
                                                                        </option>
                                                                        @foreach ($unit as $unitItem)
                                                                            <option value="{{ $unitItem->id }}" >{{ $unitItem->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <label>{{ __('dashboard.Unit') }}<span class="text-danger">*</span></label>
                                                                </div>

                                                                <div class="col-md-12">
                                                                    <input  @if($rfq->status_id !== 1 || $rfq->group_id != null) disabled @endif type="text" class="form-control" style="height: 60px;"
                                                                            placeholder="@if(in_array($rfq->category_id,\App\Models\Category::SERVICES_CATEGORY_IDS)){{ __('dashboard.Service_Product_Description_Placeholder') }} @else{{ __('dashboard.Product_Description_Placeholder') }}@endif"
                                                                            id="product_description" name="product_description" required>
                                                                    <label>{{ __('dashboard.Product_Description') }}<span class="text-danger">*</span></label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="py-2 text-end">
                                                            @if($rfq->status_id == 1)
                                                            <button type="button" class="btn btn-secondary px-2 py-1 " id="cancel_product_btn" onclick="CancelsProduct()" style="font-size: 12px;">
                                                                <img src="{{ URL::asset('front-assets/images/icons/cancel.png') }}" alt="Cancel" class="pe-1" style="max-height: 12px;"> <span>{{ __('admin.cancel') }}</span>
                                                            </button>
                                                            <button type="button" class="btn btn-primary px-2 py-1 ms-1" id="add_product_btn" onclick="AddProduct()" style="font-size: 12px;" {{($rfq->group_id != null ? 'disabled' : '') }}>
                                                                <img src="{{ URL::asset('front-assets/images/icons/icon_post_require.png') }}" alt="Edit" class="pe-1" style="max-height: 12px;"> <span id="add_edit_name_change">{{ __('admin.add') }}</span>
                                                            </button>
                                                            @endif
                                                        </div>
                                                        <div class="text-center text-danger" id="five_ptoduct_validation_msg"></div>
                                                        <div class="bg-light multi_pro_list py-1">
                                                            <table id="rfq_table" class="table bg-white mb-0">
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
                                                                            <td>
                                                                                @if($rfq->status_id == 1)
                                                                                    <a href="javascript:void(0);" class="p-1 mx-1" onclick="editProduct('{{ $product_rfq->id }}')"><img src="{{ URL::asset("front-assets/images/icons/icon_fillinmore.png") }}" alt="Cancel" class="align-top" style="max-height: 14px;"></a>
                                                                                    @if($rfq->group_id == null)
                                                                                    <a href="javascript:void(0);"class="p-1 mx-1 deleteProduct" id="deleteProduct_{{ $product_rfq->id }}" onclick="deleteProduct('{{ $product_rfq->id }}')"><img src="{{ URL::asset("front-assets/images/icons/icon_delete_add.png") }}" alt="Cancel" class="align-top" style="max-height: 14px;"></a>
                                                                                    @endif
                                                                                @endif
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
                                                        <input type="text" class="form-control" style="height: 60px;" placeholder="Give more information about your product" name="comment" id="comment" value="{{$rfq->comment}}">
                                                        <label for="comment">{{ __('dashboard.Comment') }}</label>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="" class="form-label">{{ __('rfqs.upload_attachment') }}</label>
                                                        <div class="d-flex form-control py-2">
                                                            <span class="">
                                                                <input type="file" @if($rfq->status_id !== 1) disabled @endif name="attached_document[]" class="form-control" id="attached_document" accept=".jpg,.png,.jpeg,.pdf" onchange="showFile(this)" hidden="" multiple>
                                                                <label id="upload_btn" for="attached_document">{{ __('profile.browse') }}</label>
                                                            </span>
                                                            <div id="file-attached_document" class="d-flex align-items-center">

                                                                @if (isset($rfq_attachments) && count($rfq_attachments)>=1)
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
                                                                    <span class="ms-2" id="RfqAfterRemove">
                                                                        <a href="javascript:void(0);" id="RfqFileDownload" onclick="{{$downloadAttachment}}"  title="{{ $rfq_file_name }}" style="text-decoration: none;"> {{ $rfq_file_name }}</a>
                                                                    </span>
                                                                    <span style="@if(count($rfq_attachments)>1) display:block @else display:none @endif" class="btnlistuploaded">
                                                                        <a href="javascript:void(0);" title="{{ __('rfqs.upload_attachment') }}">
                                                                            <img src="{{URL::asset('front-assets/images/icons/uploadlist.png')}}" alt="CLose button" class="ms-2">
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
                                                    <div class="col-md-6">
                                                        <label for="" class="form-label">{{ __('admin.commercial_tc') }}</label>
                                                        <div class="d-flex form-control py-2">
                                                            <span class="">
                                                                <input type="file" @if($rfq->status_id !== 1) disabled @endif name="termsconditions_file" class="form-control" id="termsconditions_file" accept=".jpg,.png,.jpeg,.pdf" data-id="{{$rfq->id}}" onchange="showFile(this)" hidden="" >
                                                                <label id="upload_btn" for="termsconditions_file">{{ __('profile.browse') }}</label>
                                                            </span>
                                                                @if ($rfq->termsconditions_file)
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
                                                                                <a class="termsconditions_file" href="javascript:void(0);" title="{{ __('profile.download_file') }}" onclick="downloadimg('{{ $rfq->id }}', 'termsconditions_file', '{{ $termsconditions_file_name }}')" style="text-decoration: none;"><img src="{{URL::asset('front-assets/images/icons/icon_download.png')}}" width="14px"></a>
                                                                            </span>
                                                                    </div>
                                                                @else
                                                                    <div id="file-termsconditions_file" class="d-flex align-items-center">
                                                                @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </section>
                                            <section id="step-3" class="mb-3">
                                                <div class="row g-3 error_res" id="fullrfq_step3">
                                                    <div class="col-md-12">
                                                        <h5 id="list-item-3" class="mb-0 dark_blue text-primary rfq_address">
                                                       @if(in_array($rfq->category_id,\App\Models\Category::SERVICES_CATEGORY_IDS)) {{ __('dashboard.pickup_details') }} @else {{ __('dashboard.Delivery_Details') }} @endif</h5>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="col-md-12" id="address_block">
                                                            <label class="form-label">{{ __('rfqs.select_address') }}<span class="text-danger">*</span></label>
                                                                <select class="form-select" id="useraddress_id" name="useraddress_id" data-isEdit="1" @if($rfq->status_id !== 1) disabled @endif required>
                                                                    <option disabled selected>{{ __('rfqs.select_address') }}</option>
                                                                    @foreach ($userAddress as $item)
                                                                        @php
                                                                            $addSelected = '';
                                                                            if ($item->address_name==$rfq->address_name && $item->address_line_1==$rfq->address_line_1 && $item->address_line_2==$rfq->address_line_2){
                                                                                $addSelected = 'selected';
                                                                            }
                                                                        @endphp
                                                                        <option data-address_name="{{$item->address_name}}" data-address_line_2="{{$item->address_line_2}}" data-address_line_1="{{$item->address_line_1}}" data-sub_district="{{$item->sub_district}}" data-district="{{$item->district}}" data-city="{{$item->city}}" data-state="{{$item->state}}" data-city-id="{{$item->city_id > 0 ? $item->city_id : \App\Models\UserAddresse::OtherCity}}" data-state-id="{{$item->state_id > 0 ? $item->state_id : \App\Models\UserAddresse::OtherState}}" data-pincode="{{$item->pincode}}" value="{{ $item->id }}" {{$addSelected}}>{{ $item->address_name }}</option>
                                                                    @endforeach
                                                                    <option data-address-id="0" value="Other">Other</option>
                                                                </select>
                                                                @if($rfq->status_id !== 1)
                                                                     @foreach ($userAddress as $item)
                                                                        @if($item->address_name==$rfq->address_name && $item->address_line_1==$rfq->address_line_1 && $item->address_line_2==$rfq->address_line_2)
                                                                            <input type="hidden" name="useraddress_id" class="form-control address_name" id="useraddress_id" value="{{$item->id}}" readonly>
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="address_name" class="form-label">{{ __('rfqs.address_name') }}<span class="text-danger">*</span></label>
                                                        <input type="text" name="address_name" class="form-control address_name" id="eaddress_name"
                                                               value="{{$rfq->address_name}}" @if($rfq->status_id !== 1) readonly @endif required>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="addressLine1" class="form-label">{{ __('rfqs.address_line1') }}<span class="text-danger">*</span></label>
                                                        <input type="text" name="address_line_1" class="form-control addressLine1" id="eaddressLine1"
                                                               value="{{$rfq->address_line_1}}" @if($rfq->status_id !== 1) readonly @endif required>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="addressLine2" class="form-label">{{ __('rfqs.address_line2') }}</label>
                                                        <input type="text" class="form-control addressLine2" name="address_line_2" id="eaddressLine2"
                                                               value="{{$rfq->address_line_2}}" @if($rfq->status_id !== 1) readonly @endif>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="sub_district" class="form-label">{{ __('rfqs.sub_district') }}<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control sub_district" name="sub_district" id="esub_district" value="{{$rfq->sub_district}}" @if($rfq->status_id !== 1) readonly @endif required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="district" class="form-label">{{ __('rfqs.district') }}<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control district" name="district" id="edistrict" value="{{$rfq->district}}" @if($rfq->status_id !== 1) readonly @endif required>
                                                    </div>

                                                    <div class="col-md-6 select2-block" id="stateEditId_block">
                                                        <label for="stateEditId" class="form-label">{{ __('rfqs.provinces') }}<span class="text-danger">*</span></label>
                                                        <select class="form-select select2-custom @if($rfq->status_id !== 1) disabled @endif" id="stateEditId" name="stateId" data-placeholder="{{ __('rfqs.select_province') }}" required>
                                                            <option value="" >{{ __('rfqs.select_province') }}</option>
                                                            @foreach ($states as $state)
                                                                <option value="{{ $state->id }}" @if($rfq->state_id == $state->id) selected @endif >{{ $state->name }}</option>
                                                            @endforeach
                                                            <option value="-1">Other</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3 hide" id="state_block">
                                                        <label for="state" class="form-label">{{ __('rfqs.other_provinces') }}<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control state" name="state" id="estate" value="{{ $rfq->state }}" @if($rfq->status_id !== 1) readonly @endif>
                                                    </div>

                                                    <div class="col-md-6 select2-block" id="cityEditId_block">
                                                        <label for="cityEditId" class="form-label">{{ __('rfqs.city') }}<span class="text-danger">*</span></label>
                                                        <select class="form-select select2-custom @if($rfq->status_id !== 1) disabled @endif" id="cityEditId" name="cityId" data-placeholder="{{ __('rfqs.select_city') }}" data-selected-city="{{ $rfq->city_id }}" required>
                                                            <option value="">{{ __('rfqs.select_city') }}</option>
                                                            <option value="-1">Other</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3 error_res hide" id="cityEdit_block">
                                                        <label for="city" class="form-label">{{ __('rfqs.other_city') }}<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control city" name="city" id="ecity" value="{{ $rfq->city }}" @if($rfq->status_id !== 1) readonly @endif>
                                                    </div>

                                                    <div class="col-md-6 col-lg-6">
                                                        <input type="text" oninput="this.value = sthis.value.replace(/[^0-9]/g, '').replace(/(\.*?)\..*/g, '$1');"   @if($rfq->status_id !== 1) readonly @else pattern=".{5,}"  minlength="5" @endif name="pincode" class="form-control pincode" id="epincode" required value="{{$rfq->pincode}}">
                                                        <label for="formGroupExampleInput" class="form-label">{{ __('dashboard.Delivery_Pincode') }}<span class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-md-6 col-lg-6">
                                                        <input type="text" class="form-control calendericons"
                                                            placeholder="dd-mm-yyyy"  @if($rfq->status_id !== 1) readonly @endif name="expected_date"
                                                            id="expected_date" name readonly required value="{{date('d-m-Y', strtotime($rfq->expected_date)) }}">
                                                        <label>{{ __('dashboard.Expected_Delivery_Date') }}<span class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-md-12 d-flex">
                                                        <div class="form-check me-3">
                                                            <input class="form-check-input"  @if($rfq->status_id !== 1) disabled @endif type="checkbox" value="1"
                                                                id="need_rental_forklift{{$rfq->id}}" name="need_rental_forklift" {{$rfq->rental_forklift == 1 ? 'checked':''}}>
                                                            <label class="form-check-label" for="need_rental_forklift">
                                                            {{ __('dashboard.need_rental_forklift') }}
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="1"
                                                                id="need_unloading_services{{$rfq->id}}"  @if($rfq->status_id !== 1) disabled @endif
                                                                name="need_unloading_services" {{$rfq->unloading_services == 1 ? 'checked' : ''}}>
                                                            <label class="form-check-label"
                                                                for="need_unloading_services">
                                                                {{ __('dashboard.need_unloading_services') }}
                                                            </label>
                                                        </div>
                                                    </div>

                                                </div>
                                            </section>

                                            <section id="list-item-1" class="mb-4">
                                                <div id="fullrfq_step1" class="row g-3">
                                                    <div class="col-md-12">
                                                        <h5 class="mb-0 dark_blue text-primary">
                                                            {{ __('rfqs.full_rfq_contact_detail') }}
                                                        </h5>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="hidden" name="groupId" id="groupId" value="{{$rfq->group_id ? $rfq->group_id : 0}}">
                                                        <input type="text" class="form-control" id="firstname"
                                                               name="firstname" required value="{{$rfq->firstname ?? ''}}" @if($rfq->status_id !== 1) disabled @endif>
                                                        <label>{{ __('rfqs.first_name') }}<span class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-md-6">

                                                        <input type="text" id="lastName" class="form-control"
                                                               name="lastname" required value="{{$rfq->lastname ?? ''}}"  @if($rfq->status_id !== 1) disabled @endif>
                                                        <label>{{ __('rfqs.last_name') }}<span class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="email" id="email" name="email" class="form-control"
                                                               value="{{$rfq->email ?? ''}}" required  @if($rfq->status_id !== 1) disabled @endif>
                                                        <label>{{ __('rfqs.Email') }}<span class="text-danger">*</span></label>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <input type="text" id="mobile" class="form-control" data-parsley-type="digits"
                                                               data-parsley-length="[9, 16]" data-parsley-length-message="It should be between 9 and 16 digit." placeholder="XXXXXXXXXXX" required name="mobile"
                                                               value="{{$rfq->mobile ?? ''}}"  @if($rfq->status_id !== 1) disabled @endif>
                                                        <label>{{ __('rfqs.Contact_Number') }}<span class="text-danger">*</span></label>

                                                    </div>

                                                </div>
                                            </section>
                                            <section id="step-4">
                                                <div class="row g-3" id="fullrfq_step4">
                                                    <div class="col-md-12">
                                                        @if($rfq->group_id == null)
                                                            <h5 id="list-item-4" class="mb-0 dark_blue text-primary"> {{ __('admin.payment_term') }} </h5>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-3 {{$rfq->group_id == null ? '' : 'd-none' }} ">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input"  @if($rfq->status_id !== 1) disabled @endif type="checkbox" role="switch" id="creditSwitchCheckDefault{{$rfq->id}}" {{$rfq->is_require_credit == 1 ? 'checked' : ''}} >
                                                            <label class="form-check-label" for="creditSwitchCheckDefault">{{ __('dashboard.require_credit') }}?</label>
                                                        </div>
                                                    </div>

                                                        <div class="col-md-3 mb-2 credit-type-div {{$rfq->is_require_credit == 1 ? '' : 'hide'}}">
                                                            <label>{{ __('rfqs.credit_type') }}:</label>
                                                            <select @if($rfq->status_id !== 1) disabled @endif class="form-select form-select-sm p-2 credit_type" id="credit_days_id" name="credit_days_id">
                                                                <option value="0">{{ __('admin.select_credit_type') }}</option>
                                                                <option {{$rfq->payment_type == 3 ?'selected' :''}} value="lc">{{ __('admin.lcdropdwn') }}</option>
                                                                <option {{$rfq->payment_type == 4 ?'selected' :''}} value="skbdn">{{ __('admin.skbdn') }}</option>
                                                            @foreach($creditDays as $i=>$creditDay)rfqs.credit_type
                                                                @php
                                                                    $credit_name = sprintf(__('rfqs.credit_type_name'),trim($creditDay->days));
                                                                @endphp
                                                                    <option {{$rfq->credit_days == $creditDay->days?'selected' :''}} value="{{$creditDay->days}}">{{$credit_name}}</option>
                                                            @endforeach
                                                            </select>
                                                        </div>

                                                    {{-- @if($rfq->status_id == 1) --}}
                                                    <!-- New Section (Ronak M - 21/06/2022) -->
                                                    <div class="{{ (isset($rfq->status_id) && $rfq->status_id == 1) ? 'col-md-12' : 'col-md-6' }}  mt-2">
                                                        @if(isset($preferredSupplierCount) && $preferredSupplierCount > 0)
                                                            <div class="supply-selector d-flex align-items-center justify-content-end">
                                                                <div class="form-check form-check-inline mt-2">
                                                                    <input class="form-check-input firstBtn" type="radio" name="is_preferred_supplier" id="inlineRadio1" value="0" {{ ($rfq->is_preferred_supplier == 0) ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="inlineRadio1">{{ __('profile.all_suppliers') }}</label>
                                                                </div>
                                                                <div class="form-check form-check-inline mt-2">
                                                                    <input class="form-check-input secondBtn" type="radio" name="is_preferred_supplier" id="inlineRadio2" value="1" {{ ($rfq->is_preferred_supplier == 1) ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="inlineRadio2">{{ __('profile.preferred_supplier_only') }}</label>
                                                                </div>

                                                                <div class="position-relative me-3">
                                                                    <button type="button" class="btn btn-default icon_prefferd" id="showPreferredSuppliers" data-user-id="{{ Auth::user()->id }}" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                                                    </button>

                                                                    <div class="collapse prefsupp shadow border" id="collapseExample">
                                                                        <div class="row mx-0">
                                                                            <div class="col-12">
                                                                                <div class="row dark_blue_bg text-white py-1">
                                                                                    <div class="col-12 text-wrap d-flex justify-content-between align-items-center">
                                                                                        <div class="form-check ms-1 mt-1">
                                                                                            <input class="form-check-input" type="checkbox" value="" id="selectAllSuppliers">
                                                                                            <label class="form-check-label bg-transparent text-white" for="selectAllSuppliers">{{ __('profile.preferred_suppliers') }} <small class="px-1 text-center text-danger" id="status"></small></label>
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
                                                                                                <input type="checkbox" class="form-check-input preferred-supplier-checked" name="supplier_chk" id="supp_{{ $supplier->preferredSuppId }}" value="{{ $supplier->preferredSuppId }}">
                                                                                                {{ 'supp_' . $supplier->preferredSuppId  . ' - ' . $supplier->companyName }}
                                                                                                <!-- {{in_array($supplier->preferredSuppId,$existingPreferredSuppliers->toArray())?'checked':''}} -->
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
                                                            @php $existingPreferredSuppliers = (isset($existingPreferredSuppliers) && sizeof($existingPreferredSuppliers) > 0) ? json_encode($existingPreferredSuppliers->toArray()) : ""; @endphp
                                                            <input type="hidden" name="preferredSuppliersIds" id="preferredSuppliersIds" value="{{ $existingPreferredSuppliers }}" />
                                                        @endif
                                                    </div>
                                                    <!-- / End -->
                                                    {{-- @endif --}}

                                                    <div class="col-12 text-end mt-3">
                                                        <a class="btn btn-secondary btn-sm px-3 py-2 mb-1 reserform" href="javascript:void(0)" data-bs-dismiss="modal">{{ __('admin.cancel') }}</a>

                                                        <button type="button" class="btn btn-primary btn-sm px-3 py-2 mb-1"
                                                            href="javascript:void(0)" id="postFulleditRfqForm{{$rfq->id}}">{{ __('admin.update') }}</button>
                                                    </div>
                                                </div>
                                              </section>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="activities" role="tabpanel" aria-labelledby="activities-tab">
                        <div class="card border-0">
                            <div class="card-body">
                                <div class="timeline">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="chat-history" role="tabpanel" aria-labelledby="chat-history-tab">
                        <div class="card chat_section_1 border-0">
                            <div class="card-body p-0">
{{--                                {{preDump($chatHistory[0]->groupChatMessage)}}--}}
                                <div class="chatwithsuppsection pb-lg-3" >
                                    @if($chatHistory->isEmpty())
                                    <div class="col-md-12 mt-2 d-flex align-items-start px-2 pb-1">
                                        <div class="col-md-auto pe-2 mt-1">
                                            <span class="userfonticon"><i class="fa fa-user" style="font-size: 14px;"></i></span>
                                        </div>
                                        <div class="col-md-9 chatdetailfromadmin">
                                            <div class="p-2">
                                                <div class="name">blitznet Team</div>
                                                <div class="text">{{__('admin.need_help_msg')}}</div>
                                                <div class="time">{{ changeTimeFormat(now()) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                        @php
                                            $todayDate = changeDateFormat(now());
                                            $yesterDay = changeDateFormat(date('d-m-Y',strtotime("yesterday")));
                                            $dateUsed = [];
                                        @endphp
                                        @foreach($chatHistory[0]->groupChatMessage as $message)
                                            @php
                                                $messageDate = changeDateFormat($message->created_at);
                                                $messageType = isset($message->message_type) ? $message->message_type : 1;
                                                if ($messageType == 1){
                                                    $innerMessage = $message->message;
                                                } elseif ($messageType == 2){
                                                    if ($message->mimtype == 1){
                                                      $innerMessage = '<a href="'.url('storage/'.$message->message).'" download><img src="'.url('storage/'.$message->message).'" class="mw-100"></a>';
                                                    } else if ($message->mimtype == 2){
                                                        $url = \Illuminate\Support\Facades\URL::asset('assets/images/PDF_icon.png');
                                                        $innerMessage = '<a href="'.url('storage/'.$message->message).'" download><img src="'.$url.'" class="mw-100"></a>';
                                                    }
                                                }
                                            @endphp
                                            @if(!in_array('today',$dateUsed) && $todayDate==$messageDate)
                                                @php
                                                    array_push($dateUsed,'today');
                                                @endphp
                                                <div class="position-relative">
                                                    <hr>
                                                    <div class="message-time text-center text-muted bg-none chatdatewise">
                                                        {{__('admin.today')}}
                                                    </div>
                                                </div>
                                            @elseif(!in_array('yesterday',$dateUsed) && $yesterDay==$messageDate)
                                                @php
                                                    array_push($dateUsed,'yesterday');
                                                @endphp
                                                <div class="position-relative">
                                                    <hr>
                                                    <div class="message-time text-center text-muted bg-none chatdatewise">
                                                        {{__('admin.yesterday')}}
                                                    </div>
                                                </div>
                                            @elseif(!in_array($messageDate,$dateUsed) && $todayDate!=$messageDate)
                                                @php
                                                    array_push($dateUsed,$messageDate);
                                                @endphp
                                                <div class="position-relative">
                                                    <hr>
                                                    <div class="message-time text-center text-muted bg-none chatdatewise">
                                                        {{$messageDate}}
                                                    </div>
                                                </div>
                                            @endif

                                            @if($message->sender_id==auth()->user()->id)
                                                <div class="col-md-12 d-flex align-items-start buyerchatside px-2 pb-1">
                                                    <div class="col-md-9 chatdetailfromuser">
                                                        <div class="p-2">
                                                            <div class="name">You</div>
                                                            <div class="text">{!! $innerMessage !!}</div>
                                                            <div class="time">{{changeTimeFormat($message->created_at)}}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-auto ps-2 mt-1">
                                                        <span class="userfonticon"><i class="fa fa-user"style="font-size: 14px;"></i></span>
                                                    </div>
                                                </div>
                                            @else
                                                @php
                                                    $name = $class = '';
                                                    if($message->sender_role_id ==1){
                                                        $name = 'blitznet Team';
                                                        $class ='chatdetailfromadmin';
                                                    }elseif($message->sender_role_id ==3){
                                                        $name = $message->user->supplier()->pluck('name')->first();
                                                        $class ='chatdetailfromsupplier';
                                                    }else{
                                                        $name = 'Buyer';
                                                    }
                                                    $messageType = isset($message->message_type) ? $message->message_type : 1;
                                                    if ($messageType == 1){
                                                        $innerMessage = $message->message;
                                                    } elseif ($messageType == 2){
                                                        if ($message->mimtype == 1){
                                                          $innerMessage = '<a href="'.url('storage/'.$message->message).'" download><img src="'.url('storage/'.$message->message).'" class="mw-100"></a>';
                                                        } else if ($message->mimtype == 2){
                                                            $pdfImage = \Illuminate\Support\Facades\URL::asset('assets/images/PDF_icon.png');
                                                            $innerMessage = '<a href="'.url('storage/'.$message->message).'" download><img src="'.$pdfImage.'" class="mw-100"></a>';
                                                        }
                                                    }
                                                @endphp
                                                <div class="col-md-12 mt-2 d-flex align-items-start px-2 pb-1">
                                                    <div class="col-md-auto pe-2 mt-1">
                                                        <span class="userfonticon"><i class="fa fa-user" style="font-size: 14px;"></i></span>
                                                    </div>
                                                    <div class="col-md-9 {{$class}}">
                                                        <div class="p-2">
                                                            <div class="name">{{$name}}</div>
                                                            <div class="text">{!! $innerMessage !!}</div>
                                                            <div class="time">{{changeTimeFormat($message->created_at)}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                                <div class="col-md-12 text-end mt-auto p-3">
{{--                                    <a href="javascript:void(0)" data-id="1167" class=" btn btn-primary px-3 py-1" style="font-size: 12px;">--}}
{{--                                        <img src="chat_icon_white.png" style="max-height: 14px;" alt="View" class="pe-1"> Chat <span class="bg-warning text-black px-1 ms-1 fw-bold rounded" style="font-size: 10px;">3</span>--}}
{{--                                    </a>--}}
                                    @php
                                        $chatData = getChatDataForRfqById($rfq->id, 'Rfq');
                                    @endphp
                                    <a href="javascript:void(0)" onclick="chat.chatRfqViewData('{{ route('new-chat-create-view')  }}', '{{$chatData['group_chat_id']??""}}','Rfq','{{$rfq->reference_number}}', '{{ $rfq->id??'' }}',$(this), '', {{ $rfq->company_id??'' }} )" data-id="{{ $rfq->id }}" class=" btn px-3 py-1" style="font-size: 12px;background-color: #B7CFFF;">
                                        <img src="{{ URL::asset('front-assets/images/icons/chat_icon.png') }}"  style="max-height: 14px;" alt="View" title="{{ __('admin.chat')}}" class="pe-1"> @if(!empty($chatData) && $chatData['unread_message_count'] != 0)<span class="bg-warning text-black px-1 ms-1 fw-bold rounded" style="font-size: 10px;">{{ $chatData['unread_message_count'] }}</span>@endif

                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


<!-- Edit Modal -->
<script>
    var last_category = -1;
    var max_product = '{{ $max_product??5 }}';
    var max_attachments = '{{ $max_attachments??0 }}';
    //let supplierValidationMsg = "({{ __('profile.atleast_one_checkbox_checked') }})";

    $(document).ready(function() {
        //By Default Disable "showPreferredSuppliers" button if there no preferred suppliers listed (Ronak M - 22/06/2022)

        // if(($("#preferredSuppliersIds").val()).length > 0) {
        //     $("#inlineRadio2").attr('checked', 'checked');
        //     $("#showPreferredSuppliers").attr('disabled', false);
        // } else {
        //     $("#inlineRadio1").attr('checked', 'checked');
        //     $("#showPreferredSuppliers").attr('disabled', true);
        // }

        //On radio button checked, enable / disable "showPreferredSuppliers" button (Ronak M - 22/06/2022
        $("input[name='is_preferred_supplier']").change(function() {
            let rfqId = $("#rfq_id").val();
            if (!$(this).prop("checked")) {
                $("#selectAllSuppliers").prop("checked", false);
            }

            if ($(this).val() == "0") {
                $("#showPreferredSuppliers").attr('disabled', true);
                $("#preferredSuppliersIds").val("");
                $("#collapseExample").removeClass("show");
                $("#showPreferredSuppliers").attr("aria-expanded","false");
                $('#postFulleditRfqForm'+rfqId).prop('disabled', false);
            } else {
                $("#showPreferredSuppliers").attr('disabled', false);
                $("input:checkbox[name=supplier_chk]").each(function() {
                    $("input:checkbox[name=supplier_chk]").prop('checked', $(this).prop("checked"));
                });
            }
        });
        //get preferred suppliers as per the selected category (Ronak M - 12/07/2022)
        getPreferredSuppByCategory($("#product_category").find("option:selected").attr('data-category-id'));
    });

    //On click of edit icon checked / unchecked icon
    $('#showPreferredSuppliers').click(function(e) {
        e.preventDefault();
        $("#status").text('');
        suppliersArray = [];

        let rfqId = $("#rfq_id").val();

        let preferredCategoryId = $("#product_category").find("option:selected").attr('data-category-id');
        getPreferredSuppliers(rfqId, preferredCategoryId);

    });


    //Get preferred supplier as per selected category id (Ronak M - 30/07/2022)
    function getPreferredSuppliers(rfqId, preferredCategoryId) {
        var url = "{{ route('get-preferred-suppliers-by-rfqId-ajax', [':rfqId', ':preferredCategoryId']) }}";
        url = url.replace(":rfqId", rfqId);
        url = url.replace(":preferredCategoryId", preferredCategoryId);
        $.ajax({
            url: url,
            type: 'GET',
            dataType: "json",
            success: function(successData) {
                var preferredSupplierHtml = '';
                preferredSupplierHtml += ' <ul class="list-group">';
                $.each(successData.suppliersData, function(index,value) {
                    preferredSupplierHtml += '<li class="list-group-item border-0 border-bottom">';
                    preferredSupplierHtml += '<input type="checkbox" class="form-check-input me-1 suppCheckBox" name="supplier_chk" id="supp_'+value.preferredSuppId+'" value="'+value.preferredSuppId+'">' + value.companyName
                });
                preferredSupplierHtml += ' </ul>';
                $('#preferredSuppliersList').html(preferredSupplierHtml);

                //console.log(successData.selectedSuppliersIds, "selectedSuppliersIds");
                if((successData.selectedSuppliersIds).length > 0) {
                    $('#preferredSuppliersIds').val(successData.selectedSuppliersIds);
                } else {
                    $('#preferredSuppliersIds').val(successData.allSuppliersIds);
                }
                //let preferred_suppliers = JSON.parse($('#preferredSuppliersIds').val());

                $.each($(".suppCheckBox"),function(key, value){
                    let currentId = parseInt($(this).val());
                    if ($.inArray(currentId, successData.selectedSuppliersIds) !== -1) {
                        $(this).prop('checked', true);
                    }else{
                        $(this).prop('checked', false);
                    }

                    //Check select all if all checkbox is selected
                    if($('.suppCheckBox:checked').length == $('.suppCheckBox').length) {
                        $('#selectAllSuppliers').prop('checked',true);
                    } else {
                        $('#selectAllSuppliers').prop('checked',false);
                    }
                });
            },
            error: function() {
                console.log('error');
            }
        });
    }

    /** get only selected suppliers id from bootstrap popup modal
    *   Ronak M - 23/06/2022
    */
    $("#selectAllSuppliers").click(function() {
        let rfqId = $("#rfq_id").val();
        if ($('input:checkbox[name=supplier_chk]').filter(':checked').length == 1){

            // $("#status").text("({{ __('profile.atleast_one_checkbox_checked') }})");
            // return false;
            $("input:checkbox[name=supplier_chk]").prop("checked", $(this).prop("checked"));
            $("#status").text("");

        } else {
            $("#status").text("");
            $("input:checkbox[name=supplier_chk]").prop("checked", $(this).prop("checked"));
            $('#postFulleditRfqForm'+rfqId).prop('disabled', false);
        }
        checkAllCheckboxChecked(rfqId);
    });

    function checkAllCheckboxChecked(rfqId) {
        if ($('input:checkbox[name=supplier_chk]').filter(':checked').length == 0) {
            $('#postFulleditRfqForm'+rfqId).prop('disabled', true);
            $("#status").text("({{ __('profile.atleast_one_checkbox_checked') }})");
            return false;
        }
    }

    $(document).on('click','input:checkbox[name=supplier_chk]',function() {
        if ($('input:checkbox[name=supplier_chk]').filter(':checked').length < 1){
            $("#status").text("({{ __('profile.atleast_one_checkbox_checked') }})");
            return false;
        } else {
            let rfqId = $("#rfq_id").val();
            $("#status").text("");
            $('#postFulleditRfqForm'+rfqId).prop('disabled', false);

            // if (!$(this).prop("checked")) {
            //     $("#selectAllSuppliers").prop("checked", false);
            // }

            if($('.suppCheckBox:checked').length == $('.suppCheckBox').length) {
                $('#selectAllSuppliers').prop('checked',true);
            } else {
                $('#selectAllSuppliers').prop('checked',false);
            }

        }
    });

    $("#applySuppBtn").click(function(e) {
        e.preventDefault();
        suppliersArray = [];
        $("input:checkbox[name=supplier_chk]:checked").each(function() {
            suppliersArray.push($(this).val());
        });
        // console.log(JSON.stringify(suppliersArray))
        $("#preferredSuppliersIds").val(JSON.stringify(suppliersArray));
        $("#collapseExample").removeClass("show");
    });

    //------- End --------//
    /** multiple attachments
     *   Vrutika - 27/07/2022
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
    //Download multiple attachment document
    function downloadAttachment(rfq_id,fieldName, ref_no){
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
                    url: "{{ route('rfq-attachment-delete-ajax') }}",
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

    //------- End --------//

    var rfqdetail = @json($rfq);
    var product = @json($productRfq);
    $(document).on('show.bs.modal','.editmodal', function (event) {
        $(this).find('.nav a:first').tab('show');
    });
    $(document).on('hide.bs.modal','.editmodal', function (event) {
        $('#collapse'+rfqdetail.id).closest('.accordion-item').find('#rfqupdatecollpse').click();
    });
    /*$(".reserform").click(function(){
        $('#collapse'+rfqdetail.id).closest('.accordion-item').find('#rfqupdatecollpse').click();
        $(".showeditmodal").html('');
    })*/

    var iti = setIntlTelInput('#mobile','phone_code');
    $("#mobile").focusin(function(){
        let countryData = iti.getSelectedCountryData();
        $('input[name="phone_code"]').val(countryData.dialCode);
    });

    $(function(){
        window.localStorage.setItem('product_edit', JSON.stringify(product));
        @php
            $phoneCode = $rfq->phone_code?str_replace('+','',$rfq->phone_code):62;
            $countryCode = $rfq->phone_code?strtolower(getRecordsByCondition('countries',['phone_code'=>$phoneCode],'iso2',1)):'id';
        @endphp
        $('input[name="phone_code"]').val('{{$phoneCode}}');
        iti.setCountry('{{$countryCode}}');

        $('#expected_date').datepicker({
            dateFormat: "dd-mm-yy",
            minDate: "+1d"
        });
        $('#product_category').select2({
            dropdownParent: $('#productCategoryDiv'+rfqdetail.id),
        });
        $('#product_category').on('select2:selecting', function (evt) {
            last_category = $('#product_category').val();
        });
        var categoryID = $("#product_category").find("option:selected").attr('data-category-id');
        var rfqname = $("#product_category").find("option:selected").attr('data-rfqname');
        var onload = true;
        $('#category_id').val(categoryID);
        getSubCategory(categoryID,onload,rfqname,rfqdetail.sub_category);
        if (product.length == max_product){
            disableAllFields(true);
        }

    });

    $(document).on('change', '#fullrfqForm', function(e) {
        $("#resetform_{{$rfq->id}}").attr('data-boolean','true');
    });
    $(document).on('click', '#edit-tab', function(e) {
        $('.editmodal').find('#activities').removeClass('show');
        $('.editmodal').find('#activities').removeClass('active');
        $('.editmodal').find('#edit').addClass('show');
        $('.editmodal').find('#edit').addClass('active');
    });
    $(document).on('click', '#activities-tab', function(e) {
        $('.editmodal').find('#edit').removeClass('show');
        $('.editmodal').find('#edit').removeClass('active');
        $('.editmodal').find('#activities').addClass('show');
        $('.editmodal').find('#activities').addClass('active');
        var rfqId = $(this).attr('data-rfqid');
        $.ajax({
            url: "{{ route('dashboard-get-rfq-activity-ajax', '') }}" + "/" + rfqId,
            type: 'GET',
            success: function(successData) {
                if (successData.activityhtml) {
                    $('.timeline').html(successData.activityhtml);
                }

            },
            error: function() {
                console.log('error');
            }
        });
    });
    $('#postFulleditRfqForm'+rfqdetail.id).click( function(e) {

        e.preventDefault();
        var categorySelected = $("#product_category").find(':selected').attr('data-category-id');
        var subCategorySelected = $("#product_sub_category").find(':selected').attr('data-sub-category-id');
        var product = JSON.parse(localStorage.getItem("product_edit")) || [];
        // console.log();
        $('#productCategoryOtherDiv'+rfqdetail.id).find('.error').remove()
        $('#productSubCategoryOtherDiv'+rfqdetail.id).find('.error').remove()
        var formValidate = true;

        if (categorySelected == 0) {
            var othercategory = $('#othercategory'+rfqdetail.id).val();
            if (othercategory.trim().length == 0) {
                $('#othercategory'+rfqdetail.id).after('<p class="error" style="color:#ff0000;">This value is required.</p>');
                formValidate = false;
            } else {
                $('#productCategoryOtherDiv'+rfqdetail.id).find('.error').remove();
            }
        }
        if(product.length != 0){
            removeOrAddValidation(false);
        }
        /** Validation remove of state and city while onchnage - start **/
        SnippetEditRFQDeliveryDetailAddress.parsleyValidationRemoveForStateCity();
        /** Validation remove of state and city while onchnage - end **/
        if ($('.fullrfqForm'+rfqdetail.id).parsley().validate() && formValidate) {
            /*
            if($('#groupId').val()){
                let achievedQtyResponse = achieveQuantityValidate($('#groupId').val());
                if(achievedQtyResponse.responseJSON.success == true && product.length == 1){
                    let groupDataAchieveQty = achievedQtyResponse.responseJSON.groups;
                    let rfq_qty = product[0].quantity;
                    let achieved_qty = groupDataAchieveQty.achieved_quantity;
                    let target_qty = groupDataAchieveQty.target_quantity;
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
            }
            */

            //Get selected preferred supplier ids (Ronak M - 30/06/2022)
            suppliersArray = [];
            $("input:checkbox[name=supplier_chk]:checked").each(function() {
                suppliersArray.push($(this).val());
            });
            $("#preferredSuppliersIds").val(suppliersArray.join(","));
            $("#collapseExample").removeClass("show");
            $("#showPreferredSuppliers").attr("aria-expanded","false");
            //End

            $('#postFulleditRfqForm'+rfqdetail.id).attr('disabled', true);
            var formData = new FormData($('.fullrfqForm'+rfqdetail.id)[0]);
            if ($("#product_category").find(':selected').attr('data-category-id') == 0) {
                formData.append("category", $("#othercategory"+rfqdetail.id).val());
            } else {
                formData.append('category', $('#product_category').find('option:selected').attr('data-category'))
                formData.append('category_id', $('#product_category').find('option:selected').attr('data-category-id'))
            }

            formData.append("product_details", JSON.stringify(product));

            if ($("#product_sub_category").find(':selected').attr('data-sub-category-id') == 0) {
                if ($("#othersubcategory"+rfqdetail.id).val() != undefined && $("#othersubcategory"+rfqdetail.id).val().length > 0) {
                    formData.append("product_sub_category", $("#othersubcategory"+rfqdetail.id).val());
                } else {
                    formData.append("product_sub_category", 'Other');
                }
            } else {
                formData.append('product_sub_category', $('#product_sub_category').find('option:selected').attr('data-text'))
            }

            var need_rental_forklift = 0;
            var need_unloading_services = 0;
            if ($('#need_rental_forklift'+rfqdetail.id).prop("checked")) {
                need_rental_forklift = 1;
            }
            if ($('#need_unloading_services'+rfqdetail.id).prop("checked")) {
                need_unloading_services = 1;
            }
            formData.append('rental_forklift', need_rental_forklift)
            formData.append('unloading_services', need_unloading_services)
            formData.append('is_require_credit', $('#creditSwitchCheckDefault'+rfqdetail.id).prop('checked')?1:0)
            formData.append('groupId', $('#groupId').val())
            if($('#creditSwitchCheckDefault'+rfqdetail.id).prop('checked'))
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

            // console.log($('#groupId').val());
            // return false;
            //console.log('$('#groupId').val()', $('#groupId').val());
            $.ajax({
                url: "{{ route('quick-editrfq-post-ajax') }}",
                data: formData,
                type: 'POST',
                contentType: false,
                processData: false,
                success: function(successData) {
                    new PNotify({
                        text: "{{ __('dashboard.RFQ_update_successfully') }}",
                        type: 'success',
                        styling: 'bootstrap3',
                        animateSpeed: 'fast',
                        delay: 1000
                    });
                    $('#editModal').modal('hide');
                    $('#collapse'+rfqdetail.id).closest('.accordion-item').find('#rfqupdatecollpse').click();
                    $(".showeditmodal").html('');
                    $('#show_change_cat_validation').attr('data-value', false);
                    $('#collapse'+rfqdetail.id).closest('.accordion-item').find('#rfqupdatecollpse').click();
                    //$('.fullrfqForm'+rfqdetail.id).parsley().reset();
                    $('#postFulleditRfqForm'+rfqdetail.id).attr('disabled', false);
                    $('#show_change_cat_validation').attr('data-value', true);
                    //$(".editmodal").modal('hide');
                    $('#myAddressCount').html(successData.userAddressCount);
                    location.reload();
                },
                error: function() {
                    console.log('error');
                }
            });
        }
    });

    function changeCategory(data){
        var cat_id = $(data).find(':selected').attr('data-category-id');
        var cat_name = $(data).find(':selected').attr('data-category');
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
                $('#quickrfqPost #category_id').val(cat_id);
                $('#quickrfqPost #product_name').val(null);
                $('#quickrfqPost #productSearchResult').remove();
                $('#quickrfqPost #productCategoryOtherDiv').removeClass('d-none');
                $('#quickrfqPost #productSubCategoryOtherDiv').removeClass('d-none');
                $('#cloneProduct').html('');
                $('#quickrfqPost #other_category').val('');
                $('#product_name_change').attr('id', 'product_name_change').html('Product');
                var options = '<option selected disabled>Select Product Sub Category</option>';
                options += '<option  data-sub-category-id="0" value="Other">Other</option>';
                $('#quickrfqPost #product_sub_category').empty().append(options);
            } else {
                $('#quickrfqPost #category_id').val(cat_id);
                $('#quickrfqPost #productCategoryOtherDiv').addClass('d-none');
                $('#quickrfqPost #productSubCategoryOtherDiv').addClass('d-none');
                $('#product_name_change').html('Product');
                $('#product_name').attr('id', 'product_name').val('');
                $('#quantity').attr('id', 'quantity').val('');
                $("#unit").val($("#unit option:first").val());
                $('#product_description').attr('id', 'product_description').val('');
                $('#cloneProduct').html('');
                cat_ajax(cat_id);

            }
        } else {
            var validates = $('#show_change_cat_validation').attr('data-value');
            if(validates === 'true'){
                if($('#product_category').val() != last_category) {
                    alertMessage('{{ __("admin.alert_change_category") }}', true, 'change_cat', cat_id,"{{ __('dashboard.are_you_sure') }}?")
                    resetData();
                }
            }
        }

        if (categoryIsService) {
            $('.rfq_address').text('{{ __('dashboard.pickup_details') }}');
            $('#product_description').attr('placeholder', '{{ __('dashboard.Service_Product_Description_Placeholder') }}');
        } else {
            $('.rfq_address').text('{{ __('dashboard.Delivery_Details') }}');
            $('#product_description').attr('placeholder', '{{ __('dashboard.Product_Description_Placeholder') }}');
        }
    }

    function cat_ajax(cat_id) {

        $("#collapseExample").removeClass("show");
        $("#showPreferredSuppliers").attr({"aria-expanded":"false", "disabled":true});
        $("#inlineRadio1").prop("checked",true);
        //get preferred suppliers as per the selected category (Ronak M - 12/07/2022)
        getPreferredSuppByCategory(cat_id);

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

    function getSubCategory(categoryId,onload,rfqname,sub_cat){
        var rfqdata = @json($rfq);
        if(onload == false){
            $('#product_name').val(null);
            $('#product_name').attr('data-id', '');
            $('#productSearchResult').remove();
            if (categoryId == 0) {
                $('#productCategoryOtherDiv'+rfqdetail.id).removeClass('d-none');
                $('#othercategory'+rfqdetail.id).val('');
                var options = '<option selected disabled>Select Product Sub Category</option>';
                options += '<option  data-sub-category-id="0" value="Other">Other</option>';
                $('#product_sub_category').empty().append(options);
            } else {
                $('#productCategoryOtherDiv'+rfqdetail.id).addClass('d-none');
            }
        }else{
            if (categoryId == 0) {
                // alert(rfqdata.category);
                $('#productCategoryOtherDiv'+rfqdetail.id).removeClass('d-none');
                if(rfqname){
                    $('#othercategory'+rfqdetail.id).val(rfqname);
                }
                var options = '<option selected disabled>Select Product Sub Category</option>';
                options += '<option  data-sub-category-id="0" value="Other">Other</option>';
                $('#product_sub_category').empty().append(options);
                $("#product_sub_category").val(0);
            }else{
                $('#productCategoryOtherDiv'+rfqdetail.id).addClass('d-none');
            }
        }

        if (categoryId && categoryId != 0) {
            $.ajax({
                url: "{{ route('get-subcategory-ajax', '') }}" + "/" + categoryId,
                type: 'GET',
                success: function(successData) {
                    var options =
                        '<option selected disabled>Select Product Sub Category</option>';
                    if (successData.subCategory.length) {
                        var other  = 1;
                        successData.subCategory.forEach(function(data) {
                            options += '<option data-sub-category-id="' + data
                                .id + '" value="' + data.name +
                                '" data-text="' + data.name + '">' + data
                                .name + '</option>';
                                if(sub_cat == data.name){
                                    other = 0;
                                }
                        });
                    }
                    /*if(other == 1){
                        options += '<option data-sub-category-id="0" value="Other" selected>Other</option>';
                    }else{*/
                        options += '<option data-sub-category-id="0" value="Other">Other</option>';
                    /*}*/

                    $('#product_sub_category').empty().append(options);
                },
                error: function() {
                    console.log('error');
                }
            });
        }
    }
        /*$(document).on('change', '#product_category', function(e) {
            var product = JSON.parse(localStorage.getItem("product_edit")) || [];
            var onload = false;
            var rfqname = null;
            var sub_category = null;
            var categoryID = $(this).find(":selected").attr('data-category-id');
            if (product.length == 0) {
                if(categoryID) {
                    getSubCategory(categoryID,onload,rfqname,sub_category);
                }
            } else {
                var validates = $('#show_change_cat_validation').attr('data-value');
                if(validates === 'true'){
                    if($('#product_category').val() != last_category) {
                        alertMessage('{{ __("admin.alert_change_category") }}', true, 'change_cat', categoryID,"{{ __('dashboard.are_you_sure') }}?")
                    }
                }
            }
        });*/
        $(document).on('click', '#product_name', function(e) {
            $('#productSearchResult').removeClass('hidden');
        });
        $(document).on('focusout', '#product_name', function(e) {
            $('#productSearchResult').addClass('hidden');
        });

        $(document).on('click', '#product_description', function(e) {
            $('#productDescriptionSearchResult').removeClass('hidden');
        });
        $(document).on('mousedown', '.searchProductDescriptionPost', function(e) {
            $('#product_description').val($(this).attr('data-value'));
            $('#product_description').attr('data-id', $(this).attr('data-id'));
            $('#productDescriptionSearchResult').addClass('hidden');
        });

        $(document).on('focusout', '#product_description', function(e) {
            $('#productDescriptionSearchResult').addClass('hidden');
        });
        /*$(document).on('mousedown', '.searchProduct', function(e) {
            $('#product_name').val($(this).attr('data-value'));
            $('#product_name').attr('data-id', $(this).attr('data-id'));
            $('#product_id').val($(this).attr('data-id'))
            $('#productSearchResult').addClass('hidden');
            if ($(this).attr('data-sub-cat-id')) {
                var selectedSubCatValue = $('#product_sub_category').find('option[data-sub-category-id="' + $(this).attr('data-sub-cat-id') + '"]').val();
                $('#product_sub_category').val(selectedSubCatValue).trigger('change');
                $('#othersubcategory'+rfqdetail.id).val('');
            }
        });*/

    $(document).on('mousedown', '.searchProduct', function (e) {
        var txt = $(this).parent().attr('id');
        var id = txt.replace('productSearchResult', '');
        $('#product_name' + id).val($(this).attr('data-value'));
        $('#product_name' + id).attr('data-id', $(this).attr('data-id'));
        $('#product_id' + id).val($(this).attr('data-id'))
        $('#productSearchResult' + id).addClass('hidden');
        if ($(this).attr('data-sub-cat-id')) {
            //$('#quickrfqPost #product_sub_category').attr('data-sub-cat-id', $(this).attr('data-sub-cat-id'));
            //var selectedSubCatValue = $('#quickrfqPost #product_sub_category').find('option[data-sub-category-id="' + $(this).attr('data-sub-cat-id') + '"]').val();
            //$('#quickrfqPost #product_sub_category').val(selectedSubCatValue).trigger('change');
            $('#product_name_change' + id).html('Product : ' + $(this).attr('data-value'));
            //$('#quickrfqPost #other_subcategory').val('');
            $('#othersubcategory'+rfqdetail.id).val('');
        }
    });

    /***
     * Ekta Patel 01-06-2022
     * check acheive order qty when edit rfq
     */
    function achieveQuantityValidate(groupId){
        return $.ajax({
            url: "{{ route('achieveqty-group-details', '') }}" + "/" + groupId,
            type: "GET",
            dataType: 'json',
            async: false,
            success: function (successData) {
                return successData;
            },
            error: function () {
                console.log("error");
            },
        });

    }

    function changeSubCategory(data) {
        $('#productSearchResult').html('');
        var text = data.id;
        $('#product_id').val('');
        $('#product_name').val(null);
        $('#productSearchResult').remove();
        var replace_id = text.replace('product_sub_category', '');
        $('#product_sub_category_id'+replace_id).val($('#product_sub_category'+replace_id+' option:selected').attr('data-sub-category-id'));
    }

    function searchProductFullForm(text) {
        var text = text.trim();
        var subCategoryId = $('#product_sub_category').find('option:selected').attr('data-sub-category-id');
        var categoryId = $("#product_category").find("option:selected").attr('data-category-id');
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
                success: function(successData) {
                    $('#productSearchResult').remove();
                    var searchData = '<ul id="productSearchResult" class="searchResult">';
                    //$('#quickrfqPost #prod_sub_cat_post').val(0).trigger('change');
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
            $('#quickrfqPost #productSearchResult'+id).removeClass('hidden');
        } else {
            $('#quickrfqPost #productSearchResult').removeClass('hidden');
        }
    }

    function alertMessage(text, checkFromCategory, key, cat_id, title='') {
        var button = "{{ __('admin.ok') }}"
        var mailText = '';
        if(key == 'change_cat'){
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
                if(checkFromCategory){
                    removeOrAddValidation(true);
                    if (key == 'change_cat'){
                        cat_ajax(cat_id)
                    }
                    localStorage.clear();
                    $('#rfq_table tbody').html('');
                    $('#rfq_table > tbody:last-child').append(
                        '<tr id="no_data">'
                        +'<td>{{ __("admin.no_product_added") }}</td>'
                        +'</tr>');
                }
                disableAllFields(false);
            } else {
                if (key == 'change_cat') {
                    $('#product_category').val(last_category).trigger("change");
                }
            }
        });
    }

    function AddProduct(){
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
        }
        else {
            if($('#product_sub_category').val() == null && $('#product_category').val() == null){
                alertMessage('{{ __("admin.alert_product_cat_sub_cat") }}');
            } else if($('#product_sub_category').val() == null) {
                alertMessage('{{ __("admin.alert_product_sub_cat") }}');
            } else if($('#product_name').val() == '' || $('#quantity').val() == '' || $('#unit').val() == null || $('#product_description').val() == '' || $('#quantity').val() <= 0){
                if ($('#product_name').val() == ''){ alertMessage('{{ __("admin.alert_product_name_error") }}');
                } else if ($('#quantity').val() == ''){ alertMessage('{{ __("admin.alert_product_qty") }}');
                } else if ($('#quantity').val() <= 0){ alertMessage('{{ __("admin.alert_product_unit_zero") }}');
                } else if ($('#unit').val() == null){ alertMessage('{{ __("admin.alert_product_unit") }}');
                } else if ($('#product_description').val() == ''){ alertMessage('{{ __("admin.alert_product_description") }}'); }
            } else if(product.length == max_product){
                alertMessage('{{ sprintf(__('admin.multiple_product_add'),$max_product??5) }}')
            }
        }
        if("{{$rfq->group_id != null}}"){
            unableRfqEditFormControls();
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
        var buttons = '<a href="javascript:void(0);" class="p-1 mx-1" onclick="editProduct('+last_id+')"><img src="{{ URL::asset("front-assets/images/icons/icon_fillinmore.png") }}" alt="{{ __('admin.rfq_save') }}" class="align-top" style="max-height: 14px;"></a> <a href="javascript:void(0);"class="p-1 mx-1 deleteProduct" id="deleteProduct_'+last_id+'" onclick="deleteProduct('+last_id+')"><img src="{{ URL::asset("front-assets/images/icons/icon_delete_add.png") }}" alt="{{ __('admin.delete') }}" class="align-top" style="max-height: 14px;"></a>';
        var edit_id = 'edit_'+last_id;
        $('#rfq_table > tbody:last-child').append(
            '<tr id="'+edit_id+'">'
            +'<td>'+$("#product_name").val()+'</td>'
            +'<td>'+$("#product_description").val()+'</td>'
            +'<td>'+$("#product_category").val()+'</td>'
            +'<td>'+$('#quantity').val()+' '+ $('#unit').find(":selected").text() +'</td>'
            +'<td class="text-nowrap">'+buttons+'</td>'
            +'</tr>');

        resetData();
        if (product.length == max_product){
            disableAllFields(true);
        }
    }

    function disableAllFields(val, text) {
        if(val){
            $('#five_ptoduct_validation_msg').html('<small>{{ sprintf(__('admin.multiple_product_add'),$max_product??5) }}</small>');
        } else {
            if(text != 'edit'){
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
        var product = JSON.parse(localStorage.getItem("product_edit")) || [];
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
        var buttons = '<a href="javascript:void(0);" class="p-1 mx-1" onclick="editProduct('+data_id+')"><img src="{{ URL::asset("front-assets/images/icons/icon_fillinmore.png") }}" alt="{{ __('admin.rfq_save') }}" class="align-top" style="max-height: 14px;"></a> <a href="javascript:void(0);"class="p-1 mx-1 deleteProduct " id="deleteProduct_'+data_id+'" onclick="deleteProduct('+data_id+')"><img src="{{ URL::asset("front-assets/images/icons/icon_delete_add.png") }}" alt="{{ __('admin.delete') }}" class="align-top" style="max-height: 14px;"></a>';
        $('#'+dispaly_edit).html('');
        $('#'+dispaly_edit).append('<td>'+$("#product_name").val()+'</td>'
            +'<td>'+$("#product_description").val()+'</td>'
            +'<td>'+$("#product_sub_category").val()+'</td>'
            +'<td>'+$('#quantity').val()+' '+ $('#unit').find(":selected").text() +'</td>'
            +'<td class="text-nowrap">'+buttons+'</td>')
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
                    if("{{$rfq->group_id != null}}"){
                        unableRfqEditFormControls();
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
                var product = JSON.parse(localStorage.getItem("product_edit"));
                $('#edit_'+id).remove();
                for (let i = 0; i < product.length; i++) {
                    if(product[i]['id'] == id){
                        product.splice(i, 1);
                    }
                }
                window.localStorage.setItem('product_edit', JSON.stringify(product));
                if(product.length == 0){
                    resetData();
                    removeOrAddValidation(true);
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

    /**
     * Ronak Bhabhor 21-05-2022
     * disableRfqEditFormControls: disable edit form controls while rfq from group edit
     */
    function disableRfqEditFormControls(){
        // $('#product_category').prop('disabled',true);
        // $('#product_sub_category').prop('disabled',true);
        // $('#product_name').prop('disabled',true);
        // $('#unit').prop('disabled',true);
        // $('#product_description').prop('disabled',true);
        $('.deleteProduct').addClass('d-none');
        $('#quantity').prop('disabled',false);
        $('#add_product_btn').prop('disabled',false); // enable btn for update quantity
    }

    /**
     * Ronak Bhabhor 21-05-2022
     * unableRfqEditFormControls: unable edit form controls while rfq from group edit
     */
    function unableRfqEditFormControls(){
        // $('#product_category').prop('disabled',false);
        // $('#product_sub_category').prop('disabled',false);
        // $('#product_name').prop('disabled',false);
        // $('#unit').prop('disabled',false);
        $('#quantity').prop('disabled',true);
        // $('#product_description').prop('disabled',true);
        $('#add_product_btn').prop('disabled',true); // disable btn for restrict add new product
        $('.deleteProduct').addClass('d-none'); //disable button after save
    }

    function editProduct(id){

        $('.deleteProduct').removeClass('d-none')
        var product = JSON.parse(localStorage.getItem("product_edit")) || [];
	    if(product.length == max_product){
            disableAllFields(false, 'edit');
        }
        if("{{$rfq->group_id != null}}"){
            disableRfqEditFormControls();
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
                //$('.deleteProduct').removeClass('d-none')
            }
        }

    }

    function resetMainForm(value) {
        if($(value).data('boolean') == true){
            swal({
                title: "{{ __('dashboard.are_you_sure') }}?",
                text: "{{ __('admin.save_changes_data_rfq_cancel') }}",
                icon: "/assets/images/warn.png",
                buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
                dangerMode: true,
            }).then((willChange) => {
                if (willChange) {
                    $("#resetform_"+rfqdetail.id).attr('data-boolean','false');
                    $("#postFulleditRfqForm"+rfqdetail.id).trigger('click');
                } else {
                    $('#editModal').modal('hide');
                    $("#resetform_"+rfqdetail.id).attr('data-boolean','false');
                    $('#collapse'+rfqdetail.id).closest('.accordion-item').find('#rfqupdatecollpse').click();
                    //$(".showeditmodal").html('');
                }
            });
        } else {
            $('#editModal').modal('hide');
            $('#collapse'+rfqdetail.id).closest('.accordion-item').find('#rfqupdatecollpse').click();
            $(".showeditmodal").html('');
        }
    }

    /*****begin: Edit Post a requirement Delivery Address******/
    var SnippetEditRFQDeliveryDetailAddress = function(){

        var isDocumentLoad = false;

        var selectStateGetCity = function(){

                $('#stateEditId').on('change',function(){

                    let state = $(this).val();
                    let targetUrl = "{{ route('admin.city.by.state',":id") }}";
                    targetUrl = targetUrl.replace(':id', state);
                    var newOption = '';

                    // Add Remove Other State filed
                    if (state == -1) {
                        $('#stateEditId_block').removeClass('col-md-4');
                        $('#stateEditId_block').removeClass('col-md-6');
                        $('#stateEditId_block').addClass('col-md-3');

                        $('#state_block').removeClass('hide');
                        $('#state').attr('required','required');

                        $('#cityEditId_block').removeClass('col-md-4');
                        $('#cityEditId_block').addClass('col-md-3');

                        $('#cityEditId').empty();

                        //set default options on other state mode
                        newOption = new Option('{{ __('admin.select_city') }}','', true, true);
                        $('#cityEditId').append(newOption).trigger('change');

                        newOption = new Option('Other','-1', true, true);
                        $('#cityEditId').append(newOption).trigger('change');


                    } else {
                        $('#stateEditId_block').removeClass('col-md-3');
                        $('#stateEditId_block').addClass('col-md-6');

                        $('#state_block').addClass('hide');
                        $('#state').removeAttr('required','required');

                        $('#cityEdit_block').addClass('hide');
                        $('#ecity').removeAttr('required','required');

                        //Fetch cities by state
                        if (state != '') {
                            $.ajax({
                                url: targetUrl,
                                type: 'POST',
                                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},

                                success: function (response) {

                                    if (response.success) {

                                        $('#cityEditId').empty();

                                        newOption = new Option('{{ __('admin.select_city') }}', '', true, true);
                                        $('#cityEditId').append(newOption).trigger('change');

                                        for (let i = 0; i < response.data.length; i++) {
                                            newOption = new Option(response.data[i].name, response.data[i].id, true, true);
                                            $('#cityEditId').append(newOption).trigger('change');
                                        }

                                        newOption = new Option('Other', '-1', true, true);
                                        $('#cityEditId').append(newOption).trigger('change');

                                        /*******begin:Add and remove last null option for no conflict*******/
                                        newOption = new Option('0', '0', true, true);
                                        $('#cityEditId').append(newOption).trigger('change');
                                        $('#cityEditId').each(function () {
                                            $(this).find("option:last").remove();
                                        });
                                        /*******end:Add and remove last null option for no conflict*******/

                                        let selectedAddressCity = $('#useraddress_id option:selected').attr('data-city-id');
                                        if (selectedAddressCity != null && selectedAddressCity != '') {
                                            $('#cityEditId').val(selectedAddressCity).trigger('change');
                                        } else {
                                            $('#cityEditId').val(null).trigger('change');
                                        }

                                    }

                                },
                                error: function () {

                                }
                            }).done(function() {
                                if (!isDocumentLoad) {
                                    $("#resetform_"+rfqdetail.id).attr('data-boolean','false');
                                    isDocumentLoad = true;
                                }
                            });
                        } else {
                            $('#cityEditId').empty();

                            newOption = new Option('Other', '-1', true, true);
                            $('#cityEditId').append(newOption).trigger('change');

                            $('#cityEditId').val(null).trigger('change');

                        }

                    }

                });

            },

            selectCitySetOtherCity = function(){

                $('#cityEditId').on('change',function(){

                    let city = $(this).val();

                    // Add Remove Other City filed
                    if (city == -1) {
                        $('#cityEditId_block').removeClass('col-md-6');
                        $('#cityEditId_block').addClass('col-md-3');

                        $('#cityEdit_block').removeClass('hide');
                        $('#ecity').attr('required','required');

                        $('#stateEditId_block').removeClass('col-md-6');
                        if ($('#stateEditId').val()==-1) {
                            $('#stateEditId_block').addClass('col-md-3');
                        } else {

                            $('#stateEditId_block').addClass('col-md-6');

                        }

                    } else {
                        $('#cityEditId_block').removeClass('col-md-3');
                        $('#cityEditId_block').addClass('col-md-6');

                        $('#cityEdit_block').addClass('hide');
                        $('#ecity').removeAttr('required','required');

                    }

                });

            },

            initiateCityState = function(){

                let state               =   $('#estate').val();
                let selectedState       =   $('#stateEditId').val();

                if (state != null && state !='') {
                    $('#stateEditId').val('-1').trigger('change');
                }

                if (selectedState !=='' && selectedState != null) {
                    $('#stateEditId').val(selectedState).trigger('change');
                }

            },

            select2Initiate = function(){

                $('#stateEditId').select2({
                    dropdownParent  : $('#stateEditId_block'),
                    placeholder:  $(this).attr('data-placeholder')

                });

                $('#cityEditId').select2({
                    dropdownParent  : $('#cityEditId_block'),
                    placeholder:  $(this).attr('data-placeholder')

                });

            },

            disableCityState = function(){

                let status  =   $('#estate').is('[readonly]');
                if (status) {
                    $('#stateEditId').select2({
                        disable:true

                    });

                    $('#cityEditId').select2({
                        disable:true

                    });

                }
            },
            enableAddress = function () {
                $('#eaddress_name').attr('readonly',false);
                $('#eaddressLine1').attr('readonly',false);
                $('#eaddressLine2').attr('readonly',false);
                $('#esub_district').attr('readonly',false);
                $('#edistrict').attr('readonly',false);
                $('#stateEditId').attr('readonly',false);
                $('#cityEditId').attr('readonly',false);
                $('#estate').attr('readonly',false);
                $('#ecity').attr('readonly',false);
                $('#epincode').attr('readonly',false);
            },
            isAddressBelongs = function (addressId) {
                $.ajax({
                    url: "{{route('address.belongs')}}",
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    type: 'POST',
                    data: {
                        id : addressId
                    },
                    success: function (data) {
                        if (!data.success) {
                            $('#eaddress_name').attr('readonly',true);
                            $('#eaddressLine1').attr('readonly',true);
                            $('#eaddressLine2').attr('readonly',true);
                            $('#esub_district').attr('readonly',true);
                            $('#edistrict').attr('readonly',true);
                            $('#stateEditId').attr('readonly',true);
                            $('#cityEditId').attr('readonly',true);
                            $('#estate').attr('readonly',true);
                            $('#ecity').attr('readonly',true);
                            $('#epincode').attr('readonly',true);

                        } else if ($('#rfq_status').val()==1){
                            enableAddress();
                        }
                    },
                    error: function () {
                        console.log('Code - 500 | Error ')
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

                $(document).ready(function(){
                    let addressId = $('#useraddress_id').val();
                    isAddressBelongs(addressId);
                });

            }

        return {
            init:function(){
                selectStateGetCity(),
                selectCitySetOtherCity(),
                initiateCityState(),
                select2Initiate(),
                disableCityState(),
                checkAddressBelongsTo()
            },
            parsleyValidationRemoveForStateCity : function () {
                window.Parsley.on('form:validated', function(){
                    $('select').on('select2:select', function(evt) {
                        $("#stateEditId").parsley().validate();
                        $("#cityEditId").parsley().validate();
                    });
                });
            }
        }

    }(1);jQuery(document).ready(function(){
        SnippetEditRFQDeliveryDetailAddress.init();
    });
    /*****end: Edit Post a requirement Delivery Address******/

    //get preferred suppliers as per the selected category (Ronak M - 12/07/2022)
    function getPreferredSuppByCategory(cat_id) {
        var isDocumentLoad = false;
        if (cat_id != '') {
            $.ajax({
                url: "{{ route('get-preferred-suppliers-by-category-ajax', '') }}" + "/" + cat_id,
                type: 'GET',
                success: function (successData) {
                    if (successData.suppliersData.length) {
                        $(".supply-selector").removeClass('d-none');
                    } else {
                        $(".supply-selector").addClass('d-none');
                    }
                },
                error: function () {
                    console.log('error');
                }
            }).done(function() {
                if (!isDocumentLoad) {
                    $("#resetform_"+rfqdetail.id).attr('data-boolean','false');
                    isDocumentLoad = true;
                }
            });
        } else {
            if (!isDocumentLoad) {
                $("#resetform_"+rfqdetail.id).attr('data-boolean','false');
                isDocumentLoad = true;
            }
        }
    }
    $("#creditSwitchCheckDefault"+rfqdetail.id).on('change',function(){
        $(".credit-type-div").toggleClass('hide')
    })
</script>
