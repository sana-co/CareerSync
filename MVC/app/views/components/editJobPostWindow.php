<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>assets/css/common.css">
    <link rel="stylesheet" href="<?= ROOT ?>assets/css/jobdetails.css">
</head>

<body>

    <div class="edit_job_display" id="editJobModel" style="display: none;">
        <div class="editWindow">
            <h1 style="text-align: center;">Edit Job Post</h1>
            <h4 class="job_title"><?= $data['job']->posTitle ?></h4>
            <h4 class="company_name"><?= $data['job']->companyName ?></h4>

            <form method="POST" action="<?= ROOT ?>jobdetails/updateJob/<?= $data['job']->job_id ?>">
                <input type="hidden" name="action" value="job_edit">

                <div class="input-field">
                    <label><span style="color:red;">*</span> Position Type</label>
                    <select id="posType" name="posType" class="filter_dropdown">
                        <option value="intern" <?= $job->posType == 'intern' ? 'selected' : '' ?>>Intern</option>
                        <option value="fullTime" <?= $job->posType == 'fullTime' ? 'selected' : '' ?>>Full-Time</option>
                        <option value="partTime" <?= $job->posType == 'partTime' ? 'selected' : '' ?>>Part-Time</option>
                        <option value="freelance" <?= $job->posType == 'freelance' ? 'selected' : '' ?>>Freelance</option>
                        <option value="contract" <?= $job->posType == 'contract' ? 'selected' : '' ?>>Contract</option>
                    </select>
                </div>

                <div class="input-field">
                    <label><span style="color:red;">*</span> Industry</label>
                    <input type="text" name="industry" value="<?= $job->industry ?>">
                </div>

                <div class="input-field">
                    <label><span style="color:red;">*</span> Experience level</label>
                    <select id="experience" name="exp_level" class="filter_dropdown">
                        <option value="entry" <?= $job->exp_level == 'entry' ? 'selected' : '' ?>>Entry</option>
                        <option value="mid" <?= $job->exp_level == 'mid' ? 'selected' : '' ?>>Mid</option>
                        <option value="senior" <?= $job->exp_level == 'senior' ? 'selected' : '' ?>>Senior</option>
                    </select>
                </div>

                <div class="input-field">
                    <label><span style="color:red;">*</span> Years of Experience</label>
                    <input type="number" name="yearsOfExp" value="<?= $job->yearsOfExp ?>">
                </div>

                <div class="input-field">
                    <label><span style="color:red;">*</span> Qualifications</label>
                    <input type="text" name="qualifications" value="<?= $job->qualifications ?>">
                </div>

                <div class="input-field">
                    <label><span style="color:red;">*</span> Required Skills</label>
                    <textarea name="required_skills"><?= $job->required_skills ?></textarea>
                </div>

                <div class="input-field">
                    <label><span style="color:red;">*</span> Salary</label>
                    <input type="text" name="salaryDetails" value="<?= $job->salaryDetails ?>">
                </div>

                <div class="input-field">
                    <label><span style="color:red;">*</span> Address</label>
                    <input type="text" name="address" value="<?= $job->address ?>">
                </div>

                <div class="input-field">
                    <label><span style="color:red;">*</span> City</label>
                    <input type="text" name="city" value="<?= $job->city ?>">
                </div>

                <div class="input-field">
                    <label><span style="color:red;">*</span> Work Mode</label>
                    <select id="workMode" name="workMode" class="filter_dropdown">
                        <option value="">All</option>
                        <option value="online" <?= $job->workMode == 'online' ? 'selected' : '' ?>>Online</option>
                        <option value="offline" <?= $job->workMode == 'offline' ? 'selected' : '' ?>>Offline</option>
                        <option value="hybrid" <?= $job->workMode == 'hybrid' ? 'selected' : '' ?>>Hybrid</option>
                    </select>
                </div>

                <div class="input-field">
                    <label><span style="color:red;">*</span> Job Description</label>
                    <textarea name="jobDescription"><?= $job->jobDescription ?></textarea>
                </div>

                <div class="input-field">
                    <label><span style="color:red;">*</span> Number of Vacancies</label>
                    <input type="number" name="vacancies" value="<?= $data['job']->vacancies ?>">
                </div>

                <div class="input-field">
                    <label><span style="color:red;">*</span>  Application Deadline</label>
                    <input type="date" id="deadline" name="deadline" value="<?= $job->deadline ?>">
                </div>

                <script>
                    const today = new Date().toISOString().split('T')[0];
                    document.getElementById("deadline").min = today;
                </script>

                <div class="form_btns">
                    <button type="submit">Save Changes</button>
                    <a href="<?= ROOT ?>jobdetails/<?= $job->job_id ?>">
                        <button type="button">Back</button>
                    </a>
                </div>
            </form>
        </div>
    </div>

</body>

</html>