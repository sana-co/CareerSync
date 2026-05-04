<!DOCTYPE html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>assets/css/common.css">
    <link rel="stylesheet" href="<?= ROOT ?>assets/css/dashboard/dash.css">
    <title>Dashboard</title>
</head>

<body>
    <div class="page-wrapper">
        <?php
        include("components/navbar.php");
        $messageCount = count(array_filter((array)($data['messages'] ?? []), fn($msg) => is_object($msg) && isset($msg->is_read) && !$msg->is_read));
        $alertCount = count(array_filter((array)($data['alerts'] ?? []), fn($alert) => is_object($alert) && isset($alert->is_read) && !$alert->is_read));
        $sysAlertCount = count(array_filter((array)($data['sysAlerts'] ?? []), fn($alert) => is_object($alert) && isset($alert->action) && $alert->action === 'ALERT'));
        $notificationCount = $messageCount + $alertCount + $sysAlertCount;
        ?>
        <div class="page-content">
            <div class="settings_btn" onclick="toggleSettings()">
                <img src="<?= ROOT ?>assets/images/settings_icon.png" alt="settings_btn">
            </div>
            <div class="messages_btn" onclick="toggleMessages()">
                <img src="<?= ROOT ?>assets/images/messages_icon.png" alt="messages_btn">
                <?php if ($notificationCount > 0): ?>
                    <span class="notification_badge" id="dashboard_notifications_badge"><?= $notificationCount ?></span>
                <?php endif; ?>
            </div>
            <?php
            switch ($_SESSION['USER']->role) {
                case 'admin':
                    include("dashboards/adminDash.php");
                    break;
                case 'candidate':
                    include("dashboards/candidateDash.php");
                    break;
                case 'counselor':
                    include("dashboards/counselorDash.php");
                    break;
                case 'validator':
                    include("dashboards/validatorDash.php");
                    break;
                case 'company':
                    include("dashboards/companyDash.php");
                    break;
            }
            ?>
        </div>
    </div>
    <script>
        function toggleSettings() {
            const settings_menu = document.getElementById('settings_menu');
            const message_menu = document.getElementById('message_menu');
            settings_menu.classList.toggle('active');
            message_menu.classList.remove('active');
        }

        function toggleMessages() {
            const message_menu = document.getElementById('message_menu');
            const settings_menu = document.getElementById('settings_menu');
            message_menu.classList.toggle('active');
            settings_menu.classList.remove('active');
        }

        document.addEventListener("DOMContentLoaded", () => {
            const settings_menu = document.getElementById("settings_menu");
            const message_menu = document.getElementById("message_menu");
            const settingsBtn = document.querySelector(".settings_btn");
            const messagesBtn = document.querySelector(".messages_btn");

            document.addEventListener("click", (e) => {
                const clickedInsideMenu =
                    settings_menu.contains(e.target) ||
                    message_menu.contains(e.target);

                const clickedOnToggle =
                    settingsBtn.contains(e.target) ||
                    messagesBtn.contains(e.target);

                if (!clickedInsideMenu && !clickedOnToggle) {
                    settings_menu.classList.remove("active");
                    message_menu.classList.remove("active");
                }
            });
        });

        document.addEventListener("DOMContentLoaded", () => {
            const profileBtn = document.getElementById("profileBtn");
            const backBtn = document.getElementById("backBtn");
            const profileDisplay = document.querySelector(".profile_display");
            const editBtn = document.getElementById("editBtn");
            const edit_backBtn = document.getElementById("edit_backBtn");
            const editProfile = document.querySelector(".edit_profile_display");

            profileBtn.addEventListener("click", (e) => {
                e.preventDefault();
                profileDisplay.classList.add("active");
                document.body.style.overflow = "hidden";
            });

            backBtn.addEventListener("click", () => {
                profileDisplay.classList.remove("active");
                document.body.style.overflow = "auto";
            });

            editBtn.addEventListener("click", (e) => {
                e.preventDefault();
                editProfile.classList.add("active");
                document.body.style.overflow = "hidden";
            });

            edit_backBtn.addEventListener("click", () => {
                editProfile.classList.remove("active");
                document.body.style.overflow = "auto";
            });
        });

        const rootPath = "<?= ROOT ?>assets/svg_icons/";

        function togglePassword(inputId, eyeId) {
            const input = document.getElementById(inputId);
            const eye = document.getElementById(eyeId);

            if (!input || !eye) return;

            if (input.type === "password") {
                input.type = "text";
                eye.style.backgroundImage = `url(${rootPath}eye_close.svg)`;
            } else {
                input.type = "password";
                eye.style.backgroundImage = `url(${rootPath}eye_open.svg)`;
            }
        }

        function show_password() {
            togglePassword("pass", "eye1");
        }

        function show_confirm_password() {
            togglePassword("confirm_pass", "eye2");
        }

        function show_confirm_password_in_profile() {
            togglePassword("confirm_pass_in_profile", "eye3");
        }

        function show_old_password() {
            togglePassword("oldPass", "eye0");
        }
    </script>
    <?php if (!empty($errors)): ?>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                <?php if (isset($errors['email']) || isset($errors['admin_photo_path']) || isset($errors['confirm_password'])): ?>
                    document.querySelector(".profile_display").classList.add("active");
                    document.querySelector(".edit_profile_display").classList.add("active");
                <?php endif; ?>
                <?php if (isset($errors['oldPassword']) || isset($errors['confirm_new_password'])): ?>
                    document.querySelector(".editPwDisplay").classList.add("active");
                <?php endif; ?>
                document.body.style.overflow = "hidden";
            });
        </script>
    <?php endif; ?>

    <script>
        // Messaging panel tab switching (applies across dashboard pages)
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.message_menu').forEach(menu => {
                const tabs = menu.querySelectorAll('.msg-tab');
                const sections = menu.querySelectorAll('[data-section]');

                function showSection(name) {
                    // Check if the target section has a <ul> with items
                    let hasContent = false;
                    sections.forEach(s => {
                        const v = s.getAttribute('data-section');
                        if (v === name && s.tagName.toLowerCase() === 'ul') {
                            hasContent = true;
                        }
                    });

                    sections.forEach(s => {
                        const v = s.getAttribute('data-section');
                        if (!v) return;
                        let shouldShow = false;
                        if (v === name) {
                            shouldShow = true;
                        } else if (v === 'empty' && !hasContent) {
                            shouldShow = true;
                        }
                        s.style.display = shouldShow ? 'flex' : 'none';
                    });
                }

                tabs.forEach(t => {
                    t.addEventListener('click', () => {
                        tabs.forEach(x => x.classList.remove('active'));
                        t.classList.add('active');
                        const tab = t.getAttribute('data-tab');
                        showSection(tab === 'alerts' ? 'alerts' : 'messages');
                    });
                });

                // initialize - show messages or empty state
                showSection('messages');
            });

            // no-JS delete/clear mode: forms submit to the server and reload page.
            // tab switching remains; no API/fetch calls are used here.
        });
    </script>

</body>

</html>