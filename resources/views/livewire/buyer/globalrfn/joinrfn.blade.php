<div wire:key="join-rfn-section">
    <form class="" autocomplete="off" name="joinRfnForm" id="joinRfnForm" wire:submit.prevent="joinRfn" type="POST">
        @csrf
        <div class="row mb-3">
            <div class="col-md-4 d-block">
                <label class="text-primary">{{__('buyer.start_date')}}: </label>
                <div class="text-dark fw-bold">{{date('d-m-Y', strtotime($startDate))}}</div>
            </div>
            <div class="col-md-4 d-block">
                <label class="text-primary">{{__('buyer.end_date')}}: </label>
                <div class="text-dark fw-bold">{{ date('d-m-Y', strtotime($endDate)) }}</div>
            </div>
            <div class="col-md-4 d-block">
                <label class="text-primary">{{__('dashboard.Product_Description')}}: </label>
                <div class="text-dark fw-bold">{{$productDestription}}</div>
            </div>
        </div>
        <div class="row mt-3 floatlables">
            <div class="col-md-6 col-xl-4 mb-4">
                <label for="quantity"
                       class="form-label quantity_for">{{__('dashboard.Quantity')}}
                    <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="rfnjoinquantity" id="rfnjoinquantity" wire:model="rfnjoinquantity" required="" value="">
                @error('rfnjoinquantity') <span class="text-danger" id="joinRfnquantityError">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-6 col-xl-4 mb-4">
                <label for="quantity"
                       class="form-label quantity_for">{{__('dashboard.Unit')}}
                    <span class="text-danger">*</span></label>
                <select class="form-select" id="unit" name="unit" wire:model="unit_id" disabled>
                    <option value="" disabled="">{{__('dashboard.Select Unit')}}</option>
                    @if(!empty($units))
                        @foreach($units as $unit)
                            <option value="{{ $unit->id}}">{{ $unit->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="col-md-6 col-xl-4">
                <label class="form-label" for="expectedDate">{{ __('dashboard.Expected_Delivery_Date') }}<span class="text-danger">*</span></label>
                <input type="text" class="form-control calendericons" name="joinRfnexpectedDate" id="joinRfnexpectedDate" placeholder="dd-mm-yyyy"   data-toggle="tooltip" data-placement="top" title="{{ __('admin.expected_delivery_date') }}" wire:model="joinRfnexpectedDate">
                @error('joinRfnexpectedDate') <span class="text-danger" id="joinRfnexpectedDateError">{{ $message }}</span> @enderror
            </div>


            <div class="col-md-12">
                <label class="form-label" for="comment">{{__('buyer.comment')}}</label>
                <textarea class="form-control"
                          placeholder="Give more information about your product"
                          name="rfnjoincomment" id="rfnjoincomment" wire:model="rfnjoincomment"></textarea>
                @error('rfnjoincomment') <span class="text-danger" id="rfnjoincommentError">{{ $message }}</span> @enderror
            </div>
        </div>
    </form>
</div>
@push('join-rfn')
<script type="text/javascript">
    var SnippetJoinRfn = function () {
            fieldsInit = function () {
                $('#joinRfnexpectedDate').datepicker({
                    onSelect: function (date) {
                    @this.set('joinRfnexpectedDate', date);
                    },
                    dateFormat: "dd-mm-yy",
                    minDate: "+1d"
                });
            },
            removeSingleError = function(){
                //company details validation remove
                $('#joinRfnForm input[type="text"]').on('input', function (evt) {
                    let inputId = $(this).attr('id');
                    window.livewire.emit('resetError',inputId,true);
                });
                $('#joinRfnForm select').on('change', function (evt) {
                    let inputId = $(this).attr('id');
                    window.livewire.emit('resetError',inputId,true);
                });
                $('#joinRfnForm textarea').on('input', function (evt) {
                    let inputId = $(this).attr('id');
                    window.livewire.emit('resetError',inputId,true);
                });
            }
        return {
            init: function () {
                fieldsInit(),removeSingleError()
            }
        }
    }(1);
    jQuery(document).ready(function(){
        SnippetJoinRfn.init()
    });

</script>
@endpush
