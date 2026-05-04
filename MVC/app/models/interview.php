<?php
class Interview
{
    use Model;
    protected $table = 'interviews';
    protected $allowedColumns = [
        'candidate_id',
        'company_id',
        'job_id',
        'mode',
        'address_link',
        'extra_details',
        'dateConfirmed',
    ];

    public function getCandidateInterview($candidate_id)
    {
        $query = "SELECT * FROM interviews WHERE candidate_id = ?";
        $interviews = $this->query($query, [$candidate_id]);

        $result = [];

        if ($interviews) {
            foreach ($interviews as $interview) {
                // Fetch slots for each interview
                $slotQuery = "SELECT slot_datetime 
                          FROM interview_slots 
                          WHERE interview_id = ?";
                $slots = $this->query($slotQuery, [$interview->interview_id]);

                $result[] = [
                    'interviewData' => $interview,
                    'slots' => $slots
                ];
            }
        }
        return $result;
    }


    public function createInterview($data, $slots)
    {
        $this->insert($data);

        $result = $this->query(
            "SELECT interview_id 
            FROM interviews 
            WHERE candidate_id = ? 
            AND company_id = ? 
            ORDER BY interview_id DESC 
            LIMIT 1",
            [$data['candidate_id'], $data['company_id']]
        );

        if (!$result || !isset($result[0]->interview_id)) {
            throw new Exception("Interview ID not found after insert");
        }

        $interview_id = $result[0]->interview_id;

        $slotModel = new InterviewSlot();
        foreach ($slots as $slot) {
            $slotModel->insert([
                'interview_id'  => $interview_id,
                'slot_datetime' => date('Y-m-d H:i:s', strtotime($slot))
            ]);
        }

        return true;
    }

    public function getInterviewsByCandidate($candidate_id)
    {
        $query = "SELECT 
                jobPost.posTitle,
                company.companyName,
                MIN(interview_slots.slot_datetime) AS slot_datetime,
                interviews.mode,
                interviews.address_link,
                cvTable.cv_file_path
            FROM interviews
            INNER JOIN interview_slots 
                ON interviews.interview_id = interview_slots.interview_id
            INNER JOIN company 
                ON interviews.company_id = company.user_id
            INNER JOIN jobPost 
                ON interviews.job_id = jobPost.job_id
            INNER JOIN cvTable 
                ON interviews.candidate_id = cvTable.candidate_id 
                AND cvTable.job_id = interviews.job_id
            WHERE interviews.candidate_id = ?
                AND cvTable.company_approval = 'approved'
                AND interviews.dateConfirmed = 'confirmed'
            GROUP BY 
                interviews.interview_id,
                jobPost.posTitle,
                company.companyName,
                interviews.mode,
                interviews.address_link,
                cvTable.cv_file_path";

        return $this->query($query, [$candidate_id]);
    }

    public function getInterviewsByCompany($company_id)
    {
        $query = "SELECT 
                jobPost.posTitle,
                CONCAT(candidate.firstName, ' ', candidate.lastName) AS candidateName,
                MIN(interview_slots.slot_datetime) AS slot_datetime,
                interviews.mode,
                interviews.address_link,
                cvTable.cv_file_path
                FROM interviews
                INNER JOIN jobPost 
                ON interviews.job_id = jobPost.job_id
                INNER JOIN candidate 
                ON interviews.candidate_id = candidate.user_id
                INNER JOIN interview_slots 
                ON interviews.interview_id = interview_slots.interview_id
                INNER JOIN cvTable
                ON interviews.candidate_id = cvTable.candidate_id
                AND interviews.job_id = cvTable.job_id
                WHERE interviews.company_id = ?
                AND interviews.dateConfirmed = 'confirmed'
                GROUP BY interviews.interview_id
                ORDER BY slot_datetime ASC";

        return $this->query($query, [$company_id]);
    }
}
