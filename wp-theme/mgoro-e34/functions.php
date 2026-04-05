<?php
/**
 * Mgoro E34 535i テーマ functions
 */

// テーマセットアップ
function mgoro_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    add_theme_support('editor-styles');

    // ナビゲーションメニュー登録
    register_nav_menus(array(
        'primary' => 'メインメニュー',
    ));
}
add_action('after_setup_theme', 'mgoro_setup');

// CSS・JS読み込み
function mgoro_enqueue_assets() {
    $ver = '20260405';

    wp_enqueue_style('mgoro-style', get_stylesheet_uri(), array(), $ver);

    // トップページのみギャラリーJS
    if (is_front_page()) {
        wp_enqueue_script('mgoro-gallery', get_template_directory_uri() . '/js/index-gallery.js', array(), $ver, true);
    }

    // メニューJS（全ページ共通）
    wp_enqueue_script('mgoro-menu', get_template_directory_uri() . '/js/menu.js', array(), $ver, true);
    // JSにWordPressのURL���報を渡す
    wp_localize_script('mgoro-menu', 'mgoroVars', array(
        'homeUrl'  => esc_url(home_url()),
        'themeUrl' => esc_url(get_template_directory_uri()),
    ));
}
add_action('wp_enqueue_scripts', 'mgoro_enqueue_assets');

// Google Tag Manager（head内）
function mgoro_gtm_head() {
    ?>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-WZ63ZVB3');</script>
    <!-- End Google Tag Manager -->
    <?php
}
add_action('wp_head', 'mgoro_gtm_head', 1);

// Google Tag Manager（body直後）
function mgoro_gtm_body() {
    ?>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WZ63ZVB3"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php
}
add_action('wp_body_open', 'mgoro_gtm_body');

// ===================================================
// カスタム投稿タイプ
// ===================================================

function mgoro_register_post_types() {
    // メンテナンス記事
    register_post_type('maintenance', array(
        'labels' => array(
            'name'               => 'メンテナンス',
            'singular_name'      => 'メンテナンス記事',
            'add_new'            => '新規追加',
            'add_new_item'       => 'メンテナンス記事を追加',
            'edit_item'          => 'メンテナンス記事を編集',
            'view_item'          => '記事を見る',
            'all_items'          => 'すべてのメンテナンス記事',
            'search_items'       => 'メンテナンス記事を検索',
            'not_found'          => '記事が見つかりません',
        ),
        'public'             => true,
        'has_archive'        => true,
        'rewrite'            => array('slug' => 'maintenance'),
        'menu_icon'          => 'dashicons-admin-tools',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions'),
        'show_in_rest'       => true, // ブロックエディタ対応
    ));

    // オフラインミーティング
    register_post_type('offline_meeting', array(
        'labels' => array(
            'name'               => 'オフラインMTG',
            'singular_name'      => 'オフラインMTG記事',
            'add_new'            => '新規追加',
            'add_new_item'       => 'オフラインMTG記事を追加',
            'edit_item'          => 'オフラインMTG記事を編集',
            'view_item'          => '記事を見る',
            'all_items'          => 'すべてのオフラインMTG',
            'search_items'       => 'オフラインMTGを検索',
            'not_found'          => '記事が見つかりません',
        ),
        'public'             => true,
        'has_archive'        => true,
        'rewrite'            => array('slug' => 'offlinemeeting'),
        'menu_icon'          => 'dashicons-groups',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions'),
        'show_in_rest'       => true,
    ));

    // コンテンツ（技術記事）
    register_post_type('tech_content', array(
        'labels' => array(
            'name'               => 'コンテンツ',
            'singular_name'      => 'コンテンツ記事',
            'add_new'            => '新規追加',
            'add_new_item'       => 'コンテンツ記事を追加',
            'edit_item'          => 'コンテンツ記事を編集',
            'view_item'          => '記事を見る',
            'all_items'          => 'すべてのコンテンツ',
            'search_items'       => 'コンテンツを検索',
            'not_found'          => '記事が見つかりません',
        ),
        'public'             => true,
        'has_archive'        => true,
        'rewrite'            => array('slug' => 'contents'),
        'menu_icon'          => 'dashicons-media-document',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions'),
        'show_in_rest'       => true,
    ));
}
add_action('init', 'mgoro_register_post_types');

// ===================================================
// カスタムタクソノミー（カテゴリ分類）
// ===================================================

