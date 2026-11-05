<?php
namespace App\Controllers;

class HomeController
{
    public function index(): void
    {
        $pageTitle = 'Home';
        require dirname(__DIR__, 2) . '/views/home.php';
    }
}
