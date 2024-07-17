<div class="search-bar p-2 px-4">
    <input class="form-control searchchatrfq" type="text" placeholder="{{ __('admin.search') }}" id="searchQuery_{{$header_name}}" onkeyup="chat.chatAdminSearch('{{ route("get-new-search-back-chat-view-ajax") }}','{{$header_name}}')" attr-name="catSearch">
</div>
<div class="message-names mt-3 p-2 px-3" id="adminGroupChatList_{{$header_name}}">
@if(!empty($newChatList))
    @foreach($newChatList as $newChat)
        @php
            $chat_id = (isset($newChat->chatGroupRfq)) ? $newChat->chatGroupRfq->_id : '';
            $groupChatName = (isset($newChat->chatGroupRfq)) ? $newChat->chatGroupRfq->group_name : $newChat->reference_number;
        @endphp
        <div class="rfq-name d-flex align-items-center mb-3 p-2 click" id="{{ $groupChatName }}"
             onclick="chat.chatAdminViewData('{{ route('get-chat-data-ajax')  }}','{{$chat_id}}','{{$header_name}}','{{$newChat->reference_number}}', '{{ $newChat->id??'' }}',$(this), '{{ $newChat->company_id }}')">
           {{-- <div class="circleonline bg-success mx-3"></div>--}}
            <a href="javascript:void(0)" class="nav-link p-0 rfq-contact text-dark">
                <p class="brfq mb-0 fw-bold">{{$newChat->reference_number}}</p>
            </a>
        </div>
    @endforeach
@else
    <div class="text-center fw-bold mb-3 p-2">
        <small>{{__('admin.no_chat_group')}}</small>
    </div>
@endif
</div>

