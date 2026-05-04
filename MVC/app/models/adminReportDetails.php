<?php

class AdminReportDetails
{
    use Model;
    protected $table = 'admin_reports';
    protected $allowedColumns = [
        'report_month',
        'report_month_name',
        'prepared_by',
        'generated_at',
        'new_companies',
        'new_candidates',
        'new_counselors',
        'total_users',
        'active_users',
        'feedback_count',
        'company_interviews',
        'counselor_meetings',
        'total_earnings',
        'system_alerts'
    ];

    public function selectOldReports()
    {
        $query = "SELECT * FROM admin_reports";
        return $this->query($query);
    }

    //live report generation
    public function generateLast30DaysReport()
    {
        $since = date('Y-m-d H:i:s', strtotime('-30 days'));

        return [
            'new_companies'       => $this->countNewCompanies($since),
            'new_candidates'      => $this->countNewCandidates($since),
            'new_counselors'      => $this->countNewCounselors($since),
            'total_users'         => $this->countTotalUsers(),
            'active_users'        => $this->countActiveUsers(),
            'company_interviews'  => $this->countCompanyInterviews($since),
            'counselor_meetings'  => $this->countCounselorMeetings($since),
            'system_alerts'       => $this->countSystemAlerts($since),
            'feedback_emails'     => $this->countFeedbackEmails($since),
        ];
    }
    private function countFeedbackEmails($since)
    {
        $query = "SELECT COUNT(*) AS total
        FROM feedback
        WHERE created_at >= ?";

        return $this->query($query, [$since])[0]->total ?? 0;
    }

    private function countNewCompanies($since)
    {
        $query = "SELECT COUNT(*) AS total
        FROM users
        WHERE role = 'company'
        AND created_at >= ?";

        return $this->query($query, [$since])[0]->total ?? 0;
    }

    private function countNewCandidates($since)
    {
        $query = "SELECT COUNT(*) AS total
        FROM users
        WHERE role = 'candidate'
        AND created_at >= ?";

        return $this->query($query, [$since])[0]->total ?? 0;
    }

    private function countNewCounselors($since)
    {
        $query = "SELECT COUNT(*) AS total
        FROM users
        WHERE role = 'counselor'
        AND created_at >= ?";

        return $this->query($query, [$since])[0]->total ?? 0;
    }

    private function countTotalUsers()
    {
        $query = "SELECT COUNT(*) AS total FROM users";
        return $this->query($query)[0]->total ?? 0;
    }

    private function countActiveUsers()
    {
        $query = "SELECT COUNT(*) AS total
        FROM users
        WHERE status = 'active'";

        return $this->query($query)[0]->total ?? 0;
    }

    private function countCompanyInterviews($since)
    {
        $query = "SELECT COUNT(DISTINCT i.interview_id) AS total
        FROM interviews i
        JOIN interview_slots s ON s.interview_id = i.interview_id
        WHERE s.slot_datetime >= ?";

        return $this->query($query, [$since])[0]->total ?? 0;
    }

    private function countCounselorMeetings($since)
    {
        $query = "SELECT COUNT(DISTINCT c.meeting_id) AS total
        FROM consultation c
        JOIN consultation_slots s ON s.meeting_id = c.meeting_id
        WHERE s.slot_datetime >= ?";

        return $this->query($query, [$since])[0]->total ?? 0;
    }

    private function countSystemAlerts($since)
    {
        $query = "SELECT COUNT(DISTINCT log_id) AS alert
        FROM system_logs
        WHERE action = 'ALERT'
        AND created_at >= ?";

        return $this->query($query, [$since])[0]->alert ?? 0;
    }

    //monthly report generation
    public function generateMonthlyReport($preparedBy = null)
    {
        // Previous month range
        $startDate = date('Y-m-01 00:00:00', strtotime('first day of last month'));
        $endDate   = date('Y-m-t 23:59:59', strtotime('last day of last month'));
        $monthKey  = date('Y-m', strtotime('last month'));

        if ($this->first(['report_month' => $monthKey])) {
            return false;
        }

        $totalUsers = $this->query("SELECT COUNT(*) AS c FROM users")[0]->c;

        $activeUsers = $this->query(
            "SELECT COUNT(*) AS c FROM users WHERE status = 'active'"
        )[0]->c;

        $newCompanies = $this->query(
            "SELECT COUNT(*) AS c FROM users 
         WHERE role='company' AND created_at BETWEEN ? AND ?",
            [$startDate, $endDate]
        )[0]->c;

        $newCandidates = $this->query(
            "SELECT COUNT(*) AS c FROM users 
         WHERE role='candidate' AND created_at BETWEEN ? AND ?",
            [$startDate, $endDate]
        )[0]->c;

        $newCounselors = $this->query(
            "SELECT COUNT(*) AS c FROM users 
         WHERE role='counselor' AND created_at BETWEEN ? AND ?",
            [$startDate, $endDate]
        )[0]->c;

        $companyInterviews = $this->query(
            "SELECT COUNT(DISTINCT i.interview_id) AS c
         FROM interviews i
         JOIN interview_slots s ON s.interview_id=i.interview_id
         WHERE s.slot_datetime BETWEEN ? AND ?",
            [$startDate, $endDate]
        )[0]->c;

        $counselorMeetings = $this->query(
            "SELECT COUNT(DISTINCT c.meeting_id) AS c
         FROM consultation c
         JOIN consultation_slots s ON s.meeting_id=c.meeting_id
         WHERE s.slot_datetime BETWEEN ? AND ?",
            [$startDate, $endDate]
        )[0]->c;

        $feedbackCount = $this->query(
            "SELECT COUNT(*) AS c FROM feedback
            WHERE created_at BETWEEN ? AND ?",
            [$startDate, $endDate]
        )[0]->c;

        $systemAlerts = $this->query(
            "SELECT COUNT(DISTINCT log_id) AS c
            FROM system_logs
            WHERE action = 'ALERT'
            AND created_at BETWEEN ? AND ?",
            [$startDate, $endDate]
        )[0]->c;

        return $this->insert([
            'report_month'       => $monthKey,
            'report_month_name'  => date('F Y', strtotime('last month')),
            'prepared_by'        => $preparedBy,
            'new_companies'      => $newCompanies,
            'new_candidates'     => $newCandidates,
            'new_counselors'     => $newCounselors,
            'total_users'        => $totalUsers,
            'active_users'       => $activeUsers,
            'feedback_count'     => $feedbackCount,
            'company_interviews' => $companyInterviews,
            'counselor_meetings' => $counselorMeetings,
            'total_earnings'     => 0.00,
            'system_alerts'      => $systemAlerts,
        ]);
    }

    public function generateMonthlyReportIfMissing($preparedBy = null)
    {
        $monthKey = date('Y-m', strtotime('last month'));

        if ($this->first(['report_month' => $monthKey])) {
            return false;
        }

        return $this->generateMonthlyReport($preparedBy);
    }

    public function getReportById($id)
    {
        return $this->first(['report_id' => $id]);
    }
}
