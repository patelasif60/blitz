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
<table style="border: 0; padding:0; margin:0 auto;text-align: left; font-size:14px;font-family: Arial, sans-serif;"
       border="0" cellspacing="0" cellpadding="0" width="100%">
    <tbody>
    <tr>
        <td style="background-color: #002050; text-align: center;">
            <table border="0" cellspacing="0" cellpadding="10" width="auto" style="margin: 0px auto;">
                <tr>
                    <td>
                        <img src="https://www.blitznet.co.id/front-assets/images/front/header-logo.png" alt="Blitznet"
                             title="Blitznet" valign="middle">
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
            <table
                style="border: 0; padding:0; margin:0 auto;text-align: left; width:750px; background-color:#fff; font-size:14px;font-family: Arial, sans-serif;"
                border="0" cellspacing="10" cellpadding="0" width="">

                <tr style="line-height: 20px;">
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td style="color: #808080;font-size: 16px;">
                        Halo
                        <strong>{{ $order['order']->user_firstname . ' ' . $order['order']->user_lastname }}</strong> ,
                    </td>
                </tr>
                <!-- <tr style="line-height: 20px;">
                    <td>&nbsp;</td>
                </tr> -->
                <tr>

                    <td style="color: #808080;font-size: 16px;">

                        <p>Pesanan anda dgn<strong> {{ $order['order']->order_number }}</strong> telah di terima oleh
                            pemasok dengan detail pesanan.
                        </p>
                        <p>Sebagai berikut :</p>
                        {{--<p>Nama Produk :<strong> {{ $order['order']->product_name }}</strong></p>
                        <p>Kuantitas :<strong>
                                {{ $order['order']->product_quantity . ' ' . $order['order']->unit_name }}</strong></p>
                        <p>Deskripsi Produk :<strong>
                                {{ $order['order']->category_name . ' ' . $order['order']->sub_category_name . ' ' . $order['order']->product_name . ' ' . $order['order']->product_description }}</strong>
                        </p>--}}
                        <p>
                        <table style="width: 100%;" cellpadding="5px" border="0">
                            <tr>
                                <th style="background-color: #002050; color: #fff;">Nama Produk</th>
                                <th style="background-color: #002050; color: #fff;">Deskripsi Produk</th>
                                <th style="background-color: #002050; color: #fff;">Kuantitas </th>
                            </tr>
                            @foreach($order['quote_items'] as $rfqProduct)
                                <tr>
                                    <td style="border-bottom: 1px solid #ccc;">{{get_product_name_by_id($rfqProduct['rfq_product_id'])}}</td>
                                    <td style="border-bottom: 1px solid #ccc;">{{get_product_desc_by_id($rfqProduct['rfq_product_id'])}}</td>
                                    <td style="border-bottom: 1px solid #ccc;">{{ $rfqProduct['product_quantity'].' '.get_unit_name($rfqProduct['price_unit'])  }}</td>
                                </tr>
                            @endforeach
                        </table>
                        </p>
                        <p>Untuk komunikasi selanjutnya silahkan hubungi <a href="mailto:contact@blitznet.co.id">contact@blitznet.co.id</a>
                        </p>
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

            <table
                style="border: 0; padding:0; margin:0 auto;text-align: left; width: 750px; background-color:#fff; font-size:14px;font-family: Arial, sans-serif;"
                border="0" cellspacing="10" cellpadding="0" width="">
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


    <!-- <tr style="line-height: 20px">
        <td class="line"></td>
    </tr> -->
    <tr>
        <td align="center" style="background-color: #f9f9f9;">
            <table cellspacing="0" cellpadding="0" border="0" align="center">
                <tr>
                    <td align="center"
                        style="color: #333; width: 750px; font-size: 16px;text-align: center; background-color: #72727e; ">
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
        <td style="line-height: 20px; background-color: #f9f9f9;">&nbsp;</td>
    </tr>
    </tbody>
</table>

</body>

</html>
