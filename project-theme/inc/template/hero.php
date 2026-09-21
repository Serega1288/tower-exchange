<?php
/**
 * Hero section.
 *
 * @package TowerExchange
 */

$suffix = sanitize_html_class((string) $args);
$section_id = tower_exchange_section_id('top');
$primary = tower_exchange_link(
    tower_exchange_sub_field('primary_link'),
    array('url' => 'https://t.me/towerexchange_kyiv', 'title' => 'Дізнатися курс та зробити обмін', 'target' => '_blank')
);
$secondary = tower_exchange_link(
    tower_exchange_sub_field('secondary_link'),
    array('url' => '#process', 'title' => 'Як відбувається обмін', 'target' => '')
);
$logo_light = tower_exchange_image_url(tower_exchange_option('tower_logo_light'), tower_exchange_asset_uri('brand/tower-light.png'));
$logo_dark  = tower_exchange_image_url(tower_exchange_option('tower_logo_dark'), tower_exchange_asset_uri('brand/tower-dark.png'));
?>
<section class="hero" id="<?php echo esc_attr($section_id); ?>" aria-labelledby="hero-title-<?php echo esc_attr($suffix); ?>">
    <div class="container hero__grid">
        <div class="hero__content">
            <div class="eyebrow"><span class="status-dot"></span><?php echo esc_html(tower_exchange_sub_field('eyebrow', 'Київ · офіс у БЦ «Парус»')); ?></div>
            <h1 id="hero-title-<?php echo esc_attr($suffix); ?>">
                <?php echo esc_html(tower_exchange_sub_field('title', 'USDT ↔ CASH')); ?>
                <span><?php echo esc_html(tower_exchange_sub_field('subtitle', 'Kyiv exchange desk')); ?></span>
            </h1>
            <p class="hero__lead"><?php echo esc_html(tower_exchange_sub_field('lead', 'Готівка ↔ USDT, перестановка кешу по всьому світу та безготівкові перекази. Актуальний курс і наявність уточнюйте у менеджера.')); ?></p>
            <div class="hero__actions">
                <a class="button button--primary" href="<?php echo esc_url($primary['url']); ?>"<?php echo $primary['target'] ? ' target="' . esc_attr($primary['target']) . '"' : ''; ?><?php echo tower_exchange_link_rel($primary) ? ' rel="' . esc_attr(tower_exchange_link_rel($primary)) . '"' : ''; ?>>
                    <?php echo tower_exchange_icon('telegram', 20); ?><?php echo esc_html($primary['title']); ?>
                </a>
                <a class="button button--ghost" href="<?php echo esc_url($secondary['url']); ?>"<?php echo $secondary['target'] ? ' target="' . esc_attr($secondary['target']) . '"' : ''; ?><?php echo tower_exchange_link_rel($secondary) ? ' rel="' . esc_attr(tower_exchange_link_rel($secondary)) . '"' : ''; ?>>
                    <?php echo esc_html($secondary['title']); ?><?php echo tower_exchange_icon('arrow', 19); ?>
                </a>
            </div>
            <div class="hero__location">
                <span class="icon-box"><?php echo tower_exchange_icon('pin', 20); ?></span>
                <span>
                    <small><?php echo esc_html(tower_exchange_sub_field('location_label', 'Адреса офісу')); ?></small>
                    <strong><?php echo esc_html(tower_exchange_sub_field('location_text', 'вул. Мечникова, 2 · БЦ «Парус»')); ?></strong>
                </span>
            </div>
        </div>
        <div class="hero-art" aria-label="<?php esc_attr_e('Фірмовий знак Tower Exchange', 'tower-exchange'); ?>">
            <div class="hero-art__grid" aria-hidden="true"></div>
            <div class="orbit orbit--outer" aria-hidden="true"><span class="orbit__dot"></span></div>
            <div class="orbit orbit--inner" aria-hidden="true"></div>
            <div class="hero-art__logo">
                <img src="<?php echo esc_url($logo_light); ?>" data-logo-light="<?php echo esc_url($logo_light); ?>" data-logo-dark="<?php echo esc_url($logo_dark); ?>" alt="<?php esc_attr_e('Логотип Tower Exchange', 'tower-exchange'); ?>">
            </div>
            <div class="hero-art__tag hero-art__tag--top"><span><?php echo esc_html(tower_exchange_sub_field('art_top_code', '01')); ?></span><?php echo esc_html(tower_exchange_sub_field('art_top_text', 'Офіційний контакт')); ?></div>
            <div class="hero-art__tag hero-art__tag--bottom"><span><?php echo esc_html(tower_exchange_sub_field('art_bottom_code', 'UA')); ?></span><?php echo esc_html(tower_exchange_sub_field('art_bottom_text', 'Kyiv · Parus')); ?></div>
            <div class="hero-art__axis" aria-hidden="true"><?php echo esc_html(tower_exchange_sub_field('coordinates', '50°26′17″N')); ?></div>
        </div>
    </div>

    <?php if (have_rows('trust_items')) : ?>
        <div class="container trust-strip" aria-label="<?php esc_attr_e('Ключова інформація', 'tower-exchange'); ?>">
            <?php while (have_rows('trust_items')) : the_row(); ?>
                <?php $item_link = get_sub_field('link'); ?>
                <?php if (is_array($item_link) && ! empty($item_link['url'])) : ?>
                    <?php $item_link = tower_exchange_link($item_link, array()); ?>
                    <a class="trust-strip__link" href="<?php echo esc_url($item_link['url']); ?>"<?php echo $item_link['target'] ? ' target="' . esc_attr($item_link['target']) . '"' : ''; ?><?php echo tower_exchange_link_rel($item_link) ? ' rel="' . esc_attr(tower_exchange_link_rel($item_link)) . '"' : ''; ?>>
                        <?php echo esc_html($item_link['title']); ?><?php echo tower_exchange_icon('arrow', 18); ?>
                    </a>
                <?php else : ?>
                    <div><small><?php echo esc_html((string) get_sub_field('label')); ?></small><strong><?php echo esc_html((string) get_sub_field('value')); ?></strong></div>
                <?php endif; ?>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</section>
