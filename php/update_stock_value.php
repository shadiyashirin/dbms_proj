<?php
include '../db_connection.php';

// Decode the JSON input from the request
$data = json_decode(file_get_contents('php://input'), true);

$phpid = isset($data['phpid']) ? intval($data['phpid']) : 0;
$total_stock = isset($data['total_stock']) ? intval($data['total_stock']) : -1;

if ($phpid > 0 && $total_stock >= 0) {
    // Prepare SQL statement to update the stock level
    $sql = "UPDATE pharmaceutical_product SET total_stock = ? WHERE phpid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $total_stock, $phpid);

    if ($stmt->execute()) {
        echo "Stock updated successfully!";
    } else {
        echo "Error updating stock: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "Invalid input data.";
}

$conn->close();
?>
