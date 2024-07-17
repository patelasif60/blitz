<style>
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

<div class="row">
    <input type="hidden" id="is_user_login" value="{{ (!Auth::user()) ? 0 : 1 }}" />
    @if(isset($groups) && count($groups) > 0)
        @foreach($groups as $group)

        <!-- Hidden values for category, subcategory, product and unit id -->
        <input type="hidden" name="groupId" id="groupId_{{$group->id}}" value="{{ $group->id }}" />
        <input type="hidden" name="categoryId" id="categoryId_{{$group->id}}" value="{{ $group->grpCatId }}" />
        <input type="hidden" name="subCategoryId" id="subCategoryId_{{$group->id}}" value="{{ $group->grpSubCatId }}" />
        <input type="hidden" name="productName" id="productName_{{$group->id}}" value="{{ $group->productName }}" />
        <input type="hidden" name="unitId" id="unitId_{{$group->id}}" value="{{ $group->grpUnitId }}" />
        <!-- End -->

        <div class="col-md-6 col-lg-6 col-xl-4 mb-3">
            <div class="card blank-link h-100">
                <div class="position-relative gbhoverimg">
                    <a href="{{ route('group-details',['id' => Crypt::encrypt($group->id)]) }}" class="stretched-link">
                        <div class="ratio ratio-16x9">
                            @if(isset($group->groupImg))
                            <img src="{{ url('/storage/'.$group->groupImg) }}" class="card-img-top" alt="{{ $group->groupName }}">
                            @else
                            <img src="{{ URL::asset('front-assets/images/no_group_img.jpg') }}" alt="No groups available" class="me-1">
                            @endif
                        </div>
                    </a>
                    <!-- Group Status => 1 : Open, 2 : Hold, 3 : Close, 4 : Expire  -->
                    @if($group->group_status == 1)
                        <span class="badge bg-success mb-1 rounded-pill fw-normal" style="height:20px; line-height: 12px; top: 10px; left: 10px; position: absolute;">&#9679; Open</span>
                    @elseif($group->group_status == 2)
                        <span class="badge bg-warning mb-1 rounded-pill fw-normal" style="height:20px; line-height: 12px; top: 10px; left: 10px; position: absolute;">&#9679; Hold</span>
                    @elseif($group->group_status == 3)
                        <span class="badge bg-primary mb-1 rounded-pill fw-normal" style="height:20px; line-height: 12px; top: 10px; left: 10px; position: absolute;">&#9679; Close</span>
                    @else
                        <span class="badge bg-danger mb-1 rounded-pill fw-normal" style="height:20px; line-height: 12px; top: 10px; left: 10px; position: absolute;">&#9679; Expired</span>
                    @endif
                    <!-- End -->
                    <div class=" ">
                        <div class="p-1 time_remain d-flex align-items-center">
                            <img src="{{ URL::asset('front-assets/images/icons/exclamation-circle.png') }}" alt="" class="me-1" height="14px">
                            @php
                                $expDate = (strtotime($group->end_date) - strtotime(now()->format('Y-m-d'))) / (60 * 60 * 24);
                            @endphp
                            <span class="tag_icon">{{ ($expDate != 0 && $expDate > 0) ? $expDate . ' ' . __('dashboard.days_remaining') : 'Group Expired' }}</span>
                        </div>
                    </div>
                    <div class="d-inline-block p-1 discount">{{ $group->max_discount}}% <br> off</div>
                    <div class="viewmore_Sec d-flex align-items-center justify-content-center">
                        <span>{{ __('dashboard.view_details') }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex">
                        <h6 class="card-title dark_blue w-75" style="font-size: 20px;">
                            <a href="{{ route('group-details',['id' => Crypt::encrypt($group->id)]) }}">{{ $group->groupName }}</a>
                        </h6>
                    </div>
                    <h6 class="card-title" style="font-size: .8rem;">{{$group->productName}}</h6>
                    <div class="card-text bg-white" style="font-size: 13px;">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="pb-2 d-flex ">
                                    <div class="col-auto me-1"><img src="{{ URL::asset('front-assets/images/icons/balance-scale-right.png') }}" alt="" height="14px"></div>
                                    <div class="col-auto">
                                        <span class="fw-bold">{{ $group->target_quantity }} {{ $group->unit }}</span><br>
                                        <span class="text-muted fsize_10">{{ __('dashboard.target_quantity') }}</span>
                                    </div>
                                </div>
                                <div class="pb-2 d-flex">
                                    <div class="col-auto me-1"><img src="{{ URL::asset('front-assets/images/icons/building_(2).png') }}" alt="" height="14px"></div>
                                    <div class="col-auto">
                                    @php
                                        $userCompanies = 0;
                                        $company_ids = [];
                                        foreach($group->groupMembersMultiple as $buyer) {
                                            array_push($company_ids, $buyer['company_id']);
                                        }
                                        $company_ids = array_unique($company_ids);
                                        $userCompanies = sizeof($company_ids);
                                    @endphp
                                    <span class="fw-bold"> {{$userCompanies ?? '0'}} companies</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 card-accent-left">
                                <div class="pb-2 d-flex">
                                    <div class="col-auto me-1"><img src="{{ URL::asset('front-assets/images/icons/balance-scale-right.png') }}" alt="" height="14px"></div>
                                    <div class="col-auto">
                                        <span class="fw-bold">{{ $group->achieved_quantity }} {{ $group->unit }}</span><br>
                                        <span class="text-muted fsize_10">{{ __('admin.achieved_quantity') }}</span>
                                    </div>
                                </div>
                                <div class="pb-2 d-flex">
                                    <div class="col-auto me-1"><img src="{{ URL::asset('front-assets/images/icons/Calendar-alt.png') }}" alt="" height="14px"></div>
                                    <div class=""><span class="fw-bold"> {{ __('dashboard.exp_date') }}: {{ date('d M Y', strtotime($group->end_date)) }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-center bg-transparent">
                    <button type="button" class="btn btn-primary btn-sm me-2 group_join_btn" id="group_join_btn" data-group-id="{{$group->id}}" {{($expDate != 0 && $expDate > 0) ? '' : 'disabled'}}>
                        <img src="{{ URL::asset('front-assets/images/icons/layer-group.png') }}" alt=""> {{ __('dashboard.join_group') }}
                    </button>
                    <button type="button" class="btn btn-warning btn-sm shr_btn" id="gt_btn" {{($expDate != 0 && $expDate > 0) ? '' : 'disabled'}} data-share-group-link="{{ route('group-details',['id' => Crypt::encrypt($group->id)]) }}" data-share-group-link-html="{{ shareGroupLink(route('group-details',['id' => Crypt::encrypt($group->id)])) }}">
                        <img src="{{ URL::asset('front-assets/images/icons/icon_share_1.png')}}" alt=""> {{ __('dashboard.share') }}
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    @else
    <div class="col-md-12 py-2">
        <div class="container-fluid m-0 p-0">
            <div class="row">
                <div class="col-md-12 text-center">
                    <img src="{{ URL::asset('front-assets/images/nogroup.png') }}" alt="No Group Available">
                    <h5 class="text-danger">{{ __('admin.no_group_found') }} "{{$query}}""</h5>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Share on social media Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h5 class="modal-title" id="exampleModalLabel">{{__('dashboard.share_via')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6 class="text-center mb-3">{{__('dashboard.share_group_social_media')}}</h6>
                <div class="social-btn-sp" id="social-btn-html">
                </div>
                <hr>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="groupLink" readonly aria-label="Group Link" aria-describedby="basic-addon2">
                    <button type="button" class="btn btn-primary" id="copyBtn">
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
    //Copy group link on click of "copy button"
    function copyGroupLink() {
        var copyText = document.getElementById("groupLink");
        copyText.select();
        navigator.clipboard.writeText(copyText.value);
        $(".copied").text("Copied to clipboard").show().fadeOut(2000);
        copyText = null;
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
