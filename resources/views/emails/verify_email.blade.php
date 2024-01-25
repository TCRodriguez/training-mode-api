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

        .email-header {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .email-body {
            display: flex;
            flex-direction: column;
            align-items: start;
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
            <p>Thank you for signing up for TrainingMode! We're excited to have you on board.</p>
            
            <p>Before you get started, we need to verify your email address. This ensures the security of your account and enables you to recover your account if you ever forget your password.</p>
            
            <p>Please click the link below to verify your email address:</p>
            <a href="{{ $verificationUrl }}">{{ $verificationUrl }}</a>

            <p>If the link above doesn't work, you can copy and paste the URL directly into your web browser's address bar.</p>

            <p>Didn't sign up for TrainingMode? If you received this email by mistake, you can safely ignore it. The account will not be activated without email verification.</p>

            <p>For any questions or support, feel free to reach out to us at <a href="mailto:support@trainingmode.gg">support@trainingmode.gg</a>.</p>

            <p>Best regards,<br>
            The TrainingMode Team</p>

            <hr>

            <p><em>TrainingMode is committed to providing you with the best experience in knowledge management for fighting games. Explore, learn, and dominate the game!</em></p>

            <p><small>You're receiving this email because you recently created a new account on TrainingMode. If you did not create this account, please contact us.</small></p>
        </div>
    </div>
</body>
</html>
