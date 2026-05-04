<link rel="stylesheet" href="<?= ROOT ?>assets/css/dashboard/validatorDash.css">

<div class="settings_menu" id="settings_menu">
    <ul class="settings_links">
        <li><a href=""><button class="setting_btn" id="profileBtn">Your Profile</button></a></li>
        <li><a href=""><button class="setting_btn" id="passwordBtn">Change Password</button></a></li>
    </ul>
</div>

<div class="message_menu" id="message_menu" role="region" aria-label="Messages" aria-live="polite">
    <div class="msg-header">
        <div class="msg-title">Inbox</div>
        <form method="POST" class="clear-messages-form" onsubmit="return confirm('Clear all messages?');">
            <input type="hidden" name="action" value="clear_messages">
            <button type="submit" class="clear-messages-btn">Clear All</button>
        </form>
        <div class="msg-tabs" role="tablist">
            <button class="msg-tab active" data-tab="messages" type="button">Messages</button>
            <button class="msg-tab" data-tab="alerts" type="button">Alerts</button>
        </div>
    </div>

    <div class="message_body">
        <div class="message_empty_state" data-section="empty" style="display: none;">
            <div class="envelope">
                <svg width="80" height="60" viewBox="0 0 24 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 3.5C1 2.119 2.119 1 3.5 1h17C21.881 1 23 2.119 23 3.5v11c0 1.381-1.119 2.5-2.5 2.5h-17C2.119 17 1 15.881 1 14.5v-11z" stroke="rgba(255,255,255,0.9)" stroke-width="0.8" />
                    <path d="M2 3.5L12 10l10-6.5" stroke="rgba(255,255,255,0.9)" stroke-width="0.8" />
                </svg>
            </div>
            <h2>All caught up!</h2>
            <p>New messages will appear here</p>
        </div>
    </div>

    <div class="decor-star" aria-hidden="true"></div>
</div>

<?php
include("C:/xampp/htdocs/CareerSync/MVC/app/views/components/changePassword.php");
include("C:/xampp/htdocs/CareerSync/MVC/app/views/profiles/validatorProfile.php");
?>

<h1 class="dashboard_tag">Welcome back <?php echo $validatorTable->firstName; ?> !</h1>
<?php
$applications = $data['applications'] ?? [];
if (!is_array($applications)) {
    $applications = [];
}

$pendingApprovals = array_filter($applications, fn($app) => is_object($app) ? (($app->validator_approval ?? '') === 'pending') : false);
?>
<div class="counting_boxes">
    <div class="box_segment">
        Companies to validate:<br>
        <h1><?= count($pendingApprovals) ?></h1>
    </div>
    <div class="box_segment">
        Candidates to validate: <br>
        <h1><?= count($pendingApprovals) ?></h1>
    </div>
    <div class="box_segment">
        Unread messages: <br>
        <h1><?= $data['unreadMsgCount'] ?? 0 ?></h1>
    </div>
</div>
<?php if (!$data['isRealValidator']) { ?>
    <div class="unverified_message">
        <p>Your Account is not verified by the administrator. Contact the administrator to gain access to account</p>
    </div>
<?php } ?>

<div class="sbContainer">
    <h3> Pending Company registration requests:</h3>
    <div class="scrollBox">
        <?php $companyDetails = $data['companyDetails']; ?>
        <?php if (!empty($companyDetails)): ?>
            <?php foreach ($companyDetails as $cd): ?>
                <div class="companylistItem">
                    <img src="<?= ROOT . htmlspecialchars($cd->company_photo_path) ?>" alt="company photo" class="photo">
                    <div class="itemContent">
                        <div class="title"></div>
                        <div class="details"><label>Company Name: </label><?= htmlspecialchars($cd->companyName) ?></div>
                        <div class="details"><label>User ID: </label><?= htmlspecialchars($cd->user_id); ?></div>
                        <div class="details"><label>HR manager Name: </label><?= htmlspecialchars($cd->hr_firstName . ' ' . $cd->hr_lastName); ?></div>
                        <div class="details"><label>Email: </label><?= htmlspecialchars($cd->email); ?></div>
                        <div class="details"><label>Comapny Contact No: </label><?= htmlspecialchars($cd->contactNo); ?></div>
                        <div class="details"><label>HR Contact No: </label><?= htmlspecialchars($cd->hr_contactNo); ?></div>
                        <div class="details"><label>Business Certificate: </label><a target="_blank" href="<?= $cd->business_certificate ?>">click here to view</a></div>
                        <form class="companyValidationForm">
                            <input type="hidden" name="action" value="validateCompany">
                            <input type="hidden" name="company_id" value="<?= $cd->user_id ?>">
                            <button type="button" data-status="approve" class="acceptBtn">Approve</button>
                            <button type="button" data-status="reject" class="denyBtn">Reject</button>
                        </form>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class='itemsEmpty'>No pending applications available.</p>
        <?php endif; ?>
    </div>
</div>

<div class="sbContainer">
    <h3> Pending Candidate Application:</h3>
    <div class="scrollBox">
        <?php
        // filter pending applications
        $pendingCvs = array_filter($data['applications'], function ($cvs) {
            return $cvs->validator_approval === 'pending';
        });
        ?>
        <?php if (!empty($pendingCvs)): ?>
            <?php foreach ($pendingCvs as $cvs): ?>
                <div class="listItem">
                    <div class="itemContent">
                        <div class="title">
                            <?= htmlspecialchars($cvs->firstName . ' ' . $cvs->lastName) ?>
                        </div>
                        <div class="description">
                            Company: <?= htmlspecialchars($cvs->companyName) ?><br>
                            CV: <a href="<?= ROOT . $cvs->cv_file_path ?>" target="_blank">View CV</a>
                        </div>
                    </div>
                    <form class="cvValidationForm">
                        <input type="hidden" name="action" value="validateCV">
                        <input type="hidden" name="cv_id" value="<?= $cvs->cv_id ?>">
                        <button type="button" data-status="approve" class="approveBtn">Approve</button>
                        <button type="button" data-status="reject" class="rejectBtn">Reject</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class='itemsEmpty'>No pending applications available.</p>
        <?php endif; ?>
    </div>
</div>

<script>
    document.addEventListener("click", function(e) {

        if (e.target.closest(".cvValidationForm button")) {
            const btn = e.target.closest("button");
            const form = btn.closest(".cvValidationForm");

            const formData = new FormData(form);
            formData.append(btn.dataset.status, btn.dataset.status);

            sendAjax(formData, form);
        }

        if (e.target.closest(".companyValidationForm button")) {
            const btn = e.target.closest("button");
            const form = btn.closest(".companyValidationForm");

            const formData = new FormData(form);
            formData.append(btn.dataset.status, btn.dataset.status);

            sendAjax(formData, form);
        }
    });

    function sendAjax(formData, form) {
        fetch("<?= ROOT ?>dashboard", {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const item = form.closest(".listItem, .companylistItem");
                    const scrollBox = item.closest(".scrollBox");

                    item.remove();

                    if (!scrollBox.querySelector(".listItem, .companylistItem")) {
                        if (!scrollBox.querySelector(".itemsEmpty")) {
                            scrollBox.insertAdjacentHTML(
                                "beforeend",
                                "<p class='itemsEmpty'>No pending applications available.</p>"
                            );
                        }
                    }
                } else {
                    alert(data.message || "Action failed");
                }
            })
            .catch(err => console.error(err));
    }
</script>