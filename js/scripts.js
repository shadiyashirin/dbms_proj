// Function to toggle mobile navigation menu
function toggleMenu() {
    const navMenu = document.querySelector('.nav-menu');
    const navIcon = document.querySelector('.nav-icon i');
    navMenu.classList.toggle('active');

    // Toggle between hamburger and close icon
    if (navMenu.classList.contains('active')) {
        navIcon.classList.remove('fa-bars');
        navIcon.classList.add('fa-times');
    } else {
        navIcon.classList.remove('fa-times');
        navIcon.classList.add('fa-bars');
    }
}

// Function to handle product ordering
function orderProduct(productName) {
    alert(`Order placed for ${productName}!`);
}

// Function to decrease stock level on inventory page
function decreaseStock(button) {
    const stockCell = button.parentElement.parentElement.querySelector('.stock-level');
    let currentStock = parseInt(stockCell.textContent);
    if (currentStock > 0) {
        stockCell.textContent = currentStock - 1;
    } else {
        alert('Stock cannot be negative.');
    }
}

// Function to increase quantity on create order page
function increaseQuantity(button) {
    const quantityCell = button.parentElement.previousElementSibling;
    let currentQuantity = parseInt(quantityCell.textContent);
    quantityCell.textContent = currentQuantity + 1;
}

// Function to decrease quantity on create order page
function decreaseQuantity(button) {
    const quantityCell = button.parentElement.previousElementSibling;
    let currentQuantity = parseInt(quantityCell.textContent);
    if (currentQuantity > 0) {
        quantityCell.textContent = currentQuantity - 1;
    } else {
        alert('Quantity cannot be negative.');
    }
}

// Function to confirm order on create order page
function confirmOrder() {
    const orderTable = document.getElementById('orderTable').getElementsByTagName('tbody')[0];
    const rows = orderTable.getElementsByTagName('tr');
    const orderDetails = [];

    for (let i = 0; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        const productName = cells[0].textContent;
        const quantity = parseInt(cells[4].textContent);

        if (quantity > 0) {
            orderDetails.push({
                productName: productName,
                quantity: quantity
            });
        }
    }

    if (orderDetails.length === 0) {
        alert('Please select at least one product to order.');
        return;
    }

    // Display order details (In real application, send this data to the server)
    let orderSummary = 'Order Confirmed for the following products:\n\n';
    orderDetails.forEach(item => {
        orderSummary += `• ${item.productName} - Quantity: ${item.quantity}\n`;
    });

    alert(orderSummary);
}

// Function to search products in the inventory table
function searchProducts() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const tables = document.getElementsByClassName('inventory-table');

    for (let t = 0; t < tables.length; t++) {
        const table = tables[t];
        const tr = table.getElementsByTagName('tr');

        // Loop through all table rows, and hide those who don't match the search query
        for (let i = 1; i < tr.length; i++) { // Start from 1 to skip table header
            const tdName = tr[i].getElementsByTagName('td')[0];
            const tdBrand = tr[i].getElementsByTagName('td')[1];
            if (tdName || tdBrand) {
                const textName = tdName.textContent || tdName.innerText;
                const textBrand = tdBrand.textContent || tdBrand.innerText;
                if (textName.toLowerCase().includes(filter) || textBrand.toLowerCase().includes(filter)) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
}
