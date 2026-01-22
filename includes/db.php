<?php


$host = 'localhost';
$dbname = 'login';

/** 
 * SECURITY NOTE: 
 * - 'root' allows all operations (SIGNUP, LOGIN, etc.)
 * - 'public_viewer' is for READ-ONLY mode (Secures your data from deletion)
 */
$username = 'root';
$password = '';

// Read-Only mode (Uncomment to enable)
// $username = 'public_viewer'; 
// $password = 'password123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>