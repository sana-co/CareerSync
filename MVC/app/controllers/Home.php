<?php
class Home
{
    use Controller;
    use Database;
    public function index()
    {
        if (
            $_SERVER['REQUEST_METHOD'] === 'POST'
            && isset($_POST['action'])
            && $_POST['action'] === 'toggle_bookmark'
        ) {

            if (empty($_SESSION['USER'])) {
                echo json_encode(['status' => 'unauthorized']);
                exit;
            }

            $job_id  = $_POST['job_id'] ?? null;
            $user_id = $_SESSION['USER']->user_id;

            if (!$job_id) {
                echo json_encode(['status' => 'error']);
                exit;
            }

            $bookmark = new Bookmark();
            $existing = $bookmark->getBmStatus($user_id, $job_id);

            if ($existing) {
                $bookmark->removeBookmark($user_id, $job_id);
                echo json_encode(['status' => 'removed']);
            } else {
                $bookmark->addBookmark($user_id, $job_id);
                echo json_encode(['status' => 'added']);
            }

            exit;
        }
        //if not logged in the $username variable is deafulted to 'User'
        if (empty($_SESSION['USER'])) {
            $data['username'] = 'User';
        } else {
            if ($_SESSION['USER']->role !== 'company') {
                $data['username'] = $_SESSION['USER']->firstName;
            } else {
                $data['username'] = $_SESSION['USER']->hr_firstName;
            }
        }
        $user = new User;
        $user->CreateTables();

        $jobPost = new JobPost;
        $data['jobs'] = $jobPost->SelectAll();
        $data['cities'] = $jobPost->getJobLocations();

        $job = new JobPost;

        $filters = [
            'minSalary'  => $_GET['minSalary'] ?? null,
            'maxSalary'  => $_GET['maxSalary'] ?? null,
            'sortBy'     => $_GET['sortBy'] ?? null,
            'city'       => $_GET['city'] ?? null,
            'workMode'   => $_GET['workMode'] ?? null,
            'jobType'    => $_GET['jobType'] ?? null,
            'experience' => $_GET['experience'] ?? null,
        ];

        $data['jobs'] = $job->getFilteredJobs($filters);

        $this->view("home", $data);
    }
}
    #if you need to load a view, there must be a controller for that view in the controller folder calling the view functuon from the Controller class
    #And that will load a the [view name].view.php or it will load the error 404 view.
