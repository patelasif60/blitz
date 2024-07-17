<div wire:key="rfn-list-section">

    @if(isset($rfnListall) && count($rfnListall) > 0)

        @foreach($rfnListall as  $key => $rfnUnit)
            @foreach($rfnUnit as  $key2 => $rfnData)
            <div class="accordion-item radius_1 mb-2 rfnlistingcollpse">
                <h2 class="accordion-header" id="headingOne{{$rfnData->first()->id}}">
                    <button id="rfnlistingcollpse{{$rfnData->first()->id}}"  rfn-id="{{$rfnData->first()->id}}" class="accordion-button justify-content-between collapsed rfnlistingcollpseToggle Rfn_Id{{$rfnData->first()->id}}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$rfnData->first()->id}}" aria-expanded="false" aria-controls="collapse{{$rfnData->first()->id}}">
                        <div class="flex-grow-1 text-dark">
                            <span class=" text-dark">{{isset($rfnData->first()->product_fullname) ? $rfnData->first()->product_fullname : ''}}</span>
                        </div>
                        <div class="text-dark ms-1 me-2"><span class="badge rounded-pill bg-info">{{isset($rfnData->first()->quantity) ? $rfnData->sum('quantity') : '0'}} {{isset($rfnData->first()->unit->name) ? $rfnData->first()->unit->name : ''}}</span></div>

                    </button>
                </h2>
                <div id="collapse{{$rfnData->first()->id}}" data-id="{{$rfnData->first()->id}}" data-type="rfn" class="accordion-collapse collapse rfnCollapse" aria-labelledby="headingOne{{$rfnData->first()->id}}" data-bs-parent="#Rfn_accordian" wire:ignore.self>
                    <div class="accordion-body p-0">
                        <div class="container-fluid">

                            <div class="editmenu_section">
                                @if($rfnData->first()->status ==\App\Models\Rfn::PENDING_STATUS)
                                    @canany('buyer rfn update','buyer rfn cancel')
                                    <div class="btn-group">
                                        <button type="button" class="btn dropdown-toggle btn-sm h1 editRfnBtn" data-bs-toggle="dropdown" aria-expanded="false">&#8942;</button>
                                        <ul class="dropdown-menu dropdown-menu-end py-0">
                                            @can('buyer rfn update')
                                                <li><a href="javascript:void(0)" class="dropdown-item py-2" data-id="{{$rfnData->first()->id}}" data-ref="{{$rfnData->first()->reference_number}}" data-bs-toggle="modal" data-bs-target="#editRfnModal" id="editRfnBtn"> <img src="{{ URL::asset('front-assets/images/icons/icon_edit_add.png') }}" alt="Edit" style="max-height: 13px;" class="pe-1"> {{ __('rfqs.edit') }}</a></li>
                                            @endcan
                                            @can('buyer rfn cancel')
                                                <li><a href="javascript:void(0)" class="dropdown-item py-2" data-id="{{$rfnData->first()->id}}" id="cancelGlobalRfnBtn"> <img src="{{ URL::asset('front-assets/images/icons/icon_cancel_1.png') }}" alt="Cancel" style="max-height: 13px;" class="pe-1"> {{ __('rfqs.cancel') }}</a></li>
                                            @endcan
                                        </ul>
                                    </div>
                                    @endcan
                                @endif
                            </div>
                            <div class="row rfqform_view py-3">

                                <div class="row mt-2 pe-0">
                                    <div class="col-md-12 pe-0 my-2">
                                        <div class="table-responsive mb-2" style="overflow-x: inherit">
                                        <table class="table table-striped-columns table-hover border ">
                                            <thead class="bg-light">
                                            <tr>
                                                {{--<th style="width: 25px;">
                                                    <div class="form-check mb-0" style="min-height: 1.2rem; ">
                                                        <input class="form-check-input selectAllrfnItems" type="checkbox" rfn-id="{{$rfnData->first()->id}}" name="selectAllrfnItems{{$rfnData->first()->id}}"   value='0' >
                                                    </div>
                                                </th>--}}
                                                <th >{{__('buyer.reference_number')}}</th>
                                                <th>{{__('buyer.branch')}}</th>
                                                <th>{{__('buyer.product')}}</th>
                                                <th class="text-end">{{__('buyer.quantity')}}</th>
                                                <th  class="text-end pe-3">{{__('buyer.creation_date')}}</th>
                                                <th class="text-end">{{__('buyer.expected_date')}}</th>
                                                <th  class="text-end pe-3">{{__('buyer.status')}}</th>

                                                @if($rfnData->first()->rfn->where('status',\App\Models\Rfn::PENDING_STATUS)->count() != 0)
                                                @canany('buyer rfn update','buyer rfn convert global rfn','buyer rfn update','buyer rfn cancel')
                                                    <th class="text-end ">{{__('buyer.action')}}</th>
                                                @endcan
                                                @endif
                                            </tr>
                                            </thead>
                                            @foreach($rfnData as $rfnResponse)
                                            <tbody>
                                                <tr>
                                                    {{--<td>
                                                        <div class="form-check">
                                                            <input class="form-check-input rfnSignleItems" type="checkbox" name="rfnSignleItems{{$rfnData->first()->id}}" id="selectSignleRfnItems{{$rfnResponse->rfn->id}}" rfn-id="{{$rfnResponse->rfn->id}}" value="{{$rfnResponse->rfn->id}}">
                                                        </div>
                                                    </td>--}}
                                                    <td class="align-middle">{{$rfnResponse->rfn->reference_number}}</td>
                                                    <td class="align-middle">{{(!empty($rfnResponse->rfn->defaultCompanyUser) && isset($rfnResponse->rfn->defaultCompanyUser->first()->branches)) ? $rfnResponse->rfn->defaultCompanyUser->first()->branches : '-'}}</td>
                                                    <td class="align-middle"><span class=" text-dark">{{$rfnData->first()->product_fullname}}</span></td>
                                                    <td class="text-end align-middle">{{$rfnResponse->quantity.' '. (!empty($rfnResponse->unit) ? $rfnResponse->unit->name : '')}} </td>
                                                    <td class="text-end align-middle">{{isset($rfnResponse->rfn->created_at) ? \Carbon\Carbon::parse($rfnResponse->rfn->created_at)->format('d-m-Y H:i') : ''}}</td>
                                                    <td class="text-end align-middle">{{isset($rfnResponse->rfn->expected_date) ? \Carbon\Carbon::parse($rfnResponse->rfn->expected_date)->format('d-m-Y H:i') : ''}}</td>
                                                    <td class="text-end align-middle">

                                                         <span class="badge rounded-pill bg-primary mt-1 {{ $rfnResponse->rfn->status_color }}" id="badgeColorSpan{{ $rfnResponse->rfn->id }}">
                                                             @if($rfnResponse->rfn->status == 3) Cancel @elseif($rfnResponse->rfn->status == 2) Approved @else Pending @endif

                                                         </span>
                                                    </td>

                                                    <td class="position-relative">

                                                        <a class="rfncollapse collapsed" data-bs-toggle="collapse" href="#collapseRfn{{$rfnResponse->rfn->id}}" role="button" aria-expanded="false" aria-controls="#collapse" @if($rfnResponse->rfn->status==\App\Models\Rfn::APPROVED_STATUS) style="pointer-events: none;opacity: .3;" @endif><span class="px-1 py-0"><button type="button" class="btn rounded-3" ><i class="fa fa-ellipsis-v" aria-hidden="true"></i></button></span></a>
                                                        <div class="text-start shadow border collapsemoreaction collapse" id="collapseRfn{{$rfnResponse->rfn->id}}">
                                                            @can(['buyer rfn to rfq', 'create buyer rfqs'])
                                                            <a href="javascript:void(0)" class="generateRfq d-block" data-toggle="tooltip" ata-placement="top" title="Generate RFQ"  data-id="{{$rfnResponse->rfn->id}}" data-ref="{{$rfnResponse->rfn->reference_number}}">
                                                                <div class="editmenu px-2 py-2">
                                                                    <span class="" style="min-width: 20px; display: inline-block;text-align: center;">
                                                                         <img height="12px" src="{{ URL::asset('front-assets/images/icons/icon_rfq.png')}}">
                                                                    </span>
                                                                    <span class="ms-2" style="font-size: 14px;">{{__('buyer.generate_rfq')}}</span>
                                                                </div>
                                                            </a>
                                                            @endcan
                                                            @can('buyer rfn convert global rfn')
                                                            <a href="javascript:void(0)" class="d-block convertToGlobalRfn" data-toggle="tooltip"  ata-placement="top"  title="{{__('buyer.global_rfn')}}" rfn-id="{{$rfnResponse->rfn->id}}" name="generateRfq" >
                                                                <div class="editmenu px-2 py-2">
                                                                    <span class="" style="min-width: 20px; display: inline-block;text-align: center;">
                                                                        <img height="12px" src="{{ URL::asset('front-assets/images/icons/icon_nav_language.svg') }}"></span>
                                                                    <span class="ms-2" style="font-size: 14px;">{{__('buyer.global_rfn')}}</span>
                                                                </div>
                                                            </a>
                                                            @endcan
                                                                @can('buyer rfn update')
                                                            <a href="javascript:void(0)" class="d-block editRfnBtn editRfnBtnClass" data-toggle="tooltip" ata-placement="top" title="Edit"  data-id="{{$rfnResponse->rfn->id}}" data-ref="{{$rfnResponse->rfn->reference_number}}" data-bs-toggle="modal" data-bs-target="#editRfnModal">
                                                                <div class="editmenu px-2 py-2">
                                                                <span class="" style="min-width: 20px; display: inline-block;text-align: center;">
                                                                    <img height="12px" src="{{ URL::asset('front-assets/images/icons/icon_edit_add.png')}}"></span>
                                                                <span class="ms-2" style="font-size: 14px;">{{__('admin.edit')}}</span>
                                                                </div>
                                                            </a>
                                                                @endcan
                                                            @can('buyer rfn cancel')
                                                               <a href="javascript:void(0)" class="d-block cancelGlobalRfnBtn" data-id="{{!empty($rfnResponse->rfn->id) ? $rfnResponse->rfn->id : ''}}" data-toggle="tooltip" ata-placement="top" title="Delete">
                                                                        <div class="editmenu px-2 py-2">
                                                                <span class="" style="min-width: 20px; display: inline-block;text-align: center;">
                                                                    <img height="12px" src="{{ URL::asset('front-assets/images/icons/icon_delete_add.png')}}"></span>
                                                                            <span class="ms-2" style="font-size: 14px;">{{__('admin.delete')}}</span>
                                                                        </div>
                                                               </a>
                                                            @endcan
                                                                @can('buyer rfn reject')
                                                                <a href="javascript:void(0)" class="d-block" id="cancelRfnBtn" data-id="{{!empty($rfnResponse->rfn->id) ? $rfnResponse->rfn->id : ''}}" data-toggle="tooltip" ata-placement="top" title="{{__('buyer.reject')}}">
                                                                        <div class="editmenu px-2 py-2">
                                                                <span class="" style="min-width: 20px; display: inline-block;text-align: center;">
                                                                    <img height="12px" src="{{ URL::asset('front-assets/images/icons/icon_reject.png')}}"></span>
                                                                            <span class="ms-2" style="font-size: 14px;">{{__('buyer.reject')}}</span>
                                                                        </div>
                                                                </a>
                                                                @endcan

                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            @endforeach
                                        </table>
                                        </div>
                                        <div class="col-md-12 d-flex justify-content-end p-0">

                                                {{--@can('buyer rfn convert global rfn')
                                                    <a href="#" class="btn btn-info btn-sm  active convertToGlobalRfn"  rfn-id="{{$rfnData->first()->id}}" name="generateRfq" >

                                                        <span class="me-1">
                                                            <img src="{{ URL::asset('front-assets/images/icons/icon_nav_language.svg') }}" width="16px">
                                                        </span>
                                                        <span class="d-none d-md-inline-block" id="gt_btn">{{__('buyer.global_rfn')}}</span>
                                                    </a>
                                                @endcan--}}

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endforeach
    @else
        <div class="col-md-12">
            <div class="alert alert-danger radius_1 text-center fs-6 fw-bold" id="no_rfq_alert">{{ __('rfqs.no_rfn_found') }}</div>
        </div>
    @endif
