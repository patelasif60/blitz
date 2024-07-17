<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Supplier should notify for RFQs which orders are not placed</title>
    <style>
        body,
        html {
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
            <img src="http://www.blitznet.co.id/front-assets/images/front/header-logo.png" alt="">
        </td>

    </tr>
    <tr>
        <td align="center" style="background-color: #002050;">
            <table border="0" width="800" style="margin: 0 auto; background-color: #fff;" align="center">
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>
                        <h1 style="font-size: 30px;color: #002050; text-align: left; padding-left: 50px; margin-bottom: 10px;"> Hello {{$supplierDetails['contact_person_name'].' '.$supplierDetails['contact_person_last_name']}}</h1>
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
                        <table border="0" width="89%" style="margin: 0 auto; color: #585858; font-size: 15px;" align="center" cellpadding="0">
                            <tr>
                                <td style="font-family: Arial, Helvetica, sans-serif;  line-height: 1.5;">
                                    <p style="margin-top: 0;">We hope you are doing well. We just wanted to know what you have thought about remaining products RFQs. Let us know if we can be of help to you.</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    @foreach($rfqsDetails as $mainCategories)
                                        @if(!empty($mainCategories['subcat_name']))
                                    <table border="0" width="100%" style="margin: 0 auto; color: #585858; font-size: 13px;" align="center" cellpadding="5" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th colspan="3" style="background-color: #dde4ff; border-bottom: 1px solid #ccc; color: #000; text-align: left">{{$mainCategories['cat_name'].'-'.$mainCategories['subcat_name']}}</th>
                                            </tr>
                                            <tr>
                                                <th style="background-color: #eef1ff; border-bottom: 1px solid #ccc; text-align: left">RFQ No</th>
                                                <th style="background-color: #eef1ff; border-bottom: 1px solid #ccc; text-align: left">Product Details</th>
                                                <th style="background-color: #eef1ff; border-bottom: 1px solid #ccc; text-align: left">Expiry Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($mainCategories as $i => $allRfqs)
                                                @if(isset($allRfqs['reference_number']))
                                                    <tr>
                                                        <td style=" border-bottom: 1px solid #ccc;">{{$allRfqs['reference_number']}}</td>
                                                        <td style=" border-bottom: 1px solid #ccc;">{{$allRfqs['product_name']}}</td>
                                                        <td style=" border-bottom: 1px solid #ccc;"><span style="font-size: 12px;">{{ date('d M Y', strtotime($allRfqs['product_expected'])) }}</span></td>
                                                    </tr>
                                                    @if($i == 4) @break; @endif
                                                @endif
                                            @endforeach
                                            <tr>
                                                <td style="text-align: right;padding: 10px 0px 0px 0px" colspan="4">
                                                    <a href="{{ route('rfq-list')}}" target="_blank" style="background-color: #002050; padding: 5px 12px; color: #fff; text-decoration: none;">View All</a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                        @endif
                                    <br>
                                    @endforeach
                                    <br>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Please <a href="{{route('admin-login-form')}}" target="_blank">login to your account</a> for further information.</p>
                                    <p>Salam,<br>Blitznet</p>
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
                    <td align="center" style="color: #333; font-size: 16px;text-align: center; background-color: #72727e;">
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
