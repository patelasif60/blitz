@extends('admin/adminLayout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-sm-6 mb-4 mb-xl-0">
                    <h3>{{__('admin.dashboard')}}</h3>
                </div>
            </div>

            <div class="tab-content tab-transparent-content pb-0 dashboardcard">
                <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                    <div class="row">
						@php
                          $deshboard_grid_main = (auth()->user()->role_id == 3) ? 4 : 3;
                        @endphp

                        @if(auth()->user()->role_id != 3 && !auth()->user()->hasRole('jne'))
                            @if(!auth()->user()->hasRole('finance'))
                                <div class="col-12 col-sm-6 col-md-6 col-xl-{{$deshboard_grid_main}} grid-margin stretch-card">
                                    <div class="card">
                                        <div class="card-body bg_product">
                                            <a class="stretched-link" href="{{ route('products-list') }}"></a>
                                            <div class="d-flex flex-wrap justify-content-between">
                                                <h4 class="card-title">{{__('admin.products')}}</h4>
                                            </div>
                                            <div id="sales" class="carousel slide dashboard-widget-carousel position-static pt-2"
                                                data-bs-ride="carousel">
                                                <div class="carousel-inner">
                                                    <div class="carousel-item active">
                                                        <div class="d-flex flex-wrap align-items-baseline">
                                                            <h2 class="me-3">{{ $products }}</h2>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        <div class="col-12 col-sm-6 col-md-6 col-xl-{{$deshboard_grid_main}} grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body bg_Suppliers">
                                <a class="stretched-link" href="{{ route('admin.supplier.index') }}"></a>
                                    <div class="d-flex flex-wrap justify-content-between">
                                        <h4 class="card-title">{{__('admin.suppliers')}}</h4>
                                    </div>
                                    <div id="purchases"
                                        class="carousel slide dashboard-widget-carousel position-static pt-2"
                                        data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <div class="d-flex flex-wrap align-items-baseline">
                                                    <h2 class="me-3">{{ $suppliers }}</h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(!auth()->user()->hasRole('jne') && !auth()->user()->hasRole('finance'))
                        <div class="col-12 col-sm-6 col-md-6 col-xl-{{$deshboard_grid_main}} grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body bg_RFQs">
                                <a class="stretched-link" href="{{ route('rfq-list') }}"></a>
                                    <div class="d-flex flex-wrap justify-content-between">
                                        <h4 class="card-title">{{__('admin.rfqs')}}</h4>
                                    </div>
                                    <div id="returns" class="carousel slide dashboard-widget-carousel position-static pt-2"
                                        data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <div class="d-flex flex-wrap align-items-baseline">
                                                    <h2 class="me-3">{{ $rfqs }}</h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(!auth()->user()->hasRole('finance'))
                        <div class="col-12 col-sm-6 col-md-6 col-xl-{{$deshboard_grid_main}} grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body bg_Quotes">
                                        <a class="stretched-link" href="{{ route('quotes-list') }}"></a>
                                        <div class="d-flex flex-wrap justify-content-between">
                                            <h4 class="card-title">{{__('admin.quotes')}}</h4>
                                        </div>
                                        <div id="marketing"
                                             class="carousel slide dashboard-widget-carousel position-static pt-2"
                                             data-bs-ride="carousel">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active">
                                                    <div class="d-flex flex-wrap align-items-baseline">
                                                        <h2 class="me-3">{{ $quotes }}</h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col-12 col-sm-6 col-md-6 col-xl-{{$deshboard_grid_main}} grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body bg_Orders">
                                <a class="stretched-link" href="{{ route('order-list') }}"></a>
                                    <div class="d-flex flex-wrap justify-content-between">
                                        <h4 class="card-title">{{__('admin.orders')}}</h4>
                                    </div>
                                    <div id="marketing"
                                        class="carousel slide dashboard-widget-carousel position-static pt-2"
                                        data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <div class="d-flex flex-wrap align-items-baseline">
                                                    <h2 class="me-3">{{ $orders }}</h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(auth()->user()->role_id != 3 && !auth()->user()->hasRole('finance')  && !auth()->user()->hasRole('jne'))
                            <div class="col-12 col-sm-6 col-md-6 col-xl-{{$deshboard_grid_main}} grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body bg_Users">
                                    <a class="stretched-link" href="{{ route('user-list') }}"></a>
                                        <div class="d-flex flex-wrap justify-content-between">
                                            <h4 class="card-title">{{__('admin.users')}}</h4>
                                        </div>
                                        <div id="marketing"
                                            class="carousel slide dashboard-widget-carousel position-static pt-2"
                                            data-bs-ride="carousel">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active">
                                                    <div class="d-flex flex-wrap align-items-baseline">
                                                        <h2 class="me-3">{{ $users }}</h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(auth()->user()->hasRole('finance'))
                            <div class="col-12 col-sm-6 col-md-6 col-xl-{{$deshboard_grid_main}} grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body bg_Users">
                                        <a class="stretched-link" href="{{ route('buyer-list') }}"></a>
                                        <div class="d-flex flex-wrap justify-content-between">
                                            <h4 class="card-title">{{__('admin.buyers')}}</h4>
                                        </div>
                                        <div id="marketing"
                                             class="carousel slide dashboard-widget-carousel position-static pt-2"
                                             data-bs-ride="carousel">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active">
                                                    <div class="d-flex flex-wrap align-items-baseline">
                                                        <h2 class="me-3">{{ $buyers }}</h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @php
                          $deshboard_grid = (auth()->user()->role_id == 1) ? 6 : 12;
                        @endphp
                        @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 3)
                            <div class="col-12 col-sm-{{ $deshboard_grid }} col-md-{{ $deshboard_grid }} col-xl-{{ $deshboard_grid }} grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body bg_xen">
                                        <a class="stretched-link"
                                           href="#"></a>
                                        <div class="d-flex flex-wrap justify-content-between">
                                            <h4 class="card-title">{{__('admin.xen_platform_balance')}}</h4>
                                        </div>
                                        <div id="marketing"
                                             class="carousel slide dashboard-widget-carousel position-static pt-2"
                                             data-bs-ride="carousel">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active">
                                                    <div class="d-flex flex-wrap align-items-baseline">
                                                        <h2 class="me-3" id="xen_balance"><img height="16px" src="{{ URL::asset('front-assets/images/icons/timer.gif') }}" title="Loading"></h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 3)
                            <div class="col-12 col-sm-{{ $deshboard_grid }} col-md-{{ $deshboard_grid }} col-xl-{{ $deshboard_grid }} grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body bg_xen">
                                        <a class="stretched-link"
                                           href="#"></a>
                                        <div class="d-flex flex-wrap justify-content-between">
                                            <h4 class="card-title">{{__('admin.koinwork_platform_balance')}}</h4>
                                        </div>
                                        <div id="marketing"
                                             class="carousel slide dashboard-widget-carousel position-static pt-2"
                                             data-bs-ride="carousel">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active">
                                                    <div class="d-flex flex-wrap align-items-baseline">
                                                        <h2 class="me-3" id="loan_balance"><img height="16px" src="{{ URL::asset('front-assets/images/icons/timer.gif') }}" title="Loading"></h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>


                </div>
                <div class="tab-pane fade" id="users" role="tabpanel" aria-labelledby="users-tab">
                    Tab Item
                </div>
                <div class="tab-pane fade" id="returns-1" role="tabpanel" aria-labelledby="returns-tab">
                    Tab Item
                </div>
                <div class="tab-pane fade" id="more" role="tabpanel" aria-labelledby="more-tab">
                    Tab Item
                </div>
            </div>
        </div>
    </div>
@stop

@push('bottom_scripts')
<script>
    @if(auth()->user()->role_id == 1)
        getXenBalance(0);
        getLoanBalance(0);
    @elseif(auth()->user()->role_id == 3)
        getLoanBalance(0);
        getXenBalance({{getSupplierByLoginId(auth()->user()->id)}});
    @endif
</script>
@endpush
