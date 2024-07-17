@extends('admin/adminLayout')

@push('bottom_head')
<style>
    .bullet-line-list a {
        color: #25378b;
        text-decoration: none;
    }

    .bullet-line-list li.active h6 a {
        color: #23af47;
    }

    .bullet-line-list li.active:before {
        border: 4px solid #23af47;
    }

</style>
<meta name="csrf-token" content="{!! csrf_token() !!}">
@endpush
@section('content')

<div class="card h-100 bg-transparent newtable_v2">
    <div class="card-body p-0">
        <h4 class="card-title">Disbursements</h4>
        <div class="row clearfix">
            <div class="col-12">
                <div class="table-responsive">
                    <table id="orderTable" class="table  table-hover">
                        <thead>
                            <tr>
                                <th class="hidden">ID</th>
                                <th>{{ __('admin.order_number') }}</th>
                                <th>{{ __('admin.disbursement_number') }}</th>
                                <th>{{ __('admin.disbursement_id') }}</th>
                                <th>{{ __('admin.supplier_company') }}</th>
                                <th>{{ __('admin.status') }}</th>
                                <th>{{ __('admin.failure_message') }}</th>
                                <th>{{ __('admin.created_at') }}</th>
                                <th>{{ __('admin.updated_at') }}</th>
                                <th>{{ __('admin.bank_code') }}</th>
                                <th>{{ __('admin.bank_account_name') }}</th>
                                <th>{{ __('admin.amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($disbursements) > 0)
                            @foreach ($disbursements as $disburse)
                            <tr>
                                <td class="hidden">{{ $disburse->id }}</td>
                                <td><a href="javascript:void(0);" style="text-decoration: none; color: #000" class="getSingleOrderDetail hover_underline" data-bs-toggle="modal" data-bs-target="#staticBackdrop" data-id="{{ $disburse->order_id }}">{{ $disburse->order_number }}</a></td>
                                <td>{{ $disburse->external_id }}</td>
                                <td>{{ $disburse->disbursement_id }}</td>
                                <td>{{ $disburse->supplier_company?$disburse->supplier_company:'' }}</td>
                                <td>
                                    @if($disburse->status=='PENDING')
                                        <span class="badge badge-pill badge-info">{{ ucwords(strtolower($disburse->status)) }}</span>
                                    @elseif($disburse->status=='COMPLETED')
                                        <span class="badge badge-pill badge-success">{{ ucwords(strtolower($disburse->status)) }}</span>
                                    @else
                                        <span class="badge badge-pill badge-danger">{{ ucwords(strtolower($disburse->status)) }}</span>
                                    @endif
                                </td>
                                <td>{{ $disburse->failure_message?ucwords(str_replace('_',' ',strtolower($disburse->failure_message))):'' }}</td>
                                <td>{{ $disburse->created?changeDateTimeFormat($disburse->created):'' }}</td>
                                <td>{{ $disburse->updated?changeDateTimeFormat($disburse->updated):'' }}</td>
                                <td>{{ $disburse->bank_code }}</td>
                                <td>{{ $disburse->bank_account_name }}</td>
                                <td>{{ 'Rp ' . number_format($disburse->amount, 2) }}</td>
                                {{--<td></td>--}}
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

<div class="modal version2 fade" id="staticBackdrop" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="getSingleOrderDetail">
        </div>
    </div>
</div>

@stop
@push('bottom_scripts')
    <script>
        $(document).ready(function() {
            $('#orderTable').DataTable({
                "order": [
                    [0, "desc"]
                ],
                "aLengthMenu": [
                    [10, 20, 50, -1],
                    [10, 20, 50, "All"]
                ],
                "iDisplayLength": 10,
                "columnDefs": [{
                    "targets": [0],
                    "visible": false,
                    "searchable": false
                }, ]
            });
        });

        $(document).on('click', '.getSingleOrderDetail', function() {
            var orderId = $(this).attr('data-id');
            $.ajax({
                url: "{{ route('get-single-order-detail-ajax', '') }}" + "/" +
                    orderId,
                type: 'GET',
                success: function(successData) {
                    console.log(successData);
                    if (successData.html) {
                        $('#getSingleOrderDetail').html(successData.html);
                        $('#staticBackdrop').modal('show');
                    }
                },
                error: function() {
                    console.log('error');
                }
            });
        });

    </script>
@endpush

