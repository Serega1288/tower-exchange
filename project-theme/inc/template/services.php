<?php
/**
 * Services section.
 *
 * @package TowerExchange
 */

$suffix = sanitize_html_class((string) $args);
$section_id = tower_exchange_section_id('services');
$allowed_icons = array('swap', 'globe', 'card');
?>
<section class="section services" id="<?php echo esc_attr($section_id); ?>" aria-labelledby="services-title-<?php echo esc_attr($suffix); ?>">
    <div class="container">
        <div class="section-heading">
            <div class="eyebrow"><?php echo esc_html(tower_exchange_sub_field('eyebrow', 'Послуги · 02')); ?></div>
            <h2 id="services-title-<?php echo esc_attr($suffix); ?>"><?php echo esc_html(tower_exchange_sub_field('title', 'Потрібний напрям —')); ?><span><?php echo esc_html(tower_exchange_sub_field('title_accent', 'в одному контакті')); ?></span></h2>
        </div>
        <?php if (have_rows('items')) : ?>
            <div class="services-grid">
                <?php $item_number = 1; ?>
                <?php while (have_rows('items')) : the_row(); ?>
                    <?php
                    $icon = (string) get_sub_field('icon');
                    $icon = in_array($icon, $allowed_icons, true) ? $icon : 'swap';
                    $link = tower_exchange_link(get_sub_field('link'), array('url' => '#', 'title' => 'Уточнити деталі', 'target' => ''));
                    ?>
                    <article class="service-card">
                        <div class="service-card__top"><span class="service-card__number"><?php echo esc_html(str_pad((string) $item_number, 2, '0', STR_PAD_LEFT)); ?></span><span class="service-card__icon"><?php echo tower_exchange_icon($icon, 27); ?></span></div>
                        <h3><?php echo esc_html((string) get_sub_field('title')); ?></h3>
                        <p><?php echo esc_html((string) get_sub_field('text')); ?></p>
                        <a class="text-link" href="<?php echo esc_url($link['url']); ?>"<?php echo $link['target'] ? ' target="' . esc_attr($link['target']) . '"' : ''; ?><?php echo tower_exchange_link_rel($link) ? ' rel="' . esc_attr(tower_exchange_link_rel($link)) . '"' : ''; ?>><?php echo esc_html($link['title']); ?><?php echo tower_exchange_icon('arrow', 17); ?></a>
                    </article>
                    <?php ++$item_number; ?>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
