<?php
/**
 * Worldwide coverage and payments section.
 *
 * @package TowerExchange
 */

$suffix     = sanitize_html_class((string) $args);
$section_id = tower_exchange_section_id('coverage');
$payments_id = tower_exchange_section_id('payment-methods');
$cta        = tower_exchange_link(
    tower_exchange_sub_field('cta_link'),
    array('url' => 'https://t.me/towerexchange_kyiv', 'title' => 'Уточнити місто та умови', 'target' => '_blank')
);
$directions = array_filter(
    array(
        tower_exchange_sub_field('direction_cash_crypto', 'Готівка → USDT'),
        tower_exchange_sub_field('direction_crypto_cash', 'USDT → готівка'),
    )
);
?>
<section class="section coverage" id="<?php echo esc_attr($section_id); ?>" aria-labelledby="coverage-title-<?php echo esc_attr($suffix); ?>">
    <div class="coverage__grid" aria-hidden="true"></div>
    <div class="container coverage__inner">
        <div class="section-heading section-heading--split coverage__header">
            <div>
                <div class="eyebrow eyebrow--dark"><?php echo esc_html(tower_exchange_sub_field('eyebrow', 'Географія · World Desk')); ?></div>
                <h2 id="coverage-title-<?php echo esc_attr($suffix); ?>"><?php echo esc_html(tower_exchange_sub_field('title', 'Працюємо по всьому світу')); ?></h2>
            </div>
            <div class="coverage__intro">
                <p><?php echo esc_html(tower_exchange_sub_field('intro', 'Оберіть країну й потрібне місто. Доступність і деталі операції підтверджує менеджер.')); ?></p>
                <?php if ($directions) : ?>
                    <div class="coverage__directions" aria-label="Доступні напрямки обміну">
                        <?php foreach ($directions as $direction) : ?>
                            <span><?php echo esc_html($direction); ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="coverage__panels">
            <div class="coverage__countries">
                <div class="coverage__panel-heading">
                    <h3><?php echo esc_html(tower_exchange_sub_field('countries_title', 'Країни та міста')); ?></h3>
                    <span>WORLD / CASH</span>
                </div>
                <?php if (have_rows('countries')) : ?>
                    <div class="coverage__country-list">
                        <?php while (have_rows('countries')) : the_row(); ?>
                            <article class="coverage__country">
                                <span class="coverage__country-code" aria-hidden="true"><?php echo esc_html((string) get_sub_field('code')); ?></span>
                                <div>
                                    <h4><?php echo esc_html((string) get_sub_field('country')); ?></h4>
                                    <p><?php echo esc_html((string) get_sub_field('cities')); ?></p>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>
                <?php endif; ?>
                <p class="coverage__other-cities"><span>+</span><?php echo esc_html(tower_exchange_sub_field('other_cities', 'Інші міста — за запитом!')); ?></p>
            </div>

            <aside class="coverage__payments" id="<?php echo esc_attr($payments_id); ?>" aria-labelledby="coverage-payments-title-<?php echo esc_attr($suffix); ?>">
                <div class="coverage__panel-heading">
                    <h3 id="coverage-payments-title-<?php echo esc_attr($suffix); ?>"><?php echo esc_html(tower_exchange_sub_field('payments_title', 'Перекази та оплати')); ?></h3>
                    <span>PAY / ROUTES</span>
                </div>
                <?php if (have_rows('payments')) : ?>
                    <ul class="coverage__payment-list">
                        <?php while (have_rows('payments')) : the_row(); ?>
                            <li><span><?php echo esc_html((string) get_sub_field('label')); ?></span><strong><?php echo esc_html((string) get_sub_field('details')); ?></strong></li>
                        <?php endwhile; ?>
                    </ul>
                <?php endif; ?>
                <div class="coverage__same-day"><span aria-hidden="true">✦</span><?php echo esc_html(tower_exchange_sub_field('same_day_text', 'Видача в той самий день!')); ?></div>
                <a class="button button--dark coverage__cta" href="<?php echo esc_url($cta['url']); ?>"<?php echo $cta['target'] ? ' target="' . esc_attr($cta['target']) . '"' : ''; ?><?php echo tower_exchange_link_rel($cta) ? ' rel="' . esc_attr(tower_exchange_link_rel($cta)) . '"' : ''; ?>><?php echo esc_html($cta['title']); ?><?php echo tower_exchange_icon('arrow', 18); ?></a>
            </aside>
        </div>
    </div>
</section>
