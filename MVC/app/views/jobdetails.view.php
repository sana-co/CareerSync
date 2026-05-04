<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>assets/css/common.css">
    <link rel="stylesheet" href="<?= ROOT ?>assets/css/jobdetails.css">
</head>

<body>
    <div class="page-wrapper">
        <?php include("components/navbar.php"); ?>
        <div class='page-content'>
            <div class="container">
                <div class="box left">
                    <div class="heading_content">
                        <div class="job_heading">
                            <img src="<?= ROOT ?>assets/svg_icons/backBtn.svg" alt="back button" class="backicon" onclick="history.back()">
                            <h1 class="job_title"><?= $data['job']->posTitle ?> | <?= $data['job']->city ?></h1>
                        </div>
                        <h3 class="company_name"><?= $data['job']->companyName ?></h3>
                    </div>
                    <h3 class="jdTitle"><?= $data['job']->posTitle ?></h3><br>
                    <p class="job_description"><?= $data['job']->jobDescription ?></p>

                    <p class="requirementsHead">Requirements : </p>
                    <p class="ExperienceLevel">Experience Level: <?= $data['job']->exp_level ?></p>
                    <p class="ExperienceYears">Years of Experience: <?= $data['job']->yearsOfExp ?> Years</p>
                    <p class="requirements"><?= $data['job']->required_skills ?></p>

                </div>

                <div class="box right">
                    <div class="jobinfo_box">
                        <img class="company_logo" src="<?= ROOT . $data['job']->company_photo_path ?>" alt="Company Logo">
                        <h4><?= $data['job']->posTitle ?> | <?= $data['job']->city ?></h4>
                        <h5><?= $data['job']->companyName ?></h5><br>
                        <?php
                        $deadlineDisplay = 'N/A';
                        if (!empty($data['job']->deadline)) {
                            try {
                                $today = new DateTime('today');
                                $deadline = new DateTime($data['job']->deadline);
                                if ($deadline < $today) {
                                    $deadlineDisplay = "Closed";
                                } else {
                                    $diff = $today->diff($deadline);
                                    $days = (int)$diff->format("%a");
                                    if ($days === 0) {
                                        $deadlineDisplay = "Today";
                                    } elseif ($days === 1) {
                                        $deadlineDisplay = "1 day left";
                                    } else {
                                        $deadlineDisplay = $days . " days left";
                                    }
                                }
                            } catch (Exception $e) {
                                $deadlineDisplay = htmlspecialchars($data['job']->deadline);
                            }
                        }
                        ?>
                        <p class="short_details"><img class="icon" src="<?= ROOT ?>assets/svg_icons/location.svg"><?= $data['job']->city ?></p>
                        <p class="short_details"><img class="icon" src="<?= ROOT ?>assets/svg_icons/clock.svg"><?= $deadlineDisplay ?></p>
                        <p class="short_details"><img class="icon" src="<?= ROOT ?>assets/svg_icons/briefcase.svg"><?= $data['job']->posType ?></p>
                        <hr><br>

                        <div class="job-meta">
                            <table>
                                <tr>
                                    <td>Work Mode</td>
                                    <td><strong><?= $data['job']->workMode ?></strong></td>
                                </tr>

                                <tr>
                                    <td>No. of Vacancies</td>
                                    <td><strong><?= $data['job']->vacancies ?></strong></td>
                                </tr>

                                <tr>
                                    <td>Education</td>
                                    <td><strong><?= $data['job']->qualifications ?></strong></td>
                                </tr>
                                <tr>
                                    <td>Experience</td>
                                    <td><strong><?= $data['job']->yearsOfExp ?> Years</strong></td>
                                </tr>
                                <tr>
                                    <td>Salary Range</td>
                                    <td><strong><?= $data['job']->salaryDetails ?></strong></td>
                                </tr>
                            </table>
                            <hr><br><br>
                            <?php if (!isset($_SESSION['USER']) || (isset($_SESSION['USER']) && $_SESSION['USER']->role === 'candidate')): ?>
                                <div>
                                    <button class="apply_job" id="ApplyBtn">Apply</button><br>
                                    <button class="save_job"
                                        id="BookmarkBtn"
                                        data-job-id="<?= $data['job']->job_id ?>">
                                        <?= $data['bm_status'] === null ? 'Bookmark' : 'Remove Bookmark' ?>
                                    </button>
                                </div>
                            <?php endif; ?>
                            <?php if (isset($_SESSION['USER']) && $_SESSION['USER']->role === 'company' && $data['job']->company_id == $_SESSION['USER']->user_id) : ?>
                                <div>
                                    <button class="edit_job" id="editJobBtn">Edit Job Post</button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php include("components/cvDropWindow.php"); ?>
            </div>
        </div>
        <?php include("components/footer.php"); ?>
    </div>
    <?php if (isset($_SESSION['USER']) && $_SESSION['USER']->role === 'company') : ?>
        <?php include("components/editJobPostWindow.php"); ?>
    <?php endif; ?>
</body>

<script>
    let userSession = <?= isset($_SESSION['USER']) ? json_encode($_SESSION['USER']->role) : 'null' ?>;
    let alreadyApplied = <?= json_encode($data['alreadyApplied']) ?>;


    document.addEventListener("DOMContentLoaded", () => {
        const ApplyBtn = document.getElementById("ApplyBtn");
        const backBtn = document.getElementById("backBtn");
        const cvdw_pageCover = document.querySelector(".cvdw_pageCover");

        //if (!ApplyBtn || !backBtn) return;
        //ApplyBtn.onclick = null;

        ApplyBtn.addEventListener("click", (e) => {
            e.preventDefault();

            if (!userSession) {
                alert("You must log in to apply for a position");
                return;
            }

            /*if (userSession !== 'candidate') {
                alert("You must register as a candidate in order to apply for a position");
                return;
            }*/

            if (alreadyApplied) {
                alert("You have already applied to this job.");
                return;
            }

            cvdw_pageCover.classList.add("active");
            document.body.style.overflow = "hidden";
        });

        backBtn.addEventListener("click", () => {
            cvdw_pageCover.classList.remove("active");
            document.body.style.overflow = "auto";
        });
    });

    const editBtn = document.getElementById("editJobBtn");
    const Model = document.getElementById("editJobModel");

    if (editBtn && Model) {
        editBtn.addEventListener("click", () => {
            Model.style.display = "flex";
        });

        const backModelBtn = Model.querySelector("button[type='button']");
        backModelBtn.addEventListener("click", () => {
            Model.style.display = "none";
        });

        window.addEventListener("click", (event) => {
            if (event.target === Model) {
                Model.style.display = "none";
            }
        });
    }
</script>
<script>
    document.addEventListener("DOMContentLoaded", () => {

        const bookmarkBtn = document.getElementById("BookmarkBtn");
        if (!bookmarkBtn) return;

        bookmarkBtn.addEventListener("click", () => {

            if (!userSession) {
                alert("You must log in to bookmark jobs.");
                return;
            }

            const jobId = bookmarkBtn.dataset.jobId;

            fetch("", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: `action=toggle_bookmark&job_id=${jobId}`
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === "added") {
                        bookmarkBtn.textContent = "Remove Bookmark";
                    } else if (data.status === "removed") {
                        bookmarkBtn.textContent = "Bookmark";
                    }
                })
                .catch(err => console.error(err));
        });
    });
</script>

</html>