<?php
/**
 * Security / official contacts section.
 *
 * @package TowerExchange
 */

$suffix = sanitize_html_class((string) $args);
$section_id = tower_exchange_section_id('security');
$cta = tower_exchange_link(
    tower_exchange_sub_field('cta_link'),
    array('url' => 'https://t.me/towerexchange_kyiv', 'title' => 'Відкрити офіційний Telegram', 'target' => '_blank')
);
$allowed_icons = array('telegram', 'shield', 'bank');
?>
<section class="security" id="<?php echo esc_attr($section_id); ?>" aria-labelledby="security-title-<?php echo esc_attr($suffix); ?>">
    <div class="security__circuit" aria-hidden="true"></div>
    <div class="container security__grid">
        <div class="security__copy">
            <div class="eyebrow eyebrow--dark"><?php echo esc_html(tower_exchange_sub_field('eyebrow', 'Офіційні контакти · 04')); ?></div>
            <h2 id="security-title-<?php echo esc_attr($suffix); ?>"><?php echo esc_html(tower_exchange_sub_field('title', 'Перевіряйте контакт перед операцією')); ?></h2>
            <p><?php echo esc_html(tower_exchange_sub_field('text', 'Використовуйте посилання на цьому сайті. Перед передаванням даних або коштів звірте нікнейм і уточніть деталі безпосередньо в офіційному чаті.')); ?></p>
            <a class="button button--light" href="<?php echo esc_url($cta['url']); ?>"<?php echo $cta['target'] ? ' target="' . esc_attr($cta['target']) . '"' : ''; ?><?php echo tower_exchange_link_rel($cta) ? ' rel="' . esc_attr(tower_exchange_link_rel($cta)) . '"' : ''; ?>><?php echo esc_html($cta['title']); ?><?php echo tower_exchange_icon('arrow', 18); ?></a>
        </div>
        <?php if (have_rows('checks')) : ?>
            <div class="security__checklist">
                <?php while (have_rows('checks')) : the_row(); ?>
                    <?php $icon = (string) get_sub_field('icon'); $icon = in_array($icon, $allowed_icons, true) ? $icon : 'shield'; ?>
                    <div><span><?php echo tower_exchange_icon($icon, 23); ?></span><p><small><?php echo esc_html((string) get_sub_field('label')); ?></small><strong><?php echo esc_html((string) get_sub_field('value')); ?></strong></p></div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
