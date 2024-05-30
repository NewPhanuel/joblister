<?php
declare(strict_types=1);

namespace App\Controllers;

use Framework\Database;

class ListingsController
{
    protected Database $db;


    /**
     * Constructor function  of the ListingsController class
     * 
     * Basically just instantiates the database
     */
    public function __construct()
    {
        $config = require basePath("config/db.php");
        $this->db = new Database($config);
    }

    /**
     * Loads the index page of the listings route
     * 
     * Basically shows all listings
     * 
     * @return void
     */
    public function index(): void
    {
        $sql = 'SELECT * FROM listings';
        $listings = $this->db->query($sql)->fetchAll();
        loadView('/listings/index', ['listings' => $listings]);
    }

    /**
     * Loads the listings create page
     *
     * Basically shows the create listing form
     * 
     * @return void
     */
    public function create(): void
    {
        loadView('/listings/create');
    }

    /**
     * Loads the details page of a job listing
     *
     * Basically shows a single listing
     * 
     * @return void
     */
    public function show(array $params): void
    {
        $id = $params['id'];
        $sql = 'SELECT * FROM listings WHERE id = :id';
        $params = ["id" => $id];

        $listing = $this->db->query($sql, $params)->fetch();

        // Check if listing exists 
        if (!$listing) {
            ErrorController::notFound("Listing not Found!");
            return;
        }
        loadView("/listings/show", ["listing" => $listing]);
    }
}