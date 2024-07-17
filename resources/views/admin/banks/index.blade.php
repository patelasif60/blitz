@extends('admin/adminLayout')

@push('bottom_head')
<meta name="csrf-token" content="{!! csrf_token() !!}">
<style>
    .rotate {
        animation: rotation 2s infinite linear;
    }
    #loading{
        width: 15px;
        height: 15px;
    }
    .modal-header {
        background-color: #13193a;
        color: #fff;
    }
</style>
@endpush
@section('content')
    <div class="card h-100 bg-transparent newtable_v2">
        <div class="card-body p-0 ">
            <div class="d-flex align-items-center">
                <h4 class="card-title">{{ __('admin.available_banks')}}</h4>
                <div class="d-flex align-items-center justify-content-end ms-auto">
                    <div class="pe-1 mb-3 clearfix">
                        @can('create available banks')
                        <a href="javascript:void(0)" id="bank-sync" type="button" class="btn btn-primary btn-sm"><img src="{{ URL('front-assets/images/icons/loading.gif') }}" alt="refresh" id="loading" style="display: none">{{ __('admin.sync')}}</a>
                        @endcan
                    </div>
                    <input type="hidden" value="{!! csrf_token() !!}" name="_token">
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-12">
                    <div class="table-responsive ">
                        <table id="bank-listing" class="table table-hover">
                            <thead>
                            <tr>
                                <th class="hidden">{{ __('admin.id')}}</th>
                                <th>{{ __('admin.logo')}}</th>
                                <th>{{ __('admin.name')}}</th>
                                <th>{{ __('admin.code')}}</th>
                                <th>{{ __('admin.created_at')}}</th>
                                <th>{{ __('admin.updated_at')}}</th>
                                @can('edit available banks' || 'delete available banks')
                                <th class="text-end" data-orderable="false">{{ __('admin.action')}}</th>
                                @endcan
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($banks) > 0)
                                @foreach ($banks as $bank)
                                    <tr>
                                        <td class="hidden">{{ $bank->id }}</td>
                                        <td class="bank-icon">
                                            @if(empty($bank->logo))
                                                <img src="{{ URL('assets/icons/bank.png') }}" style="height: 30px;width: 30px;" alt="bank icon">
                                            @else
                                                <img src="{{ URL($bank->logo) }}" style="height: 30px;width: 30px;" alt="bank icon">
                                            @endif
                                        </td>
                                        <td>{{ $bank->name }}</td>
                                        <td>{{ $bank->code }}</td>
                                        <td>
                                            {{changeDateTimeFormat($bank->created_at)}}
                                        </td>
                                        <td>
                                            {{changeDateTimeFormat($bank->updated_at)}}
                                        </td>
                                        @can('edit available banks' || 'delete available banks')
                                        <td class="text-center text-nowrap">
                                            @can('edit available banks')
                                            <a href="javascript:void(0)" class="show-icon" data-id="{{ $bank->id }}" data-name="{{ $bank->name }}" data-code="{{ $bank->code }}" data-logo="{{ $bank->logo }}" data-toggle="tooltip" ata-placement="top"
                                               title="{{__('admin.edit')}}" onclick="openEditModel($(this))">
                                                <i class="fa fa-edit" aria-hidden="true"></i>
                                            </a>
                                            @endcan
                                        </td>
                                        @endcan
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stpe-3 --}}
    <div class="modal fade" id="bankdetail" tabindex="-1" role="dialog" aria-labelledby="addBankLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-width: 800px;" role="document">
            <div class="modal-content border-0">
                <div class="modal-header p-3">
                    <h3 class="modal-title" style="color: white;" id="addBankLabel">{{ __('admin.bank_details')}}</h3>
                    <button type="button" class="btn-close ms-0 d-flex" data-bs-dismiss="modal" aria-label="Close">
                        <img src="{{ URL('front-assets/images/icons/times.png') }}" alt="Close">
                    </button>
                </div>
                <form id="bankForm" method="post" action="{{ route('bank-logo-upload') }}" enctype="multipart/form-data" class="form-group" data-parsley-validate>
                    @csrf
                    <div class="modal-body p-3">
                        <div class="card">
                            <div class="card-header align-items-center">
                                <h5>{{ __('admin.bank_info')}}</h5>
                            </div>
                            <div class="card-body p-3 pb-1">
                                <div class="row">
                                    <div class="pb-3">
                                        <label class="form-label">{{ __('admin.bank_account_name')}}</label>
                                        <div class="d-flex">
                                            <input type="text" class="form-control" id="bank-name" disabled>
                                        </div>
                                    </div>
                                    <div class="d-flex pb-2">
                                        <div class="col-md-6">
                                            <div class="">
                                                <label for="bank-code" class="form-label">{{ __('admin.bank_code')}} </label>
                                                <input type="text" class="form-control" id="bank-code" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6 ps-3">
                                            <label class="form-label">{{ __('admin.upload_bank_logo')}}</label>
                                            <div class="d-flex">
                                                <input type="hidden" name="id" id="bank-id">
                                                <span class=""><input id="logo" type="file" name="logo" accept="image/*" onchange="show(this)" hidden/><label id="upload_btn" for="logo"> {{ __('admin.browse')}}</label></span>
                                                <div id="file-logo">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer pb-2" style="background-color: #f5f5f6;">
                        <button type="submit" class="btn btn-primary">{{ __('admin.save')}}</button>
                        <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">{{ __('admin.cancel')}}</button>
                    </div>
                {{--<div class="modal-body">
                    <div class="row">
                            <div class="d-flex pb-2">
                                <div class="col-md-7">
                                    <label class="form-label">Bank Account Name </label>
                                    <div class="d-flex">
                                        <input type="text" class="form-control" id="bank-name" disabled>
                                    </div>
                                </div>
                                <div class="col-md-5 ps-3">
                                    <div class="">
                                        <label for="bank-code" class="form-label">Bank
                                            code </label>
                                        <input type="text" class="form-control" id="bank-code" disabled>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="row">
                        <div class="d-flex pb-2">
                            <div class="col-md-4">
                                <div class="">
                                    <label for="logo" class="form-label">Bank Logo</label>
                                    <input type="hidden" name="id" id="bank-id">
                                    <input type="file" id="logo" name="logo" accept="image/*" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                </div>--}}
                </form>
            </div>
        </div>
    </div>
    {{-- Stpe-3 ends --}}
