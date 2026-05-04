<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>assets/css/common.css">
    <link rel="stylesheet" href="<?= ROOT ?>assets/css/forms.css">
    <title>Verify Your Email – CareerSync</title>
    <style>
        .verify-container {
            max-width: 500px;
            margin: 80px auto;
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 40px 36px;
            text-align: center;
            box-shadow: 0 4px 18px rgba(0,0,0,0.07);
        }
        .verify-icon { font-size: 56px; margin-bottom: 16px; }
        .verify-container h2 { margin-bottom: 10px; color: #2c3e50; }
        .verify-container p { color: #555; line-height: 1.6; margin-bottom: 20px; }
        .alert-success {
            background: #d4edda; color: #155724;
            border: 1px solid #c3e6cb; border-radius: 6px;
            padding: 12px 16px; margin-bottom: 20px;
        }
        .alert-error {
            background: #f8d7da; color: #721c24;
            border: 1px solid #f5c6cb; border-radius: 6px;
            padding: 12px 16px; margin-bottom: 20px;
        }
        .resend-form { margin-top: 28px; border-top: 1px solid #eee; padding-top: 24px; }
        .resend-form p { font-size: 14px; color: #777; margin-bottom: 12px; }
        .resend-form input[type="email"] {
            width: 100%; padding: 10px 14px; border: 1px solid #ccc;
            border-radius: 6px; font-size: 14px; margin-bottom: 10px;
            box-sizing: border-box;
        }
        .resend-form button {
            background: #007bff; color: #fff; border: none;
            padding: 10px 24px; border-radius: 6px; cursor: pointer;
            font-size: 14px; width: 100%;
        }
        .resend-form button:hover { background: #0056b3; }
        .login-link { margin-top: 20px; font-size: 14px; }
        .login-link a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <?php include("components/navbar.php"); ?>
    <div class="page-content">
        <div class="verify-container">
            <div class="verify-icon"></div>
            <h2>Check Your Email</h2>
            <p>We've sent a verification link to your email address.<br>
               Please click the link in the email to activate your account.</p>
            <p style="font-size:13px; color:#999;">The link will expire in <strong>24 hours</strong>.</p>

            <?php if (!empty($message)): ?>
                <div class="<?= !empty($sent) ? 'alert-success' : 'alert-error' ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <div class="resend-form">
                <p>Didn't receive the email? Enter your email below to resend it.</p>
                <form method="POST" action="<?= ROOT ?>emailverification/resend">
                    <input type="email" name="email" placeholder="your@email.com" required>
                    <button type="submit">Resend Verification Email</button>
                </form>
            </div>

            <div class="login-link">
                <a href="<?= ROOT ?>login">← Back to Login</a>
            </div>
        </div>
    </div>
</body>
</html>