<!DOCTYPE html>
<html>
<head>
    <title>Welcome to the Bitefight!</title>
</head>

<body>
<h2>Welcome to the Bitefight {{$user['name']}}</h2>
<br/>
Your registered email is {{$user['email']}} , Please click on the below link to verify your email account
<br/>
<a href="{{url('/user/verify', $token)}}">Verify Email</a>
</body>

</html>