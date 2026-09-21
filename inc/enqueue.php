<?php
/**
 * Front-end assets.
 *
 * @package TowerExchange
 */

function tower_exchange_enqueue_assets(): void
{
    $css_path = get_template_directory() . '/assets/css/styles.css';
    $js_path  = get_template_directory() . '/assets/js/app.js';

    wp_enqueue_style(
        'tower-exchange-fonts',
        'https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap',
        array(),
        null
    );
    wp_enqueue_style(
        'tower-exchange',
        tower_exchange_asset_uri('css/styles.css'),
        array('tower-exchange-fonts'),
        file_exists($css_path) ? (string) filemtime($css_path) : wp_get_theme()->get('Version')
    );
    wp_enqueue_script(
        'tower-exchange',
        tower_exchange_asset_uri('js/app.js'),
        array(),
        file_exists($js_path) ? (string) filemtime($js_path) : wp_get_theme()->get('Version'),
        true
    );
    wp_localize_script(
        'tower-exchange',
        'towerExchangeI18n',
        array(
            'enableDarkTheme'      => __('Увімкнути темну тему', 'tower-exchange'),
            'enableLightTheme'     => __('Увімкнути світлу тему', 'tower-exchange'),
            'openMenu'             => __('Відкрити меню', 'tower-exchange'),
            'closeMenu'            => __('Закрити меню', 'tower-exchange'),
            'cash'                 => __('Готівка', 'tower-exchange'),
            /* translators: %s: source currency or asset name. */
            'amountLabel'          => __('Сума: %s', 'tower-exchange'),
            'cryptoToCash'         => __('USDT → готівка', 'tower-exchange'),
            'cashToCrypto'         => __('Готівка → USDT', 'tower-exchange'),
            /* translators: %s: amount entered by the visitor. */
            'amountFragment'       => __(', сума %s', 'tower-exchange'),
            /* translators: 1: exchange direction, 2: optional amount fragment. */
            'managerMessage'       => __('Вітаю! Хочу уточнити розрахунок: %1$s%2$s.', 'tower-exchange'),
        )
    );
    wp_script_add_data('tower-exchange', 'strategy', 'defer');
}
add_action('wp_enqueue_scripts', 'tower_exchange_enqueue_assets');

function tower_exchange_resource_hints(array $urls, string $relation_type): array
{
    if ('preconnect' === $relation_type) {
        $urls[] = 'https://fonts.googleapis.com';
        $urls[] = array('href' => 'https://fonts.gstatic.com', 'crossorigin' => 'anonymous');
    }

    return $urls;
}
add_filter('wp_resource_hints', 'tower_exchange_resource_hints', 10, 2);
