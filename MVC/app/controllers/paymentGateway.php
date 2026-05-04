<?php
class PaymentGateway
{
    use Controller;

    public function index()
    {
        if (!isset($_SESSION['USER'])) {
            redirect('login');
        }

        $data = [
            'username'    => $_SESSION['USER']->email,
            'amount'      => 5000.00,
            'description' => 'CareerSync Company Subscription'
        ];

        $this->view("paymentGateway", $data);
    }

    public function hash()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method Not Allowed']);
            exit;
        }

        $merchant_id = "1233775"; // Your sandbox merchant ID
        $merchant_secret = "Mjg0MDc2MzEyMDIxNjA3NjY2NzIyNjY1NjU5NTYzNDE0NzUwMDM5Nw=="; // Your sandbox secret

        $order_id = $_POST['order_id'] ?? '';
        $amount = number_format($_POST['amount'] ?? 0, 2, '.', '');
        $currency = "LKR";

        $hash = strtoupper(
            md5(
                $merchant_id .
                    $order_id .
                    $amount .
                    $currency .
                    strtoupper(md5($merchant_secret))
            )
        );

        echo json_encode(["hash" => $hash]);
        exit;
    }

    public function success()
    {
        redirect('dashboard');
    }

    public function notify()
    {
        $merchant_id     = $_POST['merchant_id'] ?? '';
        $order_id        = $_POST['order_id'] ?? '';
        $payhere_amount  = $_POST['payhere_amount'] ?? '';
        $payhere_currency = $_POST['payhere_currency'] ?? '';
        $status_code     = $_POST['status_code'] ?? '';
        $md5sig          = $_POST['md5sig'] ?? '';

        $merchant_secret = "Mjg0MDc2MzEyMDIxNjA3NjY2NzIyNjY1NjU5NTYzNDE0NzUwMDM5Nw==";

        $local_md5sig = strtoupper(
            md5(
                $merchant_id .
                    $order_id .
                    $payhere_amount .
                    $payhere_currency .
                    $status_code .
                    strtoupper(md5($merchant_secret))
            )
        );

        if ($local_md5sig === $md5sig && $status_code == 2) {
            $parts = explode('_', $order_id);
            $user_id = end($parts);

            $company = new Company();
            $company->activateSubscription($user_id,'TXN_' . uniqid());
        }

        http_response_code(200);
    }
}
