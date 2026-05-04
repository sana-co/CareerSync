<link rel="stylesheet" href="<?= ROOT ?>assets/css/dashboard/candidateScheduler.css">

<div class="interview_scheduler_bg">
    <div class="scheduler_window">
        <h1>Schedule Interview</h1>
        <form method="POST">
            <input type="hidden" name="action" value="candidate_scheduler">

            <div class="interview-details">
                <p><strong>Mode:</strong> <span id="modal_mode"></span></p>
                <p><strong>Address/Link:</strong> <span id="modal_link"></span></p>
                <p><strong>Extra Details:</strong> <span id="modal_details"></span></p>
            </div>

            <input type="hidden" name="interview_id" id="modal_interview_id">

            <div class="input-field">
                <label for="selected_date">Pick a comfortable date:</label>
                <select name="selected_date" id="selected_date" required>
                    <option value="" disabled selected hidden>Select a date</option>
                </select>
            </div>

            <div class="form_btns">
                <button type="button" id="interviewSchedulerBackBtn" class="back-btn">Back</button>
                <button type="submit" class="send-btn">Confirm Date</button>
            </div>
        </form>
    </div>
</div>