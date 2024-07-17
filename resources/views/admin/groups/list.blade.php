@extends('admin/adminLayout')
@section('content')
    <style>
        /* .tooltiphtml .tooltip_section {
            visibility: hidden;
            opacity: 0;
            transition: visibility 0s linear .8s, opacity .8s;
        }

        .tooltiphtml:hover .tooltip_section {
            visibility: visible;
            opacity: 1;
            transition: visibility 0s linear 0s, opacity .8s;
        }

        .tooltiphtml {
            position: relative;
            height: 12px;
        }

        .tooltip_section {
            position: absolute;
            left: -45px;
            background: black;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: 0 10px 2rem rgba(0, 0, 0, .2);
            bottom: 0px;
            bottom: 1.5rem;
        }

        .tooltip_section:before{
            position: absolute;
            left: 48%;
            bottom: -5px;
            content: '';
            border-top: 5px solid black;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
        } */

        #social-links ul{
        padding: 0px 20px;
        display: grid;
        grid-template-columns: auto auto auto auto auto;
    }
    #social-links ul li {
        padding: 5px 20px;
        list-style: none;
    }
    #social-links ul li a {
        padding: 6px;
        border-radius: 5px;
        margin: 1px;
        font-size: 36px;
    }
    #social-links .fa-facebook{
        color: #0d6efd;
    }
    #social-links .fa-twitter{
        color: deepskyblue;
    }
    #social-links .fa-linkedin{
        color: #0e76a8;
    }
    #social-links .fa-whatsapp{
        color: #25D366
    }
    #social-links .fa-reddit{
        color: #FF4500;;
    }
    #social-links .fa-telegram{
        color: #0088cc;
    }

    </style>
    <div class="card h-100 bg-transparent newtable_v2">
        <div class="card-body p-0 ">
            <div class="d-flex align-items-center">
                <h4 class="card-title">{{ __('admin.groups')}}</h4>
                <div class="d-flex align-items-center justify-content-end ms-auto">
                    <div class="pe-1 mb-3 clearfix">
                        <a href="{{ route('group-add') }}" type="button" class="btn btn-primary btn-sm">{{ __('admin.create_group') }}</a>
                    </div>
                    <input type="hidden" value="{!! csrf_token() !!}" name="_token">
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-12">
                    <div class="table-responsive ">
                        <table id="group-listing" class="table table-hover">
                            <thead>
                            <tr>
                                <th hidden>groupID</th>
                                <th>{{ __('admin.group_number') }}</th>
                                <th>{{ __('admin.group_name') }}</th>
                                <th>{{ __('admin.supplier_name') }}</th>
                                <th>{{ __('admin.product_name') }}</th>
                                <th>{{ __('admin.min_discount') }}</th>
                                <th>{{ __('admin.max_discount') }}</th>
                                <th>{{ __('admin.target_qty') }}</th>
                                <th>{{ __('admin.achieve_qty') }}</th>
                                <th>{{ __('admin.status') }}</th>
                                <th>{{ __('admin.exp_date') }}</th>
                                <th>{{ __('admin.buyers') }}</th>
                                <th class="text-center">{{ __('admin.action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($lists) > 0)
                                @foreach($lists as $list)

                                    <tr>
                                        <td hidden>{{ $list->id }}</td>
                                        <td>{{ $list->group_number }}</td>
                                        <td>{{ $list->name }}</td>
                                        <td>{{ $list->supplier_name }}</td>
                                        <td>{{ $list->category_name . ' / ' . $list->sub_category_name. ' / ' .  $list->product_name}}</td>
                                        <td>{{ $list->min_discount}}</td>
                                        <td>{{ $list->max_discount}}</td>
                                        <td>{{ $list->target_quantity }}</td>
                                        <td>{{ $list->achieved_quantity }}</td>
                                        <td>
                                            @if($list->group_status==1)
                                                <span class="badge badge-pill badge-info w-100">
                                                    {{$list->group_status_name}}{{--{{ __('order.'.trim($order->order_status_name)) }}--}}
                                                </span>
                                            @elseif($list->group_status==2)
                                                <span class="badge badge-pill badge-warning w-100">
                                                    {{$list->group_status_name}}
                                                </span>
                                            @elseif($list->group_status==3)
                                                <span class="badge badge-pill badge-success  w-100">
                                                    {{$list->group_status_name}}
                                                </span>
                                            @else
                                                <span class="badge badge-pill badge-danger w-100">
                                                    {{$list->group_status_name}}
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ date('d-m-Y', strtotime($list->end_date)) }}</td>
                                        @php
                                            $buyerCount=0;
                                            foreach($list->groupMembersMultiple as $buyer) {
                                               $buyerCount++;
                                            }
                                        @endphp

                                        <td> {{$buyerCount ?? '0'}} </td>
                                        <td class="text-end text-nowrap">
                                            <!-- @php
                                            $link = url('groups/'.$list->social_token);
                                            @endphp -->
                                            @php
                                                if(($list->target_quantity == $list->achieved_quantity)) {
                                                    $notEditable = 'd-none';
                                                } else {
                                                    $notEditable = '';
                                                }
                                            @endphp

                                            <div class="tooltiphtml d-inline-block">
                                                <a href="javascript:void(0)" class="show-icon pe-1 shr_btn {{ $notEditable }}"   title="{{ __('admin.share') }}" data-share-group-link="{{ route('group-details',['id' => Crypt::encrypt($list->id)]) }}" data-share-group-link-html="{{ shareGroupLink(route('group-details',['id' => Crypt::encrypt($list->id)])) }}">
                                                    <i class="fa fa-share-alt " aria-hidden="true"></i>
                                                </a>
                                            <!-- </div> -->

                                            <a href="javascript:void(0);" class="pe-2 cursor-pointer viewGroupDetail" data-id="{{ $list->id }}" data-toggle="tooltip" data-placement="top" title="{{ __('admin.view') }}"><i class="fa fa-eye"></i></a>

                                            <a href="{{ route('group-edit', ['id' => Crypt::encrypt($list->id)]) }}" class="show-icon pe-2 {{ $notEditable }}" data-toggle="tooltip" ata-placement="top" title="{{__('admin.edit')}}" data-bs-original-title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>

                                            <a href="javascript:void(0)" id="deletegroup_{{ $list->id }}" class="show-icon deleteGroup {{ $notEditable }}" data-toggle="tooltip" ata-placement="top"  title="{{__('admin.delete')}}" data-bs-original-title="{{__('admin.delete')}}"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
    {{--view Model--}}
    <div class="modal version2 fade" id="viewGroupModal" tabindex="-1" role="dialog"
         aria-labelledby="viewGroupModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal version2 fade" id="viewGroupModal" tabindex="-1" role="dialog"
                     aria-labelledby="viewGroupModalLabel" aria-hidden="true">
                </div>
            </div>
        </div>
    </div>
    <!-- Share on social media Modal -->
    <div class="modal fade newtable_v2" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header py-3">
                    <h5 class="modal-title text-white" id="exampleModalLabel">{{__('dashboard.share_via')}}</h5>
                    <button type="button" class="btn-close ms-0 d-flex" data-bs-dismiss="modal" aria-label="Close">
                        <img src="{{URL::asset('front-assets/images/icons/times.png')}}" alt="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <h6 class="text-center mb-3">{{__('dashboard.share_group_social_media')}}</h6>
                    <div class="social-btn-sp" id="social-btn-html"></div>
                    <hr>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control h-100" style="border: 1px solid #B5B5B5;" id="groupLink" readonly aria-label="Group Link" aria-describedby="basic-addon2">
                        <button type="button" class="input-group-text btn-primary text-white" id="copyBtn">
                            <span class="">{{__('dashboard.copy')}} <i class="fa fa-copy ms-2" style="font-size: 16px;"></i></span>
                        </button>
                    </div>
                    <div class="copied"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <script>
        $(document).ready(function() {
            $('#group-listing').DataTable({
                "order": [
                    [0, "desc"]
                ],
                "aLengthMenu": [
                    [10, 20, 50, -1],
                    [10, 20, 50, "All"]
                ],
                "iDisplayLength": 10,
                'columnDefs': [{
                    'targets': [1, 7, 8, 9, 12], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }, {
                    "targets": [0],
                    "visible": false,
                    "searchable": false
                }]
            });
            var popupSize = {
                width: 780,
                height: 550
            };

            $(document).on('click', '.social-button', function (e) {
                var verticalPos = Math.floor(($(window).width() - popupSize.width) / 2),
                    horisontalPos = Math.floor(($(window).height() - popupSize.height) / 2);

                var popup = window.open($(this).prop('href'), 'social',
                    'width=' + popupSize.width + ',height=' + popupSize.height +
                    ',left=' + verticalPos + ',top=' + horisontalPos +
                    ',location=0,menubar=0,toolbar=0,status=0,scrollbars=1,resizable=1');

                if (popup) {
                    popup.focus();
                    e.preventDefault();
                }
            });

            $(document).on('click', '.deleteGroup', function () {
                var id = $(this).attr('id').split("_")[1];
                swal({
                    title: "{{  __('admin.categories_delete_alert') }}",
                    text: "{{  __('admin.group_delete_message') }}",
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
                                url: '{{ route('group-delete') }}',
                                type: 'POST',
                                data: senddata,
                                success: function (successData) {
                                    console.log(successData);
                                    if(successData.success == false) {
                                        new PNotify({
                                            text: "{{  __('admin.buyer_place_rfq') }}",
                                            type: 'error',
                                            styling: 'bootstrap3',
                                            animateSpeed: 'fast',
                                            delay: 2000
                                        });
                                    }else{
                                        new PNotify({
                                            text: "{{  __('admin.group_deleted') }}",
                                            type: 'success',
                                            styling: 'bootstrap3',
                                            animateSpeed: 'fast',
                                            delay: 1000
                                        });
                                    }
                                    setInterval(function() {
                                        // window.location=('/admin/products');
                                        location.reload();
                                    }, 2000);
                                },
                                error: function () {
                                    console.log('error');
                                }
                            });

                        }
                    });

            });

           $(document).on('click', '.viewGroupDetail', function(e) {
               // $('#callRFQHistory').html('');
               // $('#message').html('');
               e.preventDefault();
                var id = $(this).attr('data-id');
                if (id) {
                    $.ajax({
                        url: "{{ route('group-detail', '') }}" + "/" + id,
                        type: 'GET',
                        success: function(successData) {
                            $('#viewGroupModal').find('.modal-content').html(successData.groupview);
                            $('#viewGroupModal').modal('show');
                        },
                        error: function() {
                            console.log('error');
                        }
                    });
                }
           });

        });

    //Copy group link on click of "copy button"
    function copyGroupLink() {
        var copyText = document.getElementById("groupLink");
        copyText.select();
        navigator.clipboard.writeText(copyText.value);
        $(".copied").text("Copied to clipboard").show().fadeOut(2000);
    }

    $(document).on('click', '.shr_btn', function() {
        $('#social-btn-html').html($(this).attr('data-share-group-link-html'));
        $('#groupLink').val($(this).attr('data-share-group-link'));
        $('#exampleModal').modal('show');
    });

    $(document).on('click', '#copyBtn', function() {
        var copyText = document.getElementById("groupLink");
        copyText.select();
        navigator.clipboard.writeText(copyText.value);
        $(".copied").text("Copied to clipboard").show().fadeOut(2000);
    });

    </script>
@stop
