<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>assets/css/common.css">
    <link rel="stylesheet" href="<?= ROOT ?>assets/css/forms.css">
    <title>Welcome to CareerSync</title>
    <script>
        window.onload = function() {
            const select = document.getElementById('role');
            const button = document.getElementById('proceedBtn');
            button.disabled = true;
            select.addEventListener('change', function() {
                const selected = select.value;
                // Enable if a valid option is picked
                if (selected !== '') {
                    button.disabled = false;
                } else {
                    button.disabled = true;
                }
            });
        };
    </script>
</head>

<body>
    <?php
    include("components/navbar.php");
    ?>
    <div class='page-content'>
        <div class="role-container">
            <h1>Welcome to CareerSync</h1>
            <h3>please choose your role</h3>
            <form action="register" method="GET">
                <select name="role" id="role">
                    <option value="" disabled selected hidden>Your role</option>
                    <option value="candidate">Candidate</option>
                    <option value="validator">Validation Team Member</option>
                    <option value="company">Company</option>
                    <option value="counselor">Career-Counselor</option>
                </select>
                <button type="submit" id="proceedBtn">Proceed</button>
            </form>
            <div class="links">
                <a href="login">Sign in instead</a></t>
            </div>
        </div>
    </div>
</body>

</html>