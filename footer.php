<?php
/**
 * Site footer.
 *
 * @package TowerExchange
 */

$logo_light = tower_exchange_image_url(
    tower_exchange_option('tower_logo_light'),
    tower_exchange_asset_uri('brand/tower-light.png')
);
$logo_dark = tower_exchange_image_url(
    tower_exchange_option('tower_logo_dark'),
    tower_exchange_asset_uri('brand/tower-dark.png')
);
$brand_name     = tower_exchange_option('tower_brand_name', 'Tower Exchange');
$brand_subtitle = tower_exchange_option('tower_brand_subtitle', 'Kyiv · БЦ «Парус»');
$address_label  = tower_exchange_option('tower_footer_address_label', 'Офіс у Києві');
$address        = tower_exchange_option('tower_footer_address', 'вул. Мечникова, 2 · БЦ «Парус»');
$copyright      = tower_exchange_option('tower_copyright', '© ' . wp_date('Y') . ' Tower Exchange Kyiv');
$mobile_cta     = tower_exchange_link(
    tower_exchange_option('tower_mobile_cta'),
    array(
        'url'    => 'https://t.me/towerexchange_kyiv',
        'title'  => 'Дізнатися курс',
        'target' => '_blank',
    )
);
?>
    <footer class="site-footer">
        <div class="container footer__top">
            <a class="brand" href="<?php echo esc_url(home_url('/#top')); ?>" aria-label="<?php esc_attr_e('Tower Exchange — на початок сторінки', 'tower-exchange'); ?>">
                <span class="brand__mark"><img src="<?php echo esc_url($logo_light); ?>" data-logo-light="<?php echo esc_url($logo_light); ?>" data-logo-dark="<?php echo esc_url($logo_dark); ?>" alt=""></span>
                <span class="brand__copy"><strong><?php echo esc_html($brand_name); ?></strong><small><?php echo esc_html($brand_subtitle); ?></small></span>
            </a>
            <div class="footer__address"><small><?php echo esc_html($address_label); ?></small><span><?php echo esc_html($address); ?></span></div>
            <div class="footer__social">
                <?php
                if (has_nav_menu('footer-social')) {
                    wp_nav_menu(
                        array(
                            'theme_location' => 'footer-social',
                            'container'      => false,
                            'menu_class'     => 'menu',
                            'depth'          => 1,
                            'fallback_cb'    => false,
                        )
                    );
                } else {
                    tower_exchange_default_menu(
                        array(
                            array('title' => 'Telegram', 'url' => 'https://t.me/towerexchange_kyiv'),
                            array('title' => 'Instagram', 'url' => 'https://www.instagram.com/tower.exchange.kyiv/'),
                        )
                    );
                }
                ?>
            </div>
        </div>
        <div class="container footer__bottom">
            <span><?php echo esc_html($copyright); ?></span>
            <div>
                <span class="future-link"><?php esc_html_e('Політика конфіденційності · готується', 'tower-exchange'); ?></span>
                <span class="future-link"><?php esc_html_e('Умови користування · готуються', 'tower-exchange'); ?></span>
            </div>
        </div>
    </footer>
    <a class="mobile-cta" href="<?php echo esc_url($mobile_cta['url']); ?>"<?php echo $mobile_cta['target'] ? ' target="' . esc_attr($mobile_cta['target']) . '"' : ''; ?><?php echo tower_exchange_link_rel($mobile_cta) ? ' rel="' . esc_attr(tower_exchange_link_rel($mobile_cta)) . '"' : ''; ?>>
        <?php echo tower_exchange_icon('telegram', 20); ?><?php echo esc_html($mobile_cta['title']); ?>
    </a>
</div>
<?php wp_footer(); ?>
</body>
</html>
