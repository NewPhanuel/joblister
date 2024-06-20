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

    /**
     * Validates info from the register form
     * and creates an account with it.
     * 
     * Then it saves some of the details to session
     *
     * @return void
     */
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
        if (!Validation::string($userData['password'], 6, 17)) {
            $errors['password'] = 'Password must be min 6 characters and max 17 characters';
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

        // Set user session
        Session::set('user', [
            'id' => $userId,
            'name' => $userData['name'],
            'email' => $userData['email'],
            'city' => $userData['city'],
            'state' => $userData['state'],
        ]);

        redirect('/');
    }

    /**
     * Clears the user session and the cookie
     *
     * @return void
     */
    public function logout(): void
    {
        Session::clearAll();
        clearCookie('PHPSESSID');
        redirect('/');
    }

    public function authenticate(): void
    {
        $email = sanitize($_POST['email']);
        $password = sanitize($_POST['password']);
        $errors = [];

        // Validate forms
        if (!Validation::email($email)) {
            $errors['email'] = 'Please enter a valid email address';
        }

        if (!Validation::string($password, 6, 17)) {
            $errors['password'] = 'Password must be min 6 characters and max 17 characters';
        }

        // Check fr errors
        if (!empty($errors)) {
            loadView('/users/login', [
                'errors' => $errors,
                'email' => $email,
                'password' => $password,
            ]);
            exit;
        }

        // Check for email in database
        $sql = "SELECT * FROM users WHERE email = :email";
        $params = ['email' => $email];

        $user = $this->db->query($sql, $params)->fetch();

        if (!$user) {
            $errors['email'] = 'Incorrect user credentials';
            loadView('/users/login', ['errors' => $errors]);
            exit;
        }

        // Check if password is incorrect
        if (!password_verify($password, $user->password)) {
            $errors['password'] = 'Incorrect user credentials';
            loadView('/users/login', [
                'errors' => $errors,
                'email' => $email,
            ]);
            exit;
        }

        // Set user session
        Session::set('user', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'city' => $user->city,
            'state' => $user->state,
        ]);

        redirect('/');
    }
}
