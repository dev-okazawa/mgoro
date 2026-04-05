<?php
/**
 * オフラインMTGアーカイブ — 年度別に表示（既存デザイン再現）
 */
get_header();
?>

    <div class="list-heading">
        <span style="color: #ff6600"><b><span style="font-size: x-large">オフラインミーティング</span></b></span>
    </div>

    <?php
    // 年度別にグループ表示
    $years = get_terms(array(
        'taxonomy'   => 'meeting_year',
        'orderby'    => 'name',
        'order'      => 'DESC',
        'hide_empty' => true,
    ));

    if (!empty($years) && !is_wp_error($years)) :
        foreach ($years as $year_term) :
            $meetings = new WP_Query(array(
                'post_type'      => 'offline_meeting',
                'posts_per_page' => -1,
                'orderby'        => 'date',
                'order'          => 'ASC',
                'tax_query'      => array(array(
                    'taxonomy' => 'meeting_year',
                    'field'    => 'term_id',
                    'terms'    => $year_term->term_id,
                )),
            ));

            if ($meetings->have_posts()) :
    ?>
    <p class="list-year-heading"><span style="color: #0000ff"><b><span style="font-size: x-large"><?php echo esc_html($year_term->name); ?></span></b></span></p>

    <table class="list-table" border="1" cellspacing="1" cellpadding="1">
        <tbody>
            <?php while ($meetings->have_posts()) : $meetings->the_post(); ?>
            <tr>
                <td>
                    <a href="<?php the_permalink(); ?>"><span style="color: #ff6600"><b><?php the_title(); ?></b></span></a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php
            endif;
            wp_reset_postdata();
        endforeach;
    else :
    ?>
        <p class="list-heading">まだ記事がありません。</p>
    <?php endif; ?>

    <div class="back-nav">
        <a href="<?php echo esc_url(home_url('/')); ?>"><img alt="ホームへ" src="<?php echo esc_url(get_template_directory_uri()); ?>/s001-back.gif" /></a>
    </div>

<?php get_footer(); ?>
