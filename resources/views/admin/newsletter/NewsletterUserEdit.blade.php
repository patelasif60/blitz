@extends('admin/adminLayout')
@section('content')


        <div class="row">
            <div class="col-md-12 d-flex align-items-center mb-3">
                <h1 class="mb-0 h3">{{__('admin.newsletters_users')}}</h1>
                <a href="{{ route('newsletter-list') }}" class="mb-2 backurl ms-auto btn-close"></a>
            </div>


            <div class="col-12">
                <ul class="nav nav-tabs bg-white newversiontabs ps-3" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-0 active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">
                            {{__('admin.edit_newsletters_users')}}
                        </button>
                    </li>
                </ul>
                <div class="tab-content pt-3 pb-1" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <form id="newsletteredit" class="" method="POST" action="{{ route('newsletter-update') }}"
                        data-parsley-validate>
                        @csrf
                        <div class="row">
                            <div class="col-md-12 pb-2">
                            <div class="card">
                        <div class="card-header d-flex align-items-center">
                                <h5 class="mb-0">
                                    <img src="{{URL::asset('assets/icons/file-alt.png')}}"
                                            alt="Order Details" class="pe-2">
                                    <span>{{__('admin.users')}}</span></h5>
                            </div>
                        <div class="card-body p-3 pb-1">
                            <!-- <div class="mb-2">
                                <a href="{{ route('newsletter-list') }}" class="mb-2 backurl"><i class="fa fa-arrow-circle-left"
                                        aria-hidden="true"></i> Back</a>
                            </div>
                            <h3 class="card-title">Edit Newsletters Users</h3> -->
                            <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="name" class="form-label">{{__('admin.email')}} <span class="text-danger">*</span></label>
                                            <input type="hidden" name="id" value="{{ $newsletters->id }}">
                                            <input type="text" class="form-control" id="email" name="email" required
                                                value="{{ $newsletters->email }}">
                                        </div>
                                        <!-- <div class="col-12">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div> -->


                            </div>
                        </div>
                    </div>
                            </div>
                            <div class="col-md-12 bg-white py-3 d-flex justify-content-end">
                     <button type="submit" class="btn btn-primary">{{__('admin.update')}}</button>
                            <a href="{{ route('newsletter-list') }}" >
                                <button type="button" class="btn btn-cancel ms-3">{{__('admin.cancel')}}</button>
                            </a>
                        </div>
                        </div>

    </form>
                    </div>
                </div>

            </div>


        </div>








@stop

@section('scripts')
<script>
    $("#newsletteredit").on('submit',function(event){
        event.preventDefault();
        var formData = new FormData($("#newsletteredit")[0]);
        if ($('#newsletteredit').parsley().validate()) {
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data : formData,
                contentType: false,
                processData: false,

                success: function (r) {
                    if(r.success == true){
                        resetToastPosition();
                        $.toast({
                            heading: "{{__('admin.success')}}",
                            text: "{{__('admin.news_letter_updated_alert')}}",
                            showHideTransition: "slide",
                            icon: "success",
                            loaderBg: "#f96868",
                            position: "top-right",
                        });
                        setTimeout(function(){window.top.location=$(".backurl").attr('href')} , 3000);
                    }
                },
                error: function (xhr) {
                    alert('{{__('admin.error_while_selecting_list')}}');
                }
            });

        }
    });
</script>
@stop
