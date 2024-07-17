@extends('buyer/layouts/backend/backend_layout')

    @section('css')
    <link href="{{ URL::asset('front-assets/js/front/crop/css/style.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/js/front/crop/css/style-example.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/js/front/crop/css/jquery.Jcrop.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('front-assets/css/front/croppie.min.css') }}" rel='stylesheet' />
    <link href="{{ URL::asset('front-assets/js/datatables-bs4/dataTables.bootstrap4.css') }}" rel='stylesheet' />

    @endsection

    @section('custom-css')
    <style>
        .error {
            color: red;
        }

        #userCompanyDetailsForm .select2-hidden-accessible {
            width: 100% !important;
            height: 50px !important;
            position: relative !important;
            visibility: hidden;
        }

        #product_category_block .select2-container {
            width: 100% !important;
        }
        .swal-button--danger {
            background-color: #df4740 !important;
        }
    </style>
    @endsection

    @section('content')

    <div class="col-lg-12 py-2">
        <div class=" border border-radius">
            <div class="tab-content" id="myTabContent">
                <!--begin: Profile Section-->
                <div class="tab-pane fade show " id="personal" aria-labelledby="personal-tab">
                    <div class="row mx-0">
                        @include('buyer/common/sidebar/backend/profile_sidebar')
                    </div>
                </div>
                <!--end: Profile Section-->

                <!--begin: Admin Settings Section-->
                <div class="tab-pane fade show " id="company" role="tabpanel" aria-labelledby="company-tab">
                    <div class="row mx-0">
                        @include('buyer/common/sidebar/backend/settings_sidebar')
                        <div class="col-md-8 col-lg-9">
                            <div class="tab-content" id="myTabContent">

                                <!--begin: Roles and Permissions-->
                                <div class="tab-pane fade active show" id="rolestabs" role="tabpanel" aria-labelledby="rolestabs">
                                    <div class="row mx-0">
                                        <div class="col-md-12 p-4 mt-md-2 ">
                                            <div class=" mb-3 d-flex align-items-center">
                                                <h5>{{ __('admin.roles') }}</h5>
                                                <a href="{{route('settings.roles.create')}}" class="btn btn-success ms-auto btn-sm plusicon" id="addRoleBtn">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 20 20">
                                                        <path id="icon_plus_pro" d="M18.571,39.857H12.143V33.429A1.429,1.429,0,0,0,10.714,32H9.286a1.429,1.429,0,0,0-1.429,1.429v6.429H1.429A1.429,1.429,0,0,0,0,41.286v1.429a1.429,1.429,0,0,0,1.429,1.429H7.857v6.429A1.429,1.429,0,0,0,9.286,52h1.429a1.429,1.429,0,0,0,1.429-1.429V44.143h6.429A1.429,1.429,0,0,0,20,42.714V41.286A1.429,1.429,0,0,0,18.571,39.857Z" transform="translate(0 -32)"></path>
                                                    </svg> {{__('admin.add')}}
                                                </a>
                                            </div>
                                            <div class="tablepermission p-1">
                                                <table class="table table-responsive" style="width:100%"></table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end: Roles and Permissions-->
                            </div>
                        </div>
                    </div>
                </div>
                <!--end: Admin Settings Section-->
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
    @include('buyer.common.footer.backend.footer')

    @endsection

    @section('script')
    <!--begin: plugin js for this page -->
    <script type="text/javascript" src="{{ URL::asset('front-assets/js/front/crop/scripts/jquery.Jcrop.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('front-assets/js/front/crop/scripts/jquery.SimpleCropper.js') }}"></script>
    <script type="text/javascript" src='{{ URL::asset("front-assets/js/front/croppie.js")}}'></script>
    <script type="text/javascript" src="{{ URL::asset('front-assets/js/datatables/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('front-assets/js/datatables-bs4/dataTables.bootstrap4.js') }}"></script>
    <!--end: plugin js for this page -->

    @endsection

    @section('custom-script')
    <script type="text/javascript">
        /****************************************begin:Buyer Backend Sidebar***************************************/
        var SnippetRolesPermissionsTable = function(){

            var rolesDatatable = function () {
                $('.table').DataTable({
                    serverSide: !0,
                    scrollX: !0,
                    paginate: !0,
                    lengthMenu: [
                        [10, 25, 50],
                        [10, 25, 50],
                    ],
                    scrollX:!0,
                    footer:!1,
                    ajax: {
                        url : "{{  route('settings.roles.index')  }}",
                        method : "GET"
                    },
                    columns: [
                        {data: "name", title: "{{ __('admin.name') }}", sortable:!0},

                        {data: "created_by", title: "{{ __('rfqs.created_by') }}", sortable:!0},

                        {data: "approval_process", title: "{{ __('module.Approval') }}", sortable:!0},

                        {data: "action", title: "{{ __('profile.action') }}", sortable:!1, width:150}

                    ]
                });
            },

            editRole = function () {

                $(document).on( 'click', '.editRoles', function () {
                    let id = $(this).attr('data-id');
                    location.replace("roles/"+id+"/edit");

                });

            },

            removeRole = function () {
                $(document).on( 'click', '.removeRoles', function () {

                    let role    = $(this).attr('data-id');
                    let url     = "{{ route('settings.roles.destroy',":role") }}";
                    url         = url.replace(':role', role);

                    if (checkRole(role) ) {
                        swal({

                            title       : "{{ __('dashboard.are_you_sure') }}?",
                            text        : "{{ __('dashboard.delete_warning') }}",
                            icon        : "/assets/images/bin.png",
                            buttons     : ['{{ __('admin.cancel') }}', '{{ __('admin.ok') }}'],
                            dangerMode  : true

                        }).then((confirm) => {
                            if (confirm) {
                                $.ajax({
                                    url     : url,
                                    type    : 'DELETE',
                                    dataType: "json",
                                    data    : {
                                        "role"      :   role,
                                        "_token"    :   "{{ csrf_token() }}"
                                    },
                                    success : function(data) {

                                        if (data.type == 'info') {
                                            swal({
                                                title       : "{{ __('dashboard.info') }}",
                                                text        : data.message,
                                                icon        : "/assets/images/info.png",
                                            })
                                        } else {
                                            new PNotify({
                                                text: data.message,
                                                type: data.type,
                                                styling: 'bootstrap3',
                                                animateSpeed: 'fast',
                                                delay: 3000
                                            });
                                        }

                                        $(".table").DataTable().ajax.reload();
                                    },
                                    error: function() {

                                        new PNotify({
                                            text: "{{ __('profile.something_went_wrong') }}",
                                            type: 'error',
                                            styling: 'bootstrap3',
                                            animateSpeed: 'fast',
                                            delay: 3000
                                        });

                                    }
                                });
                            }
                        });
                    }

                });
            },

            checkRole = function (role) {

                let response;

                $.ajax({
                    url     : "{{ route('settings.roles.assigned') }}",
                    type    : 'POST',
                    dataType: "json",
                    async   : false,
                    data    : {
                        "role"      :   role,
                        "_token"    :   "{{ csrf_token() }}"
                    },
                    success : function(data) {

                        if (data.success && data.type == 'info') {
                            swal({
                                title       : "{{ __('dashboard.info') }}",
                                text        : data.message,
                                icon        : "/assets/images/info.png",
                            })
                            response = false;
                        } else {
                            response = true;
                        }
                    },
                    error: function() {
                        new PNotify({
                            text: "{{ __('profile.something_went_wrong') }}",
                            type: 'error',
                            styling: 'bootstrap3',
                            animateSpeed: 'fast',
                            delay: 3000
                        });

                        response = false;
                    }
                });

                return response;

            };

            return {
                init: function () {
                    rolesDatatable(),
                    editRole(),
                    removeRole()
                }
            }


        }(1);

        jQuery(document).ready(function(){
            SnippetRolesPermissionsTable.init();

        });
        /****************************************end:Buyer Backend Sidebar***************************************/
    </script>

    @endsection

