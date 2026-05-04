<?php
class systemLog
{
    use Controller;

    public function index()
    {
        $data['username'] = empty($_SESSION['USER']) ? 'User' : $_SESSION['USER']->email;

        $admin = new Admin;
        $data['syslogs'] = $admin->getSystemLogs();

        $sysLog = new SystemLogger;
        $data['actionList'] = $sysLog->getActionList();

        $this->view("systemLog", $data);
    }

    public function filter()
    {
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        if (!$isAjax) {
            header('Location: ' . ROOT . 'systemLog');
            exit;
        }

        $date_filter = $_GET['date_filter'] ?? 'all';
        $role_filter = $_GET['role_filter'] ?? 'all';
        $action_filter = $_GET['action_filter'] ?? 'all';

        try {
            $admin = new Admin;
            $syslogs = $admin->getFilteredLogs($date_filter, $role_filter, $action_filter);

            header('Content-Type: application/json');
            echo json_encode($syslogs ?: []);
        } catch (Exception $e) {
            error_log("Filter error: " . $e->getMessage());
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }

    public function clearLogs()
    {
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        if (!$isAjax) {
            header('Location: ' . ROOT . 'systemLog');
            exit;
        }

        try {
            $admin = new Admin;
            $admin->deleteAllLogs();

            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }
}
