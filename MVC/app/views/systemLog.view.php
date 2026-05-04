<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>assets/css/systemLog.css">
    <title>System Logs</title>
</head>

<body>
    <div class="page-content">
        <h1>System Logs</h1>

        <div class="filters">
            <h3>Sort by:</h3>
            <div class="filter_inputs">
                <div class="filter_input">
                    <label for="date_filter">Date</label>
                    <select name="date_filter" id="date_filter">
                        <option value="all">All</option>
                        <option value="today">Today</option>
                        <option value="this_month">This Month</option>
                        <option value="this_year">This Year</option>
                    </select>
                </div>
                <div class="filter_input">
                    <label for="role_filter">Role</label>
                    <select name="role_filter" id="role_filter">
                        <option value="all">All</option>
                        <option value="guest">Guest</option>
                        <option value="admin">Admin</option>
                        <option value="candidate">Candidate</option>
                        <option value="company">Company</option>
                        <option value="counselor">Counselor</option>
                        <option value="validator">Validator</option>
                    </select>
                </div>
                <div class="filter_input">
                    <label for="action_filter">Actions</label>
                    <select name="action_filter" id="action_filter">
                        <option value="all">All</option>
                        <?php if (!empty($data['actionList'])): ?>
                            <?php foreach ($data['actionList'] as $a): ?>
                                <option value="<?= htmlspecialchars($a->action) ?>">
                                    <?= htmlspecialchars($a->action) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="filter_input">
                    <button class="clearLogs" id="clearLogs" onclick="clearLogs()">Clear All System logs</button>
                    <script>
                        const clear_btn = document.getElementById("clearLogs");

                        function clearLogs() {
                            if (confirm("Are you sure you want to delete all system logs?")) {
                                fetch(`<?= ROOT ?>systemLog/clearLogs`, {
                                        method: 'POST',
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest'
                                        }
                                    })
                                    .then(res => res.json())
                                    .then(data => {
                                        if (data.success) {
                                            tbody.innerHTML = `<tr><td colspan="8" style="text-align:center;">No logs found.</td></tr>`;
                                        } else {
                                            alert('Failed to delete logs: ' + data.error);
                                        }
                                    })
                                    .catch(err => console.error('Clear error:', err));
                            }
                        }
                    </script>
                </div>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="syslog-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Role</th>
                        <th>Action</th>
                        <th>Description</th>
                        <th>IP Address</th>
                        <th>User Agent</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody id="syslog-tbody">
                    <?php if (!empty($syslogs)) : ?>
                        <?php foreach ($syslogs as $log) : ?>
                            <tr class="role-<?= htmlspecialchars($log->role) ?> <?= $log->action === 'ALERT' ? 'alert' : '' ?>">
                                <td><?= $log->log_id ?></td>
                                <td><?= $log->user_id ?? '-' ?></td>
                                <td><?= ucfirst($log->role) ?></td>
                                <td class="action"><?= $log->action ?></td>
                                <td class="desc"><?= $log->description ?? '-' ?></td>
                                <td><?= $log->ip_address ?></td>
                                <td class="agent" title="<?= htmlspecialchars($log->user_agent) ?>">
                                    <?= substr($log->user_agent, 0, 30) ?>...
                                </td>
                                <td><?= date('Y-m-d H:i', strtotime($log->created_at)) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="8" style="text-align:center;">No logs found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <script>
            const dateFilter = document.getElementById('date_filter');
            const roleFilter = document.getElementById('role_filter');
            const actionFilter = document.getElementById('action_filter');
            const tbody = document.getElementById('syslog-tbody');

            function fetchLogs() {
                const date = dateFilter.value;
                const role = roleFilter.value;
                const action = actionFilter.value;

                tbody.innerHTML = `<tr><td colspan="8" style="text-align:center;">Loading...</td></tr>`;

                fetch(`<?= ROOT ?>systemLog/filter?date_filter=${date}&role_filter=${role}&action_filter=${action}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('Network response was not ok');
                        return res.json();
                    })
                    .then(logs => {
                        tbody.innerHTML = '';

                        if (!Array.isArray(logs) || logs.length === 0) {
                            tbody.innerHTML = `<tr><td colspan="8" style="text-align:center;">No logs found.</td></tr>`;
                            return;
                        }

                        logs.forEach(log => {
                            const alertClass = log.action === 'ALERT' ? 'alert' : '';
                            const agent = log.user_agent ? log.user_agent.substring(0, 30) + '...' : '-';
                            const time = new Date(log.created_at).toLocaleString('sv-SE', {
                                year: 'numeric',
                                month: '2-digit',
                                day: '2-digit',
                                hour: '2-digit',
                                minute: '2-digit'
                            }).replace('T', ' ');

                            tbody.innerHTML += `
                            <tr class="role-${log.role} ${alertClass}">
                                <td>${log.log_id}</td>
                                <td>${log.user_id ?? '-'}</td>
                                <td>${log.role.charAt(0).toUpperCase() + log.role.slice(1)}</td>
                                <td class="action">${log.action}</td>
                                <td class="desc">${log.description ?? '-'}</td>
                                <td>${log.ip_address}</td>
                                <td class="agent" title="${log.user_agent}">${agent}</td>
                                <td>${time}</td>
                            </tr>`;
                        });
                    })
                    .catch(err => {
                        tbody.innerHTML = `<tr><td colspan="8" style="text-align:center; color:red;">Failed to load logs.</td></tr>`;
                        console.error('Filter error:', err);
                    });
            }

            dateFilter.addEventListener('change', fetchLogs);
            roleFilter.addEventListener('change', fetchLogs);
            actionFilter.addEventListener('change',fetchLogs);
        </script>

    </div>
</body>

</html>