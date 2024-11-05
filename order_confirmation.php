<?php
include 'db_connection.php';

// Get the Order_ID from URL
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

// Fetch the order details
$order_query = "
    SELECT o.Order_ID, o.Order_Date, o.Supplier_ID, o.total_amount, s.Supplier_Name 
    FROM orders o
    JOIN supplier s ON o.Supplier_ID = s.Supplier_ID
    WHERE o.Order_ID = ?
";
$order_stmt = $conn->prepare($order_query);
$order_stmt->bind_param("i", $order_id);
$order_stmt->execute();
$order_result = $order_stmt->get_result();
$order = $order_result->fetch_assoc();

if (!$order) {
    echo "Order not found.";
    exit;
}

// Fetch the ordered products
$products_query = "
    SELECT pp.pro_name, od.Quantity_Ordered, od.Price_At_Order
    FROM order_detail od
    JOIN pharmaceutical_product pp ON od.Product_ID = pp.phpid
    WHERE od.Order_ID = ?
";
$products_stmt = $conn->prepare($products_query);
$products_stmt->bind_param("i", $order_id);
$products_stmt->execute();
$products_result = $products_stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation - PharmaManage</title>
    <link rel="stylesheet" href="order_confirmation.css">
</head>
<body>
    <h1>Order Confirmed</h1>
    <div class="order-summary">
        <p><strong>Order ID:</strong> <?= htmlspecialchars($order['Order_ID']) ?></p>
        <p><strong>Order Date:</strong> <?= htmlspecialchars($order['Order_Date']) ?></p>
        <p><strong>Supplier Name:</strong> <?= htmlspecialchars($order['Supplier_Name']) ?></p>
        <p><strong>Total Amount:</strong> $<?= htmlspecialchars(number_format($order['total_amount'], 2)) ?></p>
    </div>

    <h2>Products Ordered</h2>
    <table>
        <tr>
            <th>Product Name</th>
            <th>Quantity Ordered</th>
            <th>Price at Order</th>
        </tr>
        <?php while ($product = $products_result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($product['pro_name']) ?></td>
                <td><?= htmlspecialchars($product['Quantity_Ordered']) ?></td>
                <td>$<?= htmlspecialchars(number_format($product['Price_At_Order'], 2)) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php
// Close connections
$order_stmt->close();
$products_stmt->close();
$conn->close();
?>
