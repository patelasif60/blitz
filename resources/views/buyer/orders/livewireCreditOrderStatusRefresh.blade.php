@php
    //'Order Returned','Order Cancelled'
    $hideStatusIds = [6, 7 , 11,12];

@endphp
@if ($order)
    @foreach ($order->orderAllStatus as $orderStatus)
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
                }elseif ($orderStatus->order_status_id == 7){//"Order Cancelled"
                    $extraClass = ' finalOrderStatusBlock';
                }
            }else{
                continue;
            }
            @endphp
            <li class="3 active {{ $extraClass }}">
                <h6 class="mb-0">
                    @php
                        $downloadPoUrl = $order->order_status != 7 ? route('download-user-po-pdf', Crypt::encrypt($order->id)) : 'javascript:void(0)';
                        $downloadblitznetInvoiceUrl = $order->order_status != 7 ? route('download-user-blitznet-invoice-pdf', Crypt::encrypt($order->id)) : 'javascript:void(0)';
                    @endphp
                    <a href="javascript:void(0)">{{ __('order.' . trim($orderStatus->status_name)) }}</a>
                    @if($order->order_status >= 2 && $orderStatus->order_status_id == 2 && isset($order->po_number))
                        <a data-id="{{ $order->id }}" id="downloadPo{{ $order->id }}" href="{{ $downloadPoUrl }}" style="cursor: pointer;" ><img src="{{ URL::asset('front-assets/images/icons/file-order-letter.png') }}" title="{{ __('admin.download_po') }}"> </a>
                    @endif
                    @if($order->order_status >= 5 && $orderStatus->order_status_id == 5 && ($order->order_status != 9 && $order->order_status != 10) && isset($order->inv_number)))
                        <a data-id="{{ $order->id }}" id="downloadPo{{ $order->id }}" href="{{ $downloadblitznetInvoiceUrl }}" style="cursor: pointer;" ><img src="{{ URL::asset('front-assets/images/icons/file-invoice.png') }}" title="{{ __('admin.download_blitznet_invoice') }}"> </a>
                    @endif
                    @if($order->order_status >= 5 && $order->order_status != 9 && $order->order_status != 10 &&  $orderStatus->order_status_id == 5 && !empty($order->tax_receipt) )
                        <a href="javascript:void(0);" onclick="downloadimg('{{ $order->id }}', 'tax_receipt', '{{ Str::substr($order->tax_receipt, stripos($order->tax_receipt, 'tax_receipt_') + 12) }}')" style="cursor: pointer;"><img src="{{ URL::asset('front-assets/images/icons/file-tax.png') }}" title="{{ __('admin.download_tax_receipt') }}"> </a>
                    @endif
                    @if($order->order_status >= 5 && $order->order_status != 9 && $order->order_status != 10 && $orderStatus->order_status_id == 5 && !empty($order->invoice) )
                        <a href="javascript:void(0);" onclick="downloadimg('{{ $order->id }}', 'invoice', '{{ Str::substr($order->invoice, stripos($order->invoice, 'invoice_') + 8) }}')" style="cursor: pointer;"><img src="{{ URL::asset('front-assets/images/icons/file-invoice.png') }}" title="{{ __('admin.download_invoice') }}"> </a>
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
              $flexClass = '';
                $extraClass = '';
                $qcclass = '';
                if ($order->order_status == 7){
                    $show_listing = $orderTracks->toArray();

                    if (in_array($orderStatus->order_status_id, $show_listing)){
                        $class = 'active';
                    } else {
                        continue;
                    }
                } else{
                    if (($orderStatus->order_status_id == 3 || $orderStatus->order_status_id == 5 || $orderStatus->order_status_id == 8 ) &&
                        ($order->order_status == 6||$order->order_status == 7)){
                         continue;
                    }elseif ($order->request_days_status==1 && $orderStatus->order_status_id==10){
                        continue;
                    }
                }

                $paymentDone = 1;
                $qcDisabled = 1;
                if ($orderStatus->order_status_id == 3 ) {//'Payment Done'
                    $flexClass = 'd-flex';
                }

                if ($orderStatus->order_track_id) {
                    $class = 'active';
                } else {
                    if ($orderStatus->order_status_id == 3) {//'Payment Done'
                        $class = 'paymentDoneBlock';
                        $paymentDone = 0;
                    } else {
                        $class = '';
                    }
                }

                if ($orderStatus->order_status_id == 5) {//'Order Completed'
                    $extraClass = ' finalOrderStatusBlock';
                }
                $status_name = __('order.' . trim($orderStatus->status_name));
                if($orderStatus->status_name == 'Order Received'){
                    $status_name = __('order.orderplaced');
                }
                if ($orderStatus->order_status_id == 8) {//'Payment Due DD/MM/YYYY'
                    $status_name = $order->payment_due_date?sprintf($status_name,newChangeDateFormat($order->payment_due_date,'d/m/Y')):sprintf($status_name,'DD/MM/YYYY');
                }
            @endphp
            <li class="{{ $class . ' ' . $extraClass . ' ' .$qcclass }}">
                <h6 class="mb-0 {{ $flexClass }}">
                     @if(in_array($orderItemCategoryId,\App\Models\Category::SERVICES_CATEGORY_IDS) && $order->order_status== 1 && $orderStatus->order_status_id == 2 && ($orderlogisticProvided->logistic_check==1 && $orderlogisticProvided->logistic_provided ==0))
                        <a href="javascript:void(0)" style="cursor: pointer" class="creditOrderStatusChange" id="creditOrderStatusChange{{$order->id}}" onclick="creditOrderStatusChange($(this))" data-order-id="{{$order->id}}" data-status-id="{{$orderStatus->order_status_id}}">{{ $status_name }}</a>
                        <img height="16px" class="d-none" src="{{URL::asset("front-assets/images/icons/timer.gif")}}" id="creditOrderStatusChange_loading{{$order->id}}" alt="{{__('admin.loading')}}..." data-toggle="tooltip" title="{{__('admin.loading')}}..." srcset>
                    @else
                        <a href="javascript:void(0)">{{ $status_name }}</a>
                    @endif
                    @php
                        $downloadPoUrl = $order->order_status != 7 ? route('download-user-po-pdf', Crypt::encrypt($order->id)) : 'javascript:void(0)';
                        $downloadblitznetInvoiceUrl = $order->order_status != 7 ? route('download-user-blitznet-invoice-pdf', Crypt::encrypt($order->id)) : 'javascript:void(0)';
                    @endphp
                    @if($order->order_status >= 2 && $orderStatus->order_status_id == 2 && isset($order->po_number))
                        <a data-id="{{ $order->id }}" id="downloadPo{{ $order->id }}" href="{{ $downloadPoUrl }}" style="cursor: pointer;" ><img src="{{ URL::asset('front-assets/images/icons/file-order-letter.png') }}" title="{{ __('admin.download_po') }}"> </a>
                    @endif
                    @if($order->order_status >= 5 && ($order->order_status != 9 && $order->order_status != 10) && $orderStatus->order_status_id == 5 && isset($order->inv_number))
                        <a data-id="{{ $order->id }}" id="downloadPo{{ $order->id }}" href="{{ $downloadblitznetInvoiceUrl }}" style="cursor: pointer;" ><img src="{{ URL::asset('front-assets/images/icons/file-invoice.png') }}" title="{{ __('admin.download_blitznet_invoice') }}"> </a>
                    @endif
                    @if($order->order_status >= 5 && $order->order_status != 9 && $order->order_status != 10 && $orderStatus->order_status_id == 5 && !empty($order->tax_receipt) )
                        <a href="javascript:void(0);" onclick="downloadimg('{{ $order->id }}', 'tax_receipt', '{{ Str::substr($order->tax_receipt, stripos($order->tax_receipt, 'tax_receipt_') + 12) }}')" style="cursor: pointer;" ><img src="{{ URL::asset('front-assets/images/icons/file-tax.png') }}" title="{{ __('admin.download_tax_receipt') }}"> </a>
                    @endif
                    @if($order->order_status >= 5 && $order->order_status != 9 && $order->order_status != 10 && $orderStatus->order_status_id == 5 && !empty($order->invoice) )
                        <a href="javascript:void(0);" onclick="downloadimg('{{ $order->id }}', 'invoice', '{{ Str::substr($order->invoice, stripos($order->invoice, 'invoice_') + 8) }}')" style="cursor: pointer;" ><img src="{{ URL::asset('front-assets/images/icons/file-invoice.png') }}" title="{{ __('admin.download_invoice') }}"> </a>
                    @endif
                    @if ($orderStatus->order_status_id == 3 && $order->invoice_url)
                        <div class="px-2">
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
                                <a id="pay{{$order->id}}" class="btn btn-warning align-middle btn-sm shadow ms-auto px-1 py-0 text-dark position-relative" style="top: -3px;cursor: pointer;" href="{{$order->invoice_url}}" target="_blank" ><small style="font-size: 11px;">{{ __('order.pay_button') }}</small></a>
                                @if($isUnder48Hours)
                                    <small class="text-danger" style="font-size: 12px;line-height: 2;display: inline-block;"><img height="16px" src="{{ URL::asset('front-assets/images/icons/timer.gif') }}" title="Time Remaining"><span id="remainingTime{{$order->id}}" class="remainingTime" data-id="{{$order->id}}" data-remaining-seconds="{{$remainingTimeSeconds}}"></span></small>
                                @endif
                            @endif
                            @endif
                        </div>

                    @endif
                </h6>
                <p>
                    &nbsp;
                    <small>
                        {{ $orderStatus->created_at && $class == 'active' ? newChangeDateTimeFormat($orderStatus->created_at,$order->orderDateSetting) : ' ' }}
                    </small>
                </p>
            </li>
        @endif
    @endforeach
@endif
