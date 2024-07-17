@php
    //'Order Returned','Order Cancelled', 'Payment Due on %s', 'Credit Approved', 'Credit Rejected'
    if($order->payment_type == 0){
        $hideStatusIds = [6, 7, 8, 9, 10,11,12];
    }
    else{
        $hideStatusIds = [6, 7, 8, 9, 10,2,12];
        if($order->full_quote_by == 3)
        {
            $hideStatusIds = [6, 7, 8, 9, 10,2,3,11];
        }
    }
@endphp
@if ($order)
    @foreach ($order->orderAllStatus->unique('order_status_id') as $orderStatus)
        @if (in_array($orderStatus->order_status_id, $hideStatusIds))
            @php
                $extraClass = '';
                $class = '';
            if ($orderStatus->order_track_id) {
                $class = 'active';
                if ($orderStatus->order_status_id == 6) {//'Order Returned'
                    $extraClass = ' finalOrderStatusBlock';
                    if ($order->order_status == 7)
                        $extraClass = '';
                }elseif ($orderStatus->order_status_id == 6){//"Order Cancelled"
                    $extraClass = ' finalOrderStatusBlock';
                }
            }else{
                continue;
            }
            @endphp
            <li class="active {{ $extraClass }}">
                <h6 class="mb-0">
                    @php
                        $downloadPoUrl = $order->order_status != 7 ? route('download-user-po-pdf', Crypt::encrypt($order->id)) : 'javascript:void(0)';
                        $downloadblitznetInvoiceUrl = $order->order_status != 7 ? route('download-user-blitznet-invoice-pdf', Crypt::encrypt($order->id)) : 'javascript:void(0)';
                    @endphp
                    <a href="javascript:void(0)">{{ __('order.' . trim($orderStatus->status_name)) }}</a>
                    @if($order->order_status >= 2 && $orderStatus->order_status_id == 2 && isset($order->po_number))
                        <a data-id="{{ $order->id }}" id="downloadPo{{ $order->id }}" href="{{ $downloadPoUrl }}" style="cursor: pointer;">
                            <img src="{{ URL::asset('front-assets/images/icons/file-order-letter.png') }}" title="{{ __('admin.download_po') }}">
                        </a>
                    @endif
                    @if($order->order_status >= 5 && $orderStatus->order_status_id == 5 && ($order->order_status != 9 && $order->order_status != 10) && isset($order->inv_number))
                    <a data-id="{{ $order->id }}" id="downloadPo{{ $order->id }}" href="{{ $downloadblitznetInvoiceUrl }}" style="cursor: pointer;">
                        <img src="{{ URL::asset('front-assets/images/icons/file-invoice.png') }}" title="{{ __('admin.download_blitznet_invoice') }}">
                    </a>
                    @endif
                    @if($order->order_status >= 5 && $orderStatus->order_status_id == 5 && !empty($order->tax_receipt) && $order->order_status != 9 && $order->order_status != 10)
                        <a href="javascript:void(0);" onclick="downloadimg('{{ $order->id }}', 'tax_receipt', '{{ Str::substr($order->tax_receipt, stripos($order->tax_receipt, 'tax_receipt_') + 12) }}')" style="cursor: pointer;">
                            <img src="{{ URL::asset('front-assets/images/icons/file-tax.png') }}" title="{{ __('admin.download_tax_receipt') }}">
                        </a>
                    @endif
                    @if($order->order_status >= 5 && $orderStatus->order_status_id == 5 && !empty($order->invoice) && $order->order_status != 9 && $order->order_status != 10)
                        <a href="javascript:void(0);" onclick="downloadimg('{{ $order->id }}', 'invoice', '{{ Str::substr($order->invoice, stripos($order->invoice, 'invoice_') + 8) }}')" style="cursor: pointer;">
                            <img src="{{ URL::asset('front-assets/images/icons/file-invoice.png') }}" title="{{ __('admin.download_invoice') }}">
                        </a>
                    @endif
                </h6>
                <p>
                    &nbsp;
                    <small>
                        {{ $orderStatus->created_at ? date('d-m-Y H:i:s', strtotime($orderStatus->created_at)) : ' ' }}
                    </small>
                </p>
            </li>
        @else
            @php
            $extraClass = '';
            $qcclass = '';
            $flexClass = '';
                if ($order->order_status == 7){
                    $show_listing = $orderTracks->toArray();

                    if (in_array($orderStatus->order_status_id, $show_listing)){
                        $class = 'active';
                    } else {
                        continue;
                    }
                } else{
                    if (($orderStatus->order_status_id == 5) && ($order->order_status == 6||$order->order_status == 7)){//'Delivered' && ('Order Returned' || 'Order Cancelled')
                     continue;
                }
                $paymentDone = 1;
                $qcDisabled = 1;
                if ($orderStatus->order_status_id == 3 || $orderStatus->order_status_id == 9) {//'Payment Done' or 'Under QC'
                    $flexClass = 'd-flex';
                }

                if ($orderStatus->order_track_id) {
                    $class = 'active';
                } else {
                    if ($orderStatus->order_status_id == 3) {//'Payment Done'
                        $class = 'paymentDoneBlock';
                        $paymentDone = 0;
                    }elseif ($orderStatus->order_status_id == 19 && $order->is_credit==0){
                        continue;
                    } else {
                        $class = '';
                    }
                }

                if ($orderStatus->order_status_id == 5) {//'Order Completed'
                    $extraClass = ' finalOrderStatusBlock';
                }
                }

            @endphp
            <li class="{{ $class . ' ' . $extraClass.' '. $qcclass}}">
                <h6 class="mb-0 {{ $flexClass }}">
                    @if($orderStatus->status_name == 'Order Received')
                        <a href="javascript:void(0)">{{ __('order.orderplaced') }}</a>
                    @else
                    
                        @if(in_array($orderItemCategoryId,\App\Models\Category::SERVICES_CATEGORY_IDS) && $order->order_status== 1 && ($orderStatus->order_status_id == 2 || $orderStatus->order_status_id == 11 || $orderStatus->order_status_id == 12) && ($orderlogisticProvided->logistic_check==1 && $orderlogisticProvided->logistic_provided ==0))
                                <a href="javascript:void(0)" style="cursor: pointer" class="orderStatusChange" id="orderStatusChange{{$order->id}}" onclick="orderStatusChange($(this))" data-order-id="{{$order->id}}" data-status-id="{{$orderStatus->order_status_id}}">{{ __('order.' . trim($orderStatus->status_name)) }}</a>
                                @if($orderStatus->order_status_id == 2)
                                    <img height="16px" class="d-none" src="{{URL::asset('front-assets/images/icons/timer.gif')}}" id="orderStatusChange_loading{{$order->id}}" alt="{{__('admin.loading')}}..." data-toggle="tooltip" title="{{__('admin.loading')}}..." srcset>
                                @endif
                            @else
                                <a href="javascript:void(0)">{{ __('order.' . trim($orderStatus->status_name)) }}</a>
                            @endif
                    @endif
                    @php
                        $downloadPoUrl = $order->order_status != 7 ? route('download-user-po-pdf', Crypt::encrypt($order->id)) : 'javascript:void(0)';
                        $downloadblitznetInvoiceUrl = $order->order_status != 7 ? route('download-user-blitznet-invoice-pdf', Crypt::encrypt($order->id)) : 'javascript:void(0)';
                    @endphp
                    @if($order->payment_type != 0 && $order->order_status >= 1 && ($orderStatus->order_status_id == 11 || $orderStatus->order_status_id == 12))
                        @if(!$order->upload_order_doc)
                        <a href="javascript:void(0)" class="js-openmodel" data-id="{{$order->id}}">
                            <img src="{{ URL::asset('front-assets/images/icons/upload.png') }}" width="14px" height="14px" class="ms-1" alt=""> 
                        </a>
                        <img height="16px" class="d-none" src="{{URL::asset('front-assets/images/icons/timer.gif')}}" id="orderStatusChange_loading{{$order->id}}" alt="{{__('admin.loading')}}..." data-toggle="tooltip" title="{{__('admin.loading')}}..." srcset>
                        @endif
                         @if($order->order_status >= 2 && isset($order->po_number))
                            <a data-id="{{ $order->id }}" id="downloadPo{{ $order->id }}" href="{{ $downloadPoUrl }}" style="cursor: pointer;">
                                <img src="{{ URL::asset('front-assets/images/icons/file-order-letter.png') }}" title="{{ __('admin.download_po') }}">
                            </a>
                        @endif
                    @endif
                    @if($order->payment_type != 0  && ($orderStatus->order_status_id == 11 || $orderStatus->order_status_id == 12) && $order->upload_order_doc)
                        <a class="" href="{{ url($order->upload_order_doc?? '') }}" title="{{$order->upload_order_doc_filename}}" download><img src="{{URL::asset('front-assets/images/icons/icon_download.png')}}" width="14px"></a>
                        @if($order->closeflag <= 9)
                        <span class="removeFile"  data-id="{{$order->id}}" file-path="{{$order->upload_order_doc}}" data-name="upload_order_doc" data-type="lo_doc">
                            <a href="javascript:void(0);" title="{{ __('admin.docfile.remove') }}"> <img src="{{URL::asset('assets/icons/times-circle copy.png')}}" alt="CLose button" class="ms-2">
                            </a>
                        </span>
                        @endif
                    @endif
                    @if($order->order_status >= 2 && $orderStatus->order_status_id == 2 && isset($order->po_number))
                        <a data-id="{{ $order->id }}" id="downloadPo{{ $order->id }}" href="{{ $downloadPoUrl }}" style="cursor: pointer;">
                            <img src="{{ URL::asset('front-assets/images/icons/file-order-letter.png') }}" title="{{ __('admin.download_po') }}">
                        </a>
                    @endif
                    @if($order->order_status >= 5 && $orderStatus->order_status_id == 5 && ($order->order_status != 9 && $order->order_status != 10) && isset($order->inv_number))
                         <a data-id="{{ $order->id }}" id="downloadPo{{ $order->id }}" href="{{ $downloadblitznetInvoiceUrl }}" style="cursor: pointer;">
                            <img src="{{ URL::asset('front-assets/images/icons/file-invoice.png') }}" title="{{ __('admin.download_blitznet_invoice') }}">
                         </a>
                    @endif
                    @if($order->order_status >= 5 && $orderStatus->order_status_id == 5 && !empty($order->tax_receipt) && $order->order_status != 9 && $order->order_status != 10 )
                        <a href="javascript:void(0);" onclick="downloadimg('{{ $order->id }}', 'tax_receipt', '{{ Str::substr($order->tax_receipt, stripos($order->tax_receipt, 'tax_receipt_') + 12) }}')" style="cursor: pointer;">
                            <img src="{{ URL::asset('front-assets/images/icons/file-tax.png') }}" title="{{ __('admin.download_tax_receipt') }}">
                        </a>
                    @endif
                    @if($order->order_status >= 5 && $orderStatus->order_status_id == 5 && !empty($order->invoice) && $order->order_status != 9 && $order->order_status != 10)
                        <a href="javascript:void(0);" onclick="downloadimg('{{ $order->id }}', 'invoice', '{{ Str::substr($order->invoice, stripos($order->invoice, 'invoice_') + 8) }}')" style="cursor: pointer;">
                            <img src="{{ URL::asset('front-assets/images/icons/file-invoice.png') }}" title="{{ __('admin.download_invoice') }}">
                        </a>
                    @endif
                    @if ($orderStatus->order_status_id == 3)
                        <div class="px-2">
                            @if(($order->order_status==2 || $order->order_status==11 ||$order->order_status==10) && $order->invoice_url)
                                @php
                                    $expiryDateTimestamp = strtotime(utcToLocalTime($order->expiry_date));
                                    $currentDateTimestamp = strtotime(date('Y-m-d H:i:s'));
                                    $remainingTimeSeconds = $expiryDateTimestamp-$currentDateTimestamp;
                                    $isUnder48Hours = false;
                                    if ($remainingTimeSeconds>0 && $remainingTimeSeconds<=172800){//172800 sec = 48 hours
                                        $isUnder48Hours = true;
                                    }
                                @endphp
                                @if(!empty($order->payNowPermission) && $order->payNowPermission == 1)
                                @if(!empty($order->expiry_date) && $order->invoice_status == 'PENDING' && $expiryDateTimestamp>$currentDateTimestamp)
                                    <a id="pay{{$order->id}}" class="btn btn-warning align-middle btn-sm shadow ms-auto px-1 py-0 text-dark position-relative" style="top: -3px;cursor: pointer;" href="{{$order->invoice_url}}" target="_blank">
                                        <small style="font-size: 11px;">{{ __('order.pay_button') }}</small>
                                    </a>
                                    @if($isUnder48Hours)
                                        <small class="text-danger" style="font-size: 12px;line-height: 2;display: inline-block;">
                                            <img height="16px" src="{{ URL::asset('front-assets/images/icons/timer.gif') }}" title="Time Remaining">
                                            <span id="remainingTime{{$order->id}}" class="remainingTime" data-id="{{$order->id}}" data-remaining-seconds="{{$remainingTimeSeconds}}"></span>
                                        </small>
                                    @endif
                                @else
                                    <a class="btn btn-primary align-middle btn-sm generate-pay-link shadow ms-auto px-1 py-0 text-white position-relative" style="top: -3px;cursor: pointer;" href="javascript:void(0)" data-id="{{$order->id}}">
                                        <small style="font-size: 11px;">{{ __('order.pay_generate_button') }}</small>
                                    </a>
                                @endif
                                @endif
                            @endif
                        </div>
                    @endif
                </h6>
                <p>
                    &nbsp;
                    <small>
                        {{ $orderStatus->created_at && $class == 'active' ? date('d-m-Y H:i:s', strtotime($orderStatus->created_at)) : ' ' }}
                    </small>
                </p>
            </li>
        @endif
    @endforeach
@endif
