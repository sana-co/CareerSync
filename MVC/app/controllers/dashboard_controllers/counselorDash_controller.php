<?php
//extract counselor data
$counselor = new Counselor;
$data['counselorTable'] = $counselor->first(['user_id' => $_SESSION['USER']->user_id]);

//check validity of counselor
$isRealCounselor = ($_SESSION['USER']->status != 'active') ? false : true;
$data['isRealCounselor'] = $isRealCounselor;

//extract meeting requests made by candidates
$request = new ConsultationRequest;
$data['request'] = $isRealCounselor ? $request->getMeetingRequest($_SESSION['USER']->user_id) : [];

$consultation = new Consultation;
$data['confirmedConsultation'] = $consultation->getConfirmedConsultationsForCounselor($_SESSION['USER']->user_id);

require_once 'C:/xampp/htdocs/CareerSync/MVC/app/models/message.php';
$messageModel = new Message();
$unreadResult = $messageModel->query(
    "SELECT COUNT(*) AS cnt FROM messages WHERE receiver_id = ? AND receiver_type = 'counselor' AND is_read = 0",
    [$_SESSION['USER']->user_id]
);
$data['unreadMsgCount'] = $unreadResult ? (int)$unreadResult[0]->cnt : 0;
$data['messages'] = $messageModel->getByReceiver($_SESSION['USER']->user_id, 'counselor');

$isSchedulingMeeting = ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'counselor_scheduler');

$photoPath = null;

//code for updating user profile 
if ($isProfileUpdate) {
    $errors = [];

    if (!password_verify($_POST['confirm_password'], $data['userTable']->password)) {
        $errors['confirm_password'] = "Incorrect password";
    }

    if (!empty($_FILES['counselor_photo_path']['name'])) {
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/CareerSync/MVC/public/assets/uploads/counselor_photos/';

        $filename = time() . '_' . basename($_FILES['counselor_photo_path']['name']);
        $target = $uploadDir . $filename;

        $allowed = ['jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors['counselor_photo_path'] = "Invalid file type. Only JPG, JPEG, PNG allowed.";
        } elseif (move_uploaded_file($_FILES['counselor_photo_path']['tmp_name'], $target)) {
            $photoPath = 'assets/uploads/counselor_photos/' . $filename;
            $_SESSION['USER']->photo_path = $photoPath;
        } else {
            $errors['counselor_photo_path'] = "Error uploading photo.";
        }
    }

    if (empty($errors)) {
        // Prepare counselor update array
        $counselorUpdate = [
            'firstName' => $_POST['firstName'] ?? '',
            'lastName' => $_POST['lastName'] ?? '',
            'contactNo' => $_POST['contactNo'] ?? '',
            'counselor_photo_path' => $photoPath ?? $data['counselorTable']->counselor_photo_path
        ];

        $counselor->update($_SESSION['USER']->user_id, $counselorUpdate, 'user_id');

        $updatedUser = $user->first(['user_id' => $_SESSION['USER']->user_id]);
        if ($updatedUser) {
            $_SESSION['USER'] = $updatedUser;
        }
        $_SESSION['USER']->firstName = $_POST['firstName']; //this is to fix an error in the home page. do this, or log out once edited profile
        $_SESSION['USER']->photo_path = $photoPath ?? $data['counselorTable']->counselor_photo_path; //need to fix this too. editing pfp and redirecting to a logged in home doesnt show the pfp
        //unset($_SESSION['USER']);//this loggs out after editing profile
        redirect('home');
        exit;
    }

    $data['errors'] = $errors;
}

if ($isSchedulingMeeting) {

    $candidate_id = $_POST['candidate_id'] ?? null;
    $counselor_id = $_SESSION['USER']->user_id;
    $mode = $_POST['medium'] ?? null;
    $address = $_POST['address'] ?? null;
    $details = $_POST['details'] ?? '';
    $slots = $_POST['slots'] ?? [];

    $reqModel = new ConsultationRequest();
    $reqRecord = $reqModel->first([
        'candidate_id' => $candidate_id,
        'counselor_id' => $counselor_id
    ]);

    if (!$reqRecord) {
        $_SESSION['error'] = "Meeting request not found.";
        redirect("dashboard/counselorDash");
        exit;
    }

    if (!$candidate_id || !$mode || !$address || empty($slots)) {
        $_SESSION['error'] = "Please fill in all required fields.";
        redirect("dashboard/counselorDash");
        exit;
    }

    $reqModel->update($reqRecord->request_id, [
        'counselor_acceptance' => 'accepted'
    ], 'request_id');


    $meetingModel = new Consultation();
    $meeting_id = $meetingModel->createMeeting([
        'request_id'   => $reqRecord->request_id,
        'candidate_id' => $candidate_id,
        'counselor_id' => $counselor_id,
        'mode'          => $mode,
        'address_link'  => $address,
        'extra_details' => $details
    ], $slots);

    $messageModel->insert([
        'receiver_id' => $candidate_id,
        'receiver_type' => 'candidate',
        'content' => "Your counselor accepted the meeting request. Please confirm the available time slot.",
    ]);

    $_SESSION['success'] = "Meeting scheduled. Waiting for candidate to confirm.";
    redirect("dashboard/counselorDash");
    exit;
}
