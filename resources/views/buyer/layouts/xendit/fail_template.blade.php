@extends('buyer.layouts.backend.backend_single_layout')

@push('bottom_head')
    <style>
        .paymenttable{ max-width: 500px;}
    </style>
@endpush

@section('content')
    <div class="row gx-4 mx-lg-0 justify-content-center">

        <!-- left section end -->
        <div class="col-lg-6 col-xl-9 py-2">
            <div class="header_top ">
                <h1 class="mb-0 text-center">{{__('order.link_expired')}}</h1>
            </div>
            <div class="card radius_1 border-0">
                <div class="card-body text-center">
                    <img src="{{ URL::asset('front-assets/images/link_expired.png') }}" alt="Link Expired">
                    <h5>{{__('order.oops_link_expired')}}</h5>
                    <p>{!! sprintf(__('order.sorry_please_contact'),'<strong class="text-primary">blitznet</strong>') !!}</p>

                    <a href="{{ route('credit.wallet.index') }}" class="btn btn-primary btn-sm" >{{__('order.back_to_home')}}</a>
                </div>
            </div>
        </div>
    </div>
@stop

@push('bottom_scripts')
@endpush
