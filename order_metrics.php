<?php
// order_metrics.php

// Retrieve funnel counts (these options are updated via hooks in your main plugin file)
$cart_views     = (int) get_option( 'my_funnel_cart_views', 0 );
$checkout_views = (int) get_option( 'my_funnel_checkout_views', 0 );
$orders         = (int) get_option( 'my_funnel_orders', 0 );

// Calculate conversion percentages
$cart_to_checkout_rate  = $cart_views > 0 ? round( ( $checkout_views / $cart_views ) * 100, 2 ) : 0;
$checkout_to_order_rate = $checkout_views > 0 ? round( ( $orders / $checkout_views ) * 100, 2 ) : 0;
$cart_to_order_rate     = $cart_views > 0 ? round( ( $orders / $cart_views ) * 100, 2 ) : 0;
?>

<h2>Order Metrics</h2>
<table class="widefat" style="max-width:600px;">
    <thead>
        <tr>
            <th>Metric</th>
            <th>Count</th>
            <th>Conversion Rate</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Cart Views</td>
            <td><?php echo esc_html( $cart_views ); ?></td>
            <td>-</td>
        </tr>
        <tr>
            <td>Checkout Views</td>
            <td><?php echo esc_html( $checkout_views ); ?></td>
            <td><?php echo esc_html($cart_to_checkout_rate); ?>% <small>(of Cart Views)</small></td>
        </tr>
        <tr>
            <td>Orders</td>
            <td><?php echo esc_html( $orders ); ?></td>
            <td>
                <?php echo esc_html($checkout_to_order_rate); ?>% <small>(of Checkout Views)</small><br />
                <?php echo esc_html($cart_to_order_rate); ?>% <small>(of Cart Views)</small>
            </td>
        </tr>
    </tbody>
</table>
<h2 style="margin-top:30px;">Reset Funnel Data</h2>
<p>If you want to reset the funnel counts, click the button below.</p>
<form method="post">
    <?php wp_nonce_field( 'my_funnel_reset_nonce', 'my_funnel_reset_nonce_field' ); ?>
    <input type="submit" name="my_funnel_reset" class="button button-primary" value="Reset Funnel Data" />
</form>
<?php
if ( isset( $_POST['my_funnel_reset'] ) && check_admin_referer( 'my_funnel_reset_nonce', 'my_funnel_reset_nonce_field' ) ) {
    update_option( 'my_funnel_cart_views', 0 );
    update_option( 'my_funnel_checkout_views', 0 );
    update_option( 'my_funnel_orders', 0 );
    echo "<script>location.href=location.href;</script>";
}
?>