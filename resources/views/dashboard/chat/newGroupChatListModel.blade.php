<div class="card-header d-flex align-items-center">
    <div class="head text-white chatnametitle" onclick="chat.chatPreviewPage('#chatgrouplist')">
        <span class="pe-2">
            <i class="fa fa-chevron-left text-white"></i>
        </span>{{strtoupper($header_name)}}
    </div>

</div>
<div class="card-body">
    <div class="content ">
        <div class="searchbar m-2 px-2 d-flex align-items-center">
            <div class="">
                <input class="form-control form-control-sm searchchatrfq" id="searchQuery" onkeyup="chat.chatSearch('{{ route("get-new-search-chat-view-ajax") }}','{{$header_name}}')" style="width: 288px; height: 40px;" attr-name="catSearch" type="text" placeholder="{{ $header_name == 'Rfq'? __('dashboard.rfq_search'): __('dashboard.quote_search') }}">
            </div>
        </div>
    </div>
    <div class="allrfqchatlist">
        @if(!empty($newChatList))
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
                    $company_id = $newChat->rfq->company_id??0;
                    $reference_number =(isset($newChat->rfq)) ? $newChat->rfq->reference_number : '';
                    $html = "<div class='rfqchatname fw-bold mb-2'>".$quote_number."<span class='badge bg-primary ms-2' style='font-size: 10px;'>".$reference_number."</span></div>";
                }

                                                                                                                                                                                                                                                                                                                                                                                                      @endphp
                <div class="rfqchatlist mb-1" onclick="chat.chatViewData('{{ route('new-chat-create-view')  }}', '{{$chat_id}}','{{$header_name}}','{{$ref_id}}', '{{ $newChat['id']??'' }}',$(this), '', '{{ $company_id??'' }}')" >
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
    </div>
</div>
<div class="card-footer">
    <div class="card-footer_text">{{ __('dashboard.authorised_by') }} <strong>blitz<em><i>net</i></em></strong></div>
</div>

