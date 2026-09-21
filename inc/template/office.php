<?php
/**
 * Office section.
 *
 * @package TowerExchange
 */

$suffix = sanitize_html_class((string) $args);
$section_id = tower_exchange_section_id('contacts');
$primary = tower_exchange_link(
    tower_exchange_sub_field('primary_link'),
    array('url' => 'https://t.me/towerexchange_kyiv', 'title' => 'Зв’язатися перед візитом', 'target' => '_blank')
);
$map = tower_exchange_link(
    tower_exchange_sub_field('map_link'),
    array(
        'url'    => 'https://www.google.com/maps/search/?api=1&query=вул.+Мечникова+2+БЦ+Парус+Київ',
        'title'  => 'Відкрити на мапі',
        'target' => '_blank',
    )
);
?>
<section class="section office" id="<?php echo esc_attr($section_id); ?>" aria-labelledby="office-title-<?php echo esc_attr($suffix); ?>">
    <div class="container office__grid">
        <div class="office-map">
            <div class="office-map__streets" aria-hidden="true"></div>
            <span class="office-map__pin"><?php echo tower_exchange_icon('pin', 28); ?></span>
            <span class="office-map__label"><?php echo esc_html(tower_exchange_sub_field('map_label', 'Tower Exchange')); ?></span>
            <div class="office-map__coordinates"><?php echo esc_html(tower_exchange_sub_field('coordinates', '50.4382° N · 30.5231° E')); ?></div>
        </div>
        <div class="office__copy">
            <div class="eyebrow"><?php echo esc_html(tower_exchange_sub_field('eyebrow', 'Офіс · 05')); ?></div>
            <h2 id="office-title-<?php echo esc_attr($suffix); ?>"><?php echo esc_html(tower_exchange_sub_field('title', 'Tower Exchange у центрі Києва')); ?></h2>
            <address><?php echo tower_exchange_icon('pin', 23); ?><span><small><?php echo esc_html(tower_exchange_sub_field('address_label', 'БЦ «Парус»')); ?></small><?php echo esc_html(tower_exchange_sub_field('address', 'вул. Мечникова, 2, Київ')); ?></span></address>
            <p><?php echo esc_html(tower_exchange_sub_field('text', 'Перед візитом напишіть менеджеру, щоб уточнити актуальний курс, наявність і деталі операції.')); ?></p>
            <div class="office__actions">
                <a class="button button--dark" href="<?php echo esc_url($primary['url']); ?>"<?php echo $primary['target'] ? ' target="' . esc_attr($primary['target']) . '"' : ''; ?><?php echo tower_exchange_link_rel($primary) ? ' rel="' . esc_attr(tower_exchange_link_rel($primary)) . '"' : ''; ?>><?php echo esc_html($primary['title']); ?><?php echo tower_exchange_icon('arrow', 18); ?></a>
                <a class="button button--ghost" href="<?php echo esc_url($map['url']); ?>"<?php echo $map['target'] ? ' target="' . esc_attr($map['target']) . '"' : ''; ?><?php echo tower_exchange_link_rel($map) ? ' rel="' . esc_attr(tower_exchange_link_rel($map)) . '"' : ''; ?>><?php echo esc_html($map['title']); ?></a>
            </div>
        </div>
    </div>
</section>
