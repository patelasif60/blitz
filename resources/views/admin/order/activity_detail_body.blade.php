<form method="post" id="editOrderform" action="{{ route('order-update') }}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{ $order->id }}">
    <div class="row rfqform_view bg-white">
        <div class="col-md-4 pb-2 showDownloadPo">
            @if (empty($orderPo))
                <label class="form-label">{{ __('admin.generate_po') }}: </label>
                @if(auth()->user()->role_id == 3)
                    <div>{{ __('admin.po_pending') }}</div>
                @else
                    @if($order->order_status == 2 || $order->order_status >= 2 && $order->order_status != 7)
                        <div><a href="javascript:void(0);" class="text-decoration-none generatePo btn btn-primary p-1 fw-bold" data-status="{{ $order->order_status }}" data-id="{{ $order->id }}" id="generatePo{{ $order->id }}">{{ __('admin.generate_po') }}</a></div>
                    @else
                        <div><a href="javascript:void(0);" class="text-decoration-none generatePoFirst btn btn-primary p-1 fw-bold" data-status="{{ $order->order_status }}" data-id="{{ $order->id }}" id="generatePo{{ $order->id }}">{{ __('admin.generate_po') }}</a></div>
                    @endif

                @endif
            @else
                <label class="form-label">{{ __('admin.download_po') }}: </label>
                @php
                    $downloadPoUrl = $order->order_status != 7 ? route('download-po-pdf', Crypt::encrypt($order->id)) : 'javascript:void(0)';
                @endphp
                <div><a class="text-decoration-none downloadPo btn btn-primary p-1 fw-bold" data-id="{{ $order->id }}" id="downloadPo{{ $order->id }}" data-status="{{ $order->order_status }}" href="{{ $downloadPoUrl }}"> <svg id="Layer_1" width="12px" fill="#fff" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 383.26 408.81"><path d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z" transform="translate(-64.37 -51.59)"/><path d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z" transform="translate(-64.37 -51.59)"/></svg> {{ __('admin.download_po') }}</a></div>
            @endif
        </div>
        <div class="col-md-4 pb-2 pe-2">
            <label class="form-label">{{ __('admin.upload_tax_receipt') }}: </label>
            <div class="d-flex">
                <span class=""><input type="file" name="tax_receipt" class="form-control" id="image-tax_receipt" accept=".jpg,.png,.pdf" onchange="show(this)" hidden {{$order->order_status==7 ? 'disabled' : ''}}/><label id="upload_btn" for="image-tax_receipt">{{ __('admin.browse') }}</label></span>
                <div id="file-tax_receipt">
                    @if ($order->tax_receipt)
                        @php
                            $extension_tax_receipt= substr($order->tax_receipt, -4);
                            $tax_receipt_filename = substr(Str::substr($order->tax_receipt, stripos($order->tax_receipt, 'tax_receipt_') + 12), 0, -4);
                            if(strlen($tax_receipt_filename) > 10){
                                $tax_receipt_name = substr($tax_receipt_filename,0,10).'...'.$extension_tax_receipt;
                            } else {
                                $tax_receipt_name = $tax_receipt_filename.$extension_tax_receipt;
                            }
                        @endphp
                        <input type="hidden" class="form-control" id="oldtax_receipt" name="oldtax_receipt" value="{{ $order->tax_receipt }}">
                        <span class="ms-2"><a href="javascript:void(0);" id="tax_receiptFileDownload" onclick="downloadimg('{{ $order->id }}', 'tax_receipt', '{{ Str::substr($order->tax_receipt, stripos($order->tax_receipt, 'tax_receipt_') + 12) }}')"  title="{{ Str::substr($order->tax_receipt, stripos($order->tax_receipt, 'tax_receipt_') + 12) }}" style="text-decoration: none;"> {{ $tax_receipt_name }} </a></span>
                        {{--@if($order->order_status < 14)--}}
                        @if(!Auth::user()->hasRole('jne'))
                            <span class="removeFile" style="{{ $order->order_status < 5 ? '' : 'display:none' }}" id="tax_receiptFile" data-id="{{ $order->id }}" file-path="{{ $order->tax_receipt }}" data-name="tax_receipt"><a href="#" title="Remove Tax Receipt"> <img src="{{URL::asset('assets/icons/times-circle copy.png')}}" alt="CLose button" class="ms-2"></a></span>
                        @endif
                        {{--@endif--}}
                        <span class="ms-2"><a class="tax_receiptFile" href="javascript:void(0);" title="{{ __('admin.download_tax_receipt') }}" onclick="downloadimg('{{ $order->id }}', 'tax_receipt', '{{ Str::substr($order->tax_receipt, stripos($order->tax_receipt, 'tax_receipt_') + 12) }}')" style="text-decoration: none;"><i class="fa fa-cloud-download"></i></a></span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4 pb-2">
            <label class="form-label">{{ __('admin.upload_invoice') }}: </label>
            <div class="d-flex">
                <span class=""><input type="file" name="invoice" class="form-control" id="image-invoice" accept=".jpg,.png,.pdf" onchange="show(this)" hidden {{$order->order_status==7 ? 'disabled' : ''}}/><label id="upload_btn" for="image-invoice">{{ __('admin.browse') }}</label></span>
                <div id="file-invoice">
                    @if ($order->invoice)
                        @php
                            $extension_invoice = substr($order->invoice, -4);
                            $invoice_filename = substr(Str::substr($order->invoice, stripos($order->invoice, 'invoice_') + 8), 0, -4);
                            if(strlen($invoice_filename) > 10){
                                $invoice_name = substr($invoice_filename,0,10).'...'.$extension_invoice;
                            } else {
                                $invoice_name = $invoice_filename.$extension_invoice;
                            }
                        @endphp
                        <input type="hidden" class="form-control" id="oldinvoice" name="oldinvoice" value="{{ $order->invoice }}">
                        <span class="ms-2"><a href="javascript:void(0);" id="invoiceFileDownload" onclick="downloadimg('{{ $order->id }}', 'invoice', '{{ Str::substr($order->invoice, stripos($order->invoice, "invoice_") + 8) }}')"  title="{{ Str::substr($order->invoice, stripos($order->invoice, 'invoice_') + 8) }}" style="text-decoration: none;"> {{ $invoice_name }}</a></span>
                        {{--@if($order->order_status < 14)--}}
                        @if(!Auth::user()->hasRole('jne'))
                            <span class="removeFile" style="{{ $order->order_status < 5 ? '' : 'display:none' }}" id="invoiceFile" data-id="{{ $order->id }}" file-path="{{ $order->invoice }}" data-name="invoice"><a href="#" title="Remove Invoice"> <img src="{{URL::asset('assets/icons/times-circle copy.png')}}" alt="CLose button" class="ms-2"></a></span>
                        @endif
                        {{--@endif--}}
                        <span class="ms-2"><a class="invoiceFile" href="javascript:void(0);" title="{{ __('admin.download_invoice') }}" onclick="downloadimg('{{ $order->id }}', 'invoice', '{{ Str::substr($order->invoice, stripos($order->invoice, "invoice_") + 8) }}')" style="text-decoration: none;"><i class="fa fa-cloud-download"></i></a></span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</form>
