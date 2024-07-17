
<div class="header_top d-flex align-items-center">
    <h1 class="mb-0">{{ __('dashboard.payment') }}</h1>
</div>
<!-- Payment accordion -->
<div class="accordion pd_section order_section" id="accordion_pd">
@foreach($suppliers as $supplier)
    <div class="accordion-item radius_1 mb-2 bp_total_payment" data-supplier_id="{{$supplier->supplier_id}}" data-amount="{{$supplier->orders->sum('payment_amount')}}">
        <h2 class="accordion-header d-flex" id="headingOne{{$supplier->supplier_id}}">
            <button onclick="accordion_click()" class="accordion-button justify-content-between bp-accordion-btn payment-accordion-btn{{$supplier->supplier_id}} collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#collapseOne{{$supplier->supplier_id}}" aria-expanded="true"
                    aria-controls="collapseOne{{$supplier->supplier_id}}" data-supplier_id="{{$supplier->supplier_id}}">
                <div class="flex-grow-1 ">{{$supplier->supplier_company_name}}</div>
                <div class="pe-3">{{ __('order.total_payment') }}: Rp <span id="bp_total_payment{{$supplier->supplier_id}}">{{number_format($supplier->orders->sum('payment_amount'),2)}}</span></div>
            </button>
        </h2>
        <div id="collapseOne{{$supplier->supplier_id}}" class="accordion-collapse collapse" aria-labelledby="headingOne{{$supplier->supplier_id}}" data-bs-parent="#accordion_pd">



            {{view('dashboard/payment/payment_order_list', ['supplier' => $supplier,'bulkSupplierPayments'=>$bulkSupplierPayments,'transactionsCharges'=>$transactionsCharges])}}



        </div>
    </div>
@endforeach
@if(count($suppliers)===0)
        <div class="col-md-12">
            <div class="alert alert-danger radius_1 text-center fs-6 fw-bold">{{ __('order.no_order_found_for_payment') }}</div>
        </div>
@endif
</div>
<!-- Payment accordion -->
