@if(!empty($getFeedbacks))
    @foreach($getFeedbacks as $getFeedback)
    <div class="row">
        <div class="col-md-12 border-bottom">
            <div  class="comment-header d-flex align-items-center my-2">
                <img src="{{ !empty($getFeedback['user']['profile_pic']) ? URL::asset('storage/'.$getFeedback['user']['profile_pic']) : URL::asset('/assets/images/user.png') }}" alt="">
                <div>
                    <h5 class="fw-bold header-text ms-3 mb-1">
                        {{ $getFeedback['user']['firstname'].' '. $getFeedback['user']['lastname'] }}
                        <small class="mx-1">-</small>
                        <span class="text-muted header-sub-text">{{ changeDateFormat($getFeedback['updated_at']) }} <span>{{ changeTimeFormat($getFeedback['updated_at']) }}</span></span>
                    </h5>
                    <div class="text-muted ms-3 " id="feedback_description">{{ ($getFeedback['reason']['reasons']) }}</div>
                </div>
                @if(Auth::user()->id == $getFeedback['user_id'])
                <a href="javascript:void(0);" class="pe-2 show-icon ms-auto" data-toggle="tooltip" ata-placement="top" title="{{ __('admin.edit') }}" data-bs-original-title="{{ __('admin.edit') }}" data-editid="{{ $getFeedback['reason']['id'] }}" onclick="feedbackEdit('{{$getFeedback['id']}}','{{$getFeedback['reason']['id']}}', this)">
                    <i class="fa fa-edit" aria-hidden="true"></i>
                </a>
                <a href="javascript:void(0);" class="show-icon" data-toggle="tooltip" ata-placement="top" title="{{ __('admin.delete') }}" data-bs-original-title="{{ __('admin.delete') }}" data-delete="{{ $getFeedback['id'] }}" onclick="feedbackDelete('{{$getFeedback['id']}}','{{$getFeedback['reason']['id']}}', '{{ $getFeedback['feedback_id'] }}', '{{ $getFeedback['feedback_type'] }}', this)">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                </a>
                @endif
            </div>
        </div>
    </div>
    @endforeach
@else
<div class="row">
    <span style="text-align: center">{{ __('admin.no_feedback_found') }}</span>
</div>
@endif
