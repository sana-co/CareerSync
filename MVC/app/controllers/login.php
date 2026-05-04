<?php
class login
{
    use Controller;
    public function index()
    {
        $user = new User;
        $data = [];

        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = 0;
        }

        if (!isset($_SESSION['lockout_until'])) {
            $_SESSION['lockout_until'] = 0;
        }

        if ($_SESSION['lockout_until'] > 0 && time() >= $_SESSION['lockout_until']) {
            $_SESSION['lockout_until'] = 0;
        }

        $data['lockout_until'] = $_SESSION['lockout_until'];
        $data['is_locked'] = time() < $_SESSION['lockout_until'];

        $data['username'] = empty($_SESSION['USER']) ? 'User' : $_SESSION['USER']->firstName;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($data['is_locked']) {
                $remaining = $_SESSION['lockout_until'] - time();
                $data['errors']['password'] =
                    "Too many failed attempts. Try again in {$remaining} seconds.";
                $this->view("login", $data);
                return;
            }

            if (empty($_POST['email']) || empty($_POST['password'])) {
                $this->view("login", $data);
                return;
            }

            $row = $user->first(['email' => $_POST['email']]);
            if ($row) {
                if (password_verify($_POST["password"], $row->password)) {

                    // Block login if email is not verified
                    if (!$row->email_verified) {
                        $data['errors']['email'] = "Please verify your email address before logging in. Check your inbox for the verification link.";
                        $data['show_resend']     = true;
                        $data['resend_email']    = $_POST['email'];
                        $this->view("login", $data);
                        return;
                    }

                    $_SESSION['USER'] = $row;
                    switch ($row->role) {
                        case 'admin':
                            $admin = new Admin();
                            $extra = $admin->first(['user_id' => $row->user_id]);
                            $_SESSION['USER']->photo_path = $extra->admin_photo_path;
                            break;
                        case 'candidate':
                            $candidate = new Candidate();
                            $extra = $candidate->first(['user_id' => $row->user_id]);
                            $_SESSION['USER']->photo_path = $extra->candidate_photo_path;
                            break;
                        case 'counselor':
                            $counselor = new Counselor();
                            $extra = $counselor->first(['user_id' => $row->user_id]);
                            $_SESSION['USER']->photo_path = $extra->counselor_photo_path;
                            break;
                        case 'validator':
                            $validator = new Validator();
                            $extra = $validator->first(['user_id' => $row->user_id]);
                            $_SESSION['USER']->photo_path = $extra->validator_photo_path;
                            break;
                        case 'company':
                            $company = new Company();
                            $extra = $company->first(['user_id' => $row->user_id]);
                            $_SESSION['USER']->photo_path = $extra->company_photo_path;
                            break;
                        default:
                            $extra = null;
                    }

                    if ($extra && isset($extra->firstName)) {
                        $_SESSION['USER']->firstName = $extra->firstName;
                        $_SESSION['USER']->user_id   = $row->user_id;
                        $_SESSION['USER']->role      = $row->role;
                    }
                    if ($row->role == 'company') {
                        $_SESSION['USER']->hr_firstName = $extra->hr_firstName;
                    }
                    $_SESSION['USER']            = $row;
                    $_SESSION['login_attempts']  = 0;
                    $_SESSION['lockout_until']   = 0;
                    SystemLogger::log('LOGIN_SUCCESS', 'User logged in');
                    redirect('home');
                    exit;
                } else {
                    $_SESSION['login_attempts']++;
                    SystemLogger::log('LOGIN_FAILED', 'Invalid credentials');

                    if ($_SESSION['login_attempts'] >= 4) {
                        $_SESSION['lockout_until'] = time() + 3;
                        $_SESSION['login_attempts'] = 0;

                        SystemLogger::log(
                            'ALERT',
                            'Multiple failed login attempts by ' . $_POST['email']
                        );

                        $data['lockout_until'] = $_SESSION['lockout_until'];
                        $data['is_locked']     = true;
                        $data['errors']['password'] =
                            "Too many failed attempts. Please wait 30 seconds.";
                    } else {
                        $data['errors']['password'] = "Incorrect password";
                    }
                }
            } else {
                $data['errors']['email'] = "Email doesn't exist";
            }
        }

        $this->view("login", $data);
    }
}
