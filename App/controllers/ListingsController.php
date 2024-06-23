<?php
declare(strict_types=1);

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;
use Framework\Session;
use Framework\Authorize;

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
        $sql = 'SELECT * FROM listings ORDER BY created_at DESC';
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
            ErrorController::notFound("Listing Not Found!");
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
        $newListingData['user_id'] = Session::get('user')['id'];
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

            Session::setFlash('success_message', 'Listing Created Successfully');
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

        // Check if listing exists
        if (!$listing) {
            ErrorController::notFound('Listing does not exist');
            return;
        }

        // Check if user owns the listing
        if (!Authorize::isOwner($listing->user_id)) {
            Session::setFlash('error_message', 'You are not authorised to delete this listing');
            redirect('/listings/' . $id);
            return;
        }

        $sql = 'DELETE FROM listings WHERE id = :id';
        $this->db->query($sql, $params);

        // Set flash message
        Session::setFlash('success_message', 'Listing deleted successfully');

        redirect('/listings');
    }

    /**
     * Loads the edit form where a post can be edited
     *
     * @param array $params
     * @return void
     */
    public function edit(array $params): void
    {
        $id = $params['id'];
        $sql = 'SELECT * FROM listings WHERE id = :id';
        $params = ["id" => $id];

        $listing = $this->db->query($sql, $params)->fetch();

        // Check if listing exists
        if (!$listing) {
            ErrorController::notFound('Listing Not Found!');
            return;
        }

        // Check if user owns the listing
        if (!Authorize::isOwner($listing->user_id)) {
            Session::setFlash('error_message', 'You are not authorised to edit this listing');
            redirect('/listings/' . $id);
            return;
        }

        loadView('/listings/edit', ['listing' => $listing]);
    }

    /**
     * Updates the listing on the edit form 
     *
     * @param array $params
     * @return void
     */
    public function update(): void
    {
        // Get fields
        $allowedFields = ['id', 'title', 'description', 'salary', 'tags', 'requirements', 'benefits', 'company', 'address', 'city', 'state', 'phone', 'email'];
        $editedListingData = array_intersect_key($_POST, array_flip($allowedFields));
        $editedListingData = array_map('sanitize', $editedListingData);

        $params = ['id' => $editedListingData['id']];
        $sql = 'SELECT * FROM listings WHERE id = :id';
        $listing = $this->db->query($sql, $params)->fetch();

        // Check if listing exists
        if (!$listing) {
            ErrorController::notFound('Listing Not Found!');
            return;
        }

        // Check if user owns the listing
        if (!Authorize::isOwner($listing->user_id)) {
            Session::setFlash('error_message', 'You are not authorised to edit this listing');
            redirect('/listings/' . $params['id']);
            return;
        }

        $requiredFields = ['title', 'description', 'salary', 'email', 'city', 'state'];
        $errors = [];

        foreach ($requiredFields as $field) {
            if (empty($editedListingData[$field]) or !Validation::string($editedListingData[$field])) {
                $errors[$field] = ucfirst($field) . ' is required';
            }
        }

        if (!empty($errors)) {
            loadView('/listings/edit', [
                'errors' => $errors,
                'listing' => (object) $editedListingData,
            ]);
            exit;
        } else {
            $data = [];

            foreach ($editedListingData as $field => $value) {
                if ($value === '') {
                    $editedListingData[$field] = null;
                }
                $data[] = "{$field} = :{$field}";
            }

            $data = implode(', ', $data);

            $sql = "UPDATE listings SET {$data} WHERE id = :id";
            $this->db->query($sql, $editedListingData);

            Session::setFlash('success_message', 'Listing Updated Successfully');
            redirect("/listings/{$editedListingData['id']}");
        }
    }
}