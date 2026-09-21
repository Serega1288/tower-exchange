<?php
/**
 * ACF options page. Field groups are imported once by tools/bootstrap-wordpress.php
 * so editors can manage their structure in ACF.
 *
 * @package TowerExchange
 */

function tower_exchange_register_options_page(): void
{
    if (! function_exists('acf_add_options_page')) {
        return;
    }

    acf_add_options_page(
        array(
            'page_title' => __('Tower Exchange settings', 'tower-exchange'),
            'menu_title' => __('Tower settings', 'tower-exchange'),
            'menu_slug'  => 'tower-exchange-settings',
            'capability' => 'manage_options',
            'redirect'   => false,
            'position'   => 58,
            'icon_url'   => 'dashicons-building',
        )
    );
}
add_action('acf/init', 'tower_exchange_register_options_page');
