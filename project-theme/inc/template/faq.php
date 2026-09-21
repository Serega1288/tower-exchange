<?php
/**
 * FAQ section.
 *
 * @package TowerExchange
 */

$suffix = sanitize_html_class((string) $args);
$contact = tower_exchange_link(
    tower_exchange_sub_field('contact_link'),
    array('url' => 'https://t.me/towerexchange_kyiv', 'title' => 'Зв’язатися з менеджером', 'target' => '_blank')
);
?>
<section class="section faq" aria-labelledby="faq-title-<?php echo esc_attr($suffix); ?>">
    <div class="container faq__grid">
        <div class="faq__intro">
            <div class="eyebrow"><?php echo esc_html(tower_exchange_sub_field('eyebrow', 'FAQ · 07')); ?></div>
            <h2 id="faq-title-<?php echo esc_attr($suffix); ?>"><?php echo esc_html(tower_exchange_sub_field('title', 'Коротко про головне')); ?></h2>
            <p><?php echo esc_html(tower_exchange_sub_field('intro', 'Не знайшли відповіді? Поставте питання менеджеру в Telegram.')); ?></p>
            <a class="text-link" href="<?php echo esc_url($contact['url']); ?>"<?php echo $contact['target'] ? ' target="' . esc_attr($contact['target']) . '"' : ''; ?><?php echo tower_exchange_link_rel($contact) ? ' rel="' . esc_attr(tower_exchange_link_rel($contact)) . '"' : ''; ?>><?php echo esc_html($contact['title']); ?><?php echo tower_exchange_icon('arrow', 17); ?></a>
        </div>
        <?php if (have_rows('items')) : ?>
            <div class="faq-list">
                <?php $faq_index = 0; ?>
                <?php while (have_rows('items')) : the_row(); ?>
                    <details<?php echo 0 === $faq_index ? ' open' : ''; ?>><summary><span><?php echo esc_html((string) get_sub_field('question')); ?></span><span class="faq-list__icon"><?php echo tower_exchange_icon('chevron', 19); ?></span></summary><?php echo wp_kses_post((string) get_sub_field('answer')); ?></details>
                    <?php ++$faq_index; ?>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
