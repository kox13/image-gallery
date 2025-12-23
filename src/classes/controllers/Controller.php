<?php
require_once __DIR__ . '/../view/View.php';
define("ROOT_DIR", $_SERVER['DOCUMENT_ROOT']);

class Controller
{
    protected $model;
    protected $view;

    public function __construct()
    {
        $isUserLoggedIn = isset($_SESSION['user']);
        $this->view = new View($isUserLoggedIn);
    }

    protected function model($model)
    {
        require_once __DIR__ . "/../models/{$model}Model.php";
        $modelClass = $model . 'Model';
        $this->model = new $modelClass;
    }

    protected function redirect($path)
    {
        header("Location: $path");
        exit;
    }

    protected function addError($error)
    {
        if (empty($_SESSION['view_data']))
            $_SESSION['view_data'] = ['success' => false, 'error' => $error];
        else {
            if (empty($_SESSION['view_data']['error']))
                $_SESSION['view_data']['error'] = $error;
            else
                $_SESSION['view_data']['error'] .= '. ' . $error;

            if (empty($_SESSION['view_data']['success']))
                $_SESSION['view_data']['success'] = false;
        }
    }

    protected function isSuccessful($query)
    {
        if(!isset($query['success']))
            return true;
        else {
            if(empty($_SESSION['view_data']))
                $_SESSION['view_data'] = $query;
            else
                $_SESSION['view_data'] += $query;

            return false;
        }
    }
}