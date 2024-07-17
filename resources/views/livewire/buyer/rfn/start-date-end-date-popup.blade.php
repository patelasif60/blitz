<div wire:key="start-date-end-date-popup">
    <form class="" autocomplete="off" name="globalDatePopupForm" id="globalDatePopupForm" wire:submit.prevent="joinRfn" type="POST">
        @csrf
    <div class="row">
        <div class="col-md-6 text-start">
            <label for="" class="form-label">{{__('buyer.start_date')}}
                <span class="text-danger">*</span></label>
            <input type="text"
                   class="form-control calendericons"
                   name="convertStartDate" id="convertStartDate"
                   placeholder="dd-mm-yyyy" required=""
                   data-toggle="tooltip" data-placement="top"
                   title="{{__('buyer.start_date')}}" wire:model="convertStartDate">
            @error('convertStartDate') <span class="text-danger" id="convertStartDateError">{{ $message }}</span> @enderror
        </div>
        <div class="col-md-6 text-start">
            <label class="form-label" for="end_date">{{__('buyer.end_date')}}<span class="text-danger">*</span></label>
            <input type="text"
                   class="form-control calendericons"
                   name="convertEndDate" id="convertEndDate"
                   placeholder="dd-mm-yyyy" required=""
                   data-toggle="tooltip" data-placement="top"
                   title="{{__('buyer.end_date')}}" wire:model="convertEndDate">
            @error('convertEndDate') <span class="text-danger" id="convertEndDateError">{{ $message }}</span> @enderror
        </div>
    </div>
    </form>
</div>
<script type="text/javascript">
    var SnippetGlobalDatePopup = function () {
        fieldsInit = function () {
            $('#convertStartDate').datepicker({
                onSelect: function (date) {
                @this.set('convertStartDate', date);
                },
                dateFormat: "dd-mm-yy",
                minDate: "+1d"
            });
            $('#convertEndDate').datepicker({
                onSelect: function (date) {
                @this.set('convertEndDate', date);
                },
                dateFormat: "dd-mm-yy",
                minDate: "+1d"
            });
        },
            removeSingleError = function(){
                //company details validation remove
                $('#globalDatePopupForm input[type="text"]').on('input', function (evt) {
                    let inputId = $(this).attr('id');
                    window.livewire.emit('resetError',inputId,true);
                });
                $('#globalDatePopupForm select').on('change', function (evt) {
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
        SnippetGlobalDatePopup.init()
    });

</script>

