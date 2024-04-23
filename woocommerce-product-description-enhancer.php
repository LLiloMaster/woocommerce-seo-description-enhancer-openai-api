<?php
/*
Plugin Name: WooCommerce Product Description Enhancer
Plugin URI: https://yourdomain.com
Description: Enhances WooCommerce product descriptions using OpenAI, focusing on French language content.
Version: 1.0
Author: Your Name
Author URI: https://yourauthorprofile.com
License: GPL v2 or later
Text Domain: wc-product-desc-enhancer
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Plugin activation
function wc_pde_activate() {
    // Perform any setup steps such as setting default options or database tables.
}
register_activation_hook(__FILE__, 'wc_pde_activate');

// Plugin deactivation
function wc_pde_deactivate() {
    // Clean up resources, such as temporary data or cache, if necessary.
}
register_deactivation_hook(__FILE__, 'wc_pde_deactivate');

// Load plugin textdomain for localization
function wc_pde_load_textdomain() {
    load_plugin_textdomain('wc-product-desc-enhancer', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'wc_pde_load_textdomain');

// Include other necessary files
require_once(plugin_dir_path(__FILE__) . 'includes/admin-page.php');
require_once(plugin_dir_path(__FILE__) . 'includes/api-integration.php');
require_once(plugin_dir_path(__FILE__) . 'includes/product-processing.php');

// Add a link to the settings page in the plugin list table
function wc_pde_add_settings_link($links) {
    $settings_link = '<a href="admin.php?page=wc-product-desc-enhancer-settings">' . __('Settings', 'wc-product-desc-enhancer') . '</a>';
    array_push($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wc_pde_add_settings_link');