@stop

@push('bottom_scripts')
<script>
    $(document).ready(function() {
        $('#bank-listing').DataTable({
            "order": [
                [0, "desc"]
            ],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "iDisplayLength": 10,
            "columnDefs": [{
                "targets": [0],
                "visible": false,
                "searchable": false
            }, ]
        });
    });
    $('#bank-sync').click(function () {
        let that = $(this);
        that.prop('disabled',true);
        $('#loading').show();
        $.ajax({
            url: "{{ route('bank-sync') }}",
            type: "GET",
            success: function(successData) {
                that.prop('disabled',false);
                $('#loading').hide();
                location.reload();
            },
            error: function() {
                $('#loading').hide();
                console.log("error");
            },
        });
    })

    function openEditModel(selector) {
        $(".modal-body #bank-id").val(selector.data('id'));
        $(".modal-body #bank-name").val(selector.data('name'));
        $(".modal-body #bank-code").val(selector.data('code'));
        //$(".modal-body #bank-logo").attr('src',selector.data('logo'));
        $('#bankdetail').modal('show');
    }

    function show(input) {
        console.log(input);
        var file = input.files[0];
        var size = Math.round((file.size / 1024))
        if(size > 3000){
            swal({
                icon: 'error',
                title: '',
                text: '{{__('admin.file_size_under_3mb')}}',
            })
        } else {
            var fileName = file.name;
            var allowed_extensions = new Array("jpg", "png", "gif", "jpeg", "pdf");
            var file_extension = fileName.split('.').pop();
            var text = '';
            if (input.name == 'logo') {
                allowed_extensions = allowed_extensions.filter(function (value) {
                    return value != 'pdf'
                });
                text = 'Please Upload Image file';
            } else {
                text = 'Please Upload Image Or PDF file';
            }
            for (var i = 0; i < allowed_extensions.length; i++) {
                if (allowed_extensions[i] == file_extension) {
                    valid = true;
                    var tooltip = fileName;
                    if(fileName.length > 10) {
                        fileName = fileName.substring(0,10) +'....'+file_extension;
                    }
                    $('#file-' + input.name).html('');
                    $('#file-' + input.name).append('<span class="ms-2"><a href="javascript:void(0);" id="' + input.name + 'Download" title="'+tooltip+'" style="text-decoration: none">' + fileName + '</a></span>');
                    return;
                }
            }
            valid = false;
            swal({
                // title: "Rfq Update",
                text: text,
                icon: "/assets/images/bin.png",
                //buttons: true,
                buttons: ["{{ __('admin.no') }}", "{{ __('admin.yes') }}"],
                // dangerMode: true,
            })
        }
    }

</script>
@endpush
