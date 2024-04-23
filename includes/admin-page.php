<?php
// Hook into the admin menu to add your plugin's settings page
add_action('admin_menu', 'wc_pde_add_admin_menu');

function wc_pde_add_admin_menu() {
    // Add submenu page to the WooCommerce menu
    add_submenu_page(
        'woocommerce', // Parent slug
        'Product Description Enhancer Settings', // Page title
        'Description Enhancer', // Menu title
        'manage_options', // Capability
        'wc-product-desc-enhancer-settings', // Menu slug
        'wc_pde_settings_page' // Function to display the settings page
    );
}

// Display the settings page content
function wc_pde_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            // Output security fields for the registered setting "wc_pde_settings"
            settings_fields('wc_pde_settings');
            // Output setting sections and their fields
            do_settings_sections('wc-product-desc-enhancer-settings');
            // Output save settings button
            submit_button('Save Settings');
            ?>
        </form>
    </div>
    <?php
}

// Hook into admin_init to register settings, sections, and fields
add_action('admin_init', 'wc_pde_settings_init');

function wc_pde_settings_init() {
    // Register a new setting for "wc_pde" page
    register_setting('wc_pde_settings', 'wc_pde_options');

    // Register a new section in the "wc_pde" page
    add_settings_section(
        'wc_pde_section',
        __('OpenAI API Key', 'wc-product-desc-enhancer'),
        'wc_pde_section_callback',
        'wc-product-desc-enhancer-settings'
    );

    // Register a new field in the "wc_pde_section" section, inside the "wc_pde" page
    add_settings_field(
        'wc_pde_field_api_key', // As used in the 'id' attribute
        __('API Key', 'wc-product-desc-enhancer'), // Title
        'wc_pde_field_api_key_render', // Callback for rendering the field
        'wc-product-desc-enhancer-settings', // Page to display on
        'wc_pde_section' // Section to display in
    );
}

function wc_pde_section_callback() {
    echo __('Please enter your OpenAI API key below.', 'wc-product-desc-enhancer');
}

function wc_pde_field_api_key_render() {
    $options = get_option('wc_pde_options');
    ?>
    <input type='text' name='wc_pde_options[api_key]' value='<?php echo $options['api_key'] ?? ''; ?>'>
    <?php
}
