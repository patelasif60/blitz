@extends('dashboard/layout/group_layout')
@section('content')
    <link rel="stylesheet" href="{{ URL::asset('front-assets/intlTelInput/css/intlTelInput.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('/assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <style>
        #social-links ul{
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
        #social-links .fa-facebook{
            color: #0d6efd;
        }
        #social-links .fa-twitter{
            color: deepskyblue;
        }
        #social-links .fa-linkedin{
            color: #0e76a8;
        }
        #social-links .fa-whatsapp{
            color: #25D366
        }
        #social-links .fa-reddit{
            color: #FF4500;;
        }
        #social-links .fa-telegram{
            color: #0088cc;
        }

        .tooltip-inner {
            font-size: 0.7rem !important;
            max-width: 150px !important;
        }

        .iti{
            display: block !important;
        }
        .iti .iti--allow-dropdown{width: 100% !important;}
        .iti__flag-container{position: absolute !important;}
        .iti__country-list{ overflow-x: hidden; max-width: 360px;}
        /*.fa.fa-instagram {
          color: transparent;
          background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);
          background: -webkit-radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);
          background-clip: text;
          -webkit-background-clip: text;
        } */
    </style>

    @if(!isset(auth()->user()->role_id))
        <div id="carouselCaptions" class="carousel slide bannersection py-3" data-bs-ride="carousel" data-aos="fade-down"></div>
        <div class="header_top d-flex align-items-center mt-2 mb-3 container group_section1">
            <h1 class="mb-0  color-primary">{{ __('dashboard.group_details') }}</h1>
            <a href="{{ url()->previous() == route('dashboard') ? route('dashboard') : route('group-trading')}}" class="btn btn-primary ms-auto btn-sm  f-14" style="padding-top: .1rem; padding-bottom: .1rem;">
                <img class="d-none" src="{{ URL::asset('front-assets/images/icons/angle-left.png') }}" alt="Back"><span> {{ __('profile.back') }} </span>
            </a>
        </div>
    @else
        <div class="header_top d-flex align-items-center Group_nav mt-0">
            <div>
                <ul class="nav nav-tabs mt-1 border-0" id="mainTab" role="tablist">
                    <li class="nav-item mx-2 changetab" role="presentation" id="header_profile_tab">
                        <button class="nav-link active" id="personal-tab" data-bs-toggle="tab"
                                data-bs-target="#personal" type="button" role="tab" aria-controls="personal"
                                aria-selected="true">{{__('admin.details')}}</button>
                    </li>
                    <li class="nav-item mx-2 changetab" role="presentation" id="header_admin_setting">
                        <button class="nav-link" id="company-tab" data-bs-toggle="tab" data-bs-target="#company"
                                type="button" role="tab" aria-controls="company"
                                aria-selected="false">{{__('admin.activities')}}</button>
                    </li>

                </ul>
            </div>
            <a href="{{ url()->previous() == route('dashboard') ? route('dashboard') : route('group-trading')}}" class="btn btn-warning ms-auto btn-sm"
               style="padding-top: .1rem; padding-bottom: .1rem;">
                <img src="{{ URL::asset('front-assets/images/icons/angle-left.png') }}" alt="">
                {{ __('profile.back') }}
            </a>
        </div>
    @endif

    <input type="hidden" id="is_user_login" value="{{ (!Auth::user()) ? 0 : 1 }}" />

    <!-- Hidden values for category, subcategory, product and unit id -->
    <input type="hidden" name="groupId" id="groupId_{{$group->id}}" value="{{ $group->id }}" />
    <input type="hidden" name="categoryId" id="categoryId_{{$group->id}}" value="{{ $group->grpCatId }}" />
    <input type="hidden" name="subCategoryId" id="subCategoryId_{{$group->id}}" value="{{ $group->grpSubCatId }}" />
    <input type="hidden" name="grpProdId" id="grpProdId_{{$group->id}}" value="{{ $group->grpProdId }}" />
    <input type="hidden" name="productName" id="productName_{{$group->id}}" value="{{ $group->productName }}" />
    <input type="hidden" name="unitId" id="unitId_{{$group->id}}" value="{{ $group->grpUnitId }}" />
    <input type="hidden" name="unitName" id="unitName_{{$group->id}}" value="{{ $group->unit }}" />
    <input type="hidden" name="achievedQuantity" id="achievedQuantity_{{$group->id}}" value="{{ $group->achieved_quantity }}" />
    <input type="hidden" name="targetQuantity" id="targetQuantity_{{$group->id}}" value="{{ $group->target_quantity }}" />
    <input type="hidden" name="groupProdDesc" id="groupProdDesc_{{$group->id}}" value="{{ $group->product_description }}" />
    <!-- End -->

    <!-- Group Section Start -->
    <div class="container mb-2 mt-2 group_details" id="">
        <div class="row">

            <!-- tab -->
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                    <div class="mb-2 mt-2">
                        <div class="row align-items-start h-auto">
                            <div class="col-md-4 mb-2 sticky-top">
                                <div id="bootstrapSlider" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        @if(isset($groupImages) && sizeof($groupImages) > 0)
                                            @php $i=1; @endphp
                                            @foreach($groupImages as $grpImage)
                                                @if($i == 1)
                                                    <div class="carousel-item active">
                                                        <img src="{{ url('/storage/'.$grpImage->image) }}" alt="{{ __('admin.no_image') }}">
                                                    </div>
                                                @else
                                                    <div class="carousel-item">
                                                        <img src="{{ url('/storage/'.$grpImage->image) }}" alt="{{ __('admin.no_image') }}">
                                                    </div>
                                                @endif
                                                @php
                                                    $i++;
                                                    if($i == 6) {
                                                        break;
                                                    }
                                                @endphp
                                            @endforeach
                                        @else
                                            <div class="carousel-item active">
                                                <img src="{{ URL::asset('front-assets/images/no_group_img.jpg') }}" alt="No groups available" class="me-1">
                                            </div>
                                        @endif
                                    </div>

                                    <a class="carousel-control-prev" href="#bootstrapSlider" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">{{ __('dashboard.Previous') }}</span>
                                    </a>
                                    <a class="carousel-control-next" href="#bootstrapSlider" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">{{ __('dashboard.next') }}</span>
                                    </a>

                                    <ul class="carousel-indicators">
                                        @if(isset($groupImages) && sizeof($groupImages) > 0)
                                            @php $i=0; @endphp
                                            @foreach($groupImages as $grpImage)
                                                @if($i == 0)
                                                    <li data-bs-target="#bootstrapSlider" data-bs-slide-to="{{$i}}" class="active"> <img src="{{ url('/storage/'.$grpImage->image) }}" alt="Group Image"></li>
                                                @else
                                                    <li data-bs-target="#bootstrapSlider" data-bs-slide-to="{{$i}}"><img src="{{ url('/storage/'.$grpImage->image) }}" alt="Group Image"></li>
                                                @endif
                                                @php $i++; @endphp
                                            @endforeach
                                        @else
                                            <li data-bs-target="#bootstrapSlider" data-bs-slide-to="1" class="active"> <img src="{{ URL::asset('front-assets/images/no_group_img.jpg') }}" alt="Group Image"></li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-8 mb-2 ">
                                <div class="card border position-relative">
                                    @php
                                        $expDate = (strtotime($group->end_date) - strtotime(now()->format('Y-m-d'))) / (60 * 60 * 24);
                                    //dd($expDate);
                                    @endphp
                                    <div class="p-1 time_remain1">
                                        <img src="{{ URL::asset('front-assets/images/icons/exclamation-circle_1.png') }}" alt="" height="14px">
                                    @if($group->group_status == 3 || $group->group_status == 4)
                                        {{ $group->groupStatusName }}
                                    @else
                                        {{ ($expDate > 1) ? $expDate . ' ' . __('dashboard.days_remaining') : $expDate . ' ' . __('dashboard.day_remaining')  }}
                                    @endif
                                    <!-- <img src="images/icons/exclamation-circle_1.png"
                                        alt="" height="14px"> 5
                                    days remaining -->
                                    </div>
                                    <div class="card-body ">
                                        <div class="card-text">
                                            <div class="row ">
                                                <div class="col-md-12">
                                                    <h1 class="mb-0 submaintitle">{{ $group->groupName }}</h1>
                                                    <!-- Group Status => 1 : Open, 2 : Hold, 3 : Closed, 4 : Expired  -->
                                                    @if($group->group_status == 1)
                                                        <span class="badge bg-success mb-1 rounded-pill fw-normal mb-2">&#9679; {{__('admin.open')}}</span>
                                                    @elseif($group->group_status == 2)
                                                        <span class="badge bg-warning mb-1 rounded-pill fw-normal mb-2">&#9679; {{__('admin.hold')}}</span>
                                                    @elseif($group->group_status == 3)
                                                        <span class="badge bg-primary mb-1 rounded-pill fw-normal mb-2">&#9679; {{__('admin.close')}}</span>
                                                    @else
                                                        <span class="badge bg-danger mb-1 rounded-pill fw-normal mb-2" >&#9679; {{__('admin.expired')}}</span>
                                                    @endif
                                                    <div class="d-flex mb-1">
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{URL::asset('front-assets/images/icons/icon_rfq.png')}}" height="16"
                                                                 class="pe-1">
                                                            <div class="fw-bold text-primary ps-0" style="font-size: 14px;">
                                                                {{ isset($group->prodCat) ? $group->prodCat : '' }} - {{ isset($group->prodSubCat) ? $group->prodSubCat : '' }} - {{ isset($group->productName) ? $group->productName : ''}}
                                                            </div>
                                                        </div>
                                                        <div class="ms-auto d-flex align-items-center">
                                                            <div>
                                                                @if($group->location_code)
                                                                    <img src="{{ URL::asset('front-assets/images/icons/icon_address.png') }}" alt="" width="15px">
                                                                @endif
                                                                <span class="text-muted d-none" >{{ __('dashboard.location') }}</span> <strong style="font-size: 14px;">{{ $group->location_code }}</strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 mb-2 grouppro_info">
                                                    <div class="row m-0 p-0">
                                                        <div class="p-2 d-flex col-md-4 bg-light border align-items-center">
                                                            <div class="col-auto me-1 ms-2"><img src="{{ URL::asset('front-assets/images/icons/balance-scale-right.png')}}" alt="" height="14px"></div>
                                                            <div class="col-auto"><span class="fw-bold">{{ $group->achieved_quantity ?? 0 }} {{ $group->unit }}</span><br><span class="text-muted">{{ __('admin.achieved_quantity') }}</span></div>
                                                        </div>
                                                        <div class="p-2 d-flex col-md-4 align-items-center">
                                                            <div class="col-auto me-1 ms-2"><img src="{{ URL::asset('front-assets/images/icons/balance-scale-right.png')}}" alt="" height="14px"></div>
                                                            <div class="col-auto"><span class="fw-bold text-primary">{{ $group->reached_quantity ?? 0 }} {{ $group->unit }} *</span><br><span class="text-muted">{{ __('dashboard.prospect_quantity') }}</span></div>

                                                            <div class="text-sm ms-auto" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="{{ __('dashboard.prospect_quantity_line') }}">
                                                                <i class="fa fa-info-circle"></i>
                                                            </div>
                                                        </div>
                                                        <div class="p-2 d-flex col-md-4 bg-light border align-items-center">
                                                            <div class="col-auto me-1 ms-2"><img src="{{ URL::asset('front-assets/images/icons/balance-scale-right.png')}}" alt="" height="14px"></div>
                                                            <div class="col-auto"><span class="fw-bold">{{ $group->target_quantity ?? 0 }} {{ $group->unit }}</span><br><span class="text-muted">{{ __('dashboard.target_quantity') }}</span> </div>
                                                        </div>
                                                    </div>
                                                    <div class="row m-0 p-0">
                                                        <div class="p-2 d-flex col-md-4 align-items-center">
                                                            <div class="col-auto me-1 ms-2 fw-bold"><img src="{{ URL::asset('front-assets/images/icons/badge-percent.png')}} " alt="" height="14px"> {{ __('admin.discount') }}
                                                                <span class="text-primary">
                                                            @if(isset($groupOrderRange) && count($groupOrderRange) > 0)

                                                                        @php
                                                                            $i = 0; $len = count($groupOrderRange);
                                                                            $disSet = 0;
                                                                        @endphp
                                                                        @foreach($groupOrderRange as $range)

                                                                            @if(($range->min_quantity <= $group->reached_quantity) && ($group->reached_quantity <= $range->max_quantity) || ($group->reached_quantity > $range->max_quantity && $i == $len - 1) )
                                                                                @php $disSet = $range->discount @endphp
                                                                            @endif

                                                                            @php $i++; @endphp
                                                                        @endforeach
                                                                        {{$disSet}} %
                                                                    @endif
                                                            </span>
                                                            </div>
                                                        </div>
                                                        <div class="p-2 col-md-4 bg-light border">
                                                            <div class=" me-1  d-flex align-items-center h-100">
                                                                <div class="col-auto me-1  ms-2"><img src="{{ URL::asset('front-assets/images/icons/building_(2).png')}}" alt="" height="12px"> </div>

                                                                <div class="col-auto">
                                                                <span class="fw-bold">
                                                                    @if($companyCount > 1)
                                                                        {{$companyCount}} {{ __('dashboard.companies')}}
                                                                    @else
                                                                        {{$companyCount}} {{ __('dashboard.company')}}
                                                                    @endif
                                                                </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="p-2 col-md-4">
                                                            <div class="  me-1 d-flex align-items-center  h-100">
                                                                <div class="col-auto me-1  ms-2"><img  src="{{ URL::asset('front-assets/images/icons/Calendar-alt.png')}}" alt="" height="14px"></div>
                                                                <div class="col-auto"><span class="fw-bold"> {{ __('dashboard.exp_date') }} : </span> <span class="fw-bold text-primary">{{ date('d M Y', strtotime($group->end_date)) }}</span></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card border-0">
                                                    <table class="table table-responsive table-hover mt-2">
                                                        <thead>
                                                        <tr class="table-active">
                                                            <th scope="col">{{ __('admin.minimum_quantity') }}</th>
                                                            <th scope="col">{{ __('admin.maximum_quantity') }}</th>
                                                            <th scope="col">{{ __('admin.original_price') }} </th>
                                                            <th scope="col">{{ __('admin.discount') }}</th>
                                                            <th scope="col">{{ __('admin.discount_price') . ' (' . __('admin.per_unit_in_rp') . ') ' }}</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        @if(isset($groupOrderRange) && count($groupOrderRange) > 0)
                                                            @php $i = 0; $len = count($groupOrderRange); @endphp
                                                            @foreach($groupOrderRange as $range)
                                                                <tr class="align-content-center {{(($range->min_quantity <= $group->reached_quantity) && ($group->reached_quantity <= $range->max_quantity)) ? 'alert-info' : ''}}">
                                                                    <td>{{ $range->min_quantity }}</td>
                                                                    <td>{{ $range->max_quantity }}</td>
                                                                    <td class="discount_txt"><del>Rp . {{ $group->original_price }}</del></td>
                                                                    <td>{{ $range->discount}}%
                                                                        @if(($range->min_quantity <= $group->achieved_quantity) && ($group->achieved_quantity <= $range->max_quantity) && ($range->min_quantity <= $group->reached_quantity) && ($group->reached_quantity <= $range->max_quantity))
                                                                            <span class="badge bg-success text-white ms-2">{{ __('admin.current_discount') }} <span class="text-sm ms-1" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="{{ __('dashboard.current_discount_line') }}"><i class="fa fa-info-circle"></i></span></span>

                                                                        @elseif(($range->min_quantity <= $group->achieved_quantity) && ($group->achieved_quantity <= $range->max_quantity) && $group->reached_quantity > $range->max_quantity && $i == $len - 1)
                                                                            <span class="badge bg-success ms-2 text-white">{{__('admin.current_discount')}} <span class="text-sm ms-1" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="{{ __('dashboard.current_discount_line') }}">
                                                                            <i class="fa fa-info-circle"></i></span></span>

                                                                        @elseif(($group->achieved_quantity <= $range->min_quantity) && $group->reached_quantity > $range->max_quantity && $i == $len - 1)
                                                                            <span class="badge bg-info ms-2 text-dark">{{__('admin.prospect_discount')}} <span class="text-sm ms-1" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="{{ __('dashboard.prospect_discount_line') }}">
                                                                            <i class="fa fa-info-circle"></i></span></span>

                                                                        @else
                                                                            @if(($range->min_quantity <= $group->achieved_quantity) && ($group->achieved_quantity <= $range->max_quantity))
                                                                                <span class="badge bg-success text-white ms-2">{{ __('admin.current_discount') }} <span class="text-sm ms-1" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="{{ __('dashboard.current_discount_line') }}"><i class="fa fa-info-circle"></i></span></span>
                                                                            @endif

                                                                            @if(($range->min_quantity <= $group->reached_quantity) && ($group->reached_quantity <= $range->max_quantity))
                                                                                <span class="badge bg-info ms-2 text-dark">{{__('admin.prospect_discount')}} <span class="text-sm ms-1" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="{{ __('dashboard.prospect_discount_line') }}">
                                                                                <i class="fa fa-info-circle"></i></span></span>
                                                                            @endif

                                                                        @endif
                                                                    </td>
                                                                    <td>Rp . {{ $range->discount_price }}</td>
                                                                </tr>
                                                                @php $i++; @endphp
                                                            @endforeach
                                                        @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                                @php
                                                    $isGroupQuantityReached = false;
                                                    if($group['target_quantity'] <= $group['achieved_quantity'] || $group['group_status'] == 3 || $group['group_status'] == 4){
                                                        $isGroupQuantityReached = true;
                                                    }
                                                @endphp

                                                @php $shareGroupImage = null;
                                                    if(isset($groupImages) && sizeof($groupImages) > 0) {
                                                        $shareGroupImage = url('/storage/'.$groupImages[0]->image);
                                                    } else {
                                                        $shareGroupImage = URL::asset('front-assets/images/no_group_img.jpg');
                                                    }
                                                @endphp
                                                <input type="hidden" id="groupTestImg" value="{{ $shareGroupImage }}" alt="">
                                                <input type="hidden" id="groupTestName" value="{{ $group->groupName }}" alt="">
                                                <input type="hidden" id="groupTestDesc" value="{{ strip_tags($group->group_description) }}" alt="">
                                                <input type="hidden" id="groupSupplierName" value="{{ $group->supplierName }}" alt="">

                                                <div class="text-end">
                                                    <!-- joinGroupAndShareBtnDisable(); -->
                                                    @can('create buyer join group')
                                                    <button type="button" class="btn btn-primary btn-sm me-2 group_join_btn" id="group_join_btn" data-group-id="{{$group->id}}" >
                                                        <span> <img src="{{ URL::asset('front-assets/images/icons/layer-group.png')}}" alt=""> {{ __('dashboard.join_group') }}</span>
                                                    </button>
                                                    @endcan
                                                    <button type="button" class="btn btn-warning btn-sm shr_btn" data-group-name="{{ $group->groupName }}" data-group-img="{{ $shareGroupImage }}" data-bs-toggle="modal" data-bs-target="#exampleModal" id="gt_btn" >
                                                        <span><img src="{{ URL::asset('front-assets/images/icons/icon_share_1.png')}}" alt=""> {{ __('dashboard.share') }}</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mt-3">
                                    <div class="card-body">
                                        @if(isset(auth()->user()->id))
                                            <div>
                                                <h6 class="color-primary fw-bold">{{ __('dashboard.group_supplier_name') }}:</h6>
                                                <p>{{ $group->supplierName }}</p>
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="color-primary fw-bold">{{ __('admin.group') }} {{ __('admin.description') }}:</h6>
                                            <p>{{ strip_tags($group->group_description) }}</p>
                                        </div>
                                        <div>
                                            <h6 class="color-primary fw-bold">{{ __('admin.product_description') }}:</h6>
                                            <p>{{ strip_tags($group->product_description) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row px-2 mt-3">
                            <div class="card ">
                                <div class="card-body">
                                    <h6 class="grouptitle mb-3 d-flex align-items-center pt-2">
                                        <span class="text-nowrap pe-2 fw-bold">{{ __('admin.companies_list') }}</span>
                                    </h6>
                                    <div class="table-responsive">
                                        <table class="table border">
                                            <thead class="bg-light" >
                                            <tr>
                                                <th scope="col">{{__('admin.logo')}}</th>
                                                <th scope="col">{{__('admin.company_name')}}</th>
                                                <th scope="col">{{__('admin.quantity')}}</th>
                                                <th scope="col">{{__('admin.Address')}}</th>
                                                <th scope="col" class="text-center">
                                                    @if(Auth::user()){{__('admin.action')}}@endif
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(isset($userData) && count($userData))
                                                @foreach($userData as $userRfqs)
                                                    @foreach($userRfqs as $data)
                                                        <tr>
                                                            <td  style="width: 10%">
                                                                <div class="">
                                                                    @if(isset($data->companyLogo))
                                                                        <img src="{{ url('/storage/'.$data->companyLogo) }}" class="rounded" id="{{ isset(Auth::user()->id) && (Auth::user()->id == $data->user_id) ? '' : 'img'}}" alt="{{ __('admin.company_name') }}" style="height: 36px;">
                                                                    @else
                                                                        <img src="{{ URL::asset('front-assets/images/blitznetLogo.jpg') }}" class="rounded" id="{{ isset(Auth::user()->id) && (Auth::user()->id == $data->user_id) ? '' : 'img'}}" alt="{{ __('admin.no_image') }}" style="height: 36px;">
                                                                    @endif
                                                                </div>
                                                            </td>
                                                            <td  style="width: 35%" class="align-middle">
                                                                <div class="text-uppercase" id="">
                                                                    <strong>{{ isset(Auth::user()->id) && (Auth::user()->id == $data->user_id) ? $data->companyName : Str::mask($data->companyName, 'X', 1, -1) }}</strong>
                                                                </div>
                                                            </td>
                                                            <td  style="width: 10%" class="align-middle">
                                                                <div class="text-muted">
                                                                    <img src="{{ URL::asset('front-assets/images/icons/balance-scale-right_1.png') }}" alt="{{ __('admin.quantity') }}" height="14px">{{ $data->quantity . ' ' . $data->unitName }}
                                                                </div>
                                                            </td>
                                                            @php $buyerAddress = $data->address_line_1 . ' ' . $data->address_line_2 . ' ' . $data->state . ' ' . $data->city . ' ' . $data->pincode; @endphp
                                                            <td  style="width: 35%" class="align-middle">
                                                                <div class="text-muted">
                                                                    @if(!empty($data->comp_address))
                                                                        @if($data->comp_address)
                                                                            <img src="{{ URL::asset('front-assets/images/icons/icon_address.png') }}" alt="{{ __('rfqs.address') }}" width="15px">
                                                                        @endif
                                                                        {{ isset(Auth::user()->id) && (Auth::user()->id == $data->user_id) ? $data->comp_address : Str::mask($data->comp_address, 'X', 1, -1) }}
                                                                    @else
                                                                        <span>-</span>
                                                                    @endif
                                                                </div>
                                                            </td>
                                                            @if(isset(Auth::user()->id) && (Auth::user()->id == $data->user_id))
                                                                <td style="width: 10%" class="align-middle">
                                                                @canany(['publish buyer rfqs','edit buyer rfqs','delete buyer join group'])
                                                                    <div class="d-flex d-flex justify-content-center">
                                                                        @can('publish buyer rfqs')
                                                                            <a href="javascript:void(0)" class="mx-1 cursor-pointer viewRfqDetail" data-id="{{ $data->rfq_id }}" data-toggle="tooltip" data-placement="top" title="view" data-bs-original-title="view" aria-label="Melihat">
                                                                                <i class="fa fa-eye"></i>
                                                                            </a>
                                                                        @endcan

                                                                        @can('edit buyer rfqs')
                                                                            <!-- If order is placed for RFQ, edit icon should not be visible -->
                                                                            <a href="javascript:void(0)" data-rfq_id="{{ $data->rfq_id }}" class="mx-1 show-icon openeditmodal {{ isset($data->orderId) ? 'd-none' : ''  }}" data-toggle="tooltip" ata-placement="top" title="Edit" data-bs-original-title="Edit">
                                                                                <i class="fa fa-edit" aria-hidden="true"></i>
                                                                            </a>
                                                                        @endcan

                                                                        @can('delete buyer join group')
                                                                            <!-- If order is placed for RFQ, user cannot leave the group -->
                                                                            <a href="javascript:void(0)" class="mx-1 show-icon text-danger leaveGroupModal {{ isset($data->orderId) ? 'd-none' : ''  }}" data-rfq_id="{{ $data->rfq_id }}" data-user_id="{{ $data->user_id }}" data-group_id="{{ $data->group_id }}" data-toggle="tooltip" ata-placement="top" title="Leave" data-bs-original-title="leave" >
                                                                                <i class="fa fa-sign-out" aria-hidden="true"></i>
                                                                            </a>
                                                                        @endcan
                                                                    </div>
                                                                @endcanany
                                                                </td>
                                                            @else
                                                                <td class="text-center"> - </td>
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td style="text-align:center" colspan="5">
                                                        {{__('dashboard.no_data_found')}}
                                                    </td>
                                                </tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade show " id="company" role="tabpanel" aria-labelledby="company-tab">

                    <div class="card mb-3">
                        <div class="card-body activities_tab p-3 pb-1">
                            @if(sizeof($groupActivies) > 0)
                                @foreach($groupActivies as $created_at => $row)
                                    <div>
                                        <p class="text-dark">
                                            <strong>
                                                {{\Carbon\Carbon::createFromFormat('Y-m-d', $created_at)->format('d M Y')}}

                                            </strong>
                                        </p>
                                        <ul class="bullet-line-list mb-0">
                                            @foreach($row as $groupactivity)
                                                @php
                                                    $userName = "User";
                                                    if(auth()->check() && auth()->user()->id == $groupactivity->user->id){
                                                        $userName = $groupactivity->user->firstname .' '.$groupactivity->user->lastname;
                                                    }
                                                @endphp
                                                <li class="pb-3">
                                                    <div class="d-flex">
                                    <span class="h6" style="font-size: 0.75rem;">
                                        @if($groupactivity->key_name == 'create')
                                            @if($groupactivity->user->role_id == 1)
                                                Blitznet Team created {{ $groupactivity->new_value }}.
                                            @else
                                                {{$userName}} {{ $groupactivity->new_value }}.
                                            @endif
                                        @endif

                                        @if($groupactivity->key_name == 'group_deleted')
                                            @if($groupactivity->user->role_id == 1)
                                                Blitznet Team Remove {{ 'BGRP-'.$groupactivity->group_id }} Group.
                                            @else
                                                {{$userName}} Remove {{ 'BQTN-'.$groupactivity->group_id }} Group.
                                            @endif
                                        @endif

                                        @if($groupactivity->key_name == 'supplier')
                                            Blitznet Team updated Supplier  from {{ $suppliers[$groupactivity->old_value] .' to '. $suppliers[$groupactivity->new_value] }}.
                                        @endif

                                        @if($groupactivity->key_name == 'name')
                                            @if($groupactivity->user->role_id == 1)
                                                Blitznet Team updated Group Name from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.
                                            @else
                                                {{$userName}} updated Group Name from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.
                                            @endif
                                        @endif

                                        @if($groupactivity->key_name == 'location_code')
                                            @if($groupactivity->user->role_id == 1)
                                                Blitznet Team updated Location from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.
                                            @else
                                                {{$userName}} updated Location from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.
                                            @endif
                                        @endif

                                        @if($groupactivity->key_name == 'category')
                                            @if($groupactivity->user->role_id == 1)
                                                Blitznet Team updated Category from {{ $category[$groupactivity->old_value] .' to '. $category[$groupactivity->new_value] }}.
                                            @else
                                                {{$userName}} updated Category from {{ $category[$groupactivity->old_value] .' to '. $category[$groupactivity->new_value] }}.
                                            @endif
                                        @endif

                                        @if($groupactivity->key_name == 'sub_category')
                                            @if($groupactivity->user->role_id == 1)
                                                Blitznet Team updated Sub Category from {{ $subcategory[$groupactivity->old_value] .' to '. $subcategory[$groupactivity->new_value] }}.
                                            @else
                                                {{$userName}} updated Sub Category from {{ $subcategory[$groupactivity->old_value] .' to '. $subcategory[$groupactivity->new_value] }}.
                                            @endif
                                        @endif

                                        @if($groupactivity->key_name == 'product_name')
                                            @if($groupactivity->user->role_id == 1)
                                                Blitznet Team updated Product from {{ $product[$groupactivity->old_value] .' to '. $product[$groupactivity->new_value] }}.
                                            @else
                                                {{$userName}} updated Product from {{ $product[$groupactivity->old_value] .' to '. $product[$groupactivity->new_value] }}.
                                            @endif
                                        @endif


                                        @if($groupactivity->key_name == 'unit_name')
                                            @if($groupactivity->user->role_id == 1)
                                                Blitznet Team updated Unit from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.
                                            @else
                                                {{$userName}} updated Unit from {{ $unit[$groupactivity->old_value] .' to '. $unit[$groupactivity->new_value] }}.
                                            @endif
                                        @endif

                                        @if($groupactivity->key_name == 'price')
                                            @if($groupactivity->user->role_id == 1)
                                                Blitznet Team updated Price from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.
                                            @else
                                                {{$userName}} updated Unit from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.
                                            @endif
                                        @endif

                                        @if($groupactivity->key_name == 'exp_date')
                                            @if($groupactivity->user->role_id == 1)
                                                Blitznet Team updated Expiry Date from {{ date('d-m-Y', strtotime($groupactivity->old_value)) .' to '. date('d-m-Y', strtotime($groupactivity->new_value)) }}.
                                            @else
                                                {{$userName}} updated Expiry Date from {{ date('d-m-Y', strtotime($groupactivity->old_value)) .' to '. date('d-m-Y', strtotime($groupactivity->new_value)) }}.
                                            @endif
                                        @endif

                                        @if($groupactivity->key_name == 'min_order_quantity')
                                            @if($groupactivity->user->role_id == 1)
                                                Blitznet Team updated Minimum Order QTY from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.
                                            @else
                                                {{$userName}} updated Minimum Order QTY from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.
                                            @endif
                                        @endif

                                        @if($groupactivity->key_name == 'max_order_quantity')
                                            @if($groupactivity->user->role_id == 1)
                                                Blitznet Team updated Maximun Order QTY from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.
                                            @else
                                                {{$userName}} updated Maximun Order QTY from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.
                                            @endif
                                        @endif

                                        @if($groupactivity->key_name == 'description')
                                            @if($groupactivity->user->role_id == 1)
                                                Blitznet Team updated Description from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.
                                            @else
                                                {{$userName}} updated Description from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.
                                            @endif
                                        @endif

                                        @if($groupactivity->key_name == 'tag_deleted')
                                            @if($groupactivity->user->role_id == 1)
                                                Blitznet Team Remove Tag from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.
                                            @else
                                                {{$userName}} Remove Tag from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.
                                            @endif
                                        @endif

                                        @if($groupactivity->key_name == 'tag_added')
                                            @if($groupactivity->user->role_id == 1)
                                                Blitznet Team Added Tag from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.
                                            @else
                                                {{$userName}} Added Tag from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.
                                            @endif
                                        @endif

                                        @if($groupactivity->key_name == 'image_deleted')
                                            @php
                                                $image_name_old = explode('group_image_', $groupactivity->old_value);
                                            @endphp
                                            @if($groupactivity->user->role_id == 1)
                                                Blitznet Team Remove Image {{ $image_name_old[1] }}.
                                            @else
                                                {{$userName}} Remove Image {{ $image_name_old[1] }}.
                                            @endif
                                        @endif

                                        @if($groupactivity->key_name == 'image_added')
                                            @php
                                                $image_name_new = explode('group_image_', $groupactivity->new_value);
                                            @endphp
                                            @if($groupactivity->user->role_id == 1)
                                                Blitznet Team Added Image {{ $image_name_new[1] }}.
                                            @else
                                                {{$userName}} Added Image {{ $image_name_new[1] }}.
                                            @endif
                                        @endif

                                        @if($groupactivity->key_name == 'buyer_remove')
                                            @if($groupactivity->user->role_id == 1)
                                                Remove {{ $groupactivity->old_value}} by Blitznet Team.
                                            @else
                                                Remove {{ $groupactivity->old_value}} by {{$userName}}.
                                            @endif
                                        @endif

                                        @if($groupactivity->key_name == 'added_min_qty')
                                            @if($groupactivity->user->role_id == 1)
                                                Blitznet Team Added Minimum Quantity {{ $groupactivity->new_value}}.
                                            @else
                                                {{$userName}} Added Minimum Quantity {{ $groupactivity->new_value}}.
                                            @endif
                                        @endif

                                        @if($groupactivity->key_name == 'added_max_qty')
                                            @if($groupactivity->user->role_id == 1)
                                                Blitznet Team Added Maximum Quantity {{ $groupactivity->new_value}}.
                                            @else
                                                {{$userName}} Added Maximum Quantity {{ $groupactivity->new_value}}.
                                            @endif
                                        @endif

                                        @if($groupactivity->key_name == 'added_discount')
                                            @if($groupactivity->user->role_id == 1)
                                                Blitznet Team Added Discount Quantity {{ $groupactivity->new_value}}.
                                            @else
                                                {{$userName}} Added Discount Quantity {{ $groupactivity->new_value}}.
                                            @endif
                                        @endif

                                        @if($groupactivity->key_name == 'added_discount_price')
                                            @if($groupactivity->user->role_id == 1)
                                                Blitznet Team Added Discount Price {{ $groupactivity->new_value}}.
                                            @else
                                                {{$userName}} Added Discount Price {{ $groupactivity->new_value}}.
                                            @endif
                                        @endif

                                        @if($groupactivity->key_name == 'updated_min_qty')
                                            @if($groupactivity->user->role_id == 1)
                                                Blitznet Team updated Minimum Quantity from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.
                                            @else
                                                {{$userName}} updated Minimum Quantity from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.
                                            @endif
                                        @endif

                                        @if($groupactivity->key_name == 'updated_max_qty')
                                            @if($groupactivity->user->role_id == 1)
                                                Blitznet Team updated Maximum Quantity from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.
                                            @else
                                                {{$userName}} updated Maximum Quantity from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.
                                            @endif
                                        @endif

                                        @if($groupactivity->key_name == 'updated_discount')
                                            @if($groupactivity->user->role_id == 1)
                                                Blitznet Team updated Discount from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.
                                            @else
                                                {{$userName}} updated Discount from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.
                                            @endif
                                        @endif

                                        @if($groupactivity->key_name == 'updated_discount_price')
                                            @if($groupactivity->user->role_id == 1)
                                                Blitznet Team updated Discount Price from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.
                                            @else
                                                {{$userName}} updated Discount Price from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.
                                            @endif
                                        @endif

                                        @if($groupactivity->key_name == 'group_margin')
                                            @if($groupactivity->user->role_id == 1)
                                                Blitznet Team updated Blitznet Margin from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.
                                            @else
                                                {{$userName}} updated Blitznet Margin from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.
                                            @endif
                                        @endif

                                        @if($groupactivity->key_name == 'group_joined')
                                            @if($groupactivity->user->role_id == 1)
                                                Blitznet joined group.
                                            @else
                                                {{$userName}} joined group.
                                            @endif
                                        @endif

                                        @if($groupactivity->key_name == 'remove_discount_range')

                                            @if($groupactivity->user->role_id == 1)
                                                Discount range removed by Blitznet Team.
                                            @else
                                                Discount range removed by {{$userName}}.
                                            @endif
                                        @endif

                                        @if($groupactivity->key_name == 'left_group')

                                            @if($groupactivity->user->role_id == 1)
                                                Blitznet removed user.
                                            @elseif($groupactivity->user->role_id == 3)
                                                Supplier removed user.
                                            @else
                                                {{$userName}} left group.
                                            @endif
                                        @endif

                                        @if($groupactivity->key_name == 'order_placed')
                                            @if($groupactivity->user->role_id == 1)
                                                Blitznet placed an order.
                                            @else
                                                {{$userName}} placed an order.
                                            @endif
                                        @endif
                                    </span>
                                                        <span class="ms-auto">
                                        <small class="d-flex align-items-center" style="font-size: 0.875em;">
                                            <i class="fa fa-clock-o me-1"></i>
                                            {{\Carbon\Carbon::createFromFormat('Y-m-d', $created_at)->format('Y-m-d H:i:s')}}
                                        </small>
                                    </span>
                                                    </div>
                                                </li>

                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach

                            @else
                                <p class="text-center">{{__('dashboard.no_activity_found')}}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Share on social media Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="modal-title" id="exampleModalLabel">{{__('dashboard.share_via')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6 class="text-center mb-3">{{__('dashboard.share_group_social_media')}} </h6>
                    <div class="social-btn-sp">tails',['id' => Crypt::encrypt($group->id)])) !!} <!--  Link for group page local server -->
                    </div>
                    <hr>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="groupLink" value="{{ route('group-details',['id' => Crypt::encrypt($group->id)]) }}" readonly aria-label="Group Link" aria-describedby="basic-addon2">

                        <button type="button" class="btn btn-primary" id="copyBtn" onclick=""><span class="">{{__('dashboard.copy')}} <i class="fa fa-copy ms-2" style="font-size: 16px;"></i></span></button>
                    </div>
                    <div class="copied"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->

    <!-- View RFQ Modal -->
    <div class="modal fade" id="rfqDetailsModal" data-bs-backdrop="true" tabindex="-1" aria-labelledby="rfqDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content radius_1 shadow-lg" id="rfqDetailsModalBlock">

            </div>
        </div>
    </div>
    <!-- View RFQ Modal End -->

    <!-- Edit RFQ Modal -->
    <div class="modal right fade editmodal" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">

            <div class="modal-content showeditmodal">

            </div>
        </div>
    </div>
    <!-- Edit RFQ Modal End -->

    <!-- Leave Group With Comment Modal -->
    <div class="modal fade" id="leaveGroupModal" data-bs-backdrop="true" tabindex="-1" aria-labelledby="leaveGroupModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content radius_1 shadow-lg" id="showleaveGroupModal">
                <div class="modal-header bg-white border-0 pb-0">
                    <h5 class="modal-title text-center w-100" id="viewModalLabel">
                        <img src="{{ URL::asset('front-assets/images/icons/icon_group_leave.svg ') }}" alt="" height="" style="max-height: inherit; "> <br>
                        {{ __('dashboard.are_you_sure') }} , {{ __('dashboard.leave_group_line') }} ?
                    </h5>
                    <button type="button" class="btn-close d-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form name="leaveGroupForm" id="leaveGroupForm" class="floatlables" data-parsley-validate autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body pt-3">
                        <h6 class="mb-5 text-center text-danger"> {{ __('dashboard.leave_confirmation') }} </h6>

                        <div class="">
                            <label> {{ __('dashboard.leave_group_reason') }} * </label>
                            <textarea class="form-control" name="leave_group_reason" id="leave_group_reason" required></textarea>
                            <input type="hidden" name="rfqId" id="rfqId" value="" />
                            <input type="hidden" name="userId" id="userId" value="" />
                            <input type="hidden" name="groupId" id="groupId" value="" />
                        </div>
                    </div>

                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-close"></i> {{ __('admin.cancel') }}</button>
                        <button type="button" class="btn btn-primary" id="submitLeaveGroupForm"><i class="fa fa-sign-out"></i> {{ __('dashboard.leave_btn') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Leave Group With Comment Modal -->

    <script src="{{ URL::asset('front-assets/intlTelInput/js/intlTelInput.js') }}"></script>
    <script>

        $(document).ready(function() {

            let groupTestImg = $("#groupTestImg").val();
            let groupTestName = $("#groupTestName").val();
            let groupTestDesc = $("#groupTestDesc").val();
            let groupSupplierName = $("#groupSupplierName").val();
            $("#metaTitle, #metaTitleNew").attr("content", groupTestName);
            $("#metaImage, #metaImageNew").attr("content", groupTestImg);
            $("#metaDescription, #metaDescriptionNew").attr("content", groupTestDesc);
            $("#metaAuthor, #metaAuthorNew").attr("content", groupSupplierName);

            //Show tooltip
            $('[data-bs-toggle="tooltip"]').tooltip();

            joinGroupAndShareBtnDisable();

            /**
             * Ronak Bhabhor 10/03/2022
             * joinGroupAndShareBtnDisable: Disable join group and share button if quantity achieved or group expired
             */
            function joinGroupAndShareBtnDisable(){
                let groupDisable = "{{ ($expDate  < 0 || $isGroupQuantityReached == true)}}";
                if(groupDisable){
                    $('#group_join_btn').prop('disabled',true);
                    $('.shr_btn').prop('disabled',true);
                    $('.shr_btn').attr("data-bs-target","");
                    return 1;
                }else{
                    $('#group_join_btn').prop('disabled',false);
                    $('.shr_btn').prop('disabled',false);
                    $('.shr_btn').attr("data-bs-target","#exampleModal");
                    return 0;
                }
            }

            //Pass category , sub category and product data to post rfq form
            // localStorage.clear();
            $(document).on('click', '.group_join_btn', function(e) {
                let groupDisable = joinGroupAndShareBtnDisable();
                if(groupDisable){
                    return false;
                }

                var grpId = $(this).attr("data-group-id");
                e.stopImmediatePropagation();
                e.preventDefault();
                localStorage.clear();
                var groupId = $("#groupId_"+grpId).val();
                var grpCatId = $("#categoryId_"+grpId).val();
                var grpSubCatId = $("#subCategoryId_"+grpId).val();
                var grpProdId = $("#grpProdId_"+grpId).val();
                var productName = $("#productName_"+grpId).val();
                var grpUnitId = $("#unitId_"+grpId).val();
                var unitName = $("#unitName_"+grpId).val();
                var achievedQuantity = $("#achievedQuantity_"+grpId).val();
                var targetQuantity = $("#targetQuantity_"+grpId).val();
                var groupProductDesc = $("#groupProdDesc_"+grpId).val();

                localStorage.setItem('groupId', groupId);
                localStorage.setItem('category', grpCatId);
                localStorage.setItem('sub_category', grpSubCatId);
                localStorage.setItem('GroupProductId', grpProdId);
                localStorage.setItem('product', productName);
                localStorage.setItem('unit_post', grpUnitId);
                localStorage.setItem('unitName', unitName);
                localStorage.setItem('achievedQuantity', achievedQuantity);
                localStorage.setItem('targetQuantity', targetQuantity);
                localStorage.setItem('groupProductDesc', groupProductDesc);

                var isUserLogin = $("#is_user_login").val();
                if(isUserLogin == 1){
                    window.location.replace("{{ route('get-a-quote') }}");
                } else {
                    window.location.replace("{{ route('signin')}}");
                }
            });

            $(document).on('click', '.shr_btn', function(e) {
                let groupDisable = joinGroupAndShareBtnDisable();
                if(groupDisable){
                    return false;
                }
                let groupName = $(this).attr('data-group-name');
                let groupOgImg = $(this).attr('data-group-img');
                changeGroupOGImge(groupName, groupOgImg);
            });
        });

        //View RFQ details in popup modal
        $(document).on('click', '.viewRfqDetail', function() {
            // $('#rfqDetailsModal').modal('hide');
            var rfqId = $(this).attr('data-id');
            $.ajax({
                url: "{{ route('dashboard-get-rfq-details-ajax', '') }}" + "/" + rfqId,
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

        //Edit RFQ details in popup modal
        $(document).on('click','.openeditmodal',function() {
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

        //Edit RFQ details in popup address change event
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

        //Leave Group with comment
        $(document).on('click','.leaveGroupModal',function(e) {
            e.preventDefault();
            $("#rfqId").val($(this).attr('data-rfq_id'));       //set rfq id
            $("#userId").val($(this).attr('data-user_id'));     //set user id
            $("#groupId").val($(this).attr('data-group_id'));   //set group id
            $("#leaveGroupModal").modal('show');
        });

        //Submit leave group form on button click
        $('#submitLeaveGroupForm').on('click', function(e) {
            e.preventDefault();
            if ($('#leaveGroupForm').parsley().validate()) {
                var formData = new FormData($('#leaveGroupForm')[0]);
                $.ajax({
                    url: "{{ route('leave-group-ajax', '') }}",
                    data: formData,
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    success: function(successData) {
                        if (successData.success == true) {
                            
                            new PNotify({
                                text: successData.msg,
                                type: 'error',
                                styling: 'bootstrap3',
                                animateSpeed: 'fast',
                                delay: 2000
                            });

                            setTimeout(function () {
                                window.location.replace("{{ route('group-trading') }}");
                            },2000);
                        }
                    }
                });
            }
        });

        //Copy group link on click of "copy button"
        $(document).on('click', '#copyBtn', function() {
            var copyText = document.getElementById("groupLink");
            copyText.select();
            navigator.clipboard.writeText(copyText.value);
            $(".copied").text("Copied to clipboard").show().fadeOut(2000);
        });

        //Buyer Notification (Arun's code)
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

        //Change og:image on click of share button
        function changeGroupOGImge(groupName, groupOgImg) {
            $("#metaTitle, #metaTitleNew").attr("content", groupName);
            $("#metaImage, #metaImageNew").attr("content", groupOgImg);
            $("#thumbnailUrl").attr("href", groupOgImg);
        }

    </script>

@stop
@push('bottom_scripts')
    @include('dashboard.payment.payment_tab_js')
@endpush
