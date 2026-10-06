<?php
try {
    $dbh = new PDO('mysql:host=127.0.0.1', 'root', '');
    $dbh->exec('CREATE DATABASE IF NOT EXISTS bpjs2_db');
    echo "Database created successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
