// Sample Sales Data matching the user interface
const salesData = [
    { id: 2, customerId: "CID-2", productIds: "4,5", quantities: "2,1", prices: "24.00,2.00", totalPrice: "26.00", dateOrder: "01-28-2025" },
    { id: 3, customerId: "CID-3", productIds: "4,5", quantities: "2,3", prices: "24.00,6.00", totalPrice: "30.00", dateOrder: "01-28-2025" },
    { id: 4, customerId: "CID-4", productIds: "4,5", quantities: "1,2", prices: "12.00,4.00", totalPrice: "16.00", dateOrder: "01-28-2025" },
    { id: 5, customerId: "CID-5", productIds: "4,5", quantities: "2,5", prices: "24.00,10.00", totalPrice: "34.00", dateOrder: "01-28-2025" },
    { id: 6, customerId: "CID-6", productIds: "7,4,5,6", quantities: "3,4,5,6", prices: "45.00,48.00,10.00,60.00", totalPrice: "163.00", dateOrder: "01-28-2025" }
];

// Load table rows dynamically
document.addEventListener("DOMContentLoaded", () => {
    populateSalesTable();
    setupNavigation();
});

function populateSalesTable() {
    const tableBody = document.getElementById("sales-table-body");
    tableBody.innerHTML = ""; // Clear existing contents

    salesData.forEach((row, index) => {
        const tr = document.createElement("tr");

        // Highlight the first row to match screenshot
        if (index === 0) {
            tr.classList.add("selected");
        }

        tr.innerHTML = `
            <td>${row.id}</td>
            <td>${row.customerId}</td>
            <td>${row.productIds}</td>
            <td>${row.quantities}</td>
            <td>${row.prices}</td>
            <td>${row.totalPrice}</td>
            <td>${row.dateOrder}</td>
        `;

        // Row selection click event
        tr.addEventListener("click", () => {
            document.querySelectorAll("tbody tr").forEach(r => r.classList.remove("selected"));
            tr.classList.add("selected");
        });

        tableBody.appendChild(tr);
    });
}

// Simple Sidebar Navigation active state trigger
function setupNavigation() {
    const navButtons = document.querySelectorAll(".nav-btn");

    navButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            navButtons.forEach(b => b.classList.remove("active"));
            btn.classList.add("active");
        });
    });
}