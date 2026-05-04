<link rel="stylesheet" href="<?= ROOT ?>assets/css/dashboard/candidateProfileDisplay.css">
<div class="profile_display">
    <div class="pd_content">
        <h1>User Profile</h1>
        <div class="user_data">
            <div class="profile_picture"><img src="<?= ROOT . $candidateTable->candidate_photo_path ?>" alt="candidate photo"></div>
            <div class="info_segment"><label>First Name</label>
                <div class="value"><?php echo $candidateTable->firstName; ?></div>
            </div>
            <div class="info_segment"><label>Last Name</label>
                <div class="value"><?php echo $candidateTable->lastName; ?></div>
            </div>
            <div class="info_segment"><label>Contact Number</label>
                <div class="value"><?php echo $candidateTable->contactNo; ?></div>
            </div>
            <div class="info_segment"><label>Email Address</label>
                <div class="value"><?php echo $userTable->email; ?></div>
            </div>
        </div>
        <button class="backBtn" id="backBtn">Back</button>
        <button class="edit_profileBtn" id="editBtn">Edit Profile</button>
    </div>

    <div class="edit_profile_display">
        <div class="editWindow">
            <h1>Edit profile</h1>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="profile_change">
                <div class="input-field">
                    <label for="firstName">First Name</label>
                    <input
                        type="text"
                        placeholder="First Name"
                        name="firstName"
                        value="<?= $candidateTable->firstName ?>">
                </div>

                <div class="input-field">
                    <label for="lastName">Last Name</label>
                    <input
                        type="text"
                        placeholder="Last Name"
                        name="lastName"
                        value="<?= $candidateTable->lastName ?>">
                </div>

                <div class="input-field">
                    <label for="candidate_photo_path">Profile Picture</label><br>
                    <?php if (!empty($candidateTable->candidate_photo_path)): ?>
                        <img src="<?= $candidateTable->candidate_photo_path ?>" alt="Current Profile Picture">
                    <?php else: ?>
                        <img src="assets/uploads/defaultPhoto.jpg" alt="Default Profile Picture">
                    <?php endif; ?>

                    <br>
                    <input
                        type="file"
                        name="candidate_photo_path"
                        accept=".jpg, .jpeg, .png"
                        style="<?= !empty($errors['candidate_photo_path']) ? 'border: 2px solid red;' : '' ?>">
                </div>
                <?php if (!empty($errors['candidate_photo_path'])): ?>
                    <div style="color:red;" class="error"><?= $errors['candidate_photo_path'] ?></div>
                <?php endif; ?>

                <div class="input-field">
                    <label for="contactNo">Contact Number</label>
                    <input
                        type="tel"
                        placeholder="Contact Number:07xxxxxxxx"
                        name="contactNo"
                        pattern="[0-9]{10}"
                        value="<?= $candidateTable->contactNo ?>">
                </div>

                <div class="input-field">
                    <label for="confirm_password">Pasword</label>
                    <input
                        type="password"
                        id="confirm_pass_in_profile"
                        placeholder="Enter Password to confirm"
                        name="confirm_password"
                        required
                        style="<?= !empty($errors['confirm_password']) ? 'border: 2px solid red;' : '' ?>">
                    <button onclick="show_confirm_password_in_profile()" class="eye" type="button" id="eye3"></button>
                </div>
                <?php if (!empty($errors['confirm_password'])): ?>
                    <div style="color:red; padding-bottom:15px;" class="error"><?= $errors['confirm_password'] ?></div>
                <?php endif; ?>

                <div class="form_btns">
                    <button type="submit">Save Changes</button>
                    <button type="button" id="edit_backBtn">Back</button>
                </div>
            </form>

        </div>
    </div>
</div>