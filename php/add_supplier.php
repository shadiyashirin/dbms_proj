<?php
// add_supplier.php
include '../db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $supplier_name = $_POST['supplier-name'];
    $phone_number = $_POST['supplier-contact'];
    $email = $_POST['supplier-email'];
    $address = $_POST['supplier-address'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO supplier (Supplier_Name, Phone_Number, Email, Address) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $supplier_name, $phone_number, $email, $address);

    try {
        // Execute the statement
        if ($stmt->execute()) {
            echo "<script>
                alert('new supplier added successfully');
                window.location.href = '../admin.php';
            </script>";
        }
    } catch (mysqli_sql_exception $e) {
        // Handle specific error codes
        if ($e->getCode() === 1062) { // Duplicate entry
            echo "<script>
                    alert('Error: A supplier with the same name and phone number already exists.');
                    window.location.href = '../admin.php';
                </script>";
        } else {
            echo "<script>
                    alert('Error: " . $e->getMessage() . "');
                    window.location.href = '../admin.php';
                </script>";
        }
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
