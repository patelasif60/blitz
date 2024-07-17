@if(!$supportChatList->isEmpty())
    @foreach($supportChatList as $key => $value)
        @php $companyUnreadMesCount = countSupportChatCompanyMes($key); @endphp
        <div class="rfq-name d-flex align-items-center mb-3">
            <ul class="w-100 mb-0 ps-0" type="none">
                <li>
                    <a class="d-flex text-dark text-decoration-none align-items-center p-2"  data-bs-toggle="collapse" id="{{$key}}" data-name="support"  href="#company_chield_user{{$key}}">
                        <img src="{{URL::asset('assets/images/icon_plus.png')}}" alt="plus" width="10px" height="10px" class="me-1 mb-1">
                        <p class="brfq mb-0 fw-bold ms-1">{{$value[0]['company']['name']}}</p>
                        @if($companyUnreadMesCount > 0)
                            <span class="badge text-dark border border-secondary ms-auto radius_2 me-2" id="companyCount_{{$key}}">{{$companyUnreadMesCount}}</span>
                        @endif
                    </a>
                    <div class="collapse company-body" id="company_chield_user{{$key}}">
                        <ul class="user-line-list mt-0">
                            @foreach($value as $val)
                                <li class="pb-2" id="{{ $val->user_id }}" onclick="chat.chatAdminSupportViewData('{{ route('get-support-chat-data-ajax')  }}', '{{$val->user_id}}','{{$val->company_id}}', '{{$val->user->firstname .' '. $val->user->lastname}}','{{$val->_id}}', $(this))">
                                    <a href="javascript:void(0  )" class="d-flex align-items-center user-link px-2">
                                        <p class="bsupport text-muted fw-bold ms-1"> {{$val->user->firstname .' '. $val->user->lastname}}</p>
                                        @if($val->SupportChatMember->unread_message_count > 0)
                                            <span class="badge text-dark border border-secondary ms-auto radius_2 me-2 chat-count-none-backend">{{$val->SupportChatMember->unread_message_count}}</span>
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    @endforeach
@else
    <div class="text-center fw-bold mb-3 p-2">
        <small >{{__('admin.no_chat_group')}}</small>
    </div>
@endif


