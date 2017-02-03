<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Big Shot MMA | Withdrawl Confirmation</title>
<style type="text/css">
        *{
            margin:0;
            padding:0;
        }
        a{
            color:#7fdaf3;
        }
        a:hover{
            color:#666;
            text-decoration:none;
        }
</style>
</head>
<body style="background:#ddd; font-family:Verdana, sans-serif; width:100% !important;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" mc:edit="body">
	<table style="width:600px; max-width:600px; margin:15px auto; padding:0; border-collapse:collapse; background:#fff;" width="600" align="center">
    	<tr>
            <td style="margin:0; padding:0; vertical-align:top;">
                <a href="{{ env('APP_URL') }}"><img src="{{ env('APP_URL') }}/public/image/logo.png" style="width:240px; margin:20px 0 0 15px; padding:0; display:block; text-align:center;"></a>
            </td>
        </tr>
        <tr>
            <td style="padding: 24px;">
                <p style="font-size: 14px; color: #222; margin-bottom: 16px;">We received your withdrawl request on {{ date('d/m/Y') }} for the amount of ${{ number_format((float)$amount, 2) }}. Your request will normally be processed within 48 hours. If you've requested a check it may take 5 - 8 days to receive depending on your location.</p>
                <p style="font-size: 14px; color: #222;">Thank you for playing!!</p>
            </td>
        </tr>
        <tr>
            <td style="padding: 24px; text-align:center;">
                <p style="font-size: 11px; color: #999; text-transform: uppercase;">Copyright &copy; {{ date('Y') }} Big Shot MMA</p>
            </td>
        </tr>
    </table>
</body>
</html>