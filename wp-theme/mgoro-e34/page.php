<?php
/**
 * 固定ページテンプレート
 */
get_header();
?>

<?php while (have_posts()) : the_post(); ?>

    <div class="list-heading">
        <span style="color: #ff6600"><b><span style="font-size: x-large"><?php the_title(); ?></span></b></span>
    </div>

    <div class="entry-content">
        <?php the_content(); ?>
    </div>

<?php endwhile; ?>

<?php get_footer(); ?>
