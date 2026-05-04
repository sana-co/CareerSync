<?php

class Contact
{
    use Controller;

    public function index()
    {
        $data = [];
        $data['username'] = empty($_SESSION['USER']) ? 'User' : ($_SESSION['USER']->email ?? 'User');
        $data['errors'] = [];
        $data['success'] = "";

        // Handle POST submit
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'sending_feedback') {

            require_once __DIR__ . '/../core/mailer.php';
            require_once __DIR__ . '/../models/ContactModel.php'; 

            $fromEmail = trim($_POST['email'] ?? '');
            $fromName  = trim($_POST['name'] ?? '');
            $message   = trim($_POST['message'] ?? '');

            // Validation
            if ($fromName === '' || strlen($fromName) < 2 || strlen($fromName) > 80) {
                $data['errors'][] = "Name must be between 2 and 80 characters.";
            }

            if (!filter_var($fromEmail, FILTER_VALIDATE_EMAIL) || strlen($fromEmail) > 120) {
                $data['errors'][] = "Please enter a valid email address.";
            }

            if ($message === '' || strlen($message) < 5 || strlen($message) > 3000) {
                $data['errors'][] = "Message must be between 5 and 3000 characters.";
            }

            // Prevent header injection (CRLF)
            if (preg_match("/[\r\n]/", $fromName) || preg_match("/[\r\n]/", $fromEmail)) {
                $data['errors'][] = "Invalid input detected.";
            }

            // Send if valid
            if (empty($data['errors'])) {

                $feedbackModel = new ContactModel();
                $feedbackModel->insert([
                    'name' => $fromName,
                    'email' => $fromEmail,
                    'message' => $message
                ]);

                $ok = Mailer::feedBackEmail($fromEmail, $fromName, $message);

                if ($ok) {
                    $data['success'] = "Message sent successfully. We will contact you soon.";
                } else {
                    $data['errors'][] = "Failed to send message. Try again later.";
                }
            }
        }

        $this->view("contact", $data);
    }
}