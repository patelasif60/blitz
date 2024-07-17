@extends('admin/adminLayout')

@section('content')
    <meta name="csrf-token" content="{!! csrf_token() !!}">
<div class="row">
    <div class="col-12 grid-margin ">
        <div class="row">
            <div class="col-md-12 d-flex align-items-center mb-3">
                <h1 class="mb-0 h3"><th>{{ __('admin.supplier') }}</th></h1>
                <a href="{{ route('invite-supplier-list') }}" class="mb-2 backurl ms-auto btn-close"></a>
            </div>

            <div class="col-12">
                <ul class="nav nav-tabs bg-white newversiontabs ps-3" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-0 active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">{{ __('admin.invite_supplier') }}
                        </button>
                    </li>

                </ul>
                <div class="tab-content pt-3 pb-0" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <form id="inviteSupplierAdd" class="" method="POST" enctype="multipart/form-data" action="{{ route('invite-supplier-create') }}" data-parsley-validate>
                            @csrf
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <div class="card">
                                        <div class="card-header d-flex align-items-center">
                                            <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/icon_invite.png')}}" alt="Designation Details" class="pe-2"> <span>{{ __('admin.invite') }}</span></h5>
                                        </div>
                                        <div class="card-body p-3 pb-1">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="email" class="form-label">{{ __('admin.email')}} <span class="text-danger">*</span></label>
                                                    <input type="email" class="form-control" required id="user_email" name="user_email"  data-parsley-uniqueemailsupplier>
                                                </div>
                                            </div>
                                            <div class="row">
                                                @if(auth()->user()->role_id == 1 || Auth::user()->hasRole('agent'))
                                                    <div class="col-md-6 mb-3">
                                                        <label for="user_id selectBox" class="form-label">{{ __('admin.buyer')}} </label>
                                                        <select name="user_id" id="user_id" class="form-select selectBox">
                                                            <option selected value="0">Select Buyer</option>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @else
                                                    <input type="hidden" class="form-control" id="user_id" name="user_id" value="{{ auth()->user()->id }}">
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-12 bg-white py-3 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">{{ __('admin.invite')}}</button>
                                    <a href="{{ route('invite-supplier-list') }}" class="btn btn-cancel ms-3">{{ __('admin.cancel')}}</a>
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

@section('scripts')
<script>
    $(document).ready(function() {
        $('#user_id').select2();

        $("#inviteSupplierAdd").on('submit', function(event) {

            window.Parsley.addValidator('uniqueemailsupplier', {
                validateString: function (value) {
                    let res = false;
                    xhr = $.ajax({
                        url: '{{ route('check-invite-user-email-exist') }}',
                        //headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        dataType: 'json',
                        method: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            email: value,
                        },
                        async: false,
                        success: function(data) {
                            res = data;
                        },
                    });
                    //console.log(res);
                    return res;
                },
                messages: {
                    en: '{{ __('admin.email_already_exist') }}'
                },
                priority: 32
            });

            event.preventDefault();
            var formData = new FormData($("#inviteSupplierAdd")[0]);
            if ($('#inviteSupplierAdd').parsley().validate()) {
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: formData,
                    contentType: false,
                    processData: false,

                    success: function(r) {
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
                            setTimeout(function() {window.top.location = $(".backurl").attr('href')}, 3000);
                        }
                    },
                    error: function(xhr) {
                        alert('{{__('admin.error_while_selecting_list')}}');
                    }
                });
            }
        });
    });
</script>
@stop
