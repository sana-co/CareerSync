<?php
class AdminReport
{
    use Controller;

    public function index()
    {
        $data['username'] = empty($_SESSION['USER']) ? 'User' : $_SESSION['USER']->email;

        $reports = new AdminReportDetails();

        //Live report
        if (isset($_GET['live'])) {
            $data['reportData'] = $reports->generateLast30DaysReport();
            $data['reportMonth'] = "Last 30 Days";
        }

        //Stored report by ID
        else if (isset($_GET['report_id'])) {
            $report = $reports->getReportById($_GET['report_id']);

            if (!$report) {
                die("Report not found");
            }

            $data['reportData'] = [
                'new_companies'      => $report->new_companies,
                'new_candidates'     => $report->new_candidates,
                'new_counselors'     => $report->new_counselors,
                'total_users'        => $report->total_users,
                'active_users'       => $report->active_users,
                'company_interviews' => $report->company_interviews,
                'counselor_meetings' => $report->counselor_meetings,
                'feedback_emails'    => $report->feedback_count,   // ← was missing
                'system_alerts'      => $report->system_alerts,    // ← was missing
            ];

            $data['reportMonth'] = $report->report_month_name;
        } else {
            $data['reportData'] = $reports->generateLast30DaysReport();
            $data['reportMonth'] = "Last 30 Days";
        }

        $this->view("adminReport", $data);
    }
}
