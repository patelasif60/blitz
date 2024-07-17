<div class="card-header d-flex align-items-center">
    <div class="head text-white chatnametitle" style="cursor: pointer" onclick="chat.chatPreviewPage('#chatgrouplist', '{{ route("group-chattype-count-ajax") }}')">
        <span class="pe-2">
            <i class="fa fa-chevron-left text-white"></i>
        </span>{{strtoupper($header_name)}}
    </div>
    <div class="head_end_icon text-white ms-auto" class="btn text-white dropdown-toggle" title="{{ __('admin.new_chat')}}"  id="newchat"><span style="cursor: pointer;fill:white" onclick="chat.chatNewChatData('{{ route('get-new-chat-list-ajax') }}', '{{ $chatType }}', '{{ $header_name }}')"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 20 20">
        <path id="icon_plus_pro" d="M18.571,39.857H12.143V33.429A1.429,1.429,0,0,0,10.714,32H9.286a1.429,1.429,0,0,0-1.429,1.429v6.429H1.429A1.429,1.429,0,0,0,0,41.286v1.429a1.429,1.429,0,0,0,1.429,1.429H7.857v6.429A1.429,1.429,0,0,0,9.286,52h1.429a1.429,1.429,0,0,0,1.429-1.429V44.143h6.429A1.429,1.429,0,0,0,20,42.714V41.286A1.429,1.429,0,0,0,18.571,39.857Z" transform="translate(0 -32)"></path></svg>
        </span>
    </div>
</div>
<div class="card-body">
    <div class="content ">
        <div class="searchbar m-2 px-2 d-flex align-items-center">
            <div class="">
                <input class="form-control form-control-sm searchchatrfq" id="searchQuery" onkeyup="chat.chatSearch('{{ route("get-search-chat-view-ajax") }}','{{$header_name}}')" style="width: 288px; height: 40px;" attr-name="catSearch" type="text" placeholder=" {{ $header_name == 'Rfq' ? __('dashboard.rfq_exiting_search') : __('dashboard.quote_exiting_search') }}">
            </div>
        </div>
    </div>
    <div class="allrfqchatlist">
        @if(!empty($rfqChatList))
        @foreach($rfqChatList as $groupChat)
            @if(!empty($groupChat['group_chat_member']))
                   @php
                        if ($header_name == 'Rfq') {
                            $html = '<div class="rfqchatname fw-bold mb-2">'.$groupChat['group_name'].'</div>';
                        }elseif($header_name == 'Quote') {
                            $reference_number = (isset($groupChat['quote']['rfq'])) ? $groupChat['quote']['rfq']['reference_number'] : '';
                            $html = '<div class="rfqchatname fw-bold mb-2">'.$groupChat['group_name'].'<span class="badge bg-primary ms-2" style="font-size: 10px;">'.$reference_number.'</span></div>';
                        }
                    @endphp
              <div class="rfqchatlist mb-1" onclick="chat.chatViewData('{{ route("new-chat-create-view")  }}','{{$groupChat['_id']}}','{{$header_name}}','{{$groupChat['group_name']}}', '{{ $groupChat['chat_id'] }}',$(this), '', '{{ $groupChat['company_id']??'' }}')" >
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
    </div>
</div>
<div class="card-footer">
    <div class="card-footer_text">{{ __('dashboard.authorised_by') }} <strong>blitz<em><i>net</i></em></strong></div>
</div>

