<?php
include 'db_connection.php';

$suppliers = [];

if (isset($_GET['product_id'])) {
    // If product_id is set, retrieve suppliers associated with that product
    $product_id = $_GET['product_id'];
    $sql = "
        SELECT DISTINCT s.Supplier_ID, s.Supplier_Name, s.Phone_Number, s.Email, s.Address
        FROM supplier s
        JOIN batch_detail b ON s.Supplier_ID = b.supplier_id
        WHERE b.pro_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // If no product_id, retrieve all suppliers
    $sql = "SELECT Supplier_ID, Supplier_Name, Phone_Number, Email, Address FROM supplier";
    $result = $conn->query($sql);
}

// Fetch suppliers into an array
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $suppliers[] = $row;
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Details</title>
    <link rel="stylesheet" href="stylesupply.css">
</head>
<body>
<div class="container">
    <h1>Supplier Details</h1>
    <?php if (!empty($suppliers)): ?>
        <table class="supplier-table">
            <tr>
                <th>Supplier ID</th>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Email</th>
                <th>Address</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($suppliers as $supplier): ?>
            <tr>
                <td><?= htmlspecialchars($supplier['Supplier_ID']) ?></td>
                <td><?= htmlspecialchars($supplier['Supplier_Name']) ?></td>
                <td><?= htmlspecialchars($supplier['Phone_Number']) ?></td>
                <td><?= htmlspecialchars($supplier['Email']) ?></td>
                <td><?= htmlspecialchars($supplier['Address']) ?></td>
                <td>
                    <!-- Show Products button, linking to a page that displays products for this supplier -->
                    <a href="create_order.php?supplier_id=<?= htmlspecialchars($supplier['Supplier_ID']) ?>" class="action-btn">Show Products</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No supplier details available.</p>
    <?php endif; ?>
</div>

</body>
</html>
