<?php
/**
 * Process section.
 *
 * @package TowerExchange
 */

$suffix = sanitize_html_class((string) $args);
$section_id = tower_exchange_section_id('process');
$cta = tower_exchange_link(
    tower_exchange_sub_field('cta_link'),
    array('url' => 'https://t.me/towerexchange_kyiv', 'title' => 'Написати менеджеру', 'target' => '_blank')
);
?>
<section class="section process" id="<?php echo esc_attr($section_id); ?>" aria-labelledby="process-title-<?php echo esc_attr($suffix); ?>">
    <div class="container process__grid">
        <div class="process__intro">
            <div class="eyebrow"><?php echo esc_html(tower_exchange_sub_field('eyebrow', 'Як це працює · 03')); ?></div>
            <h2 id="process-title-<?php echo esc_attr($suffix); ?>"><?php echo esc_html(tower_exchange_sub_field('title', 'Від запиту до узгодженої операції')); ?></h2>
            <p><?php echo esc_html(tower_exchange_sub_field('intro', 'Без зайвих форм: почніть діалог у Telegram і отримайте актуальні умови для вашого напрямку.')); ?></p>
            <a class="button button--primary" href="<?php echo esc_url($cta['url']); ?>"<?php echo $cta['target'] ? ' target="' . esc_attr($cta['target']) . '"' : ''; ?><?php echo tower_exchange_link_rel($cta) ? ' rel="' . esc_attr(tower_exchange_link_rel($cta)) . '"' : ''; ?>><?php echo tower_exchange_icon('telegram', 20); ?><?php echo esc_html($cta['title']); ?></a>
        </div>
        <?php if (have_rows('steps')) : ?>
            <ol class="steps-list">
                <?php $step_number = 1; ?>
                <?php while (have_rows('steps')) : the_row(); ?>
                    <li><span class="steps-list__number"><?php echo esc_html(str_pad((string) $step_number, 2, '0', STR_PAD_LEFT)); ?></span><div><h3><?php echo esc_html((string) get_sub_field('title')); ?></h3><p><?php echo esc_html((string) get_sub_field('text')); ?></p></div></li>
                    <?php ++$step_number; ?>
                <?php endwhile; ?>
            </ol>
        <?php endif; ?>
    </div>
</section>
