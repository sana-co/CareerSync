<?php
class JobPost
{
    use Model;
    protected $table = 'jobPost';
    protected $allowedColumns = [
        'company_id',
        'posTitle',
        'posType',
        'industry',
        'exp_level',
        'yearsOfExp',
        'qualifications',
        'required_skills',
        'salaryDetails',
        'address',
        'city',
        'workMode',
        'jobDescription',
        'vacancies',
        'deadline',
    ];

    public function SelectAll()
    {
        $query = "SELECT jobPost.*, company.company_photo_path, company.companyName
              FROM jobPost
              JOIN company ON jobPost.company_id = company.user_id
              ORDER BY $this->order_column $this->order_type
              LIMIT $this->limit OFFSET $this->offset";

        return $this->query($query);
    }

    public function jobpost_and_company($id)
    {
        $query = "SELECT jobPost.*, company.*
              FROM jobPost
              JOIN company ON jobPost.company_id = company.user_id
              WHERE jobPost.job_id = ?
              LIMIT 1";

        $params = [$id];
        $result = $this->query($query, $params);
        return $result ? $result[0] : null;
    }

    public function __construct() //overriding "protected $order_column = "user_id";" in model.php
    {
        $this->order_column = "job_id";
    }

    public function getFilteredJobs($filters = [])
    {
        $query = "SELECT jobPost.*, company.company_photo_path, company.companyName
              FROM jobPost
              JOIN company ON jobPost.company_id = company.user_id
              WHERE 1 ";
        $params = [];

        if (!empty($filters['minSalary'])) {
            $query .= " AND jobPost.salaryDetails >= ? ";
            $params[] = $filters['minSalary'];
        }

        if (!empty($filters['maxSalary'])) {
            $query .= " AND jobPost.salaryDetails <= ? ";
            $params[] = $filters['maxSalary'];
        }

        if (!empty($filters['city'])) {
            $query .= " AND jobPost.city = ? ";
            $params[] = $filters['city'];
        }

        if (!empty($filters['workMode'])) {
            $query .= " AND jobPost.workMode = ? ";
            $params[] = $filters['workMode'];
        }

        if (!empty($filters['jobType'])) {
            $query .= " AND jobPost.posType = ? ";
            $params[] = $filters['jobType'];
        }

        if (!empty($filters['experience'])) {
            $query .= " AND jobPost.exp_level = ? ";
            $params[] = $filters['experience'];
        }

        switch ($filters['sortBy'] ?? 'none') {
            case 'asc':
                $query .= " ORDER BY jobPost.posTitle ASC ";
                break;

            case 'desc':
                $query .= " ORDER BY jobPost.posTitle DESC ";
                break;

            case 'highsal':
                $query .= " ORDER BY jobPost.salaryDetails DESC ";
                break;

            case 'lowsal':
                $query .= " ORDER BY jobPost.salaryDetails ASC ";
                break;

            default:
                $query .= " ORDER BY jobPost.job_id DESC ";
                break;
        }
        return $this->query($query, $params);
    }

    public function getJobLocations()
    {
        $query = "SELECT DISTINCT city 
                    FROM jobPost 
                    WHERE city IS NOT NULL 
                    AND city != '' 
                    ORDER BY city ASC";
        return $this->query($query);
    }
}
