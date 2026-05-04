<?php
class Dashboard
{
    use Controller;

    public function index()
    {
        $data['email'] = !empty($_SESSION['USER']) ? $_SESSION['USER']->email : null;

        //with this method we could extract data from the user id across all tables
        $user = new User;
        $data['userTable'] = $user->first(['user_id' => $_SESSION['USER']->user_id]);

        $isPasswordChange = ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'password_change');
        $isProfileUpdate  = ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'profile_change');
        $isDeleteMessage = ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete_message');
        $isClearMessages = ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'clear_messages');
        $isDeleteAlert   = ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete_alert');

        if ($isDeleteMessage || $isClearMessages) {
            require_once __DIR__ . '/../models/message.php';
            require_once __DIR__ . '/../models/Alert.php';
            $messageModel = new Message();
            $alertModel = new Alert();
            $userId = $_SESSION['USER']->user_id;
            $userRole = $_SESSION['USER']->role;

            if ($isDeleteMessage) {
                $messageId = intval($_POST['message_id'] ?? 0);
                if ($messageId > 0) {
                    $message = $messageModel->query('SELECT * FROM messages WHERE id = ?', [$messageId]);
                    if (!empty($message)) {
                        $existingMessage = $message[0];
                        if ($existingMessage->receiver_id == $userId && $existingMessage->receiver_type === $userRole) {
                            $messageModel->delete($messageId);
                        }
                    }
                }
            }

            if ($isClearMessages) {
                $messageModel->query('DELETE FROM messages WHERE receiver_id = ? AND receiver_type = ?', [$userId, $userRole]);
                $alertModel->query('DELETE FROM alerts');
                if ($userRole === 'admin') {
                    require_once __DIR__ . '/../models/admin.php';
                    $adminModel = new Admin();
                    $adminModel->deleteAllLogs();
                }
            }

            // after deletion action redirect to avoid resubmit-on-refresh
            redirect('dashboard');
            exit;
        }

        if ($isDeleteAlert) {
            $alertId = intval($_POST['alert_id'] ?? 0);
            $alertSource = $_POST['alert_source'] ?? 'alerts';
            if ($alertId > 0) {
                if ($alertSource === 'system_logs') {
                    $admin = new Admin();
                    $admin->query("DELETE FROM system_logs WHERE log_id = ?", [$alertId]);
                } else {
                    require_once __DIR__ . '/../models/Alert.php';
                    $alertModel = new Alert();
                    $alertModel->deleteAlert($alertId);
                }
            }
            redirect('dashboard');
            exit;
        }

        require_once __DIR__ . '/../models/Alert.php';
        $alertModel = new Alert();
        $data['alerts'] = $alertModel->getUnreadAlerts();

        // Load sysAlerts for admin role
        if ($_SESSION['USER']->role === 'admin') {
            require_once __DIR__ . '/../models/admin.php';
            $adminModel = new Admin();
            $data['sysAlerts'] = $adminModel->getSysAlerts();
        }

        switch ($_SESSION['USER']->role) {
            case 'admin':
                include("dashboard_controllers/adminDash_controller.php");
                break;
            case 'candidate':
                include("dashboard_controllers/candidateDash_controller.php");
                break;

            case 'company':
                include("dashboard_controllers/companyDash_controller.php");
                break;

            case 'validator':
                include("dashboard_controllers/validatorDash_controller.php");
                break;

            case 'counselor':
                include("dashboard_controllers/counselorDash_controller.php");
                break;
        }
        if ($isPasswordChange) {
            $pw_errors = [];

            if (!password_verify($_POST['oldPassword'], $data['userTable']->password)) {
                $pw_errors['oldPassword'] = "Incorrect Password";
            } else if ($_POST['newPassword'] !== $_POST['confirm_new_password']) {
                $pw_errors['confirm_new_password'] = "Passwords do not match";
            }

            if (empty($pw_errors)) {
                // Prepare user update array
                if (!empty($_POST['newPassword'])) {
                    $userUpdate['password'] = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
                }
                $user->update($_SESSION['USER']->user_id, $userUpdate, 'user_id');

                $updatedUser = $user->first(['user_id' => $_SESSION['USER']->user_id]);
                if ($updatedUser) {
                    $_SESSION['USER'] = $updatedUser;
                }
                $_SESSION['USER']->firstName = $_POST['firstName']; //this is to fix an error in the home page. do this, or log out once edited profile
                switch ($_SESSION['USER']->role) {
                    case 'admin':
                        $_SESSION['USER']->photo_path = $photoPath ?? $data['adminTable']->admin_photo_path;
                        break;
                    case 'company':
                        $_SESSION['USER']->photo_path = $photoPath ?? $data['companyTable']->company_photo_path;
                        break;
                    case 'counselor':
                        $_SESSION['USER']->photo_path = $photoPath ?? $data['counselorTable']->counselor_photo_path;
                        break;
                    case 'validator':
                        $_SESSION['USER']->photo_path = $photoPath ?? $data['validatorTable']->validator_photo_path;
                        break;
                    case 'candidate':
                        $_SESSION['USER']->photo_path = $photoPath ?? $data['candidateTable']->candidate_photo_path;
                        break;
                }
                SystemLogger::log('CHANGED_PASSWORD','ID: '.$_SESSION['USER']->user_id.' changed account password');
                //unset($_SESSION['USER']);//this loggs out after editing profile
                redirect('dashboard');
                exit;
            }

            $data['errors'] = $pw_errors;
        }

        $this->view("dashboard", $data);  // loads dashboard.view.php
    }

}

