<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="format-detection" content="telephone=no"> 
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;">
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
    <title>HMB Projects Pvt Ltd - Admin Panel</title>
    <style type="text/css">
        body {
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>
    <table width="600" cellpadding="0" cellspeacing="0" border="0" style="margin: 0 auto; background-color: #f9f9f9;">
        <tr>
            <td width="100%" style="text-align: center; padding: 20px;"><img src="{{ URL::asset('assets/admin/img/logo.png') }}" width="157px"/></td>
        </tr>
        <tr>
            <td width="100%" style="text-align: center; padding: 40px;" bgcolor="#088b7b">
                <span><img src="{{ URL::asset('assets/admin/img/padlock.png') }}"/></span>
                <h2 style="font-size: 24px; color: #fff; margin: 10px 0 0 0;"> Please reset your password</h2>
            </td>
        </tr>
        <tr>
            <td width="100%" style="padding: 40px;">
                <p style="font-size: 20px; line-height: 32px; color: #393939;">Dear <b>{{ $data['name'] }}</b>,<br/>
                We have sent you this email in response to your request to reset your password on HMB Projects.
                To reset your password, please follow the link below:</p>

                <a href="{{ $data['link'] }}" style="background-color: #088b7b; padding: 15px 35px; font-size: 18px; color: #fff; text-decoration: none; margin-top: 20px; display: inline-block;">Reset Password</a>

                <p style="font-size: 18px; color: #838383; font-style: italic; margin: 30px 0;">
                Please ignore this email if you did not request a password change.</p>
            </td>
        </tr>
        <tr>
            <td bgcolor="#088b7b"><p style="text-align: center; color: #fff; font-size: 18px;">© HMB Projects 2023. All Rights Reserved.</p></td>
        </tr>
    </table>

</body>
</html>