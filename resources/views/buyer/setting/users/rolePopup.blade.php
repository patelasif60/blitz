<div class="modal-header">
    <h5 class="modal-title" id="staticBackdropLabel">
        @if(isset($role)){{ $role->name }}@else{{ old('name') }}@endif</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body roleModalBody">
    <div class="col-md-12 addRoleSection ">
        <form id="buyerRolePermissionForm" method="" action="">
            @csrf
            <span id="rolePermissionError" class="text-danger"></span>
            <div class="row mx-0 permission_head border border-bottom-0 bg-light px-2">
                <div class="col-md-3 my-2 fw-bold">{{ __('profile.modules') }}</div>
                <div class="col-md-8 my-2 fw-bold">{{ __('profile.permissions') }}</div>
            </div>
            @foreach($permissionGroup as $group)
                <div class="row mx-0 d-flex align-items-center border-bottom-0 permission_list border p-2">

                    <div class="col-md-3">
                        <span class="fw-bold" style="font-size: 0.875rem">{{ __('profile.module.'.$group->display_name) }} </span>
                    </div>
                    <div class="col-md-8 d-flex align-items-center flex-wrap" id="permissionBadgeDiv{{$group->id}}">

                        @foreach($group->children() as $subGroup)
                            <div class="position-relative alerthover mb-1">
                                <div class="permissiondiv alert py-0 p-1 mb-0 me-2 @if(isset($role)) @if(!arr_compare(json_decode($subGroup->permissions), $role->permissions)) d-none @endif @else d-none @endif  {{ $subGroup->class_name }} permissionMainGroup{{$group->id}}" data-group="{{ $subGroup->id }}">{{ __('profile.permission.'.$subGroup->display_name)}}
                                </div>
                            </div>
                        @endforeach
                        <div class="position-relative ms-2">

                            <div class="collapse shadow border collapsecontentrole" id="collapse{{$group->id}}">
                                <div class="p-3 py-2">
                                    {{--{{dd($group->children())}}--}}
                                    @foreach($group->children() as $subGroup)
                                        <div class="form-check d-flex align-items-center">
                                            <input class="form-check-input role-check permissionGroup{{$group->id}}" type="checkbox" value="{{ $subGroup->permissions }}" id="rolePermission" name="rolePermission[]" data-group="{{ $subGroup->id }}" @if(isset($role)) @if(arr_compare(json_decode($subGroup->permissions), $role->permissions)) checked @endif @endif>
                                            <label class="form-check-label ps-2" style="font-size: 12px !important; font-weight: bold !important; font-family: 'europaNuova_b';" for="flexCheckDefault">{{ __('profile.permission.'.$subGroup->display_name) }}</label>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </form>
    </div>
</div>
<div class="modal-footer">
    <div class="d-flex justify-content-end mt-2">
        <button type="submit" class="btn btn-primary me-2 btn-submit-role d-none"
                id="saveRolePermission"> Update </button>
        <button type="button" class="btn btn-secondary ps-2"
                data-bs-dismiss="modal" aria-label="Close">{{ __('admin.cancel')}}</button>
    </div>
</div>
