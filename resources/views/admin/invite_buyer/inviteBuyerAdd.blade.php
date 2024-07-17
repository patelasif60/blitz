@extends('admin/adminLayout')

@section('content')
    <meta name="csrf-token" content="{!! csrf_token() !!}">
    <div class="row">
        <div class="col-12 grid-margin ">
            <div class="row">
                <div class="col-md-12 d-flex align-items-center mb-3">
                    <h1 class="mb-0 h3">
                        <th>{{ __('admin.buyer') }}</th>
                    </h1>
                    <a href="{{ route('invite-buyer-list') }}" class="mb-2 backurl ms-auto btn-close"></a>
                </div>
                <div class="col-12">
                    <ul class="nav nav-tabs bg-white newversiontabs ps-3" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-0 active" id="home-tab" data-bs-toggle="tab"
                                    data-bs-target="#home" type="button" role="tab" aria-controls="home"
                                    aria-selected="true">{{ __('admin.invite_buyer') }}
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content pt-3 pb-0" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <form id="inviteAdd" class="" method="POST" enctype="multipart/form-data"
                                  action="{{ route('invite-buyer-create') }}" data-parsley-validate>
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <div class="card">
                                            <div class="card-header d-flex align-items-center">
                                                <h5 class="mb-0"><img height="20px"
                                                                      src="{{URL::asset('assets/icons/icon_invite.png')}}"
                                                                      alt="Designation Details" class="pe-2">
                                                    <span>{{ __('admin.invite') }}</span></h5>
                                            </div>
                                            <div class="card-body p-3 pb-1">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="email" class="form-label">{{ __('admin.email')}}
                                                            <span class="text-danger">*</span></label>
                                                        <input type="email" class="form-control" required
                                                               id="user_email" name="user_email"
                                                               data-parsley-uniqueemailuser>
                                                    </div>
                                                </div>
                                                <div class="row mt-3">
                                                    @if(auth()->user()->role_id == 1 || Auth::user()->hasRole('agent'))
                                                        <div class="col-md-6 mb-3">
                                                            <div>
                                                                <input type="radio" value="3" name="user_type"
                                                                       class="userType" id="invitebuyer_usertype"
                                                                       checked> {{ __('admin.supplier')}}&nbsp;&nbsp;
                                                                <input type="radio" value="2" name="user_type"
                                                                       class="userType"
                                                                       id="invitebuyer_usertype"> {{ __('admin.buyer')}}
                                                            </div>
                                                        </div>
                                                    @else
                                                        <input type="hidden" class="userType" id="invitebuyer_usertype"
                                                               name="user_type" value="3">
                                                    @endif
                                                </div>
                                                <div class="row">
                                                    @if(auth()->user()->role_id == 1 || Auth::user()->hasRole('agent'))
                                                        <div class="col-md-6 mb-3">
                                                            <select name="supplier_id" id="supplier_id"
                                                                    class="form-select selectBox"></select>
                                                        </div>
                                                    @else
                                                        <input type="hidden" class="form-control" id="supplier_id"
                                                               name="supplier_id"
                                                               value="{{ getSupplierIdByUser(auth()->user()->id) }}">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 bg-white py-3 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">{{ __('admin.invite')}}</button>
                                        <a href="{{ route('invite-buyer-list') }}"
                                           class="btn btn-cancel ms-3">{{ __('admin.cancel')}}</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('bottom_scripts')
    <script>
        $(document).ready(function () {
            var authLogin = {{ auth()->user()->role_id }};
            if (authLogin == 1) {
                $('#supplier_id').select2();
            }

            //page onload get list
            var radioValue = $('#invitebuyer_usertype').val();
            usertypeChangeBuyerSupplierList(radioValue);

            $("#inviteAdd").on('submit', function (event) {

                window.Parsley.addValidator('uniqueemailuser', {
                    validateString: function (value) {
                        let res = false;
                        xhr = $.ajax({
                            url: '{{ route('check-invite-user-email-exist') }}',
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                email: value,
                            },
                            async: false,
                            success: function (data) {
                                res = data;
                            },
                        });
                        return res;
                    },
                    messages: {
                        en: '{{ __('admin.email_already_exist') }}'
                    }
                });

                event.preventDefault();
                var formData = new FormData($("#inviteAdd")[0]);
                if ($('#inviteAdd').parsley().validate()) {
                    $.ajax({
                        url: $(this).attr('action'),
                        type: $(this).attr('method'),
                        data: formData,
                        contentType: false,
                        processData: false,

                        success: function (r) {
                            if (r.success == true) {
                                resetToastPosition();
                                $.toast({
                                    heading: "{{__('admin.success')}}",
                                    text: "{{ __('admin.invite_buyer_send_alert') }}",
                                    showHideTransition: "slide",
                                    icon: "success",
                                    loaderBg: "#f96868",
                                    position: "top-right",
                                });
                                setTimeout(function () {
                                    window.top.location = $(".backurl").attr('href')
                                }, 3000);
                            }
                        },
                        error: function (xhr) {
                            alert('{{__('admin.error_while_selecting_list')}}');
                        }
                    });
                }
            });
        });


        $(document).on('click', '.userType', function (e) {
            radioValue = $(this).val();
            usertypeChangeBuyerSupplierList(radioValue)
        });

        function usertypeChangeBuyerSupplierList(radioValue) {
            //console.log(radioValue);
            $.ajax({
                url: "{{ route('get-supplier-buyer-ajax', '') }}" + "/" + radioValue,
                type: "GET",
                success: function (successData) {
                    if (successData.radioValue == 2) {
                        var options = "<option selected disabled>Select Buyer</option>";
                    } else {
                        var options = "<option selected disabled>Select Supplier</option>";
                    }
                    if (successData.userData.length) {
                        successData.userData.forEach(function (data) {
                            options += '<option value="' + data.id + '" data-text="' + data.name + '" data-user-type="' + successData.radioValue + '">' + data.name + "</option>";
                        });
                    }
                    $("#supplier_id").empty().append(options);
                    //resolve('resolved');
                },
                error: function () {
                    console.log("error");
                },
            });
        }

    </script>
@endpush
