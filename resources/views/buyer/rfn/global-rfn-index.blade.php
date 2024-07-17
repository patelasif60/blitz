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
                <a href="{{ route('rfn.index') }}" class="nav-link" id="addRfnFormTab-tab" type="button" role="tab" aria-controls="addRfnFormTab-tab-pane" aria-selected="true" data-createFrom="{{$permission['rfnForm']}}">
                    {{ __('buyer.rfn') }}
                </a>
            </li>
            @endcan
            @can('buyer global rfn publish')
            <li class="nav-item active" role="presentation">
                <a href="{{ route('rfn.global') }}" class="nav-link active" id="globalRfn-tab" type="button" role="tab"
                        aria-controls="addRfnFormTab-tab-pane" aria-selected="true" data-createFrom="{{$permission['globalRfnForm']}}">{{ __('buyer.global_rfn') }}
                </a>
            </li>
            @endcan
        </ul>
    </div>
    <div class="tab-content" id="myTabContent">
        {{--begin: RFN Tab--}}
        <div class="tab-pane fade show active" id="addRfnFormTab-tab-pane" role="tabpanel" aria-labelledby="addRfnFormTab-tab" tabindex="0">

            @can('buyer global rfn create')
                <div class="d-flex align-items-center mb-2" id="btnGlobalRfnlisting">
                    <button class="btn btn-warning btn-sm me-2 ms-auto" name="globalRfn_listing" id="globalRfn_listing" value="2">{{ __('buyer.globalRfn_form') }}</button>
                </div>
            @endcan

            @can('buyer global rfn create')
                <div class="border pt-2 d-none" id="formRequest">
                    <div class="card radius_1 border-top-0 mt-2" >
                        <div class="card-body mt-2">
                            @livewire('buyer.rfn.create-rfn',['formType' => 2])

                            <div class="col-12 mt-3 d-flex align-items-center">
                                <button type="button" class="btn btn-primary px-3 py-1 mb-1 ms-auto" id="storeRfn">
                                    <img src="{{asset('front-assets/images/icons/icon_post_require.png')}}" alt="Post Requirement" class="pe-1">{{ __('buyer.request_rfn') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endcanany

            @canany(['buyer global rfn list','buyer global rfn list-all'])
                {{--begin: Global RFN Tab--}}
                <div class="row" id="globalRfnlisting">
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

        {{--begin: RFN Join --}}
        <div class="modal fade" id="joinRfnModal" tabindex="-1" aria-labelledby="ModalRFNLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header p-3">
                        <h2 class="modal-title fs-5" id="joinRfnProductFullname">
                        </h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        @livewire('buyer.globalrfn.joinrfn')
                    </div>
                    <div class="modal-footer p-2">
                        <button type="button" class="btn btn-primary px-3 py-1 mb-1 ms-auto" id=joinrfnbtn" onclick="SnippetRFNList.joinrfnUpdate()">
                            {{ __('buyer.submit') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{--end: RFN Join--}}

    </div>
    @livewireScripts
    @stack('create-rf-search')
    @stack('update-rfn')
    @stack('rfn-list')
    @stack('join-rfn')
    @stack('globalrfn-list')
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

    globalRfnBtnClick = function () {
        $(document).on('click', '#globalRfn_listing', function() {
            var list = $('#globalRfn_listing').val();
            if(list == 1) {
                $('#formRequest').addClass('d-none');
                $('#globalRfnlisting').removeClass('d-none');
                $('#globalRfn_listing').val(2);
                $('#globalRfn_listing').html('{{ __('buyer.globalRfn_form') }}');
            }else{
                $('#formRequest').removeClass('d-none');
                $('#globalRfnlisting').addClass('d-none');
                $('#globalRfn_listing').val(1);
                $('#globalRfn_listing').html('{{ __('buyer.globalRfn_listing') }}');
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

    setJoinRfnEdit = function(){
      $(document).on('click', '.joinrfnEdit',function () {
          var id = $(this).data('id');
          var rfn_id = $(this).data('rfn_id');
          var response_id = $(this).data('response_id');
          $('#ModalRFNLabel').html($(this).data('ref'));
          $('#joinRfnProductFullname').html($(this).data('rfn_reference')+" : "+ $(this).data('rfnproductname'));
          window.livewire.emit('rfnJoinEditConfigure',id,rfn_id,response_id,1);
      });
    },
    setGlobalRfnEdit = function () {
        $(document).on('click', '.editGlobalRfnBtn',function () {
            var id = $(this).data('id');
            $('#ModalRFNLabel').html($(this).data('ref'));
            window.livewire.emit('editConfigure', 2,id);
        });
    },
    setJoinRfnForm = function () {
        $(document).on('click', '.joinRfnRequest',function () {
            $('#joinRfnForm')[0].reset();
            var id = $(this).data('id');
            $('#ModalRFNLabel').html($(this).data('ref'));
            $('#joinRfnProductFullname').html($(this).data('rfn_reference')+" : "+ $(this).data('rfnproductname'));
            window.livewire.emit('rfnJoinConfigure',id);
        });
    };
    return {
        init: function () {
            rfnListingBtnClick(),
            globalRfnBtnClick(),
            rfnForm(),
            storeRfn(),
            updateRfn(),
            isModalUpdated(),
            setGlobalRfnEdit(),
            setJoinRfnForm(),
            setJoinRfnEdit()
        },
        joinrfnUpdate : function () {
            window.livewire.emit('updateJoinRfn');
            window.livewire.emit('globalRfnlisting');
        }
    }
}(1);

</script>

@endsection
