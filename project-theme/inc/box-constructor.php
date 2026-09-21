<?php
/**
 * Flexible Content dispatcher.
 *
 * @package TowerExchange
 */

$constructor_name = '';
if (is_array($args)) {
    $constructor_name = isset($args['constructor_name']) ? (string) $args['constructor_name'] : '';
} elseif (is_scalar($args) || null === $args) {
    $constructor_name = (string) $args;
}

if (! function_exists('have_rows') || ! have_rows('constructor')) {
    return;
}

$section_index = 0;
while (have_rows('constructor')) :
    the_row();
    $section_suffix = '' !== $constructor_name
        ? $section_index . '-' . sanitize_html_class($constructor_name)
        : (string) $section_index;
    ++$section_index;

    if (get_sub_field('disable_block')) {
        continue;
    }

    switch (get_row_layout()) {
        case 'template-hero':
            get_template_part('inc/template/hero', null, $section_suffix);
            break;
        case 'template-calculator':
            get_template_part('inc/template/calculator', null, $section_suffix);
            break;
        case 'template-services':
            get_template_part('inc/template/services', null, $section_suffix);
            break;
        case 'template-process':
            get_template_part('inc/template/process', null, $section_suffix);
            break;
        case 'template-security':
            get_template_part('inc/template/security', null, $section_suffix);
            break;
        case 'template-office':
            get_template_part('inc/template/office', null, $section_suffix);
            break;
        case 'template-social':
            get_template_part('inc/template/social', null, $section_suffix);
            break;
        case 'template-faq':
            get_template_part('inc/template/faq', null, $section_suffix);
            break;
        case 'template-final-cta':
            get_template_part('inc/template/final-cta', null, $section_suffix);
            break;
    }
endwhile;
