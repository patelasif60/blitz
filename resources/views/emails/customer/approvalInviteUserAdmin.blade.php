<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->

    <title>Blitznet</title>
    <style type="text/css">
        a {
            list-style: none;
            text-decoration: none;
        }

        .line {
            border: 1px solid #ececec;
        }

    </style>
</head>

<body style="background-color: #f9f9f9;">
<table style="border: 0; padding:0; margin:0 auto;text-align: left; font-size:14px;font-family: Arial, sans-serif;" border="0" cellspacing="0" cellpadding="0" width="100%">
        <tbody>
            <tr>
                <td style="background-color: #002050; text-align: center;">
                    <table border="0" cellspacing="0" cellpadding="10" width="auto" style="margin: 0px auto;">
                        <tr>
                            <td>
                            <img src="https://www.blitznet.co.id/front-assets/images/front/header-logo.png" alt="Blitznet" title="Blitznet" valign="middle">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <!-- <tr style="line-height: 20px;">
                <td>&nbsp;</td>
            </tr> -->

            <tr>
            <td align="center" style="background-color: #f9f9f9;">
            <table style="border: 0; padding:0; margin:0 auto;text-align: left; width:750px; background-color:#fff; font-size:14px;font-family: Arial, sans-serif;" border="0" cellspacing="10" cellpadding="0" width="">
                        <tr style="line-height: 20px;">
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="color: #808080;font-size: 16px;">
                                Halo <strong>{{ $user['userData']->firstname . ' ' . $user['userData']->lastname }}</strong>,
                            </td>
                        </tr>
                        <!-- <tr style="line-height: 20px;">
                            <td>&nbsp;</td>
                        </tr> -->

                        <tr>
                            @if($user['user']->user_type == "Approver")
                                <td style="color: #808080;font-size: 16px; line-height: 1.5;">
                                    <p>{{ $user['mainUser']['firstname'] . ' ' . $user['mainUser']['lastname'] }} dari {{ $user['mainUser']['name'] }} mengundang anda sebagai pemberi persetujuan. Anda akan di minta persetujuan penawaran harga ketika {{ $user['mainUser']['name'] }} menerima penawaran harga (Quotation) dari RFQ. Persetujuan anda penting, dan tanpa persetujuan dari anda pesanan tidak dapat diteruskan.
                                    </p>
                                </td>
                            @endif

                            @if($user['user']->user_type == "Consulted")
                                <td style="color: #808080;font-size: 16px; line-height: 1.5;">
                                    <p>{{ $user['mainUser']['firstname'] . ' ' . $user['mainUser']['lastname'] }} dari {{ $user['mainUser']['name'] }} an Pembeli mengundang anda sebagai penasihat – penanggung jawab. Anda akan di minta persetujuan penawaran harga ketika {{ $user['mainUser']['name'] }} an Pembeli menerima penawaran harga (Quotation) dari RFQ. Anda dapat berdiskusi dengan tim anda perihal penawaran tersebut. </p>
                                </td>
                            @endif
                        </tr>
                        <tr>
                            <td>
                                <p>Note : Jika anda tidak berkenan menerima undangan ini silahkan abaikan email ini.</p>
                            </td>
                        </tr>
                        <!-- <tr>
                            <td style="text-align: center; ">
                                <p style="margin-bottom: 0; text-align: center;color: #006cd1;"><strong>Security Code:</strong></p>
                                <h1 style="color: #006cd1; margin-top: 10px; text-align: center; font-size: 60px;">
                                    @php
                                        $security_code = str_split($user['user']->security_code);
                                    @endphp
                                    @foreach($security_code as $code)
                                        <span style="border-bottom: 1px solid #ccc;">{{ $code }}</span>
                                    @endforeach
                                </h1>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p style="text-align: center;"><a href="{{ route('accept-user-invitation', ['id' => $user['url']]) }}" target="_blank"><img src="{{ URL::asset('front-assets/images/icons/accept_invitation.png') }}" alt="Menerima Undangan"> </a></p>
                            </td>
                        </tr> -->
                    </table>
                </td>
            </tr>



            <!-- <tr style="line-height: 20px;">
                <td>&nbsp;</td>
            </tr> -->
            <tr>
                <td style="color: #808080;font-size: 16px; background-color: #f9f9f9;" align="center">

                    <table style="border: 0; padding:0; margin:0 auto;text-align: left; width: 750px; background-color:#fff; font-size:14px;font-family: Arial, sans-serif;" border="0" cellspacing="10" cellpadding="0" width="">
                        <tr>
                            <td style="color: #808080;font-size: 16px;">
                                Salam,<br>Blitznet
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                    </table>

                </td>
            </tr>
            <!-- <tr style="line-height: 30px;">
                <td>&nbsp;</td>
            </tr> -->

            <tr>
            <td align="center" style="background-color: #f9f9f9;">
                <table cellspacing="0" cellpadding="0" border="0" align="center">
                    <tr>
                        <td align="center" style="color: #333; width: 750px; font-size: 16px;text-align: center; background-color: #72727e; ">
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
            <tr>
                <td  style="line-height: 20px; background-color: #f9f9f9;">&nbsp;</td>
            </tr>

        </tbody>
    </table>

</body>

</html>
