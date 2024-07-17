@extends('buyer.layouts.frontend.frontend_layout')

@section('css')
    <link href="{{ URL::asset('front-assets/library/jquery-ui-1.12.1.custom/jquery-ui.min.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="col-lg-8 col-xl-9 py-2" id="mainContentSection">
    <div class="header_top pt-1">
        <ul class="nav nav-tabs main_page_tab border-0" id="myTab" role="tablist">
            @can('buyer rfn publish')
            <li class="nav-item" role="presentation">
                <a href="{{ route('rfn.index') }}" class="nav-link active " id="addRfnFormTab-tab" type="button" role="tab" aria-controls="addRfnFormTab-tab-pane" aria-selected="true" data-createFrom="{{$permission['rfnForm']}}">
                    {{ __('buyer.rfn') }}
                </a>
            </li>
            @endcan
            @can('buyer global rfn publish')
            <li class="nav-item" role="presentation">
                <a href="{{ route('rfn.global') }}" class="nav-link " id="globalRfn-tab" type="button" role="tab"
                        aria-controls="addRfnFormTab-tab-pane" aria-selected="true" data-createFrom="{{$permission['globalRfnForm']}}">{{ __('buyer.global_rfn') }}
                </a>
            </li>
            @endcan
        </ul>
    </div>
    <div class="tab-content" id="myTabContent">
        {{--begin: RFN Tab--}}
        <div class="tab-pane fade show active" id="addRfnFormTab-tab-pane" role="tabpanel" aria-labelledby="addRfnFormTab-tab" tabindex="0">
            @can(['buyer rfn create','buyer rfn list'])
                <div class="d-flex align-items-center mb-2 " id="btnRfnLiting">
                    <button class="btn btn-warning btn-sm ms-auto" name="rfn_listing" id="rfn_listing" value="2">
                        {{ __('buyer.rfn_form') }}
                    </button>
                </div>
            @endcan

            @canany('buyer rfn create')
                <div class="border pt-2 d-none" id="formRequest">
                    <div class="card radius_1 border-top-0 mt-2" >
                        <div class="card-body mt-2">
                            @livewire('buyer.rfn.create-rfn',['formType' => 1])

                            <div class="col-12 mt-3 d-flex align-items-center">
                                <button type="button" class="btn btn-primary px-3 py-1 mb-1 ms-auto" id="storeRfn">
                                    <img src="{{asset('front-assets/images/icons/icon_post_require.png')}}" alt="Post Requirement" class="pe-1">{{ __('buyer.request_rfn') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endcanany

            @canany(['buyer rfn list','buyer rfn list-all'])
                <div class="row" id="rfnlisting">
                    <div class="col-md-12 RFN_Details">
                        @livewire('buyer.rfn.rfn-list')
                    </div>
                </div>
            @endcanany
            @canany(['buyer global rfn list','buyer global rfn list-all'])
                {{--begin: Global RFN Tab--}}
                <div class="row d-none" id="globalRfnlisting">
                    <div class="col-md-12 RFN_Details">
                        @livewire('buyer.globalrfn.globalrfn-list')
                    </div>
                </div>
                {{--end: Global RFN Tab--}}
            @endcanany
                </div>
            </div>
        </div>
        {{--end: RFN Tab--}}
    <div>
        {{--begin: RFN Edit--}}
        <div class="modal fade" id="editRfnModal" tabindex="-1" aria-labelledby="ModalRFNLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header p-3">
                        <h2 class="modal-title fs-5" id="ModalRFNLabel"></h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        @livewire('buyer.rfn.edit-rfn',['formType' => 1, 'rfnId' => 1])
                    </div>
                    <div class="modal-footer p-2">
                        <button type="button" class="btn btn-primary px-3 py-1 mb-1 ms-auto" id="editRfn">
                           {{ __('buyer.submit') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{--end: RFN Edit--}}

        {{--begin :Start end Date popup --}}
        <div class="modal fade" id="convertRfnStartEndDatePopup" aria-hidden="true"
         aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalToggleLabel2">{{__('buyer.global_rfn')}}
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @livewire('buyer.rfn.start-date-end-date-popup')
                </div>
                <div class="modal-footer p-1">
                    <button type="button" class="btn btn-primary px-3 py-1 mb-1 ms-auto" id="globalDateAddBtn">Submit</button>
                </div>
            </div>
        </div>
    </div>
        {{--end: Start end Date popup--}}
    </div>


    @livewireScripts
    @stack('create-rf-search')
    @stack('update-rfn')
    @stack('rfn-list')
</div>
@endsection

@section('script')
    <script src="{{ URL::asset('front-assets/library/jquery-ui-1.12.1.custom/jquery-ui.min.js') }}"></script>

<script>
jQuery(document).ready(function(){
    SnippetRFNList.init();
});
var SnippetRFNList = function () {

    /*** Click on Rfn listing button for RFN listing To form request*/
    var rfnListingBtnClick = function() {
        $(document).on('click', '#rfn_listing', function() {
               var list = $('#rfn_listing').val();
               if(list == 1) {
                    $('#formRequest').addClass('d-none');
                    $('#rfnlisting').removeClass('d-none');
                    $('#rfn_listing').val(2);
                    $('#rfn_listing').html('{{ __('buyer.rfn_form') }}');
               }else{
                   $('#rfnlisting').addClass('d-none');
                   $('#formRequest').removeClass('d-none');
                   $('#rfn_listing').val(1);
                   $('#rfn_listing').html('{{ __('buyer.rfn_listing') }}');
               }

        });
    },

    storeRfn = function() {
        $('#storeRfn').on('click', function () {
            window.livewire.emit('storeRfn');
        });
    },

    updateRfn = function() {
        $('#editRfn').on('click', function () {
            window.livewire.emit('updateRfn');
        });
    },
    isModalUpdated = function () {
        window.addEventListener('pnotify',function(e) {
            if(e.detail.modal && e.detail.success)
            {
                $('#'+e.detail.component).modal('toggle');
            }
            window.livewire.emit('rfnlisting');
            window.livewire.emit('globalRfnlisting');
        });
    },

    rfnForm = function() {
        $('#addRfnFormTab-tab').on('click', function () {
            window.livewire.emit('configure',1);
            $('#btnRfnLiting').removeClass('d-none');
            $('#formRequest').removeClass('d-none');
            $('#rfnlisting').addClass('d-none');
            $('#globalRfnlisting').addClass('d-none');
            $('#btnGlobalRfnlisting').addClass('d-none');

            if ($(this).data('createForm')) {
                $('#formRequest').removeClass('d-none');
                $('#rfnlisting').addClass('d-none');
            } else {
                $('#formRequest').addClass('d-none');
                $('#rfnlisting').removeClass('d-none');
            }
        });
    },

    setRfnEdit = function () {
        $(document).on('click', '.editRfnBtnClass',function () {
            $(".collapsemoreaction").collapse('hide');
            var id = $(this).data('id');
            $('#ModalRFNLabel').html($(this).data('ref'));
            window.livewire.emit('editConfigure', 1,id);
        });

    },

    setGlobalRfnEdit = function () {
        $(document).on('click', '.editGlobalRfnBtn',function () {
            var id = $(this).data('id');
            $('#ModalRFNLabel').html($(this).data('ref'));
            window.livewire.emit('editConfigure', 2,id);
        });
    },

    cancelGlobalRfn = function () {
        $(document).on('click', '.cancelGlobalRfnBtn', function () {
            var rfnId = $(this).data('id');
            swal({
                title: "{{ __('dashboard.are_you_sure') }}?",
                text: '{{__('buyer.cancel_rfn_alert')}}',
                icon: "/assets/images/info.png",
                buttons: ['{{ __('admin.no') }}', '{{ __('admin.yes') }}'],
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    window.livewire.emit('cancelRfn',rfnId);
                }
            });
        });
    }

    /*** Click on All checkbox to select all checkboxes*/
    getCheckedAllRfnData = function () {
        $(document).on('click', '.selectAllrfnItems', function () {
            var RfnId = $(this).attr('rfn-id');
            if ($('input:checkbox[name=selectAllrfnItems'+RfnId+']').val() == 0) {
                $('input:checkbox[name=selectAllrfnItems'+RfnId+']').val('1');
                $('input:checkbox[name=rfnSignleItems'+RfnId+']').prop("checked", true);
            } else {
                $('input:checkbox[name=selectAllrfnItems'+RfnId+']').val('1');
                $('input:checkbox[name=rfnSignleItems'+RfnId+']').prop('checked', false);
            }
        });
    },
    /*** Click on Single checkbox*/
    getCheckedSingleRfnData = function () {
        $(document).on('click', '.rfnSignleItems', function () {
            var RfnId = $(this).attr('rfn-id');
            if ($('input:checkbox[name=rfnSignleItems'+RfnId+']').filter(':checked').length == $('input:checkbox[name=rfnSignleItems'+RfnId+']').length) {
                $('input:checkbox[name=selectAllrfnItems'+RfnId+']').val('1');
                $('#selectAllrfnItems'+RfnId).prop('checked', true);
            } else {
                if ($(this).prop("checked") == false) {
                    $('#selectAllrfnItems'+RfnId).prop('checked', false);
                    $('input:checkbox[name=selectAllrfnItems'+RfnId+']').val('0');
                }
            }
        });
    },
        /** Add start date and end date on global convert **/
    globalDateAdd = function () {
        $(document).on('click', '#globalDateAddBtn', function () {
            window.livewire.emit('addDateOnGlobalRfnConvert');
        });
    }
    return {
        init: function () {
            rfnListingBtnClick(),
            rfnForm(),
            storeRfn(),
            updateRfn(),
            setRfnEdit(),
            isModalUpdated(),
            setGlobalRfnEdit(),
            getCheckedAllRfnData(),
            getCheckedSingleRfnData(),
            cancelGlobalRfn(),
            globalDateAdd()
        },
        joinrfnUpdate : function () {
            window.livewire.emit('updateJoinRfn');
        }

    }
}(1);
</script>

@endsection
