<?php
include 'db_connection.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get supplier_id from the URL
$supplier_id = isset($_GET['supplier_id']) ? (int)$_GET['supplier_id'] : 0;

// Fetch supplier details using prepared statement
$supplier_query = "SELECT * FROM supplier WHERE Supplier_ID = ?";
$supplier_stmt = $conn->prepare($supplier_query);
$supplier_stmt->bind_param("i", $supplier_id);
$supplier_stmt->execute();
$supplier_result = $supplier_stmt->get_result();
$supplier = $supplier_result->fetch_assoc();

// Check if supplier was found
if ($supplier === null) {
    echo "Supplier not found.";
    exit;
}

// Fetch products supplied by the supplier using prepared statement
$product_stmt = $conn->prepare("
    SELECT distinct pp.pro_name, pp.brand, pp.dosage_form, pp.strength
    FROM pharmaceutical_product pp
    JOIN batch_detail bd ON pp.phpid = bd.pro_id
    WHERE bd.supplier_id = ?
");
$product_stmt->bind_param("i", $supplier_id);
$product_stmt->execute();
$product_result = $product_stmt->get_result();

$products = [];
while ($row = $product_result->fetch_assoc()) {
    $products[] = $row;
}

// Close database connection
$product_stmt->close();
$supplier_stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Order - PharmaManage</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="create_order.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.html" class="nav-logo">
                <i class="fas fa-pills fa-2x"></i>
                <span class="nav-title">PharmaManage</span>
            </a>
            <div class="nav-search">
                <input type="text" id="searchInput" placeholder="Search products..." onkeyup="searchProducts()">
                <button type="submit"><i class="fas fa-search"></i></button>
            </div>
            <ul class="nav-menu">
                <li class="nav-item"><a href="index.html" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="supplier.php" class="nav-link">Supplier Page</a></li>
                <li class="nav-item"><a href="admin.php" class="nav-link">Admin Page</a></li>
            </ul>
            <div class="nav-icon" onclick="toggleMenu()">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </nav>

    <main class="main-content">
        <section class="create-order-section">
            <h2>Create New Order</h2>
            <div class="supplier-info">
                <p><strong>Supplier ID:</strong> <span id="supplierId"><?= htmlspecialchars($supplier['Supplier_ID']) ?></span></p>
                <p><strong>Supplier Name:</strong> <span id="supplierName"><?= htmlspecialchars($supplier['Supplier_Name']) ?></span></p>
            </div>
            <table class="inventory-table" id="orderTable">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Brand Name</th>
                        <th>Strength</th>
                        <th>Dosage Form</th>
                        <th>Quantity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?= htmlspecialchars($product['pro_name']) ?></td>
                                <td><?= htmlspecialchars($product['brand']) ?></td>
                                <td><?= htmlspecialchars($product['strength']) ?></td>
                                <td><?= htmlspecialchars($product['dosage_form']) ?></td>
                                <td class="quantity">0</td>
                                <td>
                                    <button class="increase-btn" onclick="increaseQuantity(this)">+</button>
                                    <button class="decrease-btn" onclick="decreaseQuantity(this)">-</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No products available for this supplier.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="confirm-order">
                <button class="confirm-order-btn" onclick="confirmOrder()">Confirm Order</button>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="footer-container">
            <p>&copy; 2024 PharmaManage. All rights reserved.</p>
            <p>Contact us: info@pharmamanage.com | +1 (234) 567-890</p>
        </div>
    </footer>

    <!-- Link to External JavaScript -->
    <script src="./js/scripts.js"></script>

    <script>
        // Function to handle quantity increase
        function increaseQuantity(button) {
            const row = button.closest('tr');
            const quantityCell = row.querySelector('.quantity');
            let quantity = parseInt(quantityCell.textContent);
            quantityCell.textContent = quantity + 1;
        }

        // Function to handle quantity decrease
        function decreaseQuantity(button) {
            const row = button.closest('tr');
            const quantityCell = row.querySelector('.quantity');
            let quantity = parseInt(quantityCell.textContent);
            if (quantity > 0) {
                quantityCell.textContent = quantity - 1;
            }
        }

        // Placeholder function for order confirmation
        
        function confirmOrder() {
    const supplierId = document.getElementById('supplierId').textContent;
    const orderDetails = [];

    // Gather product details and quantities
    const rows = document.querySelectorAll('#orderTable tbody tr');
    rows.forEach(row => {
        const productName = row.cells[0].textContent;
        const brand = row.cells[1].textContent;
        const strength = row.cells[2].textContent;
        const dosageForm = row.cells[3].textContent;
        const quantity = parseInt(row.querySelector('.quantity').textContent);
        
        if (quantity > 0) {
            orderDetails.push({
                productName,
                quantity
            });
        }
    });
    // Send data to the server using fetch API
    fetch('./php/process_order.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({ supplierId, orderDetails })
})
.then(response => {
    // Check if the response is ok
    if (!response.ok) {
        throw new Error('Network response was not ok');
    }
    return response.json();
})
.then(data => {
    if (data.success) {
        // Redirect to the confirmation page with the order ID
        window.location.href = `order_confirmation.php?order_id=${data.order_id}`;
    } else {
        alert("Failed to confirm order: " + data.message);
    }
})

.catch(error => {
    console.error('Error:', error);
    alert("An error occurred while confirming the order: " + error.message);
});
}

    </script>
</body>
</html>
