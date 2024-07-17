@extends('home/homeLayout2')

@section('content')

    <!-- banner start -->
    <div id="carouselCaptions supplier_prof" class="  bannersection aos-init aos-animate mt-5"
         data-bs-ride="carousel" data-aos="fade-down">

        <div class="ratio ratio-21x9">

                <img src="{{ asset('assets/images/supplier_profile.png') }} " class="d-block w-100 rounded" alt="Blitznet Home">
                <div class=" h-100">
                    <div class="container h-100">
                    <div class="prof_banner_text d-flex align-items-center h-100 ">
                        <div class="my-auto text-start">
                            <h3 class="text-white">{{(!empty($supplier->name))?$supplier->name:''}}</h3>
                        </div>
                        @if(!empty($supplier->logo))
                        <div class="my-auto ms-auto text-start">
                            <div class="logoimg shadow">
                                <img src="{{ url('storage/'.$supplier->logo) }}"  alt="Supplier Logo" srcset="">
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
        </div>
    </div>
    <!-- banner end -->

    <div class="container-fluid Supp_prof">
        <div class="row">
            <div class="col-md-12">
                <div class="container-xl ">
                    <div class="row g-lg-4 g-2 mt-3">
                        <div class=" col-lg-4 mb-3 d-none d-lg-block">
                            <div class="first_card card border-0 px-0 mb-lg-4 mb-3 shadow">
                                <div class="card-header"></div>
                                <div class="card-body py-3" style="background-color: #F5F5F5;">
                                    <div class="text-center rounded" style="margin-top: -56px;">
                                        @if(!empty($supplier->user->profile_pic))
                                        <div class="user_info_photo radius_1 text-center verticle-middle mx-auto rounded-circle">
                                            <img src="{{ url('storage/'.$supplier->user->profile_pic) }}" style="max-width: 80px; " class="mb-2" alt="" srcset="">
                                        </div>
                                        @elseif(!empty($supplier->full_name))
                                        <div class="user_info_photo radius_1 text-center verticle-middle mx-auto rounded-circle">
                                        {{ strtoupper(substr($supplier->contact_person_name, 0, 1)) }}{{ strtoupper(substr($supplier->contact_person_last_name, 0, 1) )}}
                                        </div>
                                        @else
                                            <div class="user_info_photo radius_1 text-center verticle-middle mx-auto rounded-circle">
                                                <img src="{{ url('front-assets/images/default_supplier_photo.png') }}" style="max-width: 80px; " class="mb-2" alt="" srcset="">
                                            </div>
                                        @endif

                                            @if(!empty($supplier->full_name))
                                            <p class="my-1">{{$supplier->salutation_name ?? ''}} {{ $supplier->full_name  ?? '' }}</p>
                                            @endif

                                            <p class="f-13 mb-1" style="font-size: 13px;"></p>
                                            @if(!empty($supplier->user))
                                            <span class="f-13 ms-auto alert alert-success p-0 px-2 rounded-1" role="alert">{{ $supplier->user->active_status ?? '' }}</span>
                                            @endif

                                            <p class="f-12 mb-1 mt-2"> <i class="fa fa-phone me-1"></i> {{__('admin.phone')}} </p>
                                            <p class="f-14 mb-1">{{ ($supplier->contact_person_phone)?$supplier->cp_phone_code.' '.$supplier->contact_person_phone:'-' }}</p>

                                            <p class="f-12 mb-1">  <i class="fa fa-envelope me-1"></i> {{__('admin.email')}} </p>

                                            <p class="f-14 mb-1">{{ $supplier->contact_person_email ?? '-' }}</p>

                                        <div class="mt-3">
                                            @if(!empty($supplier->linkedin))
                                                @if(\Illuminate\Support\Str::contains($supplier->linkedin,['http','https']))
                                                    <a href="{{ $supplier->linkedin }}" target="_blank"><img src="{{ url('front-assets/images/icons/icon_linkedin.png') }}" height="24px" alt="linkedin" class="mw-100 me-2"></a>
                                                @else
                                                    <a href="{{ 'https://'.$supplier->linkedin }}" target="_blank"><img src="{{ url('front-assets/images/icons/icon_linkedin.png') }}" height="24px" alt="linkedin" class="mw-100 me-2"></a>
                                                @endif
                                            @endif
                                            @if(!empty($supplier->facebook))
                                                @if(\Illuminate\Support\Str::contains($supplier->facebook,['http','https']))
                                                    <a href="{{ $supplier->facebook }}" target="_blank"><img src="{{ url('front-assets/images/icons/icon_fb.png') }}" alt="facebook" height="24px" class="mw-100 me-2"></a>
                                                @else
                                                    <a href="{{ 'https://'.$supplier->facebook }}" target="_blank"><img src="{{ url('front-assets/images/icons/icon_fb.png') }}" alt="facebook" height="24px" class="mw-100 me-2"></a>
                                                @endif
                                            @endif
                                            @if(!empty($supplier->twitter))
                                                @if(\Illuminate\Support\Str::contains($supplier->twitter,['http','https']))
                                                    <a href="{{$supplier->twitter}}" target="_blank"><img src="{{ url('front-assets/images/icons/icon_twitter.png') }}" height="24px" alt="twitter" class="mw-100 me-2"></a>
                                                @else
                                                    <a href="{{'https://'.$supplier->twitter}}" target="_blank"><img src="{{ url('front-assets/images/icons/icon_twitter.png') }}" height="24px" alt="twitter" class="mw-100 me-2"></a>
                                                @endif
                                            @endif
                                            @if(!empty($supplier->youtube))
                                                @if(\Illuminate\Support\Str::contains($supplier->youtube,['http','https']))
                                                    <a href="{{$supplier->youtube}}" target="_blank"><img src="{{ asset('assets/images/youtube.svg')}}" height="24px" alt="youtube" class="mw-100 me-2"></a>
                                                @else
                                                    <a href="{{'https://'.$supplier->youtube}}" target="_blank"><img src="{{ asset('assets/images/youtube.svg')}}" height="24px" alt="youtube" class="mw-100 me-2"></a>
                                                @endif
                                            @endif
                                            @if(!empty($supplier->instagram))
                                                @if(\Illuminate\Support\Str::contains($supplier->instagram,['http','https']))
                                                    <a href="{{$supplier->instagram}}" target="_blank"><img src="{{ asset('assets/images/instagram.png')}}" alt="instagram" height="24px" class="mw-100"></a>
                                                @else
                                                    <a href="{{'https://'.$supplier->instagram}}" target="_blank"><img src="{{ asset('assets/images/instagram.png')}}" alt="instagram" height="24px" class="mw-100"></a>
                                                @endif
                                            @endif
                                        </div>
                                    </div>


                                </div>
                            </div>
                                <div class="card border-0 px-0 shadow businesscard">
                                    <div class="card-header">
                                        <div>{{__('admin.business_info')}}</div>
                                    </div>
                                    <div class="card-body py-3" style="background-color: #F5F5F5;">
                                        <div class="row">

                                                <div class="col-md-12 mb-3">
                                                    <label class="f-12" for="">{{__('admin.company_name')}}</label>
                                                    <div class="">{{ ($supplier->name) ?? '-' }}</div>
                                                </div>

                                                <div class="col-md-12 mb-3">
                                                    <label class="f-12" for="">{{__('admin.contact_person_phone')}}</label>
                                                    <div class="">{{ ($supplier->mobile)?$supplier->c_phone_code.' '.$supplier->mobile : '-' }}</div>
                                                </div>
                                                @if(!empty($supplier->company_alternative_phone_code) && !empty($supplier->company_alternative_phone))
                                                <div class="col-md-12 mb-3">
                                                    <label class="f-12" for="">{{__('admin.company_alternative_phone')}}</label>
                                                    <div class="">{{ $supplier->company_alternative_phone_code.' '.$supplier->company_alternative_phone }}</div>
                                                </div>
                                                @endif
                                                <div class="col-md-12 mb-3">
                                                    <label class="f-12" for="">{{__('admin.email')}}</label>
                                                    <div class="">{{ $supplier->email ?? '-' }}</div>
                                                </div>

                                                <div class="col-md-12 mb-3">
                                                    <label class="f-12" for="">{{__('admin.nib')}}</label>
                                                    <div class="">{{ $supplier->nib ?? '-' }}</div>
                                                </div>

                                                <div class="col-md-12 mb-3">
                                                    <label class="f-12" for="">{{__('admin.npwp')}}</label>
                                                    <div class="">{{ $supplier->npwp ?? '-' }}</div>
                                                </div>

                                                <div class="col-md-12 mb-3">
                                                    <label class="f-12" for="">{{__('admin.website')}}</label>
                                                    @if(!empty($supplier->website))
                                                        @if(\Illuminate\Support\Str::contains($supplier->website,['http','https']))
                                                            <div class=""><a href="{{$supplier->website}}" target="_blank">{{$supplier->website}}</a></div>
                                                        @else
                                                            <div class=""><a href="{{'https://'.$supplier->website}}" target="_blank">{{$supplier->website}}</a></div>
                                                        @endif
                                                    @else
                                                        <div class="">-</div>
                                                    @endif
                                                </div>

