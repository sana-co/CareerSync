<?php
class about
{
    use Controller;
    public function index()
    {
        //if not logged in the $username variable is deafulted to 'User'
        $data['username'] = empty($_SESSION['USER']) ? 'User' : $_SESSION['USER']->email;

        $this->view("about", $data);
    }
}
