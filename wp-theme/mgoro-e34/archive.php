<?php
/**
 * アーカイブテンプレート（カテゴリ一覧）
 * メンテナンスカテゴリ別一覧・オフラインMTG一覧・コンテンツ一覧
 */
get_header();

$archive_title = '';
if (is_tax('maintenance_cat')) {
    $archive_title = single_term_title('', false);
} elseif (is_tax('meeting_year')) {
    $archive_title = single_term_title('', false) . ' オフラインMTG';
} elseif (is_post_type_archive('maintenance')) {
    $archive_title = 'メンテナンス記事一覧';
} elseif (is_post_type_archive('offline_meeting')) {
    $archive_title = 'オフラインミーティング';
} elseif (is_post_type_archive('tech_content')) {
    $archive_title = 'コンテンツ';
} else {
    $archive_title = get_the_archive_title();
}
?>

    <div class="list-heading">
        <span style="color: #ff6600"><b><span style="font-size: x-large"><?php echo esc_html($archive_title); ?></span></b></span>
    </div>

    <table class="list-table" border="1" cellspacing="1" cellpadding="1">
        <tbody>
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                <tr>
                    <td>
                        <span style="color: #ff6600"><b><span style="font-size: large"><a href="<?php the_permalink(); ?>"><span style="color: #ff6600"><?php the_title(); ?></span></a></span></b></span>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else : ?>
                <tr><td><p>記事がまだありません。</p></td></tr>
            <?php endif; ?>
        </tbody>
    </table>


    <div class="back-nav">
        <a href="<?php echo esc_url(home_url('/')); ?>"><img alt="ホームへ" src="<?php echo esc_url(get_template_directory_uri()); ?>/s001-back.gif" /></a>
    </div>

<?php get_footer(); ?>
