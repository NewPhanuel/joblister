<?php
declare(strict_types=1);
use Framework\Database;

$config = require basePath('config/db.php');
$db = new Database($config);

$id = $_GET['id'] ?? '';

$sql = 'SELECT * FROM listings WHERE id = :id';
$params = ['id' => $id];

$listing = $db->query($sql, $params)->fetch();

loadView('listings/show', ['listing' => $listing]);