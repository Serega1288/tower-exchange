<?php
/**
 * Reusable text section.
 *
 * @package TowerExchange
 */

$raw_suffix = (string) $args;
$suffix = sanitize_html_class($raw_suffix);
$title = (string) tower_exchange_sub_field('title', get_the_title());
$content = (string) tower_exchange_sub_field('content', '');

if ('' === trim($title) && '' === trim(wp_strip_all_tags($content))) {
    return;
}

$heading_id = wp_unique_id(
    'simple-text-title-' . ('' !== $suffix ? $suffix . '-' : '')
);
$is_first_section = 1 === preg_match('/^0(?:-|$)/', $raw_suffix);
?>
<section class="simple-text section"<?php echo '' !== $title ? ' aria-labelledby="' . esc_attr($heading_id) . '"' : ''; ?>>
    <div class="container">
        <div class="simple-text__inner">
            <a class="back-link" href="<?php echo esc_url(home_url('/')); ?>" data-back-link>
                <?php echo tower_exchange_icon('back', 18); ?><?php esc_html_e('Повернутися назад', 'tower-exchange'); ?>
            </a>

            <?php if ('' !== $title) : ?>
                <?php if ($is_first_section) : ?>
                    <h1 id="<?php echo esc_attr($heading_id); ?>"><?php echo esc_html($title); ?></h1>
                <?php else : ?>
                    <h2 id="<?php echo esc_attr($heading_id); ?>"><?php echo esc_html($title); ?></h2>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ('' !== trim($content)) : ?>
                <div class="simple-text__content">
                    <?php echo wp_kses_post($content); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
