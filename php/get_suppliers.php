<?php
include 'db_connection.php';

// Query to fetch supplier IDs and names
$sql = "SELECT Supplier_ID, Supplier_Name FROM supplier";
$result = $conn->query($sql);

// Start outputting the options for the dropdown
if ($result->num_rows > 0) {
    // Output each supplier as an option in the dropdown
    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['Supplier_ID'] . "'>" . $row['Supplier_Name'] . " (" . $row['Supplier_ID'] . ")</option>";
    }
} else {
    echo "<option disabled>No suppliers available</option>";
}

$conn->close();
?>
