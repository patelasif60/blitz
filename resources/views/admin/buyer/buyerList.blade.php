@extends('admin/adminLayout')

@push('bottom_head')
    <meta name="csrf-token" content="{!! csrf_token() !!}">
    <style>
        .color-gray {
            color: gray;
        }

        .loader{
            position: fixed;
            text-align: center;
            width: 100%;
            left: 49%;
            top: 49%;
        }
    </style>

@endpush
@section('content')
    <div class="modal fade" id="BuyerCategoryModal" tabindex="-1" role="dialog"
         aria-labelledby="BuyerCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="BuyerCategoryModalLabel">{{ __('admin.category') }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="BuyerCategoryModalBlock">

                </div>
                <div class="modal-footer">
                    {{-- <button type="button" class="btn btn-success">Send Quote</button> --}}
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card h-100 bg-transparent newtable_v2">
        <div class="card-body p-0">
            <div class="d-flex align-items-center pb-3">
                <h4 class="card-title pt-3">{{ __('admin.buyers') }}</h4>
				<div class="dropdown ms-auto me-1">
					<div class="pe-1 mb-3 clearfix d-flex align-items-center">
						<a href="{{ route('export-excel-buyer-ajax') }}" class="btn btn-warning btn-sm ms-1" style="padding: 0.25rem 0.5rem;" type="button" id="dropdownMenuButton1" aria-expanded="false">
							{{ __('admin.Export') }}
						</a>
					</div>
                </div>
                <input type="hidden" value="{!! csrf_token() !!}" name="_token">
            </div>
            <div class="row clearfix">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="buyerTable" class="table table-hover">
                            <thead>
                            <tr>
                                <th>supplierId</th>
                                <th>{{ __('admin.name') }}</th>
                                <th>{{ __('admin.company_name') }}</th>
                                <th>{{ __('admin.company_email') }}</th>
                                <th>{{ __('admin.mobile') }}</th>
                                <th>{{ __('admin.contact_person_email') }}</th>
                                <th>{{ __('admin.category') }}</th>
                                <th>{{ __('admin.status') }}</th>
                                <th>{{ __('admin.updated_by') }}</th>
                                @canany(['edit buyer list', 'publish buyer list', 'delete buyer list'])
                                <th class="text-center">{{ __('admin.actions') }}</th>
                                @endcanany
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($buyers) > 0)

                                @foreach ($buyers as $buyer)
                                    <tr>
                                        <td>{{ $buyer->id }}</td>
                                        @php
                                            $fullName = $buyer->firstname." ".$buyer->lastname;
                                            $mobile = $buyer->phone_code." ".$buyer->mobile;
                                        @endphp
                                        <td>{{ $fullName }}</td>
                                        <td>{{ $buyer->company_name }}</td>
                                        <td>{{ $buyer->company_email }}</td>
                                        <td>{{ $mobile }}</td>
                                        <td>{{ $buyer->email }}</td>
                                        <td>
                                            @php
                                                $category="";
                                                    foreach ($companyConsumption as $key=>$val) {
                                                        if($val->user_id == $buyer->id){
                                                            $category=$val->category;
                                                        }
                                                    }
                                                @endphp
                                            @if (!empty($category))
                                                {{ $category }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if ($buyer->is_active == 0)
                                                <a href="javascript:void(0)" data-id="{{ $buyer->id }}"
                                                   data-status="{{ $buyer->is_active }}"
                                                   class="color-gray changeStatus"
                                                   id="buyer{{ $buyer->id }}" data-toggle="tooltip" ata-placement="top" title="{{__('admin.inactive')}}"><i class="fa fa-user-circle"
                                                                                                                                                                  aria-hidden="true"></i></a>
                                            @else
                                                <a href="javascript:void(0)" data-id="{{ $buyer->id }}"
                                                   data-status="{{ $buyer->is_active }}"
                                                   id="buyer{{ $buyer->id }}" class="changeStatus" data-toggle="tooltip" ata-placement="top" title="{{__('admin.active')}}"><i
                                                        class="fa fa-user-circle" aria-hidden="true"></i></a>
                                            @endif
                                        </td>

                                        <td>
                                            @if( !empty( $buyer->userUpdateData() ) )
                                                @php $updatedByFullname = $buyer->userUpdateData()->firstname ." ".$buyer->userUpdateData()->lastname; @endphp
                                                {{  $updatedByFullname }}
                                            @else {{"-"}}@endif
                                        </td>
                                        @canany(['edit buyer list', 'publish buyer list', 'delete buyer list'])
                                        <td class="text-end text-nowrap">

                                            @can('publish buyer list')
                                                <a class="ps-2 cursor-pointer buyerModalView" data-bs-toggle="modal" data-bs-target="#buyerModal" data-id="{{ $buyer->id }}" data-toggle="tooltip" ata-placement="top" title="{{__('admin.view')}}"><i class="fa fa-eye"></i></a>
                                            @endcan

                                            @can('edit buyer list')
                                                <a href="{{ route('buyer-edit', ['id' => Crypt::encrypt($buyer->id)]) }}"
                                                   class="show-icon ps-2" data-toggle="tooltip" ata-placement="top" title="{{__('admin.edit')}}"><i class="fa fa-edit" aria-hidden="true"></i>
                                                </a>
                                            @endcan

                                            @can('delete buyer list')
                                                <a href="javascript:void(0)" id="deleteBuyer_{{ $buyer->id }}_{{$buyer->companyId}}"
                                                   data-name="{{ $fullName }}"
                                                   class="deleteBuyer show-icon ps-2" data-toggle="tooltip" ata-placement="top" title="{{__('admin.delete')}}"><i class="fa fa-trash"
                                                                                                                                                                     aria-hidden="true"></i>
                                                </a>
                                            @endcan
                                        </td>
                                        @endcanany
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
    <!-- --- Buyer --- -->
    <!-- Modal -->
    <div class="modal fade version2" id="buyerModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div id="modelLodear"></div>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#buyerTable').DataTable({
                "order": [
                    [0, "desc"]
                ],
                "aLengthMenu": [
                    [10, 20, 50, -1],
                    [10, 20, 50, "All"]
                ],
                "iDisplayLength": 10,
                'columnDefs': [{
                    'targets': [1, 6, 7, 8], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }, {
                    "targets": [0],
                    "visible": false,
                    "searchable": false
                }]
            });
            window.Parsley.addValidator('uniqueemail', {
                validateString: function (value) {
                    let res = false;
                    xhr = $.ajax({
                        url: '{{ route('check-xen-email-exist') }}',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        dataType: 'json',
                        method: 'POST',
                        data: {
                            email: value,
                        },
                        async: false,
                        success: function(data) {
                            res = data;
                        },
                    });
                    return res;
                },
                messages: {
                    en: 'This email already exists!'
                },
                priority: 32
            });

            $(document).on('click', '.buyerModalView', function(e) {
                $("#buyerModal").find(".modal-content").html('');
                e.preventDefault();
                var id = $(this).attr('data-id');
                if (id) {
                    $.ajax({
                        url: "{{ route('buyer-detail-view', '') }}" + "/" + id,
                        type: 'GET',
                        success: function(successData) {
                            $("#buyerModal").find(".modal-content").html(successData.buyerView);
                            $('#buyerModal').modal('show');
                        },
                        error: function() {
                            console.log('error');
                        }
                    });
                }
            });
            $(document).on('click', '.deleteBuyer', function() {
                var id = $(this).attr('id').split("_")[1];
                var companyId = $(this).attr('id').split("_")[2];

                //console.log($(this).attr('id').split("_")[2]);
                swal({
                    title: "{{ __('admin.delete_sure_alert') }}",
                    text: "{{ __('admin.supplier_delete_text') }}",
                    icon: "/assets/images/bin.png",
                    buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.delete') }}'],
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            var _token = $("input[name='_token']").val();
                            var senddata = {
                                id: id,
                                companyId :companyId,
                                _token: _token
                            }
                            $.ajax({
                                url: "{{ route('buyer-delete') }}",
                                type: 'POST',
                                data: senddata,
                                success: function(successData) {
                                    if(successData.success == true){
                                        $.toast({
                                            heading: '{{ __('admin.success') }}',
                                            text: '{{ __('admin.buyer_delete_success_message') }}',
                                            showHideTransition: 'slide',
                                            icon: 'success',
                                            loaderBg: '#f96868',
                                            position: 'top-right'
                                        })
                                        location.reload();
                                    } else {
                                        swal({
                                            text: '{{ __('admin.buyer_not_delete_hard') }}',
                                            button: {
                                                text: "{{ __('admin.ok') }}",
                                                value: true,
                                                visible: true,
                                                className: "btn btn-primary"
                                            }
                                        })
                                    }
                                },
                                error: function() {
                                    console.log('error');
                                }
                            });

                        }
                    });

            });
            $(document).on('click', '.changeStatus', function() {
                var id = $(this).attr('data-id');
                var status = $(this).attr('data-status');
                var updateStatus;
                var text = '';
                if (status == 0) {
                    text = '{{ __('admin.active_buyer_message') }}';
                    updateStatus = 1;
                } else {
                    text = '{{ __('admin.deactive_buyer_message') }}';
                    updateStatus = 0;
                }

                swal({
                    title: "{{ __('admin.delete_sure_alert') }}",
                    text: text,
                    icon: "/assets/images/info.png",
                    buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.yes') }}'],
                    dangerMode: true,
                })
                    .then((status) => {
                        if (status) {
                            var _token = $("input[name='_token']").val();
                            var senddata = {
                                id: id,
                                status: updateStatus,
                                _token: _token
                            }
                            $.ajax({
                                url: "{{ route('buyer-status-change-ajax') }}",
                                type: 'POST',
                                data: senddata,
                                success: function(successData) {
                                    if(successData.success == true){
                                        $.toast({
                                            heading: '{{ __('admin.success') }}',
                                            text: '{{ __('admin.buyer_status_updated_success_message') }}.',
                                            showHideTransition: 'slide',
                                            icon: 'success',
                                            loaderBg: '#f96868',
                                            position: 'top-right'
                                        });
                                        if (updateStatus == 1) {
                                            $('#buyer' + id).removeClass('color-gray').attr(
                                                'data-status', updateStatus);
                                        } else {
                                            $('#buyer' + id).addClass('color-gray').attr(
                                                'data-status', updateStatus);
                                        }
                                    } else {
                                        swal({
                                            text: '{{ __('admin.buyer_not_deactive_message') }}',
                                            button: {
                                                text: "{{ __('admin.ok') }}",
                                                value: true,
                                                visible: true,
                                                className: "btn btn-primary"
                                            }
                                        })
                                    }

                                },
                                error: function() {
                                    console.log('error');
                                }
                            });

                        }
                    });

            });


        });
        function downloadimg(id, fieldName, name){
            event.preventDefault();
            var data = {
                id: id,
                fieldName: fieldName
            }
            $.ajax({
                url: "{{ route('buyer-download-image-ajax') }}",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
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
@stop
