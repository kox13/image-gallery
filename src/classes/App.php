<?php
require_once 'controllers/UserController.php';
require_once 'controllers/GalleryController.php';

class App
{
    private $routing = [
        '/user/login' => ['controller' => 'UserController', 'action' => 'login'],
        '/user/register' => ['controller' => 'UserController', 'action' => 'register'],
        '/user/logout' => ['controller' => 'UserController', 'action' => 'logout'],
        '/gallery' => ['controller' => 'GalleryController', 'action' => 'showAll'],
        '/gallery/add' => ['controller' => 'GalleryController', 'action' => 'add'],
        '/gallery/favourites' => ['controller' => 'GalleryController', 'action' => 'showFavourites'],
        '/favourites/add' => ['controller' => 'UserController', 'action' => 'addToFavourites'],
        '/favourites/delete' => ['controller' => 'UserController', 'action' => 'deleteFromFavourites'],
        '/gallery/my_images' => ['controller' => 'GalleryController', 'action' => 'showUsersImages']
    ];

    private $controller;
    private $action;

    public function run()
    {
        $this->prepareURL();

        if ($this->controller && $this->action) {
            $controllerInstance = new $this->controller;
            $controllerInstance->{$this->action}();
        } else {
            echo "Page not found 404";
            http_response_code(404);
            exit;
        }
    }

    private function prepareURL()
    {
        $request = $_SERVER['REQUEST_URI'];
        $path = $_SERVER['REDIRECT_URL'];

        if ($request === '/' || $request === '/gallery') {
            header("Location: /gallery?page=1");
            exit;
        } else if ($request === '/gallery/favourites') {
            header("Location: /gallery/favourites?page=1");
            exit;
        } else if ($request === '/gallery/my_images') {
            header("Location: /gallery/my_images?page=1");
            exit;
        }

        if (array_key_exists($path, $this->routing)) {
            $controllerInfo = $this->routing[$path];
            $this->controller = $controllerInfo['controller'];
            $this->action = $controllerInfo['action'];
        }
    }
}
