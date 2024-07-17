<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Welcome mail from blitznet portal</title>
    <style>
        body, html{ margin: 0; padding: 0;}

    </style>

</head>

<body style="margin: 0; padding: 0; background-color: #EBEDF1;" border="0">
<table width="100%" cellpadding="0" cellspecing="10" style="font-family: Arial, Helvetica, sans-serif; margin-bottom: 30px;border-collapse: collapse;">
    <tr>
        <td align="center" style="background-color: #002050; color: #fff; padding-top: 30px;padding-bottom: 30px;">
            <img src="http://www.blitznet.co.id/front-assets/images/front/header-logo.png" alt="">
        </td>

    </tr>
    <tr>
        <td align="center" style="background-color: #002050;">
            <table border="0" width="800" style="margin: 0 auto; background-color: #fff;" align="center">
                <tr>
                    <td>
                        <h1 style="font-size: 30px;color: #002050; text-align: left; padding-left: 50px; margin-bottom: 10px;"> Halo&nbsp;{{ $user['user']->firstname }}&nbsp;{{ $user['user']->lastname }}</h1>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td align="center">
            <table width="800" cellpadding="5" cellspecing="0" style="font-family: Arial, Helvetica, sans-serif; background-color: #fff;">
                <tr>
                    <td align="center" style="text-align: left;">
                        <table border="0" width="90%" style="margin: 0 auto;" align="center" cellpadding="0">
                            <tr>
                                <td>
                                    <p style="margin-top: 0;">We couldn't be happier to welcome you to the blitznet community. Blitznet helps you by:</p>
                                </td>
                            </tr>
                            <tr>
                                <td style="background-color: #e6f5fc;">
                                    <table border="0" width="85%" style="margin: 0 auto;" align="center" cellspacing="0" cellpadding="16px" >
                                        <tr>
                                            <td colspan="3" style="text-align: center;vertical-align: top;"><h4 style="margin-top: 0; margin-bottom: 0;">Blitznet helps you by:</h4></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center; font-size: 14px; font-weight:bold; line-height: 1.5; vertical-align: top;">&#10003;<br>Getting  multiple quotations from credible suppliers for a single RFQ.</td>
                                            <td style="text-align: center; font-size: 14px; font-weight:bold; line-height: 1.5; vertical-align: top;">&#10003;<br>Automize by digitalizing the entire procurement processes.</td>
                                            <td style="text-align: center; font-size: 14px; font-weight:bold; line-height: 1.5; vertical-align: top;">&#10003;<br>Procure raw material with discounted prices & working capital loan.</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center; font-size: 14px; font-weight:bold; line-height: 1.5; vertical-align: top;">&#10003;<br>Having access to a reliable supplier’s network.</td>
                                            <td style="text-align: center; font-size: 14px; font-weight:bold; line-height: 1.5; vertical-align: top;">&#10003;<br>Secure payment system through escrow account.</td>
                                            <td style="text-align: center; font-size: 14px; font-weight:bold; line-height: 1.5; vertical-align: top;">&#10003;<br>Reliable & transparent logistics through real-time tracking.</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center;">
                                    <p style="margin-bottom: 0; text-align: center;color: #006cd1; font-size: 14px;"><strong>Click the link below to setup your account and get started</strong></p>
                                    <p style="text-align: center;"><a href="{{ $user['user']->url }}" target="_blank" title="Login Now"><img src="{{ url('front-assets/images/icons/login_now.png') }}" alt="Login Now"></a></p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td align="center">
            <table cellspacing="0" cellpadding="0" border="0" align="center" width="800">
                <tr>
                    <td align="center" style="color: #333; font-size: 16px;text-align: center; background-color: #72727e; ">
                        <table border="0" cellspacing="5" cellpadding="0" width="100%">
                            <tr>
                                <td align="center" style="color: #d4d2df;font-size: 16px;text-align: center; ">
                                    Blitznet. All rights reserved.
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
