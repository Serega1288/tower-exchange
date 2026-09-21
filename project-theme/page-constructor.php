<?php
/**
 * Template Name: Constructor
 *
 * @package TowerExchange
 */

get_header();
?>
<main id="main-content" role="main">
    <?php while (have_posts()) : the_post(); ?>
        <?php get_template_part('inc/box', 'constructor'); ?>
    <?php endwhile; ?>
</main>
<?php get_footer(); ?>