function mgoro_register_taxonomies() {
    // メンテナンスカテゴリ（エンジン、足回り、電装、外装、内装、その他）
    register_taxonomy('maintenance_cat', 'maintenance', array(
        'labels' => array(
            'name'          => 'メンテナンスカテゴリ',
            'singular_name' => 'カテゴリ',
            'add_new_item'  => 'カテゴリを追加',
            'edit_item'     => 'カテゴリを編集',
            'all_items'     => 'すべてのカテゴリ',
        ),
        'hierarchical'  => true,
        'public'        => true,
        'rewrite'       => array('slug' => 'maintenance-cat'),
        'show_in_rest'  => true,
        'show_admin_column' => true,
    ));

    // オフラインMTG年度
    register_taxonomy('meeting_year', 'offline_meeting', array(
        'labels' => array(
            'name'          => '開催年',
            'singular_name' => '年',
            'add_new_item'  => '年を追加',
        ),
        'hierarchical'  => true,
        'public'        => true,
        'rewrite'       => array('slug' => 'meeting-year'),
        'show_in_rest'  => true,
        'show_admin_column' => true,
    ));
}
add_action('init', 'mgoro_register_taxonomies');

// ===================================================
// カスタムリライトルール — 旧URL構造を完全再現
// ===================================================

function mgoro_custom_rewrite_rules() {
    // /maintenance/engine/engineoil.html → maintenance投稿 スラッグ engine-engineoil
    add_rewrite_rule(
        '^maintenance/(engine|ashimawari|densou|gaisou|naisou|others)/([^/]+)\.html$',
        'index.php?post_type=maintenance&name=$matches[1]-$matches[2]',
        'top'
    );

    // /maintenance/engine.html → メンテナンスカテゴリ一覧ページ
    // カテゴリスラッグのマッピング
    $cat_slugs = array(
        'engine'     => 'エンジン',
        'ashimawari' => '足回り',
        'densou'     => '電装',
        'gaisou'     => '外装',
        'naisou'     => '内装',
        'others'     => 'その他',
    );
    foreach ($cat_slugs as $slug => $name) {
        add_rewrite_rule(
            '^maintenance/' . $slug . '\.html$',
            'index.php?maintenance_cat=' . urlencode($name),
            'top'
        );
    }

    // オフラインMTGはrewriteルールではなくtemplate_redirectで処理

    // /contents/xxx.html → tech_content投稿
    add_rewrite_rule(
        '^contents/([^/]+)\.html$',
        'index.php?post_type=tech_content&name=$matches[1]',
        'top'
    );

    // /introduction/introduction.html → 固定ページ
    add_rewrite_rule(
        '^introduction/introduction\.html$',
        'index.php?pagename=introduction',
        'top'
    );
}
add_action('init', 'mgoro_custom_rewrite_rules');

// .html URLに末尾スラッシュを追加させない
function mgoro_no_trailing_slash_on_html($redirect_url, $requested_url) {
    if (preg_match('/\.html/', $requested_url)) {
        return false;
    }
    return $redirect_url;
}
add_filter('redirect_canonical', 'mgoro_no_trailing_slash_on_html', 10, 2);

