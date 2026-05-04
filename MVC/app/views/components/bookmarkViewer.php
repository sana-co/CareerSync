<link rel="stylesheet" href="<?= ROOT ?>assets/css/dashboard/bookmarkViewer.css">

<div class="bmDisplay_bg" id="bmDisplayBg">
    <div class="bm_display_window">
        <h2 class="bm_title">My Bookmarks</h2>
        <div class="bm_scrollBox">
            <div class="bm_list">
                <?php if (!empty($data['myBM'])): ?>
                    <?php foreach ($data['myBM'] as $bm): ?>
                        <div class="bm_item">
                            <div class="bm_top">
                                <img class="company-logo" src="<?= ROOT . $bm->company_photo_path ?>" alt="Logo">
                                <div class="bm_info">
                                    <h4 class="bm_job"><?= htmlspecialchars($bm->posTitle) ?></h4>
                                    <p class="bm_company"><?= htmlspecialchars($bm->companyName) ?></p>
                                </div>
                            </div>
                            <div class="bm_actions">
                                <a href="<?= ROOT ?>jobdetails/<?= urlencode($bm->job_id) ?>"><button class="viewJobPost">View</button></a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="itemsEmpty">No bookmarks added yet</p>
                <?php endif; ?>
            </div>
        </div>
        <button class="bmBackBtn" id="bmBackBtn">Back</button>
    </div>
</div>
