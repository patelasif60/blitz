@if (count($userRfq))
    @foreach ($userRfq as $rfq)
        <div class="modal-header">

            <h5 class="modal-title d-flex w-100 align-items-center" id="viewModalLabel">{{ __('rfqs.rfq_no') . ' ' . $rfq->reference_number }}<small class="text-muted font_sub fs-6 ps-2"> ({{ __('rfqs.Details') }}) </small>
            </h5>
            <a href="{{ route('dashboard-get-rfq-print', Crypt::encrypt($rfq->id)) }}" target="_blank" class="btn px-3 py-1"><img src="{{ URL::asset('front-assets/images/icons/icon_print.png') }}" alt="Print" class="pe-2"></a>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body" >

            <div class="row rfqform_view g-3">
                <div class="col-md-4">
                    <label>{{ __('rfqs.rfq_number') }}:</label>
                    <div>{{ $rfq->reference_number }}</div>
                </div>
                <div class="col-md-4">
                    <label>{{ __('rfqs.date') }}:</label>
                    <div>{{ date('d-m-Y H:i', strtotime($rfq->created_at)) }}</div>
                </div>
                @php
                    $badgeColor = $rfq->status_name == 'RFQ Completed' ? 'bg-success': ($rfq->status_name == 'RFQ Cancelled' ? 'bg-danger' : 'bg-primary');
                @endphp

                <div class="col-md-4">
                    <label>{{ __('rfqs.status') }}:</label>
                    <div><span class="badge rounded-pill  mt-1 {{ $badgeColor }}">{{ __('rfqs.' . $rfq->status_name) }}</span></div>
                </div>

                <div class="col-md-4">
                    <label>{{ __('rfqs.name') }}:</label>
                    {{-- <label>nm :</label> --}}
                    <div class="text-capitalized">{{ $rfq->firstname . ' ' . $rfq->lastname }}</div>
                </div>
                <div class="col-md-4">
                    <label>{{ __('rfqs.company') }}:</label>
                    <div>{{ $rfq->user_company_name }}</div>
                </div>
                <div class="col-md-4">
                    <label>{{ __('rfqs.mobile') }}:</label>
                    <div>{{ countryCodeFormat($rfq->user_phone_code, $rfq->mobile) }}</div>
                </div>
                <div class="col-md-4">
                    <label>{{ __('rfqs.expected_delivery_date') }}:</label>
                    @if ($rfq->expected_date)
                        <div> {{ date('d-m-Y', strtotime($rfq->expected_date)) }}</div>
                    @else
                        <div> - </div>
                    @endif
                </div>
                <div class="col-md-4">
                    <label>{{ __('rfqs.comment') }}:</label>
                    <div>{{ $rfq->rfq_comment ? $rfq->rfq_comment : ' - ' }}</div>
                </div>
                <div class="col-md-4">
                    <label class="d-block">{{ __('rfqs.payment_terms') }}:</label>
                    @if($rfq->payment_type==1)
                        <div class="text-dark"><span class="badge rounded-pill bg-danger">{{ __('admin.credit') }} - {{$rfq->credit_days}}</span></div>
                    @elseif($rfq->payment_type==0)
                        <div class="text-dark"><span class="badge rounded-pill bg-success">{{ __('admin.advance') }}</span></div>
                    @elseif($rfq->payment_type==3)
                        <div class="text-dark"><span class="badge rounded-pill bg-danger">{{  __('admin.lc')  }}</span></div>
                    @elseif($rfq->payment_type==4)
                        <div class="text-dark"><span class="badge rounded-pill bg-danger">{{ __('admin.skbdn') }}</span></div>
                    @else
                        <div class="text-dark"><span class="badge rounded-pill bg-danger">{{ __('admin.credit') }}</span></div>
                    @endif
                </div>
                @if(isset($rfq->termsconditions_file) && !empty($rfq->termsconditions_file))
                    <div class="col-md-4">
                        @php
                            $termsconditionsFileTitle = Str::substr($rfq->termsconditions_file,stripos($rfq->termsconditions_file, "termsconditions_file_") + 21);
                            $extension_termsconditions_file = getFileExtension($termsconditionsFileTitle);
                            $termsconditions_file_filename = getFileName($termsconditionsFileTitle);
                            if(strlen($termsconditions_file_filename) > 10){
                                $termsconditions_file_name = substr($termsconditions_file_filename,0,13).$extension_termsconditions_file;
                            } else {
                                $termsconditions_file_name = $termsconditions_file_filename.$extension_termsconditions_file;
                            }
                        @endphp
                        <label for="termsconditions_file">{{ __('admin.commercial_terms') }}</label>
                        <div>
                            <a href="{{ $rfq->termsconditions_file? Storage::url($rfq->termsconditions_file) :'javascript:void(0);' }}" class="text-decoration-none" target="_blank" title="{{$termsconditions_file_name}}">{{$termsconditions_file_name}}</a>
                        </div>
                   </div>
                @endif
                <div class="col-md-8">
                    <label>{{ __('rfqs.address') }}:</label>
                    <div>
                        {{ $rfq->address_line_1?($rfq->address_line_1.','):'' }} {{ $rfq->address_line_2?($rfq->address_line_2.','):'' }} {{ $rfq->sub_district?($rfq->sub_district.','):'' }} {{ $rfq->district?($rfq->district.','):'' }}
                        {!! $rfq->city_id > 0 ? getCityName($rfq->city_id) : $rfq->city !!}, {{ $rfq->state_id > 0 ? getStateName($rfq->state_id) : $rfq->state }} {{ $rfq->pincode }}
                    </div>
                </div>
                @if(isset($rfq->group_id) && !empty($rfq->group_id))
                <div class="col-md-4">
                    <label>{{ __('rfqs.group_number') }}:</label>
                    <div>
                        {{'BGRP-'.$rfq->group_id}}
                    </div>
                </div>
                @endif
            </div>
            <div class="card my-3">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr >
                            <th>{{ __('rfqs.product') }}</th>
                            <th class="ml-0">{{ __('rfqs.product_description') }}</th>
                            <th>{{ __('rfqs.quantity') }}</th>

                        </tr>
                    </thead>
                    <tbody>
                    @foreach($all_products as $key => $value)
                        <tr>
                            <td>{{ $value->category . ' - ' . $value->sub_category . ' - ' . $value->product }}</td>
                            <td class="ml-0">{{ $value->product_description }} </td>
                            <td>{{ $value->quantity }} {{ $value->unit_name }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
            <div class="row rfqform_view g-3">

                @if ($rfq->unloading_services || $rfq->rental_forklift)
                    <div class="col-md-6">
                        <label>{{ __('rfqs.other_options') }}:</label>
                        @if ($rfq->unloading_services)
                            <br><span>{{ __('dashboard.need_unloading_services') }}</span>
                        @endif
                        @if ($rfq->rental_forklift)
                            <br><span>{{ __('dashboard.need_rental_forklift') }}</span>
                        @endif
                    </div>
                @endif
            </div>

            <div class="row rfqform_view g-3">
                <div class="col-md-4">
                    @if(isset($rfq_attachments) && count($rfq_attachments)>=1)
                        @php

                            if(count($rfq_attachments)>1){
                                $downloadAttachment = "downloadAttachment(".$rfq->id.", 'attached_document','".$rfq->reference_number."')";
                            }else{
                                $rfqFileTitle = Str::substr($rfq_attachments[0]->attached_document,44);
                                $extension_rfq_file = getFileExtension($rfqFileTitle);
                                $rfq_file_filename = getFileName($rfqFileTitle);
                                if(strlen($rfq_file_filename) > 10){
                                   $rfq_file_name = substr($rfq_file_filename,0,10).'...'.$extension_rfq_file;
                                } else {
                                   $rfq_file_name = $rfq_file_filename.$extension_rfq_file;
                                }
                                $downloadAttachment = "downloadimg(".$rfq_attachments[0]->id.", 'attached_document', '".$rfq_file_name."')";
                            }
                        @endphp
                        <label for="download_attachment">{{ __('rfqs.attachment') }}</label>
                        <div>
                            <a class="text-decoration-none btn btn-primary p-1" href="javascript:void(0);" data-id="{{ $rfq->id }}" id="RfqFileDownload" onclick="{{$downloadAttachment}}"  title="{{ __('rfqs.rfq_attachment') }}" style="text-decoration: none;font-size: 12px;"> <svg id="Layer_1" width="12px" fill="#fff" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 383.26 408.81"><path d="M85.94,460.41c-7.92-2.27-14.94-6-18.44-13.85a30.88,30.88,0,0,1-3-11.82c-.23-33.38-.13-66.77-.12-100.16a16.74,16.74,0,0,1,.19-1.71h50.7V409H396.45V332.87h51.18v96.29c0,18.44-4.41,24.83-21.56,31.25Z" transform="translate(-64.37 -51.59)"/><path d="M217.68,230.45V67.21c0-10.91,4.65-15.6,15.46-15.6q23.36,0,46.69,0c9.48,0,14.49,5,14.49,14.56q0,79.62,0,159.22v5.06H299c14.9,0,29.8.05,44.69,0,6,0,10.76,2,13.31,7.64s.87,10.33-2.94,14.68q-43.76,50-87.5,100c-6.52,7.43-14.63,7.37-21.2-.12q-43.66-49.8-87.24-99.68c-3.91-4.46-5.65-9.4-3-15s7.44-7.53,13.4-7.5C184.7,230.5,200.92,230.45,217.68,230.45Z" transform="translate(-64.37 -51.59)"/></svg> {{ __('rfqs.rfq_attachment') }}</a>
                        </div>
                    @endif
                </div>
            </div>

        </div>
        <div class="modal-footer">

        </div>
        {{-- <div class="modal-footer">
            <div>
                Test
            </div>
        </div> --}}
    @endforeach
@else
    <div class="col-md-12">
        <div class="alert alert-danger radius_1 text-center fs-6 fw-bold">{{ __('rfqs.No_rfq_found') }}</div>
    </div>
@endif


<script>
    //Download attachment document
    function downloadAttachment(rfq_id,fieldName, ref_no){
        event.preventDefault();
        var data = {
            rfq_id:rfq_id,
            fieldName: fieldName,
            ref_no: ref_no,
        }
        $.ajax({
            url: "{{ route('rfq-attachment-download-ajax') }}",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: data,
            type: 'POST',
            xhrFields: {
                responseType: 'blob'
            },
            success: function (response) {
                var binaryData = [];
                binaryData.push(response);
                var blob = new Blob(binaryData, {type: "application/zip"});
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = ref_no;
                link.click();
            },
        });
    }
    //Download single attachment document
    function downloadimg(rfq_id,fieldName, name){
        event.preventDefault();
        var data = {
            rfq_id:rfq_id,
            fieldName: fieldName
        }
        $.ajax({
            url: "{{ route('rfq-single-attachment-document-ajax') }}",
            headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
            data: data,
            type: 'POST',
            xhrFields: {
                responseType: 'blob'
            },
            success: function (response) {
                var blob = new Blob([response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = name;
                link.click();
            },
        });
    }
</script>

