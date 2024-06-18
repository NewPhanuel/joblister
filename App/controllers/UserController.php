<?php
declare(strict_types=1);

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;
use Framework\Session;

class UserController
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
     * Shows the login page
     *
     * @return void
     */
    public function login(): void
    {
        loadView('users/login');
    }

    /**
     * Shows the register page
     *
     * @return void
     */
    public function create(): void
    {
        loadView('users/create');
    }

    public function store(): void
    {
        $allowedFields = ['name', 'email', 'city', 'state', 'password', 'password_confirmation'];
        $userData = array_intersect_key($_POST, array_flip($allowedFields));
        $userData = array_map('sanitize', $userData);
        $errors = [];

        if (!Validation::string($userData['name'], 5, 30)) {
            $errors['name'] = 'Name must be between 5 and 30 characters';
        }
        if (!Validation::email($userData['email'])) {
            $errors['email'] = 'Please enter a valid email address';
        }
        if (!Validation::string($userData['password'], 6, 50)) {
            $errors['password'] = 'Password must be at least 6 characters';
        }
        if (!Validation::match($userData['password'], $userData['password_confirmation'])) {
            $errors['password_confirmation'] = 'Passwords must match';
        }

        if (!empty($errors)) {
            loadView('/users/create', [
                'errors' => $errors,
                'userData' => $userData,
            ]);
            exit;
        }

        // Check if email already exists
        $params = [
            'email' => $userData['email'],
        ];
        $sql = 'SELECT * FROM users WHERE email = :email';
        $user = $this->db->query($sql, $params)->fetchAll();

        if ($user) {
            $errors['duplicate_email'] = 'Email already exists';
            loadView('/users/create', [
                'errors' => $errors,
                'userData' => $userData,
            ]);
            exit;
        }

        // Create user account
        $fields = [];
        $values = [];

        unset($userData['password_confirmation']);
        $userData['password'] = password_hash($userData['password'], PASSWORD_BCRYPT);

        foreach ($userData as $field => $value) {
            if ($value === '') {
                $userData[$field] = null;
            }
            $fields[] = $field;
            $values[] = ':' . $field;
        }

        $fields = implode(', ', $fields);
        $values = implode(', ', $values);

        $sql = "INSERT INTO users ({$fields}) VALUES ({$values})";
        $this->db->query($sql, $userData);

        $userId = $this->db->conn->lastInsertId();

        Session::set('user', [
            'id' => $userId,
            'name' => $userData['name'],
            'email' => $userData['email'],
            'city' => $userData['city'],
            'state' => $userData['state'],
        ]);

        inspectAndDie(Session::get('user'));

        redirect('/');
    }
}