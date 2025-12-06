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
}
