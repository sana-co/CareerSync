<?php
class register
{
    use Controller;

    private function addWelcomeMessage(int $receiverId, string $receiverType): void
    {
        require_once __DIR__ . '/../models/message.php';
        $message = new Message();
        $message->insert([
            'receiver_id'   => $receiverId,
            'receiver_type' => $receiverType,
            'content'       => 'Your account has been successfully created. You can now explore all the features available to you.',
            'is_read'       => 0,
        ]);
    }

    public function index()
    {
        $user = new User;
        $data = [];

        $data['username'] = empty($_SESSION['USER']) ? 'User' : $_SESSION['USER']->email;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $role = $_GET['role'] ?? null;
            if ($role) {
                $_POST['role'] = $role;
            }

            $email_existing = $user->first(['email' => $_POST['email']]);
            if ($email_existing) {
                $user->errors['email'] = "Email already exists";
            } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $user->errors['email'] = "Please enter a valid email address.";
            } else if ($_POST['confirm_password'] !== $_POST['password']) {
                $user->errors['confirm_password'] = "passwords do not match";
            } else {
                $userTableData = $_POST;
                $userTableData["password"] = password_hash($userTableData["password"], PASSWORD_DEFAULT);

                switch ($role) {
                    case 'validator':
                        $validator = new Validator;

                        $upload_path = 'assets/uploads/validator_photos/';
                        $filename = time() . '_' . basename($_FILES['validator_photo_path']['name']);
                        $photo_target = $upload_path . $filename;

                        if (move_uploaded_file($_FILES['validator_photo_path']['tmp_name'], $photo_target)) {
                            $user->insert($userTableData);
                            $newUser = $user->first(['email' => $_POST['email']]);

                            $validatorData = [
                                'user_id'              => $newUser->user_id,
                                'firstName'            => $_POST['firstName'],
                                'lastName'             => $_POST['lastName'],
                                'contactNo'            => $_POST['contactNo'],
                                'validator_photo_path' => $photo_target,
                            ];
                            $validator->insert($validatorData);

                            $fullName = $_POST['firstName'] . " " . $_POST['lastName'];

                            // Generate verification token and send email
                            require_once __DIR__ . '/../core/Mailer.php';
                            $token   = bin2hex(random_bytes(32));
                            $expires = date('Y-m-d H:i:s', strtotime('+24 hours'));
                            $user->update($newUser->user_id, ['verification_token' => $token, 'token_expires_at' => $expires], 'user_id');
                            Mailer::sendVerificationEmail($_POST['email'], $token);

                            $this->addWelcomeMessage($newUser->user_id, 'validator');
                            SystemLogger::log('VALIDATOR_REGISTERED', '(' . $newUser->user_id . ')' . $fullName . ' registered');

                            redirect('emailverification/pending');
                            exit;
                        } else {
                            $user->errors['validator_photo_path'] = "Failed to upload profile picture";
                        }
                        break;

                    case 'company':
                        $company = new Company;

                        $photo_upload_path       = 'assets/uploads/company_logos/';
                        $certificate_upload_path = 'assets/uploads/business_certificates/';

                        $logo_filename        = time() . '_' . basename($_FILES['company_photo_path']['name']);
                        $certificate_filename = time() . '_' . basename($_FILES['business_certificate']['name']);
                        $photo_target         = $photo_upload_path . $logo_filename;
                        $certificate_target   = $certificate_upload_path . $certificate_filename;

                        if (
                            move_uploaded_file($_FILES['company_photo_path']['tmp_name'], $photo_target) &&
                            move_uploaded_file($_FILES['business_certificate']['tmp_name'], $certificate_target)
                        ) {
                            $user->insert($userTableData);
                            $newUser = $user->first(['email' => $_POST['email']]);

                            $companyData = [
                                'user_id'              => $newUser->user_id,
                                'companyName'          => $_POST['companyName'],
                                'contactNo'            => $_POST['contactNo'],
                                'hr_firstName'         => $_POST['hr_firstName'],
                                'hr_lastName'          => $_POST['hr_lastName'],
                                'hr_email'             => $_POST['hr_email'],
                                'hr_contactNo'         => $_POST['hr_contactNo'],
                                'company_photo_path'   => $photo_target,
                                'business_certificate' => $certificate_target,
                            ];
                            $company->insert($companyData);

                            // Generate verification token and send email
                            require_once __DIR__ . '/../core/Mailer.php';
                            $token   = bin2hex(random_bytes(32));
                            $expires = date('Y-m-d H:i:s', strtotime('+24 hours'));
                            $user->update($newUser->user_id, ['verification_token' => $token, 'token_expires_at' => $expires], 'user_id');
                            Mailer::sendVerificationEmail($_POST['email'], $token);

                            $this->addWelcomeMessage($newUser->user_id, 'company');
                            SystemLogger::log('COMPANY_REGISTERED', '(' . $newUser->user_id . ')' . $_POST['companyName'] . ' registered');

                            redirect('emailverification/pending');
                            exit;
                        } else {
                            $user->errors['file_upload'] = "Failed to upload file";
                        }
                        break;

                    case 'counselor':
                        $counselor = new Counselor;

                        $photo_upload_path       = 'assets/uploads/counselor_photos/';
                        $certificate_upload_path = 'assets/uploads/counselor_certificates/';

                        $photo_filename       = time() . '_' . basename($_FILES['counselor_photo_path']['name']);
                        $certificate_filename = time() . '_' . basename($_FILES['certificate']['name']);
                        $photo_target         = $photo_upload_path . $photo_filename;
                        $certificate_target   = $certificate_upload_path . $certificate_filename;

                        if (
                            move_uploaded_file($_FILES['counselor_photo_path']['tmp_name'], $photo_target) &&
                            move_uploaded_file($_FILES['certificate']['tmp_name'], $certificate_target)
                        ) {
                            $user->insert($userTableData);
                            $newUser = $user->first(['email' => $_POST['email']]);

                            $counselorData = [
                                'user_id'              => $newUser->user_id,
                                'firstName'            => $_POST['firstName'],
                                'lastName'             => $_POST['lastName'],
                                'contactNo'            => $_POST['contactNo'],
                                'counselor_photo_path' => $photo_target,
                                'certificate_path'     => $certificate_target,
                            ];
                            $counselor->insert($counselorData);

                            $fullName = $_POST['firstName'] . " " . $_POST['lastName'];

                            // Generate verification token and send email
                            require_once __DIR__ . '/../core/Mailer.php';
                            $token   = bin2hex(random_bytes(32));
                            $expires = date('Y-m-d H:i:s', strtotime('+24 hours'));
                            $user->update($newUser->user_id, ['verification_token' => $token, 'token_expires_at' => $expires], 'user_id');
                            Mailer::sendVerificationEmail($_POST['email'], $token);

                            $this->addWelcomeMessage($newUser->user_id, 'counselor');
                            SystemLogger::log('COUNSELOR_REGISTERED', '(' . $newUser->user_id . ')' . $fullName . ' registered');

                            redirect('emailverification/pending');
                            exit;
                        } else {
                            $user->errors['file_upload'] = "Failed to upload proof or certificate file";
                        }
                        break;

                    case 'candidate':
                        $candidate = new Candidate;

                        $upload_path  = 'assets/uploads/candidate_photos/';
                        $filename     = time() . '_' . basename($_FILES['candidate_photo_path']['name']);
                        $photo_target = $upload_path . $filename;

                        $now = new DateTime();
                        $dob = DateTime::createFromFormat('Y-m-d', $_POST['dob']);
                        if (!$dob || $dob > $now) {
                            $user->errors['dob'] = "Please enter a valid Birth date";
                        } else {
                            if (move_uploaded_file($_FILES['candidate_photo_path']['tmp_name'], $photo_target)) {
                                $user->insert($userTableData);
                                $newUser = $user->first(['email' => $_POST['email']]);

                                $candidateData = [
                                    'user_id'              => $newUser->user_id,
                                    'firstName'            => $_POST['firstName'],
                                    'lastName'             => $_POST['lastName'],
                                    'DOB'                  => $_POST['dob'],
                                    'address'              => $_POST['address'],
                                    'contactNo'            => $_POST['contactNo'],
                                    'candidate_photo_path' => $photo_target,
                                ];
                                $candidate->insert($candidateData);

                                $fullName = $_POST['firstName'] . " " . $_POST['lastName'];

                                // Generate verification token and send email
                                require_once __DIR__ . '/../core/Mailer.php';
                                $token   = bin2hex(random_bytes(32));
                                $expires = date('Y-m-d H:i:s', strtotime('+24 hours'));
                                $user->update($newUser->user_id, ['verification_token' => $token, 'token_expires_at' => $expires], 'user_id');
                                Mailer::sendVerificationEmail($_POST['email'], $token);

                                $this->addWelcomeMessage($newUser->user_id, 'candidate');
                                SystemLogger::log('CANDIDATE_REGISTERED', '(' . $newUser->user_id . ')' . $fullName . ' registered');

                                redirect('emailverification/pending');
                                exit;
                            } else {
                                $user->errors['candidate_photo_path'] = "Failed to upload profile picture";
                            }
                            break;
                        }
                }
            }
            $data['errors'] = $user->errors;
        }
        $this->view("register", $data);
    }
}