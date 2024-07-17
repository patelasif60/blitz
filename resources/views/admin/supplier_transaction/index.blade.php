@extends('admin/adminLayout')
@section('content')
    <div class="card h-100 bg-transparent newtable_v2">
        <div class="card-body p-0">
            <div class="d-flex align-items-center">
                <h4 class="card-title">{{__('admin.supplier_transaction_charges')}}</h4>
            </div>
            <div class="row clearfix">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="supplier-charge-list" class="table table-hover">
                            <thead>
                            <tr>
                                <th class="hidden">{{__('admin.id')}}</th>
                                <th>{{__('admin.supplier_name')}}</th>
                                <th>{{__('admin.paid_date')}}</th>
                                <th>{{__('admin.paid_amount')}}</th>
                                {{--<th>Added At</th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($supplerCharges) > 0)
                                @foreach ($supplerCharges as $supplerCharge)
                                    <tr>
                                        <td class="hidden">{{ $supplerCharge->id }}</td>
                                        <td>{{ $supplerCharge->supplier()->value('name') }}</td>
                                        <td>{{ changeDateFormat($supplerCharge->paid_date) }}</td>
                                        <td>
                                            {{ 'Rp ' . number_format($supplerCharge->paid_amount,2) }}
                                        </td>
                                        {{--<td>
                                            {{ changeDateTimeFormat($supplerCharge->created_at) }}
                                        </td>--}}
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


@stop

@push('bottom_scripts')
<script>
    $(document).ready(function() {
        $('#supplier-charge-list').DataTable({
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
            },]
        });
    });
</script>
@endpush

