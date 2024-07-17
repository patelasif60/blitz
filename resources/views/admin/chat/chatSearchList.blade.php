@if(!empty($rfqChatList))
    @foreach($rfqChatList as $groupChat)
        @if(!empty($groupChat['group_chat_member']))
            <div class="rfq-name d-flex align-items-center mb-3 p-2 click" id="{{ $groupChat['group_name'] }}" onclick="chat.chatAdminViewData('{{ route("get-chat-data-ajax")  }}','{{$groupChat['_id']}}','{{$header_name}}','{{$groupChat['group_name']}}', '{{ $groupChat['chat_id'] }}',$(this))">
                {{--<div class="circleonline bg-success mx-3"></div>--}}
                <a href="javascript:void(0)" class="nav-link p-0 rfq-contact text-dark">
                    @if($header_name == 'Rfq')
                        <p class="brfq mb-0 fw-bold">{{ $groupChat['group_name'] }}</p>
                    @elseif($header_name == 'Quote')
                        <p class="brfq mb-0 fw-bold">{{$groupChat['group_name']}}
                            <span class="badge bg-info ms-2" style="font-size: 10px;">{{$groupChat['quote']['rfq']['reference_number']}}</span>
                        </p>
                    @endif

                    @if($groupChat['chat_id'])
                        <small class="time text-muted">{{\Carbon\Carbon::parse($groupChat['created_at'])->format('d-m-Y H:i:s')}}</small>
                    @endif
                </a>
                @if(isset($groupChat['group_chat_member']) && $groupChat['group_chat_member']['unread_message_count'] > 0)
                    <div class="ms-auto chat-count-none-backend">
                        <span class="badge bg-yellow text-black" style="border: 1px solid black;">{{$groupChat['group_chat_member']['unread_message_count']}}</span>
                    </div>
                @endif
            </div>
        @endif
    @endforeach
@else
    <div class="text-center fw-bold mb-3 p-2">
        @if($header_name == 'Rfq')
            <small >{{__('admin.no_rfq_chat_group')}}</small>
        @elseif($header_name == 'Quote')
            <small >{{__('admin.no_quote_chat_group')}}</small>
        @endif

    </div>
@endif


