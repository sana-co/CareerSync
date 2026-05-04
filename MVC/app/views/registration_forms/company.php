<script>
    function show_password() {
        console.log(document.getElementById("pass").type);
        var x = document.getElementById("pass");
        if (x.type === "password") {
            x.type = "text";
            document.getElementById("eye1").style.backgroundImage = "url(<?= ROOT ?>assets/svg_icons/eye_close.svg)";
        } else {
            x.type = "password";
            document.getElementById("eye1").style.backgroundImage = "url(<?= ROOT ?>assets/svg_icons/eye_open.svg)";

        }
    }

    function show_confirm_password() {
        console.log(document.getElementById("confirm_pass").type);
        var x = document.getElementById("confirm_pass");
        if (x.type === "password") {
            x.type = "text";
            document.getElementById("eye2").style.backgroundImage = "url(<?= ROOT ?>assets/svg_icons/eye_close.svg)";
        } else {
            x.type = "password";
            document.getElementById("eye2").style.backgroundImage = "url(<?= ROOT ?>assets/svg_icons/eye_open.svg)";

        }
    }
</script>
<h1>Register as a Company</h1>
<form method="POST" enctype="multipart/form-data" onsubmit="return handleSubmit(this)">
    <div class="input-field">
        <label for="companyName"><span style="color:red;">*</span> Company Name</label>
        <input
            type="text"
            placeholder="Company name"
            name="companyName"
            required
            value="<?= isset($_POST['companyName']) ? htmlspecialchars($_POST['companyName']) : '' ?>">
    </div>

    <div class="input-field">
        <label for="email"><span style="color:red;">*</span> Company Email</label>
        <input
            type="email"
            placeholder="Company email"
            name="email"
            required
            value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
            style="<?= !empty($errors['email']) ? 'border: 2px solid red;' : '' ?>">
    </div>
    <?php if (!empty($errors['email'])): ?>
        <div style="color:red;" class="error"><?= $errors['email'] ?></div>
    <?php endif; ?>

    <div class=input-field>
        <label for="contactNo"><span style="color:red;">*</span> Company Contact Number</label>
        <input
            type="tel"
            placeholder="Contact number ex: 071 xxx xxxx"
            name="contactNo"
            pattern="[0-9]{10}"
            required
            value="<?= isset($_POST['hr_email']) ? htmlspecialchars($_POST['contactNo']) : '' ?>">

    </div>

    <div class="input-field">
        <label for="hr_firstName"><span style="color:red;">*</span> HR Manager First Name</label>
        <input
            type="text"
            placeholder="First name of HR Manager"
            name="hr_firstName"
            required
            value="<?= isset($_POST['hr_firstName']) ? htmlspecialchars($_POST['hr_firstName']) : '' ?>">
    </div>

    <div class="input-field">
        <label for="hr_lastName"><span style="color:red;">*</span> HR Manager Last Name</label>
        <input
            type="text"
            placeholder="Last name of HR Manager"
            name="hr_lastName"
            required
            value="<?= isset($_POST['hr_lastName']) ? htmlspecialchars($_POST['hr_lastName']) : '' ?>">
    </div>

    <div class="input-field">
        <label for="hr_email"><span style="color:red;">*</span> HR Contact Email</label>
        <input
            type="email"
            placeholder="HR contact email"
            name="hr_email"
            required
            value="<?= isset($_POST['hr_email']) ? htmlspecialchars($_POST['hr_email']) : '' ?>">
    </div>

    <div class="input-field">
        <label for="hr_contactNo"><span style="color:red;">*</span> HR Contact Number</label>
        <input
            type="tel"
            placeholder="HR contact number ex: 0718888888"
            name="hr_contactNo"
            pattern="[0-9]{10}"
            required
            value="<?= isset($_POST['hr_email']) ? htmlspecialchars($_POST['hr_contactNo']) : '' ?>">
    </div>

    <div class="input-field">
        <label for="business_certificate"><span style="color:red;">*</span> Business Registration Certificate</label>
        <input
            type="file"
            name="business_certificate"
            required
            accept=".pdf, .jpg, .jpeg, .png"
            style="<?= !empty($errors['business_certificate']) ? 'border: 2px solid red;' : '' ?>">
    </div>
    <?php if (!empty($errors['business_certificate'])): ?>
        <div style="color:red;" class="error"><?= $errors['business_certificate'] ?></div>
    <?php endif; ?>

    <div class="input-field">
        <label for="company_photo_path"><span style="color:red;">*</span> Company Logo</label>
        <input
            type="file"
            name="company_photo_path"
            required
            accept=".pdf, .jpg, .jpeg, .png"
            style="<?= !empty($errors['company_photo_path']) ? 'border: 2px solid red;' : '' ?>">
    </div>
    <?php if (!empty($errors['company_photo_path'])): ?>
        <div style="color:red;" class="error"><?= $errors['company_photo_path'] ?></div>
    <?php endif; ?>

    <div class="input-field">
        <label for="password"><span style="color:red;">*</span> Password</label>
        <input
            type="password"
            id="pass"
            placeholder="Password"
            name="password"
            required
            minlength="8"
            style="<?= !empty($errors['password']) ? 'border: 2px solid red;' : '' ?>">
        <button onclick="show_password()" class="eye" type="button" id="eye1"></button>
    </div>

    <div class="input-field">
        <label for="confirm_password"><span style="color:red;">*</span> Re-enter Password</label>
        <input
            type="password"
            id="confirm_pass"
            placeholder="Confirm Password"
            name="confirm_password"
            required
            minlength="8"
            style="<?= !empty($errors['confirm_password']) ? 'border: 2px solid red;' : '' ?>">
        <button onclick="show_confirm_password()" class="eye" type="button" id="eye2"></button>
    </div>
    <?php if (!empty($errors['confirm_password'])): ?>
        <div style="color:red; padding-bottom:15px;" class="error"><?= $errors['confirm_password'] ?></div>
    <?php endif; ?>

    <button type="submit">Register</button>
</form>
