<?php
// Include necessary files for API integration and any other required functionality
require_once 'api-integration.php';

// Add a button for manual description generation on the WooCommerce product edit page
function wc_pde_add_custom_button() {
    global $post;

    if ('product' !== $post->post_type) {
        return;
    }

    // Output the button and script
    echo '<div class="options_group">';
    echo '<button type="button" class="button" id="generate_description_btn">' . esc_html__('Generate Description', 'wc-product-desc-enhancer') . '</button>';
    echo '</div>';
    ?>
    <script type="text/javascript">
        jQuery('#generate_description_btn').click(function() {
            var data = {
                'action': 'wc_pde_generate_description_ajax',
                'product_id': '<?php echo esc_js($post->ID); ?>'
            };

            jQuery.post(ajaxurl, data, function(response) {
                alert(response);
                window.location.reload();
            });
        });
    </script>
    <?php
}
add_action('woocommerce_product_options_general_product_data', 'wc_pde_add_custom_button');

// Handle AJAX request for generating product descriptions
function wc_pde_generate_description_ajax() {
    // Security checks here if needed (like nonce verification)

    $product_id = intval($_POST['product_id']);
    if (!$product_id) {
        wp_send_json_error('Invalid Product ID.');
        wp_die();
    }

    $product = wc_get_product($product_id);
    if (!$product) {
        wp_send_json_error('Product not found.');
        wp_die();
    }

    // Construct your prompt based on product details
    $product_details = sprintf("Name: %s. Description: %s", $product->get_name(), $product->get_description());
    $enhanced_description = wc_pde_generate_description($product_details);

    if (!$enhanced_description) {
        wp_send_json_error('Failed to enhance description.');
        wp_die();
    }

    // Update the product description
    wp_update_post(array(
        'ID' => $product_id,
        'post_content' => $enhanced_description,
    ));

    wp_send_json_success('Product description enhanced successfully.');
}

add_action('wp_ajax_wc_pde_generate_description_ajax', 'wc_pde_generate_description_ajax');
