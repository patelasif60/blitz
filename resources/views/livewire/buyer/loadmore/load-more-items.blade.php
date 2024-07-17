
<div class="d-flex align-items-center">
    <button wire:click="$emitSelf('loadMoreItems',{{$favrfq}},'{{$searchedText}}','{{$status}}')" class="btn btn-primary btn-sm me-2">
        {{ __('rfqs.load_more') }} 
        <div wire:loading>
            <i class="fa fa-spinner fa-pulse ms-2"></i>
        </div>
    </button>
    <div class="ms-auto text-end">
        <small class="text-muted">{{ __('order.showing') }} 1 {{ __('order.to') }} {{$currentRecord}} {{ __('order.of') }} {{$total}} {{ __('order.entries') }}</small>
    </div>
</div>