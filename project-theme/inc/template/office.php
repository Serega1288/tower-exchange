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
$address_label = (string) tower_exchange_sub_field('address_label', 'БЦ «Парус»');
$address = (string) tower_exchange_sub_field('address', 'вул. Мечникова, 2, Київ');
$map_label = (string) tower_exchange_sub_field('map_label', 'Tower Exchange');
$map_embed_url = tower_exchange_google_maps_embed_url(
    tower_exchange_sub_field('map_embed_url'),
    'https://www.google.com/maps/embed?origin=mfe&pb=!1m2!2m1!1z0JHQpiDQn9Cw0YDRg9GBLCDQstGD0LsuINCc0LXRh9C90LjQutC-0LLQsCwgMiwg0JrQuNGX0LI'
);
$map_title = implode(': ', array_filter(array($map['title'], implode(', ', array_filter(array($map_label, $address_label, $address))))));
?>
<section class="section office" id="<?php echo esc_attr($section_id); ?>" aria-labelledby="office-title-<?php echo esc_attr($suffix); ?>">
    <div class="container office__grid">
        <div class="office-map">
            <iframe class="office-map__frame" src="<?php echo esc_url($map_embed_url, array('https')); ?>" title="<?php echo esc_attr($map_title); ?>" loading="lazy" allowfullscreen referrerpolicy="strict-origin-when-cross-origin"></iframe>
        </div>
        <div class="office__copy">
            <div class="eyebrow"><?php echo esc_html(tower_exchange_sub_field('eyebrow', 'Офіс · 05')); ?></div>
            <h2 id="office-title-<?php echo esc_attr($suffix); ?>"><?php echo esc_html(tower_exchange_sub_field('title', 'Tower Exchange у центрі Києва')); ?></h2>
            <address><?php echo tower_exchange_icon('pin', 23); ?><span><small><?php echo esc_html($address_label); ?></small><?php echo esc_html($address); ?></span></address>
            <p><?php echo esc_html(tower_exchange_sub_field('text', 'Перед візитом напишіть менеджеру, щоб уточнити актуальний курс, наявність і деталі операції.')); ?></p>
            <div class="office__actions">
                <a class="button button--dark" href="<?php echo esc_url($primary['url']); ?>"<?php echo $primary['target'] ? ' target="' . esc_attr($primary['target']) . '"' : ''; ?><?php echo tower_exchange_link_rel($primary) ? ' rel="' . esc_attr(tower_exchange_link_rel($primary)) . '"' : ''; ?>><?php echo esc_html($primary['title']); ?><?php echo tower_exchange_icon('arrow', 18); ?></a>
                <a class="button button--ghost" href="<?php echo esc_url($map['url']); ?>"<?php echo $map['target'] ? ' target="' . esc_attr($map['target']) . '"' : ''; ?><?php echo tower_exchange_link_rel($map) ? ' rel="' . esc_attr(tower_exchange_link_rel($map)) . '"' : ''; ?>><?php echo esc_html($map['title']); ?></a>
            </div>
        </div>
    </div>
</section>
