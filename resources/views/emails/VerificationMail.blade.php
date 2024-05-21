<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
    <h1>Hi, {{ $mailData['name'] }}</h1>
    <p>Thank you for registering with us. Please click on the link below to verify your email address.</p>
    <a href="{{ url('api/verify-email?code='.$mailData['code']) }}">Verify Email</a>
    <p>Thank you</p>
</body>
</html>