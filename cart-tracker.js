
    jQuery(document).ready(function($) {
        $('.single_add_to_cart_button').click(function(){
            var pid = $(this).val();
            $.post(tasos_ajax.ajax_url, { action: 'add_cart_clicked', pid: pid });
        });

        $('body').on('click', '.add_to_cart_button', function(){
            var pid = $(this).data('product_id');
            $.post(tasos_ajax.ajax_url, { action: 'add_cart_clicked', pid: pid });
        });
    });
