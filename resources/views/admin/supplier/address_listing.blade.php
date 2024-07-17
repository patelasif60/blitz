@extends('admin/adminLayout')
@section('content')

<style>
.form-switch .form-check-input {
    display: inline-block;
    line-height: 2 !important;
}

.form-switch .form-check-label {
    margin-left: 0em !important;
    line-height: 2 !important;
}
</style>

    <meta name="csrf-token" content="{!! csrf_token() !!}">
    <div class="card h-100 bg-transparent newtable_v2">
        <div class="card-body p-0 ">
            <div class="d-flex align-items-center">
                <h4 class="card-title">{{ __('admin.Address') }}</h4>
                <div class="d-flex align-items-center justify-content-end ms-auto">
                    <div class="pe-1 mb-3 clearfix">
                        <a href="{{ route('add-address') }}" type="button" class="btn btn-primary btn-sm">{{ __('admin.add') }}</a>
                    </div>
                    <input type="hidden" value="{!! csrf_token() !!}" name="_token">
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-12">
                    <div class="row">
                    @if(isset($addresses) && count($addresses) > 0)
                        <input type="text" id="addressCount" value="{{ count($addresses) }}" style="display:none">
                        <input type="text" id="defaultAddressValues" value="{{in_array('1',array_column($addresses->toArray(), 'default_address')) ? 1 : 0}}" style="display:none">
                        @foreach($addresses as $address)
                        <div class="col-md-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="d-flex">
                                        <h4 class="card-title">{{ $address->address_name }}</h4>
                                    </div>
                                    <div class="d-flex flex-wrap align-items-baseline">
                                        <h6 class="me-3" style="line-height: 1.5;">{{ $address->address_line_1 . ' ' . $address->address_line_2 }}
                                            <br>{{ $address->sub_district . ', ' . $address->district }}
                                            <br>{{ $address->city_id > 0 ? $address->getCity()->name : ($address->city ? $address->city : '' ) }}  {{ $address->pincode }}  {{ $address->state_id > 0 ? $address->getState()->name : ($address->state ? $address->state : '') }}
                                        </h6>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-md-7">
                                            <!-- <a href="javascript:void(0)" class="supplierAddressModal" data-bs-toggle="modal" data-bs-target="#SupplierAddressModal" data-id="{{$address->id}}" data-bs-original-title="{{__('admin.view')}}" aria-label="{{__('admin.view')}}"><i class="fa fa-eye" aria-hidden="true"></i></a> -->

                                            <a href="{{ route('supplier-address-edit', ['id' => Crypt::encrypt($address->id)]) }}" class="" data-toggle="tooltip" data-placement="top" data-bs-original-title="{{ __('admin.edit')}}" aria-label="{{ __('admin.edit')}}"><i class="fa fa-edit" aria-hidden="true"></i></a>

                                            <a href="javascript:void(0)" id="" class="ps-2 deleteAddressDetails" data-id="{{$address->id}}" data-toggle="tooltip" ata-placement="top" data-bs-original-title="{{__('admin.delete')}}" aria-label="{{__('admin.delete')}}"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                        </div>
                                        <div class="col-md-5 d-flex justify-content-end">
                                            <div class="form-check form-switch m-0">
                                                <input class="form-check-input account_is_primary me-2" id="account_is_primary_{{$address->id}}" data-id="{{$address->id}}" value="{{ $address->default_address == 0 ? 0 : 1 }}" name="default_address" type="checkbox" role="switch" {{ $address->default_address == 0 ? '' : 'checked' }}>
                                                <label class="form-check-label text-nowrap" for="account_is_primary_{{$address->id}}">{{ __('admin.default_address')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="text-center">
                                        <h6 class="mb-0" >{{ __('admin.no_address_available') }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Address View Model-->
    <div class="modal version2 fade" id="SupplierAddressModal" tabindex="-1" role="dialog" aria-labelledby="SupplierAddressModalLabel" aria-modal="true" style="padding-right: 17px;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <!-- Ajax data will be show here -->
            </div>
        </div>
    </div>
    <!-- End -->

    <script>
        $(document).ready(function () {
            $('#supplier_address_listing').DataTable({
                "order": [
                    [0, "desc"]
                ],
            });
        });

        //View supplier address in popup modal
        $(document).on('click', '.supplierAddressModal', function(e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            if (id) {
                $.ajax({
                    url: "{{ route('supplier-address-detail', '') }}" + "/" + id,
                    type: 'GET',
                    success: function(successData) {
                        $("#SupplierAddressModal").find(".modal-content").html(successData.addressView);
                        $('#SupplierAddressModal').modal('show');
                    },
                    error: function() {
                        console.log('error');
                    }
                });
            }
        });

        //Make address primary (at a time only one address can be primary address)
        $(document).on('click', '#is_primary', function(e) {
            let is_checked = $(this).prop('checked');
            if (!is_checked && parseInt($('#defaultAddressValues').val()) > 0) {
                e.preventDefault();
                swal("{{ __('admin.primary_bank_message') }}!", {
                    icon: "warning",
                });
                return false;
            }
        })

        $(document).on('click', '.account_is_primary', function(e) {
            e.preventDefault();
            let id = $(this).attr("data-id");
            let is_checked = $(this).prop('checked');
            if($("#addressCount").val() > 1) {
                if (!is_checked) {
                    swal("{{ __('admin.primary_address_msg') }}!", {
                        icon: "warning",
                    });
                    return false;
                }
            }
            let that = $(this);
            swal({
                title: "{{ __('admin.delete_sure_alert') }}",
                text: is_checked?"{{ __('admin.primary_address_set_msg') }}.":"{{ __('admin.secondary_address_msg') }}",
                buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    let _token = $('meta[name="csrf-token"]').attr("content");
                    let senddata = {
                        id: id,
                        _token: _token,
                        is_primary:(is_checked ? 1 : 0)
                    }
                    $.ajax({
                        url: "{{ route('supplier-address-status-update') }}",
                        type: 'POST',
                        data: senddata,
                        success: function(successData) {
                            if (successData.success) {
                                $.toast({
                                    heading: '{{ __('admin.success') }}',
                                    text: '{{ __('admin.supplier_address_change') }}.',
                                    showHideTransition: 'slide',
                                    icon: 'success',
                                    loaderBg: '#f96868',
                                    position: 'top-right'
                                });
                                if (is_checked){
                                    $('.account_is_primary').prop('checked',false).attr('checked',false);
                                    $('#account_is_primary_'+id).prop('checked',true).attr('checked',true);
                                }else{
                                    $('#account_is_primary_'+id).prop('checked',false).attr('checked',false);
                                }
                            }else{
                                $.toast({
                                    heading: '{{ __('admin.warning') }}',
                                    text: '{{ __('admin.something_error_message') }}',
                                    showHideTransition: 'slide',
                                    icon: 'warning',
                                    loaderBg: '#57c7d4',
                                    position: 'top-right'
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

        //Delete Supplier address by id
        $(document).on('click', '.deleteAddressDetails', function() {
            var id = $(this).attr("data-id");
            swal({
                title: "{{ __('admin.delete_sure_alert') }}",
                text: "{{ __('admin.department_delete_alert_text') }}",
                icon: "/assets/images/bin.png",
                buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    var _token = $('meta[name="csrf-token"]').attr("content");
                    var senddata = {
                        id: id,
                        _token: _token
                    }
                    $.ajax({
                        url: "{{ route('supplier-address-delete') }}",
                        type: 'POST',
                        data: senddata,
                        success: function(successData) {
                            $.toast({
                                heading: '{{ __('admin.success') }}',
                                text: '{{ __('admin.supplier_address_deleted') }}.',
                                showHideTransition: 'slide',
                                icon: 'success',
                                loaderBg: '#f96868',
                                position: 'top-right'
                            });
                            setInterval(function() {
                                window.location=('/admin/supplier-address-list');
                            }, 2000);
                        },
                        error: function() {
                            console.log('error');
                        }
                    });

                }
            });
        });

    </script>
@stop
