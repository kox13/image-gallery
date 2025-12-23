<?php
class View
{
    public $data = [];
    public $isUserLoggedIn;

    public function __construct($isUserLoggedIn)
    {
        $this->isUserLoggedIn = $isUserLoggedIn;
    }

    public function renderView($view, $stylesheet){
        if(!empty($_SESSION['view_data'])) {
            $this->data += $_SESSION['view_data'];
            unset($_SESSION['view_data']);
        }

        $mainLayout = $this->mainLayout();
        $viewLayout = $this->viewLayout($view);
        $stylePath = "/static/styles/$stylesheet.css";
        $styles = "<link rel='stylesheet' href=$stylePath />";
        $navElements = $this->determineNavElements();

        $completeView = str_replace('{{stylesheet}}', $styles, $mainLayout);
        $completeView = str_replace('{{content}}', $viewLayout, $completeView);
        echo str_replace('{{navElements}}', $navElements, $completeView);
    }

    private function mainLayout(){
        ob_start();
        include_once __DIR__ . "/../../views/main.php";
        return ob_get_clean();
    }

    private function viewLayout($view){
        ob_start();
        include_once __DIR__ . "/../../views/$view.php";
        return ob_get_clean();
    }

    private function determineNavElements(): string
    {
        if($this->isUserLoggedIn)
            return "
                <li><a href='/gallery/favourites?page=1'>Favourites</a></li>
                <li><a href='/gallery/my_images?page=1'>My Images</a></li>
                <li><a href='/user/logout'>Logout</a></li>
                ";

        return "
            <li><a href='/user/login'>Login</a></li>
            <li><a href='/user/register'>Register</a></li>
            ";
    }

    public function createImageCards($images)
    {
        $output = [];

        foreach($images as $image){
            ob_start();
            include_once __DIR__ . "/../../views/partials/imageCard.php";
            $output[] = ob_get_clean();
        }

        return $output;
    }
}