<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>assets/css/common.css">
    <link rel="stylesheet" href="<?= ROOT ?>assets/css/home.css">
    <script>
        function confirm_logout() {
            if (confirm("Are you sure you want to log out") == true) {
                event.preventDefault();
            }
        }

        window.onload = function() {
            const rangeInput = document.getElementById("salRange");
            const valueDisplay = document.getElementById("salValue");

            rangeInput.addEventListener("input", function() {
                valueDisplay.textContent = Number(this.value).toLocaleString();
            });
        };
    </script>
    <title>Home</title>
</head>

<body>
    <div class="page-wrapper">
        <?php
        include("components/navbar.php");
        ?>
        <div class="ss">
            <img id="heroImg" src="<?= ROOT ?>/assets/slideshow/1.png" alt="slideshow image">
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const slides=[
                "<?= ROOT ?>/assets/slideshow/1.png",
                "<?= ROOT ?>/assets/slideshow/2.png",
            ]
            let slideIndex = 0;
            const hero = document.getElementById('heroImg');

            function nextSlide() {
                hero.classList.add('fade-out');
                setTimeout(() => {
                    slideIndex = (slideIndex + 1) % slides.length;
                    hero.src = slides[slideIndex];
                    hero.classList.remove('fade-out');
                }, 250);
            }

            setInterval(nextSlide, 5000);
        });
        </script>

        <div class='page-content'>
            <section class="intro">
                <div class="intro-content">
                    <h1>Empowering Careers. Connecting Talent.</h1>
                    <p class="introTxt1">One platform for potential employees and companies to collaborate.</p>
                    <?php
                    if ($username == 'User') {
                    ?>
                        <p class="introTxt2">you are currently exploring as a guest.<br> would you like to:</p>
                        <div class="intro-buttons">
                            <a href="login"><button class="intro-btn">Login</button></a>
                            <a href="welcome"><button class="intro-btn secondary">Register</button></a>
                        </div>
                    <?php
                    } else {
                    ?>
                        <h2 style="font-family: roboto,sans-serif;">Welcome, <?= $username ?></h1>
                        <?php
                    }
                        ?>
                </div>
            </section>
            <?php
            //include("components/searchBar.php");
            include("components/joblist.php");
            ?>
        </div>
        <?php
        include("components/footer.php");
        ?>
    </div>
</body>

</html>