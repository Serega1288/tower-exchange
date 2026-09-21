<?php
/**
 * Small presentation helpers shared by theme templates.
 *
 * @package TowerExchange
 */

function tower_exchange_asset_uri(string $path = ''): string
{
    return trailingslashit(get_template_directory_uri() . '/assets') . ltrim($path, '/');
}

function tower_exchange_option(string $name, mixed $fallback = ''): mixed
{
    if (! function_exists('get_field') || ! function_exists('get_field_object')) {
        return $fallback;
    }

    $field = get_field_object($name, 'option', false, false);
    if (! $field) {
        return $fallback;
    }

    return get_field($name, 'option');
}

function tower_exchange_sub_field(string $name, mixed $fallback = ''): mixed
{
    if (! function_exists('get_sub_field') || ! function_exists('get_sub_field_object')) {
        return $fallback;
    }

    $field = get_sub_field_object($name, false, false, false);
    if (! $field) {
        return $fallback;
    }

    return get_sub_field($name);
}

function tower_exchange_image_url(mixed $image, string $fallback): string
{
    if (is_array($image) && ! empty($image['url'])) {
        return (string) $image['url'];
    }

    if (is_numeric($image)) {
        $url = wp_get_attachment_image_url((int) $image, 'full');
        if ($url) {
            return $url;
        }
    }

    if (is_string($image) && '' !== $image) {
        return $image;
    }

    return $fallback;
}

/**
 * Normalise an ACF Link value and provide a complete fallback.
 *
 * @return array{url:string,title:string,target:string}
 */
function tower_exchange_link(mixed $value, array $fallback): array
{
    $link = is_array($value) ? $value : array();

    return array(
        'url'    => (string) ($link['url'] ?? $fallback['url'] ?? '#'),
        'title'  => (string) ($link['title'] ?? $fallback['title'] ?? ''),
        'target' => (string) ($link['target'] ?? $fallback['target'] ?? ''),
    );
}

function tower_exchange_link_rel(array $link): string
{
    return '_blank' === ($link['target'] ?? '') ? 'noopener noreferrer' : '';
}

/**
 * Return a trusted Google Maps embed URL.
 *
 * Editors paste the URL from Google Maps' "Embed a map" dialog. Limiting the
 * host and path prevents the iframe field from becoming an arbitrary embed.
 */
function tower_exchange_google_maps_embed_url(mixed $value, string $fallback = ''): string
{
    foreach (array($value, $fallback) as $candidate) {
        if (! is_string($candidate) || '' === trim($candidate)) {
            continue;
        }

        $candidate = trim($candidate);
        $parts = wp_parse_url($candidate);
        if (! is_array($parts)
            || 'https' !== strtolower((string) ($parts['scheme'] ?? ''))
            || 'www.google.com' !== strtolower((string) ($parts['host'] ?? ''))
            || '/maps/embed' !== ($parts['path'] ?? '')
        ) {
            continue;
        }

        parse_str((string) ($parts['query'] ?? ''), $query);
        if (empty($query['pb']) || ! is_string($query['pb'])) {
            continue;
        }

        return esc_url_raw($candidate, array('https'));
    }

    return '';
}

/**
 * Return a stable anchor for the first section instance and a unique suffix
 * for any repeated Flexible Content layout.
 */
function tower_exchange_section_id(string $base): string
{
    static $occurrences = array();

    $base = sanitize_title($base);
    $occurrences[$base] = ($occurrences[$base] ?? 0) + 1;

    return 1 === $occurrences[$base]
        ? $base
        : $base . '-' . $occurrences[$base];
}

function tower_exchange_icon(string $name, int $size = 18): string
{
    $paths = array(
        'arrow'     => '<path d="M5 19 19 5"></path><path d="M8 5h11v11"></path>',
        'telegram'  => '<path d="m21 3-8 18-4.5-7L3 10l18-7Z"></path><path d="m8.5 14 5-4"></path>',
        'pin'       => '<path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"></path><circle cx="12" cy="10" r="2.5"></circle>',
        'swap'      => '<path d="M17 2l4 4-4 4"></path><path d="M3 11V9a3 3 0 0 1 3-3h15"></path><path d="m7 22-4-4 4-4"></path><path d="M21 13v2a3 3 0 0 1-3 3H3"></path>',
        'globe'     => '<circle cx="12" cy="12" r="9"></circle><path d="M3 12h18M12 3a15 15 0 0 1 0 18M12 3a15 15 0 0 0 0 18"></path>',
        'card'      => '<rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="M3 10h18M7 15h4"></path>',
        'shield'    => '<path d="M12 3 5 6v5c0 4.6 2.8 8 7 10 4.2-2 7-5.4 7-10V6l-7-3Z"></path><path d="m9 12 2 2 4-4"></path>',
        'bank'      => '<path d="m3 10 9-6 9 6"></path><path d="M5 10v8m5-8v8m4-8v8m5-8v8M3 21h18"></path>',
        'instagram' => '<rect x="3" y="3" width="18" height="18" rx="5"></rect><circle cx="12" cy="12" r="4"></circle><circle cx="17.5" cy="6.5" r=".7" fill="currentColor" stroke="none"></circle>',
        'chevron'   => '<path d="m7 10 5 5 5-5"></path>',
        'back'      => '<path d="M19 12H5"></path><path d="m11 18-6-6 6-6"></path>',
        'menu'      => '<path d="M4 7h16M4 12h16M4 17h16"></path>',
        'sun'       => '<circle cx="12" cy="12" r="3.5"></circle><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"></path>',
        'moon'      => '<path d="M20.5 15.5A8.5 8.5 0 0 1 8.5 3.5a8.5 8.5 0 1 0 12 12Z"></path>',
    );

    if (! isset($paths[$name])) {
        return '';
    }

    return sprintf(
        '<svg aria-hidden="true" class="icon" width="%1$d" height="%1$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">%2$s</svg>',
        $size,
        $paths[$name]
    );
}

function tower_exchange_default_menu(array $items, string $menu_class = 'menu'): void
{
    printf('<ul class="%s">', esc_attr($menu_class));
    foreach ($items as $item) {
        printf(
            '<li><a href="%1$s">%2$s%3$s</a></li>',
            esc_url($item['url']),
            esc_html($item['title']),
            ! empty($item['icon']) ? tower_exchange_icon('arrow', 18) : ''
        );
    }
    echo '</ul>';
}
