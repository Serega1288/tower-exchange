<?php
/**
 * Final CTA section.
 *
 * @package TowerExchange
 */

$suffix = sanitize_html_class((string) $args);
$primary = tower_exchange_link(
    tower_exchange_sub_field('primary_link'),
    array('url' => 'https://t.me/towerexchange_kyiv', 'title' => 'Написати в Telegram', 'target' => '_blank')
);
$secondary = tower_exchange_link(
    tower_exchange_sub_field('secondary_link'),
    array('url' => 'https://t.me/towerexchkyiv', 'title' => 'Дивитися актуальний курс', 'target' => '_blank')
);
?>
<section class="final-cta" aria-labelledby="final-cta-title-<?php echo esc_attr($suffix); ?>">
    <div class="final-cta__linework" aria-hidden="true"></div>
    <div class="container final-cta__inner">
        <div>
            <div class="eyebrow eyebrow--dark"><?php echo esc_html(tower_exchange_sub_field('eyebrow', 'Tower Exchange Kyiv')); ?></div>
            <h2 id="final-cta-title-<?php echo esc_attr($suffix); ?>"><?php echo esc_html(tower_exchange_sub_field('title', 'Уточніть курс і формат операції')); ?></h2>
            <p><?php echo esc_html(tower_exchange_sub_field('text', 'Напишіть менеджеру, щоб підтвердити актуальний курс, наявність і подальші кроки.')); ?></p>
        </div>
        <div class="final-cta__actions">
            <a class="button button--primary" href="<?php echo esc_url($primary['url']); ?>"<?php echo $primary['target'] ? ' target="' . esc_attr($primary['target']) . '"' : ''; ?><?php echo tower_exchange_link_rel($primary) ? ' rel="' . esc_attr(tower_exchange_link_rel($primary)) . '"' : ''; ?>><?php echo tower_exchange_icon('telegram', 20); ?><?php echo esc_html($primary['title']); ?></a>
            <a class="button button--outline-light" href="<?php echo esc_url($secondary['url']); ?>"<?php echo $secondary['target'] ? ' target="' . esc_attr($secondary['target']) . '"' : ''; ?><?php echo tower_exchange_link_rel($secondary) ? ' rel="' . esc_attr(tower_exchange_link_rel($secondary)) . '"' : ''; ?>><?php echo esc_html($secondary['title']); ?></a>
        </div>
    </div>
</section>
