<link rel="stylesheet" href="<?= ROOT ?>assets/css/dashboard/listPosition.css">
<div class="listing_application_bg">
    <div class="listing_application_window">
        <h1>Create a Job Position</h1>
        <button id="listing_application_backBtn">Back</button>
        <div class="scrollbox">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="posting_job">
                <div class="input-field">
                    <label for="posTitle">Position Title</label>
                    <input
                        type="text"
                        placeholder="Position Title"
                        name="posTitle"
                        required
                        value="<?= isset($_POST['posTitle']) ? htmlspecialchars($_POST['posTitle']) : '' ?>">
                </div>
                <div class="input-field">
                    <label for="posType">Position Type</label>
                    <select
                        name="posType"
                        required
                        value="<?= isset($_POST['posType']) ? htmlspecialchars($_POST['posType']) : '' ?>">
                        <option disabled selected hidden>Select Position Type</option>
                        <option value="intern">Internship</option>
                        <option value="fullTime">Full Time</option>
                        <option value="partTime">Part Time</option>
                        <option value="contract">Contract</option>
                        <option value="freelance">Freelance</option>
                    </select>
                </div>

                <div class="input-field">
                    <label for="industry">Industry/Department</label>
                    <input
                        type="text"
                        placeholder="e.g., IT, Marketing, Finance"
                        name="industry"
                        required
                        value="<?= isset($_POST['industry']) ? htmlspecialchars($_POST['industry']) : '' ?>">
                </div>

                <div class="input-field">
                    <label for="exp_level">Experience Level</label>
                    <select
                        name="exp_level"
                        required
                        value="<?= isset($_POST['exp_level']) ? htmlspecialchars($_POST['exp_level']) : '' ?>">
                        <option disabled selected hidden>Select reqired level of experience</option>
                        <option value="entry">Entry-level</option>
                        <option value="mid">Mid-level</option>
                        <option value="senior">Senior</option>
                    </select>
                </div>

                <div class="input-field">
                    <label for="yearsOfExp">Years of experiance required</label>
                    <input
                        type="text"
                        placeholder="Number of years of experience"
                        name="yearsOfExp"
                        required
                        value="<?= isset($_POST['yearsOfExp']) ? htmlspecialchars($_POST['yearsOfExp']) : '' ?>">
                </div>

                <div class="input-field">
                    <label for="qualifications">Education Requirements</label>
                    <input
                        type="text"
                        placeholder="Bachelor's, Master's, High School, etc."
                        name="qualifications"
                        required
                        value="<?= isset($_POST['qualifications']) ? htmlspecialchars($_POST['qualifications']) : '' ?>">
                </div>

                <div class="input-field">
                    <label for="required_skills">Required Skills</label>
                    <input
                        type="text"
                        placeholder="required Skills"
                        name="required_skills"
                        required
                        value="<?= isset($_POST['required_skills']) ? htmlspecialchars($_POST['required_skills']) : '' ?>">
                </div>

                <div class="input-field">
                    <label for="salaryDetails">Salary Details</label>
                    <input
                        type="text"
                        placeholder="Salary Details"
                        name="salaryDetails"
                        required
                        value="<?= isset($_POST['salaryDetails']) ? htmlspecialchars($_POST['salaryDetails']) : '' ?>">
                </div>

                <div class="input-field">
                    <label for="address">Work Location</label>
                    <input
                        type="text"
                        placeholder="Address"
                        name="address"
                        required
                        value="<?= isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '' ?>">
                </div>

                <div class="input-field">
                    <label for="city">City</label>
                    <input
                        type="text"
                        placeholder="city"
                        name="city"
                        required
                        value="<?= isset($_POST['city']) ? htmlspecialchars($_POST['city']) : '' ?>">
                </div>

                <div class="input-field">
                    <label>Work Mode</label>
                    <div id="workMode">
                        <label>
                            <input type="radio" name="workMode" value="online"
                                <?= (isset($_POST['workMode']) && $_POST['workMode'] === 'online') ? 'checked' : '' ?> required>
                            Online
                        </label>
                        <label>
                            <input type="radio" name="workMode" value="offline"
                                <?= (isset($_POST['workMode']) && $_POST['workMode'] === 'offline') ? 'checked' : '' ?>>
                            Offline
                        </label>
                        <label>
                            <input type="radio" name="workMode" value="hybrid"
                                <?= (isset($_POST['workMode']) && $_POST['workMode'] === 'hybrid') ? 'checked' : '' ?>>
                            Hybrid
                        </label>
                    </div>
                </div>

                <div class="input-field">
                    <label for="jobDescription">Job Description</label>
                    <textarea
                        id="jobDesc"
                        name="jobDescription"
                        rows="10" placeholder="A breif description about the job"
                        required></textarea>
                </div>

                <div class="input-field">
                    <label for="vacancies">Number of Vacancies</label>
                    <input
                        type="text"
                        placeholder="Number of Vacancies"
                        name="vacancies"
                        required
                        value="<?= isset($_POST['vacancies']) ? htmlspecialchars($_POST['vacancies']) : '' ?>">
                </div>

                <div class="input-field">
                    <label for="deadline">Appllication Deadline</label>
                    <input
                        type="date"
                        id ="deadline"
                        placeholder="Appllication Deadline"
                        name="deadline"
                        required
                        value="<?= isset($_POST['deadline']) ? htmlspecialchars($_POST['deadline']) : '' ?>">
                </div>

                <script>
                    const today = new Date().toISOString().split('T')[0];
                    document.getElementById("deadline").min = today;
                </script>

                <div class="form_btns">
                    <button type="submit" class="submit">CREATE</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const listing_application_backBtn = document.getElementById("listing_application_backBtn");
        const createBtn = document.getElementById("createBtn");
        const listing_application_bg = document.querySelector(".listing_application_bg");

        createBtn.addEventListener("click", (e) => {
            e.preventDefault();
            listing_application_bg.classList.add("active");
        });

        listing_application_backBtn.addEventListener("click", () => {
            listing_application_bg.classList.remove("active");
        });
    });
</script>