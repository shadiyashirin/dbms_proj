<?php
// add_product.php

error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phpid = $_POST['phpid'];
    $product_name = $_POST['product-name'];
    $brand_name = $_POST['brand-name'];
    $dosage = $_POST['dosage'];
    $strength = $_POST['strength'];
    $price = $_POST['price'];

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("INSERT INTO pharmaceutical_product (phpid, pro_name, brand, dosage_form, strength, price_per_unit) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssd", $phpid, $product_name, $brand_name, $dosage, $strength, $price);

    try {
        // Execute the statement
        $stmt->execute();
        echo "<script>
                alert('Successfully added new product');
                window.location.href = '../admin.php';
            </script>";
    } catch (mysqli_sql_exception $e) {
        // Check for duplicate entry error
        if ($e->getCode() == 1062) {
            echo "<script>
                    alert('Error: Product already exists. Please use a unique ID.');
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
