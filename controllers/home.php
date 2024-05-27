<?php
declare(strict_types=1);

$config = require basePath("config/db.php");
$db = new Database($config);
$sql = "SELECT * FROM listings LIMIT 6";

$listings = $db->query($sql)->fetchAll();

loadView('home');
