<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Group Closed mail</title>
    <style>
        body,
        html {
            margin: 0;
            padding: 0;
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
                        <img src="{{ URL::asset('front-assets/images/front/header-logo.png') }}" alt="Blitznet" title="Blitznet" valign="middle">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td align="center" style="background-color: #f9f9f9;">
            <table style="border: 0; padding:0; margin:0 auto;text-align: left; width:750px; background-color:#fff; font-size:14px;font-family: Arial, sans-serif;" border="0" cellspacing="10" cellpadding="0" width="">
                <tr style="line-height: 20px;">
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td style="color: #808080;font-size: 16px;">
                        Hello <strong>{{$BuyerName}}</strong>,
                    </td>
                </tr>
                <tr>
                    <td style="color: #808080;font-size: 16px;">
                        <p>This is to inform you that the Group <strong>{{ $group->group_number . ' ' . $group->name }} </strong> has been closed.</p>

                        <table style="width: 100%;" cellpadding="5px" border="0">
                            <tr>
                                <th style="background-color: #002050; color: #fff;">Group Name</th>
                                <th style="background-color: #002050; color: #fff; text-align: center;">Target Quantity</th>
                                <th style="background-color: #002050; color: #fff; text-align: center;">Achieved Quantity</th>
                                <th style="background-color: #002050; color: #fff; text-align: center;">Discount</th>
                                <th style="background-color: #002050; color: #fff; text-align: center;">Status</th>
                            </tr>
                            <tr>
                                <td style="border-bottom: 1px solid #ccc;">{{ ucfirst($group->name) }}</td>
                                <td style="border-bottom: 1px solid #ccc; text-align: center;">{{ $group->target_quantity.' '. ucfirst($group->unit_name)}}</td>
                                <td style="border-bottom: 1px solid #ccc; text-align: center;">{{ $group->achieved_quantity.' '. ucfirst($group->unit_name)}}</td>
                                @if($group->achieved_quantity > 0)
                                    @foreach($productDetailsMultiple as $key => $value)
                                        @if(($group->achieved_quantity >= $value->min_quantity && $group->achieved_quantity <= $value->max_quantity) )
                                            <td style="border-bottom: 1px solid #ccc; text-align: center;">{{ $value->discount }}% </td>
                                        @endif
                                    @endforeach
                                @else
                                    <td style="border-bottom: 1px solid #ccc; text-align: center;">0% </td>
                                @endif
                                <td style="border-bottom: 1px solid #ccc; text-align: center; color: red;">Closed </td>
                            </tr>
                        </table>

                    </td>
                </tr>

                <tr>

                    <td style="color: #808080;font-size: 16px; text-align: center;">
                        <p style="text-align: center;"><a href="{{ $url }}" target="_blank" title="Group Details"><img src="{{ url('front-assets/images/icons/group_now_in.png') }}" alt="Group Details"></a></p>
                    </td>
                </tr>
                <tr>
                    <td style="color: #808080;font-size: 16px;">
                        <p>Get more information about the group by <a href="https://www.blitznet.co.id/signin">Logging</a> into the system.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="color: #808080;font-size: 16px; background-color: #f9f9f9;" align="center">

            <table style="border: 0; padding:0; margin:0 auto;text-align: left; width: 750px; background-color:#fff; font-size:14px;font-family: Arial, sans-serif;" border="0" cellspacing="10" cellpadding="0" width="">
                <tr>
                    <td style="color: #808080;font-size: 16px;">
                        Regards,<br>Blitznet
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