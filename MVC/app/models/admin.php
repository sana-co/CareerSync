<?php
class Admin
{
    use Model;
    protected $table = 'admin';
    protected $allowedColumns = [
        'user_id',
        'firstName',
        'lastName',
        'contactNo',
        'admin_photo_path',
    ];

    public function getValidatorDetails()
    {
        $query = "SELECT v.*, u.email, u.status
                FROM validator v
                JOIN users u ON v.user_id = u.user_id";

        return $this->query($query);
    }

    public function getCandidateDetails()
    {
        $query = "SELECT c.*, u.email, u.status
                FROM candidate c
                JOIN users u ON c.user_id = u.user_id
                WHERE u.status = 'pending'";

        return $this->query($query);
    }

    public function getSystemLogs()
    {
        $query = "SELECT * FROM system_logs
                ORDER BY created_at ASC";

        return $this->query($query);
    }

    public function getSysAlerts()
    {
        $query = "SELECT * FROM system_logs 
                WHERE action = 'ALERT'
                ORDER BY  created_at";
        return $this->query($query);
    }

    public function getTotalUsers()
    {
        $query = "SELECT COUNT(*) AS total FROM users";
        $result = $this->query($query);

        return $result[0]->total ?? 0;
    }

    public function getActiveUsers()
    {
        $query = "SELECT COUNT(*) AS total FROM users
        WHERE status = 'active'";
        $result = $this->query($query);

        return $result[0]->total ?? 0;
    }

    public function getSystemAlertCount()
    {
        $query = "SELECT COUNT(*) AS total FROM system_logs
                WHERE action = 'ALERT'";
        $result = $this->query($query);

        return $result[0]->total ?? 0;
    }

    public function getTotalJobPosts()
    {
        $query = "SELECT COUNT(*) AS total FROM jobPost";
        $result = $this->query($query);

        return $result[0]->total ?? 0;
    }

    public function getCounselorDetails()
    {
        $query = "SELECT c.*, u.email, u.status
                FROM counselor c
                JOIN users u ON c.user_id = u.user_id";

        return $this->query($query);
    }

    public function getCompanyDetails()
    {
        $query = "SELECT c.*, u.email, u.status
                FROM company c
                JOIN users u ON c.user_id = u.user_id";

        return $this->query($query);
    }

    //system log sorting
    public function getFilteredLogs($date_filter = 'all', $role_filter = 'all',$action_filter='all')
    {
        $role_filter = strtolower(trim($role_filter));

        $query = "SELECT * FROM system_logs WHERE 1=1";

        if ($role_filter !== 'all') {
            $query .= " AND LOWER(role) = '$role_filter'";
        }

        if($action_filter !== 'all'){
            $query .= " AND action = '$action_filter'";
        }
        
        switch ($date_filter) {
            case 'today':
                $query .= " AND DATE(created_at) = CURDATE()";
                break;
            case 'this_month':
                $query .= " AND MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW())";
                break;
            case 'this_year':
                $query .= " AND YEAR(created_at) = YEAR(NOW())";
                break;
        }

        $query .= " ORDER BY created_at ASC";

        return $this->query($query);
    }

    public function deleteAllLogs()
    {
        $query = "DELETE FROM system_logs";
        return $this->query($query);
    }

    public function getMonthlyRegistrations($months = 6)
    {
        $results = $this->query(
            "SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, COUNT(*) AS count
         FROM users
         WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? MONTH)
         GROUP BY DATE_FORMAT(created_at, '%Y-%m')
         ORDER BY month ASC",
            [$months]
        );
        return $results ?? [];
    }

    public function getUserRoleDistribution()
    {
        return $this->query(
            "SELECT role, COUNT(*) AS count FROM users GROUP BY role"
        ) ?? [];
    }
}
