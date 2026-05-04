
<?php
class logout
{
    use Controller;
    public function index()
    {
        if (!empty($_SESSION['USER'])) {
            SystemLogger::log('LOGGED_OUT', 'UserID: '.$_SESSION['USER']->user_id.' logged out');
            unset($_SESSION['USER']);
        }
        redirect('home');
    }
}
