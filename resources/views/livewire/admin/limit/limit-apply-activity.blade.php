<div>
    <div class="card mb-3">
        <div class="card-header d-flex align-items-center">
            <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/icon_update_activities.png')}}" alt="Charges" class="pe-2"> <span>{{ __('admin.recent_activities') }}</span></h5>
        </div>
        <div class="card-body p-3 pb-1">
            <ul class="bullet-line-list">
            @if(!empty($activity))
                @foreach($activity as $activityObj)
                    @if(!empty($activityObj->new_value))

                        @foreach((is_array($activityObj->new_value) ? $activityObj->new_value : json_decode($activityObj->new_value,true)) as $activitykey => $activityvalue)

                        <!--begin: Updated activities-->
                            @if ($activityObj->action == \App\Models\SystemActivity::UPDATED)

                                @if(isset(json_decode($activityObj->old_value,true)[$activitykey]) && !empty(json_decode($activityObj->old_value,true)[$activitykey]))

                                    <li class="pb-3">
                                        <p class="h6">
                                            {{__('admin.activity.limit_application.updated',[
                                                'name'  =>  $activityObj->causer[0]->firstname.' '.$activityObj->causer[0]->lastname,
                                                'column'=>  __('admin.activity.'.$activitykey),
                                                'reference_number'  =>  isset($limitApplication->loan_application_number) ? $limitApplication->loan_application_number : '',
                                                'old_value' => $activitykey == 'status' ? \App\Models\LoanApplication::STATUS[json_decode($activityObj->old_value,true)[$activitykey]] : json_decode($activityObj->old_value,true)[$activitykey],
                                                'new_value' => $activitykey == 'status' ? \App\Models\LoanApplication::STATUS[$activityvalue] : $activityvalue
                                            ])}}
                                        </p>
                                        <small class="d-flex align-items-top">
                                            <i class="mdi mdi-clock-outline me-1"></i>
                                            {{ \Carbon\Carbon::parse($activityObj->created_at)->format('d-m-Y H:i:s') }}
                                        </small>
                                    </li>

                                @else

                                    <li class="pb-3">
                                        <p class="h6">
                                            {{__('admin.activity.limit_application.new_updated',[
                                                'name'  =>  isset($activityObj->causer[0]->firstname) && isset($activityObj->causer[0]->lastname) ? $activityObj->causer[0]->firstname.' '.$activityObj->causer[0]->lastname : 'Blitznet',
                                                'column'=>  __('admin.activity.'.$activitykey),
                                                'reference_number'  =>  isset($limitApplication->loan_application_number) ? $limitApplication->loan_application_number : '',
                                                'new_value' => $activitykey == 'status' ? \App\Models\LoanApplication::STATUS[$activityvalue] : $activityvalue
                                            ])}}
                                        </p>
                                        <small class="d-flex align-items-top">
                                            <i class="mdi mdi-clock-outline me-1"></i>
                                            {{ \Carbon\Carbon::parse($activityObj->created_at)->format('d-m-Y H:i:s') }}
                                        </small>
                                    </li>

                                @endif

                            @endif
                        <!--end: Updated activities-->

                        @endforeach

                        <!--begin: Created activities-->
                            @if ($activityObj->action == \App\Models\SystemActivity::CREATED)
                                <li class="pb-3">
                                    <p class="h6">
                                        {{__('admin.activity.limit_application.created',[
                                            'name'  =>  $activityObj->causer[0]->firstname.' '.$activityObj->causer[0]->lastname,
                                            'reference_number' => isset($limitApplication->loan_application_number) ? $limitApplication->loan_application_number : '',
                                        ])}}
                                    </p>
                                    <small class="d-flex align-items-top">
                                        <i class="mdi mdi-clock-outline me-1"></i>
                                        {{ \Carbon\Carbon::parse($activityObj->created_at)->format('d-m-Y H:i:s') }}
                                    </small>
                                </li>
                            @endif
                        <!--end: Created activities-->
                        @endif
                    @endforeach
                @endif
            </ul>
        </div>
    </div>

</div>
