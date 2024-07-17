@if (count($suppliers) > 0)
    @foreach ($suppliers as $supplier)
        @php
        $mobile = !empty($supplier->mobile)? $supplier->c_phone_code.' '.$supplier->mobile : '-';
        $ContactPersonEmail =  (isset($supplier->contact_person_email) && !empty($supplier->contact_person_email))? $supplier->contact_person_email : '-';
        @endphp
        <tr>
            <td class="hidden">{{ $supplier->id }}</td>
            <td>
                @if(!empty($supplier->profile_username))
                    <a href="{{ route('supplier.professional.profile', (getSettingValueByKey('slug_prefix').($supplier->profile_username)))}}" target="_blank" class="hover_underline" style="text-decoration: none; color: #000">{{  $supplier->name  }}</a>
                @else
                {{ $supplier->name }}
                @endif</td>
            <td>{{ $supplier->email??'-' }}</td>
            <td>{{ $mobile??'-' }}</td>
            <td>{{ $ContactPersonEmail }}</td>
            <td>
                @if ($supplier->interested_in != NULL)
                    {{ str_replace(",", ", ", $supplier->interested_in) }}
                @else
                    -
                @endif
            </td>
            <td>
                @if ($supplier->status == 0)
                    <a href="javascript:void(0)" data-id="{{ $supplier->id }}"
                       data-status="{{ $supplier->status }}"
                       class="color-gray changeStatus"
                       id="supplier{{ $supplier->id }}" data-toggle="tooltip" ata-placement="top"
                       title="{{__('admin.inactive')}}"><i class="fa fa-user-circle"
                                                           aria-hidden="true"></i></a>
                @else
                    <a href="javascript:void(0)" data-id="{{ $supplier->id }}"
                       data-status="{{ $supplier->status }}"
                       id="supplier{{ $supplier->id }}" class="changeStatus" data-toggle="tooltip" ata-placement="top"
                       title="{{__('admin.active')}}"><i
                            class="fa fa-user-circle" aria-hidden="true"></i></a>
                @endif
            </td>
            <td>
                @if( !empty( $supplier->trackAddData ) )
                    {{ $supplier->trackAddData->full_name }}
                @else
                    -
                @endif
            </td>
            <td>
                @if( !empty( $supplier->trackUpdateData ) )

                    {{ $supplier->trackUpdateData->full_name }}
                @else {{"-"}}@endif
            </td>
            <td>
                {{ changeDateTimeFormat($supplier->updated_at) }}
            </td>
            @canany(['edit supplier list' , 'delete supplier list' , 'create supplier list' , 'publish supplier list'])
                <td class="text-end text-nowrap iconsection">
                    @if($supplier->profile_username != NULL)
                        <a href="{{ route('supplier.professional.profile', (getSettingValueByKey('slug_prefix').($supplier->profile_username)))}}" target="_blank" data-toggle="tooltip" title="Preview"><i class="fa fa-rocket" aria-hidden="true"></i></a>

                    @endif
                    @if(empty($supplier->xen_platform_id))
                        @php $xenStyle=''; @endphp
                        @if($supplier->status==0)
                            @php $xenStyle='display:none'; @endphp
                        @endif
                        @can('create supplier list')
                            <a href="javascript:void(0)" style="{{$xenStyle}}" data-id="{{$supplier->id}}"
                               data-name="{{$supplier->name}}" data-email="{{$supplier->contact_person_email}}"
                               class="show-icon create-xenaccount" id="create-xenaccount{{$supplier->id}}"
                               data-bs-toggle="modal" data-bs-target="#xenditpopup" data-toggle="tooltip"
                               ata-placement="top" title="{{__('admin.create_xen_account')}}"><i
                                    class="fa fa-chain" aria-hidden="true"></i>
                            </a>
                        @endcan
                    @endif
                    @can('edit supplier list')
                        {{-- <a href="{{ route('supplier-edit', ['id' => Crypt::encrypt($supplier->id)]) }}"
                           class="show-icon ps-2" data-toggle="tooltip" ata-placement="top"
                           title="{{__('admin.edit')}}"><i class="fa fa-edit" aria-hidden="true"></i>
                        </a> --}}
                        <a href="{{ route('admin.supplier.edit', ['id' => Crypt::encrypt($supplier->id)]) }}"
                           class="show-icon ps-2" data-toggle="tooltip" ata-placement="top"
                           title="{{__('admin.edit')}}"><i class="fa fa-edit" aria-hidden="true"></i>
                        </a>
                    @endcan
                    @can('publish supplier list')
                        <a class="ps-2 cursor-pointer supplierModalView" data-bs-toggle="modal"
                           data-bs-target="#supplierModal" data-id="{{ $supplier->id }}" data-toggle="tooltip"
                           ata-placement="top" title="{{__('admin.view')}}"><i class="fa fa-eye"></i></a>
                    @endcan
                    @can('delete supplier list')
                        <a href="javascript:void(0)" id="deleteSupplier_{{ $supplier->id }}"
                           data-name="{{ $supplier->name }}"
                           class="deleteSupplier show-icon ps-2" data-toggle="tooltip" ata-placement="top"
                           title="{{__('admin.delete')}}"><i class="fa fa-trash"
                                                             aria-hidden="true"></i>
                        </a>
                    @endcan
                </td>
            @endcan
        </tr>
    @endforeach
@endif
