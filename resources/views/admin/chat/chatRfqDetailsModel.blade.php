<div class="row chat-main-section">
    <div class="col-md-3 pe-0 cahtlisting" class="collapse in collapsing" id="collapsechatarea">
        <div class="card border-0 rounded-0 h-100">
            <div class="col-md-12 message-content mt-2">
                <div class="message_newChat d-flex align-items-center p-2 px-4">
                    <div class="message-title fw-bold">{{ __('home_latest.message')}}</div>
                    <div class="newChat ms-auto" id="newChatButtonAdmin_{{$header_name}}" onclick="chat.backNew('{{$header_name}}')">
                        <a href="javascript:void(0)" class="btn btn-outline-dark btn-sm px-2 py-1"  id="newChatButton_{{$header_name}}" onclick="chat.chatAdminNewChatData('{{ route('get-admin-new-chat-list-ajax') }}', '{{$header_name}}', '{{$header_name}}')" style="font-size: 0.7rem;" type="button">{{__('admin.new_chat')}}</a>
                    </div>
                    <div class="newChat ms-auto d-none" id="backButton_{{$header_name}}" onclick="chat.backNew('{{$header_name}}')">
                        <a href="javascript:void(0)" class="btn btn-outline-dark btn-sm px-2 py-1"  id="newChatButtonBack_{{$header_name}}" onclick="chat.chatAdminBackData('{{ route('get-back-chat-data-ajax') }}', '{{$header_name}}')" style="font-size: 0.7rem;" type="button">{{__('admin.back')}}</a>
                    </div>
                </div>
                <div id="replaceWithSearch_{{$header_name}}">
                    <div class="search-bar p-2 px-4">
                        <input class="form-control searchchatrfq" type="text" placeholder="{{ __('admin.search') }}" id="searchQuery_{{$header_name}}" onkeyup="chat.chatAdminSearch('{{ route("get-front-search-chat-view-ajax") }}','{{$header_name}}')" attr-name="catSearch">
                    </div>
                    <div class="message-names mt-3 p-2 px-3" id="adminGroupChatList_{{$header_name}}">
                        @if(Auth::user()->hasRole('admin'))
                            @if(!$groupChatList->isEmpty())
                                @foreach($groupChatList as $groupChat)
                                    <div class="rfq-name d-flex align-items-center mb-3 p-2 click" id="{{ $groupChat['group_name'] }}" onclick="chat.chatAdminViewData('{{ route('get-chat-data-ajax')  }}', '{{$groupChat['_id']}}', '{{$header_name}}','{{$groupChat['group_name']}}', '{{$groupChat['chat_id']}}', $(this),'{{$groupChat['company_id']??''}}') ">
                                        <a href="javascript:void(0)" class="nav-link p-0 rfq-contact text-dark">
                                            <p class="brfq mb-0 fw-bold">{{$groupChat['group_name']}}</p>
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
                                        $chat_id = $supplier->chatGroup->chat_id;
                                        $id = $supplier->chatGroup->_id;
                                        $group_name = $supplier->chatGroup->group_name;
                                        $created_at = $supplier->chatGroup->created_at;
                                        $company_id = $supplier->chatGroup->comapny_id??'';
                                    @endphp
                                    <div class="rfq-name d-flex align-items-center mb-3 p-2 click" id="{{ $group_name }}" onclick="chat.chatAdminViewData('{{ route('get-chat-data-ajax')  }}', '{{$id}}', '{{$header_name}}','{{$group_name}}', '{{$chat_id}}',$(this),'{{$company_id}}') ">
                                        <a href="javascript:void(0)" class="nav-link p-0 rfq-contact text-dark">
                                            <p class="brfq mb-0 fw-bold">{{$group_name}}</p>
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
                                            $chat_id = $agent->chatGroup->chat_id;
                                            $id = $agent->chatGroup->_id;
                                            $group_name = $agent->chatGroup->group_name;
                                            $created_at = $agent->chatGroup->created_at;
                                            $company_id = $agent->company_id??'';
                                        @endphp
                                        <div class="rfq-name d-flex align-items-center mb-3 p-2 click" id="{{ $group_name }}" onclick="chat.chatAdminViewData('{{ route('get-chat-data-ajax')  }}', '{{$id}}', '{{$header_name}}','{{$group_name}}', '{{$chat_id}}',$(this), '{{$company_id}}') ">
                                            <a href="javascript:void(0)" class="nav-link p-0 rfq-contact text-dark">
                                                <p class="brfq mb-0 fw-bold">{{$group_name}}</p>
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
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9 p-0 pe-3 chatmiddlearea visible" id="middledata_{{$header_name}}"></div>
    <div class="col-md-3 d-none ps-0 rightdata" id="rightdata_{{$header_name}}"></div>
</div>
