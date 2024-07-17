@extends('dashboard/contact/layout')

@section('content')
    <style>
        @keyframes slideInFromLeft {
            0% {
                transform: translateY(-100%);
            }

            100% {
                transform: translateY(0);
            }
        }

        .qoute_icons li.active::before {
            /* This section calls the slideInFromLeft animation we defined above */
            animation: 1s ease-out 0s 1 slideInFromLeft;
        }

        .qoute_icons li a span {
            -moz-transition: all .3s ease-in;
            -o-transition: all .3s ease-in;
            -webkit-transition: all .3s ease-in;
            transition: all .3s ease-in;
        }

        .qoute_icons li.active a span {
            background-color: #FFC107;
            -moz-transition: all .3s ease-in;
            -o-transition: all .3s ease-in;
            -webkit-transition: all .3s ease-in;
            transition: all .3s ease-in;
        }

    </style>
    <div class="row gx-4 mx-lg-0 quoteforms joinGroup_popup">
        <div class="col-lg-12 py-2">
            <div class="header_top d-flex align-items-center pb-4">
                <h1 class="mb-0">{{ __('rfqs.full_rfq_title') }}</h1>
                @if (Auth::user())
                    <a class="btn-close ms-auto rounded-circle" href="{{ route('group-trading') }}"><img
                        src="{{ URL::asset('front-assets/images/icons/close.png') }}" alt="">
                    </a>
                @else
                    <a class="btn-close ms-auto rounded-circle" href="{{ route('home') }}"><img
                        src="{{ URL::asset('front-assets/images/icons/close.png') }}" alt="">
                    </a>
                @endif
            </div>
            <div class="card radius_1  mb-4">
                <div class="card-body floatlables">
                    <div class="row" data-spy="scroll" data-bs-target="#list-example" data-bs-offset="0"
                        tabindex="0">
                        <div class="col-md-2 d-none d-md-block justify-content-center text-center">
                            <ul id="list-example" class="qoute_icons list-group sticky-top mx-auto">
                                <li class="action2"><a href="#list-item-2" class="rounded-circle">
                                    <span class="rounded-circle">
                                        <img src="{{ URL::asset('front-assets/images/icons/icon_product_details.png') }}" alt="Product Detail">
                                    </span></a>
                                </li>
                                <li class="action3"><a href="#list-item-3" class="rounded-circle">
                                    <span class="rounded-circle">
                                        <img src="{{ URL::asset('front-assets/images/icons/icon_delivery_details.png') }}" alt="Delivery Detail">
                                    </span></a>
                                </li>
                                <li class="action1"><a href="#list-item-1" class="rounded-circle">
                                    <span class="rounded-circle">
                                        <img src="{{ URL::asset('front-assets/images/icons/icon_contact_details.png') }}" alt="Contact Detail">
                                    </span></a>
                                </li>
                                <!-- <li class="action4"><a href="#list-item-4" class="rounded-circle">
                                    <span class="rounded-circle">
                                        <img src="{{ URL::asset('front-assets/images/icons/icon_credit_request.png') }}" alt="Require Credit">
                                    </span></a>
                                </li> -->
                            </ul>
                        </div>

                        <div class="col-md-10 scrollspy-example error_res" id="fullRfqDataBlock">
                            <form data-parsley-validate autocomplete="off" name="fullrfqForm" id="fullrfqForm" enctype="multipart/form-data">
                            @csrf
                            <section id="step-2" class="mb-3">
                                <div class="row g-3" id="fullrfq_step2" id="fullrfq_step2">
                                    <div class="col-md-12">
                                        <h6 id="list-item-2" class="mb-0 dark_blue text-primary">{{ __('rfqs.Product_Details') }}</h6>
                                    </div>
                                    <div class="col-md-12" id="productCategoryDiv">
                                        <select class="form-select" id="productCategory" name="prod_cat_post" aria-label="Default select example" required>
                                            <option selected disabled>{{ __('dashboard.select_product_category') }}</option>
                                            @foreach ($category as $categoryItem)
                                                <option value="{{ $categoryItem->id }}" data-text="{{ $categoryItem->name }}">{{ $categoryItem->name }}</option>
                                            @endforeach
                                            <option value="0">Other</option>
                                        </select>
                                        <label>{{ __('dashboard.select_product_category') }}<span class="text-danger">*</span></label>
                                        <input type="hidden" name="prod_cat_post" id="product_category_val" value="" />
                                    </div>
                                    <div class="col-md-12 d-none" id="productCategoryOtherDiv">
                                        <input class="form-control" id="othercategory" type="text" placeholder="Other Category">
                                        <label>{{ __('dashboard.other_product_category') }}<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-12" id="productSubCategoryDiv">
                                        <select class="form-select" id="productSubCategory" name="prod_sub_cat_post" aria-label="Default select example" required>
                                            <option selected disabled>{{ __('admin.select_product_sub_category') }}</option>
                                        </select>
                                        <label>{{ __('admin.select_product_sub_category') }}<span class="text-danger">*</span></label>
                                        <input type="hidden" name="prod_sub_cat_post" id="product_subcategory_val" value="" />
                                    </div>
                                    <div class="d-none col-md-12" id="productSubCategoryOtherDiv">
                                        <input class="form-control" id="othersubcategory" type="text" placeholder="{{ __('dashboard.Other_Sub_Category') }}">
                                        <label>{{ __('dashboard.Other_Sub_Category') }}</label>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" name="prod_name_post" onkeyup="searchProductFullForm(this.value)" id="prod_name" required>
                                        <label>{{ __('dashboard.Product_Name') }}<span class="text-danger">*</span></label>
                                        <input type="hidden" name="prod_name_post" id="product_name_val" value="" />
                                    </div>
                                    <div class="col-md-12">
                                        <textarea class="form-control" placeholder="{{ __('dashboard.Product_Description_Placeholder') }}" name="prod_desc" id="prod_desc" required></textarea>
                                        <label>{{ __('dashboard.Product_Description') }}<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-12">
                                        <textarea class="form-control" placeholder="{{ __('dashboard.Comment_Placeholder') }}" name="comment" id="comment"></textarea>
                                        <label for="comment">{{ __('dashboard.Comment') }}</label>
                                    </div>
                                    {{-- <div class="row g-3 hidden" id="brandBlock">
                                        <div class="col-md-6">
                                            <label>{{ __('dashboard.Brands') }}</label>
                                            <div class="brand-select form-control" style="height: 45px;">
                                                <div class="mw-500 " id="brandData" style="min-height:30px">
                                                    @foreach ($brand as $brandItem)
                                                        <label data-id="{{ $brandItem->id }}"
                                                            data-value="{{ $brandItem->name }}"
                                                            class="selectBrand">{{ $brandItem->name }}</label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" placeholder="other brand"
                                                id="otherBrand">
                                            <label for="otherBrand">{{ __('dashboard.Other Brand') }}</label>
                                        </div>
                                    </div>
                                    <div class="row g-3 hidden" id="gradeBlock">
                                        <div class="col-md-6">

                                            <label>{{ __('dashboard.Grades') }}</label>
                                            <div class="brand-select form-control" style="height: 45px;">
                                                <div class="mw-500 " id="gradeData" style="min-height:30px">
                                                    @if (count($grade))

                                                        @foreach ($grade as $gradeItem)
                                                            <label data-id="{{ $gradeItem->id }}"
                                                                data-value="{{ $gradeItem->name }}"
                                                                class="selectGrade">{{ $gradeItem->name }}</label>
                                                        @endforeach
                                                    @else
                                                        &nbsp;
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">

                                            <input type="text" class="form-control" placeholder="other grade"
                                                id="otherGrade">
                                            <label for="otherBrand">{{ __('dashboard.Other Grade') }}</label>
                                        </div>

                                    </div>--}}

                                    <div class="col-md-4">
                                        <input type="number" class="form-control" id="quantity" required
                                            data-parsley-type="number" placeholder="0" min="1">
                                        <label>{{ __('dashboard.Quantity') }}<span class="text-danger">*</span></label>
                                    </div>

                                    <div class="col-md-4">
                                        <select class="form-select" id="unitBlock" aria-label="Default select example" required>
                                            <option selected disabled value="">*{{ __('dashboard.Select Unit') }}</option>
                                            @foreach ($unit as $unitItem)
                                                <option value="{{ $unitItem->id }}">{{ $unitItem->name }}</option>
                                            @endforeach
                                        </select>
                                        <label>{{ __('dashboard.Unit') }}<span class="text-danger">*</span></label>
                                        <input type="hidden" name="" id="unit_val" value="" />
                                    </div>

                                    <div class="col-md-4">
                                        <label for="" class="form-label">{{ __('rfqs.upload_attachment') }}</label>
                                        <div class="form-control d-flex">
                                            <span class="">
                                                <input type="file" name="rfq_attachment_doc[]" class="form-control" id="rfq_attachment_doc" accept=".jpg,.png,.jpeg,.pdf" onchange="showFile(this)" hidden="" multiple>
                                                <label id="upload_btn" for="rfq_attachment_doc" style="padding: 2px 10px;">{{ __('profile.browse') }}</label>
                                            </span>
                                            <div id="file-rfq_attachment_doc" class="d-flex align-items-center">
                                                <input type="hidden" class="form-control" id="old_attachment_file" name="old_attachment_file" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <section id="step-3" class="mb-3">
                                <div class="row g-3" id="fullrfq_step3">
                                    <div class="col-md-12">
                                        <h6 id="list-item-3" class="mb-0 dark_blue text-primary">{{ __('dashboard.Delivery_Details') }}</h6>
                                    </div>
                                    {{-- <div class="col-md-6 col-lg-4">
                                        <input type="text" class="form-control" pattern=".{5,}" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\.*?)\..*/g, '$1');" id="pincode" required>
                                        <label for="formGroupExampleInput"
                                            class="form-label">{{ __('dashboard.delivery_location') }}<span class="text-danger">*</span></label>
                                    </div> --}}
                                    <div class="col-md-6">
                                        <div class="col-md-12" id="address_block">
                                        <label class="form-label">{{ __('rfqs.select_address') }}<span class="text-danger">*</span></label>
                                        <select class="form-select" id="useraddress_id" name="useraddress_id" required>
                                            <option disabled selected>{{ __('rfqs.select_delivery_address') }}</option>
                                            @foreach ($userAddress as $item)
                                                <option data-address_name="{{$item->address_name}}" data-address_line_2="{{$item->address_line_2}}" data-address_line_1="{{$item->address_line_1}}" data-sub_district="{{$item->sub_district}}" data-district="{{$item->district}}" data-city="{{$item->city}}" data-state="{{$item->state}}" data-state-id="{{$item->state_id}}" data-city-id="{{$item->city_id}}" data-pincode="{{$item->pincode}}" value="{{ $item->id }}">{{ $item->address_name }}</option>
                                            @endforeach
                                            <option data-address-id="0" value="0">Other</option>
                                        </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="address_name" class="form-label">{{ __('rfqs.address_name') }}<span class="text-danger">*</span></label>
                                        <input type="text" name="address_name" class="form-control" id="address_name"
                                            required="">
                                    </div>
                                    <div class="col-md-12">
                                        <label for="addressLine1" class="form-label">{{ __('rfqs.address_line1') }}<span class="text-danger">*</span></label>
                                        <input type="text" name="address_line_1" class="form-control" id="addressLine1"
                                            required>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="addressLine2" class="form-label">{{ __('rfqs.address_line2') }}</label>
                                        <input type="text" class="form-control" name="address_line_2" id="addressLine2">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="sub_district" class="form-label">{{ __('rfqs.sub_district') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="sub_district" id="sub_district" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="district" class="form-label">{{ __('rfqs.district') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="district" id="district" required>
                                    </div>
                                    <div class="col-md-3 select2-block" id="stateId_block">
                                        <label for="stateId" class="form-label">{{ __('rfqs.provinces') }}<span class="text-danger">*</span></label>
                                        <select class="form-select select2-custom" id="stateId" name="stateId" data-placeholder="{{ __('rfqs.select_province') }}" required>
                                            <option value="" >{{ __('rfqs.select_province') }}</option>
                                            @foreach ($states as $state)
                                                <option value="{{ $state->id }}" >{{ $state->name }}</option>
                                            @endforeach
                                            <option value="-1">Other</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3 hide" id="state_block">
                                        <label for="state" class="form-label">{{ __('rfqs.other_provinces') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="state" id="state" required>
                                    </div>

                                    <div class="col-md-3 select2-block" id="cityId_block">
                                        <label for="cityId" class="form-label">{{ __('rfqs.city') }}<span class="text-danger">*</span></label>
                                        <select class="form-select select2-custom" id="cityId" name="cityId" data-placeholder="{{ __('rfqs.select_city') }}" required>
                                            <option value="">{{ __('rfqs.select_city') }}</option>
                                            <option value="-1">Other</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3 hide" id="city_block">
                                        <label for="city" class="form-label">{{ __('rfqs.other_city') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="city" id="city" >
                                    </div>

                                    <div class="col-md-3">
                                        <label for="delivery_pincode_post" class="form-label">{{ __('dashboard.Delivery_Pincode') }}<span class="text-danger">*</span></label>
                                        <input type="text" pattern=".{5,}" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\.*?)\..*/g, '$1');" id="delivery_pincode_post" class="form-control" name="pincode" id="pincode" required data-parsley-type="number">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control calendericons" placeholder="dd-mm-yyyy" name="expected_date" id="expected_date" required readonly>
                                        <label>{{ __('dashboard.Expected_Delivery_Date') }}<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check me-3">
                                            <input class="form-check-input" type="checkbox" value="1"
                                                id="need_rental_forklift" name="need_rental_forklift">
                                            <label class="form-check-label" for="need_rental_forklift">
                                                {{ __('dashboard.need_rental_forklift') }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check me-3">
                                            <input class="form-check-input" type="checkbox" value="1"
                                                id="need_unloading_services" name="need_unloading_services">
                                            <label class="form-check-label" for="need_unloading_services">
                                                {{ __('dashboard.need_unloading_services') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <section id="list-item-1" class="mb-4">
                                <div id="fullrfq_step1" class="row g-4">
                                    <div class="col-md-12">
                                        <h6 class="mb-0 dark_blue text-primary">{{ __('rfqs.full_rfq_contact_detail') }}</h6>
                                    </div>
                                    <input type="hidden" name="groupId" id="groupId" value="" />
                                    <input type="hidden" name="GroupProductId" id="GroupProductId" />
                                    <input type="hidden" name="achievedQuantity" id="achievedQuantity"  />
                                    <input type="hidden" name="targetQuantity" id="targetQuantity"  />
                                    <input type="hidden" name="unitName" id="unitName"  />
                                    <input type="hidden" name="grpProdDesc" id="grpProdDesc"  />

                                    <div class="col-md-6">
                                        <label>{{ __('rfqs.first_name') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="firstname" name="firstname"
                                            required value="{{ Auth::user() ? Auth::user()->firstname : '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label>{{ __('rfqs.last_name') }}<span class="text-danger">*</span></label>
                                        <input type="text" id="lastName" class="form-control" name="lastName" required
                                            value="{{ Auth::user() ? Auth::user()->lastname : '' }}">

                                    </div>
                                    <div class="col-md-6">
                                        <input type="email" id="email" name="email" class="form-control"
                                            value="{{ Auth::user() ? Auth::user()->email : '' }}" required>
                                        <label>{{ __('rfqs.Email') }}<span class="text-danger">*</span></label>
                                    </div>

                                    <div class="col-md-6">
                                        <input type="tel" class="form-control" id="mobile" required data-parsley-type="digits" data-parsley-length="[9, 16]" data-parsley-length-message="It should be between 9 and 16 digit." placeholder="XXXXXXXXXXX" required
                                            name="mobile" value="{{ Auth::user() ? Auth::user()->mobile : '' }}">
                                        <label>{{ __('rfqs.Contact_Number') }}<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                            </section>

                            <section id="step-4" class="d-none">
                                <div class="row g-3" id="fullrfq_step4">
                                    <div class="col-md-12">
                                        <h6 id="list-item-4" class="mb-0 dark_blue text-primary">{{ __('dashboard.payment_details') }}</h6>
                                    </div>
                                    <div class="col-md-6 align-items-center d-flex">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="creditSwitchCheckDefault">
                                            <label class="form-check-label" for="creditSwitchCheckDefault">{{ __('dashboard.require_credit') }}?</label>
                                        </div>
                                    </div>
                                </div>
                            </section>
                                <div class="row">
                                    <div class="col-12 text-end mt-3">
                                        <button type="button" class="btn btn-primary px-3 py-2 mb-1" id="postFullRfqForm">
                                            <img src="{{ URL::asset('front-assets/images/icons/icon_post_require.png') }}" alt="Post Requirement" class="pe-1">{{ __('dashboard.post_requirement') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var max_attachments = '{{ $max_attachments??0 }}';
        function loadUserActivityData() {
            $.ajax({
                url: "{{ route('dashboard-user-activity-ajax') }}",
                type: 'GET',
                success: function(successData) {
                    console.log("loaded")
                    $('#userActivitySection').html(successData.userActivityHtml);
                },
                error: function() {
                    console.log('error');
                }
            });
        }

        $("document").ready(function(){
            // $("#productCategory").prop('disabled', true);
            $("#productCategory").attr('disabled','disabled');
        })

        var selectedProductSubCatId = 0;

        // $('#useraddress_id').select2({
        //     dropdownParent: $('#address_block'),
        //     allowClear: true
        // });
        var defaultAddress = @json($defaultAddress);
        if(defaultAddress){
            //console.log(defaultAddress);
            $('#useraddress_id').val(defaultAddress.id);
            let selected_option = $('#useraddress_id');
            let stateId     = (defaultAddress.state_id == '' && defaultAddress.state_id == 0) ? -1 : defaultAddress.state_id;
            let cityId      = (defaultAddress.city_id == '' && defaultAddress.city_id == 0) ? -1 : defaultAddress.city_id;

            $("#address_name").val(defaultAddress.address_name);
            $("#addressLine1").val(defaultAddress.address_line_1);
            $("#addressLine2").val(defaultAddress.address_line_2);
            $("#sub_district").val(defaultAddress.sub_district);
            $("#district").val(defaultAddress.district);
            $("#city").val(defaultAddress.city);
            $("#state").val(defaultAddress.state);
            $("#delivery_pincode_post").val(defaultAddress.pincode);
            $("#stateId").val(stateId).trigger('change');
            setTimeout(function () { $("#cityId").val(cityId).trigger('change') }, 500);
            // addressFieldsBind(selected_option);
        }

        $(document).on('change','#useraddress_id',function(){
            let selected_option = $('option:selected', this);
            let city        = selected_option.attr('data-city') != 'null' ? selected_option.attr('data-city') : '';
            let provinces   = selected_option.attr('data-state') != 'null' ? selected_option.attr('data-state') : '';
            let stateId     = (selected_option.attr('data-state-id') == '' && selected_option.attr('data-state-id') == 0) ? -1 : selected_option.attr('data-state-id');
            let cityId     = (selected_option.attr('data-city-id') == '' && selected_option.attr('data-city-id') == 0) ? -1 : selected_option.attr('data-city-id');

            //console.log(selected_option);
            $("#address_name").val(selected_option.attr('data-address_name'));
            $("#addressLine1").val(selected_option.attr('data-address_line_1'));
            $("#addressLine2").val(selected_option.attr('data-address_line_2'));
            $("#sub_district").val(selected_option.attr('data-sub_district'));
            $("#district").val(selected_option.attr('data-district'));
            $("#city").val(selected_option.attr('data-city'));
            $("#state").val(selected_option.attr('data-state'));
            $("#delivery_pincode_post").val(selected_option.attr('data-pincode'));
            $("#city").val(city ?? '');
            $("#provinces").val(provinces ?? '');
            $("#stateId").val(stateId).trigger('change');
        });

        //Attachment Document
        function showFile(input) {
            var rdt = cdt = new DataTransfer();
            var fileName = '';
            if(input.id == 'rfq_attachment_doc' && input.files.length > max_attachments){
                swal({
                    icon: 'error',
                    title: '',
                    text: '{{ sprintf(__('admin.multiple_attachment_add'),$max_attachments??0) }}'
                })
                $('#file-' + input.id).html('');
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
                            $('#file-' + input.id).html('');
                        } else {
                            $('#rfq_attachment_doc')[0].files = rdt.files;
                        }

                        checkFileSizeCount = 0;
                    });
                }
            }

        }

        function searchProductFullForm(text) {
            var text = text.trim();
            var subCategoryId = $('#fullrfq_step2 #productSubCategory').find(':selected').val();
            if (subCategoryId == "{{ __('admin.select_product_sub_category') }}") {
                subCategoryId = null;
            }
            var categoryId = $('#fullrfq_step2 #productCategory').find(':selected').val();
            if (categoryId == "{{ __('dashboard.select_product_category') }}") {
                categoryId = null;
            }
            if (categoryId && subCategoryId != 0) {
                var data = {
                    product: text,
                    subCategoryId: subCategoryId,
                    categoryId: categoryId,
                    _token: $('meta[name="csrf-token"]').attr('content')
                }
                $.ajax({
                    url: '{{ route('search-product-ajax') }}',
                    data: data,
                    type: 'POST',
                    success: function(successData) {
                        $('#fullrfq_step2 #productSearchResult').remove();
                        var dataArray = [];
                        var searchData = '<ul id="productSearchResult" class="searchResult">';

                        if (successData.filterData.length) {
                            successData.filterData.forEach(function(data) {
                                if (!dataArray.includes(data.name)) {
                                    dataArray.push(data.name);
                                    searchData += '<li data-sub-cat-id="' + data.subcategory_id + '" data-id="' + data.id + '" data-value="' + data.name + '" class="searchProduct">' + data.name + '</li>';
                                }
                            });
                        }
                        searchData += '</ul>';
                        $('#fullrfq_step2 #prod_name').after(searchData);


                        var dbProduct = [];
                        $('#fullrfq_step2 #productSearchResult li').each(function() {
                            dbProduct.push($(this).text().toLowerCase());
                        });

                        if (dbProduct.includes(text.toLowerCase())) {
                            var dataSubCatId = $('#fullrfq_step2 #productSearchResult').find(
                                'li[data-value*="' + text.trim() + '"]').attr('data-sub-cat-id');
                            if (dataSubCatId) {
                                selectedProductSubCatId = dataSubCatId;
                            } else {
                                selectedProductSubCatId = 0;
                            }
                            // if (dataSubCatId) {
                            //     $('#fullrfq_step2 #productSubCategory').val(dataSubCatId);
                            // } else {
                            //     $('#fullrfq_step2 #productSubCategory').val(0);
                            // }
                            // $('#fullrfq_step2 #productSubCategoryDiv').addClass('col-md-6')
                            //     .removeClass('col-md-3');
                            // $('#fullrfq_step2 #productSubCategoryOtherDiv').addClass(
                            //     'd-none');
                        } else {
                            console.log("else");
                            selectedProductSubCatId = 0;
                            // $('#fullrfq_step2 #productSubCategory').val(0);
                            // $('#fullrfq_step2 #productSubCategoryDiv').removeClass('col-md-6')
                            //     .addClass('col-md-3');
                            // $('#fullrfq_step2 #productSubCategoryOtherDiv').removeClass(
                            //     'd-none');
                        }
                        console.log("selectedProductSubCatId from pro ", selectedProductSubCatId)
                    },
                    error: function() {
                        console.log('error');
                    }
                });
            }
        }

        function searchProductDescriptionFullForm(text) {
            var product = $('#fullrfq_step2 #prod_name').val();

            var data = {
                productDescription: text,
                product: product,
                _token: $('meta[name="csrf-token"]').attr('content')
            }
            if (product.length) {
                $.ajax({
                    url: "{{ route('search-product-description-ajax') }}",
                    data: data,
                    type: 'POST',
                    success: function(successData) {
                        $('#fullrfq_step2 #productDescriptionSearchResult').remove();
                        var dataArray = [];
                        var searchData =
                            '<ul id="productDescriptionSearchResult" class="descriptionSearchResult">';
                        if (successData.filterData.length) {
                            successData.filterData.forEach(function(data) {
                                if (data.description) {
                                    if (!dataArray.includes(data.description)) {
                                        dataArray.push(data.description);
                                        searchData += '<li data-id="' + data.id + '" data-value="' +
                                            data.description +
                                            '" class="searchProductDescription">' +
                                            data.description + '</li>';
                                    }
                                }
                            });
                        }
                        searchData += '</ul>'

                        $('#fullrfq_step2 #prod_desc').after(searchData);
                    },
                    error: function() {
                        console.log('error');
                    }
                });
            }
        }

        async function productCategoryChange(categoryId) {
            return new Promise(resolve => {
                $('#fullrfq_step2 #productSubCategoryDiv').removeClass('col-md-3').addClass('col-md-6');
                $('#fullrfq_step2 #productSubCategoryOtherDiv').addClass('d-none col-md-3').removeClass(
                    'col-md-6');
                $('#fullrfq_step2 #othercategory').val('');
                $('#fullrfq_step2 #othersubcategory').val('');
                $('#fullrfq_step2 #prod_name').val('');
                $('#fullrfq_step2 #productSearchResult').remove();
                $('#brandBlock').addClass('hidden');
                $('#gradeBlock').addClass('hidden');
                if (categoryId == 0) {
                    //$('#fullrfq_step2 #productCategoryDiv').removeClass('col-md-6').addClass('col-md-3');
                    $('#fullrfq_step2 #productCategoryOtherDiv').removeClass('d-none');
                    var options =
                        "<option selected disabled>{{ __('admin.select_product_sub_category') }}</option>";
                    options += '<option value="0">Other</option>';
                    // $('#fullrfq_step2 #productSubCategory').empty().append(options);
                    $('#fullrfq_step2 #productSubCategory').empty().append(options);
                    $('#fullrfq_step2 #productSubCategoryDiv').addClass('d-none');
                    // $('#fullrfq_step2 #productSubCategoryOtherDiv').removeClass('d-none col-md-3').addClass(
                    //     'col-md-6');
                    $("#fullrfq_step2 #productSubCategory").val(0);
                } else {
                    $('#fullrfq_step2 #productCategoryDiv').removeClass('col-md-3').addClass('col-md-6');
                    $('#fullrfq_step2 #productCategoryOtherDiv').addClass('d-none');
                    $('#fullrfq_step2 #productSubCategoryDiv').removeClass('d-none');
                }
                if (categoryId && categoryId != 0) {
                    $.ajax({
                        url: "{{ route('get-subcategory-ajax', '') }}" + "/" + categoryId,
                        type: 'GET',
                        success: function(successData) {

                            $('#fullrfq_step2 #prod_name').val('');
                            var options =
                                "<option selected disabled>{{ __('admin.select_product_sub_category') }}</option>";
                            if (successData.subCategory.length) {
                                successData.subCategory.forEach(function(data) {
                                    options += '<option value="' + data.id +'" data-text="' + data.name + '">' + data.name + '</option>';
                                });
                            }
                            options += '<option value="0">Other</option>';

                            $('#productSubCategory').empty().append(options);
                            //$('#unitBlock').empty().append(unitoptions);
                            resolve('resolved');

                        },
                        error: function() {
                            console.log('error');
                        }
                    });
                } else {
                    resolve('resolved');
                }
            });

        }

        async function productSubCategoryChange(subCategoryId) {
            return new Promise(resolve => {
                $('#fullrfq_step2 #othersubcategory').val('');
                $('#fullrfq_step2 #prod_name').val('');
                $('#fullrfq_step2 #productSearchResult').remove();
                if (subCategoryId == 0) {
                    $('#fullrfq_step2 #productSubCategoryDiv').removeClass('col-md-6').addClass('col-md-3');
                    // $('#fullrfq_step2 #productSubCategoryOtherDiv').removeClass('d-none');
                } else {
                    $('#fullrfq_step2 #productSubCategoryDiv').removeClass('col-md-3').addClass('col-md-6');
                    $('#fullrfq_step2 #productSubCategoryOtherDiv').addClass('d-none');
                }
                resolve('resolved');
                // if (subCategoryId && subCategoryId != 0) {
                //     $.ajax({
                //         url: "{{ route('get-brand-and-grade-ajax', '') }}" + "/" + subCategoryId,
                //         type: 'GET',
                //         success: function(successData) {
                //             var brand = '';
                //             var grade = '';

                //             if (successData.brands.length) {
                //                 $('#brandBlock').removeClass('hidden');
                //                 successData.brands.forEach(function(data) {
                //                     brand += '<label class="selectBrand" data-value="' +
                //                         data.name +
                //                         '" data-id="' + data.id + '">' + data
                //                         .name + '</label>';
                //                 });
                //                 $('#brandData').empty().append(brand);
                //             } else {
                //                 $('#brandBlock').addClass('hidden');
                //             }

                //             if (successData.grades.length) {
                //                 $('#gradeBlock').removeClass('hidden');
                //                 successData.grades.forEach(function(data) {
                //                     grade += '<label class="selectGrade" data-value="' +
                //                         data.name +
                //                         '" data-id="' + data.id + '">' + data
                //                         .name + '</label>';
                //                 });
                //                 $('#gradeData').empty().append(grade);
                //             } else {
                //                 $('#gradeBlock').addClass('hidden');
                //             }
                //             resolve('resolved');

                //         },
                //         error: function() {
                //             console.log('error');
                //         }
                //     });
                // } else {
                //     $('#brandBlock').addClass('hidden');
                //     $('#gradeBlock').addClass('hidden');
                //     resolve('resolved');
                // }
            });
        }

        async function setLocalStorageStep1Data() {

            var groupId = localStorage.getItem('groupId');
            if (groupId && groupId.length && groupId != "undefined") {
                $('#fullrfq_step1 #groupId').val(groupId);
            }

            var GroupProductId = localStorage.getItem('GroupProductId');
            if (GroupProductId && GroupProductId.length && GroupProductId != "undefined") {
                $('#fullrfq_step1 #GroupProductId').val(GroupProductId);
            }

            var achievedQuantity = localStorage.getItem('achievedQuantity');
            if (achievedQuantity && achievedQuantity.length && achievedQuantity != "undefined") {
                $('#fullrfq_step1 #achievedQuantity').val(achievedQuantity);
            }

            var targetQuantity = localStorage.getItem('targetQuantity');
            if (targetQuantity && targetQuantity.length && targetQuantity != "undefined") {
                $('#fullrfq_step1 #targetQuantity').val(targetQuantity);
            }

            var unitName = localStorage.getItem('unitName');
            if (unitName && unitName.length && unitName != "undefined") {
                $('#fullrfq_step1 #unitName').val(unitName);
            }

            var firstname = localStorage.getItem('firstname');
            if (firstname && firstname.length && firstname != "undefined") {
                $('#fullrfq_step1 #firstname').val(firstname);
            }

            var lastname = localStorage.getItem('lastname');
            if (lastname && lastname.length && lastname != "undefined") {
                $('#fullrfq_step1 #lastName').val(lastname);
            }

            var phone_code = localStorage.getItem('phone_code');
            if (phone_code && phone_code.length && phone_code != "undefined") {
                $('#fullrfq_step1 input[name="phone_code"]').val(phone_code);
            }

            var iso2 = localStorage.getItem('iso2');
            if (iso2 && iso2.length && iso2 != "undefined") {
                iti.setCountry(iso2);
            }

            var mob_number = localStorage.getItem('mob_number');
            if (mob_number && mob_number.length && mob_number != "undefined") {
                $('#fullrfq_step1 #mobile').val(mob_number);
            }

            var email = localStorage.getItem('email');
            if (email && email.length && email != "undefined") {
                $('#fullrfq_step1 #email').val(email);
            }

            var category = localStorage.getItem('category');
            var sub_category = localStorage.getItem('sub_category');

            if (category && category.length && category != "undefined") {
                $("#fullrfq_step2 #productCategory").val(category).attr('disabled', true);
                $("#fullrfq_step2 #product_category_val").val(category);
                await productCategoryChange(category);
                if (category == 0) {
                    var otherCategory = localStorage.getItem('otherCategory');
                    $("#fullrfq_step2 #othercategory").val(otherCategory);
                }
            }
            if (sub_category && sub_category.length && sub_category != "undefined") {
                $("#fullrfq_step2 #productSubCategory").val(sub_category).attr('disabled', true);
                $("#fullrfq_step2 #product_subcategory_val").val(sub_category);
                selectedProductSubCatId = sub_category;
                await productSubCategoryChange(sub_category);
                if (sub_category == 0) {
                    var othersubcategory = localStorage.getItem('othersubcategory');
                    $("#fullrfq_step2 #othersubcategory").val(othersubcategory);
                }
            }

            var product = localStorage.getItem('product');
            if (product && typeof product != "undefined" && product != "undefined") {
                $("#fullrfq_step2 #prod_name").val(product).attr('disabled', true);
                $("#fullrfq_step2 #product_name_val").val(product);
                await getBrandAndGrade();
            }

            // var product_description = localStorage.getItem('product_description');
            // if (product_description && typeof product_description != "undefined" &&
            //     product_description != "undefined") {
            //     $("#fullrfq_step2 #prod_desc").val(product_description);
            // }

            var groupProductDesc = localStorage.getItem('groupProductDesc');
            if (groupProductDesc && groupProductDesc.length && groupProductDesc != "undefined") {
                $("#fullrfq_step2 #prod_desc").val(groupProductDesc).attr('disabled', true);
                $('#fullrfq_step2 #prod_desc').val(groupProductDesc);

                $('#fullrfq_step1 #grpProdDesc').val(groupProductDesc);


            }

            var quantity_post = localStorage.getItem('quantity_post');
            if (quantity_post && typeof quantity_post != "undefined" && quantity_post !=
                "undefined") {
                $("#fullrfq_step2 #quantity").val(quantity_post);
            }

            var unit_post = localStorage.getItem('unit_post');
            if (unit_post && typeof unit_post != "undefined" && unit_post != "undefined" &&
                unit_post != 'null') {
                $("#fullrfq_step2 #unitBlock").val(unit_post).attr('disabled', true);
                $("#fullrfq_step2 #unit_val").val(product);
            }


            var delivery_pincode_post = localStorage.getItem('delivery_pincode_post');
            if (delivery_pincode_post && typeof delivery_pincode_post != "undefined" &&
                delivery_pincode_post != "undefined") {
                $("#fullrfq_step3 #pincode").val(delivery_pincode_post);
            }

            var expected_date_post = localStorage.getItem('expected_date');
            if (expected_date_post && typeof expected_date_post != "undefined" &&
                expected_date_post != "undefined") {
                $("#fullrfq_step3 #expected_date").val(expected_date_post);
            }


            var requirecredit = parseInt(localStorage.getItem('requirecredit'));
            if(requirecredit) {
                $('#fullrfq_step4 #creditSwitchCheckDefault').prop("checked",true)
            }

            var comment_post = localStorage.getItem('comment');
            if (comment_post && typeof comment_post != "undefined" &&
                comment_post != "undefined") {
                $("#fullrfq_step2 #comment").val(comment_post);
            }
            var need_rental_forklift = parseInt(localStorage.getItem('need_rental_forklift'));
            if(need_rental_forklift) {
                $('#fullrfq_step3 #need_rental_forklift').prop("checked",true)
            }

            var need_unloading_services = parseInt(localStorage.getItem('need_unloading_services'));
            if(need_unloading_services) {
                $('#fullrfq_step3 #need_unloading_services').prop("checked",true)
            }

        }

        function getBrandAndGrade() {
            return new Promise(resolve => {
                var productName = $('#fullrfq_step2 #prod_name').val();
                if (productName.length) {
                    var data = {
                        productName: productName,
                        subCategoryId: $('#fullrfq_step2 #productSubCategory').val(),
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    }
                    $.ajax({
                        url: "{{ route('get-brand-and-grade-ajax', '') }}",
                        type: 'POST',
                        data: data,
                        success: function(successData) {
                            var brand = '';
                            var grade = '';

                            $('#brandBlock').removeClass('hidden');
                            if (successData.brands.length) {
                                successData.brands.forEach(function(data) {
                                    brand += '<label class="selectBrand" data-value="' +
                                        data.name +
                                        '" data-id="' + data.id + '">' + data
                                        .name + '</label>';
                                });
                                $('#brandData').empty().append(brand);
                            } else {
                                $('#brandBlock').addClass('hidden');
                            }

                            $('#gradeBlock').removeClass('hidden');
                            if (successData.grades.length) {
                                successData.grades.forEach(function(data) {
                                    grade += '<label class="selectGrade" data-value="' +
                                        data.name +
                                        '" data-id="' + data.id + '">' + data
                                        .name + '</label>';
                                });
                                $('#gradeData').empty().append(grade);
                            } else {
                                $('#gradeBlock').addClass('hidden');
                            }
                            resolve('resolved');

                        },
                        error: function() {
                            console.log('error');
                        }
                    });
                } else {
                    $('#brandBlock').addClass('hidden');
                    $('#gradeBlock').addClass('hidden');
                    resolve('resolved');
                }
            });
        }

        $(window).on('load', function() {
            setLocalStorageStep1Data();
        });
        $(document).ready(function() {
            // Toolbar extra buttons
            $('#expected_date').datepicker({
                dateFormat: "dd-mm-yy",
                minDate: "+1d"
                // appendText: "dd-mm-yyyy"
            });

            /* $('#expected_date').datepicker({
                dateFormat: "dd-mm-yy",
                minDate: "+1d"
            }); */
            $('#expected_date').on('change', function (ev) {
                // alert('sdfkjs');
                // $(this).datepicker('hide');
                $('#expected_date').parsley().reset();
            });

            $('#postFullRfqForm').on('click', function(e) {
                e.preventDefault();
                //$('#postFullRfqForm').attr('disabled', true);
                var formValidate = true;
                var categorySelected = $("#fullRfqDataBlock #productCategory").find(':selected').val();
                var subCategorySelected = $("#fullRfqDataBlock #productSubCategory").find(':selected').val();
                $('#productCategoryOtherDiv .error').remove()
                $('#productSubCategoryOtherDiv .error').remove();
                if (categorySelected == 0) {
                    var othercategory = $('#fullRfqDataBlock #othercategory').val();
                    if (othercategory.trim().length == 0) {
                        $('#fullrfq_step2 #othercategory').after('<p class="error">This value is required.</p>');
                        formValidate = false;
                    } else {
                        $('#fullrfq_step2 #productCategoryOtherDiv .error').remove();
                    }
                }
                if ($('#fullrfqForm').parsley().validate() && formValidate) {

                    //Show popup if (RFQ_qty + achieved_qty) is greater than target_qty
                    var rfq_qty = parseInt($("#fullRfqDataBlock #quantity").val());
                    var achieved_qty = parseInt($("#fullRfqDataBlock #achievedQuantity").val());
                    var target_qty = parseInt($("#fullRfqDataBlock #targetQuantity").val());
                    var totalQty = rfq_qty + achieved_qty;
                    var remainingQty = target_qty - achieved_qty;
                    /*
                    if(totalQty > target_qty) {
                        let text = "{{__('admin.rfq_quantity_should_not_be_greater')}} " + remainingQty + ' ' + $("#fullRfqDataBlock #unitName").val() ;
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
                    var formValidate = true;
                    var need_rental_forklift = 0;
                    var need_unloading_services = 0;
                    if ($('#fullRfqDataBlock #need_rental_forklift').prop("checked")) {
                        need_rental_forklift = 1;
                    }
                    if ($('#fullRfqDataBlock #need_unloading_services').prop("checked")) {
                        need_unloading_services = 1;
                    }
                    var formdata = new FormData();
                    formdata.append("_token", $('meta[name="csrf-token"]').attr('content'));
                    formdata.append("firstname", $("#fullRfqDataBlock #firstname").val());
                    formdata.append("lastname", $("#fullRfqDataBlock #lastName").val());
                    formdata.append("phone_code", $('#fullRfqDataBlock input[name="phone_code"]').val());
                    formdata.append("mobile", $("#fullRfqDataBlock #mobile").val());
                    formdata.append("email", $("#fullRfqDataBlock #email").val());

                    if ($("#fullRfqDataBlock #productCategory").find(':selected').val() == 0) {
                        formdata.append("productCategory", $("#fullRfqDataBlock #othercategory").val());
                    } else {
                        formdata.append("productCategory", $("#fullRfqDataBlock #productCategory").find(':selected').attr('data-text'));
                    }

                    if ($("#fullRfqDataBlock #productSubCategory").find(':selected').val() == 0) {
                        if ($("#fullRfqDataBlock #othersubcategory").val().length > 0) {
                            formdata.append("productSubCategory", $("#fullRfqDataBlock #othersubcategory").val());
                        } else {
                            formdata.append("productSubCategory", 'Other');
                        }
                    } else {
                        formdata.append("productSubCategory", $("#fullRfqDataBlock #productSubCategory").find(':selected').attr('data-text'));
                    }

                    var brandDataSelected = [];
                    $("#fullRfqDataBlock #brandData .selected").text(function(index, text) {
                        brandDataSelected.push($(this).attr('data-id'))
                        // dataselected.push(text)
                    });
                    formdata.append("brandData", brandDataSelected);
                    formdata.append("other_preferred_brand", $("#fullRfqDataBlock #otherBrand")
                        .val());
                    var gradeDataSelected = [];
                    $("#fullRfqDataBlock #gradeData .selected").text(function(index, text) {
                        gradeDataSelected.push($(this).attr('data-id'))
                        // dataselected.push(text)
                    });
                    formdata.append("gradeData", gradeDataSelected);
                    formdata.append("categoryId",$("#product_category_val").val());
                    formdata.append("sub_categoryId",$("#product_subcategory_val").val());
                    formdata.append("other_preferred_grade", $("#fullRfqDataBlock #otherGrade").val());
                    formdata.append("product", $("#fullRfqDataBlock #prod_name").val());
                    formdata.append("productDescription", $("#fullRfqDataBlock #prod_desc").val());
                    formdata.append("quantity", $("#fullRfqDataBlock #quantity").val());
                    formdata.append("unit", $("#fullRfqDataBlock #unitBlock").find(':selected').val());
                    formdata.append("unitName", $("#fullRfqDataBlock #unitName").val());

                    formdata.append("useraddress_id", $("#fullRfqDataBlock #useraddress_id").find(':selected').val());
                    formdata.append("address_name", $("#fullRfqDataBlock #address_name").val());
                    formdata.append("address_line_1", $("#fullRfqDataBlock #addressLine1").val());
                    formdata.append("address_line_2", $("#fullRfqDataBlock #addressLine2").val());
                    formdata.append("sub_district", $("#fullRfqDataBlock #sub_district").val());
                    formdata.append("district", $("#fullRfqDataBlock #district").val());
                    formdata.append("city", $("#fullRfqDataBlock #city").val());
                    formdata.append("state", $("#fullRfqDataBlock #state").val());
                    formdata.append("cityId", $("#fullRfqDataBlock #cityId").val());
                    formdata.append("stateId", $("#fullRfqDataBlock #stateId").val());
                    $.each($('#fullRfqDataBlock #rfq_attachment_doc')[0].files,function(key,input){
                        formdata.append('rfq_attachment_doc[]', input);
                    });
                    formdata.append("groupId", $("#fullRfqDataBlock #groupId").val());
                    formdata.append("GroupProductId", $("#fullRfqDataBlock #GroupProductId").val());
                    formdata.append("achievedQuantity", $("#fullRfqDataBlock #achievedQuantity").val());
                    formdata.append("targetQuantity", $("#fullRfqDataBlock #targetQuantity").val());
                    formdata.append("grpProdDesc", $("#fullRfqDataBlock #grpProdDesc").val());

                    formdata.append("paymentTerm", $("#fullRfqDataBlock input[name='paymentTerm']:checked").val());
                    formdata.append("pincode", $("#fullRfqDataBlock #delivery_pincode_post").val());
                    formdata.append("expected_date", $("#fullRfqDataBlock #expected_date").val());
                    formdata.append("comment", $("#fullRfqDataBlock #comment").val());
                    formdata.append('rental_forklift', need_rental_forklift)
                    formdata.append('unloading_services', need_unloading_services)
                    formdata.append('is_require_credit', $('#creditSwitchCheckDefault').prop('checked')?1:0)
                    $('#postFullRfqForm').prop('disabled', true);
                    $.ajax({
                        url: "{{ route('add-full-rfq-ajax') }}",
                        type: 'POST',
                        data: formdata,
                        contentType: false,
                        processData: false,
                        success: function(successData) {
                            new PNotify({
                                text: "{{ __('dashboard.RFQ_Sent_successfully') }}",
                                type: 'success',
                                styling: 'bootstrap3',
                                animateSpeed: 'slow',
                                delay: 2000
                            });
                            $('#postFullRfqForm').prop('disabled', false);
                            setInterval(function() {
                                location.reload(true);
                                var url = "{{ route('dashboard') }}";
                                window.location.replace(url);
                            }, 1000);
                            //var url = "{{ route('dashboard') }}";
                            //window.location.replace(url);
                            //location.reload();
                        },
                        error: function() {
                            console.log('error');
                            $('#postFullRfqForm').prop('disabled', false);
                        }
                    });
                }
            });

            $(document).on('change', '#productCategory', function(e) {
                var categoryId = $(this).find(':selected').val();
                productCategoryChange(categoryId);
            });

            $(document).on('change', '#productSubCategory', function(e) {
                var subCategoryId = $(this).find(':selected').val();
                productSubCategoryChange(subCategoryId);
            });

            $(document).on('click', '#prod_name', function(e) {
                $('#fullrfq_step2 #productSearchResult').removeClass('hidden');
            });

            $(document).on('mousedown', '.searchProduct', function(e) {
                $('#fullrfq_step2 #prod_name').val($(this).attr('data-value'));
                $('#fullrfq_step2 #prod_name').attr('data-id', $(this).attr('data-id'));
                $('#fullrfq_step2 #productSearchResult').addClass('hidden');

                if ($(this).attr('data-sub-cat-id')) {
                    $('#fullrfq_step2 #productSubCategory').val($(this).attr('data-sub-cat-id'));
                    $('#fullrfq_step2 #productSubCategoryDiv').removeClass('col-md-3').addClass('col-md-6');
                    // $('#fullrfq_step2 #productSubCategoryOtherDiv').addClass('d-none col-md-3').removeClass(
                    //     'col-md-6');
                    $('#fullrfq_step2 #othersubcategory').val('');

                    selectedProductSubCatId = $(this).attr('data-sub-cat-id');

                }
            });

            $(document).on('focusout', '#prod_name', function(e) {
                $('#fullrfq_step2 #productSearchResult').addClass('hidden');
                getBrandAndGrade();
                console.log('selectedProductSubCatId', selectedProductSubCatId)
                if (selectedProductSubCatId.length > 0 && selectedProductSubCatId != 0) {

                    $('#fullrfq_step2 #productSubCategory').val(selectedProductSubCatId);
                    $('#fullrfq_step2 #productSubCategoryDiv').addClass('col-md-6')
                        .removeClass('col-md-3');
                    $('#fullrfq_step2 #productSubCategoryOtherDiv').addClass(
                        'd-none');
                } else {
                    $('#fullrfq_step2 #productSubCategory').val(0);
                    $('#fullrfq_step2 #productSubCategoryDiv').removeClass('col-md-6')
                        .addClass('col-md-3');
                    // $('#fullrfq_step2 #productSubCategoryOtherDiv').removeClass(
                    //     'd-none');
                }

            });

            $(document).on('click', '#prod_desc', function(e) {
                $('#fullrfq_step2 #productDescriptionSearchResult').removeClass('hidden');
            });

            $(document).on('mousedown', '.searchProductDescription', function(e) {
                $('#fullrfq_step2 #prod_desc').val($(this).attr('data-value'));
                $('#fullrfq_step2 #prod_desc').attr('data-id', $(this).attr('data-id'));
                $('#fullrfq_step2 #productDescriptionSearchResult').addClass('hidden');
            });

            $(document).on('focusout', '#prod_desc', function(e) {
                $('#fullrfq_step2 #productDescriptionSearchResult').addClass('hidden');
            });

            $(document).on('click', '.selectBrand', function(e) {
                $(this).toggleClass('selected');
            });

            $(document).on('click', '.selectGrade', function(e) {
                $(this).toggleClass('selected');
            });

            $('section#list-item-1').on('click', function() {
                $('.action1').addClass('active');
                $('.action2').removeClass('active');
                $('.action3').removeClass('active');
                $('.action4').removeClass('active');
            });

            $('section#step-2').on('click', function() {
                $('.action2').addClass('active');
                $('.action1').removeClass('active');
                $('.action3').removeClass('active');
                $('.action4').removeClass('active');
            });


            $('section#step-3').on('click', function() {
                $('.action3').addClass('active');
                $('.action2').removeClass('active');
                $('.action1').removeClass('active');
                $('.action4').removeClass('active');
            });
            $('section#step-4').on('click', function() {
                $('.action4').addClass('active');
                $('.action3').removeClass('active');
                $('.action2').removeClass('active');
                $('.action1').removeClass('active');
            });
        });
    </script>
    <script src="{{ URL::asset('front-assets/intlTelInput/js/intlTelInput.js') }}"></script>
    <script>

        var input = document.querySelector("#mobile");
        var iti = window.intlTelInput(input, {
            initialCountry:"id",
            separateDialCode:true,
            dropdownContainer:null,
            preferredCountries:["id"],
            hiddenInput:"phone_code"
        });

        $("#mobile").focusin(function(){
            let countryData = iti.getSelectedCountryData();
            $('input[name="phone_code"]').val(countryData.dialCode);
        });

        $("#mobile").focusin(function(){
            let countryData = iti.getSelectedCountryData();
            $('input[name="phone_code"]').val(countryData.dialCode).attr('iso2',countryData.iso2);
        });

        $(document).ready(function() {
            @php
                $phoneCode = Auth::user()->phone_code?str_replace('+','',Auth::user()->phone_code):62;
                $country = Auth::user()->phone_code?strtolower(getRecordsByCondition('countries',['phone_code'=>$phoneCode],'iso2',1)):'id';
            @endphp
            $('input[name="phone_code"]').val({{ $phoneCode }}).attr('iso2','{{$country}}');
            iti.setCountry('{{$country}}');
        });

        /*****begin: Add Get a Quote Delivery Detail******/
        var SnippetAddContactDeliveryDetail = function(){

            var selectStateGetCity = function(){

                    $('#stateId').on('change',function(){

                        let state = $(this).val();
                        let targetUrl = "{{ route('admin.city.by.state',":id") }}";
                        targetUrl = targetUrl.replace(':id', state);
                        var newOption = '';

                        // Add Remove Other State filed
                        if (state == -1) {

                            $('#state_block').removeClass('hide');
                            $('#state').attr('required','required');

                            $('#cityId').empty();

                            //set default options on other state mode
                            newOption = new Option('{{ __('admin.select_city') }}','', true, true);
                            $('#cityId').append(newOption).trigger('change');

                            newOption = new Option('Other','-1', true, true);
                            $('#cityId').append(newOption).trigger('change');


                        } else {

                            $('#state_block').addClass('hide');
                            $('#state').removeAttr('required','required');

                            $('#city_block').addClass('hide');
                            $('#city').removeAttr('required','required');

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
                            }

                        }

                    });

                },

                selectCitySetOtherCity = function(){

                    $('#cityId').on('change',function(){

                        let city = $(this).val();

                        // Add Remove Other City filed
                        if (city == -1) {
                            $('#city_block').removeClass('hide');
                            $('#city').attr('required','required');

                        } else {

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
            SnippetAddContactDeliveryDetail.init();
        });
        /*****end: Add Get a Quote Delivery Detail******/

    </script>
@stop
