<?php
/**
 * 個別記事テンプレート（メンテナンス、オフラインMTG、コンテンツ共通）
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

    <div class="back-nav">
        <?php
        $post_type = get_post_type();
        if ($post_type === 'maintenance') {
            $terms = get_the_terms(get_the_ID(), 'maintenance_cat');
            if ($terms && !is_wp_error($terms)) {
                echo '<a href="' . esc_url(get_term_link($terms[0])) . '"><img alt="戻る" src="' . esc_url(get_template_directory_uri()) . '/s001-back.gif" /></a>';
            }
        } elseif ($post_type === 'offline_meeting') {
            echo '<a href="' . esc_url(get_post_type_archive_link('offline_meeting')) . '"><img alt="戻る" src="' . esc_url(get_template_directory_uri()) . '/s001-back.gif" /></a>';
        } else {
            echo '<a href="' . esc_url(home_url('/')) . '"><img alt="戻る" src="' . esc_url(get_template_directory_uri()) . '/s001-back.gif" /></a>';
        }
        ?>
    </div>

<?php endwhile; ?>

<?php get_footer(); ?>
