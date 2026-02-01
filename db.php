<?php
// db.php

$host = 'localhost';
$port = '5432';
$db   = 'travelease_db';
$user = 'travelease_user';
$pass = 'strongpassword';

$dsn = "pgsql:host=$host;port=$port;dbname=$db;options='--client_encoding=UTF8'";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    error_log($e->getMessage());
    die('Database connection failed. Please try again later.');
}
