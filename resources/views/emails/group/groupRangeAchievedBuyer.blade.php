<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Group Range Achieved mail</title>
    <style>
        body,
        html {
            margin: 0;
            padding: 0;
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
                        <img src="{{ URL::asset('front-assets/images/front/header-logo.png') }}"
                             alt="Blitznet" title="Blitznet" valign="middle">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td align="center" style="background-color: #f9f9f9;">
            <table style="border: 0; padding:0; margin:0 auto;text-align: left; width:750px; background-color:#fff; font-size:14px;font-family: Arial, sans-serif;"
                   border="0" cellspacing="10" cellpadding="0" width="">
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
                        <p>We are pleased inform you that the target quantity of the group <strong>{{$group['group']->group_number.' '.ucfirst($group['group']->name) }}</strong> has been achieved which will lead to maximum discount.</p>

                        <table style="width: 100%;" cellpadding="5px" border="0">
                            <tr>
                                <th style="background-color: #002050; color: #fff;">Group Name</th>
                                <th style="background-color: #002050; color: #fff; text-align: center;">Min. Quantity</th>
                                <th style="background-color: #002050; color: #fff; text-align: center;">Max. Quantity</th>
                                <th style="background-color: #002050; color: #fff; text-align: center;">Discount</th>
                                <th style="background-color: #002050; color: #fff; text-align: center;">Status</th>

                            </tr>
                            <tr>
                                <td style="border-bottom: 1px solid #ccc;">{{ ucfirst($group['group']->name) }}</td>
                                <td style="border-bottom: 1px solid #ccc; text-align: center;">50 Ton</td>
                                <td style="border-bottom: 1px solid #ccc; text-align: center;">100 Ton </td>
                                <td style="border-bottom: 1px solid #ccc; text-align: center; color: green;">10% </td>
                                <td style="border-bottom: 1px solid #ccc; text-align: center; color: green;">Achieved </td>
                            </tr>

                        </table>

                    </td>
                </tr>

                <tr>
                    <td style="color: #808080;font-size: 16px; text-align: center;">
                        <p style="text-align: center;"><a href="{{ $group['url'] }}" target="_blank" title="Group Details"><img src="{{ url('front-assets/images/icons/group_now_in.png') }}" alt="Group Details"></a></p>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center; font-size: 12px;">
                        <p style="font-weight: bold;">Share:</p>
                        <p>
                            <a href="https://www.facebook.com/" target="_blank" ><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 30 30.005"><path d="M252.026,235.981q0-5.5,0-11a3.993,3.993,0,0,1,4.022-4.006H278a3.976,3.976,0,0,1,4,3.84c.043,1.45.018,2.9.019,4.354q0,8.8,0,17.6a4.023,4.023,0,0,1-3.393,4.148,4.221,4.221,0,0,1-.729.052c-1.986,0-3.972-.006-5.958.009-.324,0-.407-.089-.4-.409q.024-5,.015-9.991c0-.285.077-.36.358-.356,1.039.017,2.078.008,3.116.006.647,0,.842-.175.917-.815.118-1,.232-2,.355-3,.076-.619-.179-.932-.8-.936-1.176-.006-2.353-.015-3.529.007-.346.006-.439-.091-.429-.433.024-.87,0-1.741.009-2.612.011-1.394.555-1.974,1.953-2.048.609-.033,1.222-.005,1.833-.014s.823-.213.825-.827q0-1.307,0-2.612c0-.545-.2-.758-.744-.749a27.488,27.488,0,0,0-3.8.065,5.238,5.238,0,0,0-4.709,5.321c-.054,1.159-.033,2.322-.013,3.483.006.35-.1.438-.434.425-.717-.026-1.436-.011-2.154-.006-.622,0-.863.234-.863.861,0,1.069.018,2.137.038,3.206.008.462.243.679.728.683.779.007,1.558.014,2.337-.005.283-.007.36.075.359.358q-.021,5.019-.014,10.038c0,.281-.074.36-.357.359-3.483-.01-6.967.014-10.449-.014a3.981,3.981,0,0,1-4.052-4.164Z" transform="translate(-252.026 -220.973)" fill="#475992"/></svg></a>
                            &nbsp;&nbsp;
                            <a href="https://twitter.com/i/flow/login" target="_blank" ><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 30 29.999"><path d="M194.858,220.977H182.191q-6.358,0-12.715,0a2.2,2.2,0,0,0-2.31,2.308v25.381a2.2,2.2,0,0,0,2.308,2.309h25.382a2.2,2.2,0,0,0,2.31-2.308V223.286A2.2,2.2,0,0,0,194.858,220.977Zm-1.761,8.552a7.947,7.947,0,0,1-1.551,1.563.8.8,0,0,0-.349.719,13.437,13.437,0,0,1-4.411,10.075,12.338,12.338,0,0,1-7.673,3.242,13.237,13.237,0,0,1-7.618-1.662,2.542,2.542,0,0,1-.483-.344,9.625,9.625,0,0,0,6.647-1.915,4.775,4.775,0,0,1-4.255-3.254,4.9,4.9,0,0,0,1.948-.1,4.773,4.773,0,0,1-2.805-1.982,4.608,4.608,0,0,1-.75-2.2c-.026-.323.005-.449.365-.265a2.857,2.857,0,0,0,1.459.356c-.1-.249-.307-.36-.456-.511a4.686,4.686,0,0,1-.942-5.2c.176-.392.292-.37.561-.067a12.989,12.989,0,0,0,8.711,4.453c.359.036.485.013.429-.433a4.636,4.636,0,0,1,7.737-4,.594.594,0,0,0,.647.156c.426-.137.857-.261,1.272-.425s.814-.37,1.214-.554c-.1.683-.424,1.154-1.724,2.444a9.026,9.026,0,0,0,2.248-.583A.632.632,0,0,1,193.1,229.529Z" transform="translate(-167.166 -220.977)" fill="#1da0f1"/></svg></a>
                            &nbsp;&nbsp;
                            <a href="https://web.whatsapp.com/" target="_blank" ><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 30 30"><g transform="translate(-371.755 -220.976)"><path d="M401.755,225c-.082-.075-.051-.175-.058-.267v-.212a2.722,2.722,0,0,0-.2-1.048,4.013,4.013,0,0,0-.744-1.171,3.44,3.44,0,0,0-2.519-1.271c-.094-.007-.2.025-.273-.058H375.549c-.078.084-.181.051-.274.058a2.671,2.671,0,0,0-.935.166,4.062,4.062,0,0,0-1.839,1.408,3.077,3.077,0,0,0-.687,1.9c-.012.108.034.226-.059.319v22.3c.093.094.047.213.059.322a2.631,2.631,0,0,0,.172.963,4.105,4.105,0,0,0,1.283,1.721,3.144,3.144,0,0,0,2.037.788h.212c.091.007.191-.024.265.058h21.945c.075-.083.176-.051.267-.058h.213a2.767,2.767,0,0,0,.968-.167,4.08,4.08,0,0,0,1.824-1.4,3.047,3.047,0,0,0,.7-1.935v-.215c.012-.107-.033-.223.058-.315ZM388.289,245.03a9.135,9.135,0,0,1-5.43-.731.669.669,0,0,0-.472-.047c-1.433.346-2.87.681-4.306,1.018-.438.1-.629-.083-.537-.532q.442-2.168.9-4.334a.784.784,0,0,0-.05-.532,9.261,9.261,0,1,1,17.516-5.41,10.9,10.9,0,0,1,.113,1.337A9.289,9.289,0,0,1,388.289,245.03Z" fill="#29a61a"/><path d="M380.012,242.741c.144-.7.245-1.366.421-2.008a2.221,2.221,0,0,0-.192-1.681,7.214,7.214,0,1,1,3,3.191.947.947,0,0,0-.743-.1C381.7,242.362,380.881,242.537,380.012,242.741Z" fill="#29a61a"/><path d="M389.163,240.409a4.97,4.97,0,0,1-2.335-.646,10.748,10.748,0,0,1-4.119-3.9,3.652,3.652,0,0,1-.565-2.647,2.782,2.782,0,0,1,1.257-1.69.612.612,0,0,1,.891.276c.28.587.537,1.184.793,1.781a.621.621,0,0,1-.1.634c-.115.157-.238.307-.353.464a.64.64,0,0,0-.023.858,9.353,9.353,0,0,0,2.93,2.583.629.629,0,0,0,.847-.144c.776-.827.782-.634,1.652-.409.385.1.766.213,1.146.33a.672.672,0,0,1,.4,1.078,2.755,2.755,0,0,1-1.642,1.354A3.019,3.019,0,0,1,389.163,240.409Z" fill="#fefefe"/></g></svg></a>
                            &nbsp;&nbsp;
                            <a href="https://www.linkedin.com/" target="_blank" ><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 30 29.999"><path d="M119.874,220.977H107.207q-6.357,0-12.715,0a2.2,2.2,0,0,0-2.309,2.308q0,12.69,0,25.381a2.2,2.2,0,0,0,2.307,2.309h25.383a2.2,2.2,0,0,0,2.309-2.308V223.286A2.2,2.2,0,0,0,119.874,220.977ZM100.843,246.53q-1.983-.012-3.966,0c-.195,0-.235-.06-.235-.243q.009-3.46,0-6.923c0-2.291,0-4.583-.006-6.874,0-.226.066-.271.278-.27q1.971.015,3.942,0c.184,0,.221.05.221.226q-.008,6.923,0,13.845C101.081,246.482,101.031,246.531,100.843,246.53Zm-1.971-16.267a2.572,2.572,0,1,1,2.562-2.577A2.567,2.567,0,0,1,98.872,230.263Zm18.6,16.268q-1.971-.015-3.942,0c-.19,0-.241-.052-.24-.241q.009-3.593,0-7.187a6.054,6.054,0,0,0-.246-1.92,1.849,1.849,0,0,0-1.6-1.371,2.64,2.64,0,0,0-2.271.575,2.9,2.9,0,0,0-.795,1.808,8.691,8.691,0,0,0-.077,1.269c0,2.268,0,4.535.005,6.8,0,.213-.053.268-.266.266q-1.971-.015-3.942,0c-.192,0-.238-.056-.238-.242q.007-6.922,0-13.845c0-.187.057-.222.23-.221q1.911.012,3.822,0c.188,0,.224.058.22.231-.011.5,0,.993,0,1.49,0,.075-.027.157.034.238.092-.05.121-.146.173-.22a4.621,4.621,0,0,1,4.02-2.081,7.5,7.5,0,0,1,2.089.259,4.13,4.13,0,0,1,2.915,3.128,11.942,11.942,0,0,1,.361,3.138c.008,2.62,0,5.24.008,7.86C117.738,246.473,117.689,246.533,117.474,246.531Z" transform="translate(-92.182 -220.977)" fill="#0a66c1"/></svg></a>
                            &nbsp;&nbsp;
                            <a href="mailto:" target="_blank" ><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 30 29.999"><g transform="translate(-442.958 -222.868)"><path d="M470.649,222.868H445.267a2.2,2.2,0,0,0-2.309,2.308v25.381a2.2,2.2,0,0,0,2.308,2.309q12.69,0,25.382,0a2.2,2.2,0,0,0,2.31-2.308V225.177A2.2,2.2,0,0,0,470.649,222.868Z" fill="#fec007"/><path d="M467.444,232.365v.284q0,5.295,0,10.591c0,.69-.174.858-.866.858H449.334c-.687,0-.858-.169-.859-.865q0-5.28,0-10.562a.555.555,0,0,1,.03-.287c.106-.021.155.068.219.118q4.342,3.442,8.683,6.889a.729.729,0,0,0,1.113-.005q4.32-3.425,8.637-6.853A.564.564,0,0,1,467.444,232.365Z" fill="#fefefe"/><path d="M449.831,231.636h16.187c.015.1-.077.128-.131.171q-3.87,3.076-7.74,6.151c-.134.107-.217.127-.365.009q-3.876-3.091-7.762-6.17C449.97,231.758,449.923,231.715,449.831,231.636Z" fill="#fefefe"/></g></svg></a>
                        </p>
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

            <table
                style="border: 0; padding:0; margin:0 auto;text-align: left; width: 750px; background-color:#fff; font-size:14px;font-family: Arial, sans-serif;"
                border="0" cellspacing="10" cellpadding="0" width="">
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
