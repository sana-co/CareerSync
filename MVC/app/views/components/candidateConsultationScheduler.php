<div class="consultation_scheduler_bg">
    <div class="scheduler_window">
        <h1>Schedule Consultation</h1>

        <form method="POST">
            <input type="hidden" name="action" value="candidate_consultation_scheduler">
            <input type="hidden" name="request_id" id="consultationRequestId">

            <div class="interview-details">
                <p><strong>Mode:</strong> <?= htmlspecialchars($data['consultationMeeting']['meetingData']->mode ?? '') ?></p>
                <p><strong>Address/Link:</strong> <?= htmlspecialchars($data['consultationMeeting']['meetingData']->address_link ?? '') ?></p>
                <p><strong>Extra Details:</strong> <?= htmlspecialchars($data['consultationMeeting']['meetingData']->extra_details ?? '') ?></p>
            </div>

            <input type="hidden" name="meeting_id" value="<?= $data['consultationMeeting']['meetingData']->meeting_id ?? '' ?>">

            <div class="input-field">
                <label for="consultation_selected_date">Pick a comfortable date:</label>
                <select name="selected_date" id="consultation_selected_date" required>
                    <option value="" disabled selected hidden>Select a date</option>
                    <?php if (!empty($data['consultationMeeting']['slots'])): ?>
                        <?php foreach ($data['consultationMeeting']['slots'] as $slot): ?>
                            <option value="<?= $slot->slot_datetime ?>">
                                <?= date("F j, Y - g:i A", strtotime($slot->slot_datetime)) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form_btns">
                <button type="button" id="consultationSchedulerBackBtn" class="back-btn">Back</button>
                <button type="submit" class="send-btn">Confirm Date</button>
            </div>
        </form>
    </div>
</div>