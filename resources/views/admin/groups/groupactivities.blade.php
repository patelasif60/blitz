@if($groupActivies)
    <div class="card mb-3">
        <div class="card-header d-flex align-items-center">
            <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/icon_update_activities.png')}}" alt="Charges" class="pe-2"> <span>{{ __('admin.recent_activities') }}</span></h5>
        </div>
        <div class="card-body p-3 pb-1">
            <ul class="bullet-line-list">
                @foreach($groupActivies as $groupactivity)
                    <li class="pb-3">
                        @if($groupactivity->key_name == 'create')
                            @if($groupactivity->user->role_id == 1)
                                <p class="h6">Blitznet Team created {{ $groupactivity->new_value }}. </p>
                            @else
                                <p class="h6">{{$groupactivity->user->firstname .' '. $groupactivity->user->lastname}} {{ $groupactivity->new_value }}.</p>
                            @endif
                        @endif

                        @if($groupactivity->key_name == 'group_deleted')
                            @if($groupactivity->user->role_id == 1)
                                <p class="h6">Blitznet Team Remove {{ 'BGRP-'.$groupactivity->group_id }} Group. </p>
                            @else
                                <p class="h6">{{$groupactivity->user->firstname .' '. $groupactivity->user->lastname}} Remove {{ 'BQTN-'.$groupactivity->group_id }} Group.</p>
                            @endif
                        @endif

                        @if($groupactivity->key_name == 'supplier')
                            <p class="h6">Blitznet Team updated Supplier  from {{ $suppliers[$groupactivity->old_value] .' to '. $suppliers[$groupactivity->new_value] }}.</p>
                        @endif

                        @if($groupactivity->key_name == 'name')
                            @if($groupactivity->user->role_id == 1)
                                <p class="h6">Blitznet Team updated Group Name from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.</p>
                            @else
                                <p class="h6">{{$groupactivity->user->firstname .' '.$groupactivity->user->lastname}} updated Group Name from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.</p>
                            @endif
                        @endif

                        @if($groupactivity->key_name == 'location_code')
                                @if($groupactivity->user->role_id == 1)
                                    <p class="h6">Blitznet Team updated Location from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.</p>
                                @else
                                    <p class="h6">{{$groupactivity->user->firstname .' '.$groupactivity->user->lastname}} updated Location from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.</p>
                                @endif
                        @endif

                        @if($groupactivity->key_name == 'category')
                            @if($groupactivity->user->role_id == 1)
                                <p class="h6">Blitznet Team updated Category from {{ $category[$groupactivity->old_value] .' to '. $category[$groupactivity->new_value] }}.</p>
                            @else
                                <p class="h6">{{$groupactivity->user->firstname .' '.$groupactivity->user->lastname}} updated Category from {{ $category[$groupactivity->old_value] .' to '. $category[$groupactivity->new_value] }}.</p>
                            @endif
                        @endif

                        @if($groupactivity->key_name == 'sub_category')
                            @if($groupactivity->user->role_id == 1)
                                <p class="h6">Blitznet Team updated Sub Category from {{ $subcategory[$groupactivity->old_value] .' to '. $subcategory[$groupactivity->new_value] }}.</p>
                            @else
                                <p class="h6">{{$groupactivity->user->firstname .' '.$groupactivity->user->lastname}} updated Sub Category from {{ $subcategory[$groupactivity->old_value] .' to '. $subcategory[$groupactivity->new_value] }}.</p>
                            @endif
                        @endif

                        @if($groupactivity->key_name == 'product_name')
                            @if($groupactivity->user->role_id == 1)
                                <p class="h6">Blitznet Team updated Product from {{ $product[$groupactivity->old_value] .' to '. $product[$groupactivity->new_value] }}.</p>
                            @else
                                <p class="h6">{{$groupactivity->user->firstname .' '.$groupactivity->user->lastname}} updated Product from {{ $product[$groupactivity->old_value] .' to '. $product[$groupactivity->new_value] }}.</p>
                            @endif
                        @endif

                        @if($groupactivity->key_name == 'unit_name')
                            @if($groupactivity->user->role_id == 1)
                                <p class="h6">Blitznet Team updated Unit from {{ $unit[$groupactivity->old_value] .' to '. $unit[$groupactivity->new_value] }}.</p>
                            @else
                                <p class="h6">{{$groupactivity->user->firstname .' '.$groupactivity->user->lastname}} updated Unit from {{ $unit[$groupactivity->old_value] .' to '. $unit[$groupactivity->new_value] }}.</p>
                            @endif
                        @endif

                        @if($groupactivity->key_name == 'price')
                            @if($groupactivity->user->role_id == 1)
                                <p class="h6">Blitznet Team updated Price from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.</p>
                            @else
                                <p class="h6">{{$groupactivity->user->firstname .' '.$groupactivity->user->lastname}} updated Unit from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.</p>
                            @endif
                        @endif

                        @if($groupactivity->key_name == 'exp_date')
                            @if($groupactivity->user->role_id == 1)
                                <p class="h6">Blitznet Team updated Expiry Date from {{ date('d-m-Y', strtotime($groupactivity->old_value)) .' to '. date('d-m-Y', strtotime($groupactivity->new_value)) }}.</p>
                            @else
                                <p class="h6">{{$groupactivity->user->firstname .' '.$groupactivity->user->lastname}} updated Expiry Date from {{ date('d-m-Y', strtotime($groupactivity->old_value)) .' to '. date('d-m-Y', strtotime($groupactivity->new_value)) }}.</p>
                            @endif
                        @endif

                        @if($groupactivity->key_name == 'min_order_quantity')
                            @if($groupactivity->user->role_id == 1)
                                <p class="h6">Blitznet Team updated Minimum Order QTY from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.</p>
                            @else
                                <p class="h6">{{$groupactivity->user->firstname .' '.$groupactivity->user->lastname}} updated Minimum Order QTY from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.</p>
                            @endif
                        @endif

                        @if($groupactivity->key_name == 'max_order_quantity')
                            @if($groupactivity->user->role_id == 1)
                                <p class="h6">Blitznet Team updated Maximun Order QTY from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.</p>
                            @else
                                <p class="h6">{{$groupactivity->user->firstname .' '.$groupactivity->user->lastname}} updated Maximun Order QTY from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.</p>
                            @endif
                        @endif

                        @if($groupactivity->key_name == 'description')
                            @if($groupactivity->user->role_id == 1)
                                <p class="h6">Blitznet Team updated Description from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.</p>
                            @else
                                <p class="h6">{{$groupactivity->user->firstname .' '.$groupactivity->user->lastname}} updated Description from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.</p>
                            @endif
                        @endif

                        @if($groupactivity->key_name == 'tag_deleted')
                            @if($groupactivity->user->role_id == 1)
                                <p class="h6">Blitznet Team Remove Tag from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.</p>
                            @else
                                <p class="h6">{{$groupactivity->user->firstname .' '.$groupactivity->user->lastname}} Remove Tag from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.</p>
                            @endif
                        @endif

                        @if($groupactivity->key_name == 'tag_added')
                            @if($groupactivity->user->role_id == 1)
                                <p class="h6">Blitznet Team Added Tag from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.</p>
                            @else
                                <p class="h6">{{$groupactivity->user->firstname .' '.$groupactivity->user->lastname}} Added Tag from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.</p>
                            @endif
                        @endif

                        @if($groupactivity->key_name == 'image_deleted')
                                @php
                                    $image_name_old = explode('group_image_', $groupactivity->old_value);
                                @endphp
                            @if($groupactivity->user->role_id == 1)
                                <p class="h6">Blitznet Team Remove Image {{ $image_name_old[1] }}.</p>
                            @else
                                <p class="h6">{{$groupactivity->user->firstname .' '.$groupactivity->user->lastname}} Remove Image {{ $image_name_old[1] }}.</p>
                            @endif
                        @endif

                        @if($groupactivity->key_name == 'image_added')
                                @php
                                    $image_name_new = explode('group_image_', $groupactivity->new_value);
                                @endphp
                            @if($groupactivity->user->role_id == 1)
                                <p class="h6">Blitznet Team Added Image {{ $image_name_new[1] }}.</p>
                            @else
                                <p class="h6">{{$groupactivity->user->firstname .' '.$groupactivity->user->lastname}} Added Image {{ $image_name_new[1] }}.</p>
                            @endif
                        @endif

                        @if($groupactivity->key_name == 'buyer_remove')
                            @if($groupactivity->user->role_id == 1)
                                <p class="h6"> Remove {{ $groupactivity->old_value}} by Blitznet Team. </p>
                            @else
                                <p class="h6"> Remove {{ $groupactivity->old_value}} by {{$groupactivity->user->firstname .' '.$groupactivity->user->lastname}}.</p>
                            @endif
                        @endif

                        @if($groupactivity->key_name == 'added_min_qty')
                            @if($groupactivity->user->role_id == 1)
                                <p class="h6"> Blitznet Team Added Minimum Quantity {{ $groupactivity->new_value}}. </p>
                            @else
                                <p class="h6"> {{$groupactivity->user->firstname .' '.$groupactivity->user->lastname}} Added Minimum Quantity {{ $groupactivity->new_value}}.</p>
                            @endif
                        @endif

                        @if($groupactivity->key_name == 'added_max_qty')
                            @if($groupactivity->user->role_id == 1)
                                <p class="h6"> Blitznet Team Added Maximum Quantity {{ $groupactivity->new_value}}. </p>
                            @else
                                <p class="h6"> {{$groupactivity->user->firstname .' '.$groupactivity->user->lastname}} Added Maximum Quantity {{ $groupactivity->new_value}}.</p>
                            @endif
                        @endif

                        @if($groupactivity->key_name == 'added_discount')
                            @if($groupactivity->user->role_id == 1)
                                <p class="h6"> Blitznet Team Added Discount Quantity {{ $groupactivity->new_value}}. </p>
                            @else
                                <p class="h6"> {{$groupactivity->user->firstname .' '.$groupactivity->user->lastname}} Added Discount Quantity {{ $groupactivity->new_value}}.</p>
                            @endif
                        @endif

                        @if($groupactivity->key_name == 'added_discount_price')
                            @if($groupactivity->user->role_id == 1)
                                <p class="h6"> Blitznet Team Added Discount Price {{ $groupactivity->new_value}}. </p>
                            @else
                                <p class="h6"> {{$groupactivity->user->firstname .' '.$groupactivity->user->lastname}} Added Discount Price {{ $groupactivity->new_value}}.</p>
                            @endif
                        @endif

                            @if($groupactivity->key_name == 'updated_min_qty')
                                @if($groupactivity->user->role_id == 1)
                                    <p class="h6"> Blitznet Team updated Minimum Quantity from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}. </p>
                                @else
                                    <p class="h6"> {{$groupactivity->user->firstname .' '.$groupactivity->user->lastname}} updated Minimum Quantity from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.</p>
                                @endif
                            @endif

                            @if($groupactivity->key_name == 'updated_max_qty')
                                @if($groupactivity->user->role_id == 1)
                                    <p class="h6"> Blitznet Team updated Maximum Quantity from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}. </p>
                                @else
                                    <p class="h6"> {{$groupactivity->user->firstname .' '.$groupactivity->user->lastname}} updated Maximum Quantity from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.</p>
                                @endif
                            @endif

                            @if($groupactivity->key_name == 'updated_discount')
                                @if($groupactivity->user->role_id == 1)
                                    <p class="h6"> Blitznet Team updated Discount from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}. </p>
                                @else
                                    <p class="h6"> {{$groupactivity->user->firstname .' '.$groupactivity->user->lastname}} updated Discount from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.</p>
                                @endif
                            @endif

                            @if($groupactivity->key_name == 'updated_discount_price')
                                @if($groupactivity->user->role_id == 1)
                                    <p class="h6"> Blitznet Team updated Discount Price from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}. </p>
                                @else
                                    <p class="h6"> {{$groupactivity->user->firstname .' '.$groupactivity->user->lastname}} updated Discount Price from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.</p>
                                @endif
                            @endif

                            @if($groupactivity->key_name == 'group_margin')
                                @if($groupactivity->user->role_id == 1)
                                    <p class="h6"> Blitznet Team updated Blitznet Margin from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}. </p>
                                @else
                                    <p class="h6"> {{$groupactivity->user->firstname .' '.$groupactivity->user->lastname}} updated Blitznet Margin from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.</p>
                                @endif
                            @endif

                            @if($groupactivity->key_name == 'group_status')
                                @if($groupactivity->user->role_id == 1)
                                    <p class="h6"> Blitznet Team updated Group Status from {{ $status[$groupactivity->old_value] .' to '. $status[$groupactivity->new_value] }}. </p>
                                @else
                                    <p class="h6"> {{$groupactivity->user->firstname .' '.$groupactivity->user->lastname}} updated Group Status from {{ $status[$groupactivity->old_value] .' to '. $status[$groupactivity->new_value] }}.</p>
                                @endif
                            @endif
                            @if($groupactivity->key_name == 'product_description')
                                @if($groupactivity->user->role_id == 1)
                                    <p class="h6">Blitznet Team updated Product Description from {{ $groupactivity->old_value .' to '. $groupactivity->new_value }}.</p>
                                @else
                                    <p class="h6">{{$groupactivity->user->firstname .' '.$groupactivity->user->lastname}} updated Product Description from {{ $product[$groupactivity->old_value] .' to '. $product[$groupactivity->new_value] }}.</p>
                                @endif
                            @endif

                            {{--$groupactivity->key_name--}}
                            @if($groupactivity->key_name == 'group_joined')
                                @if($groupactivity->user->role_id == 1)
                                    <p class="h6">Blitznet joined group.</p>
                                @else
                                    <p class="h6">{{$groupactivity->user->firstname .' '.$groupactivity->user->lastname}} joined group.</p>
                                @endif
                            @endif

                            @if($groupactivity->key_name == 'order_placed')
                                @if($groupactivity->user->role_id == 1)
                                    <p class="h6">Blitznet placed an order.</p>
                                @else
                                    <p class="h6">{{$groupactivity->user->firstname .' '.$groupactivity->user->lastname}} placed an order.</p>
                                @endif
                            @endif


                        <small class="d-flex align-items-top text-muted">
                            <i class="mdi mdi-clock-outline me-1"></i>
                            {{  \Carbon\Carbon::parse($groupactivity->created_at)->format('d-m-Y H:i:s')}}
                        </small>
                    </li>
                @endforeach

{{--                @if(isset($user))--}}

{{--                    <li class="pb-3">--}}

{{--                        <p class="h6">{{$user->firstname .' '. $user->lastname }} Generate Quote {{$quote->quote_number}}.</p>--}}
{{--                        <small class="d-flex align-items-top">--}}
{{--                            <i class="mdi mdi-clock-outline me-1"></i>--}}
{{--                            {{  \Carbon\Carbon::parse($quote->created_at)->format('d-m-Y H:i:s')}}--}}
{{--                        </small>--}}
{{--                    </li>--}}
{{--                @endif--}}
            </ul>
        </div>
@else

@endif
