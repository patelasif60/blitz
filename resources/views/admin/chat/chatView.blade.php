@extends('admin/adminLayout')
@section('content')
    <div class="col-12 grid-margin  h-100">

        <div class=" row">
            <div class="col-md-12 mb-3 d-flex align-items-center">
                <h1 class="mb-0 h3">{{ __('admin.chat')}}</h1>
            </div>
            <div class="col-12">
                <ul class="nav nav-tabs bg-white newversiontabs ps-3" role="tablist">
                    <li class="nav-item active">
                        <a class="nav-link px-0 active chat-bg" id="rfq-tab" data-bs-toggle="tab"
                           onclick="chat.chatAdminChatTypeData('{{ route('chat-wise-view-ajax') }}', 'Rfq')"
                           href="#rfq_chat_id" role="tab" aria-controls="rfq_chat_id"
                           aria-selected="true">{{ __('dashboard.my_rfqs') }}
                            @if($rfqCount > 0)
                                <span class="dots" id="dots_Rfq" hidden></span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-0 mx-3 chat-bg" id="quote-tab" data-bs-toggle="tab"
                            href="#quote_chat_id" onclick="chat.chatAdminChatTypeData('{{ route('chat-wise-view-ajax') }}', 'Quote')"
                            role="tab" aria-controls="quote_chat_id"
                            aria-selected="false">{{ __('admin.quotes') }}
                            @if($quoteCount > 0)
                                <span class="dots" id="dots_Quote"></span>
                            @endif
                        </a>
                    </li>
                    @if(Auth::user()->role_id == ADMIN)
                    <li class="nav-item">
                        <a class="nav-link px-0 mx-3 chat-bg" id="support-tab" data-bs-toggle="tab"
                           href="#support_chat_id"
                           onclick="chat.chatAdminChatTypeData('{{ route('chat-wise-view-ajax') }}', 'Support')"
                           role="tab" aria-controls="support_chat_id" aria-selected="false">{{ __('admin.support') }}
                            @if($supportChatCount > 0)
                                <span class="dots" id="dots_Support"></span>
                            @endif
                        </a>
                    </li>
                    @endif
                </ul>
                <div class="tab-content pb-2 pt-3">
                    <div class="tab-pane fade active show" id="rfq_chat_id" role="tabpanel" aria-labelledby="rfq-tab">
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
                                            <a href="javascript:void(0)" class="btn btn-outline-dark btn-sm px-2 py-1"  id="newChatButtonBack_{{$header_name}}" onclick="chat.chatAdminBackData('{{ route('get-back-chat-data-ajax') }}','{{$header_name}}')" style="font-size: 0.7rem;" type="button">{{__('admin.back')}}</a>
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
                                                        @php
                                                            $groupCountRfq = $groupChat['group_chat_member']['unread_message_count']??0;
                                                        @endphp
                                                        <div class="rfq-name d-flex align-items-center mb-3 p-2 click" id="{{ $groupChat['group_name'] }}" onclick="chat.chatAdminViewData('{{ route('get-chat-data-ajax')  }}', '{{$groupChat['_id']}}', '{{$header_name}}','{{$groupChat['group_name']}}', '{{$groupChat['chat_id']}}', $(this), '{{ $groupChat['company_id'] }}')">
                                                            <a href="javascript:void(0)" class="nav-link p-0 rfq-contact text-dark">
                                                                <p class="brfq mb-0 fw-bold">{{$groupChat['group_name']}}</p>
                                                                @if($groupChat['chat_id'])
                                                                    <small class="time text-muted">{{\Carbon\Carbon::parse($groupChat['created_at'])->format('d-m-Y H:i')}}</small>
                                                                @endif
                                                            </a>
                                                            @if($groupCountRfq != 0)
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
                                                            $supplierCountRfq = $supplier->unread_message_count??0;
                                                            $company_id = $supplier->chatGroup->comapny_id;
                                                        @endphp
                                                        <div class="rfq-name d-flex align-items-center mb-3 p-2 click" id="{{ $group_name }}" onclick="chat.chatAdminViewData('{{ route('get-chat-data-ajax')  }}', '{{$id}}', '{{$header_name}}','{{$group_name}}', '{{$chat_id}}',$(this), '{{ $company_id }}')">
                                                            <a href="javascript:void(0)" class="nav-link p-0 rfq-contact text-dark">
                                                                <p class="brfq mb-0 fw-bold">{{$group_name}}</p>
                                                                @if($chat_id)
                                                                    <small class="time text-muted">{{\Carbon\Carbon::parse($created_at)->format('d-m-Y H:i')}}</small>
                                                                @endif

                                                            </a>
                                                            @if($supplierCountRfq != 0)
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
                                                                $agentCountRfq = $agent->unread_message_count??0;
                                                                $company_id = $agent->company_id;
                                                            @endphp
                                                            <div class="rfq-name d-flex align-items-center mb-3 p-2 click" id="{{ $group_name }}" onclick="chat.chatAdminViewData('{{ route('get-chat-data-ajax')  }}', '{{$id}}', '{{$header_name}}','{{$group_name}}', '{{$chat_id}}',$(this), '{{ $company_id }}')">
                                                                <a href="javascript:void(0)" class="nav-link p-0 rfq-contact text-dark">
                                                                    <p class="brfq mb-0 fw-bold">{{$group_name}}</p>
                                                                    @if($chat_id)
                                                                        <small class="time text-muted">{{\Carbon\Carbon::parse($created_at)->format('d-m-Y H:i')}}</small>
                                                                    @endif

                                                                </a>
                                                                @if($agentCountRfq != 0)
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
                    </div>
                    <div class="tab-pane fade" id="quote_chat_id" role="tabpanel" aria-labelledby="quote-tab">
                        {{-- code for quote --}}
                    </div>
                    @if(Auth::user()->role_id == ADMIN)
                    <div class="tab-pane fade" id="support_chat_id" role="tabpanel" aria-labelledby="support-tab">
                        {{-- code for Support chat --}}
                    </div>
                    @endif
                    <div class="tab-pane fade" id="profile-2" role="tabpanel" aria-labelledby="order-tab">
                    {{-- code for order --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function(){
            var chat_redirect_type = window.localStorage.getItem('chat_redirect_type');
            var chat_redirect_id = window.localStorage.getItem('chat_redirect_id');
            var chat_redirect_group_name = window.localStorage.getItem('chat_redirect_group_name');
            if(chat_redirect_type != '' && chat_redirect_id != '' && chat_redirect_group_name != ''){
                if(chat_redirect_type == 'Rfq') {
                    window.onclick = chat.chatAdminViewData("{{ route('get-chat-data-ajax')  }}", '', chat_redirect_type, chat_redirect_group_name, chat_redirect_id, $(this));
                    setTimeout(function (){
                        if(adminNewChatStatusForRedirect){
                            document.querySelector('#newChatButton_'+chat_redirect_type).click();
                        }
                        setTimeout(function () {
                            $('#' + chat_redirect_group_name).addClass('shadow')
                            $('#adminGroupChatList_'+chat_redirect_type).animate({
                                scrollTop: $('#' + chat_redirect_group_name).offset().top - $('#adminGroupChatList_'+chat_redirect_type).offset().top + $('#adminGroupChatList_'+chat_redirect_type).scrollTop()
                            });
                        }, 1000);
                    }, 1000);
                } else if(chat_redirect_type == 'Quote'){
                    document.querySelector('#quote-tab').click();
                    window.onclick = chat.chatAdminChatTypeData("{{ route('chat-wise-view-ajax')  }}", chat_redirect_type);
                    window.onclick = chat.chatAdminViewData("{{ route('get-chat-data-ajax')  }}", '', chat_redirect_type, chat_redirect_group_name, chat_redirect_id, $(this));
                    setTimeout(function (){
                        if(adminNewChatStatusForRedirect){
                            document.querySelector('#newChatButton_'+chat_redirect_type).click();
                        }
                        setTimeout(function () {
                            $('#' + chat_redirect_group_name).addClass('shadow')
                            $('#adminGroupChatList_'+chat_redirect_type).animate({
                                scrollTop: $('#' + chat_redirect_group_name).offset().top - $('#adminGroupChatList_'+chat_redirect_type).offset().top + $('#adminGroupChatList_'+chat_redirect_type).scrollTop()
                            });
                        }, 1000);
                    }, 1000);
                }
            }
            adminNewChatStatusForRedirect = 0;
            window.localStorage.setItem('chat_redirect_type', '');
            window.localStorage.setItem('chat_redirect_id', '');
            window.localStorage.setItem('chat_redirect_group_name', '');
        });
        function closebtn(header_name){
            $('#middledata_'+header_name).removeClass("col-md-6");
            $('#middledata_'+header_name).addClass("col-md-9 pe-3");
            $('#rightdata_'+header_name).addClass("d-none");
            $('.right-collapse').removeClass("d-none");
        }
    </script>
@endsection