// .html URLのルーティング — parse_requestで早期にクエリを書き換え
function mgoro_html_parse_request($wp) {
    $path = trim($wp->request, '/');

    if (!preg_match('/\.html$/', $path)) return;

    global $wpdb;

    // /offlinemeeting/offlinemeetingtop.html → アーカイブ
    if ($path === 'offlinemeeting/offlinemeetingtop.html') {
        $wp->query_vars = array('post_type' => 'offline_meeting');
        return;
    }

    // /offlinemeeting/xxx.html or /offlinemeeting/folder/xxx.html → 個別記事
    if (preg_match('#^offlinemeeting/(?:[^/]+/)?([^/]+)\.html$#', $path, $m)) {
        $filename = $m[1];
        // 末尾のドットを除去してから変換
        $clean = rtrim($filename, '.');
        $candidates = array_unique(array(
            $filename,
            $clean,
            str_replace('.', '-', $filename),
            str_replace('.', '-', $clean),
            str_replace('_', '-', $filename),
            str_replace('_', '-', $clean),
            str_replace(array('.', '_'), '-', $filename),
            str_replace(array('.', '_'), '-', $clean),
        ));

        foreach ($candidates as $slug) {
            $post_name = $wpdb->get_var($wpdb->prepare(
                "SELECT post_name FROM {$wpdb->posts} WHERE post_type='offline_meeting' AND post_status='publish' AND post_name=%s LIMIT 1",
                $slug
            ));
            if ($post_name) {
                $wp->query_vars = array('post_type' => 'offline_meeting', 'name' => $post_name);
                return;
            }
        }
    }

    // /maintenance/engine.html → カテゴリ一覧
    $cat_slugs = array(
        'engine' => 'エンジン', 'ashimawari' => '足回り', 'densou' => '電装',
        'gaisou' => '外装', 'naisou' => '内装', 'others' => 'その他',
    );
    if (preg_match('#^maintenance/(\w+)\.html$#', $path, $m) && isset($cat_slugs[$m[1]])) {
        $wp->query_vars = array('maintenance_cat' => $cat_slugs[$m[1]]);
        return;
    }

    // /maintenance/engine/engineoil.html → メンテナンス個別記事
    if (preg_match('#^maintenance/(\w+)/([^/]+)\.html$#', $path, $m)) {
        $slug = $m[1] . '-' . $m[2];
        $post_name = $wpdb->get_var($wpdb->prepare(
            "SELECT post_name FROM {$wpdb->posts} WHERE post_type='maintenance' AND post_status='publish' AND post_name=%s LIMIT 1",
            $slug
        ));
        if ($post_name) {
            $wp->query_vars = array('post_type' => 'maintenance', 'name' => $post_name);
            return;
        }
    }

    // /contents/xxx.html → tech_content個別記事
    if (preg_match('#^contents/([^/]+)\.html$#', $path, $m)) {
        $post_name = $wpdb->get_var($wpdb->prepare(
            "SELECT post_name FROM {$wpdb->posts} WHERE post_type='tech_content' AND post_status='publish' AND post_name=%s LIMIT 1",
            $m[1]
        ));
        if ($post_name) {
            $wp->query_vars = array('post_type' => 'tech_content', 'name' => $post_name);
            return;
        }
    }

    // /introduction/introduction.html → 固定ページ
    if ($path === 'introduction/introduction.html') {
        $wp->query_vars = array('pagename' => 'introduction');
        return;
    }
}
add_action('parse_request', 'mgoro_html_parse_request', 1);

// カスタムクエリ変数を登録
function mgoro_query_vars($vars) {
    $vars[] = 'mgoro_offline_slug';
    return $vars;
}
add_filter('query_vars', 'mgoro_query_vars');

// カテゴリ一覧は全件表示
function mgoro_archive_posts_per_page($query) {
    if (!$query->is_main_query() || is_admin()) return;
    if ($query->is_tax('maintenance_cat') || $query->is_post_type_archive('maintenance') || $query->is_post_type_archive('tech_content')) {
        $query->set('posts_per_page', -1);
    }
}
add_action('pre_get_posts', 'mgoro_archive_posts_per_page');

// URLのファイル名からスラッグを解決して検索
function mgoro_pre_get_posts($query) {
    if (!$query->is_main_query() || is_admin()) return;

    $offline_slug = $query->get('mgoro_offline_slug');
    if ($offline_slug) {
        // 複数パターンでスラッグを検索（元のまま、.→-、_→-、両方→-）
        $candidates = array_unique(array(
            $offline_slug,
            str_replace('.', '-', $offline_slug),
            str_replace('_', '-', $offline_slug),
            str_replace(array('.', '_'), '-', $offline_slug),
        ));

        global $wpdb;
        foreach ($candidates as $candidate) {
            $found = $wpdb->get_var($wpdb->prepare(
                "SELECT post_name FROM {$wpdb->posts} WHERE post_type='offline_meeting' AND post_status='publish' AND post_name=%s LIMIT 1",
                $candidate
            ));
            if ($found) {
                $query->set('post_type', 'offline_meeting');
                $query->set('name', $found);
                $query->set('mgoro_offline_slug', '');
                return;
            }
        }

        // どれもマッチしなければ最初の変換を使う
        $query->set('post_type', 'offline_meeting');
        $query->set('name', $offline_slug);
        $query->set('mgoro_offline_slug', '');
    }
}
add_action('pre_get_posts', 'mgoro_pre_get_posts');

