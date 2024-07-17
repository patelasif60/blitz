@extends('admin/adminLayout')

@section('content')

<div class="row">
    <div class="col-12 grid-margin">
        <div class="row">
            <div class="col-md-12 d-flex align-items-center mb-3">
                <h1 class="mb-0 h3">{{ __('admin.charges')}}</h1>
                <a href="{{ route('charges-list') }}" class="mb-2 backurl ms-auto btn-close"></a>
            </div>
            <div class="col-12">
                <ul class="nav nav-tabs bg-white newversiontabs ps-3" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-0 active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true"> {{ __('admin.edit_charge')}}</button>
                    </li>
                </ul>
                <div class="tab-content pt-3 pb-0" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <form id="chargesAdd" class="" method="POST" action="{{ route('charge-update') }}" data-parsley-validate>
                            @csrf
                    		<input type="hidden" name="id" value="{{ $charge->id }}">
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <div class="card">
                                    <div class="card-header d-flex align-items-center">
                                            <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/credit-card.png')}}" alt="Charges" class="pe-2"> <span>{{ __('admin.charges')}}</span></h5>
                                        </div>
                                        <div class="card-body p-3 pb-1">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="chargeName" class="form-label">{{ __('admin.charges')}}<span class="text-danger">*</span></label>
                                                    <input type="text" name="chargeName" id="chargeName" class="form-control" required value="{{ $charge->name }}">
                                                </div>
                                                <div class="col-md-6  mb-3">
                                                    <label for="chargeValue" class="form-label">{{ __('admin.value')}}<span class="text-danger">*</span></label>
                                                    <input type="text" name="chargeValue" id="chargeValue" class="form-control" required value="{{ $charge->charges_value }}">
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                <label for="chargeType" class="form-label">{{ __('admin.type')}}<span class="text-danger">*</span></label>
                                                        <select class="form-select selectBox" name="chargeType">
                                                            <option {{ $charge->type == 0 ? 'selected="selected"' : '' }}  value="0">%</option>
                                                            <option {{ $charge->type == 1 ? 'selected="selected"' : '' }}  value="1">RP (Flat)</option>
                                                        </select>
                                                        <i class="fa fa-chevron-down"></i>
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                <label for="charges_type" class="form-label">{{ __('admin.charges_type')}}<span class="text-danger">*</span></label>
                                                <select class="form-select selectBox" name="charges_type" onchange="chargesTypeOnchange(this.value)">
                                                    <option {{ $charge->charges_type == 0 ? 'selected="selected"' : '' }}  value="0">{{ __('admin.supplier_other_charges') }}</option>
                                                    <option {{ $charge->charges_type == 1 ? 'selected="selected"' : '' }}  value="1">{{ __('admin.logistic_charges') }}</option>
                                                    <option {{ $charge->charges_type == 2 ? 'selected="selected"' : '' }}  value="2">{{ __('admin.platform_charges') }}</option>
                                                </select>
                                                <i class="fa fa-chevron-down"></i>
                                                </div>
                                                <div class="col-md-6">
                                                <label for="addition_substraction" class="form-label">{{ __('admin.addition_or_subtraction')}}<span class="text-danger">*</span></label>
                                                <select class="form-select selectBox" name="addition_substraction">
                                                    <option {{ $charge->addition_substraction == 0 ? 'selected="selected"' : '' }} value="0">Discount (-)</option>
                                                    <option {{ $charge->addition_substraction == 1 ? 'selected="selected"' : '' }} value="1">Charges (+)</option>
                                                </select>
                                                <i class="fa fa-chevron-down"></i>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label for="description" class="form-label">{{ __('admin.description')}}</label>
								                    <textarea class="form-control newtextarea" id="description" name="description">{{ $charge->description }}</textarea>
                                                </div>
                                                <div class="col-md-12 d-flex mb-3">
                                                <label for="status" class="form-label me-2">{{ __('admin.status')}}</label>
                                                    <div>
                                                        <input type="radio" value="1" name="status" {{ $charge->status == 1 ? 'checked' : '' }}> {{ __('admin.active')}}
                                                        <input type="radio" value="0" name="status" {{ $charge->status == 0 ? 'checked' : '' }}> {{ __('admin.deactive')}}
                                                    </div>
                                                    <div class="form-check ms-auto mt-0 {{$charge->charges_type == 2? '': 'd-none'}}" id="buyer_editable" >
                                                        <input class="form-check-input rounded-0" type="checkbox" name="editable" value="{{$charge->editable??0}}" id="editable" onclick="buyerEditableValue()" {{$charge->charges_type == 2? '': 'disabled'}} {{$charge->editable == 1? 'checked': ''}}>
                                                        <label class="form-label fw-bold" for="flexCheckDefault"> Editable for buyer? </label>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 bg-white py-3 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">{{ __('admin.update')}}</button>
                                    <a href="{{ route('charges-list') }}" class="btn btn-cancel ms-3">
                                        {{ __('admin.cancel')}}
                                    </a>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('scripts')
<script>
    $("#chargesAdd").on('submit',function(event){
        event.preventDefault();
        var formData = new FormData($("#chargesAdd")[0]);
        formData.append('description', tinyMCE.get('description').getContent());
        if ($('#chargesAdd').parsley().validate()) {
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data : formData,
                contentType: false,
                processData: false,

                success: function (r) {
                    if(r.success == true) {
                        resetToastPosition();
                        $.toast({
                            heading: "{{__('admin.success')}}",
                            text: "{{__('admin.charges_updated_alert')}}",
                            showHideTransition: "slide",
                            icon: "success",
                            loaderBg: "#f96868",
                            position: "top-right",
                        });
                        setTimeout(function(){window.top.location=$(".backurl").attr('href')} , 3000);
                    } else {
                        resetToastPosition();
                        $.toast({
                            heading: "{{__('admin.success')}}",
                            text: "{{__('admin.charges_exist')}}",
                            showHideTransition: "slide",
                            icon: "error",
                            loaderBg: "#f96868",
                            position: "top-right",
                        });
                        setTimeout(function() {
                            window.top.location = $(".backurl").attr('href')
                        }, 3000);
                    }
                },
                error: function (xhr) {
                    alert('{{__('admin.error_while_selecting_list')}}');
                }
            });

        }
    });
    function chargesTypeOnchange(charge_type){
        if(charge_type == 2){
            $("#buyer_editable").removeClass('d-none');
            $('#editable').prop('disabled', false);
            $("#editable").prop("checked", true);
            $('#editable').val('1');
        }else{
            $("#buyer_editable").addClass('d-none')
            $('#editable').prop('disabled', true);
            $("#editable").prop("checked", false);
            $('#editable').val('0');
        }
    }
    function buyerEditableValue() {
        if($("#editable").prop('checked') == true){
            $('#editable').val('1');
        }else{
            swal({
                text: "{{ __('admin.charge_editable_message') }}",
                icon: "/assets/images/info.png",
                buttons: ["{{ __('admin.no') }}", "{{ __('admin.yes') }}"],
                closeOnClickOutside: false
            }).then((willCheck) => {
                if (willCheck) {
                    $("#editable").prop("checked", false);
                    $('#editable').val('0');
                } else {
                    $("#editable").prop("checked", true);
                }
            });

        }
    }
</script>
@stop
