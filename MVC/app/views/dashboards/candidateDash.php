<link rel="stylesheet" href="<?= ROOT ?>assets/css/dashboard/candidateDash.css">

<div class="settings_menu" id="settings_menu">
    <ul class="settings_links">
        <li><a href=""><button class="setting_btn" id="profileBtn">Your Profile</button></a></li>
        <li><a href=""><button class="setting_btn" id="passwordBtn">Change Password</button></a></li>
        <li><a href=""><button class="setting_btn" id="bookmarksBtn">Bookmarks</button></a></li>
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
include("C:/xampp/htdocs/CareerSync/MVC/app/views/profiles/candidateProfile.php");
include("C:/xampp/htdocs/CareerSync/MVC/app/views/components/candidateSideScheduler.php");
include("C:/xampp/htdocs/CareerSync/MVC/app/views/components/bookmarkViewer.php");
include("C:/xampp/htdocs/CareerSync/MVC/app/views/components/counselorSelector.php");
include("C:/xampp/htdocs/CareerSync/MVC/app/views/components/candidateConsultationScheduler.php");
?>

<h1 class="dashboard_tag">Welcome back <?php echo $candidateTable->firstName; ?> !</h1>
<?php
$allCVs = $data['cv'] ?? [];
if (!is_array($allCVs)) {
    $allCVs = [];
}

$messages = $data['messages'] ?? [];
if (!is_array($messages)) {
    $messages = [];
}

$pendingApps = array_filter($allCVs, fn($cv) => is_object($cv) ? (($cv->company_approval ?? '') === 'pending') : false);
$acceptedApps = array_filter($allCVs, fn($cv) => is_object($cv) ? (($cv->company_approval ?? '') === 'approved') : false);
$rejectedApps = array_filter($allCVs, fn($cv) => is_object($cv) ? ((($cv->company_approval ?? '') === 'rejected') || (($cv->validator_approval ?? '') === 'rejected')) : false);
$unreadMsgs = array_filter($messages, fn($msg) => is_object($msg) ? (!($msg->is_read ?? false)) : false);
?>
<div class="counting_boxes">
    <div class="box_segment">
        Pending applications:<br>
        <h1><?= count($pendingApps) ?></h1>
    </div>
    <div class="box_segment">
        Accepted applications: <br>
        <h1><?= count($acceptedApps) ?></h1>
    </div>
    <div class="box_segment">
        Rejected applications: <br>
        <h1><?= count($rejectedApps) ?></h1>
    </div>
    <div class="box_segment">
        Unread messages: <br>
        <h1><?= count($unreadMsgs) ?></h1>
    </div>
</div>

<div class="contact_counselor_section">
    <label>Unsure about your next step?<br> Reach out to one of our counselors for personalized guidance.</label>
    <button id="select_counselor">Contact a Counselor</button>
