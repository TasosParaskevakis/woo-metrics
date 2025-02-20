# WooCommerce Add to Cart Click Tracker with Order Metrics

**Version:** 2.0  
**Author:** Tasos Paraskevakis  
**License:** GNU GPL v2 or later

## Overview

The **WooCommerce Add to Cart Click Tracker with Order Metrics** plugin is designed for WooCommerce stores. It provides an easy way to track:
- **Add-to-Cart Clicks** on product, shop, and archive pages.
- **Cart Views** and **Checkout Views**.
- **Orders** via the WooCommerce thank-you page, ensuring each order is counted only once.

The plugin features an admin page with two tabs:
- **Product Clicks:** Displays product click statistics with search, sorting, pagination, and a dynamic Chart.js bar chart.
- **Order Metrics:** Displays a funnel with conversion rates (Cart → Checkout, Checkout → Order, and Cart → Order) and allows resetting of metrics.

## Features

- **AJAX Tracking:** Uses front-end JavaScript to track add-to-cart button clicks.
- **Dynamic Chart:** Renders a bar chart using [Chart.js](https://www.chartjs.org/) to visualize product clicks.
- **Admin Dashboard:** A two-tab admin page with:
  - **Product Clicks Tab:** Detailed view of click statistics.
  - **Order Metrics Tab:** Funnel metrics for cart views, checkout views, and orders.
- **Prevents Double Counting:** Marks orders as "counted" so that each order is only tracked once.
- **GPL Licensed:** Free and open-source under the GNU General Public License v2 or later.

## Installation

1. **Upload Plugin Files:**
   - Place the plugin folder (containing the files below) into your WordPress `wp-content/plugins/` directory.

2. **Activate the Plugin:**
   - In your WordPress admin, go to **Plugins** and activate **WooCommerce Add to Cart Click Tracker with Order Metrics**.

3. **Usage:**
   - The plugin will automatically track add-to-cart clicks on the front end.
   - Access the plugin dashboard by navigating to **Cart Click Stats** in your WordPress admin menu.
   - Use the tabs on the dashboard to view **Product Clicks** and **Order Metrics**.

## Files

- **woo-cart-click-tracker.php**  
  The main plugin file that registers front-end scripts, AJAX handlers, and the admin menu.

- **main.php**  
  Contains the admin page layout with tab navigation. This file loads either `product_clicks.php` or `order_metrics.php` based on the selected tab.

- **product_clicks.php**  
  Displays product click statistics, including:
  - A search bar.
  - Sorting and pagination for product rows.
  - A Chart.js bar chart for visualizing click data.

- **order_metrics.php**  
  Displays order metrics (cart views, checkout views, orders) and calculates conversion rates. It also includes a reset option for funnel data.

- **cart-tracker.js**  
  The front-end JavaScript file that attaches event handlers to "Add to Cart" buttons and sends AJAX requests to track clicks.

## Customization & Contributing

- **Customization:**  
  Feel free to modify the code to suit your theme or additional requirements. Inline styles in the admin pages can be moved to an external CSS file if preferred.

- **Contributing:**  
  Contributions, bug reports, and feature requests are welcome! Please fork the repository and submit a pull request with your improvements.

## License

This project is licensed under the [GNU General Public License v2 or later](https://www.gnu.org/licenses/gpl-2.0.html).

## Author

Tasos Paraskevakis