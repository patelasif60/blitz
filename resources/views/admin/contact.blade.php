@extends('admin/adminLayout')
@section('content')
    <div class="card h-100  bg-transparent newtable_v2">
        <div class="card-body p-0">
            <h4 class="card-title">{{ __('admin.contact')}}</th></h4>
            <div class="d-flex align-items-center justify-content-end">
                <input type="hidden" value="{!! csrf_token() !!}" name="_token">
            </div>
            <div class="row clearfix">
                <div class="col-12">
                    <div class="table-responsive newtable_v2">
                        <table id="contactTable" class="table table-hover">
                            <thead>
                            <tr>
                                <th class="hidden">{{ __('admin.id')}}</th>
                                <th> {{ __('home_latest.name')}}</th>
                                <th> {{ __('home_latest.company_name')}}</th>
                                <th> {{ __('home_latest.mobile')}}</th>
                                <th> {{ __('home_latest.email')}}</th>
                                <th> {{ __('home_latest.message')}}</th>
                                <th> {{ __('home_latest.user_type')}}</th>
                                <th> {{ __('admin.date')}}</th>
                                <th> {{ __('admin.action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($contactData) > 0)
                                @foreach ($contactData as $contact)
                                    <tr>
                                        @php
                                            $vr = Str::replace('" "', '', $contact->user_type);
                                        @endphp
                                        <td class="hidden">{{ $contact->id }}</td>
                                        <td>{{ $contact->fullname }}</td>
                                        <td>{{ $contact->company_name }}</td>
                                        <td>{{ $contact->mobile }}</td>
                                        <td>{{ $contact->email }}</td>
                                        <td>{{ $contact->message }}</td>
                                        <td>{{ $vr }}</td>
                                        <td>{{ date('d-m-Y H:i:s',strtotime($contact->created_at)) }}</td>
                                        <td>
                                            @if(!in_array($contact->email,$blockedContact))
                                            <a href = "javascript:void(0)" data-id="{{$contact->email}}" class="ps-2 blockContact" data-toggle="tooltip" ata-placement="top" title="{{__('admin.block')}}">
                                                <i class="fa fa-ban"></i>
                                            </a>
                                            @else
                                                <a href = "javascript:void(0)" data-id="{{$contact->email}}" class="ps-2 unblockContact" data-toggle="tooltip" ata-placement="top" title="{{__('admin.blocked')}}">
                                                    <i class="fa fa-ban" style="color: red"></i>
                                                </a>
                                            @endif

                                        </td>
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

    <script>
        $(document).ready(function() {
            $('#contactTable').DataTable({
                    "order": [
                        [0, "desc"]
                    ],
                "aLengthMenu": [
                    [10, 20, 50, -1],
                    [10, 20, 50, "All"]
                ],
                "iDisplayLength": 10,
            });
        });
        $(document).on('click', '.blockContact', function() {
                var id = $(this).attr("data-id");
                swal({
                    title: "{{ __('admin.delete_sure_alert') }}",
                    text: "{{ __('admin.block_contact_message') }}",
                    icon: "/assets/images/bin.png",
                    buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        var _token = $("input[name='_token']").val();
                        var senddata = {
                            id: id,
                            _token: _token
                        }
                        $.ajax({
                            url: "{{ route('block-contact-ajax') }}",
                            type: 'POST',
                            data: senddata,
                            success: function(successData) {
                                if(successData.success == true){
                                    $.toast({
                                        heading: '{{ __('admin.success') }}',
                                        text: '{{ __('admin.contact_blocked_success_message') }}',
                                        showHideTransition: 'slide',
                                        icon: 'success',
                                        loaderBg: '#f96868',
                                        position: 'top-right'
                                    })
                                    setTimeout(function() {
                                        location.reload();
                                    }, 3000);
                                }else{
                                    swal({
                                        text: '{{ __('admin.something_error_message	') }}',
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
        $(document).on('click', '.unblockContact', function() {
                var id = $(this).attr("data-id");
                swal({
                    title: "{{ __('admin.delete_sure_alert') }}",
                    text: "{{ __('admin.unblock_contact_message') }}",
                    icon: "/assets/images/bin.png",
                    buttons: ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        var _token = $("input[name='_token']").val();
                        var senddata = {
                            id: id,
                            _token: _token
                        }
                        $.ajax({
                            url: "{{ route('unblock-contact-ajax') }}",
                            type: 'POST',
                            data: senddata,
                            success: function(successData) {
                                if(successData.success == true){
                                    $.toast({
                                        heading: '{{ __('admin.success') }}',
                                        text: '{{ __('admin.contact_unblocked_success_message') }}',
                                        showHideTransition: 'slide',
                                        icon: 'success',
                                        loaderBg: '#f96868',
                                        position: 'top-right'
                                    })
                                    setTimeout(function() {
                                        location.reload();
                                    }, 3000);
                                }else{
                                    swal({
                                        text: '{{ __('admin.something_error_message	') }}',
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
    </script>
@stop
