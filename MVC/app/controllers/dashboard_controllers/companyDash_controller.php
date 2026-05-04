<?php
// Extract company data
$company = new Company;
$data['companyTable'] = $company->first(['user_id' => $_SESSION['USER']->user_id]);

//for getting the company status
$data['companyStatus'] = $company->getCompanyStatus($_SESSION['USER']->user_id);

//fetching posted jobs
$jobPost = new JobPost;
$data['postedJobs'] = $jobPost->where(['company_id' => $_SESSION['USER']->user_id]);

//fetching valid CV's
$cv = new CV;
$data['cv'] = $cv->getApprovedCVsByCompany($_SESSION['USER']->user_id);

$confirmedInterviews = new Interview();
$data['confirmedInterviews'] = $confirmedInterviews->getInterviewsByCompany($_SESSION['USER']->user_id);
$data['pendingInterviews'] = $confirmedInterviews->query(
    "SELECT COUNT(*) AS cnt FROM interviews WHERE company_id = ? AND (dateConfirmed IS NULL OR dateConfirmed != 'confirmed')",
    [$_SESSION['USER']->user_id]
);

// Load messages for company
require_once 'C:/xampp/htdocs/CareerSync/MVC/app/models/message.php';
$messageModel = new Message();
$companyId = $_SESSION['USER']->user_id ?? null;
$data['messages'] = $companyId ? $messageModel->getByReceiver($companyId, 'company') : [];

$subscription = new Subscription;
$data['subscribers'] = $companyId ? $subscription->getSubscribersByCompany($companyId) : [];

$photoPath = null;
$certificatePath = $data['companyTable']->business_certificate ?? null;

$isPostingJob = ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'posting_job');
$isExtendingDeadline = ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'postedJobActions' && $_POST['btn'] === 'Extend Deadline');
$isDeletingJob = ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'postedJobActions' && $_POST['btn'] === 'Delete');
$isSchedulingInterview = ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'company_scheduler');
$isSendingAnnouncement = ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'send_announcement');

if ($isProfileUpdate) {
    $errors = [];

    // Password check
    if (!password_verify($_POST['confirm_password'], $data['userTable']->password)) {
        $errors['confirm_password'] = "Incorrect password";
    }

    // Handle company logo upload
    if (!empty($_FILES['company_photo_path']['name'])) {
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/CareerSync/MVC/public/assets/uploads/company_logos/';
        $filename = time() . '_' . basename($_FILES['company_photo_path']['name']);
        $target = $uploadDir . $filename;

        $allowed = ['jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors['company_photo_path'] = "Invalid file type. Only JPG, JPEG, PNG allowed.";
        } elseif (move_uploaded_file($_FILES['company_photo_path']['tmp_name'], $target)) {
            $photoPath = 'assets/uploads/company_logos/' . $filename;
            $_SESSION['USER']->photo_path = $photoPath;
        } else {
            $errors['company_photo_path'] = "Error uploading logo.";
        }
    }

    // Handle business certificate upload
    if (!empty($_FILES['business_certificate']['name'])) {
        $certDir = $_SERVER['DOCUMENT_ROOT'] . '/CareerSync/MVC/public/assets/uploads/business_certificates/';
        $certFilename = time() . '_' . basename($_FILES['business_certificate']['name']);
        $certTarget = $certDir . $certFilename;

        $allowed = ['jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($certFilename, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors['business_certificate'] = "Invalid file type. Only JPG, JPEG, PNG allowed.";
        } elseif (move_uploaded_file($_FILES['business_certificate']['tmp_name'], $certTarget)) {
            $certificatePath = 'assets/uploads/business_certificates/' . $certFilename;
        } else {
            $errors['business_certificate'] = "Error uploading certificate.";
        }
    }

    if (empty($errors)) {
        // Update company table
        $companyUpdate = [
            'companyName'          => $_POST['companyName'] ?? '',
            'contactNo'            => $_POST['contactNo'] ?? '',
            'hr_firstName'         => $_POST['hr_firstName'] ?? '',
            'hr_lastName'          => $_POST['hr_lastName'] ?? '',
            'hr_contactNo'         => $_POST['hr_contactNo'] ?? '',
            'hr_email'             => $_POST['hr_email'] ?? '',
            'company_photo_path'   => $photoPath ?? $data['companyTable']->company_photo_path,
            'business_certificate' => $certificatePath
        ];

        $company->update($_SESSION['USER']->user_id, $companyUpdate, 'user_id');

        // Refresh session
        $updatedUser = $user->first(['user_id' => $_SESSION['USER']->user_id]);
        if ($updatedUser) {
            $_SESSION['USER'] = $updatedUser;
        }

        // set name & photo for header use
        $_SESSION['USER']->hr_firstName = $_POST['hr_firstName'];
        $_SESSION['USER']->photo_path = $photoPath ?? $data['companyTable']->company_photo_path;

        redirect('home');
        exit;
    }

    $data['errors'] = $errors;
}
if ($isPostingJob) {
    $jobPost = new JobPost;
    $jobData = [
        'company_id'       => $_SESSION['USER']->user_id,
        'posTitle'          => $_POST['posTitle'],
        'posType'          => $_POST['posType'],
        'industry'          => $_POST['industry'],
        'exp_level'         => $_POST['exp_level'],
        'yearsOfExp'        => $_POST['yearsOfExp'],
        'qualifications'    => $_POST['qualifications'] ?? '',
        'required_skills'   => $_POST['required_skills'] ?? '',
        'salaryDetails'     => $_POST['salaryDetails'],
        'address'           => $_POST['address'],
        'city'              => ucfirst(trim($_POST['city'], " ")),
        'workMode'          => $_POST['workMode'],
        'jobDescription'    => $_POST['jobDescription'],
        'vacancies'         => $_POST['vacancies'],
        'deadline'          => $_POST['deadline'],
    ];
    $jobPost->insert($jobData);
    SystemLogger::log('JOB_CREATED', $_POST['posTitle'].' : by '.$_SESSION['USER']->user_id);
    redirect("dashboard/companyDash");
    unset($_POST);
}

