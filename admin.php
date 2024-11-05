<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Pharmacy Management System</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>




    <div class="dashboard-container">
        <h1>Admin Dashboard</h1>
        <nav>
            <ul>
                <li><a href="#add-product">Add New Product</a></li>
                <li><a href="#update-stock">Update Stock Level</a></li>
                <li><a href="#add-supplier">Add New Supplier</a></li>
                <li><a href="index.html">Logout</a></li>
            </ul>
        </nav>

        <!-- Add New Product Section -->
        <section id="add-product">
            <h2>Add New Product</h2>
            <form class="product-form" action="./php/add_product.php" method="POST">
                <label for="phpid">php id:</label>
                <input type="number" id="phpid" name="phpid" required>

                <label for="product-name">Product Name:</label>
                <input type="text" id="product-name" name="product-name" required>

                <label for="brand-name">Brand Name:</label>
                <input type="text" id="brand-name" name="brand-name" required>

                <label for="dosage">Dosage:</label>
                <input type="text" id="dosage" name="dosage" required>

                <label for="strength">Strength:</label>
                <input type="text" id="strength" name="strength" required>

                <label for="price">Price-per-unit:</label>
                <input type="text" id="price" name="price" required>

                <button type="submit" class="btn">Add Product</button>
            </form>
        </section>

        
        <!-- Update Stock Level Section -->
<section id="update-stock">
    <h2>Update Stock Level</h2>
    <form class="stock-form" action="./php/update_stock.php" method="POST">
        
        <!-- Product selection dropdown -->
        <label for="product-select">Select Product:</label>
        <select id="product-select" name="product-select" required>
            <option value="" disabled selected>Select a product</option>
            <?php include './php/get_product.php'; ?>
        </select>

        <!-- New input for Product PHP ID (read-only) -->
        <label for="phpid_stock">Product PHP ID:</label>
        <input type="text" id="phpid_stock" name="phpid_stock" readonly>

        <label for="batch-number">Batch Number:</label>
        <input type="text" id="batch-number" name="batch-number" required>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required>

        <!-- Supplier ID Dropdown -->
        <label for="supplier-id">Supplier ID:</label>
        <select id="supplier-id" name="supplier-id" required>
            <option value="" disabled selected>Select a supplier</option>
            <?php include './php/get_suppliers.php'; ?>
        </select>

        <label for="expiry-date">Expiry Date:</label>
        <input type="date" id="expiry-date" name="expiry-date" required>

        <button type="submit" class="btn">Update Stock</button>
    </form>
</section>

<script>
// Function to update the PHP ID input field based on selected product
document.getElementById('product-select').addEventListener('change', function() {
    console.log('Dropdown changed'); // Debug log
    var selectedOption = this.options[this.selectedIndex];
    document.getElementById('phpid_stock').value = selectedOption.value;
});
</script>


<script>
// Function to update the PHP ID input field based on selected product
function updatePHPID() {
    var select = document.getElementById('product-select');
    var phpidInput = document.getElementById('phpid');
    
    // Get the selected option
    var selectedOption = select.options[select.selectedIndex];
    
    // Set the value of the input field to the selected option's value (phpid)
    phpidInput.value = selectedOption.value;
}
</script>


        <!-- Add New Supplier Section -->
        <section id="add-supplier">
            <h2>Add New Supplier</h2>
            <form class="supplier-form" action="./php/add_supplier.php" method="POST">
                <label for="supplier-name">Supplier Name:</label>
                <input type="text" id="supplier-name" name="supplier-name" required>

                <label for="supplier-contact">Phone Number:</label>
                <input type="tel" id="supplier-contact" name="supplier-contact" required>

                <label for="supplier-email">Email:</label>
                <input type="email" id="supplier-email" name="supplier-email" required>

                <label for="supplier-address">Address:</label>
                <textarea id="supplier-address" name="supplier-address" required></textarea>

                <button type="submit" class="btn">Add Supplier</button>
            </form>
        </section>
    </div>


</body>
</html>
