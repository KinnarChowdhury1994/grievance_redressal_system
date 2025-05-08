<?php
header('Content-Type: application/json');
include_once '../config/db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['complaint_id'], $data['status'], $data['updated_by'], $data['action_taken'])) {
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

$pdo->beginTransaction();

try {
    $stmt = $pdo->prepare("UPDATE complaints SET status = ?, updated_at = NOW() WHERE id = ?");
    $stmt->execute([$data['status'], $data['complaint_id']]);

    $stmt = $pdo->prepare("INSERT INTO complaint_actions (complaint_id, action_taken, updated_by) VALUES (?, ?, ?)");
    $stmt->execute([$data['complaint_id'], $data['action_taken'], $data['updated_by']]);

    $pdo->commit();
    echo json_encode(['message' => 'Complaint status updated']);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['error' => $e->getMessage()]);
}
?>
