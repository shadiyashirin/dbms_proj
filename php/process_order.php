<?php
include '../db_connection.php';

// Get the posted data from JavaScript fetch request
$data = json_decode(file_get_contents("php://input"), true);
$supplier_id = $data['supplierId'];
$orderDetails = $data['orderDetails'];

// Check if supplier_id and orderDetails exist
if (!$supplier_id || empty($orderDetails)) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    exit;
}

// Insert a new row into `orders` table
$order_date = date('Y-m-d');
$total_amount = 0;

// Start transaction
$conn->begin_transaction();

try {
    // Insert into orders table
    $order_query = "INSERT INTO orders (Order_Date, Supplier_ID, total_amount) VALUES (?, ?, ?)";
    $order_stmt = $conn->prepare($order_query);
    $order_stmt->bind_param("sid", $order_date, $supplier_id, $total_amount);
    $order_stmt->execute();
    $order_id = $conn->insert_id; // Get the inserted Order_ID

    // Prepare query for order details insertion
    $order_detail_query = "INSERT INTO order_detail (Order_ID, Product_ID, Quantity_Ordered, Price_At_Order) VALUES (?, ?, ?, ?)";
    $order_detail_stmt = $conn->prepare($order_detail_query);

    foreach ($orderDetails as $item) {
        // Retrieve Product_ID and price_per_unit based on product name
        $product_query = "SELECT phpid, price_per_unit FROM pharmaceutical_product WHERE pro_name = ?";
        $product_stmt = $conn->prepare($product_query);
        $product_stmt->bind_param("s", $item['productName']);
        $product_stmt->execute();
        $product_result = $product_stmt->get_result();
        $product = $product_result->fetch_assoc();

        if (!$product) {
            throw new Exception("Product not found: " . $item['productName']);
        }

        // Calculate Price_At_Order and update total amount
        $product_id = $product['phpid'];
        $price_per_unit = $product['price_per_unit'];
        $quantity_ordered = $item['quantity'];
        $price_at_order = $price_per_unit * $quantity_ordered;
        $total_amount += $price_at_order;

        // Insert into order_detail table with calculated Price_At_Order
        $order_detail_stmt->bind_param("iiid", $order_id, $product_id, $quantity_ordered, $price_at_order);
        $order_detail_stmt->execute();
    }

    // Update total_amount in orders table
    $update_order_query = "UPDATE orders SET total_amount = ? WHERE Order_ID = ?";
    $update_order_stmt = $conn->prepare($update_order_query);
    $update_order_stmt->bind_param("di", $total_amount, $order_id);
    $update_order_stmt->execute();

    // Commit transaction
    $conn->commit();

    // Respond with success and the new order ID
    echo json_encode(['success' => true, 'order_id' => $order_id]);

} catch (Exception $e) {
    $conn->rollback(); // Rollback transaction on error
    echo json_encode(['success' => false, 'message' => 'Failed to confirm order: ' . $e->getMessage()]);
}

// Close the database connection
$conn->close();
?>
