<link rel="stylesheet" href="<?= ROOT ?>assets/css/dashboard/counselorDash.css">

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
        <?php if (!empty($messages ?? [])): ?>
            <ul class="message_list" data-section="messages">
                <?php foreach ($messages as $msg): ?>
                    <li class="message">
                        <form method="POST" class="message-item-form" onsubmit="return confirm('Delete this message?');">
                            <input type="hidden" name="action" value="delete_message">
                            <input type="hidden" name="message_id" value="<?= htmlspecialchars($msg->id) ?>">
                            <button type="submit" class="message-item-btn">
                                <div class="msg-content"><?= htmlspecialchars($msg->content) ?></div>
                                <span class="msg-time"><?= htmlspecialchars(date('M d, Y', strtotime($msg->created_at))) ?></span>
                            </button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class="message_empty_state" data-section="empty">
                <div class="envelope">
                    <svg width="80" height="60" viewBox="0 0 24 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 3.5C1 2.119 2.119 1 3.5 1h17C21.881 1 23 2.119 23 3.5v11c0 1.381-1.119 2.5-2.5 2.5h-17C2.119 17 1 15.881 1 14.5v-11z" stroke="rgba(255,255,255,0.9)" stroke-width="0.8" />
                        <path d="M2 3.5L12 10l10-6.5" stroke="rgba(255,255,255,0.9)" stroke-width="0.8" />
                    </svg>
                </div>
                <h2>All caught up!</h2>
                <p>New messages will appear here</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="decor-star" aria-hidden="true"></div>
</div>

<?php
include("C:/xampp/htdocs/CareerSync/MVC/app/views/components/changePassword.php");
include("C:/xampp/htdocs/CareerSync/MVC/app/views/profiles/counselorProfile.php");
?>

<h1 class="dashboard_tag">Welcome back <?php echo $counselorTable->firstName; ?> !</h1>

<?php
$requests = $data['request'] ?? [];
if (!is_array($requests)) {
    $requests = [];
}

$confirmedConsultation = $data['confirmedConsultation'] ?? [];
if (!is_array($confirmedConsultation)) {
    $confirmedConsultation = [];
}

$assignedStudents = array_filter($requests, fn($req) => is_object($req) ? (($req->counselor_acceptance ?? '') === 'accepted') : false);
$pendingApprovals = array_filter($requests, fn($req) => is_object($req) ? (($req->counselor_acceptance ?? '') === 'pending') : false);
?>
<div class="counting_boxes">
    <div class="box_segment">
        Assigned Students:<br>
        <h1><?= count($assignedStudents) ?></h1>
    </div>
    <div class="box_segment">
        Scheduled Sessions: <br>
        <h1><?= count($confirmedConsultation) ?></h1>
    </div>
    <div class="box_segment">
        Pending Approvals: <br>
        <h1><?= count($pendingApprovals) ?></h1>
    </div>
    <div class="box_segment">
        Messages: <br>
        <h1><?= $data['unreadMsgCount'] ?? 0 ?></h1>
    </div>
</div>

<?php if (!$data['isRealCounselor']) { ?>
    <div class="unverified_message">
        <p>Your Account is not verified by the administrator. Contact the administrator to gain access to account</p>
    </div>
<?php } ?>

<?php
include("C:/xampp/htdocs/CareerSync/MVC/app/views/components/counselorSideSchdeuler.php");
?>

<div class="content_section">
    <div class="meeting-requests">
        <h1>Meeting requests</h1>
        <div class="scrollBox">
            <?php
            $pendingRequests = array_filter($data['request'] ?: [], function ($req) {
                return $req->counselor_acceptance === "pending";
            });
            ?>
            <?php if (!empty($pendingRequests)): ?>
                <?php foreach ($pendingRequests as $req): ?>
                    <div class="request-card">
                        <img src="<?= ROOT . htmlspecialchars($req->candidate_photo_path) ?>" alt="candidate photo" class="candidate_photo">
                        <div class="candidate-name"><?= htmlspecialchars($req->candidate_firstName . " " . $req->candidate_lastName) ?></div>
                        <button class="schedule-btn" data-candidate="<?= $req->candidate_id ?>">Schedule Meeting</button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class='itemsEmpty'>No Meeting Requests Received Yet </p>
            <?php endif; ?>

        </div>
    </div>
</div>

<div class="interview-section">
    <h3>Upcoming Counselor Meetings</h3>
    <div class="interview-scrollbox">
        <?php if (!empty($data['confirmedConsultation'])): ?>
            <?php foreach ($data['confirmedConsultation'] as $cm): ?>
                <div class="interview-item">
                    <div class="interview-row">
                        <span class="interview-label">Candidate:</span>
                        <span class="interview-value"><?= htmlspecialchars($cm->candidate_first_name)." ".htmlspecialchars($cm->candidate_last_name) ?></span>
                    </div>
                    <div class="interview-row">
                        <span class="interview-label">Consultation Date and Time:</span>
                        <span class="interview-value"><?= htmlspecialchars($cm->slot_datetime) ?></span>
                    </div>
                    <div class="interview-row">
                        <span class="interview-label">Method:</span>
                        <span class="interview-value"><?= htmlspecialchars(ucfirst($cm->mode)) ?></span>
                    </div>
                    <div class="interview-row">
                        <span class="interview-label">Address:</span>
                        <a href="<?= htmlspecialchars($cm->address_link) ?>" target="_blank" class="interview-value"><?= htmlspecialchars($cm->address_link) ?></a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="itemsEmpty">No Upcoming Consultations</p>
        <?php endif; ?>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
    const schedulerBg = document.querySelector(".popup-overlay");
    const backBtn = document.getElementById("schedulerBackBtn");
    const openBtns = document.querySelectorAll(".schedule-btn");
    const candidateInput = document.getElementById("schedulerCandidateId");

    openBtns.forEach(btn => {
        btn.addEventListener("click", () => {
            const candidateId = btn.dataset.candidate;

            candidateInput.value = candidateId;

            schedulerBg.classList.add("active");
        });
    });

    if (backBtn) {
        backBtn.addEventListener("click", (e) => {
            e.preventDefault();
            schedulerBg.classList.remove("active");
        });
    }
});

</script>