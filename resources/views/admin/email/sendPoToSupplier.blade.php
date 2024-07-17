{{-- @component('mail::message')
# Introduction

The body of your message.

@component('mail::button', ['url' => $order['url']])

Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent --}}

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

<body>
    <table
        style="border: 0; padding:0; margin:0 auto;text-align: left; width:750px;font-size:14px;font-family: Arial, sans-serif;"
        border="0" cellspacing="0" cellpadding="0" width="750">
        <tbody>
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
            <tr style="line-height: 20px;">
                <td>&nbsp;</td>
            </tr>
            <tr>
                <th style="color: #000;font-size: 30px;font-weight: 800;">Order Confirmed
                    {{ $order['order']->order_number }}</th>
            </tr>
            <tr style="line-height: 30px;">
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td style="color: #808080;font-size: 16px;">
                    Hello {{ $order['order']->suppliers_name }},
                </td>
            </tr>
            <tr style="line-height: 30px;">
                <td>&nbsp;</td>
            </tr>

            <tr>

                <td style="color: #808080;font-size: 16px;">

                    <p>You have received a new Order {{ $order['order']->order_number }}. Please start preparing the
                        order and let us know when
                        it is Ready for Dispatch.
                    </p>
                    <p>Order details are as follows:</p>
                    <p>ProductName : {{ $order['order']->product_name }}</p>
                    <p>Quantity : {{ $order['order']->product_quantity . ' ' . $order['order']->unit_name }}</p>
                    <p>ProductDescription :
                        {{ $order['order']->category_name . ' ' . $order['order']->sub_category_name . ' ' . $order['order']->product_name . ' ' . $order['order']->product_description }}
                    </p>
                    <br>
                    <!-- <p> Please Contact on 09898989899 for further communication or write back to us on <a
                            href="mailto:contact@blitznet.co.id">contact@blitznet.co.id</a></p> -->
                            <p>Silahkan <a href="https://www.blitznet.co.id/admin">masuk Akun Anda</a> untuk memeriksa rincian lebih lanjut.</p>
                </td>
            </tr>
            <tr style="line-height: 20px;">
                <td>&nbsp;</td>
            </tr>


            <tr>
                <td style="color: #808080;font-size: 16px;">
                    Regards, <br>
                    Blitznet

                </td>
            </tr>
            <tr style="line-height: 30px;">
                <td>&nbsp;</td>
            </tr>


            <tr style="line-height: 20px">
                <td class="line"></td>
            </tr>
            <tr>
                <td
                    style="color: #d4d4d4;font-size: 16px;text-align: center; background-color: #333;">
                    Blitznet. All rights reserved.
                </td>
            </tr>
            <tr style="line-height: 20px;">
                <td>&nbsp;</td>
            </tr>

        </tbody>
    </table>

</body>

</html>
