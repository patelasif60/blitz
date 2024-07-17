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
                <th style="color: #000;font-size: 30px;font-weight: 800;">Quote Received
                    {{ $quote['quote']->reference_number }}
                </th>
            </tr>
            <tr style="line-height: 30px;">
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td style="color: #808080;font-size: 16px;">
                    Hello {{ $quote['quote']->firstname . ' ' . $quote['quote']->lastname }},
                </td>
            </tr>
            <tr style="line-height: 30px;">
                <td>&nbsp;</td>
            </tr>

            <tr>

                <td style="color: #808080;font-size: 16px;">

                    <p>A response for your {{ $quote['quote']->reference_number }} has been received.
                    <p>
                    <p>Requested Product:
                        {{ $quote['quote']->category . ' ' . $quote['quote']->sub_category . ' ' . $quote['quote']->product . ' ' . $quote['quote']->product_description }}
                    </p>
                    <p>Quantity: {{ $quote['quote']->quantity . ' ' . $quote['quote']->unit_name }}</p>
                    <p>Please <a href="https://www.blitznet.co.id/signin">login into your account</a> to check the
                        further details.</p>
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
