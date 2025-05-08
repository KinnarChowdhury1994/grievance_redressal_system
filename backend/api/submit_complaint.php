<?php
header('Content-Type: application/json');
include_once '../config/db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['citizen_name'], $data['citizen_email'], $data['district_id'], $data['complaint_text'])) {
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

$stmt = $pdo->prepare("INSERT INTO complaints (citizen_name, citizen_email, district_id, complaint_text) VALUES (?, ?, ?, ?)");
$stmt->execute([$data['citizen_name'], $data['citizen_email'], $data['district_id'], $data['complaint_text']]);

echo json_encode(['message' => 'Complaint submitted successfully']);
?>
