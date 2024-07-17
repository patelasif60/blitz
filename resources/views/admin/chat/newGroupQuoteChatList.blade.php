<div class="search-bar p-2 px-4">
    <input class="form-control searchchatrfq" type="text" placeholder="{{ __('admin.search') }}" id="searchQuery_{{$header_name}}" onkeyup="chat.chatAdminSearch('{{ route("get-new-search-back-chat-view-ajax") }}','{{$header_name}}')" attr-name="catSearch">
</div>
<div class="message-names mt-3 p-2 px-3" id="adminGroupChatList_{{$header_name}}">
@if(!empty($newChatList))
    @foreach($newChatList as $newChat)
        @php
            $chat_id = (isset($newChat->chatGroupQuote)) ? $newChat->chatGroupQuote->_id : '';
            $groupChatName = isset($newChat->chatGroupQuote->group_name) ? $newChat->chatGroupQuote->group_name : $newChat->quote_number;
        @endphp
        <div class="rfq-name d-flex align-items-center mb-3 p-2" id="{{ $groupChatName }}"
             onclick="chat.chatAdminViewData('{{ route('get-chat-data-ajax')  }}','{{$chat_id}}','{{$header_name}}','{{$groupChatName}}', '{{ $newChat->id??'' }}',$(this), '{{ $newChat->company_id }}')">
           {{-- <div class="circleonline bg-success mx-3"></div>--}}
            <a href="javascript:void(0)" class="nav-link p-0 rfq-contact text-dark">
                <p class="brfq mb-0 fw-bold">{{$newChat->quote_number}}
                    <span class="badge bg-info ms-2" style="font-size: 10px;">{{(isset($newChat->rfq)) ? $newChat->rfq->reference_number : ''}}</span>
                    {{--<small class="text-primary ms-2">(BRFQ-963)</small>--}}
                </p>
            </a>
        </div>
    @endforeach
@else
    <div class="text-center fw-bold mb-3 p-2">
        <small>{{__('admin.no_chat_group')}}</small>
    </div>
@endif
</div>

