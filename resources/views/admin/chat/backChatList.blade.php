<div class="search-bar p-2 px-4">
    <input class="form-control searchchatrfq" type="text" placeholder="{{ __('admin.search') }}" id="searchQuery" onkeyup="chat.chatAdminSearch('{{ route("get-front-search-chat-view-ajax") }}','{{$header_name}}')" attr-name="catSearch">
</div>
<div class="message-names mt-3 p-2 px-3" id="adminGroupChatList_{{$header_name}}">
    @if(Auth::user()->hasRole('admin'))
        @if(!$groupChatList->isEmpty())
            @foreach($groupChatList as $groupChat)

                <div class="rfq-name d-flex align-items-center mb-3 p-2 click" id="{{ $groupChat['group_name'] }}" onclick="chat.chatAdminViewData('{{ route('get-chat-data-ajax')  }}', '{{$groupChat['_id']}}', '{{$header_name}}','{{$groupChat['group_name']}}', '{{$groupChat['chat_id']}}', $(this), '{{$groupChat['company_id']??''}}') ">
                    {{--<div class="circleonline bg-success mx-3"></div>--}}
                    <a href="javascript:void(0)" class="nav-link p-0 rfq-contact text-dark">
                        @if($header_name == 'Rfq')
                            <p class="brfq mb-0 fw-bold">{{$groupChat['group_name']}}</p>
                        @elseif($header_name == 'Quote')
                            <p class="brfq mb-0 fw-bold">{{$groupChat['group_name']}}
                                <span class="badge bg-info ms-2" style="font-size: 10px;">{{$groupChat->quote->rfq->reference_number}}</span>
                            </p>
                        @endif
                        @if($groupChat['chat_id'])
                            <small class="time text-muted">{{\Carbon\Carbon::parse($groupChat['created_at'])->format('d-m-Y H:i')}}</small>
                        @endif
                    </a>
                    @if($groupChat['group_chat_member']['unread_message_count'] > 0)
                        <div class="ms-auto chat-count-none-backend">
                            <span class="badge bg-yellow text-black" style="border: 1px solid black;">{{$groupChat['group_chat_member']['unread_message_count']}}</span>
                        </div>
                    @endif
                </div>
            @endforeach
        @else
            <div class="text-center fw-bold mb-3 p-2">
                <small >{{__('admin.no_chat_group')}}</small>
            </div>
        @endif
    @endif
    @if(Auth::user()->hasRole('supplier'))
        @if(!$supplierRfq->isEmpty())
            @foreach($supplierRfq as  $supplier)
                @php
                    $chat_id = $supplier->chatGroup->chat_id??'';
                    $id = $supplier->chatGroup->_id??'';
                    $group_name = $supplier->chatGroup->group_name??'';
                    $rfq_number = $supplier->chatGroup->quote->rfq->reference_number??'';
                    $created_at = $supplier->chatGroup->created_at??'';
                    $company_id = $supplier->chatGroup->comapny_id??'';
                @endphp
                <div class="rfq-name d-flex align-items-center mb-3 p-2 click" id="{{ $group_name }}" onclick="chat.chatAdminViewData('{{ route('get-chat-data-ajax')  }}', '{{$id}}', '{{$header_name}}','{{$group_name}}', '{{$chat_id}}',$(this), '{{$company_id}}') ">
                    {{--<div class="circleonline bg-success mx-3"></div>--}}
                    <a href="javascript:void(0)" class="nav-link p-0 rfq-contact text-dark">
                        @if($header_name == 'Rfq')
                            <p class="brfq mb-0 fw-bold">{{$group_name}}</p>
                        @elseif($header_name == 'Quote')
                            <p class="brfq mb-0 fw-bold">{{$group_name}}
                                <span class="badge bg-info ms-2" style="font-size: 10px;">{{$rfq_number}}</span>
                            </p>
                        @endif
                        @if($chat_id)
                            <small class="time text-muted">{{\Carbon\Carbon::parse($created_at)->format('d-m-Y H:i')}}</small>
                        @endif

                    </a>
                    @if($supplier->unread_message_count > 0)
                        <div class="ms-auto chat-count-none-backend">
                            <span class="badge bg-yellow text-black" style="border: 1px solid black;">{{$supplier->unread_message_count}}</span>
                        </div>
                    @endif
                </div>

            @endforeach
        @else
            <div class="text-center fw-bold mb-3 p-2">
                <small >{{__('admin.no_chat_group')}}</small>
            </div>
        @endif
    @endif
    @if(Auth::user()->hasRole('agent'))
            @if(!$agentRfq->isEmpty())
                @foreach($agentRfq as  $agent)
                    @php
                        $chat_id = $agent->chatGroup->chat_id??'';
                        $id = $agent->chatGroup->_id??'';
                        $group_name = $agent->chatGroup->group_name??'';
                        $rfq_number = $agent->chatGroup->quote->rfq->reference_number??'';
                        $created_at = $agent->chatGroup->created_at??'';
                        $company_id = $agent->chatGroup->comapny_id??'';
                    @endphp
                    <div class="rfq-name d-flex align-items-center mb-3 p-2 click" id="{{ $group_name }}" onclick="chat.chatAdminViewData('{{ route('get-chat-data-ajax')  }}', '{{$id}}', '{{$header_name}}','{{$group_name}}', '{{$chat_id}}',$(this), '{{$company_id}}') ">
                        {{--<div class="circleonline bg-success mx-3"></div>--}}
                        <a href="javascript:void(0)" class="nav-link p-0 rfq-contact text-dark">
                            @if($header_name == 'Rfq')
                                <p class="brfq mb-0 fw-bold">{{$group_name}}</p>
                            @elseif($header_name == 'Quote')
                                <p class="brfq mb-0 fw-bold">{{$group_name}}
                                    <span class="badge bg-info ms-2" style="font-size: 10px;">{{$rfq_number}}</span>
                                </p>
                            @endif
                            @if($chat_id)
                                <small class="time text-muted">{{\Carbon\Carbon::parse($created_at)->format('d-m-Y H:i')}}</small>
                            @endif

                        </a>
                        @if($agent->unread_message_count > 0)
                            <div class="ms-auto chat-count-none-backend">
                                <span class="badge bg-yellow text-black" style="border: 1px solid black;">{{$agent->unread_message_count}}</span>
                            </div>
                        @endif
                    </div>

                @endforeach
            @else
                <div class="text-center fw-bold mb-3 p-2">
                    <small >{{__('admin.no_chat_group')}}</small>
                </div>
            @endif
        @endif
</div>
