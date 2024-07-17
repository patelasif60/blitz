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
                        Halo <strong>{{ $rfq->firstname . ' ' . $rfq->lastname }}</strong>,
                    </td>
                </tr>
                <!-- <tr style="line-height: 20px;">
                    <td>&nbsp;</td>
                </tr> -->

                <tr>

                    <td style="color: #808080;font-size: 16px;">
                        <P>
                            Barang pesanan anda no. <strong>{{ $orderItem->order_item_number }}</strong> statustelah terupdate.
                        <table style="width: 100%;" cellpadding="5px" border="0">
                            <tr>
                                <th style="background-color: #002050; color: #fff;">Product Name</th>
                                <th style="background-color: #002050; color: #fff;">Description</th>
                                <th style="background-color: #002050; color: #fff;">Quantity </th>
                                <th style="background-color: #002050; color: #fff;">Status </th>
                            </tr>
                            <tr>
                                <td style="border-bottom: 1px solid #ccc;">{{get_product_name_by_id($quoteItem->rfq_product_id)}}</td>
                                <td style="border-bottom: 1px solid #ccc;">{{get_product_desc_by_id($quoteItem->rfq_product_id)}}</td>
                                <td style="border-bottom: 1px solid #ccc;">{{ $quoteItem->product_quantity.' '.get_unit_name($quoteItem->price_unit)  }}</td>
                                <td style="border-bottom: 1px solid #ccc;"><strong>{{$statusName}}</strong></td>
                            </tr>
                        </table>
                        </P>
                        <p>Silahkan <a href="https://www.blitznet.co.id/signin"> Login
                                Akunanda</a> untuk keterang anlebihlanjut. </p>
                    </td>
                </tr>
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