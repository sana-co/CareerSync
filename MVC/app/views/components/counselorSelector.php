<link rel="stylesheet" href="<?= ROOT ?>assets/css/dashboard/counselorSelector.css">
<div class="selector_bg">
    <div class="selector_window">
        <h1>Select a Counselor</h1>
        <div class="scrollbox">
            <?php if (!empty($data['counselors'])): ?>
                <?php foreach ($data['counselors'] as $counsleor): ?>
                    <div class="listItem">
                        <div class="itemContent">
                            <div class="title"><?= htmlspecialchars($counsleor->firstName) ?> <?= htmlspecialchars($counsleor->lastName) ?></div>
                            <img src="<?= ROOT . htmlspecialchars($counsleor->counselor_photo_path) ?>" alt="Counselor photo" class="counselor_photo">
                            <div class="description"></div>
                            <a href="<?= ROOT . htmlspecialchars($counsleor->certificate_path) ?>" class="interview-cvBtn" target="_blank">View Certificate</a>
                            <form method="POST">
                                <input type="hidden" name="action" value="send_meeting_request">
                                <input type="hidden" name="counselor_id" value="<?= $counsleor->user_id ?>">
                                <button type="submit" class="request_meeting_btn" >Send Meeting Request</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="itemsEmpty">No Counselors Available</p>
            <?php endif; ?>
        </div>
        <button id="counselor_selector_backBtn">Back</button>
    </div>
</div>