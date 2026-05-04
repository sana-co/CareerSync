<link rel="stylesheet" href="<?= ROOT ?>assets/css/footer.css">

<footer>
    <div class="footer-container">
        <div class="footer-column">
            <h1 class="logo">CareerSync</h1>
            <p>Empowering careers.<br>Connecting talent.</p>
        </div>

        <div class="footer-column" id="nav_links">
            <h3>Navigation</h3>
            <ul>
                <li><a href="home">Home</a></li>
                <li><a href="login">Login</a></li>
                <li><a href="welcome">Register</a></li>
                <li><a href="about">About Us</a></li>
                <li><a href="contact">Contact Us</a></li>
            </ul>
        </div>

        <div class="footer-column">
            <h3>Contact</h3>
            <p>Phone: <?= htmlspecialchars($admin_contact) ?></p>
            <p>Email: <?= htmlspecialchars($admin_email) ?></p>
        </div>
    </div>
</footer>