<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
// get_products.php
include 'db_connection.php';

// Query to fetch product ID and name
echo "<!-- Loaded get_products.php -->";


$sql = "SELECT phpid, pro_name FROM pharmaceutical_product";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output each product as an option in the dropdown
    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['phpid'] . "'>" . $row['pro_name'] . "</option>";
    }
} else {
    echo "<option disabled>No products available</option>";
}

$conn->close();
?>
