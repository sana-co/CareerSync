<link rel="stylesheet" href="<?= ROOT ?>assets/css/navbar.css">
<div class="desktop">
    <nav class="navbar">
        <div class="navbar-left">
            <div class="logo">CareerSync</div>
            <ul class="navbar_links">
                <li><a href="<?= ROOT ?>home"><button class="navbtn">Home</button></a></li>
                <li><a href="<?= ROOT ?>about"><button class="navbtn">About</button></a></li>
                <li><a href="<?= ROOT ?>contact"><button class="navbtn">Contact</button></a></li>
                <?php
                if (isset($_SESSION['USER'])) {
                ?>
                    <li><a href="<?= ROOT ?>dashboard"><button class="navbtn" style="padding: 0px;">Dashboard</button></a></li>
                <?php
                }
                ?>
            </ul>
        </div>
        <ul class="navbar_right">
            <?php
            if (!isset($_SESSION['USER'])) {
            ?>
                <li><a href="<?= ROOT ?>login"><button class="navbtn">Login</button></a></li>
                <li><a href="<?= ROOT ?>welcome"><button class="navbtn">Register</button></a></li>
            <?php
            } else {
            ?>
                <li class="nav_pfp"><img src="<?= ROOT.$_SESSION['USER']->photo_path ?>" alt="pfp"></li>
                <li><a href="<?= ROOT ?>logout" onclick="return confirm('Are you sure you want to log out?');"><button class="navbtn">Log out</button></a></li>
            <?php
            }
            ?>
        </ul>
    </nav>
</div>
<div class="mobile">
    <nav class="navbar">
        <div class="hamburger_btn" onclick="toggleMenu()">
            <img src="<?= ROOT ?>assets/images/options_btn.png" alt="options_btn">
        </div>
        <div class="logo">CareerSync</div>
        <?php if (isset($_SESSION['USER'])) { ?>
            <li class="nav_pfp"><img src="<?=ROOT.$_SESSION['USER']->photo_path ?>" alt="pfp"></li>
        <?php
        } ?>
    </nav>

    <div class="mobile_menu" id="mobileMenu">
        <ul class="mobile_links">
            <li><a href="<?= ROOT ?>home"><button class="navbtn">Home</button></a></li>
            <li><a href="<?= ROOT ?>about"><button class="navbtn">About</button></a></li>
            <li><a href="<?= ROOT ?>contact"><button class="navbtn">Contact</button></a></li>
            <?php if (isset($_SESSION['USER'])) { ?>
                <li><a href="<?= ROOT ?>dashboard"><button class="navbtn" style="padding: 0px;">Dashboard</button></a></li>
            <?php } ?>
            <?php if (!isset($_SESSION['USER'])) { ?>
                <li><a href="<?= ROOT ?>login"><button class="navbtn">Login</button></a></li>
                <li><a href="<?= ROOT ?>welcome"><button class="navbtn">Register</button></a></li>
            <?php } else { ?>
                <li><a href="<?= ROOT ?>logout" onclick="return confirm('Are you sure you want to log out?');"><button class="navbtn">Log out</button></a></li>
            <?php } ?>
        </ul>
    </div>
</div>
<script>
    function toggleMenu() {
        const menu = document.getElementById('mobileMenu');
        menu.classList.toggle('active');
    }
</script>