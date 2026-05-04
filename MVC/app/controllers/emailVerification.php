<?php
class EmailVerification
{
    use Controller;

    // Shows "Please check your email" page after registration
    // URL: /emailverification/pending
    public function pending()
    {
        $data = [];
        $this->view('email_verify_pending', $data);
    }

    // Handles the link the user clicks in their email
    // URL: /emailverification/verify?token=XXXX
    public function verify()
    {
        $token = $_GET['token'] ?? '';
        $data  = ['success' => false, 'message' => ''];

        if (empty($token)) {
            $data['message'] = 'Invalid verification link. No token provided.';
            $this->view('email_verified', $data);
            return;
        }

        $user = new User();
        $row  = $user->first(['verification_token' => $token]);

        if (!$row) {
            $data['message'] = 'This verification link is invalid or has already been used.';
            $this->view('email_verified', $data);
            return;
        }

        if (strtotime($row->token_expires_at) < time()) {
            $data['message'] = 'This verification link has expired. Please use the resend option below.';
            $this->view('email_verified', $data);
            return;
        }

        if ($row->email_verified == 1) {
            $data['success'] = true;
            $data['message'] = 'Your email is already verified. You can log in.';
            $this->view('email_verified', $data);
            return;
        }

        $updated = $user->update($row->user_id, [
        'email_verified'     => 1,
        'verification_token' => '',
        'token_expires_at'   => '2000-01-01 00:00:00',
    ], 'user_id');

        if ($updated) {
            SystemLogger::log('EMAIL_VERIFIED', 'User (' . $row->user_id . ') verified their email');
            $data['success'] = true;
            $data['message'] = 'Your email has been successfully verified! You can now log in.';
        } else {
            $data['message'] = 'Something went wrong. Please try again or contact support.';
        }

        $this->view('email_verified', $data);
    }

    // Resends a fresh verification email
    // URL: /emailverification/resend  (POST)
    public function resend()
    {
        $data = ['sent' => false, 'message' => ''];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('login');
            exit;
        }

        $email = trim($_POST['email'] ?? '');

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $data['message'] = 'Please enter a valid email address.';
            $this->view('email_verify_pending', $data);
            return;
        }

        $user = new User();
        $row  = $user->first(['email' => $email]);

        if (!$row) {
            $data['sent']    = true;
            $data['message'] = 'If that email is registered, a new verification link has been sent.';
            $this->view('email_verify_pending', $data);
            return;
        }

        if ($row->email_verified == 1) {
            $data['sent']    = true;
            $data['message'] = 'This email is already verified. Please log in.';
            $this->view('email_verify_pending', $data);
            return;
        }

        $token   = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+24 hours'));

        $user->update($row->user_id, [
            'verification_token' => $token,
            'token_expires_at'   => $expires,
        ], 'user_id');

        require_once __DIR__ . '/../core/Mailer.php';
        Mailer::sendVerificationEmail($email, $token);

        SystemLogger::log('EMAIL_RESENT', 'Verification email resent to ' . $email);

        $data['sent']    = true;
        $data['message'] = 'A new verification link has been sent to your email address.';
        $this->view('email_verify_pending', $data);
    }
}