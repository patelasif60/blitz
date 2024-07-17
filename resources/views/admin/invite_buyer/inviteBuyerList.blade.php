@extends('admin/adminLayout')
@push('bottom_head')
    <meta name="csrf-token" content="{!! csrf_token() !!}">
    <link href="{{ URL::asset('/assets/css/admin/filter.css') }}" rel="stylesheet">
@endpush
@section('content')
    <div class="card h-100 bg-transparent newtable_v2">
        <div class="card-body p-0 ">
            <div class="d-flex align-items-center">
                <h4 class="card-title">{{ __('admin.buyer') }}</h4>
                <div class="d-flex align-items-center justify-content-end ms-auto">
                    @if(auth()->user()->role_id == 1  || Auth::user()->hasRole('agent'))
                    <div class="dropdown ms-auto me-1">
                        <div class="pe-1 mb-3 clearfix d-flex align-items-center">
						<span class="text-muted me-2" id="filter_count_one" style="font-size: 0.7rem; padding: 0.25rem 0.5rem;">
							0 {{__('admin.filter_applied')}}
						</span>
                            <div class="nav-item nav-settings" >
                                <a class="btn btn-dark btn-sm" style="padding: 0.25rem 0.5rem;" href="javascript:void(0)">{{__('admin.filters')}}</a>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="pe-1 mb-3 clearfix">
                        @can('create invite buyer')
                        <a href="{{ route('invite-buyer-add') }}" type="button"
                           class="btn btn-primary btn-sm">{{ __('admin.invite_buyer') }}</a>
                        @endcan
                    </div>
                    <input type="hidden" value="{!! csrf_token() !!}" name="_token">
                </div>
            </div>

            <!-- ---Filter---- -->
            @if(auth()->user()->role_id == 1 || Auth::user()->hasRole('agent'))
            <div id="right-sidebar" class="settings-panel filter shadow-lg" style="z-index: 5;">
                <div class="d-flex p-2 align-items-center position-sticky shadow">
                    <h4 class="mt-2 fw-bold">{{__('admin.filters')}}</h4>
                    <span class="badge badge-outline-primary ms-2" id="filter_count_two" style="padding: 0.125em 0.25em;">0</span>
                    <span class="ms-auto">
							<a href="javascript:void(0)" id="clear_btn" class="btn btn-sm btn-dark  p-1 pe-1" style="font-size: 0.8em; margin-right: 0.2rem;" type="button" role="button">
								{{__('admin.clear')}}
							</a>
							<a id="apply_btn" href="javascript:void(0)" class="btn btn-primary btn-sm  p-1" style="font-size: 0.7em; margin-right: 1.75rem;" role="button">
								{{__('admin.apply')}}
							</a>
						</span>
                    <a id="filter_close"><i class="settings-close mdi mdi-close"></i></a>
                </div>

                <div class="tab-content h-auto" id="setting-content" style="padding-top: 10px; ">
                    <div class="tab-pane fade show active scroll-wrapper" id="todo-section"
                         role="tabpanel" aria-labelledby="todo-section">
                        <div class="d-flex px-3 mb-0">
                            <form class="form w-100 d-none">
                                <div class="form-group d-flex ">
                                    <input type="text" class="form-control todo-list-input" placeholder="{{__('admin.search')}}">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fa fa-search"></i></button>
                                </div>
                            </form>
                        </div>
                        <div class="list-wrapper px-2 py-2" style="overflow-y: auto;">
                            <div class="accordion accordion-solid-header" id="accordion-4"
                                 role="tablist">
                                {{--user filter--}}
                                <div class="card filter-card">
                                    <div class="card-header" role="tab" id="heading-1">
                                        <h6 class="mb-0">
                                            <a data-bs-toggle="collapse" href="#collapse-1"
                                               aria-expanded="false" aria-controls="collapse-1"
                                               class="collapsed">
                                                {{__('admin.users')}}
                                            </a>
                                        </h6>
                                    </div>
                                    <div id="collapse-1" class="collapse" role="tabpanel"
                                         aria-labelledby="heading-1" style="">
                                        <div class="card-body py-1 pe-1">
                                            <input class="form-control form-control-sm mb-2 filtersearch" id="user_filtersearch" type="text" placeholder="{{__('admin.search')}}..">
                                            <div id="user_content">
                                                @if(sizeof($users) > 0)
                                                    @foreach($users as $key => $value)
                                                        <div class="form-check d-flex align-items-center user_content">
                                                            <input type="checkbox" class="form-check-input user_name_checkbox" id="user_id{{$value['user_id']}}" data-id="{{$value['user_id']}}">
                                                            <label class="form-check-label" for="user_id{{$value['user_id']}}">{{$value['user_name']}}</label>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{--user type filter--}}
                                <div class="card filter-card">
                                    <div class=" card-header" role="tab" id="heading-2">
                                        <h6 class="mb-0">
                                            <a class="collapsed" data-bs-toggle="collapse"
                                               href="#collapse-2" aria-expanded="false"
                                               aria-controls="collapse-2">
                                                {{__('admin.user_type')}}
                                            </a>
                                        </h6>
                                    </div>
                                    <div id="collapse-2" class="collapse" role="tabpanel"
                                         aria-labelledby="heading-2">
                                        <div class="card-body py-1 pe-1">
                                            <input class="form-control form-control-sm mb-2 filtersearch"
                                                   id="usertype_filtersearch" type="text" placeholder="{{__('admin.search')}}..">
                                            <div id="usertype_content">
                                                @if(sizeof($user_types) > 0)
                                                    @foreach($user_types as $key => $value)
                                                        <div class="form-check d-flex align-items-center">
                                                            <input type="checkbox" class="form-check-input user_type_checkbox" id="user_type{{$value['user_type']}}" data-id="{{$value['user_type']}}">

                                                            @if($value['user_type'] == 1)
                                                                <label class="form-check-label text-start" for="user_type{{$value['user_type']}}">Admin</label>
                                                            @elseif($value['user_type'] == 2)
                                                                <label class="form-check-label text-start" for="user_type{{$value['user_type']}}">User</label>
                                                            @elseif($value['user_type'] == 3)
                                                                <label class="form-check-label text-start" for="user_type{{$value['user_type']}}">Supplier</label>
                                                            @elseif($value['user_type'] == 5)
                                                                <label class="form-check-label text-start" for="user_type{{$value['user_type']}}">Agent</label>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{--status filter--}}
                                <div class="card filter-card">
                                    <div class=" card-header" role="tab" id="heading-10">
                                        <h6 class="mb-0">
                                            <a class="collapsed" data-bs-toggle="collapse"
                                               href="#collapse-10" aria-expanded="false"
                                               aria-controls="collapse-10">
                                                {{__('admin.status')}}
                                            </a>
                                        </h6>
                                    </div>
                                    <div id="collapse-10" class="collapse" role="tabpanel"
                                         aria-labelledby="heading-10">
                                        <div class="card-body py-2">
                                            <input class="form-control form-control-sm mb-2 filtersearch"
                                                   id="status_filtersearch" type="text" placeholder="{{__('admin.search')}}..">
                                            <div id="status_content">
                                                @if(sizeof($status) > 0)
                                                    @foreach($status as $key => $value)
                                                        <div class="form-check d-flex align-items-center">
                                                                <input type="checkbox" class="form-check-input status_checkbox" id="status{{$value['status']}}" data-id="{{$value['status']}}">

                                                                @if($value['status'] == 0)
                                                                    <label class="form-check-label text-start" for="status{{$value['status']}}">Pending </label>
                                                                @elseif($value['status'] == 1)
                                                                    <label class="form-check-label text-start" for="status{{$value['status']}}">Active</label>
                                                                @else
                                                                    <label class="form-check-label text-start" for="status{{$value['status']}}">link expired</label>
                                                                @endif
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{--date filter--}}
                                <div class="card filter-card">
                                    <div class=" card-header" role="tab" id="heading-6">
                                        <h6 class="mb-0">
                                            <a class="collapsed" data-bs-toggle="collapse"
                                               href="#collapse-6" aria-expanded="false"
                                               aria-controls="collapse-6">
                                                {{__('admin.date')}}
                                            </a>
                                        </h6>
                                    </div>
                                    <div id="collapse-6" class="collapse" role="tabpanel"
                                         aria-labelledby="heading-6">
                                        <div class="card-body py-2">
                                            <div class="input-group input-daterange">
                                                <input type="text" class="form-control ps-2" value="" readonly id="start_date">
                                                <div class="input-group-addon ps-2 pe-2">to</div>
                                                <input type="text" class="form-control pe-2" value="" readonly id="end_date">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <!-- ---Filter---- -->

            <div class="row clearfix">
                <div class="col-12">
                    <div class="table-responsive ">
                        <table id="inviteBuyer-listing" class="table table-hover">
                            <thead>
                            <tr>
                                <th hidden>{{ __('admin.id')}}</th>
                                <th>{{ __('admin.email')}}</th>
                                @if(auth()->user()->role_id == 1 || Auth::user()->hasRole('agent'))
                                    <th>{{ __('admin.users') }}</th>
                                    <th>{{ __('admin.user_type') }}</th>
                                @endif
                                <th>{{ __('admin.status') }}</th>
                                <th>{{ __('admin.date') }}</th>
                                <th class="text-center">{{ __('admin.action')}}</th>
                            </tr>
                            </thead>
                            <tbody id="inviteBuyerData">
                            {{--
                                                        @if (count($invitebuyer) > 0)
                                                            @foreach ($invitebuyer as $invitation)
                                                                <tr>
                                                                    <td hidden>{{ $invitation->id }}</td>
                                                                    <td>{{ $invitation->user_email }}</td>
                                                                    @if(auth()->user()->role_id == 1)
                                                                        <td>{{ $invitation->user_name }}</td>
                                                                        <td>{{ ucfirst($invitation->user_type_name) }}</td>
                                                                    @endif
                                                                    <td>
                                                                        @if($invitation->status == 0){{ __('admin.pending') }}
                                                                        @elseif($invitation->status == 1) {{ __('admin.active') }}
                                                                        @else{{ __('admin.link_expired')}}
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ date('d-m-Y',strtotime($invitation->date)) }}</td>
                                                                    <td class="text-end text-nowrap">
                                                                        @if ($invitation->status != 1)
                                                                            <a href="javascript:void(0)" id="resendInviteBuyer_{{ $invitation->id }}" class="resendInviteBuyer ps-2 me-1" data-toggle="tooltip" ata-placement="top" title="{{__('admin.resend')}}"><i class="fa fa-send" aria-hidden="true"></i></a>
                                                                        @endif
                                                                        @if ($invitation->status != 1)
                                                                            <a href="{{ route('invite-buyer-edit', ['id' => Crypt::encrypt($invitation->id)]) }}" class="show-icon" data-toggle="tooltip" ata-placement="top" title="{{__('admin.edit')}}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                                                        @endif

                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            {{-- --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            var inviteBuyerDataHtml = @json($inviteBuyerDataHtml);
            $('#inviteBuyerData').html(inviteBuyerDataHtml);

            $('#inviteBuyer-listing').DataTable({
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

            $(document).on('click', '.resendInviteBuyer', function () {
                var id = $(this).attr('id').split("_")[1];
                swal({
                    {{--title: "{{ __('admin.are_you_sure_to_send_invitation') }}",--}}
                    text: "{{ __('admin.are_you_sure_to_send_invitation') }}",
                    icon: "/assets/images/info.png",
                    buttons: ['{{ __('admin.no') }}', '{{ __('admin.yes') }}'],
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
                                url: '{{ route('invite-buyer-resend') }}',
                                type: 'POST',
                                data: senddata,
                                success: function (successData) {
                                    new PNotify({
                                        text: '{{ __('admin.invitation_send_successfully') }}',
                                        type: 'success',
                                        styling: 'bootstrap3',
                                        animateSpeed: 'slow',
                                        delay: 3000,
                                    });
                                    location.reload();

                                },
                                error: function () {
                                    console.log('error');
                                }
                            });

                        }
                    });

            });

        //Filter start

            $(document).on('click','#clear_btn', function(){
                resetFilters();
                let emptyData = {};
                reinitializeDataTable(emptyData);
            });

            $(document).on('click','#apply_btn', function(){
                let data = getOrderRequestData();
                reinitializeDataTable(data);
            });

            function getOrderRequestData(){
                let data = {};
                let user_ids = [];
                let filterCount = 0;
                let checkedUser = $('.user_name_checkbox:checkbox:checked');
                $.each(checkedUser, function(key, value){
                    user_ids.push($(value).attr('data-id'));
                });
                if(user_ids.length > 0){
                    data.user_ids = user_ids;
                    filterCount++;
                }

                let usertype_ids = [];
                let checkedUserType = $('.user_type_checkbox:checkbox:checked');
                $.each(checkedUserType, function(key, value){
                    usertype_ids.push($(value).attr('data-id'));
                });
                if(usertype_ids.length > 0){
                    data.usertype_ids = usertype_ids;
                    filterCount++;
                }

                let status_ids = [];
                let checkedStatus = $('.status_checkbox:checkbox:checked');
                $.each(checkedStatus, function(key, value){
                    status_ids.push($(value).attr('data-id'));
                });
                if(status_ids.length > 0){
                    data.status_ids = status_ids;
                    filterCount++;
                }

                let start_date = $('#start_date').val();
                let end_date = $('#end_date').val();
                let date_range = [];
                if(start_date && end_date){
                    data.start_date = start_date;
                    data.end_date = end_date;
                    filterCount++;
                }

                $('#filter_count_one').text(filterCount+" {{__('admin.filter_applied')}}");
                $('#filter_count_two').text(filterCount);
                return data;
            }

            function reinitializeDataTable(data){
                $.ajax({
                    url: "{{ route('invite-buyer-list') }}",
                    data: data,
                    type: 'GET',
                    success: function(successData) {
                        console.log(successData);
                        $('#inviteBuyer-listing').DataTable().destroy();
                        $('#inviteBuyerData').html(successData);
                        $('#inviteBuyer-listing').DataTable({
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
                        }).draw();
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }

            function resetFilters(){

                /* clear search input and reset div elements */
                $("#user_filtersearch").val("");
                searchFilterContent($("#user_filtersearch"), '#user_content div');

                $("#usertype_filtersearch").val("");
                searchFilterContent($("#usertype_filtersearch"), '#usertype_content div');

                $("#status_filtersearch").val("");
                searchFilterContent($("#status_filtersearch"), '#status_content div');

                /* clear search input and reset div elements */

                $(".user_name_checkbox").prop( "checked", false);
                $(".user_type_checkbox").prop( "checked", false);
                $(".status_checkbox").prop( "checked", false);

                $('#filter_count_one').text("0 {{__('admin.filter_applied')}}");
                $('#filter_count_two').text(0);

                /* clear date and reset datepicker */
                $("#start_date").val("");
                $("#end_date").val("");
                $('.input-daterange .form-control').datepicker('clearDates');
                /* clear date and reset datepicker */
            }

            function searchFilterContent(currentElement, ChildElement){
                // Retrieve the input field text and reset the count to zero
                var filter = currentElement.val(), count = 0;
                // Loop through the comment list
                $(ChildElement).each(function() {
                    // If the list item does not contain the text phrase fade it out
                    if ($(this).find("label").text().search(new RegExp(filter, "i")) < 0) {
                        $(this).addClass('d-none');
                        $(this).removeClass('d-flex');
                        $(this).find("label").addClass('d-flex')
                        // Show the list item if the phrase matches and increase the count by 1
                    } else {
                        $(this).removeClass('d-none');
                        $(this).addClass('d-flex');
                        $(this).find("label").removeClass('d-flex')
                        count++;
                    }
                });
            }

            $("#user_filtersearch").keyup(function() {
                searchFilterContent($(this), '#user_content div');
            });

            $("#usertype_filtersearch").keyup(function() {
                searchFilterContent($(this), '#usertype_content div');
            });

            $("#status_filtersearch").keyup(function() {
                searchFilterContent($(this), '#status_content div');
            });

        //filter end

        });

    </script>
@stop
