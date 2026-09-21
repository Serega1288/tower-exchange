<?php
/**
 * Site header.
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
$header_cta     = tower_exchange_link(
    tower_exchange_option('tower_header_cta'),
    array(
        'url'    => 'https://t.me/towerexchange_kyiv',
        'title'  => 'Дізнатися курс',
        'target' => '_blank',
    )
);
$desktop_items = array(
    array('title' => 'Послуги', 'url' => home_url('/#services')),
    array('title' => 'Як це працює', 'url' => home_url('/#process')),
    array('title' => 'Безпека', 'url' => home_url('/#security')),
    array('title' => 'Instagram', 'url' => home_url('/#instagram')),
    array('title' => 'Контакти', 'url' => home_url('/#contacts')),
);
?>
<!doctype html>
<html <?php language_attributes(); ?> data-theme="light" data-default-theme="light" data-variant="2" data-asset-base="<?php echo esc_url(tower_exchange_asset_uri()); ?>">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f2efe7">
    <link rel="icon" type="image/png" href="<?php echo esc_url($logo_light); ?>" data-logo-light="<?php echo esc_url($logo_light); ?>" data-logo-dark="<?php echo esc_url($logo_dark); ?>">
    <script>
        (() => {
            const root = document.documentElement;
            let saved = null;
            try { saved = localStorage.getItem('tower-exchange-theme'); } catch (error) {}
            const theme = saved === 'dark' ? 'dark' : 'light';
            root.dataset.theme = theme;
            root.style.colorScheme = theme;
        })();
    </script>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="app-shell">
    <a class="skip-link" href="#main-content"><?php esc_html_e('Перейти до основного вмісту', 'tower-exchange'); ?></a>
    <header class="site-header">
        <div class="container header__inner">
            <a class="brand" href="<?php echo esc_url(home_url('/#top')); ?>" aria-label="<?php esc_attr_e('Tower Exchange — на початок сторінки', 'tower-exchange'); ?>">
                <span class="brand__mark">
                    <img src="<?php echo esc_url($logo_light); ?>" data-logo-light="<?php echo esc_url($logo_light); ?>" data-logo-dark="<?php echo esc_url($logo_dark); ?>" alt="">
                </span>
                <span class="brand__copy">
                    <strong><?php echo esc_html($brand_name); ?></strong>
                    <small><?php echo esc_html($brand_subtitle); ?></small>
                </span>
            </a>

            <nav class="desktop-nav" aria-label="<?php esc_attr_e('Основна навігація', 'tower-exchange'); ?>">
                <?php
                if (has_nav_menu('header-desktop')) {
                    wp_nav_menu(
                        array(
                            'theme_location' => 'header-desktop',
                            'container'      => false,
                            'menu_class'     => 'menu',
                            'depth'          => 1,
                            'fallback_cb'    => false,
                        )
                    );
                } else {
                    tower_exchange_default_menu($desktop_items);
                }
                ?>
            </nav>

            <div class="header__actions">
                <span class="language" aria-label="<?php esc_attr_e('Мова сайту — українська', 'tower-exchange'); ?>">UA</span>
                <button class="theme-toggle" type="button" role="switch" data-current-theme="light" aria-label="<?php esc_attr_e('Темна тема', 'tower-exchange'); ?>" aria-checked="false" title="<?php esc_attr_e('Увімкнути темну тему', 'tower-exchange'); ?>">
                    <span class="theme-toggle__option theme-toggle__option--light"><?php echo tower_exchange_icon('sun', 15); ?><span><?php esc_html_e('Світла', 'tower-exchange'); ?></span></span>
                    <span class="theme-toggle__option theme-toggle__option--dark"><?php echo tower_exchange_icon('moon', 14); ?><span><?php esc_html_e('Темна', 'tower-exchange'); ?></span></span>
                    <span class="theme-toggle__thumb" aria-hidden="true"></span>
                </button>
                <a class="button button--small button--dark" href="<?php echo esc_url($header_cta['url']); ?>"<?php echo $header_cta['target'] ? ' target="' . esc_attr($header_cta['target']) . '"' : ''; ?><?php echo tower_exchange_link_rel($header_cta) ? ' rel="' . esc_attr(tower_exchange_link_rel($header_cta)) . '"' : ''; ?>>
                    <?php echo esc_html($header_cta['title']); ?><?php echo tower_exchange_icon('arrow', 17); ?>
                </a>
                <button class="menu-button" type="button" aria-expanded="false" aria-controls="mobile-menu" aria-label="<?php esc_attr_e('Відкрити меню', 'tower-exchange'); ?>">
                    <?php echo tower_exchange_icon('menu', 23); ?>
                </button>
            </div>
        </div>

        <div id="mobile-menu" class="mobile-menu" aria-hidden="true" inert>
            <nav class="container" aria-label="<?php esc_attr_e('Мобільна навігація', 'tower-exchange'); ?>">
                <?php
                $mobile_items = array_map(
                    static fn(array $item): array => $item + array('icon' => true),
                    $desktop_items
                );
                if (has_nav_menu('header-mobile')) {
                    wp_nav_menu(
                        array(
                            'theme_location' => 'header-mobile',
                            'container'      => false,
                            'menu_class'     => 'menu',
                            'depth'          => 1,
                            'fallback_cb'    => false,
                            'link_after'     => tower_exchange_icon('arrow', 18),
                        )
                    );
                } else {
                    tower_exchange_default_menu($mobile_items);
                }
                ?>
            </nav>
        </div>
    </header>
