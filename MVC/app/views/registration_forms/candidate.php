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
<h1>Register as a Candidate</h1>
<form method="POST" enctype="multipart/form-data" onsubmit="return handleSubmit(this)">
    <div class="input-field">
        <label for="firstName"> <span style="color:red;">*</span> First Name</label>
        <input
            type="text"
            placeholder="First Name"
            name="firstName"
            required
            value="<?= isset($_POST['firstName']) ? htmlspecialchars($_POST['firstName']) : '' ?>">
    </div>

    <div class="input-field">
        <label for="lastName"> <span style="color:red;">*</span> Last Name</label>
        <input
            type="text"
            placeholder="Last Name"
            name="lastName"
            required
            value="<?= isset($_POST['lastName']) ? htmlspecialchars($_POST['lastName']) : '' ?>">
    </div>

    <div class="input-field">
        <label for="email"><span style="color:red;">*</span> Email Address</label>
        <input
            type="email"
            placeholder="Email"
            name="email"
            required
            value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
            style="<?= !empty($errors['email']) ? 'border: 2px solid red;' : '' ?>">
    </div>
    <?php if (!empty($errors['email'])): ?>
        <div style="color:red;" class="error"><?= $errors['email'] ?></div>
    <?php endif; ?>

    <div class="input-field">
        <label for="dob"> <span style="color:red;">*</span> Date of Birth</label>
        <input
            type="date"
            id="dob"
            placeholder="Date of Birth"
            name="dob"
            required
            value="<?= isset($_POST['dob']) ? htmlspecialchars($_POST['dob']) : '' ?>"
            style="<?= !empty($errors['dob']) ? 'border: 2px solid red;' : '' ?>">
    </div>

    <!-- <script>
            const today = new Date();
            today.setFullYear(today.getFullYear()-18);
            const maxDate = today.toISOString().split('T')[0];
            document.getElementById("dob").max = maxDate;
    </script> -->

    <?php if (!empty($errors['dob'])): ?>
        <div style="color:red;" class="error"><?= $errors['dob'] ?></div>
    <?php endif; ?>

    <div class="input-field">
        <label for="address"><span style="color:red;">*</span> Residential Address</label>
        <input
            type="text"
            placeholder="Enter your full Address here "
            name="address"
            required
            value="<?= isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '' ?>">
    </div>

    <div class="input-field">
        <label for="candidate_photo_path"><span style="color:red;">*</span> Insert a photo of yourself</label>
        <input
            type="file"
            name="candidate_photo_path"
            required
            accept=".pdf, .jpg, .jpeg, .png"
            style="<?= !empty($errors['candidate_photo_path']) ? 'border: 2px solid red;' : '' ?>">
    </div>
    <?php if (!empty($errors['candidate_photo_path'])): ?>
        <div style="color:red;" class="error"><?= $errors['candidate_photo_path'] ?></div>
    <?php endif; ?>

    <div class="input-field">
        <label for="contactNo"> <span style="color:red;">*</span> Contact Number</label>
        <input
            type="tel"
            placeholder="Contact Number:07xxxxxxxx"
            name="contactNo"
            pattern="[0-9]{10}"
            required
            value="<?= isset($_POST['contactNo']) ? htmlspecialchars($_POST['contactNo']) : '' ?>">
    </div>

    <div class="input-field">
        <label for="password"> <span style="color:red;">*</span> Password</label>
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
        <label for="confirm_password"> <span style="color:red;">*</span> Re-enter Password</label>
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
