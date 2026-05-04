<?php
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

//extract admin data
$admin = new Admin;
$data['totalUsers'] = $admin->getTotalUsers();
$data['activeUsers'] = $admin->getActiveUsers();
$data['systemAlertCount'] = $admin->getSystemAlertCount();
$data['totalJobPosts'] = $admin->getTotalJobPosts();
$data['adminTable'] = $admin->first(['user_id' => $_SESSION['USER']->user_id]);
$data['validators'] = $admin->getValidatorDetails();
$data['candidates'] = $admin->getCandidateDetails();
$data['counselors'] = $admin->getCounselorDetails();
$data['companies'] = $admin->getCompanyDetails();
$data['sysAlerts'] = $admin->getSysAlerts();

require_once __DIR__ . '/../../models/ContactModel.php';

//for charts
$data['monthlyRegistrations'] = $admin->getMonthlyRegistrations(6);
$data['roleDistribution'] = $admin->getUserRoleDistribution();

$feedbackModel = new ContactModel();
$data['feedbacks'] = $feedbackModel->SelectAll();
$reports = new AdminReportDetails;
$reports->generateMonthlyReportIfMissing($_SESSION['USER']->user_id);
$data['oldReportDetails'] = $reports->selectOldReports();


// Handle delete
if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    $deleted = $feedbackModel->deleteMessage($id); // now $model is not null
    redirect("Dashboard");
    exit;
}


$photoPath = null;

//code for updating user profile 
if ($isProfileUpdate) {
    $errors = [];

    if (!password_verify($_POST['confirm_password'], $data['userTable']->password)) {
        $errors['confirm_password'] = "Incorrect password";
    }

    if (!empty($_FILES['admin_photo_path']['name'])) {
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/CareerSync/MVC/public/assets/uploads/admin_photo/';

        $filename = time() . '_' . basename($_FILES['admin_photo_path']['name']);
        $target = $uploadDir . $filename;

        $allowed = ['jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors['admin_photo_path'] = "Invalid file type. Only JPG, JPEG, PNG allowed.";
        } elseif (move_uploaded_file($_FILES['admin_photo_path']['tmp_name'], $target)) {
            $photoPath = 'assets/uploads/admin_photo/' . $filename;
        } else {
            $errors['admin_photo_path'] = "Error uploading photo.";
        }
    }

    if (empty($errors)) {
        // Prepare admin update array
        $adminUpdate = [
            'firstName' => $_POST['firstName'] ?? '',
            'lastName' => $_POST['lastName'] ?? '',
            'contactNo' => $_POST['contactNo'] ?? '',
            'admin_photo_path' => $photoPath ?? $data['adminTable']->admin_photo_path
        ];

        $admin->update($_SESSION['USER']->user_id, $adminUpdate, 'user_id');

        $updatedUser = $user->first(['user_id' => $_SESSION['USER']->user_id]);
        if ($updatedUser) {
            $_SESSION['USER'] = $updatedUser;
        }
        $_SESSION['USER']->firstName = $_POST['firstName']; //this is to fix an error in the home page. do this, or log out once edited profile
        $_SESSION['USER']->photo_path = $photoPath ?? $data['adminTable']->admin_photo_path; //need to fix this too. editing pfp and redirecting to a logged in home doesnt show the pfp
        //unset($_SESSION['USER']);//this loggs out after editing profile
        redirect('dashboard');
        exit;
    }

    $data['errors'] = $errors;
}

$isManageValidator = ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'validateValidator');
$isManageCounselor = ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'validateCounselor');
$isManageCompany   = ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'validateCompany');
$isManageCandidate = ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'validateCandidate');

if ($isManageValidator) {
    $validatorId = $_POST['validator_id'] ?? null;
    if (!$validatorId) {
        if ($isAjax) {
            echo json_encode(['success' => false, 'message' => 'No ID provided']);
            exit;
        }
        redirect('dashboard');
        exit;
    }

    $user = new User;
    $validator = new Validator;

    if (isset($_POST['grant'])) {
        $user->update(
            $validatorId,
            ['status' => 'active'],
            'user_id'
        );
        SystemLogger::log('ACCESS_GRANTED', 'access granted for validator(ID: ' . $validatorId . ')');
    }

    if (isset($_POST['revoke'])) {
        $user->update($validatorId, ['status' => 'pending'], 'user_id');
        SystemLogger::log('ACCESS_REVOKED', 'access revoked for validator(ID: ' . $validatorId . ')');
    }

    if (isset($_POST['deny'])) {
        $validator->query("DELETE FROM messages WHERE receiver_id = ? AND receiver_type = 'validator'", [$validatorId]);
        $validator->delete($validatorId, 'user_id');
        $user->delete($validatorId, 'user_id');
        SystemLogger::log('ACCOUNT_DELETION', 'validator account(ID: ' . $validatorId . ') deleted from the database');
        redirect('dashboard');
    }

    if ($isAjax) {
        echo json_encode(['success' => true]);
        exit;
    }
    redirect('dashboard');
    exit;
}

