<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Forgot Password</title>
    <style>
        body, html{ margin: 0; padding: 0;}

    </style>

</head>

<body style="margin: 0; padding: 0; background-color: #EBEDF1;">
<table width="100%" cellpadding="0" cellspecing="0" style="font-family: Arial, Helvetica, sans-serif; margin-bottom: 30px; border-collapse: collapse;">
    <tr>
        <td align="center" style="background-color: #002050; color: #fff; padding-top: 50px;padding-bottom: 50px;">
            <img src="http://www.blitznet.co.id/front-assets/images/front/header-logo.png" alt="">
        </td>

    </tr>
    <tr>
        <td align="center" style="background-color: #002050;">
            <table width="800" cellpadding="5" cellspecing="0" style="font-family: Arial, Helvetica, sans-serif; background-color: #fff; margin: 0 auto;" border="0">
                <tr>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td align="center">
            <table width="800" cellpadding="5" cellspecing="0" style="font-family: Arial, Helvetica, sans-serif; background-color: #fff;">
                <tr>
                    <td align="center">
                        <p><strong>Halo {{ $full_name }}</strong></p>
                        <h2 style="font-size: 24px;color: #002050; text-align: center;">Anda Lupa Password?</h2>
                        <h1 style="font-size: 30px;color: #002050; text-align: center;">Tidak masalah, kami siap membantu. </h1>

                        <p>Kami telah menerima permintaan untuk me-reset akun blitznet anda. Lanjut klik tombol dibawah ini untuk lakukan reset.</p>
                    </td>

                </tr>
                <tr>
                    <td align="center" style="text-align: center; padding-top: 10px;padding-bottom: 10px;">
                        <a href="{{ route('reset-password-get', $token) }}"><img src="{{ url('front-assets/images/reset_password.png') }}" alt="Reset Password" title="Reset Password" style="display: inline-block;"> </a>
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <p style="font-size: 15px; text-align: center;">Jika anda tidak ingin meneruskan atau me-reset password, anda dapat hiraukan saja email ini dan password anda akan tetap sama.</p>
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        Salam,<br>Blitznet
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
                            <tr>
                                <td align="center" style="color: #333; font-size: 16px;text-align: center; background-color: #72727e; ">
                                    <table border="0" cellspacing="5" cellpadding="0" width="100%">
                                        <tr>
                                            <td align="center" style="color: #d4d2df;font-size: 16px;text-align: center; ">
                                                Blitznet. Seluruh hak cipta.
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

        </td>
    </tr>



</table>

</body>

</html>
