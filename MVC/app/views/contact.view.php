<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>assets/css/common.css">
    <link rel="stylesheet" href="<?= ROOT ?>assets/css/contact.css">
    <title>Contact us</title>
</head>

<body>
<div id="page-loader">
    <div class="spinner"></div>
    <p>Sending Feedback...</p>
</div>
<?php include("components/navbar.php"); ?>

<div class='page-content'>
    <div class="contact-container">
        <h2>Contact Us</h2>
        <p>If you have any questions, suggestions, or need support, feel free to reach out to us using the form below.</p>

        <?php if (!empty($success)) : ?>
            <p style="color:green; font-weight:600;"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <?php if (!empty($errors)) : ?>
            <ul style="color:red;">
                <?php foreach ($errors as $e) : ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form method="POST" class="contact-form" autocomplete="off" onsubmit="return handleSubmit(this)">
            <input type="hidden" name="action" value="sending_feedback">

            <input type="text" name="name" placeholder="Your Name" required maxlength="80"
                   value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">

            <input type="email" name="email" placeholder="Your Email" required maxlength="120"
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

            <textarea name="message" rows="5" placeholder="Your Message" required minlength="5" maxlength="3000"><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>

            <button type="submit">Send Message</button>
        </form>
    </div>
</div>
</body>

<script>
    function handleSubmit(form) {
        if (form.dataset.submitted) return false;
        form.dataset.submitted = "true";

        document.getElementById("page-loader").classList.add("active");

        requestAnimationFrame(() => {
            requestAnimationFrame(() => form.submit());
        });

        return false;
    }
</script>

</html>