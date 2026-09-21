<?php
/**
 * Fallback template.
 *
 * @package TowerExchange
 */

get_header();
?>
<main id="main-content" class="template-page" role="main">
    <div class="container">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <?php the_title('<h1>', '</h1>'); ?>
                    <?php the_content(); ?>
                </article>
            <?php endwhile; ?>
        <?php else : ?>
            <p><?php esc_html_e('Вміст не знайдено.', 'tower-exchange'); ?></p>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
