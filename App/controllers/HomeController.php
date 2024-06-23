<?php
declare(strict_types=1);

namespace App\Controllers;

use Framework\Database;

class HomeController
{
    protected Database $db;

    /**
     * Constructor for the HomeController class
     * 
     * Basically justs instatiates the database class
     */
    public function __construct()
    {
        $config = require basePath("config/db.php");
        $this->db = new Database($config);
    }

    /**
     * Loads the index page of the app
     *
     * Baiscally just shows the six latest listings
     * 
     * @return void
     */
    public function index(): void
    {
        $sql = "SELECT * FROM listings ORDER BY created_at DESC LIMIT 6";
        $listings = $this->db->query($sql)->fetchAll();
        loadView('home', [
            'listings' => $listings,
        ]);
    }
}