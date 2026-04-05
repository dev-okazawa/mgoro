<?php
/**
 * デフォルトテンプレート（フォールバック）
 */
get_header();
?>

    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
        <div class="archive-list__item">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            <span class="archive-list__date"><?php echo get_the_date('Y/n/j'); ?></span>
        </div>
        <?php endwhile; ?>

        <?php the_posts_pagination(array(
            'prev_text' => '&laquo; 前へ',
            'next_text' => '次へ &raquo;',
        )); ?>
    <?php else : ?>
        <p class="list-heading">記事がありません。</p>
    <?php endif; ?>

<?php get_footer(); ?>
