<?php
/**
 * Optional calculator/request section.
 *
 * @package TowerExchange
 */

$suffix = sanitize_html_class((string) $args);
$cta = tower_exchange_link(
    tower_exchange_sub_field('cta_link'),
    array('url' => 'https://t.me/towerexchange_kyiv', 'title' => 'Уточнити розрахунок', 'target' => '_blank')
);
?>
<section class="calculator-section" data-feature="calculator" aria-labelledby="calculator-title-<?php echo esc_attr($suffix); ?>">
    <div class="container">
        <div class="section-heading section-heading--split">
            <div><div class="eyebrow"><?php echo esc_html(tower_exchange_sub_field('eyebrow', 'Опційний модуль · 01')); ?></div><h2 id="calculator-title-<?php echo esc_attr($suffix); ?>"><?php echo esc_html(tower_exchange_sub_field('title', 'Попередній розрахунок')); ?></h2></div>
            <p><?php echo esc_html(tower_exchange_sub_field('intro', 'Вкажіть напрям і суму. Остаточний курс та наявність підтверджує менеджер Tower Exchange.')); ?></p>
        </div>
        <div class="calculator-card">
            <div class="calculator-card__accent" aria-hidden="true"><span><?php echo esc_html(tower_exchange_sub_field('accent_primary', 'CALC / REQUEST')); ?></span><span><?php echo esc_html(tower_exchange_sub_field('accent_secondary', 'KYIV 01')); ?></span></div>
            <div class="direction-switch" role="group" aria-label="<?php esc_attr_e('Напрям обміну', 'tower-exchange'); ?>">
                <button type="button" class="is-active" aria-pressed="true"><?php echo esc_html(tower_exchange_sub_field('direction_crypto_cash', 'USDT → готівка')); ?></button>
                <button type="button" aria-pressed="false"><?php echo esc_html(tower_exchange_sub_field('direction_cash_crypto', 'Готівка → USDT')); ?></button>
            </div>
            <div class="calculator-grid">
                <label class="amount-field">
                    <span><?php echo esc_html(tower_exchange_sub_field('amount_label', 'Віддаєте')); ?></span>
                    <span class="amount-field__control"><input inputmode="decimal" placeholder="0.00" aria-label="<?php esc_attr_e('Сума: USDT', 'tower-exchange'); ?>"><strong>USDT</strong></span>
                </label>
                <button class="swap-button" type="button" aria-label="<?php esc_attr_e('Змінити напрям обміну', 'tower-exchange'); ?>"><?php echo tower_exchange_icon('swap', 22); ?></button>
                <div class="amount-field amount-field--result">
                    <span><?php echo esc_html(tower_exchange_sub_field('result_label', 'Отримуєте')); ?></span>
                    <span class="amount-field__control"><b><?php echo esc_html(tower_exchange_sub_field('result_text', 'За запитом')); ?></b><strong>Готівка</strong></span>
                </div>
                <div class="calculator-action">
                    <div><span class="status-dot"></span><small><?php echo esc_html(tower_exchange_sub_field('note', 'Курс не фіксується автоматично')); ?></small></div>
                    <a class="button button--dark" href="<?php echo esc_url($cta['url']); ?>"<?php echo $cta['target'] ? ' target="' . esc_attr($cta['target']) . '"' : ''; ?><?php echo tower_exchange_link_rel($cta) ? ' rel="' . esc_attr(tower_exchange_link_rel($cta)) . '"' : ''; ?>><?php echo esc_html($cta['title']); ?><?php echo tower_exchange_icon('arrow', 18); ?></a>
                </div>
            </div>
        </div>
    </div>
</section>
