
<div>
<style type="css">
    .filter-multi-select>.viewbar>.selected-items>.item {
        margin: .125rem .25rem .125rem 0 !important;
        padding: 0px 0.8em 0px 0.8em;
    }

    .filter-multi-select>.dropdown-menu>.filter>button {
        top: 2rem;
    }

</style>

    <form id="businessDetailsFrm" enctype="multipart/form-data" autocomplete="off" method="{{$updateMode==true ? 'PUT' : 'POST'}}" action="{{ $updateMode==true ? route('supplier.business.update',Crypt::encrypt($supplier->id)) : route('supplier.business.store',isset($supplier->id) ? Crypt::encrypt($supplier->id) : '') }}" autocomplete="off">
        <input type="hidden" name="formType" value="businessDetails">
        <input type="hidden" name="user_id" id="user_id" value="{{ isset($supplier->id) ? Crypt::encrypt($supplier->id) : '' }}">
        <input type="hidden" name="inputErrors" class="inputErrors" value="">
        <div class="card mb-3">
            <div class="card-header d-flex align-items-center">
                <h5 class="mb-0"><img height="20px" src="{{URL::asset('assets/icons/icon_company.png')}}" alt="Company" class="pe-2">
                    <span>{{__('admin.business_details')}}</span>
                </h5>
            </div>
            <div class="card-body p-3 pb-1">
                @csrf
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="founders" class="form-label">{{ __('admin.founder')}}</label>
                        <input type="text" class="form-control max-255" id="founders" name="founders" value="{{ $companyDetails->founders ?? ''}}" placeholder="{{ __('admin.founder_placehoder')}}" >
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="name" class="form-label">{{ __('admin.ceo')}}</label>
                        <input type="text" class="form-control max-255" placeholder="{{ __('admin.ceo_placeholder') }}" id="name" name="name" value="{{ $companyDetails->name ?? ''}}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="headquarters" class="form-label">{{ __('admin.headquaters')}}</label>
                        <input type="text" class="form-control max-255" placeholder="{{ __('admin.headquaters_placeholder') }}" id="headquarters" name="headquarters" value="{{ $companyDetails->headquarters ?? ''}}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="product_services" class="form-label">{{ __('admin.products_and_services')}}</label>
                        <input type="text" class="form-control max-255" id="product_services" name="product_services" value="{{ $companyDetails->product_services ?? ''}}" placeholder="{{ __('admin.products_and_services_palcehoder') }}" >
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="number_of_employee" class="form-label">{{ __('admin.number_of_employees')}}</label>
                        <select name="number_of_employee" id="number_of_employee" class="form-select">
                            <option value="">Select {{ __('admin.number_of_employees')}}</option>
                            <option value="1-10" @if(isset($companyDetails->number_of_employee) && $companyDetails->number_of_employee == "1-10") {{ 'selected' }} @endif>1-10</option>
                            <option value="11-50" @if(isset($companyDetails->number_of_employee) && $companyDetails->number_of_employee == "11-50") {{ 'selected' }} @endif>11-50</option>
                            <option value="51-200" @if(isset($companyDetails->number_of_employee) && $companyDetails->number_of_employee == "51-200") {{ 'selected' }} @endif>51-200</option>
                            <option value="201-500" @if(isset($companyDetails->number_of_employee) && $companyDetails->number_of_employee == "201-500") {{ 'selected' }} @endif>201-500</option>
                            <option value="501-1000" @if(isset($companyDetails->number_of_employee) && $companyDetails->number_of_employee == "501-1000") {{ 'selected' }} @endif>501-1000</option>
                            <option value="1001-5000" @if(isset($companyDetails->number_of_employee) && $companyDetails->number_of_employee == "1001-5000") {{ 'selected' }} @endif>1001-5000</option>
                            <option value="5001-10,000" @if(isset($companyDetails->number_of_employee) && $companyDetails->number_of_employee == "5001-10,000") {{ 'selected' }} @endif>5001-10,000</option>
                            <option value="10,001+" @if(isset($companyDetails->number_of_employee) && $companyDetails->number_of_employee == "10,001+") {{ 'selected' }} @endif>10,001+</option>
                        </select>
                        <small id="number_of_employeeError" class="errorValidate text-danger"></small>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="sector" class="form-label">{{ __('admin.sector') }}</label>
                        <select name="sector" id="sector" class="form-select">
                            <option value="1" @if(isset($companyDetails->sector) && $companyDetails->sector == 1) {{ 'selected' }} @endif>Primary Sector (Raw Materials)</option>
                            <option value="2" @if(isset($companyDetails->sector) && $companyDetails->sector == 2) {{ 'selected' }} @endif>Secondary Sector (Manufactured Products)</option>
                            <option value="3" @if(isset($companyDetails->sector) && $companyDetails->sector == 3) {{ 'selected' }} @endif>Tertiary Sector (Services)</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="financial_target" class="form-label">{{ __('admin.financial_target') }}</label>
                        <div class="input-group">
                            <div class="d-flex w-100">
                                <select name="financial_target_currency" class="form-select border-end-0" style="border-radius: 0px;width:65px;color:#767676;text-align:center;background-image:none !important;background-color: rgba(0, 0, 0, 0.05);" id="financial_target_currency" disabled>
                                    <option value="IDR" selected>IDR</option>
                                    <option value="Rp">Rp</option>
                                </select>
                                <input type="text" class="form-control max-255" maxlength="35" name="financial_target" id="financial_target" value="{{ $companyDetails->financial_target ?? ''}}" placeholder="{{ __('admin.financial_target_palceholder') }}" data-inputmask="'alias':'numeric','groupSeparator': ',', 'autoGroup' : true, 'showMaskOnHover':false, 'digits': 2, 'digitsOptional': false, 'placeholder': '0.00', 'rightAlign':false">
                            </div>
                        </div>
                        <small id="financial_targetError" class="errorValidate text-danger"></small>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="net_income" class="form-label">{{ __('admin.profile_net_income') }} </label>
                        <div class="input-group">
                            <div class="d-flex w-100">
                                <select name="net_income_currency" class="form-select border-end-0" style="border-radius: 0px;width:65px;color:#767676;text-align:center;background-image:none !important;background-color: rgba(0, 0, 0, 0.05);" id="net_income_currency" disabled>
                                    <option value="IDR" selected>IDR</option>
                                    <option value="Rp">Rp</option>
                                </select>
                                <input type="text" class="form-control max-255" maxlength="35" name="net_income" id="net_income" value="{{ $companyDetails->net_income ?? ''}}" placeholder="{{ __('admin.profile_net_income_placehoder') }}" data-inputmask="'alias':'numeric','groupSeparator': ',', 'autoGroup' : true, 'showMaskOnHover':false, 'digits': 2, 'digitsOptional': false, 'placeholder': '0.00', 'rightAlign':false">
                            </div>
                        </div>
                        <small id="net_incomeError" class="errorValidate text-danger"></small>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="annual_sales" class="form-label">{{ __('admin.annual_sales') }} </label>
                        <div class="input-group">
                            <div class="d-flex w-100">
                                <select name="annual_sales_currency" class="form-select border-end-0" style="border-radius: 0px;width:65px;color:#767676;text-align:center;background-image:none !important;background-color: rgba(0, 0, 0, 0.05);" id="annual_sales_currency" disabled>
                                    <option value="IDR" selected>IDR</option>
                                    <option value="Rp">Rp</option>
                                </select>
                                <input type="text" class="form-control max-255" maxlength="35" name="annual_sales" id="annual_sales" value="{{ $companyDetails->annual_sales ?? ''}}" placeholder="{{ __('admin.annual_sales_placehoder') }}" data-inputmask="'alias':'numeric','groupSeparator': ',', 'autoGroup' : true, 'showMaskOnHover':false, 'digits': 2, 'digitsOptional': false, 'placeholder': '0.00', 'rightAlign':false">
                            </div>
                        </div>
                        <small id="annual_salesError" class="errorValidate text-danger"></small>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="sector_type" class="form-label">{{ __('admin.sector_type') }}</label>
                        <div class="ps-4">
                            <div class="form-check form-check-inline me-3">
                                <input class="form-check-input" type="radio" name="sector_type" id="public" value="1" @if(isset($companyDetails->sector_type) && $companyDetails->sector_type == 1) {{ 'checked' }} @endif>
                                <label class="form-check-label" for="public">Public </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="sector_type" id="private" value="2" @if(isset($companyDetails->sector_type) && $companyDetails->sector_type == 2) {{ 'checked' }} @endif>
                                <label class="form-check-label" for="private">Private </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="company_description" class="form-label">{{ __('admin.about_company') }}</label>
                        <textarea name="company_description" class="form-control" placeholder="{{ __('admin.about_company_placeholder') }}" id="company_description" cols="30" rows="3" aria-hidden="true">{{ $companyDetails->company_description ?? ''}}</textarea>
                        <small id="company_descriptionError" class="errorValidate text-danger"></small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 pb-2">
                <div class="card">
                    <div
                        class="card-header d-flex align-items-center">
                        <h5 class="mb-0">
                            <span>{{ __('admin.category_details')}}</span>
                        </h5>
                    </div>
                    <div class="card-body p-3 pb-1">
                        <div class="row">
                            <div class="col-md-12 mb-3 position-relative multiselectall">
                                <label class="form-label">{{ __('admin.dealing_with_categories') }} </label>
                                <select id="all_suppliers123" name="supplier_category[]" class="form-control" multiple="multiple"></select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <div class="text-end">
            <button class="btn btn-primary" type="submit" id="btnBusinessDetailSubmit">{{ __('admin.save')}}</button>
        </div>
    </form>
    <script src="{{ URL::asset('assets/vendors/inputmask/jquery.inputmask.bundle.js') }}"></script>
    <script type="text/javascript">
        $("#net_income,#annual_sales,#financial_target").inputmask({
            onKeyDown:function(event,value){
                if (event.originalEvent.code == 'Delete' || event.originalEvent.code == 'Backspace') {
                    if (value.join('') <= 0) event.target.value = '';
                }
            }
        });
        $('#businessDetailsFrm').on('submit',function(e){
            e.preventDefault();
            $('#businessDetailsFrm .inputErrors').val('');
            $('#businessDetailsFrm .errorValidate').html('');
            var formData = $('#businessDetailsFrm').serializeArray();
            var url = $('#businessDetailsFrm').attr('action').replace(/\s+/g, "");
            var method = $('#businessDetailsFrm').attr('method').replace(/\s+/g, "");
            var supplierId = $('#businessDetailsFrm #user_id').val();

            $.ajax({
                url: url,
                data: formData,
                type: method,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                async:false,
                success: function(data) {
                    if(data.success) {
                        resetToastPosition();

                        SnippetApp.toast.success("{{ __('admin.success') }}",data.message);

                        $('#businessDetailsFrm .errorValidate').html('');
                        var updateAction = "{{ route('supplier.business.update', [':id']) }}";
                        updateAction = updateAction.replace(":id", supplierId);

                        $('#businessDetailsFrm').attr('action',updateAction);
                        $('#businessDetailsFrm').attr('method','PUT');
                    } else {
                        resetToastPosition();
                        SnippetApp.toast.alert("{{ __('admin.warning') }}",data.message);

                    }
                },
                error:function(e){
                    if (e.status == 422){
                        var errorsArr = [];
                        $('#businessDetailsFrm .errorValidate').html('');
                        $.each(e.responseJSON.errors, function(key,value) {
                            $('#businessDetailsFrm #'+key+'Error').html(value);
                            errorsArr.push(key);
                        });
                        $('#businessDetailsFrm .inputErrors').val(errorsArr);
                        $("#businessDetailsFrm").find(".errorValidate:not(:empty)").parent().find("textarea:visible,input:visible").first().focus();
                    } else {
                        resetToastPosition();
                        SnippetApp.toast.alert("{{ __('admin.warning') }}","{{ __('admin.something_went_wrong') }}");
                    }

                }
            });
            return false;
        });
    </script>
    <script type="text/javascript">
        /******************begin: Init jQuery Modules*********************/
        jQuery(document).ready(function () {
            SnippetFrontDefaultInit.init();
        });
        /******************end: Init jQuery Modules*********************/
        /******************begin: Front Default Init**********************/
        var SnippetFrontDefaultInit = function () {

            var initTooltip = function () {
                $('[data-toggle="tooltip"]').tooltip({ trigger: 'hover' });
            };

            return {
                init: function () {
                    initTooltip()
                }
            }
        }(1);
        /******************end: Front Default Init**********************/
        $(document).ready(function () {
            var subCategories = '@php echo ($categories); @endphp';
            var all_suppliers = $('#all_suppliers123').filterMultiSelect({
                placeholderText: "Select Category",
                filterText: 'search',
                caseSensitive: false,
                items: JSON.parse(subCategories),
            });
        });
    </script>
</div>
