<?php
class JobDetails
{
    use Controller;
    public function index($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'toggle_bookmark') {

            if (empty($_SESSION['USER'])) {
                echo json_encode(['status' => 'unauthorized']);
                exit;
            }

            $job_id  = $_POST['job_id'] ?? null;
            $user_id = $_SESSION['USER']->user_id;

            if (!$job_id) {
                echo json_encode(['status' => 'error']);
                exit;
            }

            $bookmark = new Bookmark();
            $existing = $bookmark->getBmStatus($user_id, $job_id);

            if ($existing) {
                $bookmark->removeBookmark($user_id, $job_id);
                echo json_encode(['status' => 'removed']);
            } else {
                $bookmark->addBookmark($user_id, $job_id);
                echo json_encode(['status' => 'added']);
            }
            exit;
        }

        $data['username'] = empty($_SESSION['USER']) ? 'User' : $_SESSION['USER']->email;
        if (!$id) {
            redirect('home'); // if no job id, go back to home
        }

        $job = new JobPost;
        $jobData = $job->jobpost_and_company($id);

        $bookmark = new Bookmark;

        if (!empty($_SESSION['USER'])) {
            $data['bm_status'] = $bookmark->getBmStatus(
                $_SESSION['USER']->user_id,
                $jobData->job_id
            );
        } else {
            $data['bm_status'] = null;
        }

        if (!$jobData) {
            $this->view("404");
            return;
        }

        $job_apply = ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'job_apply');
        if ($job_apply) {
            //code for storing CV in the database
            $cv = new CV;
            $cv_upload_path = 'assets/uploads/cv/';

            $cv_filename = time() . '_' . basename($_FILES['cv_file_path']['name']);
            $cv_target = $cv_upload_path . $cv_filename;

            if (move_uploaded_file($_FILES['cv_file_path']['tmp_name'], $cv_target)) {

                $cvData = [
                    'job_id'        => $jobData->job_id,
                    'candidate_id'  => $_SESSION['USER']->user_id,
                    'cv_file_path'  => $cv_target,
                ];

                $cv->insert($cvData);

                redirect('dashboard');
                exit;
            }
        }

        $data['job'] = $jobData;

        $alreadyApplied = false;

        if (!empty($_SESSION['USER']) && $_SESSION['USER']->role === 'candidate') {
            $cv = new CV;
            $existingCV = $cv->first([
                'job_id' => $jobData->job_id,
                'candidate_id' => $_SESSION['USER']->user_id
            ]);

            if ($existingCV) {
                $alreadyApplied = true;
            }
        }

        $data['alreadyApplied'] = $alreadyApplied;

        $this->view("jobdetails", $data);
    }

    public function updateJob($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_POST['action'] ?? '') !== 'job_edit') {
            redirect('home');
            return;
        }

        if (!$id) {
            redirect('home');
            return;
        }

        // Only the company that owns the job can edit it
        if (empty($_SESSION['USER']) || $_SESSION['USER']->role !== 'company') {
            redirect('home');
            return;
        }

        $jobModel = new JobPost;
        $jobData = $jobModel->jobpost_and_company($id);
        if (!$jobData || $jobData->company_id != $_SESSION['USER']->user_id) {
            redirect('home');
            return;
        }

        // Collect updatable fields (match Model::$allowedColumns)
        $updateData = [
            'posTitle'       => $_POST['posTitle'],
            'posType'        => $_POST['posType'],
            'industry'       => $_POST['industry'],
            'exp_level'      => $_POST['exp_level'],
            'yearsOfExp'     => $_POST['yearsOfExp'],
            'qualifications' => $_POST['qualifications'],
            'required_skills'=> $_POST['required_skills'],
            'salaryDetails'  => $_POST['salaryDetails'],
            'address'        => $_POST['address'],
            'city'           => ucfirst(trim($_POST['city']," ")),
            'workMode'       => $_POST['workMode'],
            'jobDescription' => $_POST['jobDescription'],
            'vacancies'      => $_POST['vacancies'],
            'deadline'       => $_POST['deadline'],
        ];

        // Remove fields that weren't submitted
        $updateData = array_filter($updateData, function ($v) {
            return $v !== null;
        });

        if (!empty($updateData)) {
            $jobModel->update($id, $updateData, 'job_id');
        }

        redirect("jobdetails/{$id}");
        exit;
    }
}
