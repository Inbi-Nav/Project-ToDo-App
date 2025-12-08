<?php

class ApplicationController extends Controller
{
    protected $data = [];

    protected function render($view, $data = [])
    {
        extract($data);

        $file = VIEW_PATH . '/' . $view . '.phtml';

        if (!file_exists($file)) {
            echo "View not found: " . $file;
            return;
        }

        include $file;
    }

    protected function json($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

}
