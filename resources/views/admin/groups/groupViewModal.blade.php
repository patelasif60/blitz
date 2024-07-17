<div class="modal-header py-3">
    <h5 class="modal-title d-flex align-items-center" id="viewGroupModalLabel">
        <img height="24px" class="pe-2"
             src="{{URL::asset('front-assets/images/icons/order_detail_title.png')}}"
             alt="View Group"> {{$data->group_number ?? ''}}
    </h5>
    <button type="button" class="btn-close ms-0 d-flex" data-bs-dismiss="modal" aria-label="Close">
        <img src="{{URL::asset('front-assets/images/icons/times.png')}}" alt="Close">
    </button>
</div>
<div class="modal-body p-3">
    <div id="viewGroupDetailBlock">
        <div class="row align-items-stretch">
            <!-- Group Detail -->
            <div class="col-md-12 pb-2">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h5><img height="20px" src="{{URL::asset('assets/icons/group_details.png')}}"
                                 alt="{{ __('admin.group_detail') }}" class="pe-2">{{ __('admin.group_detail') }}</h5>
                    </div>
                    <div class="card-body p-3 pb-1">
                        <div class="row groupform_view bg-white">
                            <!-- <div class="col-md-5 pb-2">
                                <img src="steel_img.jpg" class="mw-100" style="max-height: 200px;" alt="" srcset="">
                            </div> -->
                            <div class="col-md-4 ">
                                <div id="carouselExampleSlidesOnly"
                                     class="carousel slide bg-light h-100 d-flex align-items-center"  data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                            @if(isset($groupImages) && sizeof($groupImages) > 0)
                                                @php $i=1; @endphp
                                                @foreach($groupImages as $grpImage)
                                                    @if($i == 1)
                                                        <div class="carousel-item active">
                                                            <div class="ratio ratio-16x9">
                                                                <img src="{{ url('/storage/'.$grpImage->image) }}" alt="{{ __('admin.no_image') }}">
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="carousel-item">
                                                            <div class="ratio ratio-16x9">
                                                                <img src="{{ url('/storage/'.$grpImage->image) }}" alt="{{ __('admin.no_image') }}">
                                                            </div>
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
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleSlidesOnly" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleSlidesOnly" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6 pb-2">
                                        <label>{{ __('admin.group_name') }}:</label>
                                        <div class="">{{$data->name ?? ''}}</div>
                                    </div>
                                    <div class="col-md-6 pb-2">
                                        <label>{{ __('admin.product_name') }}:</label>
                                        <div class="">{{$data->product_name ?? ''}}</div>
                                    </div>
                                    <div class="col-md-6 pb-2">
                                        <label>{{__('admin.product')}} {{__('admin.category')}}:</label>
                                        <div class="text-dark"> {{$data->category_name ?? ''}}</div>
                                    </div>
                                    <div class="col-md-6 pb-2">
                                        <label>{{__('admin.product_sub_category')}}:</label>
                                        <div class="text-dark">{{$data->sub_category_name ?? ''}}</div>
                                    </div>
                                    <div class="col-md-6 pb-2">
                                        <label>{{__('admin.target_quantity')}}:</label>
                                        <div class="text-dark">{{$data->target_quantity ?? ''}} {{ $data->unit_name }}</div>
                                    </div>
                                    <div class="col-md-6 pb-2">
                                        <label>{{__('admin.prospect_quantity')}}:</label>
                                        <div class="text-dark">{{$data->reached_quantity ?? ''}} {{ $data->unit_name }}</div>
                                    </div>
                                    <div class="col-md-6 pb-2">
                                        <label>{{__('admin.price')}}({{ __('admin.per_unit_in_rp') }}):</label>
                                        <div class="text-dark">Rp
                                            {{$data->price ?? ''}}</div>
                                    </div>
                                    <div class="col-md-6 pb-2">
                                        <label>{{__('admin.total')}} {{__('admin.buyers')}}:</label>
                                        @php
                                            $buyerCount=0;
                                            foreach($data->groupMembersMultiple as $buyer) {
                                               $buyerCount++;
                                            }
                                        @endphp
                                        <div>{{$buyerCount ?? '0'}}</div>
                                    </div>

                                    <div class="col-md-6 pb-2">
                                        <label>{{__('admin.exp_date')}}:</label>
                                        <div class="text-dark">{{ date('d-m-Y', strtotime($data->end_date)) ?? '' }}</div>
                                    </div>
                                    <div class="col-md-6 pb-2">
                                        <label>{{__('admin.status')}}:</label>
                                        <div class="text-dark">{{$data->group_status_name ?? ''}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 pb-2">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h5><img height="20px"
                                 src="{{URL::asset('assets/icons/people-carry-1.png')}}"
                                 alt="{{__('admin.supplier_detail')}}" class="pe-2"> {{__('admin.supplier_detail')}}
                        </h5>
                    </div>

                    <div class="card-body p-3 pb-1">
                        <div class="row groupform_view bg-white">

                            <div class="col-md-6 pb-2">
                                <label>{{__('admin.supplier_company_name')}} </label>
                                <div class="text-dark">{{$data->supplier_name ?? ''}}</div>
                            </div>
                            <div class="col-md-6 pb-2">
                                <label>{{__('admin.supplier_name')}}</label>
                                <div class="text-dark">{{$data->contact_person_name ?? ''}} {{$data->contact_person_last_name ?? ''}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-12 pb-2">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h5 height="20px" class="d-flex align-items-center"><img
                                src="{{URL::asset("assets/icons/boxes.png")}}"
                                alt="Range Detail" class="pe-2"> {{ __('admin.range_details') }}</h5>
                    </div>
                    <div class="card-body p-0 pb-1">
                        <div class="table-responsive p-2 pb-0">
                            <table class="table text-dark table-striped">
                                <tbody>
                                <tr class="bg-light">
                                    <th class="text-center">{{__('admin.min_quantity')}}</th>
                                    <th class="text-center">{{__('admin.max_quantity')}}</th>
                                    <th class="text-center">{{__('admin.price')}}({{ __('admin.per_unit_in_rp') }})</th>
                                    <th class="text-center">{{__('admin.discount')}}</th>
                                    <th class="text-center">{{__('admin.discounted_price')}}({{ __('admin.per_unit_in_rp') }})</th>
                                </tr>
                                @if($discount)
                                    @foreach($discount as $key => $value)
                                        <tr>
                                            <td class="text-nowrap text-center">{{ $value['min_quantity'] ?? ''}} {{ $data->unit_name }}</td>
                                            <td class="text-nowrap text-center">{{ $value['max_quantity'] ?? ''}} {{ $data->unit_name }}</td>
                                            <td class="text-nowrap text-center text-danger"><strike>Rp {{ $data->price ?? ''}}</strike></td>
                                            <td class="text-nowrap text-center">{{ $value['discount'] ?? ''}}%</td>
                                            <td class="text-nowrap text-center">Rp {{ $value['discount_price'] ?? ''}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer px-3">
    <a class="btn btn-cancel" data-bs-dismiss="modal">{{__('admin.cancel')}}</a>
</div>
