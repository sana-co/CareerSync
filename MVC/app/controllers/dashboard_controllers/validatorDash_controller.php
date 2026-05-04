<?php
//extract validator data
$validator = new Validator;
$cv = new CV;

$isRealValidator = ($_SESSION['USER']->status != 'active') ? false : true;
$data['isRealValidator'] = $isRealValidator;
$data['validatorTable'] = $validator->first(['user_id' => $_SESSION['USER']->user_id]);
$data['companyDetails'] = $isRealValidator ? $validator->getCompanyDetails() : [];

$is_Validating_CV  = ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'validateCV');
$is_Validating_Company  = ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'validateCompany');

$photoPath = null;

//code for updating user profile 
if ($isProfileUpdate) {
    $errors = [];

    if (!password_verify($_POST['confirm_password'], $data['userTable']->password)) {
        $errors['confirm_password'] = "Incorrect password";
    }
    if (!empty($_FILES['validator_photo_path']['name'])) {
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/CareerSync/MVC/public/assets/uploads/validator_photos/';

        $filename = time() . '_' . basename($_FILES['validator_photo_path']['name']);
        $target = $uploadDir . $filename;

        $allowed = ['jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors['validator_photo_path'] = "Invalid file type. Only JPG, JPEG, PNG allowed.";
        } elseif (move_uploaded_file($_FILES['validator_photo_path']['tmp_name'], $target)) {
            $photoPath = 'assets/uploads/validator_photos/' . $filename;
            $_SESSION['USER']->photo_path = $photoPath;
        } else {
            $errors['validator_photo_path'] = "Error uploading photo.";
        }
    }

    if (empty($errors)) {
        $validatorUpdate = [
            'firstName' => $_POST['firstName'] ?? '',
            'lastName'  => $_POST['lastName'] ?? '',
            'contactNo' => $_POST['contactNo'] ?? '',
            'validator_photo_path' => $photoPath ?? $data['validatorTable']->validator_photo_path
        ];

        $validator->update($_SESSION['USER']->user_id, $validatorUpdate, 'user_id');

        $updatedUser = $user->first(['user_id' => $_SESSION['USER']->user_id]);
        if ($updatedUser) {
            $_SESSION['USER'] = $updatedUser;
        }
        $_SESSION['USER']->firstName  = $_POST['firstName'];
        $_SESSION['USER']->photo_path = $photoPath ?? $data['validatorTable']->validator_photo_path;
        redirect('home');
        exit;
    }

    $data['errors'] = $errors;
}

require_once 'C:/xampp/htdocs/CareerSync/MVC/app/models/jobPost.php';
require_once 'C:/xampp/htdocs/CareerSync/MVC/app/models/candidate.php';
require_once 'C:/xampp/htdocs/CareerSync/MVC/app/models/message.php';

$messageModel = new Message();
$unreadResult = $messageModel->query(
    "SELECT COUNT(*) AS cnt FROM messages WHERE receiver_id = ? AND receiver_type = 'validator' AND is_read = 0",
    [$_SESSION['USER']->user_id]
);
$data['unreadMsgCount'] = $unreadResult ? (int)$unreadResult[0]->cnt : 0;

$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($is_Validating_CV) {
    $cv_id = $_POST['cv_id'] ?? null;

    if (isset($_POST['approve'])) {
        $status = 'approved';
    } elseif (isset($_POST['reject'])) {
        $status = 'rejected';
    } else {
        $status = null;
    }

    if ($cv_id && $status) {
        $cv->update($cv_id, ['validator_approval' => $status], 'cv_id');

        if ($status === 'approved') {
            $cvData      = $cv->first(['cv_id' => $cv_id]);
            $job_id      = $cvData->job_id;

            $jobPost     = new JobPost();
            $jobData     = $jobPost->first(['job_id' => $job_id]);
            $company_id  = $jobData->company_id;

            $candidateModel = new Candidate();
            $candidateData  = $candidateModel->first(['user_id' => $cvData->candidate_id]);
            $candidateName  = $candidateData->firstName . ' ' . $candidateData->lastName;

            $messageModel = new Message();
            $messageModel->insert([
                'receiver_id'   => $company_id,
                'receiver_type' => 'company',
                'content'       => "A candidate, {$candidateName}, has applied to your job '{$jobData->posTitle}' and has been validated.",
                'is_read'       => 0
            ]);
        } elseif ($status === 'rejected') {
            $cvData      = $cv->first(['cv_id' => $cv_id]);
            $candidate_id = $cvData->candidate_id;

            $messageModel = new Message();
            $messageModel->insert([
                'receiver_id'   => $candidate_id,
                'receiver_type' => 'candidate',
                'content'       => "Your CV has been rejected. Please ensure it contains accurate and proper information.",
                'is_read'       => 0
            ]);
        }

        if ($isAjax) {
            echo json_encode(['success' => true]);
            exit;
        }
        redirect('dashboard');
        exit;
    }

    // If cv_id or status is missing, return error for AJAX
    if ($isAjax) {
        echo json_encode(['success' => false, 'message' => 'Invalid request.']);
        exit;
    }
}

if ($is_Validating_Company) {
    $company_id = $_POST['company_id'] ?? null;
    if (!$company_id) {
        if ($isAjax) {
            echo json_encode(['success' => false, 'message' => 'No company ID provided.']);
            exit;
        }
        redirect('dashboard');
        exit;
    }

    $user    = new User;
    $company = new Company;

    if (isset($_POST['approve'])) {
        $company->update(
            $company_id,
            ['validator_approval' => 'approved'],
            'user_id'
        );
    } elseif (isset($_POST['reject'])) {
        $company->delete($company_id, 'user_id');
        $user->delete($company_id, 'user_id');
    }

    if ($isAjax) {
        echo json_encode(['success' => true]);
        exit;
    }
    redirect('dashboard');
    exit;
}

$data['applications'] = $isRealValidator ? $cv->getValidatorUnapprovedCv() : [];