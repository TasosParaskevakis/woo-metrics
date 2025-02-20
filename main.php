<?php

// main.php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

function tasos_display_admin_page() {
    // Determine active tab: default is 'product_clicks'
    $active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'product_clicks';
    ?>
    <div class="wrap">
        <h1>Add to Cart Stats & Order Metrics</h1>
        <h2 class="nav-tab-wrapper">
            <a href="<?php echo esc_html(admin_url( 'admin.php?page=cart-click-stats&tab=product_clicks' )); ?>" class="nav-tab <?php echo $active_tab === 'product_clicks' ? 'nav-tab-active' : ''; ?>">Product Clicks</a>
            <a href="<?php echo esc_html(admin_url( 'admin.php?page=cart-click-stats&tab=order_metrics' )); ?>" class="nav-tab <?php echo $active_tab === 'order_metrics' ? 'nav-tab-active' : ''; ?>">Order Metrics</a>
        </h2>
        <div class="tab-content" style="padding:20px 0;">
            <?php
            // Load the appropriate file based on the active tab.
            if ( $active_tab === 'order_metrics' ) {
                include plugin_dir_path( __FILE__ ) . 'order_metrics.php';
            } else {
                include plugin_dir_path( __FILE__ ) . 'product_clicks.php';
            }
            ?>
        </div>
    </div>
    <?php
}
