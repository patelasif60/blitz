<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->

    <style>
        body,
        html {
            margin: 0;
            padding: 0;
        }

        table {
            border-collapse: collapse;
        }

        a {
            text-decoration: none;
        }

    </style>
    <title>Aktivasi Email</title>
</head>

<body style="margin: 0; padding: 0; background-color: #EBEDF1;">
    <table width="100%" cellpadding="0" cellspecing="10"
        style="font-family: Arial, Helvetica, sans-serif; margin-bottom: 30px;">
        <tr>
            <td style="background-color: #002050; text-align: center;">
                <table border="0" cellspacing="0" cellpadding="10" width="auto" style="margin: 0px auto;">
                    <tr>
                        <td>
                            <img src="https://www.blitznet.co.id/front-assets/images/front/header-logo.png" alt=""
                                valign="middle">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center" style="background-color: #002050;">
                <table width="800" cellpadding="5" cellspecing="0"
                    style="font-family: Arial, Helvetica, sans-serif; background-color: #fff; margin: 0 auto;">
                    <tr>
                        <td>
                            &nbsp;
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center">
                <table width="800" cellpadding="5" cellspecing="0"
                    style="font-family: Arial, Helvetica, sans-serif; background-color: #fff;">
                    <tr>
                        <td align="center">
                            <h1 style="font-size: 36px;color: #002050; text-align: center;"> Kami senang Anda bergabung.<br>{{ $user->full_name }}</h1>
                        </td>
                    </tr>
                    <tr>
                        <td align=" center" style="text-align: center; padding-top: 30px;padding-bottom: 30px;">
                            <a href="{{ route('signin') }}">
                                <img src="https://www.blitznet.co.id/front-assets/images/icons/login_now.png" alt="User Signin">
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td align="center">
                            <p style="font-size: 16px; text-align: center;">kata sandi masuk Anda adalah <strong> {{ $password }} </strong></p>
                        </td>
                    </tr>

                    <tr>
                        <td align="center">
                            <p style="font-size: 16px; text-align: center;"><strong>(Hanya Mengonfirmasi bahwa Anda adalah Anda.)</strong></p>
                        </td>
                    </tr>

                    <tr>
                        <td align="center">
                            <hr>
                            <p style=" text-align: center;"><strong> Hak Cipta © 2021 Blitznet. Seluruh hak cipta.</strong></p>
                            <p style="color: #0D6EFD; text-align: center;"><strong><a style="color: #0D6EFD; text-decoration: none;" href="https://blitznet.co.id/#home_subscribe" target="_blank">Hubungi kami</a> |
                                    <a style="color: #0D6EFD; text-decoration: none;" href="https://blitznet.co.id/" target="_blank">blitznet.co.id</a></strong></p>
                            <p style=" text-align: center;"><strong>Ikuti kami di</strong></p>
                            <p style=" text-align: center;"><a href="https://www.linkedin.com/company/74970780/admin/" target="_blank"><img src="https://www.blitznet.co.id/front-assets/images/front/icon_linkedin_s.png" alt="linkedin"></a></p>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
</body>
</html>
