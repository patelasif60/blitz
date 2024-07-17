<div class="col-md-12 ">
    <form action="blank">
        <div class="row">
            <div class="col-md-12 d-flex justify-content-center">
                <div class="col-md-6">
                    <img src="{{ URL::asset('front-assets/images/otp-img-looping.gif')}}" class="w-100" alt="">
                </div>
                <div class="col-md-6 m-2 d-flex justify-content-center align-items-center">
                    <div>
                        <div class="fs-6 fw-bold mb-2 text-center">Enter OTP To Accept Quote</div>
                        <p class="text-center mb-0 text-muted">{{__('dashboard.enter_otp')}} <small class="text-muted">+{{$request->phone_code}} {{$request->approve_mobile}}</small></p>
                        <p class="mb-0 text-center fw-bold text-muted my-4 ">OTP</p>
                        <div class="col-md-12 d-flex justify-content-between mt-2">
                            <div class="input-group">
                                <input type="tel" name="otp1" pattern="[0-9]" maxlength="1" data-index="0" class="form-control border-0 border-bottom border-primary rounded-0 text-center ap-otp-input" placeholder="0" aria-label="Username" aria-describedby="basic-addon1">
                            </div>
                            <div class="input-group">
                                <input type="tel" name="otp2" pattern="[0-9]" maxlength="1" data-index="1" class="form-control border-0 border-bottom border-primary rounded-0 text-center ap-otp-input" placeholder="0" aria-label="Username" aria-describedby="basic-addon2">
                            </div>
                            <div class="input-group">
                                <input type="tel" name="otp3" pattern="[0-9]" maxlength="1" data-index="2" class="form-control border-0 border-bottom border-primary rounded-0 text-center ap-otp-input" placeholder="0" aria-label="Username" aria-describedby="basic-addon3">
                            </div>
                            <div class="input-group">
                                <input type="tel" name="otp4" pattern="[0-9]" maxlength="1" data-index="3" class="form-control border-0 border-bottom border-primary rounded-0 text-center ap-otp-input" placeholder="0" aria-label="Username" aria-describedby="basic-addon4">
                            </div>
                            <div class="input-group">
                                <input type="tel" name="otp5" pattern="[0-9]" maxlength="1" data-index="4" class="form-control border-0 border-bottom border-primary rounded-0 text-center ap-otp-input" placeholder="0" aria-label="Username" aria-describedby="basic-addon5">
                            </div>
                            <div class="input-group">
                                <input type="tel" name="otp6" pattern="[0-9]" maxlength="1" data-index="5" class="form-control border-0 border-bottom border-primary  rounded-0 text-center ap-otp-input" placeholder="0" aria-label="Username" aria-describedby="basic-addon6">
                            </div>
                        </div>

                        <div class="col-md-12 my-4 text-center">
                            <button type="button" class="btn btn-primary px-3 py-1 mb-1" id="subForm">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    // script for enter otp without tab button
    const $inp = $(".ap-otp-input");
    let timer;
    let timeRemaining;
    let retryButton = document.getElementById("retry");
    let retryclass = $('#retry');
    $inp.on({
        paste(ev) {// Handle Pasting
            const clip = ev.originalEvent.clipboardData.getData('text').replaceAll(/\s/g,'').trim();
            // Allow numbers only
            if (!/\d{6}/.test(clip)) return ev.preventDefault(); // Invalid. Exit here
            // Split string to Array or characters
            const s = [...clip];
            // Populate inputs. Focus last input.
            $inp.val(i => s[i]).eq(5).focus();
        },
        input(ev) {// Handle typing
            const i = $inp.index(this);
            if (this.value) $inp.eq(i + 1).focus();
        },
        keydown(ev) { // Handle Deleting
            const i = $inp.index(this);
            if (!this.value && ev.key === "Backspace" && i) $inp.eq(i - 1).focus();
        }

    });
</script>
