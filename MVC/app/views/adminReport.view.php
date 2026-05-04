<!DOCTYPE html>
<html>

<head>
    <title>System Report</title>
    <link rel="stylesheet" href="<?= ROOT ?>assets/css/report.css">
</head>

<body>
    <h1 class="title">Monthly System Report</h1>

    <table class="info-table">
        <tr>
            <td><strong>Month:</strong></td>
            <td><?= $data['reportMonth'] ?? date("F Y") ?></td>
            <td><strong>Prepared By:</strong></td>
            <td><?= $_SESSION['USER']->email ?? "Admin" ?></td>
        </tr>
    </table>

    <h2 class="section-title">Summary</h2>
    <?php
    $reportDetails = $data['reportData'];

    $new_companies      = $reportDetails['new_companies'] ?? 0;
    $new_candidates     = $reportDetails['new_candidates'] ?? 0;
    $new_counselors     = $reportDetails['new_counselors'] ?? 0;
    $total_users        = $reportDetails['total_users'] ?? 0;
    $active_users       = $reportDetails['active_users'] ?? 0;
    $company_interviews = $reportDetails['company_interviews'] ?? 0;
    $counselor_meetings = $reportDetails['counselor_meetings'] ?? 0;
    $feedback_emails = $reportDetails['feedback_emails'];
    //$total_earnings = $reportDetails['total_earnings'];
    $system_alerts = $reportDetails['system_alerts'];
    ?>
    <table class="summary-table">
        <tr>
            <td>Newly registered companies</td>
            <td class="amount"><?= $new_companies ?? 0 ?></td>
        </tr>
        <tr>
            <td>Newly registered candidates</td>
            <td class="amount"><?= $new_candidates ?? 0 ?></td>
        </tr>
        <tr>
            <td>Newly registered counselors</td>
            <td class="amount"><?= $new_counselors ?? 0 ?></td>
        </tr>
        <tr>
            <td>Total users</td>
            <td class="amount"><?= $total_users ?? 0 ?></td>
        </tr>
        <tr>
            <td>Active users</td>
            <td class="amount"><?= $active_users ?? 0 ?></td>
        </tr>
        <tr>
            <td>No of feedback emails</td>
            <td class="amount"><?= $feedback_emails ?? 0 ?></td>
        </tr>
        <tr>
            <td>Scheduled company interviews</td>
            <td class="amount"><?= $company_interviews ?? 0 ?></td>
        </tr>
        <tr>
            <td>Scheduled counselor meetings</td>
            <td class="amount"><?= $counselor_meetings ?? 0 ?></td>
        </tr>
        <tr>
            <td>total earnings in LKR</td>
            <td class="amount"><?= 0 ?></td>
        </tr>
        <tr>
            <td>System alerts</td>
            <td class="amount"><?= $system_alerts ?? 0 ?></td>
        </tr>
    </table>

    <p class="footer">
        Report generated on <?= date("Y-m-d H:i") ?>
    </p>

</body>

<script>
    window.onload = () => window.print();
</script>

</html>