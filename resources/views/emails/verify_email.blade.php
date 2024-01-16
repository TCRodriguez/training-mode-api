<!DOCTYPE html>
<html>
<head>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Play&display=swap');

        body{
            font-family: 'Play', sans-serif;
        }
        
        .main-content {
            padding: 2rem;
            background-color: #181E5B;
            color: #FFF;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .tm-banner {
            width: 50%;
            height: auto;
        }

        .email-header,
        .email-body {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        a {
            color: #E6C900;
        }
    </style>
    <title>Email Verification</title>
</head>
<body>
    <div class="main-content">
        <div class="email-header">
            <img src="{{ asset('images/banners/Training_Mode_Logo_White.png') }}" alt="" class="tm-banner">
            <h1>Welcome, {{ $user->username }}!</h1>
        </div>
        <div class="email-body">
            <p>Please click the link below to verify your email address:</p>
            <a href="{{ $verificationUrl }}">Verify Email</a>
        </div>
    </div>
</body>
</html>
