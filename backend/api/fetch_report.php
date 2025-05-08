<?php
header('Content-Type: application/json');
include_once '../config/db.php';

$query = "
    SELECT 
        d.name AS district_name,
        SUM(CASE WHEN c.status = 'Resolved' THEN 1 ELSE 0 END) AS resolved_complaints,
        SUM(CASE WHEN c.status = 'Pending' THEN 1 ELSE 0 END) AS pending_complaints,
        COUNT(*) AS total_complaints
    FROM districts d
    LEFT JOIN complaints c ON c.district_id = d.id
    GROUP BY d.name
    WITH ROLLUP
";

$result = $pdo->query($query);
$data = [];

foreach ($result as $row) {
    $data[] = [
        'district_name' => $row['district_name'] ?? 'GRAND TOTAL',
        'resolved_complaints' => $row['resolved_complaints'],
        'pending_complaints' => $row['pending_complaints'],
        'total_complaints' => $row['total_complaints']
    ];
}

echo json_encode($data);
?>
