@if($newChatList->isNotEmpty())
    @foreach($newChatList as $newChat)
        @php
            if ($header_name == 'Rfq') {
                $chat_id = (isset($newChat->chatGroupRfq)) ? $newChat->chatGroupRfq->_id : '';
                $reference_number = $newChat->reference_number;
                $ref_id=$reference_number;
                $company_id = $newChat->company_id;
                $html = '<div class="rfqchatname fw-bold mb-2">'.$reference_number.'</div>';

            }elseif($header_name == 'Quote') {
                $chat_id = (isset($newChat->chatGroupQuote)) ? $newChat->chatGroupQuote->_id : '';
                $quote_number = $newChat->quote_number;
                $ref_id=$quote_number;
                $company_id = $newChat->company_id;
                $reference_number =(isset($newChat->rfq)) ? $newChat->rfq->reference_number : '';
                $html = "<div class='rfqchatname fw-bold mb-2'>".$quote_number."<span class='badge bg-primary ms-2' style='font-size: 10px;'>".$reference_number."</span></div>";
            }
        @endphp

        <div class="rfqchatlist mb-1"
             onclick="chat.chatViewData('{{ route('new-chat-create-view')  }}', '{{$chat_id}}','{{$header_name}}','{{$ref_id}}', '{{ $newChat['id']??'' }}',$(this), '', '{{ $company_id??'' }}')" >
            <div class="p-2">
                {!!$html!!}
            </div>
        </div>
    @endforeach
@else
    <div class="blankchatpage">
        <img src="{{URL::asset('chat/images/blank_image.png')}}" alt="" srcset="">
    </div>
@endif



