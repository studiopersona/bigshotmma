<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BloodsportMMA | Password Reset Link</title>
<style type="text/css">
* {margin:0; padding:0;}
body {font-family: 'Montserrat', sans-serif; background:#fff;}
#email {color:#000; text-align:center; width:340px; margin:2em auto; border:1px solid #000;}
#email img {display:block; text-align:center; margin:1.5em auto 3em auto;width: 80%;}
#email p {font-size:1.15em; margin:0 1em 1em 1em; font-weight:300; line-height:1.25em;}
#email p a {font-size:16px;}
#email .button { display: inline-block; margin-top: 1rem; padding: 1rem; background-color: #e51937; color: #fff; text-transform: uppercase; text-decoration: none; font-weight: 700; }
#email h3 {font-size:2.25em; text-transform:uppercase; font-weight:900; margin:1em auto .5em auto;}
#email h4 {font-size:2em; font-weight:900;}
#email h5 {font-size:1.6em; font-weight:normal; margin-bottom:0.15em;}
#email span {display:block; margin:0 auto 2em auto;}
#email span strong {font-size:1.4em;}

.email-verify {padding-top:0; margin-bottom:3em;}
.email-verify a {color:#a80300;}

.login {margin:2em auto 3em auto;}
.login a {display:inline-block; font-size:1.65em; color:#fff; background:#a80300; text-decoration:none; padding:.35em 1.75em; text-transform:uppercase; margin:0 0.2em; border-radius:5px; font-weight:bold;}
.login a:hover {background:#000; color:#fff;}

#wrap {width:100%; height:100%;}
</style>
<link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
</head>
<body>
<div id="wrap">
	<div id="email">
        <div class="email-verify">
            <h3 style="line-height:1em;">Password Reset Request</h3>
			<p>We received a password change request for your email address. To complete the password reset follow this link: <br><a class="button" href="{{ $link = url('password/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}">Reset Link</a></p>
            <a href="{{ url('/') }}"><img src="http://www.bsmma.com/public/image/logo.png" /></a>
        </div>
	</div>
</div>
</body>
</html>

