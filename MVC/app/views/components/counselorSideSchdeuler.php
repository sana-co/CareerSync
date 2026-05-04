<link rel="stylesheet" href="<?= ROOT ?>assets/css/dashboard/counselorScheduler.css">

<div class='popup-overlay'>
    <div class='popup-window'>
        <h1>Schedule meeting with candidate</h1>
        <form method="POST">
            <input type="hidden" name="candidate_id" id="schedulerCandidateId">
            <input type="hidden" name="request_id" id="schedulerRequestId">
            <input type="hidden" name="action" value="counselor_scheduler">
            <input type="hidden" name="decision" value="accept">

            <div class="input-field">
                <label for="medium">Online or Physical </label>
                <select name="medium">
                    <option value="" disabled selected hidden>Select interview medium</option>
                    <option value="online">Online</option>
                    <option value="physical">Physical</option>
                </select>
            </div>

            <div class="input-field">
                <label for="address">Address/Link</label>
                <input type="text" placeholder="physical address or link" name="address" required>
            </div>

            <div class="input-field">
                <label for="details">Extra Details</label>
                <textarea name="details" rows="4" placeholder="Additional info (optional)"></textarea>
            </div>

            <!-- This is to add/remove time slots -->
            <div id="slots-container">
                <div class="slot-input">
                    <input type="datetime-local" name="slots[]" required>
                </div>
            </div>
            <button type="button" id="add-slot">Add another date/time</button>
            <script>
                function setMinDate() {
                    const now = new Date();
                    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
                    const min = now.toISOString().slice(0, 16);
                    document.querySelectorAll('input[type="datetime-local"]').forEach(input => {
                        input.min = min;
                    });
                }

                setMinDate();

                document.getElementById('add-slot').addEventListener('click', function() {
                    const div = document.createElement('div');
                    div.classList.add('slot-input');
                    div.innerHTML = `<input type="datetime-local" name="slots[]" required><button type="button" class="remove-slot">X</button>`;
                    document.getElementById('slots-container').appendChild(div);
                    setMinDate();
                });

                // Remove slot
                document.getElementById('slots-container').addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-slot')) {
                        e.target.parentElement.remove();
                    }
                });
            </script>

            <div class="form_btns">
                <button type="submit">Send Dates</button>
                <button id="schedulerBackBtn">Back</button>
            </div>
        </form>
    </div>
</div>