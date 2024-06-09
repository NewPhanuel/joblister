<?php
declare(strict_types=1);

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;

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
     * @param array $params
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

    /**
     * Inserts listing from the user into database
     *
     * @return void
     */
    public function store(): void
    {
        $allowedFields = ['title', 'description', 'salary', 'tags', 'requirements', 'benefits', 'company', 'address', 'city', 'state', 'phone', 'email'];
        $newListingData = array_intersect_key($_POST, array_flip($allowedFields));
        $newListingData['user_id'] = 1;
        $newListingData = array_map('sanitize', $newListingData);

        $requiredFields = ['title', 'description', 'salary', 'email', 'city', 'state'];
        $errors = [];

        foreach ($requiredFields as $field) {
            if (empty($newListingData[$field]) or !Validation::string($newListingData[$field])) {
                $errors[$field] = ucfirst($field . ' is required');
            }
        }

        if (!empty($errors)) {
            loadView('listings/create', [
                'errors' => $errors,
                'listings' => $newListingData,
            ]);
        } else {
            // Submit data to database
            $fields = [];

            foreach ($newListingData as $field => $value) {
                // Replace empty strings with null
                if ($value === '') {
                    $newListingData[$field] = null;
                }
                $fields[] = $field;
                $values[] = ':' . $field;
            }

            $fields = implode(', ', $fields);
            $values = implode(', ', $values);

            // Query
            $sql = "INSERT INTO listings ({$fields}) VALUES ({$values})";
            $this->db->query($sql, $newListingData);

            $_SESSION['success_message'] = 'Listing Added Successfully';
            redirect('/listings');
        }
    }

    /**
     * Deletes a listing from the database
     *
     * @param array $params
     * @return void
     */
    public function destroy(array $params): void
    {
        $id = $params['id'];

        $params = [
            'id' => $id,
        ];
        $sql = 'SELECT * FROM listings WHERE id = :id';

        $listing = $this->db->query($sql, $params)->fetch();

        if (!$listing) {
            ErrorController::notFound('Listing does not exist');
            return;
        }

        $sql = 'DELETE FROM listings WHERE id = :id';
        $this->db->query($sql, $params);

        // Set flash message
        $_SESSION['success_message'] = 'Listing deleted successfully';

        redirect('/listings');
    }
}