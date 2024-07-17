<div class="card border-0 rounded-0">
    <div class="middle-section d-flex align-items-center p-3">
        <div class="middle-message-name d-flex align-items-center">
            <div class="RFQ-Name mt-2 fw-bold" style="cursor: pointer;" onclick="chat.toggalInfo('{{ route('get-more-info-list-ajax') }}', '{{$chat_id}}' , '{{$id}}' , '{{$type}}')">{{$header_group_name}}</div>
        </div>
        <div class="ms-auto right-collapse">
            <button class="btn btn-transparent moreoption" data-toggle="tooltip" ata-placement="top" title="{{__('admin.more_info')}}" onclick="chat.toggalInfo('{{ route('get-more-info-list-ajax') }}', '{{$chat_id}}' , '{{$id}}' , '{{$type}}')"  type="button">
                <img src="{{URL::asset('chat/images/admin/threedots.png')}}" alt="" srcset="">
            </button>

        </div>
    </div>

    <div class="chat-content p-3 pb-1">
        <div id="groupdataforshowmessage" data-group-name="{{ $header_group_name }}"></div>
        <div class="chatbodytext mb-2 pe-2">
        @if($allMessage->isEmpty())
            @if(auth()->user()->role_id == 3)
                <div class="chat-message text-end mb-2">
                    <div class="card message-from-blitznet">
                        <div class="card-body p-1 px-2">
                            <div class="messanger-name fw-bold mb-0">blitznet Team</div>
                            <div class="card-text message mb-1 f-13">{{__('admin.need_help_msg')}}</div>
                            <div class="sender-message-time d-flex justify-content-end mt-1">{{changeTimeFormat(now())}}</div>
                        </div>
                    </div>
                </div>
                @else
                    <div class="text-center fw-bold mb-3 p-2">
                        <small >{{__('admin.no_chat_found')}}</small>
                    </div>
                @endif
        @else
            @php
            $todayDate = changeDateFormat(now());
            $yesterDay = changeDateFormat(date('d-m-Y',strtotime("yesterday")));
            $dateUsed = [];
            @endphp
            @foreach($allMessage as $message)
                @php
                    $name = $class = '';
                    if($message->sender_role_id == 2){
                        $name = $message->user->company()->pluck('name')->first();
                        $class ='message-from-sender';
                    }elseif($message->sender_role_id == 3){
                        if (auth()->user()->id == $message->sender_id || auth()->user()->role_id == 1){
                            $name =  $message->user->supplier()->pluck('name')->first();
                        } else {
                            $name = $message->sender_role_id == 3 ? 'Supplier': 'blitznet Team';
                        }
                        $class ='message-from-supplier';
                    }elseif($message->sender_role_id == 5){
                        $name = $message->user->full_name;
                        $class ='message-from-agent';
                    }
                    if (empty($name)){
                        $name = 'blitznet Team';
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

                    if (auth()->user()->id != $message->sender_id && auth()->user()->role_id == 3 && $message->sender_role_id == 3){
                        $innerMessage = '**********';
                    }
                @endphp
                    @php
                        $messageDate = changeDateFormat($message->created_at);
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
                @if($message->sender_id != auth()->user()->id)
                    <div class="card chat-message {{$class}} mb-2">
                        <div class="card-body p-1 px-2">
                            <div class="messanger-name fw-bold mb-0">{{ucfirst($name)}}</div>
                            <div class="card-text buyer-message f-13 mb-1">{!! $innerMessage !!}</div>
                            <div class="timer d-flex justify-content-end">
                                <small class="sender-message-time text-muted fw-bold">{{changeTimeFormat($message->created_at)}}</small>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="chat-message ms-auto text-end mb-2">
                        <div class="card message-from-blitznet">
                            <div class="card-body p-1 px-2">
                                <div class="messanger-name fw-bold mb-0">{{ $name?? 'blitznet Team'; }}</div>
                                <div class="card-text message mb-1 f-13">{!! $innerMessage !!}</div>
                                <div class="timer d-flex justify-content-end mt-1"><small class="sender-message-time text-muted fw-bold">{{changeTimeFormat($message->created_at)}}</small></div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        @endif
        <div id="addChatByMessageBox"></div>
        </div>
        <span id="showFilesName" class="ms-auto filessection shadow d-none"></span>
        <div class="chat-type-content mt-auto">
            <div class="input-group">
                <input type="text" id="chatInput_{{$type}}" class="form-control p-1 chat-type mb-0" onkeypress="chat.sendMessageBackend(event, this, '{{ route("chat-create-backend-ajax") }}','{{ $id }}', '{{ $chat_id??'' }}', '{{ $type }}', '{{ $header_group_name }}', '{{ Auth::user()->id }}', '{{ Auth::user()->role_id }}', '{{ $company_id }}')" placeholder="{{ __('admin.reply_here') }}" aria-label="Username" aria-describedby="basic-addon1" style="height: 36px;">
            </div>

            <div class="multiple-buttons d-flex justify-content-start align-items-center px-3 py-2">
                <div class="d-flex position-relative">
                    <div class="accordion accordion-flush  d-flex chat-accordion" id="chataccordionFlush">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingOne">
                                <button class="accordion-button collapsed"  title="{{__('admin.emoji')}}" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                    <img src="{{ URL::asset('chat/images/emoji.png')}}" alt="emoji" srcset="">
                                </button>
                            </h2>
                            <div id="flush-collapseOne" class="accordion-collapse collapse smileysection" aria-labelledby="flush-headingOne" data-bs-parent="#chataccordionFlush">
                                <div class="accordion-body p-0" id="smiley_{{$type}}"></div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <a class="click" href="javascript:void(0)" title="{{ __('admin.attachment')}}" >
                                <input type="file" name="attachment[]" class="form-control" onchange="chat.openFileDialog(this)" id="image-attachment" accept=".jpg,.png,.pdf" multiple hidden /><label style="cursor: pointer;" for="image-attachment"><img src="{{ URL::asset('chat/images/attach.png')}}" alt="attach" srcset=""></label>
                            </a>

                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingTwo">
                                <button class="accordion-button collapsed" title="{{ __('admin.quick_message')}}" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                    <img src="{{URL::asset('chat/images/admin/msg.png')}}" alt="attach" srcset="">
                                </button>
                            </h2>
                            <div id="flush-collapseTwo" class="accordion-collapse collapse message-suggestion" aria-labelledby="flush-headingTwo" data-bs-parent="#chataccordionFlush">
                                <div class="accordion-body p-0">
                                    @if(!$quickMessages->isEmpty())
                                        @foreach($quickMessages as $message)
                                            <div class="col-12 pe-1" ><a class="chatQuickMessage" onclick="chat.removeQuickMessagePopup()" href="javascript:void(0)"  id="message_{{$message->id}}" class="ms-auto ">{{$message->message}}</a></div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="javascript:void(0)" class="ms-auto click" onclick="chat.sendMessageBackendOnClick(event, this, '{{ route("chat-create-backend-ajax") }}','{{ $id }}', '{{ $chat_id??'' }}', '{{ $type }}', '{{ $header_group_name }}', '{{ Auth::user()->id }}', '{{ Auth::user()->role_id }}', '{{ $company_id??0 }}')">
                    <span class="input-group-text bg-transparent border-0 fs-5" id="basic-addon1"> <img src="{{URL::asset('chat/images/admin/send.png')}}" alt="" srcset=""></span>
                </a>
            </div>
        </div>
    </div>

</div>

<script>
    $(document).ready(function(){
        $('#chataccordionFlush .accordion-collapse').on('show.bs.collapse', function (e) {
            $('#chataccordionFlush .accordion-collapse').collapse("hide")
        });
        showEmoji();
    });

    $(document).on('click', '.click', function () {
        $('.chat-accordion .accordion-collapse').collapse("hide");
        $('#chatInput').focus().trigger('click');
    });

    function showEmoji() {
        var emoji = '';
        for (var i = 128512; i <= 128566; i++) {
            emoji += `<span style="font-size: 22px; cursor: pointer;" onclick="getEmoji(this)">&#${i};</span>`;
        }
        $('#smiley_Rfq').html(emoji);
    }
    /*function hideEmojiPanel() {
        $('#emoji').addClass('d-none')
    }*/
    function getEmoji(control) {
        document.getElementById('chatInput_Rfq').value += control.innerHTML;
        $('#chatInput_Rfq').focus().trigger('click');
        $('#chatInput_Quote').focus().trigger('click');
    }

    $('#showEmojiPanel').on('click', function (e) {
        e.preventDefault();
        var emoji = '';
        for (var i = 128512; i <= 128566; i++) {
            emoji += `<a href="javascript:void(0)" style="font-size: 22px; cursor: pointer;" onclick="getEmoji(this)">&#${i};</a>`;
        }
        $('#smiley_Rfq').html(emoji);
        $('#emoji').toggleClass('d-none');
        $('#smiley_Rfq').toggleClass("show");
        if ($('#chatQuickMessage').attr("aria-expanded") === "true") {
            $('#collapseChat').removeClass("show");
            $('#chatQuickMessage').attr('aria-expanded', 'true');
            //$('#chatQuickMessage').trigger('click')
        }
    });
    $('.chatQuickMessage').click(function(e) {
        //
        if ($('#showEmojiPanel').attr("aria-expanded") === "true") {
            $('#smiley_Rfq').removeClass("show");
            $('#showEmojiPanel').attr('aria-expanded', 'true');
            //$('#showEmojiPanel').trigger('click');
        }

        $('#collapseChat').removeClass("show");
        $('#chatInput_Rfq').val($(this).text());
        e.stopPropagation();
    });
</script>
