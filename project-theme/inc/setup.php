<?php
/**
 * Theme supports and menu locations.
 *
 * @package TowerExchange
 */

function tower_exchange_setup(): void
{
    load_theme_textdomain('tower-exchange', get_template_directory() . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');
    add_theme_support(
        'html5',
        array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script')
    );

    register_nav_menus(
        array(
            'header-desktop' => __('Header desktop menu', 'tower-exchange'),
            'header-mobile'  => __('Header mobile menu', 'tower-exchange'),
            'footer-social'  => __('Footer social links', 'tower-exchange'),
        )
    );
}
add_action('after_setup_theme', 'tower_exchange_setup');

/**
 * Anchor links point to the current document but not to the current section
 * until the visitor activates them, so WordPress' page-current state is noisy.
 */
function tower_exchange_nav_menu_classes(array $classes, WP_Post $item): array
{
    if (wp_parse_url($item->url, PHP_URL_FRAGMENT)) {
        $classes = array_values(
            array_filter(
                $classes,
                static fn(string $class): bool => ! str_starts_with($class, 'current-')
                    && ! str_starts_with($class, 'current_')
            )
        );
    }

    return $classes;
}
add_filter('nav_menu_css_class', 'tower_exchange_nav_menu_classes', 10, 2);

function tower_exchange_nav_menu_attributes(array $attributes, WP_Post $item): array
{
    if (wp_parse_url($item->url, PHP_URL_FRAGMENT)) {
        unset($attributes['aria-current']);
    }

    if ('_blank' === ($attributes['target'] ?? '')) {
        $relations = preg_split('/\s+/', (string) ($attributes['rel'] ?? ''), -1, PREG_SPLIT_NO_EMPTY);
        $relations = array_unique(array_merge($relations ?: array(), array('noopener', 'noreferrer')));
        $attributes['rel'] = implode(' ', $relations);
    }

    return $attributes;
}
add_filter('nav_menu_link_attributes', 'tower_exchange_nav_menu_attributes', 10, 2);
