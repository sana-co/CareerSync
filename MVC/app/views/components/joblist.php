<link rel="stylesheet" href="<?= ROOT ?>assets/css/joblist.css">
<section class="job-section">
    <div class="filtersContainer">
        <h2>Add Filters : </h2>
        <!-- Salary Range -->
        <div class="filterTypeBox">
            <label>Salary Range:</label>

            <div class="salary-inputs">
                <div class="salary-box">
                    <label for="minSalary">Min:</label>
                    <input type="number" id="minSalary" name="minSalary" placeholder="21000">
                </div>

                <div class="salary-box">
                    <label for="maxSalary">Max:</label>
                    <input type="number" id="maxSalary" name="maxSalary" placeholder="10000000">
                </div>
            </div>
        </div>

        <!-- Sort Dropdown -->
        <div class="filterTypeBox">
            <label for="sortBy">Sort by:</label>
            <select id="sortBy" name="sortBy" class="filter-dropdown">
                <option value="none">No filter</option>
                <option value="asc">Ascending order</option>
                <option value="desc">Descending order</option>
                <option value="highsal">Highest Salary</option>
                <option value="lowsal">Lowest Salary</option>
            </select>
        </div>

        <!-- City Dropdown -->
        <div class="filterTypeBox">
            <label for="city">City:</label>
            <select id="city" name="city" class="filter-dropdown">
                <option value="">All</option>
                <?php if (!empty($cities)): ?>
                    <?php foreach ($cities as $c): ?>
                        <option value="<?= htmlspecialchars($c->city) ?>">
                            <?= htmlspecialchars($c->city) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <!-- Work Mode Dropdown -->
        <div class="filterTypeBox">
            <label for="workMode">Work Mode:</label>
            <select id="workMode" name="workMode" class="filter-dropdown">
                <option value="">All</option>
                <option value="remote">Remote</option>
                <option value="onsite">On-site</option>
                <option value="hybrid">Hybrid</option>
            </select>
        </div>

        <!-- Job Type Dropdown -->
        <div class="filterTypeBox">
            <label for="jobType">Job Type:</label>
            <select id="jobType" name="jobType" class="filter-dropdown">
                <option value="">All</option>
                <option value="intern">Intern</option>
                <option value="fullTime">Full-Time</option>
                <option value="partTime">Part-Time</option>
                <option value="freelance">Freelance</option>
                <option value="contract">Contract</option>
            </select>
        </div>

        <!-- Experience Level Dropdown -->
        <div class="filterTypeBox">
            <label for="experience">Experience Level:</label>
            <select id="experience" name="experience" class="filter-dropdown">
                <option value="">All</option>
                <option value="entry">Entry</option>
                <option value="mid">Mid</option>
                <option value="senior">Senior</option>
            </select>
        </div>
        <br><button id="search" class="apply-filter-btn">Apply Filters</button><br>
    </div>

    <div class="jobContainer">
        <h3>Featured Jobs:</h3>
        <div class="scrollBox">
            <?php if (!empty($data['jobs'])): ?>
                <?php foreach ($data['jobs'] as $job): ?>
                    <?php
                    $deadlineDisplay = 'N/A';
                    if (!empty($job->deadline)) {
                        try {
                            $today = new DateTime('today');
                            $deadline = new DateTime($job->deadline);
                            if ($deadline < $today) {
                                $deadlineDisplay = "Closed";
                            } else {
                                $diff = $today->diff($deadline);
                                $days = (int)$diff->format("%a");
                                if ($diff === 0) {
                                    $deadlineDisplay = "Today";
                                } elseif ($diff === 1) {
                                    $deadlineDisplay = "1 day left";
                                } else {
                                    $deadlineDisplay = $days . " days left";
                                }
                            }
                        } catch (Exception $e) {
                            $deadlineDisplay = htmlspecialchars($job->deadline);
                        }
                    }
                    ?>
                    <div class="listItem">
                        <a class="jobListLink" href="<?= ROOT ?>jobdetails/<?= urlencode($job->job_id) ?>">
                            <div class="job-header">
                                <img class="company-logo" src="<?= htmlspecialchars($job->company_photo_path) ?>" alt="Logo">
                                <div class="deadline-box" area-hidden="false" title="Application deadline">
                                    <div class="deadline_row">
                                        <img class="icon" src="<?= ROOT ?>assets/svg_icons/clock.svg">
                                        <span class="deadline-text"><?= $deadlineDisplay ?></span>
                                    </div>
                                    <div class="bm_section">
                                        <?php
                                        $bm = null;
                                        if (!empty($_SESSION['USER'])) {
                                            $bookmarkModel = new Bookmark();
                                            $bm = $bookmarkModel->getBmStatus($_SESSION['USER']->user_id, $job->job_id);
                                        } ?>
                                        <button class="save_job bookmark-btn"
                                            data-job-id="<?= $job->job_id ?>">
                                            <img class="bm_icon" src="<?= ROOT ?>assets/svg_icons/<?= $bm ? 'remove_bm.svg' : 'add_bm.svg' ?>">
                                        </button>
                                    </div>
                                </div>

                            </div>
                            <div class="job-content">
                                <h4 class="job-title"><?= htmlspecialchars($job->posTitle) ?>
                                </h4>
                                <h4 class="company-name"><?= htmlspecialchars($job->companyName) ?></h4>
                                <div class="industry"><?= htmlspecialchars($job->industry) ?></div>
                                <div class="meta-item">
                                    <img class="icon" src="<?= ROOT ?>assets/svg_icons/location.svg">
                                    <span class="job-location"><?= htmlspecialchars($job->city) ?></span>
                                </div>
                                <div>
                                    <img class="icon" src="<?= ROOT ?>assets/svg_icons/briefcase.svg">
                                    <span class="job-type"> <?= htmlspecialchars($job->posType) ?>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="itemsEmpty">No jobs available.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
    document.getElementById("search").addEventListener("click", function() {
        const minSalary = document.getElementById("minSalary").value;
        const maxSalary = document.getElementById("maxSalary").value;
        const sortBy = document.getElementById("sortBy").value;
        const city = document.getElementById("city").value;
        const workMode = document.getElementById("workMode").value;
        const jobType = document.getElementById("jobType").value;
        const experience = document.getElementById("experience").value;

        const params = new URLSearchParams();

        if (minSalary) params.append("minSalary", minSalary);
        if (maxSalary) params.append("maxSalary", maxSalary);
        if (sortBy) params.append("sortBy", sortBy);
        if (city) params.append("city", city);
        if (workMode) params.append("workMode", workMode);
        if (jobType) params.append("jobType", jobType);
        if (experience) params.append("experience", experience);

        window.location.href = "<?= ROOT ?>home?" + params.toString();
    });

    //interim panel's request to disable clicking on jobs for non-candidates
    let userSession = <?= isset($_SESSION['USER']) ? json_encode($_SESSION['USER']->role) : 'null' ?>;

    document.addEventListener("click", function(e) {
        const jobLink = e.target.closest(".jobListLink");
        if (!jobLink) return;

        if (
            userSession === 'counselor' ||
            userSession === 'validator'
        ) {
            e.preventDefault();
            alert("You must register as a candidate in order to apply for a position");
        }
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", () => {

        document.querySelectorAll(".bookmark-btn").forEach(btn => {

            btn.addEventListener("click", (e) => {
                e.preventDefault();
                e.stopPropagation(); // prevent opening job link

                if (!userSession) {
                    alert("You must log in to bookmark jobs.");
                    return;
                }

                if (userSession !== 'candidate') {
                    alert("Only a candidate can bookmark jobs.");
                    return;
                }

                const jobId = btn.dataset.jobId;
                const icon = btn.querySelector("img");

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
                            icon.src = "<?= ROOT ?>assets/svg_icons/remove_bm.svg";
                        } else if (data.status === "removed") {
                            icon.src = "<?= ROOT ?>assets/svg_icons/add_bm.svg";
                        }
                    })
                    .catch(err => console.error(err));
            });

        });
    });
</script>