if ($isManageCounselor) {
    $counselorId = $_POST['counselor_id'] ?? null;
    if (!$counselorId) {
        if ($isAjax) {
            echo json_encode(['success' => false, 'message' => 'No ID provided']);
            exit;
        }
        redirect('dashboard');
        exit;
    }

    $user = new User;
    $counselor = new Counselor;

    if (isset($_POST['grant'])) {
        $user->update($counselorId, ['status' => 'active'], 'user_id');
        SystemLogger::log('ACCESS_GRANTED', 'access granted for counselor(ID: ' . $counselorId . ')');
    }

    if (isset($_POST['revoke'])) {
        $user->update($counselorId, ['status' => 'pending'], 'user_id');
        SystemLogger::log('ACCESS_REVOKED', 'access revoked for counselor(ID: ' . $counselorId . ')');
    }

    if (isset($_POST['deny'])) {
        $counselor->query("DELETE FROM consultation_slots WHERE meeting_id IN (SELECT meeting_id FROM consultation WHERE counselor_id = ?)", [$counselorId]);
        $counselor->query("DELETE FROM consultation WHERE counselor_id = ?", [$counselorId]);
        $counselor->query("DELETE FROM consultation_requests WHERE counselor_id = ?", [$counselorId]);
        $counselor->query("DELETE FROM messages WHERE receiver_id = ? AND receiver_type = 'counselor'", [$counselorId]);
        $counselor->delete($counselorId, 'user_id');
        $user->delete($counselorId, 'user_id');
        SystemLogger::log('ACCOUNT_DELETION', 'counselor account(ID: ' . $counselorId . ') deleted from the database');
        redirect('dashboard');
    }

    if ($isAjax) {
        echo json_encode(['success' => true]);
        exit;
    }
    redirect('dashboard');
    exit;
}

if ($isManageCandidate) {
    $candidateId = $_POST['candidate_id'] ?? null;
    if (!$candidateId) {
        redirect('dashboard');
        exit;
    }

    $user = new User;
    $candidate = new Candidate;

    if (isset($_POST['deny'])) {
        $candidate->query("DELETE FROM bookmarks WHERE user_id = ?", [$candidateId]);
        $candidate->query("DELETE FROM interview_slots WHERE interview_id IN (SELECT interview_id FROM interviews WHERE candidate_id = ?)", [$candidateId]);
        $candidate->query("DELETE FROM interviews WHERE candidate_id = ?", [$candidateId]);
        $candidate->query("DELETE FROM consultation_slots WHERE meeting_id IN (SELECT meeting_id FROM consultation WHERE candidate_id = ?)", [$candidateId]);
        $candidate->query("DELETE FROM consultation WHERE candidate_id = ?", [$candidateId]);
        $candidate->query("DELETE FROM consultation_requests WHERE candidate_id = ?", [$candidateId]);
        $candidate->query("DELETE FROM cvTable WHERE candidate_id = ?", [$candidateId]);
        $candidate->query("DELETE FROM messages WHERE receiver_id = ? AND receiver_type = 'candidate'", [$candidateId]);
        $candidate->delete($candidateId, 'user_id');
        $user->delete($candidateId, 'user_id');
    }

    redirect('dashboard');
    exit;
}

if ($isManageCompany) {
    $companyId = $_POST['company_id'] ?? null;
    if (!$companyId) {
        redirect('dashboard');
        exit;
    }

    $user = new User;
    $company = new Company;

    if (isset($_POST['deny'])) {
        $company->query("DELETE FROM interview_slots WHERE interview_id IN (SELECT interview_id FROM interviews WHERE company_id = ?)", [$companyId]);
        $company->query("DELETE FROM interviews WHERE company_id = ?", [$companyId]);
        $company->query("DELETE FROM cvTable WHERE job_id IN (SELECT job_id FROM jobPost WHERE company_id = ?)", [$companyId]);
        $company->query("DELETE FROM bookmarks WHERE job_id IN (SELECT job_id FROM jobPost WHERE company_id = ?)", [$companyId]);
        $company->query("DELETE FROM jobPost WHERE company_id = ?", [$companyId]);
        $company->query("DELETE FROM messages WHERE receiver_id = ? AND receiver_type = 'company'", [$companyId]);
        $company->delete($companyId, 'user_id');
        $user->delete($companyId, 'user_id');
    }

    redirect('dashboard');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'dismissAlert') {

    $logId = $_POST['log_id'] ?? null;

    if (!$logId) {
        if ($isAjax) {
            echo json_encode(['success' => false, 'message' => 'No log ID']);
            exit;
        }
        redirect('dashboard');
        exit;
    }

    $admin = new Admin;

    $admin->query("DELETE FROM system_logs WHERE log_id = ?", [$logId]);

    SystemLogger::log('ALERT_DISMISSED', "Alert dismissed by Admin");

    if ($isAjax) {
        echo json_encode(['success' => true]);
        exit;
    }

    redirect('dashboard');
    exit;
}
