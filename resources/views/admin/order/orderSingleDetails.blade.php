<div class="modal-header py-3">
    <h5 class="modal-title d-flex align-items-center" id="staticBackdropLabel"><img height="24px" class="pe-2" src="{{URL::asset('assets/icons/order_detail_title.png')}}" alt="Order Details"> {{ $order->order_number }}</h5>
    @if(isset($group_id) && !empty($group_id))
    <span>
        <button type="button" class="btn btn-info btn-sm ms-2 text-white" style="border-radius: 20px"> BGRP - {{ $group_id }}</button>
    </span>
    @endif

    <span class="btn btn-warning rounded-pill printicon ms-auto"><a target="_blank" href="{{ route('dashboard-print-order',Crypt::encrypt($order->id)) }}" data-toggle="tooltip" ata-placement="top" title="{{ __('admin.print') }}"><i class="fa fa-print"></i></a></span>
    <button type="button" class="btn-close ms-0" data-bs-dismiss="modal" aria-label="Close">
        <img src="{{URL::asset('assets/icons/times.png')}}" alt="Close">
    </button>
</div>
<div class="modal-body p-3">
    @php
        $common_info =  get_common_order_info($order);
    @endphp
    <div class="row">
        <div class="col-md-7">
            <div class="row align-items-stretch">
                <div class="col-md-12 pb-2">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h5><img src="{{URL::asset('assets/icons/shopping-cart.png')}}" alt="Order Details" class="pe-2"> {{ __('admin.order_details') }}</h5>
                        </div>
                        <div class="card-body p-3">
                            <div class="row rfqform_view bg-white">
                                <div class="col-md-6 pb-2">
                                    <label>{{ __('admin.rfq_number') }}:</label>
                                    <div>{{ $common_info['rfq']->reference_number ?? '' }}</div>
                                </div>
                                <div class="col-md-6 pb-2">
                                    <label>{{ __('admin.quote_number') }}:</label>
                                    <div> {{ $common_info['quote']->quote_number }}</div>
                                </div>
                                <div class="col-md-6 pb-2">
                                    <label>{{ __('admin.order_number') }}:</label>
                                    <div>
                                        {{ $order->order_number }}
                                    </div>
                                </div>
                                <div class="col-md-6 pb-2">
                                    <label>{{ __('admin.date') }}:</label>
                                    <div class="text-dark"> {{ $order->created_at }}</div>
                                </div>
                                @php
                                    $status_name = __('order.' . trim($common_info['orderStatus']->name));
                                    if ($order->order_status == 8) {//'Payment Due DD/MM/YYYY'
                                        $status_name = sprintf($status_name,changeDateFormat($order->payment_due_date,'d/m/Y'));
                                    }
                                @endphp
                                <div class="col-md-6 pb-2">
                                    <label>{{ __('admin.status_of_order') }}:</label>
                                    <div>{{ $status_name }}</div>
                                </div>
                                @if(isset($order->customer_reference_id) && !empty($order->customer_reference_id))
                                    <div class="col-md-6 pb-2">
                                        <label class="form-label">{{ __('rfqs.customer_ref_id') }}: </label>
                                        <div class="text-dark"> {{ isset($order) ? $order->customer_reference_id : '' }}</div>
                                    </div>
                                @endif
                                {{--@if(isset($awb->airwaybill_number) && !empty($awb->airwaybill_number))
                                    <div class="col-md-6">
                                        <label>{{ __('admin.airwaybill_number') }}:</label>
                                        <div class="text-dark"> {{ isset($awb) ? $awb->airwaybill_number : '-' }}</div>
                                    </div>
                                @endif--}}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buyer Detail -->
                <div class="col-md-12 pb-2">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h5><img height="20px" src="{{URL::asset('assets/icons/person-dolly-1.png')}}" alt="Buyer Details" class="pe-2"> {{ __('admin.buyer_detail') }}</h5>
                        </div>
                        <div class="card-body p-3 pb-1">
                            <div class="row rfqform_view bg-white">
                                <div class="col-md-6 pb-2">
                                    <label>{{ __('admin.company_name') }}:</label>
                                    <div class="text-dark">{{ isset($common_info['buyer_company']->name) ? $common_info['buyer_company']->name : '' }}</div>
                                </div>
                                <div class="col-md-6 pb-2">
                                    <label>{{ __('admin.customer_name') }}:</label>
                                      @php
                                        $name = '';
                                        if(isset($common_info['buyer']->firstname) && isset($common_info['buyer']->lastname))
                                        {
                                            $name = $common_info['buyer']->firstname.' '.$common_info['buyer']->lastname;
                                        }
                                        else if(isset($common_info['buyer']->firstname)) {
                                            $name = $common_info['buyer']->firstname;
                                        }
                                        else if(isset($buyer->lastname))
                                        {
                                            $name = $common_info['buyer']->lastname;
                                        }
                                    @endphp
                                    <div class="text-dark"> {{ $name }}</div>
                                </div>
                                @if(auth()->user()->role_id != 3)
                                    <div class="col-md-6 pb-2">
                                        <label>{{ __('admin.customer_email') }}:</label>
                                        <div class="text-dark">{{ $common_info['buyer']->email }}</div>
                                    </div>
                                    <div class="col-md-6 pb-2">
                                        <label>{{ __('admin.customer_phone') }}:</label>
                                        <div class="text-dark"> {{ countryCodeFormat($common_info['buyer']->phone_code,$common_info['buyer']->mobile) }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Supplier Detail -->


            </div>
        </div>
        <div class="col-md-5 ps-md-0 pb-2">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center">
                    <h5 class="dark_blue mb-0 d-flex align-items-center">
                        <img src="{{URL::asset('assets/icons/truck-moving.png')}}" alt="Track Order" class="pe-2"> {{ __('admin.track_order') }}
                    </h5>
                </div>
                <div id="orderStatusDetails">
                {!! $Order_status !!}
                </div>
            </div>
        </div>
        <div class="col-md-12 pb-2">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5><img height="20px" src="{{URL::asset('assets/icons/people-carry-1.png')}}" alt="Supplier Details" class="pe-2"> {{ __('admin.supplier_detail') }}</h5>
                </div>
                <div class="card-body p-3 pb-1">
                    <div class="row rfqform_view bg-white">
                        <div class="col-md-3 pb-2">
                            <label>{{ __('admin.supplier_company') }}:</label>
                            <div class="text-dark"> {{ $common_info['supplier']->name }}</div>
                        </div>
                        <div class="col-md-3 pb-2">
                            <label>{{ __('admin.supplier_name') }}:</label>
                            <div class="text-dark"> {{ $common_info['supplier']->contact_person_name }}</div>
                        </div>
                        <div class="col-md-3 pb-2">
                            <label>{{ __('admin.supplier_email') }}:</label>
                            <div class="text-dark"> {{ $common_info['supplier']->contact_person_email }}</div>
                        </div>
                        <div class="col-md-3 pb-2">
                            <label>{{ __('admin.supplier_phone') }}:</label>
                            <div class="text-dark"> {{ countryCodeFormat($common_info['supplier']->cp_phone_code,$common_info['supplier']->contact_person_phone) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 pb-2">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5><img height="20px" src="{{URL::asset('assets/icons/actions.png')}}" alt="Supplier Details" class="pe-2"> {{ __('admin.attachment') }}</h5>
                </div>
                <div class="card-body p-3 pb-1">
                    <div class="row rfqform_view bg-white">
                        <div class="col-md-4 pb-2">
                            <label class="d-none"></label>
                            <div>{{--<a href="" class="text-decoration-none"><span style="width: 25px;"><img src="{{URL::asset('assets/icons/file-pdf.png')}}" alt="Order Details" class="pe-2"></span> Download PO</a>--}}
                                @php
                                    $downloadPoUrl = $order->order_status != 7 ? route('download-po-pdf', Crypt::encrypt($order->id)) : 'javascript:void(0)';
                                @endphp
                                @if (empty($common_info['orderPo']))
                                    <a href="{{ route('order-edit', ['id' => Crypt::encrypt($order->id)]) }}" class="text-decoration-none d-flex align-items-center" data-id="{{ $order->id }}" title="{{ __('admin.generate_po') }}" id="generatePo{{ $order->id }}"><span style="width: 25px;"><img src="{{URL::asset('assets/icons/file-pdf.png')}}" alt="Order Details" class="pe-2"></span>{{ __('admin.generate_po') }}</a>
                                @else
                                    <a class="text-decoration-none downloadPo d-flex align-items-center" title="{{ __('admin.download_po') }}" data-id="{{ $order->id }}" id="downloadPo{{ $order->id }}" href="{{ $downloadPoUrl }}"><span style="width: 25px;"><img src="{{URL::asset('assets/icons/file-pdf.png')}}" alt="Order Details" class="pe-2"></span>{{ __('admin.download_po') }} <svg id="Layer_1" width="10px" class="ms-1" fill="#0d6efd" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 383.26 408.81"><path d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z" transform="translate(-64.37 -51.59)"/><path d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z" transform="translate(-64.37 -51.59)"/></svg></a>
                                @endif
                            </div>
                        </div>
                        @if (!empty($common_info['orderPo']) && auth()->user()->role_id != 3)
                        <div class="col-md-4 pb-2">
                            <label class="d-none"></label>
                            <div>{{--<a href="" class="text-decoration-none"><span style="width: 25px;"><img src="{{URL::asset('assets/icons/file-pdf.png')}}" alt="Order Details" class="pe-2"></span> Download PO</a>--}}
                                @php
                                    $downloadBuyerPoUrl = $order->order_status != 7 ? route('download-buyer-po-pdf', Crypt::encrypt($order->id)) : 'javascript:void(0)';
                                @endphp
                                <a class="text-decoration-none downloadPo d-flex align-items-center" title="{{ __('admin.download_buyer_po') }}" data-id="{{ $order->id }}" id="downloadPo{{ $order->id }}" href="{{ $downloadBuyerPoUrl }}"><span style="width: 25px;"><img src="{{URL::asset('assets/icons/file-pdf.png')}}" alt="Order Details" class="pe-2"></span>{{ __('admin.download_buyer_po') }} <svg id="Layer_1" width="10px" class="ms-1" fill="#0d6efd" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 383.26 408.81"><path d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z" transform="translate(-64.37 -51.59)"/><path d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z" transform="translate(-64.37 -51.59)"/></svg></a>
                            </div>
                        </div>
                        @endif
                        @if(!empty($order->tax_receipt))
                            <div class="col-md-4 pb-2">
                                <label class="d-none"></label>
                                <div><a href="javascript:void(0);" title="{{ __('admin.download_tax_receipt') }}" onclick="downloadimg('{{ $order->id }}', 'tax_receipt', '{{ Str::substr($order->tax_receipt, stripos($order->tax_receipt, 'tax_receipt_') + 12) }}')" class="text-decoration-none"><img src="{{URL::asset('assets/icons/file-tax.png')}}" alt="Order Details" class="pe-2">{{ __('admin.tax_receipt') }} <svg id="Layer_1" width="10px" fill="#0d6efd" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 383.26 408.81"><path d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z" transform="translate(-64.37 -51.59)"/><path d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z" transform="translate(-64.37 -51.59)"/></svg></a></div>
                            </div>
                        @endif
                        @if(!empty($order->order_latter))
                            <div class="col-md-4 pb-2">
                                <label class="d-none"></label>
                                <div><a href="javascript:void(0);" title="Download Order Latter" onclick="downloadimg('{{ $order->id }}', 'order_latter', '{{ Str::substr($order->order_latter, stripos($order->order_latter, "order_latter_") + 12) }}')" class="text-decoration-none"><img src="{{URL::asset('assets/icons/file-order-letter.png')}}" alt="Order Details" class="pe-2">{{ __('admin.order_letter') }} <svg id="Layer_1" width="10px" fill="#0d6efd" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 383.26 408.81"><path d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z" transform="translate(-64.37 -51.59)"/><path d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z" transform="translate(-64.37 -51.59)"/></svg></a></div>
                            </div>
                        @endif
                        @if(!empty($order->invoice))
                            <div class="col-md-4 pb-2">
                                <label class="d-none"></label>
                                <div><a href="javascript:void(0);" title="{{ __('admin.download_invoice') }}" onclick="downloadimg('{{ $order->id }}', 'invoice', '{{ Str::substr($order->invoice, stripos($order->invoice, "invoice_") + 8) }}')" class="text-decoration-none"><img src="{{URL::asset('assets/icons/file-invoice.png')}}" alt="Order Details" class="pe-2">{{ __('admin.invoice') }} <svg id="Layer_1" width="10px" fill="#0d6efd" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 383.26 408.81"><path d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z" transform="translate(-64.37 -51.59)"/><path d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z" transform="translate(-64.37 -51.59)"/></svg></a></div>
                            </div>
                        @endif
                        @if(!empty($orderPo->inv_number))
                            <div class="col-md-4 pb-2">
                                <label class="d-none"></label>
                                @php
                                    $downloadblitznetInvoiceUrl = $order->order_status != 7 ? route('download-blitznet-invoice-pdf', Crypt::encrypt($order->id)) : 'javascript:void(0)';
                                @endphp
                                <div><a class="text-decoration-none downloadPo d-flex align-items-center" title="{{ __('admin.download_blitznet_invoice') }}" data-id="{{ $order->id }}" id="downloadPo{{ $order->id }}" href="{{ $downloadblitznetInvoiceUrl }}"><span style="width: 25px;"><img src="{{URL::asset('assets/icons/file-invoice.png')}}" alt="Order Details" class="pe-2"></span>{{ __('admin.download_blitznet_invoice') }} <svg id="Layer_1" width="10px" class="ms-1" fill="#0d6efd" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 383.26 408.81"><path d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z" transform="translate(-64.37 -51.59)"/><path d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z" transform="translate(-64.37 -51.59)"/></svg></a></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex align-items-center w-100">
                    <h5 class="d-flex align-items-center w-100"><img src="{{URL::asset('assets/icons/credit-card.png')}}" alt="Payment Detail" class="pe-2">  {{ __('admin.payment_detail') }}
                        @php
                            $isCreditStatus = __('admin.advance');
                            if($order->is_credit == ORDER_IS_CREDIT['CREDIT']){
                                $isCreditStatus = __('admin.credit').' - '.$order->orderCreditDay->approved_days;
                            }elseif ($order->is_credit == ORDER_IS_CREDIT['LOAN_PROVIDER_CREDIT']){
                                $isCreditStatus = __('admin.loan_provider_credit');
                            }
                        @endphp
                        <div class="ms-auto"><small>{{ __('admin.payment_terms') }}: 
                            @if($order->payment_type==1)
                                <span class="badge rounded-pill bg-danger">{{ __('order.credit') }} -{{$order->credit_days}}</span>
                            @elseif($order->payment_type==0)
                                <span class="badge rounded-pill bg-success">{{ __('order.advance') }}</span>
                            @elseif($order->payment_type==3)
                                <span class="badge rounded-pill bg-danger">{{ __('admin.lc')  }}</span>
                            @elseif($order->payment_type==4)
                                <span class="badge rounded-pill bg-danger">{{__('admin.skbdn')  }}</span>
                            @else
                                <span class="badge rounded-pill bg-danger">{{$isCreditStatus}}</span>
                            @endif
                        </small>
                        </div>
                    </h5>
                </div>
                <div class="card-body p-2">
                    <div class="table-responsive">
                        @php
                            $finalAmount = 0;
                            $discount = 0;
                            $flag=0;
                            $flagLogistic = 0;
                            $flagSuppler = 0;
                        @endphp
                        <table class="table text-dark table-striped">
                            <tbody>
                            <tr class="bg-light">
                                <th>{{ __('admin.description') }}</th>
                                <th>{{ __('admin.status') }}</th>
                                <th>{{ __('admin.order_letter') }}</th>
                                <th>{{ __('admin.price') }}</th>
                                <th>{{ __('admin.qty') }}</th>
                                <th class="text-center">{{ __('admin.amount') }}</th>
                                <!-- align="right" -->
                            </tr>
                            @foreach($quote_items as $key => $value)
                                @php
                                    $statusAndOrder = getOrderStatusNameAndOrderLetter($order->id, $value->rfq_product_id);
                                    $finalAmount += $value->product_price_per_unit * $value->product_quantity ;
                                @endphp
                            <tr>
                                <td>{{ get_product_name_by_id($value->rfq_product_id, 1) }}</td>
                                <td class="text-nowrap">{{ $statusAndOrder['order_status_name'] }}</td>
                                <td class="text-center">
                                    @if(!empty($statusAndOrder['order_letter']))
                                        <a href="javascript:void(0);" onclick="downloadOrderLatter('{{ $statusAndOrder['id'] }}', '{{ Str::substr($statusAndOrder['order_letter'], stripos($statusAndOrder['order_letter'], "order_latter_") + 13) }}')" title="{{ Str::substr($statusAndOrder['order_letter'], stripos($statusAndOrder['order_letter'], 'order_latter_') + 13) }}">
                                            <img src="{{URL::asset('assets/icons/file-pdf.png')}}" alt="Order Details">
                                        </a>
                                    @endif
                                </td>
                                <td class="text-nowrap">{{ 'Rp ' . number_format($value->product_price_per_unit, 2) }} per {{ $value->name }}</td>
                                <td class="text-nowrap">{{ $value->product_quantity??'' }} {{ $value->name??'' }}</td>
                                <td align="right" class="text-nowrap">{{ 'Rp ' . number_format($value->product_amount, 2) }}</td>
                            </tr>
                            @endforeach

                            @foreach ($amount_details as $charges)
                                @if($charges->charge_amount>0)
                                <tr>
                                    @if ($charges->type == 0)
                                        @php
                                            if($charges->charge_name == 'Discount')
                                            { $flag = 1; }
                                            $flagSuppler = 1;
                                        @endphp
                                        <td colspan="5" style="border-bottom: 1px solid #e6e4e4; font-size: 12px;">@if($charges->custom_charge_name) {{$charges->custom_charge_name.' -'}} @endif{{ $charges->charge_name . ' ' . $charges->charge_value }} %
                                            @if($charges->charge_type != 2)
                                            <small class="fw-bold text-blue">({{ __('admin.supplier_other_charges') }})</small>
                                            @endif
                                        </td>
                                    @else
                                        @if($charges->charge_type == 2)
                                            <td colspan="5">{{ $charges->charge_name }} {{--<small class="fw-bold text-blue">({{ __('admin.platform_charges') }})</small>--}}</td>
                                        @elseif($charges->charge_type == 1)
                                             @php
                                                $flagLogistic = 1;
                                            @endphp
                                            <td colspan="5">@if($charges->custom_charge_name) {{$charges->custom_charge_name.' -'}} @endif {{ $charges->charge_name }} <small class="fw-bold text-blue">({{ __('admin.logistic_charges') }})</small></td>
                                        @else
                                        @php
                                            $flagSuppler = 1;
                                        @endphp
                                            <td colspan="5">@if($charges->custom_charge_name) {{$charges->custom_charge_name.' -'}} @endif {{ $charges->charge_name }} <small class="fw-bold text-blue">({{ __('admin.supplier_other_charges') }})</small></td>
                                        @endif
                                    @endif
                                    <td style="text-align: right;border-bottom: 1px solid #e6e4e4; font-size: 12px; white-space: nowrap;">
                                        {{ $charges->addition_substraction == 0 ? '- ' : '+ ' }}{{ 'Rp ' . number_format($charges->charge_amount, 2) }}
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                            @php
                                /*if (auth()->user()->role_id == 3){
                                    $totalAmount = $finalAmount - $discount;
                                    $taxamount = round(($totalAmount * $order->tax) / 100);
                                    $payamount = round($totalAmount + $taxamount);
                                }
                                $tex = (auth()->user()->role_id == 3) ? $taxamount : $order->tax_value;
                                $lastamount = (auth()->user()->role_id == 3) ? $payamount : $order->final_amount;*/
                                $total =  (auth()->user()->role_id == 3) ?  $common_info['quote']->supplier_final_amount: $common_info['quote']->final_amount
                            @endphp
                            @if($common_info['quote']->tax > 0)
                            <tr>
                                <td colspan="5">{{ __('admin.tax') }} {{ $common_info['quote']->tax .'%'}}</td>
                                <td align="right" class="text-nowrap"> + Rp
                                    @if(auth()->user()->role_id == 3)
                                        {{ number_format($common_info['quote']->supplier_tex_value, 2) }}
                                    @else
                                        {{number_format($common_info['quote']->tax_value, 2)}}
                                    @endif
                                </td>
                            </tr>
                            @endif
                            @php
                                $billedAmount = (auth()->user()->role_id == 3) ? $common_info['quote']->supplier_final_amount : $common_info['quote']->final_amount;
                                $bulkOrderDiscount = 0;
                                if (auth()->user()->role_id != 3){
                                   $bulkOrderDiscount = getBulkOrderDiscount($order->id);
                                }
                            @endphp
                            @if($bulkOrderDiscount>0)
                                @php
                                    $billedAmount = $billedAmount-$bulkOrderDiscount;
                                @endphp
                                <tr>
                                    <td colspan="5">
                                        {{ __('admin.bulk_payment_discount') }}
                                    </td>
                                    <td align="right" class="text-nowrap" >
                                        {{ '- Rp ' . number_format($bulkOrderDiscount, 2) }}
                                    </td>
                                </tr>
                            @endif
                            <tr class="bg-secondary text-white">
                                <td colspan="5" class="text-white fw-bold">{{ __('admin.amount_to_pay') }}</td>
                                <td align="right" class="text-white fw-bold text-nowrap">{{ 'Rp ' . number_format($billedAmount, 2) }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer px-3">
    <a class="btn btn-cancel" data-bs-dismiss="modal">{{ __('admin.cancel') }}</a>
</div>
