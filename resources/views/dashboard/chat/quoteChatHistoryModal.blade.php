<div class="modal-header dark_blue_bg text-white">
    <h6 class="modal-title" id="historyModalLabel">QUOTE No.{{$quoteNumber}} </h6>
    <button type="button" data-boolean="false" class="btn-close rounded-circle" data-bs-dismiss="modal" aria-label="Close"><img src="{{ URL::asset('front-assets/images/icons/close.png') }}" alt=""></button>
</div>
<div class="modal-body p-0">
    <ul class="nav nav-tabs mb-3 bg-info bg-opacity-10" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" data-rfqid="{{$quoteId}}" id="chat-history-tab" data-bs-toggle="tab"
               data-bs-target="#chat-history" type="button" role="tab" aria-controls="chat-history"
               aria-selected="false">{{__('admin.chat_history')}}</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="chat-history" role="tabpanel" aria-labelledby="chat-history-tab">
            <div class="card chat_section_1 border-0">
                <div class="card-body p-0">
{{--                                {{preDump($chatHistory[0]->groupChatMessage)}}--}}
                    <div class="chatwithsuppsection pb-lg-3" >
                        @if($chatHistory->isEmpty())
                        <div class="col-md-12 mt-2 d-flex align-items-start px-2 pb-1">
                            <div class="col-md-auto pe-2 mt-1">
                                <span class="userfonticon"><i class="fa fa-user" style="font-size: 14px;"></i></span>
                            </div>
                            <div class="col-md-9 chatdetailfromadmin">
                                <div class="p-2">
                                    <div class="name">{{__('admin.blitznet_team')}}</div>
                                    <div class="text">{{__('admin.need_help_msg')}}</div>
                                    <div class="time">{{ changeTimeFormat(now()) }}</div>
                                </div>
                            </div>
                        </div>
                        @else
                            @php
                                $todayDate = changeDateFormat(now());
                                $yesterDay = changeDateFormat(date('d-m-Y',strtotime("yesterday")));
                                $dateUsed = [];
                            @endphp
                            @foreach($chatHistory[0]->groupChatMessage as $message)
                                @php
                                    $messageDate = changeDateFormat($message->created_at);
                                    $messageType = isset($message->message_type) ? $message->message_type : 1;
                                    if ($messageType == 1){
                                        $innerMessage = $message->message;
                                    } elseif ($messageType == 2){
                                        if ($message->mimtype == 1){
                                          $innerMessage = '<a href="'.url('storage/'.$message->message).'" download><img src="'.url('storage/'.$message->message).'" class="mw-100"></a>';
                                        } else if ($message->mimtype == 2){
                                            $url = \Illuminate\Support\Facades\URL::asset('assets/images/PDF_icon.png');
                                            $innerMessage = '<a href="'.url('storage/'.$message->message).'" download><img src="'.$url.'" class="mw-100"></a>';
                                        }
                                    }
                                @endphp
                                @if(!in_array('today',$dateUsed) && $todayDate==$messageDate)
                                    @php
                                        array_push($dateUsed,'today');
                                    @endphp
                                    <div class="position-relative">
                                        <hr>
                                        <div class="message-time text-center text-muted bg-none chatdatewise">
                                            {{__('admin.today')}}
                                        </div>
                                    </div>
                                @elseif(!in_array('yesterday',$dateUsed) && $yesterDay==$messageDate)
                                    @php
                                        array_push($dateUsed,'yesterday');
                                    @endphp
                                    <div class="position-relative">
                                        <hr>
                                        <div class="message-time text-center text-muted bg-none chatdatewise">
                                            {{__('admin.yesterday')}}
                                        </div>
                                    </div>
                                @elseif(!in_array($messageDate,$dateUsed) && $todayDate!=$messageDate)
                                    @php
                                        array_push($dateUsed,$messageDate);
                                    @endphp
                                    <div class="position-relative">
                                        <hr>
                                        <div class="message-time text-center text-muted bg-none chatdatewise">
                                            {{$messageDate}}
                                        </div>
                                    </div>
                                @endif

                                @if($message->sender_id==auth()->user()->id)
                                    <div class="col-md-12 d-flex align-items-start buyerchatside px-2 pb-1">
                                        <div class="col-md-9 chatdetailfromuser">
                                            <div class="p-2">
                                                <div class="name">You</div>
                                                <div class="text">{!! $innerMessage !!}</div>
                                                <div class="time">{{changeTimeFormat($message->created_at)}}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-auto ps-2 mt-1">
                                            <span class="userfonticon"><i class="fa fa-user"style="font-size: 14px;"></i></span>
                                        </div>
                                    </div>
                                @else
                                    @php
                                        $name = $class = '';
                                        if($message->sender_role_id ==1){
                                            $name = 'blitznet Team';
                                            $class ='chatdetailfromadmin';
                                        }elseif($message->sender_role_id ==3){
                                            $name = $message->user->supplier()->pluck('name')->first();
                                            $class ='chatdetailfromsupplier';
                                        }else{
                                            $name = 'Buyer';
                                        }
                                        $messageType = isset($message->message_type) ? $message->message_type : 1;
                                        if ($messageType == 1){
                                            $innerMessage = $message->message;
                                        } elseif ($messageType == 2){
                                            if ($message->mimtype == 1){
                                              $innerMessage = '<a href="'.url('storage/'.$message->message).'" download><img src="'.url('storage/'.$message->message).'" class="mw-100"></a>';
                                            } else if ($message->mimtype == 2){
                                                $pdfImage = \Illuminate\Support\Facades\URL::asset('assets/images/PDF_icon.png');
                                                $innerMessage = '<a href="'.url('storage/'.$message->message).'" download><img src="'.$pdfImage.'" class="mw-100"></a>';
                                            }
                                        }
                                    @endphp
                                    <div class="col-md-12 mt-2 d-flex align-items-start px-2 pb-1">
                                        <div class="col-md-auto pe-2 mt-1">
                                            <span class="userfonticon"><i class="fa fa-user" style="font-size: 14px;"></i></span>
                                        </div>
                                        <div class="col-md-9 {{$class}}">
                                            <div class="p-2">
                                                <div class="name">{{$name}}</div>
                                                <div class="text">{!! $innerMessage !!}</div>
                                                <div class="time">{{changeTimeFormat($message->created_at)}}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                    <div class="col-md-12 text-end mt-auto p-3">
                        @php
                            $chatData = getChatDataForRfqById($quoteId, 'Quote');
                        @endphp
                        <a href="javascript:void(0)" onclick="chat.chatRfqViewData('{{ route('new-chat-create-view')  }}', '{{$chatHistory[0]->_id??""}}','Quote','{{$quoteNumber}}', '{{ $quoteId??'' }}',$(this), 1)" data-id="{{ $quoteId }}" class=" btn px-3 py-1" style="font-size: 12px;background-color: #B7CFFF;">
                            <img src="{{ URL::asset('front-assets/images/icons/chat_icon.png') }}"  style="max-height: 14px;" alt="View"  title="{{ __('admin.chat')}}" class="pe-1"> @if(!empty($chatData) && $chatData['unread_message_count'] != 0)<span class="bg-warning text-black px-1 ms-1 fw-bold rounded" style="font-size: 10px;">{{ $chatData['unread_message_count'] }}</span>@endif
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

