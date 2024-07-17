@if(!empty($newChatList) || $newChatList->isNotEmpty())
    @foreach($newChatList as $newChat)
        @php
            if ($header_name == 'Rfq') {
                $chat_id = (isset($newChat->chatGroupRfq)) ? $newChat->chatGroupRfq->_id : '';
                $groupChatName = (isset($newChat->chatGroupRfq)) ? $newChat->chatGroupRfq->group_name : $newChat->reference_number;
                $reference_number = $newChat->reference_number;
                $html = '<p class="brfq mb-0 fw-bold">'.$reference_number.'</p>';

            }elseif($header_name == 'Quote') {
                $chat_id = isset($newChat->chatGroupQuote) ? $newChat->chatGroupQuote->_id : '';
                $groupChatName = isset($newChat->chatGroupQuote->group_name) ? $newChat->chatGroupQuote->group_name : $newChat->quote_number;
                $quote_number = $newChat->quote_number;
                $reference_number =(isset($newChat->rfq)) ? $newChat->rfq->reference_number : '';
                $html = '<p class="brfq mb-0 fw-bold">'.$quote_number.'<span class="badge bg-info ms-2" style="font-size: 10px;">'.$reference_number.'</span></p>';
            }
        @endphp
        <div class="rfq-name d-flex align-items-center mb-3 p-2 click" id="{{ $groupChatName }}"
             onclick="chat.chatAdminViewData('{{ route('get-chat-data-ajax')  }}','{{$chat_id}}','{{$header_name}}','{{$groupChatName}}', '{{ $newChat->id??'' }}',$(this))">
            <a href="javascript:void(0)" class="nav-link p-0 rfq-contact text-dark">
                {!!$html!!}
            </a>
        </div>
    @endforeach
@else
    <div class="text-center fw-bold mb-3 p-2">
        <small>{{__('admin.no_chat_group')}}</small>
    </div>
@endif

