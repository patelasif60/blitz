@extends('buyer.layouts.backend.backend_single_layout')

@push('bottom_head')
    <style>
        .paymenttable{ max-width: 500px;}
        p, table{ font-family: 'europaNuova_re';}
    </style>
@endpush

@section('content')
    <div class="row gx-4 mx-lg-0 justify-content-center">

        <!-- left section end -->
        <div class="col-lg-6 col-xl-9 py-2">
            <div class="header_top ">
                <h1 class="mb-0 text-center">{{__('order.payment_success')}}</h1>
            </div>
            <div class="card radius_1 border-0">
                <div class="card-body text-center">
                    <img src="{{ URL::asset('front-assets/images/payment_done.png') }}" alt="Paymet Success">
                    <h5>{{__('order.payment_was_success')}}</h5>
                    <p>{{__('order.an_automated_recept_will_be')}}<br>
                        <span class="text-primary">{{ (isset($attribute['payer_email']) && !empty($attribute['payer_email'])) ? $attribute['payer_email'] : '-'}}</span></p>

                    <table class="mx-auto table table-bordered paymenttable mb-3">
                        <tr class="bg-light">
                            <th>{{__('admin.amount_paid')}}</th>
                            <th>{{__('admin.date')}}</th>
                            <th>{{__('admin.payment_channel')}}</th>
                        </tr>
                        <tr>
                            <td>Rp {{ (isset($attribute['paid_amount']) && !empty($attribute['paid_amount'])) ? number_format((float)$attribute['paid_amount'],2) : 0.00 }}</td>
                            <td>{{ (isset($attribute['payment_date']) && !empty($attribute['payment_date'])) ? \Carbon\Carbon::parse($attribute['payment_date'])->format('d-m-Y') : '-' }}</td>
                            <td>{{ (isset($attribute['payment_channel']) && !empty($attribute['payment_channel'])) ? $attribute['payment_channel'] : '-'}}</td>
                        </tr>
                    </table>
                    <a href="{{route('credit.wallet.index')}}" class="btn btn-primary btn-sm" >{{__('admin.back_to_home')}}</a>
                </div>
            </div>
        </div>
    </div>
@stop

@push('bottom_scripts')
@endpush
