<?php
class Subscription
{
    use Model;

    protected $table = 'subscriptions';
    protected $allowedColumns = [
        'candidate_id',
        'company_id',
    ];

    public function getCompaniesForCandidate($candidateId)
    {
        $query = "SELECT
                    company.user_id,
                    company.companyName,
                    company.company_photo_path,
                    company.contactNo,
                    company.hr_firstName,
                    company.hr_lastName,
                    company.validator_approval,
                    company.payment_status,
                    subscriptions.subscription_id,
                    CASE WHEN subscriptions.subscription_id IS NULL THEN 0 ELSE 1 END AS is_subscribed
                FROM company
                LEFT JOIN subscriptions
                    ON subscriptions.company_id = company.user_id
                   AND subscriptions.candidate_id = ?
                                ORDER BY
                                        CASE WHEN company.validator_approval = 'approved' THEN 0 ELSE 1 END,
                                        CASE WHEN company.payment_status = 'active' THEN 0 ELSE 1 END,
                                        company.companyName ASC";

        $result = $this->query($query, [$candidateId]);
        return $result ?: [];
    }

    public function getCandidateSubscriptions($candidateId)
    {
        $query = "SELECT
                    subscriptions.subscription_id,
                    subscriptions.candidate_id,
                    subscriptions.company_id,
                    subscriptions.created_at,
                    company.companyName,
                    company.company_photo_path,
                    company.contactNo,
                    company.hr_firstName,
                    company.hr_lastName
                FROM subscriptions
                INNER JOIN company
                    ON subscriptions.company_id = company.user_id
                WHERE subscriptions.candidate_id = ?
                ORDER BY subscriptions.created_at DESC";

        $result = $this->query($query, [$candidateId]);
        return $result ?: [];
    }

    public function subscribe($candidateId, $companyId)
    {
        $existing = $this->first([
            'candidate_id' => $candidateId,
            'company_id' => $companyId,
        ]);

        if ($existing) {
            return false;
        }

        return $this->insert([
            'candidate_id' => $candidateId,
            'company_id' => $companyId,
        ]);
    }

    public function unsubscribe($candidateId, $companyId)
    {
        $query = "DELETE FROM subscriptions WHERE candidate_id = ? AND company_id = ?";
        return $this->query($query, [$candidateId, $companyId]);
    }

    public function getSubscribersByCompany($companyId)
    {
        $query = "SELECT
                    subscriptions.subscription_id,
                    subscriptions.created_at,
                    candidate.user_id AS candidate_id,
                    candidate.firstName,
                    candidate.lastName,
                    candidate.contactNo,
                    candidate.candidate_photo_path
                FROM subscriptions
                INNER JOIN candidate
                    ON candidate.user_id = subscriptions.candidate_id
                WHERE subscriptions.company_id = ?
                ORDER BY subscriptions.created_at DESC";

        $result = $this->query($query, [$companyId]);
        return $result ?: [];
    }
}
