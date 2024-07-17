@if(!empty($rfqChatList))
    @foreach($rfqChatList as $groupChat)
        @if(!empty($groupChat['group_chat_member']))
            @php
                if ($header_name == 'Rfq') {
                    $html = '<div class="rfqchatname fw-bold mb-2">'.$groupChat['group_name'].'</div>';
                }
                if ($header_name == 'Quote') {
                    $reference_number = (isset($groupChat['quote']['rfq'])) ? $groupChat['quote']['rfq']['reference_number'] : '';
                    $html = '<div class="rfqchatname fw-bold mb-2">'.$groupChat['group_name'].'<span class="badge bg-primary ms-2" style="font-size: 10px;">'.$reference_number.'</span></div>';
                }
            @endphp
            <div class="rfqchatlist mb-1"
                 onclick="chat.chatViewData('{{ route("new-chat-create-view")  }}','{{$groupChat['_id']}}','{{$header_name}}','{{$groupChat['group_name']}}', '{{ $groupChat['chat_id'] }}',$(this), '', '{{ $groupChat['company_id']??'' }}')" >
                <div class="p-2 d-flex align-items-center click">
                    <div>
                        {!! $html !!}
                        @if($groupChat['chat_id'])
                            <div class="rfqdatename">{{\Carbon\Carbon::parse($groupChat['created_at'])->format('d-m-Y H:i:s')}}</div>
                        @endif
                    </div>
                    @if(isset($groupChat['group_chat_member']) && $groupChat['group_chat_member']['unread_message_count'] > 0)
                        <div class="ms-auto chat-count-none">
                                <span class="badge bg-yellow text-black">{{$groupChat['group_chat_member']['unread_message_count']}}</span>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    @endforeach
@else
    <div class="blankchatpage">
        <img src="{{URL::asset('chat/images/blank_image.png')}}" alt="" srcset="">
    </div>
@endif


