<link rel="stylesheet" href="<?= ROOT ?>assets/css/dashboard/changePassword.css">
<div class="editPwDisplay">
    <div class="editPwWindow">
        <h1>Change Password</h1>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="password_change">
            <div class="input-field">
                <label for="oldPassword">Old Password</label>
                <input
                    type="password"
                    id="oldPass"
                    placeholder="Old Password"
                    name="oldPassword"
                    required
                    style="<?= !empty($errors['oldPassword']) ? 'border: 2px solid red;' : '' ?>">
                <button onclick="show_old_password()" class="eye" type="button" id="eye0"></button>
            </div>
            <?php if (!empty($errors['oldPassword'])): ?>
                <div style="color:red; padding-bottom:15px;" class="error"><?= $errors['oldPassword'] ?></div>
            <?php endif; ?>

            <div class="input-field">
                <label for="newPassword">New Password</label>
                <input
                    type="password"
                    id="pass"
                    placeholder="New Password"
                    name="newPassword"
                    required>
                <button onclick="show_password()" class="eye" type="button" id="eye1"></button>
            </div>

            <div class="input-field">
                <label for="confirm_new_password">Re-enter New Password</label>
                <input
                    type="password"
                    id="confirm_pass"
                    placeholder="Confirm New Password"
                    name="confirm_new_password"
                    required
                    style="<?= !empty($errors['confirm_new_password']) ? 'border: 2px solid red;' : '' ?>">
                <button onclick="show_confirm_password()" class="eye" type="button" id="eye2"></button>
            </div>
            <?php if (!empty($errors['confirm_new_password'])): ?>
                <div style="color:red; padding-bottom:15px;" class="error"><?= $errors['confirm_new_password'] ?></div>
            <?php endif; ?>

            <div class="form_btns">
                <button type="submit">Save Changes</button>
                <button type="button" id="pwBackBtn">Back</button>
            </div>
        </form>

    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const passwordBtn = document.getElementById("passwordBtn");
        const pwBackBtn = document.getElementById("pwBackBtn");
        const editPwDisplay = document.querySelector(".editPwDisplay");

        passwordBtn.addEventListener("click", (e) => {
            e.preventDefault();
            editPwDisplay.classList.add("active");
            document.body.style.overflow = "hidden";
        });

        pwBackBtn.addEventListener("click", () => {
            editPwDisplay.classList.remove("active");
            document.body.style.overflow = "auto";
        });
    });
</script>