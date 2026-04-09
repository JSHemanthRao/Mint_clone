<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS mint_db");
    echo "Database 'mint_db' created or already exists.\n";

    // Check if we can connect to the database explicitly
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=mint_db', 'root', '');
    echo "Successfully connected to 'mint_db'.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