</div>
<div class="subscription_section">
    <div class="content_section">
        <div class='scrollBoxContainer subscriptionBox'>
            <div class="subscription_header">
                <h1>Subscribed Companies</h1>
                <span class="subscription_count"><?= count((array)($data['candidateSubscriptions'] ?? [])) ?></span>
            </div>
            <div class="scrollBox subscriptionScroll">
                <ul class="subscription_list">
                    <?php if (!empty($data['subscriptionCompanies'])): ?>
                        <?php foreach ($data['subscriptionCompanies'] as $companyRow): ?>
                            <li class="subscription_item">
                                <div class="subscription-company">
                                    <img class="subscription-logo" src="<?= ROOT . htmlspecialchars($companyRow->company_photo_path) ?>" alt="<?= htmlspecialchars($companyRow->companyName) ?> logo">
                                    <div class="subscription-meta">
                                        <h2><?= htmlspecialchars($companyRow->companyName) ?></h2>
                                        <span class="company_status_tag <?= htmlspecialchars($companyRow->validator_approval) ?> <?= htmlspecialchars($companyRow->payment_status) ?>">
                                            <?= htmlspecialchars(ucfirst($companyRow->validator_approval)) ?> / <?= htmlspecialchars(ucfirst($companyRow->payment_status)) ?>
                                        </span>
                                    </div>
                                </div>
                                <form method="POST" class="subscription_form">
                                    <input type="hidden" name="action" value="subscription_action">
                                    <input type="hidden" name="company_id" value="<?= htmlspecialchars($companyRow->user_id) ?>">
                                    <input type="hidden" name="subscription_action" value="<?= (int)$companyRow->is_subscribed ? 'unsubscribe' : 'subscribe' ?>">
                                    <button type="submit" class="<?= (int)$companyRow->is_subscribed ? 'unsubscribeBtn' : 'subscribeBtn' ?>">
                                        <?= (int)$companyRow->is_subscribed ? 'Unsubscribe' : 'Subscribe' ?>
                                    </button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class='itemsEmpty'>No companies available yet.</p>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="upcoming_section">
    <div class="content_section">
        <div class='scrollBoxContainer'>
            <h1>Sent Applications</h1>
            <div class="scrollBox">
                <ul class="applications job-applications">
                    <?php
                    $sent_cv = $data['cv'];
                    ?>
                    <?php if (!empty($sent_cv)): ?>
                        <?php foreach ($sent_cv as $cv): ?>
                            <?php
                            $matchingInterview = null;
                            $matchingSlots = [];

                            foreach ($data['interview'] as $iv) {
                                if ($iv['interviewData']->job_id == $cv->job_id) {
                                    $matchingInterview = $iv['interviewData'];
                                    $matchingSlots = $iv['slots'];
                                    break;
                                }
                            }
                            ?>

                            <li class="application_item"
                                data-id="<?= $matchingInterview->interview_id ?? '' ?>"
                                data-mode="<?= htmlspecialchars($matchingInterview->mode ?? '') ?>"
                                data-link="<?= htmlspecialchars($matchingInterview->address_link ?? '') ?>"
                                data-details="<?= htmlspecialchars($matchingInterview->extra_details ?? '') ?>"
                                data-slots='<?= json_encode($matchingSlots) ?>'>
                                <div class="application-title"><?= htmlspecialchars($cv->posTitle) ?></div>
                                <div class="application_state">
                                    <?php
                                    switch ($cv->company_approval) {
                                        case 'pending':
                                    ?>
                                            <span class="status pending">Pending</span>
                                        <?php
                                            break;
                                        case 'rejected':
                                        ?>
                                            <span class="status rejected">Rejected</span>
                                        <?php
                                            break;
                                        case 'approved':
                                        ?>
                                            <span class="status accepted">Accepted</span>
                                    <?php
                                            break;
                                    }
                                    ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class='itemsEmpty'>No CV's sent yet</p>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="content_section">
        <div class='scrollBoxContainer'>
            <h1>Sent Consultation Requests</h1>
            <div class="scrollBox">
                <ul class="applications consultation-list">
                    <?php if (!empty($data['consultation'])): ?>
                        <?php foreach ($data['consultation'] as $cons): ?>
                            <li class="application_item" data-request-id="<?= $cons->request_id ?>">
                                <div class="application-title"><?= htmlspecialchars($cons->firstName . $cons->lastName) ?></div>
                                <div class="application_state">
                                    <?php
                                    switch ($cons->counselor_acceptance) {
                                        case 'pending':
                                    ?>
                                            <span class="status pending">Pending</span>
                                        <?php
                                            break;
                                        case 'accepted':
                                        ?>
                                            <span class="status accepted">Accepted</span>
                                    <?php
                                            break;
                                    }
                                    ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class='itemsEmpty'>No Requests Sent</p>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="upcoming_section">
    <div class="interview-section">
        <h3>Upcoming Interviews</h3>
        <div class="interview-scrollbox">
            <?php if (!empty($data['confirmedInterview'])): ?>
                <?php foreach ($data['confirmedInterview'] as $iv): ?>
                    <div class="interview-item">
                        <div class="interview-row">
                            <span class="interview-label">Position:</span>
                            <span class="interview-value"><?= htmlspecialchars($iv->posTitle) ?></span>
                        </div>
                        <div class="interview-row">
                            <span class="interview-label">Company:</span>
                            <span class="interview-value"><?= htmlspecialchars($iv->companyName) ?></span>
                        </div>
                        <div class="interview-row">
                            <span class="interview-label">Interview Date:</span>
                            <span class="interview-value"><?= htmlspecialchars($iv->slot_datetime) ?></span>
                        </div>
                        <div class="interview-row">
                            <span class="interview-label">Method:</span>
                            <span class="interview-value"><?= htmlspecialchars(ucfirst($iv->mode)) ?></span>
                        </div>
                        <div class="interview-row">
                            <span class="interview-label">Address:</span>
                            <a href="<?= htmlspecialchars($iv->address_link) ?>" target="_blank" class="interview-value"><?= htmlspecialchars($iv->address_link) ?></a>
                        </div>
                        <div class="interview-row interview-cv">
                            <span class="interview-label">Candidate CV:</span>
                            <a href="<?= ROOT . htmlspecialchars($iv->cv_file_path) ?>" class="interview-cvBtn" target="_blank">View CV</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="itemsEmpty">No upcoming interviews</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="interview-section">
        <h3>Upcoming Counselor Meetings</h3>
        <div class="interview-scrollbox">
            <?php if (!empty($data['confirmedConsultation'])): ?>
                <?php foreach ($data['confirmedConsultation'] as $cm): ?>
                    <div class="interview-item">
                        <div class="interview-row">
                            <span class="interview-label">Counselor:</span>
                            <span class="interview-value"><?= htmlspecialchars($cm->counselor_first_name) . " " . htmlspecialchars($cm->counselor_last_name) ?></span>
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
                        <div class="interview-row">
                            <span class="interview-label">Extra Details:</span>
                            <span class="interview-value"><?= htmlspecialchars(ucfirst($cm->extra_details)) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="itemsEmpty">No Upcoming Consultations</p>
            <?php endif; ?>
        </div>
    </div>
