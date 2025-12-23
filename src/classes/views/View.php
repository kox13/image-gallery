<?php
class View
{
    public $data = [];
    public $isUserLoggedIn;

    public function __construct($isUserLoggedIn)
    {
        $this->isUserLoggedIn = $isUserLoggedIn;
    }

    public function renderView($view, $stylesheet)
    {
        if (!empty($_SESSION['view_data'])) {
            $this->data += $_SESSION['view_data'];
            unset($_SESSION['view_data']);
        }

        $mainLayout = $this->mainLayout();
        $viewLayout = $this->viewLayout($view);
        $stylePath = "/static/styles/$stylesheet.css";
        $styles = "<link rel='stylesheet' href=$stylePath />";

        $completeView = str_replace('{{stylesheet}}', $styles, $mainLayout);
        echo str_replace('{{content}}', $viewLayout, $completeView);
    }

    private function mainLayout()
    {
        ob_start();
        include_once __DIR__ . "/../../views/main.php";
        return ob_get_clean();
    }

    private function viewLayout($view)
    {
        ob_start();
        include_once __DIR__ . "/../../views/$view.php";
        return ob_get_clean();
    }
}