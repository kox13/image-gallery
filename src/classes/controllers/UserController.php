<?php
require_once "Controller.php";
class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model('User');
    }

    public function login()
    {
        if(isset($_SESSION['user'])) {
            $this->redirect('/gallery?page=1');
            exit;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processLogin();
            $this->redirect('/user/login');
            exit;
        }

        $this->view->renderView('loginForm', 'form');
    }

    private function processLogin(){
        $username = $_POST['username'];
        $password = $_POST['password'];

        if(!$this->validateLoginInput($username, $password))
            return;

        $result = $this->model->query('users', 'loginUser', [$username, $password]);

        if ($result['success']) {
            $_SESSION['user'] = $result['user'];
            $userId = $result['user']['id'];

            $_SESSION['favourites'] = $this->model->query('users', 'getFavourites', [$userId]);

            unset($result['user']);
        }

        $_SESSION['view_data'] = $result;
    }

    private function validateLoginInput($username, $password): bool
    {
        if (empty($username) || empty($password))
            $this->addError("Fields cannot be empty");

        if(empty($_SESSION['view_data']))
            return true;

        return false;
    }

    public function register()
    {
        if(isset($_SESSION['user'])) {
            $this->redirect('/gallery?page=1');
            exit;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processRegister();
            $this->redirect('/user/register');
            exit;
        }

        $this->view->renderView('registerForm', 'form');
    }

    private function processRegister(){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $repeatPassword = $_POST['repeatPassword'];
        $email = $_POST['email'];

        if($this->validateRegisterInput($username, $password, $repeatPassword, $email))
            $_SESSION['view_data'] = $this->model->query('users', 'registerUser', [$username, $password, $email]);
    }

    private function validateRegisterInput($username, $password, $repeatPassword, $email): bool
    {
        if (empty($username) || empty($password) || empty($repeatPassword) || empty($email))
            $this->addError('Fields cannot be empty');
        else {
            if ($password !== $repeatPassword)
                $this->addError("Passwords don't match");

            if ($this->alreadyExists($username, 'username'))
                $this->addError("Username already taken");

            if ($this->alreadyExists($email, 'email'))
                $this->addError("Email already taken");
        }

        if(empty($_SESSION['view_data']))
            return true;

        return false;
    }

    public function logout(){
        if(empty($_SESSION['user'])){
            $this->redirect('/user/login');
            exit;
        }

        unset($_SESSION['user']);
        unset($_SESSION['favourites']);

        $_SESSION['view_data'] = ['success' => true, 'message' => 'Logged out'];

        $this->redirect('/gallery?page=1');
    }

    public function addToFavourites()
    {
        if(empty($_SESSION['user'])){
            $this->redirect('/gallery');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $imageIds = $_POST['favourites'] ?? [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($userId) || empty($imageIds)) {
                $this->addError("You didn't choose any images");
                $this->redirect('/gallery');
                exit;
            }

            $_SESSION['view_data'] = $this->model->query('users', 'addFavourites', [$imageIds, $userId]);
            $_SESSION['favourites'] = $this->model->query('users', 'getFavourites', [$userId]);
        }

        $this->redirect('/gallery/favourites');
    }

    private function alreadyExists($value, $field): bool
    {
        $user = $this->model->getUser($value, $field);

        if (!empty($user))
            return true;

        return false;
    }

    public function deleteFromFavourites(){
        if(empty($_SESSION['user'])){
            $this->redirect('/gallery');
            exit;
        }

        $toDelete = $_POST['favourites'] ?? null;

        if(empty($toDelete)){
            $this->addError("You didn't choose any images");
            $this->redirect('/gallery/favourites');
            exit;
        }

        $userId = $_SESSION['user']['id'];

        $_SESSION['view_data'] = $this->model->query('users', 'deleteFavourites', [$toDelete, $userId]);
        $_SESSION['favourites'] = $this->model->query('users', 'getFavourites', [$userId]);

        $this->redirect('/gallery/favourites');
    }
}