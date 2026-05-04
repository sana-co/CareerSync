<?php
trait Controller
{
    public function view($name, $data = [])
    {

        // Add global admin info
        $adminModel = new Admin;
        $admin = $adminModel->first([]); // fetch first admin

        $userModel = new User;
        $user = $userModel->first([]);
        $data['admin_email'] = $user->email;
        $data['admin_contact'] = $admin->contactNo;

        // Extract data to make variables available in view
        if (!empty($data)) {
            extract($data);
        }
        $filename = "../app/views/" . $name . ".view.php";
        if (file_exists($filename)) {
            require $filename;
        } else {
            $filename = "../app/views/404.view.php";
            require $filename;
        }
    }
}
