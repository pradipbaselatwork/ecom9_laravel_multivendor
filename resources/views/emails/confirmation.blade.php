<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
       <tr><td>Dear {{ $name }},</td></tr>
       <tr><td>&nbsp;<br></td></tr>
       <tr><td>Please click on below link to activate your Web Hat Developers Account :-:</td></tr>
       <tr><td>&nbsp;</td></tr>
       <tr><td><a href="{{ url('/user/confirm/'.$code) }}">Confirm Account</a></td></tr>
       <tr><td>&nbsp;</td></tr>
       <tr><td>&nbsp;</td></tr>
       <tr><td>Thanks & Regards,</td></tr>
       <tr><td>Web Hat Developers</td></tr>
</body>
</html>
