<div class="card-header d-flex align-items-center">
{{--    <div class="head text-white" style="cursor: pointer" onclick="chat.chatPreviewPage('#allchatlist')">--}}
    <div class="head text-white" style="cursor: pointer">
        <span class="pe-2 d-none" id="removeBackIcon" onclick="chat.chatPreviewPage('#allchatlist')">
            <i class="fa fa-chevron-left text-white"></i>
        </span>
        <span onclick="chat.chatProductViewData('{{ $id }}', '{{ $type }}','{{ route("rfq-product-details-ajax") }}', '{{ $header_group_name }}')">
            {{ $header_group_name }}
        </span>
    </div>
    <div class="head_end_text text-white ms-auto">
        <div id="moreoption"  title="{{ __('admin.more_info')}}"  onclick="chat.chatProductViewData('{{ $id }}', '{{ $type }}','{{ route("rfq-product-details-ajax") }}', '{{ $header_group_name }}')">
            <span>
                <a href="javascript:void(0)">
                    <img src="{{ URL::asset('chat/images/threedotsv.png')}}" alt="" srcset="">
                </a>
            </span>
        </div>
    </div>
</div>
<div class="card-body pb-1">
    <div class="content h-100 ">
        <div class="chatwithsuppsection m-2 px-0 ">
            <div id="groupdataforshowmessage" data-group-name="{{ $header_group_name }}"></div>
            <div class="main w-100 py-2 chatheight" style="overflow-x: auto; height: 267px;">
                @if($getMessageLists->isEmpty())
                    <div class="col-md-12 mt-2 d-flex align-items-start px-2 pb-1">
                        <div class="col-md-auto pe-2 mt-1">
                            <span class="userfonticon"><i class="fa fa-user" style="font-size: 14px;"></i></span>
                        </div>
                        <div class="col-md-9 chatdetailfromadmin">
                            <div class="p-2">
                                <div class="name">blitznet Team</div>
                                <div class="text">{{__('admin.need_help_msg')}}</div>
                                <div class="time">{{changeTimeFormat(now())}}</div>
                            </div>
                        </div>
                    </div>
                @else
                    @php
                        $todayDate = changeDateFormat(now());
                        $yesterDay = changeDateFormat(date('d-m-Y',strtotime("yesterday")));
                        $dateUsed = [];
                    @endphp
                    @foreach($getMessageLists as $message)
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
                                    <span class="userfonticon">
                                        <i class="fa fa-user" style="font-size: 14px;"></i>
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="col-md-12 d-flex align-items-start px-2 pb-1">
                                <div class="col-md-auto pe-2 mt-1">
                                    <span class="userfonticon">
                                        <i class="fa fa-user" style="font-size: 14px;"></i>
                                    </span>
                                </div>
                                @php
                                    $name = $class = '';
                                    if($message->sender_role_id ==1){
                                        $name = 'blitznet Team';
                                        $class ='chatdetailfromadmin';
                                    }elseif($message->sender_role_id ==3){
                                        $name = $message->user->supplier()->pluck('name')->first();
                                        $class ='chatdetailfromsupplier';
                                    }elseif($message->sender_role_id ==5){
                                        $class ='chatdetailfromagent';
                                        $name = $message->user->full_name;
                                    }else{
                                        if ($message->sender_id != Auth::user()->id && $message->sender_role_id == 2){
                                            $class ='chatdetailfromuser';
                                        }
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
                <div id="addChatByMessageBox"></div>
            </div>
            <div class="typesection w-100 mt-auto ">
                <span id="showFilesName" class="ms-auto filessection shadow d-none"></span>
                <div class="input-group mb-0">
                    <input type="text" class="form-control position-relative text-wrap chat-input" title="{{ __('admin.rfq_info')}}" id="chatInput" onkeypress="chat.sendMessage(event, this, '{{ route("chat-create-ajax") }}','{{ $id }}', '{{ $chat_id??'' }}', '{{ $type }}', '{{ $header_group_name }}', '{{ Auth::user()->id }}', '{{ Auth::user()->role_id }}', '{{ $company_id??'' }}')" style="padding-right: 3.75rem;" placeholder="{{ __('admin.reply_here') }}" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="action pe-1">
                        <div class="accordion accordion-flush  d-flex chat-accordion" id="chataccordionFlush">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingOne">
                                    <button class="accordion-button collapsed" title="{{ __('admin.emoji')}}" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                        <img src="{{ URL::asset('chat/images/emoji.png')}}" alt="emoji" srcset="">
                                    </button>
                                </h2>
                                <div id="flush-collapseOne" class="accordion-collapse collapse smileysection" aria-labelledby="flush-headingOne" data-bs-parent="#chataccordionFlush">
                                    <div class="accordion-body p-0 " id="smiley"></div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <a class="click" href="javascript:void(0)" title="{{ __('admin.attachment')}}" >
                                    <input type="file" name="attachment[]" class="form-control" onchange="chat.openFileDialog(this)" id="image-attachment" accept=".jpg,.png,.pdf" multiple hidden /><label style="cursor: pointer;" for="image-attachment"><img src="{{ URL::asset('chat/images/attach.png')}}" alt="attach" srcset=""></label>
                                </a>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingTwo">
                                    <button class="accordion-button collapsed"  title="{{ __('admin.quick_message')}}" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                        <img src="{{URL::asset('chat/images/admin/msg.png')}}" alt="attach" srcset="">
                                    </button>
                                </h2>
                                <div id="flush-collapseTwo" class="accordion-collapse collapse message-suggestion" aria-labelledby="flush-headingTwo" data-bs-parent="#chataccordionFlush">
                                    <div class="accordion-body p-0">
                                        @if(!$quickMessages->isEmpty())
                                            @foreach($quickMessages as $message)
                                                <div class="col-12 pe-1" ><a class="chatQuickMessage" href="javascript:void(0)" onclick="chat.removeQuickMessagePopup()"  id="message_{{$message->id}}" class="ms-auto ">{{$message->message}}</a></div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <span class="input-group-text send-color click" onclick="chat.sendMessageOnclick(event, this, '{{ route("chat-create-ajax") }}','{{ $id }}', '{{ $chat_id??'' }}', '{{ $type }}', '{{ $header_group_name }}', '{{ Auth::user()->id }}', '{{ Auth::user()->role_id }}', '{{ $company_id??'' }}')">
                    <a href="javascript:void(0)">
                            <img src="{{ URL::asset('chat/images/send.png')}}" id="basic-addon2">
                    </a>
                    </span>
                </div>
                {{--<div class="input-group mb-0">
                    <div class="collapse message-suggestion shadow border"
                         id="collapseChat">
                        <div class="row mx-0">
                            @if(!$quickMessages->isEmpty())
                                @foreach($quickMessages as $message)
                                    <div class="col-12 pe-1" ><a class="chatQuickMessage" href="javascript:void(0)"  id="message_{{$message->id}}" class="ms-auto ">{{$message->message}}</a></div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>--}}
            </div>
        </div>
    </div>
</div>
<div class="card-footer">
    <div class="card-footer_text">{{ __('dashboard.authorised_by') }} <strong>blitz<em><i>net</i></em></strong></div>
</div>
<script>
    $(document).ready(function(){
        $('.action .accordion-collapse').on('show.bs.collapse', function (e) {
            $('.action .accordion-collapse').collapse("hide")
        });
        showEmoji();
    });

    $(document).on('click', '.click', function () {
        $('.action .accordion-collapse').collapse("hide");
        $('#chatInput').focus().trigger('click');
    });

    function showEmoji() {
        var emoji = '';
        for (var i = 128512; i <= 128566; i++) {
            emoji += `<span style="font-size: 22px; cursor: pointer;" onclick="getEmoji(this)">&#${i};</span>`;
        }
        $('#smiley').html(emoji);
    }

    function getEmoji(control) {
        document.getElementById('chatInput').value += control.innerHTML;
        $('#chatInput').focus().trigger('click');
    }

    $('#flush-collapseOne').on('click', function (e) {
        e.preventDefault();
        var emoji = '';
        for (var i = 128512; i <= 128566; i++) {
            emoji += `<span style="font-size: 22px; cursor: pointer;" onclick="getEmoji(this)">&#${i};</span>`;
        }

        $('#smiley').html(emoji);
    });
    $('.chatQuickMessage').click(function(e) {
        $('#smiley').removeClass("show");
        $('#collapseChat').removeClass("show");
        $('#chatInput').val($(this).text());
        e.stopPropagation();
    });
    </script>
