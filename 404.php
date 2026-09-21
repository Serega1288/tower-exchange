<?php
/**
 * The template for displaying 404 pages.
 *
 * @package TowerExchange
 */

$manager = tower_exchange_link(
    tower_exchange_option('tower_header_cta'),
    array(
        'url'    => 'https://t.me/towerexchange_kyiv',
        'title'  => __('Написати менеджеру', 'tower-exchange'),
        'target' => '_blank',
    )
);

get_header();
?>
<main id="main-content" class="error-page section" role="main" aria-labelledby="error-page-title">
    <div class="container error-page__inner">
        <div class="error-page__code" aria-hidden="true">404</div>
        <div class="error-page__content">
            <a class="back-link" href="<?php echo esc_url(home_url('/')); ?>" data-back-link>
                <?php echo tower_exchange_icon('back', 18); ?><?php esc_html_e('Повернутися назад', 'tower-exchange'); ?>
            </a>
            <p class="eyebrow"><?php esc_html_e('Tower Exchange Kyiv', 'tower-exchange'); ?></p>
            <h1 id="error-page-title"><?php esc_html_e('Сторінку не знайдено', 'tower-exchange'); ?></h1>
            <p><?php esc_html_e('Можливо, посилання застаріло або адресу введено з помилкою. Поверніться на головну чи напишіть менеджеру.', 'tower-exchange'); ?></p>
            <div class="error-page__actions">
                <a class="button button--primary" href="<?php echo esc_url(home_url('/')); ?>">
                    <?php esc_html_e('На головну', 'tower-exchange'); ?><?php echo tower_exchange_icon('arrow', 18); ?>
                </a>
                <a class="button button--ghost" href="<?php echo esc_url($manager['url']); ?>"<?php echo $manager['target'] ? ' target="' . esc_attr($manager['target']) . '"' : ''; ?><?php echo tower_exchange_link_rel($manager) ? ' rel="' . esc_attr(tower_exchange_link_rel($manager)) . '"' : ''; ?>>
                    <?php echo tower_exchange_icon('telegram', 20); ?><?php echo esc_html($manager['title']); ?>
                </a>
            </div>
        </div>
    </div>
</main>
<?php get_footer(); ?>
