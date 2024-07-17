<div class="text-center py-5">
    <h5 class="fw-bold ">{{ __('profile.otptext') }} <br>+{{$request->phone_code}} {{$request->phone_number}}</h5>
    <p class="text-danger mt-3">{{ __('profile.otplimit') }}</p>
    <div class="ap-otp-inputs my-3">
        <input class="ap-otp-input" type="tel" name="otp1" pattern="[0-9]" maxlength="1" data-index="0">
        <input class="ap-otp-input" type="tel" name="otp2" pattern="[0-9]" maxlength="1" data-index="1">
        <input class="ap-otp-input" type="tel" name="otp3" pattern="[0-9]" maxlength="1" data-index="2">
        <input class="ap-otp-input" type="tel" name="otp4" pattern="[0-9]" maxlength="1" data-index="3">
        <input class="ap-otp-input" type="tel" name="otp5" pattern="[0-9]" maxlength="1" data-index="4">
        <input class="ap-otp-input" type="tel" name="otp6" pattern="[0-9]" maxlength="1" data-index="5">
        <input type="hidden" name="otpType" value="{{$request->otp_type}}">
    </div>
    <span id="otpError" class="col-md-12 mb-3 text-danger"></span>
    <a id="retry"; disabled="true" style="display: none" class="text-decoration-none rounded-1 pt-3 d-block" href="javascript:void(0)"></a>
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
