<!--begin: Profile Tab Sidebar -->
    <div class="col-md-4 col-lg-3 naviconsbtn px-0 d-none">
            <ul class="nav nav-tabs d-block border-0" id="myTab" role="tablist">
                <li class="nav-item changetab active-none" role="presentation" id="change_personal_info">
                    <button class="nav-link w-100 text-start " id="home-tab" data-bs-toggle="tab"
                            data-bs-target="#home" type="button" role="tab" aria-controls="home"
                            aria-selected="true"><svg xmlns="http://www.w3.org/2000/svg" width="17.5"
                                                      height="20" viewBox="0 0 17.5 20">
                            <path id="icon_personal_pro"
                                  d="M8.75,10a5,5,0,1,0-5-5A5,5,0,0,0,8.75,10Zm3.5,1.25H11.6a6.8,6.8,0,0,1-5.7,0H5.25A5.251,5.251,0,0,0,0,16.5v1.625A1.875,1.875,0,0,0,1.875,20h13.75A1.875,1.875,0,0,0,17.5,18.125V16.5A5.251,5.251,0,0,0,12.25,11.25Z" />
                        </svg>
                        {{ __('profile.personal_info') }}</button>
                </li>
                <li class="nav-item changetab" role="presentation" id="change_change_password">
                    <button class="nav-link w-100 text-start" id="contact-tab" data-bs-toggle="tab"
                            data-bs-target="#contact" type="button" role="tab" aria-controls="contact"
                            aria-selected="false"><svg xmlns="http://www.w3.org/2000/svg" width="17.5"
                                                       height="20" viewBox="0 0 17.5 20">
                            <path id="icon_lock_pro"
                                  d="M15.625,20H1.875A1.877,1.877,0,0,1,0,18.125v-7.5A1.877,1.877,0,0,1,1.875,8.75h.938V5.937a5.937,5.937,0,1,1,11.875,0V8.75h.938A1.877,1.877,0,0,1,17.5,10.625v7.5A1.877,1.877,0,0,1,15.625,20ZM9,13.983a1,1,0,0,0-1,1v2a1,1,0,1,0,2,0v-2A1,1,0,0,0,9,13.983ZM8.75,3.125A2.816,2.816,0,0,0,5.937,5.937V8.75h5.625V5.937A2.816,2.816,0,0,0,8.75,3.125Z" />
                        </svg> {{ __('profile.change_password') }}</button>
                </li>

            </ul>
        </div>
<!--end: Profile Tab Sidebar -->
