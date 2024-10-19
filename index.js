function goToHome(){
    window.location.href = "./home.html";  // Replace with the desired URL
}

function orderProduct(){
    window.location.href = "./supplier.html";
}


function goTODetail(){
    window.location.href = "./suplly_det.html";
}


function selectpro(){
    window.location.href = "./create_order.html";
}

// Function to toggle order details visibility
function toggleDetails(orderId) {
    const details = document.getElementById(orderId);
    const toggleBtn = details.previousElementSibling.querySelector('.toggle-btn');
    
    if (details.style.display === "block") {
        details.style.display = "none";
        toggleBtn.classList.remove('rotate');
        toggleBtn.innerHTML = "▼";
    } else {
        details.style.display = "block";
        toggleBtn.classList.add('rotate');
        toggleBtn.innerHTML = "▲";
    }
}

// Function to show/hide additional products in an order
document.addEventListener('DOMContentLoaded', () => {
    const hiddenRows = document.querySelectorAll('.hidden-row');
    hiddenRows.forEach(row => {
        row.style.display = 'none';
    });
});



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
//function orderProduct(productName) {
//    alert(`Order placed for ${productName}!`);
//}

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

















// Function to search products in the inventory table
function searchProducts() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('inventoryTable');
    const tr = table.getElementsByTagName('tr');

    // Loop through all table rows, and hide those who don't match the search query
    for (let i = 1; i < tr.length; i++) { // Start from 1 to skip table header
        const tdName = tr[i].getElementsByTagName('td')[0];
        const tdBrand = tr[i].getElementsByTagName('td')[1];
        if (tdName || tdBrand) {
            const textName = tdName.textContent || tdName.innerText;
            const textBrand = tdBrand.textContent || tdBrand.innerText;
            if (textName.toLowerCase().indexOf(filter) > -1 || textBrand.toLowerCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }       
    }
}
