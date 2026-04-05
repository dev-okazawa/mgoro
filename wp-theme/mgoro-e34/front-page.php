<?php
/**
 * トップページテンプレート
 * 既存index.htmlのデザインをそのまま再現
 */
get_header();
?>

    <section class="top-slider" aria-label="E34 535i photo slider">
        <div class="top-slider__frame">
            <img class="top-slider__image is-active" id="top-slider-image-primary" src="<?php echo esc_url(get_template_directory_uri()); ?>/photos/186032.jpg" alt="E34 535i">
            <img class="top-slider__image" id="top-slider-image-secondary" src="<?php echo esc_url(get_template_directory_uri()); ?>/photos/186058.jpg" alt="E34 535i">
        </div>
    </section>

    <p class="list-heading"><span style="color: #ff6600"><b><i><span style="font-size: medium">2003年からの535iのメンテナンス記録の一部です。</span></i></b></span></p>

    <table class="list-table list-table--wide" border="1" cellspacing="1" cellpadding="1">
        <tbody>
            <?php
            // メンテナンスカテゴリ一覧
            $maintenance_cats = array(
                array('name' => 'エンジン',   'slug' => 'エンジン'),
                array('name' => '足回り',     'slug' => '足回り'),
                array('name' => '電装',       'slug' => '電装'),
                array('name' => '外装',       'slug' => '外装'),
                array('name' => '内装',       'slug' => '内装'),
                array('name' => 'その他',     'slug' => 'その他'),
            );

            foreach ($maintenance_cats as $cat) :
                $term = get_term_by('name', $cat['slug'], 'maintenance_cat');
                $link = $term ? get_term_link($term) : '#';

                // 最新記事の日付を取得
                $latest = new WP_Query(array(
                    'post_type'      => 'maintenance',
                    'posts_per_page' => 1,
                    'tax_query'      => $term ? array(array(
                        'taxonomy' => 'maintenance_cat',
                        'field'    => 'term_id',
                        'terms'    => $term->term_id,
                    )) : array(),
                ));
                $last_date = $latest->have_posts() ? get_the_date('Y/n/j', $latest->posts[0]) : '';
                wp_reset_postdata();
            ?>
            <tr>
                <td>
                    <p><span style="color: #ff6600"><b><span style="font-size: large"><a href="<?php echo esc_url($link); ?>"><span style="color: #ff6600"><?php echo esc_html($cat['name']); ?></span></a></span></b>
                    <?php if ($last_date) : ?>
                        <span style="font-size: x-small"><i><span style="color: #000000">最終更新日<?php echo esc_html($last_date); ?></span></i></span>
                    <?php endif; ?>
                    </span></p>
                </td>
            </tr>
            <?php endforeach; ?>

            <tr>
                <td><span style="color: #ff6600"><span style="font-size: large"><?php
                    $intro_page = get_page_by_title('愛車紹介　1991 E34 535i');
                    $intro_url = $intro_page ? get_permalink($intro_page) : home_url('/');
                ?><a href="<?php echo esc_url($intro_url); ?>"><strong style="color: #ff6600">愛車紹介</strong></a></span></span></td>
            </tr>
            <tr>
                <td><span style="font-size: medium"><a href="<?php echo esc_url(get_post_type_archive_link('offline_meeting')); ?>" style="color: #cc6600; text-decoration: none"><strong>オフラインミーティング</strong></a></span></td>
            </tr>
            <tr>
                <td>
                    <p><a href="https://bbs1.sekkaku.net/bbs/mgoro/" target="_self" rel="noopener noreferrer"><strong>E34掲示板はこちら</strong> <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/new025_12.gif" alt="new" /></a></p>
                </td>
            </tr>
        </tbody>
    </table>

    <section class="photo-gallery" aria-labelledby="photo-gallery-title">
        <div class="photo-gallery__header">
            <div class="photo-gallery__meta">
                <p class="photo-gallery__title" id="photo-gallery-title">Photo Gallery</p>
                <p class="photo-gallery__updated">Last Update <?php echo date('Y/n/j'); ?></p>
            </div>
            <button class="photo-gallery__refresh" type="button" id="photo-gallery-refresh">写真を入れ替える</button>
        </div>
        <div class="photo-gallery__grid" id="photo-gallery-grid"></div>
    </section>

<?php get_footer(); ?>