</div>
@push('rfn-list')
    <script type="text/javascript">
        $(document).mouseup(function (e) {
            var container = $(".collapsemoreaction");

            // if the target of the click isn't the container nor a descendant of the container
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                container.collapse('hide');
            }
        });

        var SnippetRfnList = function () {
            var initFields = function () {
                $('.editmenu_section .btn-group .editRfnBtn').hover(function () {
                    $(this).find('.dropdown-menu').first().stop(true, true).slideDown(150);
                }, function () {
                    $(this).find('.dropdown-menu').first().stop(true, true).slideUp(105)
                });
            },
            convertToGlobalRfn = function () {
                $(document).on('click', '.convertToGlobalRfn', function () {
                    var RfnId = $(this).attr('rfn-id');
                    /*var RfnItemIds = [];
                    $("input:checkbox[name=rfnSignleItems"+RfnId+"]:checked").each(function() {
                        RfnItemIds.push($(this).val());
                    });*/
                    swal({
                        title: "{{ __('dashboard.are_you_sure') }}?",
                        text: '{{__('buyer.rfn_to_global_rfn')}}',
                        icon: "/assets/images/info.png",
                        buttons: ['{{ __('admin.no') }}', '{{ __('admin.yes') }}'],
                        dangerMode: true,
                    })
                        .then((isCheck) => {
                            if (isCheck) {
                                window.livewire.emit('dateRfnIdConfigure',RfnId);
                                $('#convertRfnStartEndDatePopup').modal('show');
                                //window.livewire.emit('createGlobalRfn',RfnId);
                            } else {
                                return false;sessionStorage.clear();
                            }
                        });
                })
            },
            rejectRfnBtn = function () {
                $(document).on('click', '#cancelRfnBtn', function () {
                    var rfnId = $(this).data('id');
                    swal({
                        title: "{{ __('dashboard.are_you_sure') }}?",
                        text: '{{__('buyer.reject_rfn_alert')}}',
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

            refreshData = function (){
                $(document).on('click', '.rfnlistingcollpseToggle', function () {
                    var RfnId = $(this).attr('rfn-id');
                    window.livewire.emit('refreshData');
                    $('.rfnCollapse').removeClass('show');
                    $('#rfnlistingcollpse'+RfnId).removeClass('collapsed');
                    $('#rfnlistingcollpse'+RfnId).toggleClass('show');

                });
            },

            generateRfq = function () {
                $('.generateRfq').on('click', function () {
                    var id = $(this).data('id');

                    swal({
                        title: "{{ __('dashboard.are_you_sure') }}?",
                        text: '{{__('buyer.generate_rfq_text')}}',
                        icon: "/assets/images/info.png",
                        buttons: ['{{ __('admin.no') }}', '{{ __('admin.yes') }}'],
                        dangerMode: true,
                    }).then((res) => {
                        if (res) {
                            window.livewire.emit('generateRfq',id);
                            sessionStorage.clear();
                        } else {
                            return false;
                            sessionStorage.clear();
                        }
                    });
                });
            };

            return {
                init: function () {
                    initFields(),
                    convertToGlobalRfn(),
                    generateRfq(),
                    refreshData(),
                    rejectRfnBtn()
                }
            }
        }(1);

        jQuery(document).ready(function () {
            SnippetRfnList.init();
        });
    </script>
@endpush
