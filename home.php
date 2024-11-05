<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmaManage - Home</title>
    <!-- Link to Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Link to External CSS -->
    <link rel="stylesheet" href="style1.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-container">
            <!-- Logo/Icon and Brand Name -->
            <div class="nav-logo">
                <img src="./images/logo.png">
                <span class="nav-title">PharmaManage</span>
            </div>

            <!-- Search Bar -->
            <div class="nav-search">
                <input type="text" id="searchInput" placeholder="Search products..." onkeyup="searchProducts()">
                <button type="submit"><i class="fas fa-search"></i></button>
            </div>

            <!-- Navigation Links -->
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="index.html" class="nav-link">Home</a>
                </li>
                <li class="nav-item">
                    <a href="supplier.php" class="nav-link">Supplier Page</a>
                </li>
                <li class="nav-item">
                    <a href="admin.php" class="nav-link">Admin Page</a>
                </li>
            </ul>

            <!-- Mobile Menu Icon -->
            <div class="nav-icon" onclick="toggleMenu()">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <section class="inventory-section">
            <h2>Product Inventory</h2>
            <table class="inventory-table" id="inventoryTable">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Brand Name</th>
                        <th>Stock Level</th>
                        <th>Price per Unit ($)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Connect to the database
                    include 'db_connection.php';

                    // Query to get product details
                    $sql = "SELECT phpid, pro_name, brand, total_stock, price_per_unit FROM pharmaceutical_product";
                    $result = $conn->query($sql);

                    // Check if there are results and output each product as a table row
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr data-phpid='" . $row['phpid'] . "'>";
                            echo "<td>" . htmlspecialchars($row['pro_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['brand']) . "</td>";
                            echo "<td class='stock-level'>" . htmlspecialchars($row['total_stock']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['price_per_unit']) . "</td>";
                            echo "<td>
                            <button class='order-btn' onclick=\"window.location.href='supplier.php?product_id=" . $row['phpid'] . "'\">Order</button>
                            <button class='decrease-btn' onclick='decreaseStock(this)'>-</button>
                            <button class='tick-btn' onclick='updateStock(" . $row['phpid'] . ")'>&#10003;</button>
                            </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No products available</td></tr>";
                    }

                    // Close the database connection
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <p>&copy; 2024 PharmaManage. All rights reserved.</p>
            <p>Contact us: info@pharmamanage.com | +1 (234) 567-890</p>
        </div>
    </footer>

    <!-- Link to External JavaScript -->
    <script>
        // Call the function on page load


// Function to decrease stock level
function decreaseStock(button) {
    const stockCell = button.parentElement.parentElement.querySelector('.stock-level');
    let currentStock = parseInt(stockCell.textContent);
    if (currentStock > 0) {
        stockCell.textContent = currentStock - 1;
    } else {
        alert('Stock cannot be negative.');
    }
}

// Function to update stock level in the database
function updateStock(phpid) {
    const stockCell = document.querySelector(`tr[data-phpid="${phpid}"] .stock-level`);
    const updatedStock = parseInt(stockCell.textContent);

    if (isNaN(updatedStock) || updatedStock < 0) {
        alert("Invalid stock level.");
        return;
    }

    fetch('./php/update_stock_value.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ phpid: phpid, total_stock: updatedStock }),
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
    })
    .catch(error => console.error('Error updating stock:', error));
}


    // Function to highlight rows with zero stock level
    function highlightZeroStockRows() {
    const rows = document.querySelectorAll('#inventoryTable tbody tr');
    rows.forEach(row => {
        const stockCell = row.querySelector('.stock-level');
        const stockLevel = parseInt(stockCell.textContent.trim(), 10);
        console.log("Stock Level:", stockLevel); // Log the stock level

        // Check if stock level is exactly zero
        if (stockLevel <= 10 ) {
            row.style.backgroundColor = "#ffcccc"; // Temporarily set background color
            row.style.color = "#800000"; // Temporarily set text color
        } else {
            row.style.backgroundColor = ""; // Reset background color
            row.style.color = ""; // Reset text color
        }
    });
}


// Call the function on page load
document.addEventListener('DOMContentLoaded', () => {
    highlightZeroStockRows();
});




</script>



    <script src="./js/index.js"></script>
</body>
</html>
