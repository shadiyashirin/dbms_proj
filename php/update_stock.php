<?php
// update_stock.php
include '../db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['phpid_stock']; // Use the phpid field here
    $batch_number = $_POST['batch-number'];
    $quantity = $_POST['quantity'];
    $supplier_id = $_POST['supplier-id'];
    $expiry_date = $_POST['expiry-date'];

    // Insert batch details into batch_detail table
    $stmt = $conn->prepare("INSERT INTO batch_detail (batch_no, pro_id, exp_date, quantity, supplier_id) VALUES (?,?,?,?,?)");
    $stmt->bind_param("sssid",$batch_number, $product_id, $expiry_date, $quantity, $supplier_id);

    try {
        // Execute the statement
        $stmt->execute();
        echo "<script>
                alert('Successfully added new batch');
                window.location.href = '../admin.php';
            </script>";
    } catch (mysqli_sql_exception $e) {
        // Check for duplicate entry error
        if ($e->getCode() == 1062) {
            echo "<script>
                    alert('Error: batch already exist,please check the batch id .');
                    window.location.href = '../admin.php';
                </script>";
        } else {
            echo "<script>
                    alert('Error: " . $e->getMessage() . "');
                    window.location.href = '../admin.php';
                </script>";
        }
    }

    $stmt->close();
}
$conn->close();
?>
