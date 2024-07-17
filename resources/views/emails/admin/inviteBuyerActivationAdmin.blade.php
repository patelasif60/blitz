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
            <img src="http://www.blitznet.co.id/front-assets/images/front/header-logo.png" alt="">
        </td>

    </tr>
    <tr>
        <td align="center" style="background-color: #002050;">
            <table border="0" width="800" style="margin: 0 auto; background-color: #fff;" align="center">
                <tr>
                    <td>
                        <h1 style="font-size: 30px;color: #002050; text-align: left; padding-left: 50px; margin-bottom: 10px;"> Halo </h1>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td align="center">
            <table width="800" cellpadding="5" cellspecing="0" style="font-family: Arial, Helvetica, sans-serif; background-color: #fff;">
                <!-- <tr>
                    <td align="left">
                        <table border="0" width="90%" style="margin: 0 auto;" align="center">
                            <tr>
                                <td>
                                    <h1 style="font-size: 30px;color: #002050; text-align: left; margin-bottom: 0;"> Halo Nimisha</h1>
                                </td>
                                </tr>
                        </table>
                    </td>
                </tr> -->
                <tr>
                    <td align="center" style="text-align: left;">
                        <table border="0" width="90%" style="margin: 0 auto;" align="center" cellpadding="0">
                            <tr>
                                <td>
                                    <p style="margin-top: 0;"><strong>{{ $user['user']->contact_person_name }}</strong> @if($user['user']->buyer_supplier_mail!=3) dari <strong>{{ $user['user']->name }}</strong> @endif telah mengundang Anda bergabung di blitznet sebagai {{ $user['user']->buyer_supplier_mail==2 ? 'pembeli' : 'pemasok'}} bahan baku.
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td style="background-color: #e6f5fc;">
                                    <table border="0" width="85%" style="margin: 0 auto;" align="center" cellspacing="0" cellpadding="16px" >
                                        <tr>
                                            <td colspan="3" style="text-align: center;vertical-align: top;"><h4 style="margin-top: 0; margin-bottom: 0;">Blitznet dapat membantu melalui:</h4></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center; font-size: 14px; font-weight:bold; line-height: 1.5; vertical-align: top;">&#10003;<br>Menerima Multiple Quotation dari Kredibel Supplier per single RFQ.</td>
                                            <td style="text-align: center; font-size: 14px; font-weight:bold; line-height: 1.5; vertical-align: top;">&#10003;<br>Automisasi Digital Proses Procurement.</td>
                                            <td style="text-align: center; font-size: 14px; font-weight:bold; line-height: 1.5; vertical-align: top;">&#10003;<br>Pengadaan Raw Material dengan Penawaran Terbaik & Modal Kerja.</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center; font-size: 14px; font-weight:bold; line-height: 1.5; vertical-align: top;">&#10003;<br>Akses ke berbagai jaringan supplier terpercaya.</td>
                                            <td style="text-align: center; font-size: 14px; font-weight:bold; line-height: 1.5; vertical-align: top;">&#10003;<br>Sistem Pembayaran melalui Escrow Akun.</td>
                                            <td style="text-align: center; font-size: 14px; font-weight:bold; line-height: 1.5; vertical-align: top;">&#10003;<br>Sistem Logistik terpercaya melalui Real-Time Tracking.</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center;">
                                    <p style="margin-bottom: 0; text-align: center;color: #006cd1; font-size: 14px;"><strong>Klik link berikut dibawah untuk set-up dan mulai gunakan akun</strong></p>
                                    <p style="text-align: center;"><a href="{{ $user['url'] }}" target="_blank"><img src="{{ url('front-assets/images/icons/register_now.png') }}" alt="Register Now"></a></p>
                                </td>

                            </tr>
                            <tr>
                                <td>
                                    <p style="font-size: 14px;">Note: Jika anda tidak mengharapkan undangan ini, Sillahkan hiraukan email ini. </p>
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
