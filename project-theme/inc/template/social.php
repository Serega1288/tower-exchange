<?php
/**
 * Instagram section.
 *
 * @package TowerExchange
 */

$suffix = sanitize_html_class((string) $args);
$section_id = tower_exchange_section_id('instagram');
$profile = tower_exchange_link(
    tower_exchange_sub_field('profile_link'),
    array('url' => 'https://www.instagram.com/tower.exchange.kyiv/', 'title' => '@tower.exchange.kyiv', 'target' => '_blank')
);
$reviews = tower_exchange_link(
    tower_exchange_sub_field('reviews_link'),
    array('url' => 'https://www.instagram.com/stories/highlights/17900850174112330/', 'title' => 'Переглянути відгуки', 'target' => '_blank')
);
$allowed_styles = array('yellow', 'black', 'paper');
?>
<section class="section social" id="<?php echo esc_attr($section_id); ?>" aria-labelledby="social-title-<?php echo esc_attr($suffix); ?>">
    <div class="container">
        <div class="section-heading section-heading--split social__heading">
            <div><div class="eyebrow"><?php echo esc_html(tower_exchange_sub_field('eyebrow', 'Instagram · 06')); ?></div><h2 id="social-title-<?php echo esc_attr($suffix); ?>"><?php echo esc_html(tower_exchange_sub_field('title', 'Tower у стрічці')); ?></h2></div>
            <div class="social__intro">
                <p><?php echo esc_html(tower_exchange_sub_field('intro', 'Новини, процес обміну та життя Tower Exchange у Києві.')); ?></p>
                <a class="text-link" href="<?php echo esc_url($profile['url']); ?>"<?php echo $profile['target'] ? ' target="' . esc_attr($profile['target']) . '"' : ''; ?><?php echo tower_exchange_link_rel($profile) ? ' rel="' . esc_attr(tower_exchange_link_rel($profile)) . '"' : ''; ?>><?php echo esc_html($profile['title']); ?><?php echo tower_exchange_icon('arrow', 17); ?></a>
            </div>
        </div>
        <?php if (have_rows('posts')) : ?>
            <div class="post-grid">
                <?php $post_number = 1; ?>
                <?php while (have_rows('posts')) : the_row(); ?>
                    <?php
                    $style = (string) get_sub_field('style');
                    $style = in_array($style, $allowed_styles, true) ? $style : 'yellow';
                    $link  = tower_exchange_link(get_sub_field('link'), array('url' => '#', 'title' => '', 'target' => ''));
                    ?>
                    <a class="post-card post-card--<?php echo esc_attr($style); ?>" href="<?php echo esc_url($link['url']); ?>"<?php echo $link['target'] ? ' target="' . esc_attr($link['target']) . '"' : ''; ?><?php echo tower_exchange_link_rel($link) ? ' rel="' . esc_attr(tower_exchange_link_rel($link)) . '"' : ''; ?>>
                        <div class="post-card__top"><span><?php echo esc_html(str_pad((string) $post_number, 2, '0', STR_PAD_LEFT) . ' / ' . (string) get_sub_field('type')); ?></span><?php echo tower_exchange_icon('instagram', 21); ?></div>
                        <div class="post-card__motif" aria-hidden="true"><span></span><span></span><span></span></div>
                        <div class="post-card__bottom"><h3><?php echo esc_html((string) get_sub_field('title')); ?></h3><span class="post-card__arrow"><?php echo tower_exchange_icon('arrow', 20); ?></span></div>
                    </a>
                    <?php ++$post_number; ?>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
        <div class="reviews-link">
            <div><span class="reviews-link__icon">“</span><p><small><?php echo esc_html(tower_exchange_sub_field('reviews_label', 'Відгуки клієнтів')); ?></small><?php echo esc_html(tower_exchange_sub_field('reviews_text', 'Переглядайте в актуальному Instagram')); ?></p></div>
            <a class="button button--ghost" href="<?php echo esc_url($reviews['url']); ?>"<?php echo $reviews['target'] ? ' target="' . esc_attr($reviews['target']) . '"' : ''; ?><?php echo tower_exchange_link_rel($reviews) ? ' rel="' . esc_attr(tower_exchange_link_rel($reviews)) . '"' : ''; ?>><?php echo esc_html($reviews['title']); ?><?php echo tower_exchange_icon('arrow', 18); ?></a>
        </div>
    </div>
</section>