if ($isExtendingDeadline) {
    $job_id = $_POST['job_id'] ?? null;
    $new_deadline = $_POST['new_deadline'] ?? null;

    if ($job_id && $new_deadline) {
        $jobPost = new JobPost;
        $jobPost->update($job_id, ['deadline' => $new_deadline], 'job_id');
        $_SESSION['flash_message'] = "Deadline updated to $new_deadline successfully.";
    } else {
        $_SESSION['pjExtend_error_message'] = "Missing job ID or new deadline.";
    }

    redirect("dashboard/companyDash");
    exit;
}

if ($isDeletingJob) {
    $job_id = $_POST['job_id'] ?? null;

    if ($job_id) {
        $jobPost = new JobPost;

        try {
            $jobPost->query("DELETE FROM interview_slots WHERE interview_id IN (SELECT interview_id FROM interviews WHERE candidate_id IN (SELECT candidate_id FROM cvTable WHERE job_id = ?))", [$job_id]);
            $jobPost->query("DELETE FROM interviews WHERE candidate_id IN (SELECT candidate_id FROM cvTable WHERE job_id = ?)", [$job_id]);
            $jobPost->query("DELETE FROM cvTable WHERE job_id = ?", [$job_id]);
            $jobPost->query("DELETE FROM jobPost WHERE job_id = ?", [$job_id]);

            SystemLogger::log('JOB_DELETED','Deleted Job, ID: ' . $job_id);
            redirect("dashboard/companyDash");
            exit;
        } catch (Exception $e) {
            $_SESSION['pjDeletion_error_message'] = "Error deleting job: " . $e->getMessage();
        }
    }
}

if ($isSchedulingInterview) {
    $candidate_id = $_POST['candidate_id'] ?? null;
    $job_id = $_POST['job_id'];
    $company_id = $_SESSION['USER']->user_id;
    $mode = $_POST['medium'] ?? null;
    $address = $_POST['address'] ?? null;
    $details = $_POST['details'] ?? '';
    $slots = $_POST['slots'] ?? [];
    $actionType = $_POST['decision'] ?? 'accept';

    // Get job and company details for message
    $jobPost = new JobPost;
    $jobDetails = $jobPost->first(['job_id' => $job_id]);
    $companyDetails = $company->first(['user_id' => $company_id]);
    $messageModel = new Message;

    $cv = new CV;
    $cvRecord = $cv->first(['candidate_id' => $candidate_id, 'job_id' => $job_id]);

    if (!$cvRecord) {
        $_SESSION['error'] = "No matching CV found for this candidate.";
        redirect("dashboard/companyDash");
        exit;
    }

    // REJECT CASE
    if ($actionType === 'reject') {
        $cv->update($cvRecord->cv_id, ['company_approval' => 'rejected'], 'cv_id');

        // Send message to candidate
        $messageModel->insert([
            'receiver_id' => $candidate_id,
            'receiver_type' => 'candidate',
            'content' => "Your application for {$jobDetails->posTitle} at {$companyDetails->companyName} has been rejected.",
            'is_read' => 0
        ]);

        $_SESSION['success'] = "Candidate rejected successfully.";
        redirect("dashboard/companyDash");
        exit;
    }

    // ACCEPT CASE
    if ($actionType === 'accept') {
        if ($candidate_id && $company_id && $mode && $address && !empty($slots)) {
            // Update approval status
            $cv->update($cvRecord->cv_id, ['company_approval' => 'approved'], 'cv_id');

            // Create interview entry
            $interviewModel = new Interview();
            $interviewModel->createInterview([
                'candidate_id'  => $candidate_id,
                'company_id'    => $company_id,
                'job_id'        => $job_id,
                'mode'          => $mode,
                'address_link'  => $address,
                'extra_details' => $details
            ], $slots);

            // Send message to candidate
            $messageModel->insert([
                'receiver_id' => $candidate_id,
                'receiver_type' => 'candidate',
                'content' => "Your application for {$jobDetails->posTitle} at {$companyDetails->companyName} has been accepted. Interview has been scheduled.",
                'is_read' => 0
            ]);

            $_SESSION['success'] = "Candidate accepted and interview scheduled.";
            redirect("dashboard/companyDash");
            exit;
        } else {
            $_SESSION['error'] = "Please fill in all required fields.";
        }
    }
}

if ($isSendingAnnouncement) {
    $announcementMessage = trim($_POST['announcement_message'] ?? '');
    $subscribers = $companyId ? $subscription->getSubscribersByCompany($companyId) : [];

    if ($announcementMessage === '') {
        $_SESSION['error'] = 'Please write an announcement first.';
        redirect('dashboard/companyDash');
        exit;
    }

    if (empty($subscribers)) {
        $_SESSION['error'] = 'There are no subscribed candidates to notify yet.';
        redirect('dashboard/companyDash');
        exit;
    }

    $companyName = $data['companyTable']->companyName ?? 'Your company';

    foreach ($subscribers as $subscriber) {
        $messageModel->insert([
            'receiver_id' => $subscriber->candidate_id,
            'receiver_type' => 'candidate',
            'content' => "Announcement from {$companyName}: {$announcementMessage}",
            'is_read' => 0
        ]);
    }

    $_SESSION['success'] = 'Announcement sent to all subscribed candidates.';
    redirect('dashboard/companyDash');
    exit;
}
