<?php

class SystemLogger
{
    use Database;

    public static function log($action, $description = null, $status = 'SUCCESS')
    {
        $logger = new self();

        $query = "INSERT INTO system_logs
                  (user_id, role, action, description, ip_address, user_agent)
                  VALUES (?, ?, ?, ?, ?, ?)";

        $user_id = $_SESSION['USER']->user_id ?? 0;
        $role    = $_SESSION['USER']->role ?? 'guest';
        if($_SERVER['REMOTE_ADDR'] == "::1"){
            $_SERVER['REMOTE_ADDR'] = "localhost";
        }
        $data = [
            $user_id,
            $role,
            $action,
            $description ?? '',
            $_SERVER['REMOTE_ADDR'] ?? '',
            $_SERVER['HTTP_USER_AGENT'] ?? '',
        ];

        $logger->query($query, $data);
    }

    public function getActionList()
    {
        $query = "SELECT DISTINCT action 
                    FROM system_logs 
                    WHERE action IS NOT NULL 
                    AND action != '' 
                    ORDER BY action ASC";
        return $this->query($query);
    }
}