{{--                                                <div class="col-md-12">--}}
{{--                                                    <label class="f-12" for="">{{__('admin.address')}}</label>--}}
{{--                                                    <div class="d-none" >{{ isset($supplier->companyAddress->first()->address) ? strip_tags($supplier->companyAddress->first()->address) : '-' }}</div>--}}

{{--                                                    <div class="mb-3 border">--}}
{{--                                                         <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.7924330919377!2d106.80243161431096!3d-6.290989563324606!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f1fa3c8fa59b%3A0x7df89ae532fac695!2sMarquee%20-%20Alamanda%20Tower!5e0!3m2!1sen!2sin!4v1670921760723!5m2!1sen!2sin" width="100%" height="120" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="mb-3 border">--}}
{{--                                                         <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.7924330919377!2d106.80243161431096!3d-6.290989563324606!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f1fa3c8fa59b%3A0x7df89ae532fac695!2sMarquee%20-%20Alamanda%20Tower!5e0!3m2!1sen!2sin!4v1670921760723!5m2!1sen!2sin" width="100%" height="120" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="mb-3 border">--}}
{{--                                                         <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.7924330919377!2d106.80243161431096!3d-6.290989563324606!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f1fa3c8fa59b%3A0x7df89ae532fac695!2sMarquee%20-%20Alamanda%20Tower!5e0!3m2!1sen!2sin!4v1670921760723!5m2!1sen!2sin" width="100%" height="120" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="mb-3 border">--}}
{{--                                                         <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.7924330919377!2d106.80243161431096!3d-6.290989563324606!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f1fa3c8fa59b%3A0x7df89ae532fac695!2sMarquee%20-%20Alamanda%20Tower!5e0!3m2!1sen!2sin!4v1670921760723!5m2!1sen!2sin" width="100%" height="120" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="border">--}}
{{--                                                         <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.7924330919377!2d106.80243161431096!3d-6.290989563324606!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f1fa3c8fa59b%3A0x7df89ae532fac695!2sMarquee%20-%20Alamanda%20Tower!5e0!3m2!1sen!2sin!4v1670921760723!5m2!1sen!2sin" width="100%" height="120" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="col-xl-8 col-lg-8 ">

                            <div class="row">
                                <div class="col-md-12 prof_tab mb-3">
                                    <div class="card card-body border-0 p-0">
                                        <ul class="nav nav-tabs border nav-justified" id="myTab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active text-center" id="Company-tab"
                                                        data-bs-toggle="tab" data-bs-target="#Company-tab-pane"
                                                        type="button" role="tab" aria-controls="Company-tab-pane"
                                                        aria-selected="false">
                                                    <div><img src="{{ url('assets/images/Company_basic_img.png') }}" alt="" srcset=""></div>
                                                    {{__('admin.company_basics')}}
                                                </button>
                                            </li>

                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="Highlights-tab" data-bs-toggle="tab"
                                                        data-bs-target="#Highlights-tab-pane" type="button" role="tab"
                                                        aria-controls="Highlights-tab-pane" aria-selected="false">
                                                    <div><img src="{{ url('assets/images/Highlights_img.png') }}" alt="" srcset=""></div>
                                                    {{__('admin.achievements')}}
                                                </button>
                                            </li>

                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="Other-tab" data-bs-toggle="tab"
                                                        data-bs-target="#Other-tab-pane" type="button" role="tab"
                                                        aria-controls="Other-tab-pane" aria-selected="false">
                                                    <div><img src="{{ url('assets/images/other_details_img.png') }}" alt="" srcset=""></div>
                                                    {{__('admin.other_details')}}
                                                </button>
                                            </li>
                                        </ul>

                                        <div class="tab-content Sub_tab">
                                            <div class="tab-pane fade show active" id="Company-tab-pane" role="tabpanel"
                                                 aria-labelledby="Company-tab" tabindex="0">
                                                <div class="container border border-top-0">
                                                <div class="row p-3">
                                                @if(!empty($supplier->companyDetails->vision) || !empty($supplier->companyDetails->mission) || !empty($supplier->companyDetails->business_description) || !empty($supplier->companyDetails->company_description) || !empty($supplier->companyDetails->history_growth) || !empty($supplier->companyDetails->industry_information) || !empty($supplier->companyDetails->public_relations) || !empty($supplier->companyDetails->policies) || !empty($supplier->companyDetails->policies_image) || !empty($supplier->companyDetails->advertising) || (count($supplier->supplierProducts) > 0) || ($supplier->companyMembers->whereIn('company_user_type_id', [\App\Models\CompanyMembers::TEAM,\App\Models\CompanyMembers::PROFILE])->count() > 0) || !empty($supplier->catalog) || !empty($supplier->product))
                                                        @if(!empty($supplier->companyDetails->vision) || !empty($supplier->companyDetails->mission) || !empty($supplier->companyDetails->business_description) || !empty($supplier->companyDetails->company_description) || !empty($supplier->companyDetails->history_growth) || !empty($supplier->companyDetails->industry_information) || !empty($supplier->companyDetails->public_relations) || !empty($supplier->companyDetails->policies) || !empty($supplier->companyDetails->policies_image) || !empty($supplier->companyDetails->advertising))
                                                            <div class="col-md-12 py-3">
                                                                <p class="fw-bold color-primary">{{__('admin.about_company')}}</p>
                                                                <p>{!! nl2br(e($supplier->companyDetails->company_description)) ?? '' !!}</p>

                                                                @if(isset($supplier->companyDetails->vision) && !empty($supplier->companyDetails->vision))
                                                                    <div class=" py-2 ">
                                                                        <p class="fw-bold f-13 mb-1">{{__('admin.vision')}}</p>
                                                                        <p class="f-14">{!! nl2br(e($supplier->companyDetails->vision)) ?? '' !!}</p>
                                                                    </div>
                                                                @endif
                                                                @if(isset($supplier->companyDetails->mission) && !empty($supplier->companyDetails->mission))
                                                                    <div class=" py-2 ">
                                                                        <p class="fw-bold f-13 mb-1">{{__('admin.mission')}}</p>
                                                                        <p class="f-14">{!! nl2br(e($supplier->companyDetails->mission)) ?? '' !!}</p>
                                                                    </div>
                                                                @endif
                                                                @if(isset($supplier->companyDetails->business_description) && !empty($supplier->companyDetails->business_description))
                                                                    <div class=" py-2 ">
                                                                        <p class="fw-bold f-13 mb-1">{{__('admin.business_detail')}}</p>
                                                                        <p class="f-14">{!! nl2br(e($supplier->companyDetails->business_description)) ?? '' !!}</p>
                                                                    </div>
                                                                @endif
                                                                @if(isset($supplier->companyDetails->history_growth) && !empty($supplier->companyDetails->history_growth))
                                                                    <div class=" py-2 ">
                                                                        <p class="fw-bold f-13 mb-1">{{__('admin.history_expansion_growth')}}</p>
                                                                        <p class="f-14">{!! nl2br(e($supplier->companyDetails->history_growth)) ?? '' !!}</p>
                                                                    </div>
                                                                @endif
                                                                @if(isset($supplier->companyDetails->industry_information) && !empty($supplier->companyDetails->industry_information))
                                                                    <div class=" py-2 ">
                                                                        <p class="fw-bold f-13 mb-1">{{__('admin.industry_information')}}</p>
                                                                        <p class="f-14">{!! nl2br(e($supplier->companyDetails->industry_information)) ?? '' !!}</p>
                                                                    </div>
                                                                @endif
                                                                @if(isset($supplier->companyDetails->public_relations) && !empty($supplier->companyDetails->public_relations))
                                                                    <div class=" py-2 ">
                                                                        <p class="fw-bold f-13 mb-1">{{__('admin.public_relations')}}</p>
                                                                        <p class="f-14">{!! nl2br(e($supplier->companyDetails->public_relations)) ?? '' !!}</p>
                                                                    </div>
                                                                @endif
                                                                @if((isset($supplier->companyDetails->policies) && !empty($supplier->companyDetails->policies)) || (isset($supplier->companyDetails->policies_image) && !empty($supplier->companyDetails->policies_image)))
                                                                    <div class=" py-2 ">
                                                                        <p class="fw-bold f-13 mb-1">
                                                                            {{__('admin.ahep')}}
                                                                            @if(isset($supplier->companyDetails->policies_image) && !empty($supplier->companyDetails->policies_image))
                                                                            <a href="{{ url('storage')."/".$supplier->companyDetails->policies_image }}" class="text-primary ms-2" data-toggle="tooltip" ata-placement="top" title="{{__('admin.download')}} {{__('admin.ahep')}}" download="{{Str::substr(Str::substr($supplier->companyDetails->policies_image, stripos($supplier->companyDetails->policies_image, 'policies_') + 9), 0, -4)}}"><i class="fa fa-download"></i> </a>
                                                                            @endif
                                                                        </p>
                                                                        <p class="f-14">{!! nl2br(e($supplier->companyDetails->policies)) ?? '' !!}</p>
                                                                    </div>
                                                                @endif
                                                                @if(isset($supplier->companyDetails->advertising) && !empty($supplier->companyDetails->advertising))
                                                                    <div class=" py-2 ">
                                                                        <p class="fw-bold f-13 mb-1">{{__('admin.advertising')}}</p>
                                                                        <p class="f-14">{!! nl2br(e($supplier->companyDetails->advertising)) ?? '' !!}</p>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endif
                                                        @if(isset($supplier->supplierProducts) && ($supplier->supplierProducts->count()>0))

                                                            <div class="col-md-12 py-2">
                                                            <p class="fw-bold color-primary">{{__('admin.products')}}</p>
                                                            <div class="pt-lg-4 py-2 supp_prod_show_img owl-carousel owl-products">
                                                                @foreach($supplier->supplierProducts->toArray() as $singleProduct)
                                                                    <div class="text-center productsImg shadow  mb-3 py-3 h-100 card popup-gallery">
                                                                        @if(!empty($singleProduct['product_image']))
                                                                            <div class="pro_img mx-auto">
                                                                                <a href="{{ url('storage')."/".$singleProduct['product_image']['image'] }}" alt="">
{{--                                                                                <img src="{{ asset($singleProduct['product_image']['image']) }}" alt="" srcset="">--}}
                                                                                <img src="{{ url('storage')."/".$singleProduct['product_image']['image'] }}" alt="" srcset="" class="">
                                                                                </a>
                                                                            </div>
                                                                        @else
                                                                        <div class="pro_img mx-auto">
                                                                        <a href="{{ url('front-assets/images/no_product.png') }}">
                                                                            <img src="{{ url('front-assets/images/no_product.png') }}" alt="" srcset="">
                                                                        </a>
                                                                        </div>
                                                                        @endif
                                                                        <h6 class="mb-1 mt-3 color-primary fw-bold px-2">{{$singleProduct['product']['name'] ?? ''}}</h6>
                                                                        @if(!empty($singleProduct['product_discount_range']))
                                                                        <p class="f-14 text-muted mb-1  px-2">{{__('admin.min_order_qty')}}: {{($singleProduct['product_discount_range']['min_qty'] ?? '1').' '. ($singleProduct['product_unit']['name'] ?? '')}}</p>
                                                                        @endif
                                                                        <div class="f-13  px-2">{!! \Illuminate\Support\Str::words(strip_tags($singleProduct['description']),17) ?? '' !!}</div>
                                                                    </div>
                                                                @endforeach
                                                            </div>

                                                        </div>
                                                        @endif
                                                        <div class="row justify-content-center">
                                                            @if(isset($supplier->catalog) && !empty($supplier->catalog))
                                                            <div class="col-md-6 mb-3">
                                                                <div class="card card-body border-0 rounded-1 position-relative" style="background-color: #F5F5F5;">
                                                                    <div class="row align-items-center justify-content-center">
                                                                        <div class="col-md-auto">
                                                                            <img src="{{ url('front-assets/images/catelog.png') }}" class="mw-100" alt="" srcset="">
                                                                        </div>
                                                                        <div class="col-md-auto text-center">
                                                                            <h6 class="mb-0 color-primary fw-bold">{{__('admin.download')}} {{__('admin.catalog')}}</h6>
                                                                            <a href="{{ url('storage')."/".$supplier->catalog }}" class="stretched-link" download="{{Str::substr(Str::substr($supplier->catalog, stripos($supplier->catalog, 'catalog_') + 8), 0, -4)}}"></a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @endif
                                                            @if(isset($supplier->product) && !empty($supplier->product))
                                                                <div class="col-md-6 mb-3">
                                                                    <div class="card card-body border-0 rounded-1 position-relative" style="background-color: #F5F5F5;">
                                                                        <div class="row align-items-center justify-content-center">
                                                                            <div class="col-md-auto">
                                                                                <img src="{{ url('front-assets/images/catelog.png') }}" class="mw-100" alt="" srcset="">
                                                                            </div>
                                                                            <div class="col-md-auto text-center">
                                                                                <h6 class="mb-0 color-primary fw-bold">{{__('admin.download')}} {{__('admin.product')}}</h6>
                                                                                <a href="{{ url('storage')."/".$supplier->product }}" class="stretched-link" download="{{Str::substr(Str::substr($supplier->product, stripos($supplier->product, 'product_') + 8), 0, -4)}}"></a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        @if($supplier->companyMembers->where('company_user_type_id', \App\Models\CompanyMembers::TEAM)->count()>0 && (!empty($supplier->companyDetails->vision) ||
                                                       !empty($supplier->companyDetails->mission) ||
                                                       !empty($supplier->companyDetails->company_description) ||
                                                       !empty($supplier->supplierProducts)))
                                                            <hr>
                                                        @endif

                                                        @if($supplier->companyMembers->where('company_user_type_id', \App\Models\CompanyMembers::TEAM)->count()>0)
                                                            <div class="col-md-12 py-3">
                                                                <p class="fw-bold color-primary">{{__('admin.core_team_details')}}</p>
                                                                <div class=" py-lg-4 py-2 supp_prod_show_img mt-0 owl-team owl-carousel popup-gallery7">
                                                                    @foreach($supplier->companyMembers->where('company_user_type_id', \App\Models\CompanyMembers::TEAM)->toArray() as $team)

                                                                    <div class="col-md-12 teamImg h-100" style="padding-top: 56px">

                                                                        <div class=" card border-0 px-0 mb-lg-4 mb-3 shadow h-100">
                                                                            <div class="card-body py-3" style="background-color: #F5F5F5;">
                                                                                <div class="text-center rounded" style="margin-top: -56px;">
                                                                                    @if(!empty($team['image']))
                                                                                        <div class="pro_img mx-auto mb-2" style="background-color: #fff;">
                                                                                        <a href="{{ url('storage')."/".$team['image'] }}">
                                                                                            <img src="{{ url('storage')."/".$team['image'] }}" style="max-width: 80px; " class="d-inline" alt="" srcset="">
                                                                                            </a>
                                                                                        </div>
                                                                                    @else
                                                                                        <div class="pro_img mx-auto mb-2" style="background-color: #fff;">
                                                                                        <a href="{{ url('front-assets/images/user_pro.png') }}">
                                                                                            <img src="{{ url('front-assets/images/user_pro.png') }}" style="max-width: 80px; " class="d-inline" alt="" srcset="">
                                                                                        </a>
                                                                                        </div>
                                                                                    @endif
                                                                                    <p class="fw-bold mb-1">{{ $team['salutation']." ".$team['firstname']." ".$team['lastname'] }}</p>
                                                                                    <p class="f-13" style="font-size: 13px;">{{ $team['designation'] }}</p>
                                                                                    <div class="text-center my-3">
                                                                                        @if(!empty($team['phone']))
                                                                                            <p class="f-14 mb-1">{{__('admin.phone')}}: {{ $team['phone'] }}</p>
                                                                                        @endif

                                                                                        @if(!empty($team['email']))
                                                                                            <p class="f-14 mb-1">{{__('admin.email')}}: {{ $team['email'] }}</p>
                                                                                        @endif
                                                                                            @if(!empty($team['description']))
                                                                                                <p class="f-14 mb-1 mt-2 px-3"><small>{!! nl2br(e($team['description'])) !!}</small></p>
                                                                                            @endif
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    @endforeach

                                                                </div>
                                                            </div>
                                                        @endif

                                                       @if($supplier->companyMembers->where('company_user_type_id', \App\Models\CompanyMembers::PROFILE)->count()>0)
                                                            <hr>
                                                            <div class="col-md-12 py-3">
                                                                <p class="fw-bold color-primary">{{__('admin.client_portfolio')}}</p>
                                                                <div class="row py-lg-4 py-2 supp_prod_show_img g-5 popup-gallery1">
                                                                    @foreach($supplier->companyMembers->where('company_user_type_id', \App\Models\CompanyMembers::PROFILE) as $profile)
                                                                        <div class="col-md-6 col-xl-4 text-center mb-3">
                                                                            <div class="shadow pb-2 h-100">
                                                                            @if(!empty($profile->image))
                                                                                <div class="client_portfolio">
                                                                                    <a href="{{ url('storage')."/".$profile->image }}">
                                                                                    <img src="{{ url('storage')."/".$profile->image }}" style="max-width: 80px;" alt="" srcset="">
                                                                                    </a>
                                                                                </div>
                                                                            @endif
                                                                            <p class="fw-bold f-14 my-2">{{ $profile->company_name ?? '' }}</p>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                       @endif

                                                @else
                                                <div id="underconstuction" class="col-md-12 text-center pt-5">
                                                    <h1 class="color-primary">{{__('admin.front_data_not_found')}}</h1>
                                                    <img src="{{ url('assets/images/comingsoon.png') }}" class="mw-100" alt="Coming soon">
                                                </div>
                                                @endif
                                                </div>
                                                </div>
                                            </div>

                                            @if((!empty($supplier->companyHighlights) ) || (!empty($supplier->companyMembers)))
                                            <div class="tab-pane fade " id="Highlights-tab-pane" role="tabpanel" aria-labelledby="Highlights-tab" tabindex="0">
                                               <div class="container border border-top-0">
                                                    <div class="row p-3">
                                                        @if(isset($supplier->companyHighlights) && ($supplier->companyHighlights->where('category', \App\Models\CompanyHighlights::AWARD)->where('model_id',$supplierId)->where('model_type',\App\Models\Supplier::class)->count()>0))
                                                            <div class="col-md-12 py-3">
                                                                <p class="fw-bold color-primary">{{__('admin.awards')}}</p>
                                                                <div class="row py-lg-4 py-2 supp_prod_show_img g-5 popup-gallery2">
                                                                   @foreach($supplier->companyHighlights->where('category', \App\Models\CompanyHighlights::AWARD)->where('model_id',$supplierId)->where('model_type',\App\Models\Supplier::class)->get() as $award)
                                                                        <div class="col-md-6 col-xl-4 text-center mb-3">
                                                                        <div class="shadow card h-100">
                                                                            <div class="pro_img mx-auto bg-white">
                                                                                <a href="{{ url('storage')."/".$award->image}}">
                                                                                    <img src="{{ url('storage')."/".$award->image}}" alt="" srcset="">
                                                                                </a>
                                                                            </div>
                                                                            <p class="f-14 my-2 mb-2">{{ $award->name ?? '' }}</p>
                                                                        </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if(isset($supplier->companyMembers) && ($supplier->companyMembers->where('company_user_type_id', \App\Models\CompanyMembers::TESTIMONIAL)->where('model_id',$supplierId)->where('model_type',\App\Models\Supplier::class)->count()>0))
                                                            <hr>
                                                            <div class="col-md-12 py-3 testimonial">
                                                                <p class="fw-bold color-primary ">{{__('admin.testimonials')}}</p>
                                                                <div class="py-lg-4 py-2 supp_prod_show_img  shadow">
                                                                    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="true">
                                                                        <div class="carousel-indicators d-none">
                                                                            @php $k = 0 ; @endphp
                                                                        @foreach($supplier->companyMembers->where('company_user_type_id', \App\Models\CompanyMembers::TESTIMONIAL)->where('model_id',$supplierId)->where('model_type',\App\Models\Supplier::class)->toArray() as $testimonial)

                                                                                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{$k}}" class="@if($k==0) active @endif" aria-current="true" aria-label="Slide 1"></button>
                                                                            @php $k++ @endphp
                                                                        @endforeach
                                                                        </div>

                                                                        <div class="carousel-inner">
                                                                            @php $j = 0 ; @endphp

                                                                        @foreach($supplier->companyMembers->where('company_user_type_id', \App\Models\CompanyMembers::TESTIMONIAL)->where('model_id',$supplierId)->where('model_type',\App\Models\Supplier::class)->toArray() as $testimonial)
                                                                            <div class="carousel-item @if($j==0) active @endif" data-bs-interval="4000">
                                                                                <div class="text-center px-lg-5 px-3 ">
                                                                                    @if(!empty($testimonial['quote']))
                                                                                    <p class="fw-bold">{{$testimonial['quote']}}</p>
                                                                                    @endif
                                                                                    <q class="quot_img">{!! nl2br(e($testimonial['description'])) !!}</q>
                                                                                    <div class="pt-lg-3 py-2">
                                                                                        <div class="quote_image pb-3">
                                                                                        <div class="logoimg shadow mx-auto">
                                                                                            <img src="{{ url('storage')."/".$testimonial['image']}}"  alt="Testimonial Logo" srcset="">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="fw-bold">{{$testimonial['salutation']." ".$testimonial['firstname']." ".$testimonial['lastname']}}</div>
                                                                                        <p class="f-13 text-muted">
                                                                                            {{$testimonial['designation']}} -
                                                                                            {{$testimonial['company_name']}}</p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @php $j++; @endphp
                                                                            @endforeach
                                                                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                                                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                                                <span class="visually-hidden">Previous</span>
                                                                            </button>
                                                                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                                                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                                                <span class="visually-hidden">Next</span>
                                                                            </button>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if(isset($supplier->companyHighlights) &&($supplier->companyHighlights->where('category', \App\Models\CompanyHighlights::CERTIFICATIONS)->where('model_id',$supplierId)->where('model_type',\App\Models\Supplier::class)->count()>0))
                                                            <hr>
                                                            <div class="col-md-12 py-3">
                                                                <p class="fw-bold color-primary">{{__('admin.certifications')}}</p>
                                                                <div class="py-lg-4 py-2 supp_prod_show_img owl-carousel owl-certificate popup-gallery3">
                                                                    @foreach(($supplier->companyHighlights->where('category', \App\Models\CompanyHighlights::CERTIFICATIONS)->where('model_id',$supplierId)->where('model_type',\App\Models\Supplier::class)->get())->toArray() as $key => $certification)
                                                                        @if($certification['image'] != null)
                                                                            <div class="certificateImg shadow card h-100">
                                                                                <div class="pro_img mx-auto bg-white">
                                                                                    <a href="{{ url('storage')."/".$certification['image'] }}">
                                                                                        <img src="{{ url('storage')."/".$certification['image'] }}" alt="" srcset="">
                                                                                    </a>
                                                                                </div>
                                                                                <p class="p-2 text-center">{{$certification['name']}}</p>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach

                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if(isset($supplier->companyHighlights) &&($supplier->companyHighlights->where('category', \App\Models\CompanyHighlights::MEDIARECOGNITION)->where('model_id',$supplierId)->where('model_type',\App\Models\Supplier::class)->count()>0))
                                                            <hr>
                                                            <div class="col-md-12 py-3">
                                                                <p class="fw-bold color-primary">{{__('admin.media_recognition')}}</p>
                                                                <div class="py-lg-4 py-2 supp_prod_show_img owl-carousel owl-recognition popup-gallery4">
                                                                    @foreach(($supplier->companyHighlights->where('category', \App\Models\CompanyHighlights::MEDIARECOGNITION)->where('model_id',$supplierId)->where('model_type',\App\Models\Supplier::class)->get())->toArray() as $key => $mediarecognition)
                                                                        @if($mediarecognition['image'] != null)
                                                                            <div class="mediaRecognitionImg shadow card h-100">
                                                                                <div class="pro_img mx-auto bg-white">
                                                                                    <a href="{{ url('storage')."/".$mediarecognition['image'] }}">
                                                                                        <img src="{{ url('storage')."/".$mediarecognition['image'] }}" alt="" srcset="">
                                                                                    </a>
                                                                                </div>
                                                                                <p class="p-2 text-center">{{$mediarecognition['name']}}</p>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach

                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            @else
                                            <div class="tab-pane fade " id="Highlights-tab-pane" role="tabpanel" aria-labelledby="Highlights-tab" tabindex="0">
                                               <div class="container border border-top-0">
                                                    <div class="row p-3">
                                                        <div id="underconstuction" class="col-md-12 text-center pt-5">
                                                            <h1 class="color-primary">{{__('admin.front_data_not_found')}}</h1>
                                                            <img src="{{ url('assets/images/comingsoon.png') }}" class="mw-100" alt="Coming soon">
                                                        </div>
                                                    </div>
                                               </div>
                                            </div>
                                            @endif

                                            @if((isset($supplier->companyDetails->annual_sales) && !empty($supplier->companyDetails->annual_sales)) || (isset($supplier->companyDetails->financial_target) && !empty($supplier->companyDetails->financial_target)) || (isset($supplier->companyDetails->number_of_employee) && !empty($supplier->companyDetails->number_of_employee)) || $supplier->companyMembers->where('company_user_type_id', \App\Models\CompanyMembers::PARTNER)->count()>0 || (isset($supplier->supplierGallery) && ($supplier->supplierGallery->count()>0)))
                                            <div class="tab-pane fade " id="Other-tab-pane" role="tabpanel" aria-labelledby="Other-tab" tabindex="0">
                                                <div class="container border border-top-0">
                                                    <div class="row p-3">
                                                        @if(isset($supplier->companyDetails->annual_sales) || isset($supplier->companyDetails->financial_target) || isset($supplier->companyDetails->number_of_employee))
                                                            <div class="col-md-12 py-3">

                                                                <div class="row supp_prod_show_img">
                                                                    @if(isset($supplier->companyDetails->annual_sales))
                                                                        <div class="col-md-6 col-xl-4 text-center mb-3">
                                                                            <div class="card card-body border-0 rounded-1"
                                                                                 style="background-color: #F5F5F5;">
                                                                                <div class="row align-items-center">
                                                                                    <div class="col-md-auto">
                                                                                        <img src="{{ asset('assets/images/Annual_img.png') }}" class="mw-100" alt="" srcset="">
                                                                                    </div>
                                                                                    <div class="col-md-auto text-start">
                                                                                        <h5 class="mb-1 color-primary fw-bold">{{numberToName($supplier->companyDetails->annual_sales,false)}}</h5>
                                                                                        <div class="text-muted f-13">{{__('admin.annual_sales')}}</div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                    @if(isset($supplier->companyDetails->financial_target))
                                                                        <div class="col-md-6 col-xl-4 text-center mb-3">
                                                                            <div class="card card-body border-0 rounded-1"
                                                                                 style="background-color: #F5F5F5;">
                                                                                <div class="row align-items-center">
                                                                                    <div class="col-md-auto">
                                                                                        <img src="{{ asset('assets/images/financial_img.png') }}"
                                                                                             class="mw-100" alt="" srcset="">
                                                                                    </div>
                                                                                    <div class="col-md-auto text-start">
                                                                                        <h5 class="mb-1 color-primary fw-bold">
                                                                                            {{numberToName($supplier->companyDetails->financial_target,false)}}</h5>
                                                                                        <div class="text-muted f-13">{{__('admin.financial_targets')}}</div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                    @if(isset($supplier->companyDetails->number_of_employee))
                                                                        <div class="col-md-6 col-xl-4 text-center mb-3">
                                                                            <div class="card card-body border-0 rounded-1"
                                                                                 style="background-color: #F5F5F5;">
                                                                                <div class="row align-items-center">
                                                                                    <div class="col-md-auto">
                                                                                        <img src="{{ asset('assets/images/No_of_employee.png') }}"
                                                                                             class="mw-100" alt="" srcset="">
                                                                                    </div>
                                                                                    <div class="col-md-auto text-start">
                                                                                        <h5 class="mb-1 color-primary fw-bold">{{ $supplier->companyDetails->number_of_employee }}</h5>
                                                                                        <div class="text-muted f-13">{{__('admin.number_of_employee')}}</div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if($supplier->companyMembers->where('company_user_type_id', \App\Models\CompanyMembers::PARTNER)->count()>0)
                                                            <hr>
                                                            <div class="col-md-12 py-3">
                                                                <p class="fw-bold color-primary">{{__('admin.partner')}}</p>
                                                                <div class="py-2 partnerimg owl-carousel owl-partner popup-gallery5">
                                                                    @foreach($supplier->companyMembers->where('company_user_type_id', \App\Models\CompanyMembers::PARTNER)->toArray() as $key => $value)
                                                                        @if (!empty($value['image']))
                                                                            <div class="shadow card h-100">
                                                                                <div class="pro_img mx-auto bg-white" style="height: 70px">
                                                                                <a href="{{ url('storage')."/".($value['image']) }}">
                                                                                    <img src="{{ url('storage')."/".($value['image']) }}"  class="d-inline partnerImg" style="max-height: 50px !important; width: auto !important; object-fit: contain;" alt="" srcset="">
                                                                                    </a>
                                                                                </div>
                                                                                <p class="f-14 px-2 text-center mb-2">{{$value['company_name']}}</p>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if(isset($supplier->supplierGallery) && !empty($supplier->supplierGallery->count()>0))
                                                            <hr>
                                                            <div class="row py-2 otherdetailimg popup-gallery6">
                                                            <p class="fw-bold color-primary">Gallery</p>
                                                                    @foreach($supplier->supplierGallery as $gallery)
                                                                        @if (!empty($gallery))
                                                                            <div class="col-md-4 gallerysection mb-3">
                                                                                <div class="shadow card border-0 w-100 ">
                                                                                    <a href="{{ url('storage')."/".($gallery->image) }}">
                                                                                        <img src="{{ url('storage')."/".($gallery->image) }}"  class="mw-100" alt="" srcset="">
                                                                                     </a>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                            </div>
                                                        @endif

                                                    </div>
                                                </div>
                                            </div>

                                            @else
                                                <div class="tab-pane fade " id="Other-tab-pane" role="tabpanel" aria-labelledby="Other-tab" tabindex="0">
                                                    <div class="container border border-top-0">
                                                        <div class="row p-3">
                                                            <div id="underconstuction" class="col-md-12 text-center pt-5">
                                                                <h1 class="color-primary">{{__('admin.front_data_not_found')}}</h1>
                                                                <img src="{{ url('assets/images/comingsoon.png') }}" class="mw-100" alt="Coming soon">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
    </div>
{{--    <script src="js/owl.carousel.min.js"></script>--}}
    <script>
        $(document).ready(function () {
            $("body").tooltip({ selector: '[data-toggle=tooltip]' });
            $('.owl-products').owlCarousel({
                loop:false,
                mouseDrag:false,
                autoplay:false,
                autoplayTimeout:1500,
                autoplayHoverPause:true,
                margin: 15,
                animateOut: 'slideOutDown',
                animateIn: 'flipInX',
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 1,
                        nav: true,
                    },
                    768: {
                        items: 2,
                        nav: true,
                    },
                    1200: {
                        items: 2,
                        nav: true,
                    }
                }
            });

            $('.owl-team').owlCarousel({
                loop:false,
                mouseDrag:false,
                autoplay:false,
                autoplayTimeout:1500,
                autoplayHoverPause:true,
                margin:15,
                animateOut: 'slideOutDown',
                animateIn: 'flipInX',
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 1,
                        nav: true,
                    },
                    800: {
                        items: 2,
                        nav: true,
                    },
                }
            })

            $('.owl-partner').owlCarousel({
                loop:false,
                mouseDrag:false,
                margin: 30,
                autoplay:false,
                autoplayTimeout:1500,
                autoplayHoverPause:true,
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 1,
                        nav: true

                    },
                    600: {
                        items: 3,
                        nav: true
                    },
                    1200: {
                        items: 4,
                        nav: true
                    }
                }
            });


            $('.owl-testimonial').owlCarousel({
                loop:false,
                mouseDrag:false,
                animateOut: 'slideOutDown',
                autoplay:false,
                autoplayTimeout:1500,
                autoplayHoverPause:true,
                animateIn: 'flipInX',
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 1,
                        nav: true,
                    },
                }
            });

            $('.owl-certificate').owlCarousel({
                loop:false,
                mouseDrag:false,
                animateOut: 'slideOutDown',
                margin: 15,
                animateIn: 'flipInX',
                autoplay:false,
                autoplayTimeout:1500,
                autoplayHoverPause:true,
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 1,
                        nav: true

                    },
                    600: {
                        items: 3,
                        nav: true
                    },
                    1200: {
                        items: 4,
                        nav: true
                    }
                }
            });

            $('.owl-recognition').owlCarousel({
                loop:false,
                mouseDrag:false,
                animateOut: 'slideOutDown',
                animateIn: 'flipInX',
                margin: 15,
                autoplay:false,
                autoplayTimeout:1500,
                autoplayHoverPause:true,
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 1,
                        nav: true

                    },
                    600: {
                        items: 3,
                        nav: true
                    },
                    1200: {
                        items: 4,
                        nav: true
                    }
                }
            });
        });

        Math.random()
    </script>
    <link href="{{ URL::asset('front-assets/js/zoom/magnific-popup.css') }}" rel="stylesheet">
    <script src="{{ URL::asset('front-assets/js/zoom/jquery.magnific-popup.min.js') }}"></script>
    <script>
       $(document).ready(function() {
	$('.popup-gallery').magnificPopup({
		delegate: 'a',
		type: 'image',
		tLoading: 'Loading image #%curr%...',
		mainClass: 'mfp-img-mobile',
        closeBtnInside: false,
		gallery: {
			enabled: true,
			navigateByImgClick: true,
			preload: [0,1] // Will preload 0 - before current, and 1 after the current image
		},
		image: {
			tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
		}
	});
    $('.popup-gallery1').magnificPopup({
		delegate: 'a',
		type: 'image',
		tLoading: 'Loading image #%curr%...',
		mainClass: 'mfp-img-mobile',
        closeBtnInside: false,
		gallery: {
			enabled: true,
			navigateByImgClick: true,
			preload: [0,1] // Will preload 0 - before current, and 1 after the current image
		},
		image: {
			tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
		}
	});
    $('.popup-gallery2').magnificPopup({
		delegate: 'a',
		type: 'image',
		tLoading: 'Loading image #%curr%...',
		mainClass: 'mfp-img-mobile',
        closeBtnInside: false,
		gallery: {
			enabled: true,
			navigateByImgClick: true,
			preload: [0,1] // Will preload 0 - before current, and 1 after the current image
		},
		image: {
			tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
		}
	});
    $('.popup-gallery3').magnificPopup({
		delegate: 'a',
		type: 'image',
		tLoading: 'Loading image #%curr%...',
		mainClass: 'mfp-img-mobile',
        closeBtnInside: false,
		gallery: {
			enabled: true,
			navigateByImgClick: true,
			preload: [0,1] // Will preload 0 - before current, and 1 after the current image
		},
		image: {
			tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
		}
	});
    $('.popup-gallery4').magnificPopup({
		delegate: 'a',
		type: 'image',
		tLoading: 'Loading image #%curr%...',
		mainClass: 'mfp-img-mobile',
        closeBtnInside: false,
		gallery: {
			enabled: true,
			navigateByImgClick: true,
			preload: [0,1] // Will preload 0 - before current, and 1 after the current image
		},
		image: {
			tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
		}
	});
    $('.popup-gallery5').magnificPopup({
		delegate: 'a',
		type: 'image',
		tLoading: 'Loading image #%curr%...',
		mainClass: 'mfp-img-mobile',
        closeBtnInside: false,
		gallery: {
			enabled: true,
			navigateByImgClick: true,
			preload: [0,1] // Will preload 0 - before current, and 1 after the current image
		},
		image: {
			tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
		}
	});
    $('.popup-gallery6').magnificPopup({
		delegate: 'a',
		type: 'image',
		tLoading: 'Loading image #%curr%...',
		mainClass: 'mfp-img-mobile',
        closeBtnInside: false,
		gallery: {
			enabled: true,
			navigateByImgClick: true,
			preload: [0,1] // Will preload 0 - before current, and 1 after the current image
		},
		image: {
			tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
		}
	});
    $('.popup-gallery7').magnificPopup({
		delegate: 'a',
		type: 'image',
		tLoading: 'Loading image #%curr%...',
		mainClass: 'mfp-img-mobile',
        closeBtnInside: false,
		gallery: {
			enabled: true,
			navigateByImgClick: true,
			preload: [0,1] // Will preload 0 - before current, and 1 after the current image
		},
		image: {
			tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
		}
	});
});
    </script>
@endsection
