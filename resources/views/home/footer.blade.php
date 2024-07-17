<footer class="bg-color-first">
    <div class="container">
        <div class="row py-3 py-lg-5 pb-lg-1 justify-content-center align-items-center">
            <div class="col-md-6 col-lg-4 ">
                <img src="{{ asset('home_design/images/icons/footer-logo.svg') }}" class="mw-100 mb-3 mb-lg-4 footerlogo" alt="Blitznet footer logo">
                <p class="pe-lg-5 text-justify">{{ __('frontend.footer_prg') }}</p>
            </div>
            <div class="col-md-6 col-lg-3 footmargin">
                <h5 class="fw-bold">Indonesia</h5>
                <p>
                    <address>
                        Alamanda Tower 25 th Floor<br>
                        Jl. TB. Simatupang Kav. 23-24<br>
                        Cilandak Barat, Cilandak,<br>
                        Kota Jakarta Selatan, DKI Jakarta 12430
                    </address>
                </p>
            </div>
            <div class="col-md-6 col-lg-2 footmargin">
                <h5 class="fw-bold">India</h5>
                <p>
                    <address>
                        401-SMIT<br>
                        Sarabhai IT Campus<br>
                        Alembic Rd, , Gorwa, Vadodara,<br>
                        Gujarat 390023, India
                    </address>
                </p>
            </div>
            <div class="col-md-6 col-lg-3  footmargin">
                <p class="fw-bold mb-1"><a href="{{route('terms-and-condition') }}">{{ __('home_latest.terms_condition') }}</a></p>
                {{-- <p class="fw-bold mb-1"><a href="javascript:void(0);">{{ __('home_latest.our_offer') }}</a></p> --}}
                <p class="fw-bold mb-1"><a href="{{ route('faq')}}">{{ __('home_latest.question_answers') }}</a></p>
                <br>
                <div class="row mt-0 partners g-2">
                <div class="col-lg-12">
                        <p class="fw-bold text-uppercase mb-1">Follow us on</p>
                        <a href="https://www.linkedin.com/company/blitznet-pt-blitznet-upaya-indonesia/" class="footer-48f11e8c" target="_blank">
                            <img src="{{ asset('home_design/images/icon_linkedin_s.png') }}" alt="linkedin" class="mw-100 me-1">
                        </a>
                        <a href="https://www.facebook.com/profile.php?id=100083333012873" class="footer-48f12364" target="_blank">
                            <img src="{{ asset('home_design/images/facebook.png') }}" alt="facebook" class="mw-100 me-1">
                        </a>
                        <a href="https://www.instagram.com/blitznetsp/" class="footer-48f12558" target="_blank">
                            <img src="{{ asset('home_design/images/instagram.png') }}" alt="instagram" class="mw-100">
                        </a>
                    </div>
                    <div class="col-lg-12">
                        <p class="fw-bold text-uppercase mb-1">Partnerships</p>
                        <img src="{{ asset('home_design/images/jne_logo.png') }}" alt="jne partner" class="mw-100">
                        <img src="{{ asset('home_design/images/JTR_Logo.png') }}" alt="jne partner" class="mw-100">
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row pt-2">
            <div class="col-lg-8 pb-lg-4 footer_last">Copyright All Right Reserved {{ date("Y") }}, Blitznet</div>
        </div>
    </div>
</footer>