</div>



<script>
    document.addEventListener("DOMContentLoaded", function() {

        const interviewBg = document.querySelector(".interview_scheduler_bg");
        const interviewBackBtn = document.getElementById("interviewSchedulerBackBtn");

        // Select only job application items
        const jobAppItems = document.querySelectorAll(".applications.job-applications .application_item");

        jobAppItems.forEach(item => {
            const status = item.querySelector(".status");
            if (status && status.classList.contains("accepted")) {
                // Open interview scheduler
                item.addEventListener("click", () => {
                    interviewBg.classList.add("active");

                    document.getElementById("modal_mode").textContent = item.dataset.mode;
                    document.getElementById("modal_link").textContent = item.dataset.link;
                    document.getElementById("modal_details").textContent = item.dataset.details;
                    document.getElementById("modal_interview_id").value = item.dataset.id;

                    const slots = JSON.parse(item.dataset.slots || "[]");
                    const select = document.getElementById("selected_date");

                    select.innerHTML = '<option disabled selected hidden>Select a date</option>';

                    slots.forEach(slot => {
                        const opt = document.createElement("option");
                        opt.value = slot.slot_datetime;
                        opt.textContent = new Date(slot.slot_datetime).toLocaleString();
                        select.appendChild(opt);
                    });
                });

            } else if (status && status.classList.contains("rejected")) {
                // Allow deletion of rejected job applications
                item.addEventListener("click", () => {
                    const confirmDelete = confirm("This application was rejected. Do you want to delete it?");
                    if (confirmDelete) item.remove();//need to delete fr
                });
            }
        });

        interviewBackBtn.addEventListener("click", () => {
            interviewBg.classList.remove("active");
        });

        const consultationBackBtn = document.getElementById("consultationSchedulerBackBtn");
        const consultationItems = document.querySelectorAll(".applications.consultation-list .application_item");
        const consultationBg = document.querySelector(".consultation_scheduler_bg");
        const consultationRequestInput = document.getElementById("consultationRequestId");

        if (consultationBackBtn && consultationBg) {
            consultationBackBtn.addEventListener("click", () => {
                consultationBg.classList.remove("active");
            });
        }

        consultationItems.forEach(item => {
            const status = item.querySelector(".status");

            if (status && status.classList.contains("accepted")) {
                item.addEventListener("click", () => {
                    const requestId = item.dataset.requestId;
                    window.location.href = "<?= ROOT ?>dashboard?request_id=" + requestId;
                });
            }
        });

        const selectCounselorBtn = document.getElementById("select_counselor");
        const counselorSelector = document.querySelector(".selector_bg");
        const counselorSelectBackBtn = document.getElementById("counselor_selector_backBtn");

        selectCounselorBtn.addEventListener("click", () => {
            counselorSelector.style.display = "flex";
        });

        counselorSelectBackBtn.addEventListener("click", () => {
            counselorSelector.style.display = "none";
        });

    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", () => {

        const bookmarksBtn = document.getElementById("bookmarksBtn");
        const bmBg = document.querySelector(".bmDisplay_bg");
        const bmBackBtn = document.getElementById("bmBackBtn");

        bookmarksBtn.addEventListener("click", (e) => {
            e.preventDefault();
            bmBg.classList.add("active");
        });

        bmBackBtn.addEventListener("click", () => {
            bmBg.classList.remove("active");
        });

    });
</script>
<?php if (isset($_GET['request_id'])): ?>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelector(".consultation_scheduler_bg").classList.add("active");
        });
    </script>
<?php endif; ?>