<?php
$host = 'mysql';  // Docker service name
$db   = 'grievance_system';
$user = 'root';
$pass = 'root'; // Replace with env or secret in prod

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}
?>