// オフラインMTGスラッグ→元ファイル名の復元マップ
function mgoro_get_offline_filename_map() {
    // WPスラッグ → 元ファイル名（.html除く）
    // ファイル名に . や _ が含まれるものだけマップが必要
    // WPが . → - , _ → - に変換するため
    static $map = null;
    if ($map !== null) return $map;

    $map = array();
    $theme_dir = get_template_directory();
    $source_dir = $theme_dir . '/html_source/offlinemeeting';

    if (is_dir($source_dir)) {
        // ルート直下のHTML
        $files = glob($source_dir . '/*.html');
        foreach ($files as $f) {
            $basename = basename($f, '.html');
            if ($basename === 'offlinemeetingtop') continue;
            $wp_slug = sanitize_title($basename);
            $map[$wp_slug] = $basename;
        }
        // サブフォルダ内のHTML
        $sub_files = glob($source_dir . '/*/*.html');
        foreach ($sub_files as $f) {
            $basename = basename($f, '.html');
            $wp_slug = sanitize_title($basename);
            $map[$wp_slug] = $basename;
        }
    }

    return $map;
}

function mgoro_restore_offline_filename($wp_slug) {
    $map = mgoro_get_offline_filename_map();
    return isset($map[$wp_slug]) ? $map[$wp_slug] : $wp_slug;
}

// カスタム投稿タイプのパーマリンクを旧URL形式に書き換え
function mgoro_custom_post_link($post_link, $post) {
    if ($post->post_type === 'maintenance') {
        $slug = $post->post_name;
        // engine-engineoil → maintenance/engine/engineoil.html
        if (preg_match('/^(engine|ashimawari|densou|gaisou|naisou|others)-(.+)$/', $slug, $m)) {
            return home_url('/maintenance/' . $m[1] . '/' . $m[2] . '.html');
        }
    }

    if ($post->post_type === 'offline_meeting') {
        // スラッグから元のファイル名を復元
        // WPスラッグ: 2006-10-15 → 元ファイル名: 2006.10.15
        $filename = mgoro_restore_offline_filename($post->post_name);
        return home_url('/offlinemeeting/' . $filename . '.html');
    }

    if ($post->post_type === 'tech_content') {
        return home_url('/contents/' . $post->post_name . '.html');
    }

    return $post_link;
}
add_filter('post_type_link', 'mgoro_custom_post_link', 10, 2);

// 固定ページ「愛車紹介」のパーマリンク
function mgoro_page_link($link, $post_id) {
    $post = get_post($post_id);
    if ($post && $post->post_name === 'introduction') {
        return home_url('/introduction/introduction.html');
    }
    return $link;
}
add_filter('page_link', 'mgoro_page_link', 10, 2);

// メンテナンスカテゴリのパーマリンクを旧URL形式に
function mgoro_term_link($url, $term, $taxonomy) {
    if ($taxonomy === 'maintenance_cat') {
        $slug_map = array(
            'エンジン' => 'engine',
            '足回り'   => 'ashimawari',
            '電装'     => 'densou',
            '外装'     => 'gaisou',
            '内装'     => 'naisou',
            'その他'   => 'others',
        );
        if (isset($slug_map[$term->name])) {
            return home_url('/maintenance/' . $slug_map[$term->name] . '.html');
        }
    }
    return $url;
}
add_filter('term_link', 'mgoro_term_link', 10, 3);

// オフラインMTGアーカイブのパーマリンク
function mgoro_archive_link($link, $post_type) {
    if ($post_type === 'offline_meeting') {
        return home_url('/offlinemeeting/offlinemeetingtop.html');
    }
    return $link;
}
add_filter('post_type_archive_link', 'mgoro_archive_link', 10, 2);

// ===================================================
// 初期カテゴリ登録（テーマ有効化時）
// ===================================================

function mgoro_activate_theme() {
    mgoro_register_post_types();
    mgoro_register_taxonomies();

    // メンテナンスカテゴリの初期値
    $categories = array('エンジン', '足回り', '電装', '外装', '内装', 'その他');
    foreach ($categories as $cat) {
        if (!term_exists($cat, 'maintenance_cat')) {
            wp_insert_term($cat, 'maintenance_cat');
        }
    }

    // オフラインMTG年度の初期値
    for ($year = 2006; $year <= 2015; $year++) {
        $label = $year . '年';
        if (!term_exists($label, 'meeting_year')) {
            wp_insert_term($label, 'meeting_year');
        }
    }

    flush_rewrite_rules();
}
add_action('after_switch_theme', 'mgoro_activate_theme');

