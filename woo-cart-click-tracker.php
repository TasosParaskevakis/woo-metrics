<?php
/**
 * Plugin Name: WooCommerce Add to Cart Click Tracker
 * Description: Tracks Add to Cart button clicks and provides an admin page to display product statistics with order metrics.
 * Version: 2.0
 * Author: Tasos Paraskevakis
 * License: GPL2
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/*===============================
=  Front-End Tracking Code      =
===============================*/

// Enqueue front-end JavaScript for tracking Add to Cart clicks
add_action( 'wp_enqueue_scripts', 'tasos_enqueue_tracking_script' );
function tasos_enqueue_tracking_script() {
    if ( is_product() || is_shop() || is_archive() ) {
        wp_enqueue_script( 'tasos-cart-tracker', plugin_dir_url( __FILE__ ) . 'cart-tracker.js', array( 'jquery' ), null, true );
        wp_localize_script( 'tasos-cart-tracker', 'tasos_ajax', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
    }
}

// AJAX handler for incrementing Add to Cart click counts
add_action( 'wp_ajax_add_cart_clicked', 'tasos_add_cart_clicked' );
add_action( 'wp_ajax_nopriv_add_cart_clicked', 'tasos_add_cart_clicked' );
function tasos_add_cart_clicked() {
    if ( ! isset( $_POST['pid'] ) ) {
        wp_die("No product ID provided");
    }
    $pid = intval( $_POST['pid'] );
    $times_added_to_cart = (int) get_post_meta( $pid, 'add_cart_clicks', true );
    update_post_meta( $pid, 'add_cart_clicks', $times_added_to_cart + 1 );
    wp_die("Success");
}

/*===============================
=  Order Metrics Tracking Code  =
===============================*/

// Track Cart Page Views (must be loaded on the front-end)
function my_funnel_track_cart_views() {
    if ( is_cart() ) {
        $count = (int) get_option( 'my_funnel_cart_views', 0 );
        update_option( 'my_funnel_cart_views', $count + 1 );
    }
}
add_action( 'template_redirect', 'my_funnel_track_cart_views' );

function my_funnel_track_checkout_views() {
    // Only count if we're on the checkout page and not on the order received page.
    if ( is_checkout() && empty( $_GET['pay_for_order'] ) && empty( $_GET['key'] ) ) {
        $count = (int) get_option( 'my_funnel_checkout_views', 0 );
        update_option( 'my_funnel_checkout_views', $count + 1 );
    }
}
add_action( 'template_redirect', 'my_funnel_track_checkout_views' );
// Track Orders on the Thank-You page (count each order only once)
function my_funnel_track_orders_thankyou( $order_id ) {
    if ( ! $order_id ) {
        return;
    }
    // Check if this order has already been counted.
    if ( get_post_meta( $order_id, '_my_funnel_counted', true ) ) {
        return;
    }
    $count = (int) get_option( 'my_funnel_orders', 0 );
    update_option( 'my_funnel_orders', $count + 1 );
    error_log( "Order tracked on thank-you: Order ID {$order_id}, new count: " . ($count + 1) );
    // Mark the order as counted.
    update_post_meta( $order_id, '_my_funnel_counted', 1 );
}
add_action( 'woocommerce_thankyou', 'my_funnel_track_orders_thankyou', 10, 1 );

/*===============================
=  Admin Code (Tabs & Display)  =
===============================*/

// Load the admin page code only in the admin area.
if ( is_admin() ) {
    add_action( 'admin_menu', 'tasos_add_admin_menu' );
    function tasos_add_admin_menu() {
        add_menu_page(
            'Cart Click Stats',
            'Cart Click Stats',
            'manage_options',
            'cart-click-stats',
            'tasos_display_admin_page', // Defined in main.php
            'dashicons-chart-bar',
            20
        );
    }
    
    // Include the admin page file (main.php) which contains our tab structure.
    include_once plugin_dir_path( __FILE__ ) . 'main.php';
}
?>