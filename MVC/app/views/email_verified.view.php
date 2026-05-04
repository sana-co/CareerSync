<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>assets/css/common.css">
    <link rel="stylesheet" href="<?= ROOT ?>assets/css/forms.css">
    <title>Email Verification – CareerSync</title>
    <style>
        .verify-container {
            max-width: 480px;
            margin: 80px auto;
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 40px 36px;
            text-align: center;
            box-shadow: 0 4px 18px rgba(0,0,0,0.07);
        }
        .verify-icon { font-size: 64px; margin-bottom: 16px; }
        .verify-container h2 { margin-bottom: 12px; color: #2c3e50; }
        .verify-container p { color: #555; line-height: 1.6; margin-bottom: 24px; }
        .btn-login {
            display: inline-block; background: #007bff; color: #fff;
            padding: 12px 32px; border-radius: 6px; text-decoration: none;
            font-size: 16px; margin-top: 8px;
        }
        .btn-login:hover { background: #0056b3; }
        .btn-resend {
            display: inline-block; margin-top: 14px; font-size: 13px;
            color: #007bff; text-decoration: none;
        }
    </style>
</head>
<body>
    <?php include("components/navbar.php"); ?>
    <div class="page-content">
        <div class="verify-container">
            <?php if (!empty($success)): ?>
                <div class="verify-icon">✅</div>
                <h2>Email Verified!</h2>
                <p><?= htmlspecialchars($message) ?></p>
                <a href="<?= ROOT ?>login" class="btn-login">Log In Now</a>
            <?php else: ?>
                <div class="verify-icon">❌</div>
                <h2>Verification Failed</h2>
                <p><?= htmlspecialchars($message) ?></p>
                <a href="<?= ROOT ?>emailverification/pending" class="btn-resend">
                    → Resend verification email
                </a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>