<link rel="stylesheet" href="<?= ROOT ?>assets/css/dashboard/companyProfileDisplay.css">
<div class="profile_display">
    <div class="pd_content">
        <h1>User Profile</h1>
        <div class="user_data">
            <div class="profile_picture"><img src="<?= $companyTable->company_photo_path ?>" alt="company photo"></div>
            <div class="info_segment"><label>Company Name</label>
                <div class="value"><?php echo $companyTable->companyName; ?></div>
            </div>
            <div class="info_segment"><label>Company Contact Number</label>
                <div class="value"><?php echo $companyTable->contactNo; ?></div>
            </div>
            <div class="info_segment"><label>Company Email</label>
                <div class="value"><?php echo $userTable->email; ?></div>
            </div>
            <div class="info_segment"><label>HR First Name</label>
                <div class="value"><?php echo $companyTable->hr_firstName; ?></div>
            </div>
            <div class="info_segment"><label> HR Last Name</label>
                <div class="value"><?php echo $companyTable->hr_lastName; ?></div>
            </div>
            <div class="info_segment"><label>HR Contact Number</label>
                <div class="value"><?php echo $companyTable->hr_contactNo; ?></div>
            </div>
            <div class="info_segment"><label>HR Email Address</label>
                <div class="value"><?php echo $companyTable->hr_email; ?></div>
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
                    <label for="companyName"> Company Name</label>
                    <input
                        type="text"
                        placeholder="Company Name"
                        name="companyName"
                        value="<?= $companyTable->companyName ?>">
                </div>

                <div class="input-field">
                    <label for="contactNo"> Company Contact Number</label>
                    <input
                        type="text"
                        placeholder="Contact number ex: 071 xxx xxxx"
                        name="contactNo"
                        value="<?= $companyTable->contactNo ?>">
                </div>

                <div class="input-field">
                    <label for="hr_firstName"> HR First Name</label>
                    <input
                        type="text"
                        placeholder="HR First Name"
                        name="hr_firstName"
                        value="<?= $companyTable->hr_firstName ?>">
                </div>

                <div class="input-field">
                    <label for="hr_lastName">HR Last Name</label>
                    <input
                        type="text"
                        placeholder="HR Last Name"
                        name="hr_lastName"
                        value="<?= $companyTable->hr_lastName ?>">
                </div>

                <div class="input-field">
                    <label for="hr_contactNo"> HR Contact Number</label>
                    <input
                        type="text"
                        placeholder="Contact number ex: 071 xxx xxxx"
                        name="hr_contactNo"
                        value="<?= $companyTable->hr_contactNo ?>">
                </div>

                <div class="input-field">
                    <label for="email">HR Email Address</label>
                    <input
                        type="email"
                        placeholder="HR Email Address"
                        name="hr_email"
                        value="<?= $companyTable->hr_email ?>"
                        style="<?= !empty($errors['hr_email']) ? 'border: 2px solid red;' : '' ?>">
                </div>
                <?php if (!empty($errors['hr_email'])): ?>
                    <div style="color:red;" class="error"><?= $errors['hr_email'] ?></div>
                <?php endif; ?>

                <div class="input-field">
                    <label for="company_photo_path">Profile Picture</label><br>
                    <?php if (!empty($companyTable->company_photo_path)): ?>
                        <img src="<?= $companyTable->company_photo_path ?>" alt="Current Profile Picture">
                    <?php else: ?>
                        <img src="assets/uploads/defaultPhoto.jpg" alt="Default Profile Picture">
                    <?php endif; ?>

                    <br>
                    <input
                        type="file"
                        name="company_photo_path"
                        accept=".jpg, .jpeg, .png"
                        style="<?= !empty($errors['company_photo_path']) ? 'border: 2px solid red;' : '' ?>">
                </div>
                <?php if (!empty($errors['company_photo_path'])): ?>
                    <div style="color:red;" class="error"><?= $errors['company_photo_path'] ?></div>
                <?php endif; ?>

                <div class="input-field">
                    <label for="business_certificate">Update Business Certificate</label><br>
                    <input
                        type="file"
                        name="business_certificate"
                        accept=".jpg, .jpeg, .png"
                        style="<?= !empty($errors['business_certificate']) ? 'border: 2px solid red;' : '' ?>">
                </div>
                <?php if (!empty($errors['business_certificate'])): ?>
                    <div style="color:red;" class="error"><?= $errors['business_certificate'] ?></div>
                <?php endif; ?>

                <div class="input-field">
                    <label for="confirm_password">Password</label>
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