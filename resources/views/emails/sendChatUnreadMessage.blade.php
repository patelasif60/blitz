<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invited you to join blitznet portal</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #EBEDF1;" border="0">
<table width="100%" cellpadding="0" cellspecing="10"
       style="font-family: Arial, Helvetica, sans-serif; margin-bottom: 30px;border-collapse: collapse;">
    <tr>
        <td align="center" style="background-color: #002050; color: #fff; padding-top: 30px;padding-bottom: 30px;">
            <img src="{{ URL::asset('front-assets/images/front/header-logo.png') }}" alt="Blitznet" title="Blitznet"
                 valign="middle">
        </td>
    </tr>
    <tr>
        <td align="center" style="background-color: #002050;">
            <table border="0" width="800" style="margin: 0 auto; background-color: #fff;" align="center">
                <tr>
                    <td>
                        <h1 style="font-size: 30px;color: #002050; text-align: left; padding-left: 44px; margin-bottom: 10px;">
                            Halo</h1>
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
                    <td align="center" style="text-align: left;">
                        <table border="0" width="90%" style="margin: 0 auto;" align="center" cellpadding="0">
                            <tr>
                                <td>
                                    <p style="margin-top: 0;"><strong>Pesan baru telah diterima pada {{$chatMessages['chat_group']['group_name']}} </strong></p>
                                </td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #ccc;">
                                    <table border="0" width="100%" style="margin: 0 10px;" align="center"
                                           cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td>
                                                @php
                                                    if ($chatMessages['chat_messages'][0]['sender_role_id'] != 1){
                                                        $comanyName = getCompanyByUserId($chatMessages['chat_messages'][0]['sender_id']);
                                                     } else {
                                                        $comanyName = 'blitznet Team';
                                                     }
                                                @endphp
                                                <p>
                                                    <small><strong>{{ $comanyName }}</strong></small>&nbsp;&nbsp;&nbsp;<small
                                                        style="color: #a3a3a3;">{{ changeDateFormat($chatMessages['chat_messages'][0]['created_at']) }}</small>
                                                </p>
                                                <p>
                                                    @php
                                                        if($chatMessages['chat_messages'][0]['sender_role_id'] == 3 && $chatMessages['user_id'] == $chatMessages['chat_messages'][0]['sender_id']){
                                                            $chatMessages['chat_messages'][0]['message'] = '*********';
                                                        }
                                                    @endphp

                                                    <small>{{$chatMessages['chat_messages'][0]['message']}}</small>
                                                    @if($chatMessages['unread_message_count'] >= 2)
                                                        <small style="font-weight: bold; color: rgb(0, 110, 255);">{{ $chatMessages['unread_message_count'] }} more</small>
                                                    @endif
                                                </p>
                                            </td>
                                        </tr>

                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center;">
                                    <p style="margin-bottom: 0; text-align: center;color: #006cd1; font-size: 14px;">
                                        <strong>Silahkan Login ke akun Anda untuk keterangan lebih lanjut</strong></p>
                                    @if($chatMessages['user_role_id'] == 2)
                                        <p style="text-align: center;"><a href="{{ route('signin') }}" target="_blank"><img
                                                    src="{{ URL::asset('front-assets/images/icons/login_now.png') }}"
                                                    alt="Login Now"></a></p>
                                    @else
                                        <p style="text-align: center;"><a href="{{route('admin-login')}}"
                                                                          target="_blank"><img
                                                    src="{{ URL::asset('front-assets/images/icons/login_now.png') }}"
                                                    alt="Login Now"></a></p>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p style="font-size: 14px;">Catatan: Abaikan jika Anda tidak menginginkan pesan ini.</p>
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
                    <td align="center"
                        style="color: #333; font-size: 16px;text-align: center; background-color: #72727e; ">
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
