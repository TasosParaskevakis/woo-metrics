<?php
// product_clicks.php

// Note: This file is included in the admin page, so it does not need its own plugin header.

echo '<!-- BEGIN Product Clicks Stats -->';

// Inline CSS for layout
echo '
<style>
  .tasos-container { display: flex; flex-direction: column; width: 100%; }
  .tasos-search { margin-bottom: 20px; }
  .tasos-search input { width: 100%; padding: 8px; box-sizing: border-box; }
  .tasos-main-content { display: flex; gap: 20px; align-items: flex-start; }
  .tasos-table-container { flex: 1; }
  #cart-stats-table { width: 100%; }
  .tasos-pagination { margin-top: 20px; display: flex; justify-content: flex-start; gap: 10px; }
  .tasos-pagination button { padding: 8px 16px; cursor: pointer; }
  .tasos-chart-container { flex: 1; }
  @media (max-width: 1200px) { .tasos-main-content { flex-direction: column; } }
</style>
';

echo '<div class="tasos-container">';

// Search bar
echo '<div class="tasos-search">
        <input type="text" id="search-product" placeholder="Search product...">
      </div>';

// Main content: table (with pagination) and chart
echo '<div class="tasos-main-content">';

// Left column: Table and pagination
echo '<div class="tasos-table-container">
        <table class="widefat fixed" id="cart-stats-table">
            <thead>
                <tr>
                    <th><a href="#" class="sort" data-sort="name" data-order="asc">Product &#8597;</a></th>
                    <th><a href="#" class="sort" data-sort="clicks" data-order="asc">Clicks &#8597;</a></th>
                </tr>
            </thead>
            <tbody id="cart-stats-body">';
            
$args = array(
    'post_type'      => 'product',
    'posts_per_page' => -1
);
$products = get_posts( $args );
foreach ( $products as $product ) {
    $clicks = (int) get_post_meta( $product->ID, 'add_cart_clicks', true );
    echo '<tr class="product-row">
            <td class="name">' . esc_html( get_the_title( $product->ID ) ) . '</td>
            <td class="clicks">' . esc_html( $clicks ) . '</td>
          </tr>';
}
echo '</tbody>
      </table>
      
      <!-- Pagination buttons -->
      <div class="tasos-pagination">
        <button id="prev-page">Previous</button>
        <button id="next-page">Next</button>
      </div>
    </div>'; // .tasos-table-container

// Right column: Chart
echo '<div class="tasos-chart-container">
        <canvas id="cartClicksChart" width="400" height="200"></canvas>
      </div>';

echo '</div>'; // .tasos-main-content
echo '</div>'; // .tasos-container

// Include Chart.js
echo '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>';

// JavaScript for search, sorting, pagination, and chart update.
echo '<script>
document.addEventListener("DOMContentLoaded", function() {
    var ctx = document.getElementById("cartClicksChart").getContext("2d");
    var chart;
    var currentPage = 1;
    var rowsPerPage = 10;
    
    function updateChart() {
        let labels = [];
        let data = [];
        document.querySelectorAll(".product-row").forEach(function(row) {
            if (row.style.display !== "none") {
                labels.push(row.querySelector(".name").innerText);
                data.push(parseInt(row.querySelector(".clicks").innerText));
            }
        });
        if (chart) { chart.destroy(); }
        chart = new Chart(ctx, {
            type: "bar",
            data: {
                labels: labels,
                datasets: [{
                    label: "Add to Cart Clicks",
                    data: data,
                    backgroundColor: "rgba(75, 192, 192, 0.2)",
                    borderColor: "rgba(75, 192, 192, 1)",
                    borderWidth: 1
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });
    }
    
    function updatePagination() {
        var filter = document.getElementById("search-product").value.toLowerCase();
        var allRows = Array.from(document.querySelectorAll(".product-row"));
        var filteredRows = allRows.filter(function(row) {
            return row.querySelector(".name").innerText.toLowerCase().includes(filter);
        });
        allRows.forEach(function(row) { row.style.display = "none"; });
        var startIndex = (currentPage - 1) * rowsPerPage;
        var endIndex = startIndex + rowsPerPage;
        filteredRows.slice(startIndex, endIndex).forEach(function(row) {
            row.style.display = "";
        });
        updateChart();
        document.getElementById("next-page").style.display = endIndex >= filteredRows.length ? "none" : "inline-block";
        document.getElementById("prev-page").style.display = currentPage === 1 ? "none" : "inline-block";
    }
    
    updatePagination();
    
    document.getElementById("search-product").addEventListener("keyup", function() {
        currentPage = 1;
        updatePagination();
    });
    
    document.querySelectorAll(".sort").forEach(function(header) {
        header.addEventListener("click", function() {
            var order = this.dataset.order === "asc" ? "desc" : "asc";
            this.dataset.order = order;
            var rows = Array.from(document.querySelectorAll(".product-row"));
            rows.sort(function(a, b) {
                var aVal, bVal;
                if (header.dataset.sort === "name") {
                    aVal = a.querySelector(".name").innerText.toLowerCase();
                    bVal = b.querySelector(".name").innerText.toLowerCase();
                } else {
                    aVal = parseInt(a.querySelector(".clicks").innerText);
                    bVal = parseInt(b.querySelector(".clicks").innerText);
                }
                return order === "asc" ? (aVal > bVal ? 1 : aVal < bVal ? -1 : 0) : (aVal < bVal ? 1 : aVal > bVal ? -1 : 0);
            });
            var tbody = document.getElementById("cart-stats-body");
            tbody.innerHTML = "";
            rows.forEach(function(row) { tbody.appendChild(row); });
            currentPage = 1;
            updatePagination();
        });
    });
    
    document.getElementById("next-page").addEventListener("click", function() {
        currentPage++;
        updatePagination();
    });
    
    document.getElementById("prev-page").addEventListener("click", function() {
        if (currentPage > 1) {
            currentPage--;
            updatePagination();
        }
    });
});
</script>';

echo '<!-- END Product Clicks Stats -->';
?>