// ===================================================
// OGP設定
// ===================================================

function mgoro_ogp_meta() {
    if (is_front_page()) {
        ?>
        <meta property="og:type" content="website" />
        <meta property="og:site_name" content="Mゴロー E34 535i" />
        <meta property="og:title" content="E34 535i メンテナンス記録" />
        <meta property="og:description" content="2003年からのBMW E34 535i メンテナンス記録とオフラインミーティングの記録。" />
        <meta property="og:url" content="<?php echo esc_url(home_url('/')); ?>" />
        <meta property="og:image" content="<?php echo esc_url(get_template_directory_uri()); ?>/photos/186039.jpg" />
        <meta property="og:locale" content="ja_JP" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:image" content="<?php echo esc_url(get_template_directory_uri()); ?>/photos/186039.jpg" />
        <?php
    } elseif (is_singular()) {
        global $post;
        $title = get_the_title();
        $desc = has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 80, '...');
        $thumb = has_post_thumbnail() ? get_the_post_thumbnail_url($post, 'large') : get_template_directory_uri() . '/photos/186039.jpg';
        ?>
        <meta property="og:type" content="article" />
        <meta property="og:site_name" content="Mゴロー E34 535i" />
        <meta property="og:title" content="<?php echo esc_attr($title); ?>" />
        <meta property="og:description" content="<?php echo esc_attr($desc); ?>" />
        <meta property="og:url" content="<?php echo esc_url(get_permalink()); ?>" />
        <meta property="og:image" content="<?php echo esc_url($thumb); ?>" />
        <meta property="og:locale" content="ja_JP" />
        <meta name="twitter:card" content="summary_large_image" />
        <?php
    }
}
add_action('wp_head', 'mgoro_ogp_meta', 5);

// ===================================================
// フォトギャラリー用REST API
// ===================================================

function mgoro_gallery_photos_api() {
    register_rest_route('mgoro/v1', '/gallery-photos', array(
        'methods'  => 'GET',
        'callback' => 'mgoro_get_gallery_photos',
        'permission_callback' => '__return_true',
    ));
}
add_action('rest_api_init', 'mgoro_gallery_photos_api');

function mgoro_get_gallery_photos() {
    $photos_dir = get_template_directory() . '/photos';
    $photos_url = get_template_directory_uri() . '/photos';
    $files = array();

    if (is_dir($photos_dir)) {
        $entries = scandir($photos_dir);
        foreach ($entries as $file) {
            if (preg_match('/\.(jpg|jpeg|png|webp)$/i', $file) && $file !== '.' && $file !== '..') {
                $files[] = array(
                    'full'  => $photos_url . '/' . $file,
                    'thumb' => is_file($photos_dir . '/thumbs/' . $file)
                        ? $photos_url . '/thumbs/' . $file
                        : $photos_url . '/' . $file,
                );
            }
        }
    }

    return $files;
}

// ===================================================
// 管理画面カスタマイズ
// ===================================================

// 管理画面のフッターテキスト変更
function mgoro_admin_footer_text() {
    return 'Mゴロー E34 535i 管理画面';
}
add_filter('admin_footer_text', 'mgoro_admin_footer_text');

// ダッシュボードにヘルプウィジェット追加
function mgoro_dashboard_widget() {
    wp_add_dashboard_widget(
        'mgoro_help_widget',
        '記事の追加・編集方法',
        'mgoro_help_widget_content'
    );
}
add_action('wp_dashboard_setup', 'mgoro_dashboard_widget');

function mgoro_help_widget_content() {
    echo '<h3>メンテナンス記事を追加する</h3>';
    echo '<ol>';
    echo '<li>左メニュー「メンテナンス」→「新規追加」をクリック</li>';
    echo '<li>タイトルと本文を入力（画像はドラッグ＆ドロップで追加可能）</li>';
    echo '<li>右側パネルで「メンテナンスカテゴリ」を選択（エンジン、足回り等）</li>';
    echo '<li>「公開」ボタンを押して完了</li>';
    echo '</ol>';
    echo '<h3>オフラインMTG記事を追加する</h3>';
    echo '<ol>';
    echo '<li>左メニュー「オフラインMTG」→「新規追加」をクリック</li>';
    echo '<li>タイトルと本文を入力</li>';
    echo '<li>右側パネルで「開催年」を選択</li>';
    echo '<li>「公開」ボタンを押して完了</li>';
    echo '</ol>';
}
