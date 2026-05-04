<?php
class CV
{
    use Model;
    protected $table = 'cvTable';
    protected $allowedColumns = [
        'job_id',
        'candidate_id',
        'cv_file_path',
        'validator_approval',
        'company_approval',
    ];

    public function SelectAll()
    {
        $query = "  SELECT 
            cvTable.*, 
            candidate.firstName, 
            candidate.lastName, 
            company.companyName
                    FROM cvTable
                    JOIN candidate ON cvTable.candidate_id = candidate.user_id
                    JOIN jobPost ON cvTable.job_id = jobPost.job_id
                    JOIN company ON jobPost.company_id = company.user_id";

        $result = $this->query($query);
        return $result ?: [];
    }

    public function getValidatorUnapprovedCv()
    {
        $query = "SELECT 
        cvTable.*, 
        candidate.firstName, 
        candidate.lastName, 
        company.companyName
        FROM cvTable
        JOIN candidate ON cvTable.candidate_id = candidate.user_id
        JOIN jobPost ON cvTable.job_id = jobPost.job_id
        JOIN company ON jobPost.company_id = company.user_id
        WHERE cvTable.validator_approval = 'pending'";

        $result = $this->query($query);
        return $result ?: [];
    }

    public function getSentCVsByCandidate($candidate_id)
    {
        $query = "SELECT 
            cvTable.cv_id,
            cvTable.cv_file_path,
            cvTable.candidate_id,
            cvTable.job_id,
            cvTable.applied_at,
            cvTable.validator_approval,
            cvTable.company_approval,
            candidate.firstName,
            candidate.lastName,
            CONCAT(candidate.firstName, ' ', candidate.lastName) AS candidateName,
            company.companyName,
            jobPost.posTitle AS posTitle,
            interviews.dateConfirmed
        FROM cvTable
        JOIN candidate 
            ON cvTable.candidate_id = candidate.user_id
        JOIN jobPost 
            ON cvTable.job_id = jobPost.job_id
        JOIN company 
            ON jobPost.company_id = company.user_id
        LEFT JOIN interviews 
            ON interviews.candidate_id = cvTable.candidate_id 
            AND interviews.job_id = cvTable.job_id
        WHERE cvTable.candidate_id = ?
          AND (interviews.dateConfirmed IS NULL OR interviews.dateConfirmed <> 'confirmed')
        ORDER BY cvTable.applied_at DESC";

        $result = $this->query($query, [$candidate_id]);
        return $result ?: [];
    }

    public function getApprovedCVsByCompany($company_id)
    {
        $query = "SELECT 
                cvTable.cv_id,
                cvTable.cv_file_path,
                cvTable.candidate_id,
                cvTable.job_id,
                cvTable.applied_at,
                cvTable.validator_approval,
                cvTable.company_approval,
                candidate.firstName,
                candidate.lastName,
                CONCAT(candidate.firstName, ' ', candidate.lastName) AS candidateName,
                company.companyName,
                jobPost.posTitle
            FROM cvTable
            JOIN candidate ON cvTable.candidate_id = candidate.user_id
            JOIN jobPost ON cvTable.job_id = jobPost.job_id
            JOIN company ON jobPost.company_id = company.user_id
            WHERE cvTable.validator_approval = 'approved'
              AND company.user_id = ?";

        $result = $this->query($query, [$company_id]);
        return $result ?: [];
    }
}
