<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Email Activation</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
        }

        table { border: 0; border-collapse: collapse;}

    </style>

</head>

<body style="margin: 0; padding: 0; background-color: #EBEDF1;" border="0">
<table width="100%" cellpadding="0" cellspecing="10" style="font-family: Arial, Helvetica, sans-serif; margin-bottom: 30px; ">
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
                    <td align="left">
                        <table border="0" width="90%" style="margin: 0 auto;" align="center">
                            <tr>
                                <td>
                                    <h1 style="font-size: 30px;color: #002050; text-align: left;">Halo {{ $supplier->contact_person_name }},</h1>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td align="center" style="text-align: left; padding-bottom: 30px;">
                        <table border="0" width="90%" style="margin: 0 auto;" align="center">
                            <tr>
                                <td>
                                    <p>Anda telah menerima Order baru.<br>Nomor Order Anda:<strong> {{ $order->order_number }}</strong>. Mohon agar disiapkan dan diinfokan kami jika barang
                                        sudah bisa dikirimkan.
                                    </p>
                                    <table style="width: 100%;" cellpadding="5px" border="0">
                                        <tr>
                                            <th style="background-color: #002050; color: #fff;">Product Name</th>
                                            <th style="background-color: #002050; color: #fff;">Description</th>
                                            <th style="background-color: #002050; color: #fff;">Quantity </th>
                                        </tr>
                                        @foreach($quoteItems as $quoteItem)
                                            <tr>
                                                <td style="border-bottom: 1px solid #ccc;">{{get_product_name_by_id($quoteItem->rfq_product_id)}}</td>
                                                <td style="border-bottom: 1px solid #ccc;">{{get_product_desc_by_id($quoteItem->rfq_product_id)}}</td>
                                                <td style="border-bottom: 1px solid #ccc;">{{ $quoteItem->product_quantity.' '.get_unit_name($quoteItem->price_unit)  }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center; ">
                                    <p style="margin-bottom: 0; text-align: center;color: #006cd1;"><strong>Security Code:</strong></p>
                                    <h1 style="color: #006cd1; margin-top: 10px; text-align: center; font-size: 60px;">
                                        @php
                                            $singles = str_split($order->otp_supplier);
                                        @endphp
                                        @foreach($singles as $char)
                                            <span style="border-bottom: 1px solid #ccc;">{{ $char }}</span>
                                        @endforeach
                                    </h1>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p style="text-align: center;"><a href="{{ route('view-accept-order-details', ['id' => Crypt::encrypt($order->id)]) }}" target="_blank"><img src="http://www.blitznet.co.id/front-assets/images/icons/accept_order_button.png" alt="Accept Order"></a></p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
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
</table>
</body>
</html>
