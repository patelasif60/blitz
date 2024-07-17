<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Invited you to join blitznet portal</title>
    <style>
        body, html{ margin: 0; padding: 0;}
    </style>

</head>

<body style="margin: 0; padding: 0; background-color: #EBEDF1;" border="0">
<table width="100%" cellpadding="0" cellspecing="10" style="font-family: Arial, Helvetica, sans-serif; margin-bottom: 30px;border-collapse: collapse;">
    <tr>
        <td align="center" style="background-color: #002050; color: #fff; padding-top: 30px;padding-bottom: 30px;">
            <img src="{{ URL::asset('front-assets/images/front/header-logo.png') }}" alt="Blitznet" title="Blitznet" valign="middle">
        </td>
    </tr>
    <tr>
        <td align="center" style="background-color: #002050;">
            <table border="0" width="800" style="margin: 0 auto; background-color: #fff;" align="center">
                <tr>
                    <td>
                        <table border="0" width="90%" style="margin: 0 auto;" align="center" cellpadding="0">
                            <tr>
                                <td>
                                    <h1 style="font-size: 30px;color: #002050; text-align: left; margin-bottom: 10px;">Hello {{ $data['loanApplicants']->first_name.' '.$data['loanApplicants']->last_name}},</h1>
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
            <table width="800" cellpadding="5" cellspecing="0" style="font-family: Arial, Helvetica, sans-serif; background-color: #fff;">
                <tr>
                    <td align="center" style="text-align: left;">
                        <table border="0" width="90%" style="margin: 0 auto;" align="center" cellpadding="0">
                            <tr>
                                <td style="">
                                    <table border="0" width="100%" style="margin: 0" align="center" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td>
                                                <p>Thank you for your interest in applying for loan with Blitznet. We hereby submit a response to the loan application received on <strong>{{\Carbon\Carbon::parse($data->created_at)->format('d-m-Y')}}.</strong> The application for loan is accepted.</p>

                                                <p>Loan ID: <strong>{{$data->loan_number}}</strong>.</p>
                                                <p>Loan Amount: <strong>RP {{$data->loan_amount}}</strong>.</p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center;">
                                    <p style="margin-bottom: 0; color: #006cd1; font-size: 14px;">Thank you for contacting us.</p>
                                    <p>Please <a href="https://www.blitznet.co.id/signin">login</a> to your account to see further updates.</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Regards, <br> Blitznet</p>
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
