<?php
/**
 * SHIZUOKA OCEANS RADIO テーマ設定
 *
 * @package sor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // 直接アクセス禁止
}

define( 'SOR_VERSION', '1.0.0' );

/**
 * テーマサポート
 */
function sor_setup() {
	load_theme_textdomain( 'sor', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );          // <title> をWPに任せる
	add_theme_support( 'post-thumbnails' );    // アイキャッチ（カードのサムネイル）
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );

	// カード用のサムネイルサイズ（静的HTMLのプレースホルダー比率に合わせる）
	add_image_size( 'sor-card', 400, 225, true );      // 16:9 お知らせ・番組
	add_image_size( 'sor-square', 250, 250, true );    // 1:1 パーソナリティー
	add_image_size( 'sor-hero', 800, 480, true );      // 記事詳細のヒーロー

	register_nav_menus( array(
		'primary_left'  => __( 'ヘッダー左（ロゴの左側）', 'sor' ),
		'primary_right' => __( 'ヘッダー右（ロゴの右側）', 'sor' ),
		'footer'        => __( 'フッター（主要メニュー）', 'sor' ),
		'footer_small'  => __( 'フッター（小メニュー）', 'sor' ),
	) );
}
add_action( 'after_setup_theme', 'sor_setup' );

/**
 * CSS / JS の読み込み
 * 静的HTMLと同じCDN構成（Bootstrap / Font Awesome / slick / Google Fonts）を踏襲する
 */
function sor_assets() {
	$uri = get_template_directory_uri();

	wp_enqueue_style( 'sor-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Noto+Sans+JP:wght@100..900&display=swap', array(), null );
	wp_enqueue_style( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', array(), '5.3.3' );
	wp_enqueue_style( 'fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css', array(), '6.5.2' );
	wp_enqueue_style( 'slick', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css', array(), '1.8.1' );
	wp_enqueue_style( 'slick-theme', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css', array( 'slick' ), '1.8.1' );

	// 自前のスタイル（最後に読み込んで上書きできるようにする）
	wp_enqueue_style( 'sor-custom', $uri . '/assets/css/custom.css', array( 'bootstrap' ), SOR_VERSION );

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'slick', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array( 'jquery' ), '1.8.1', true );
	wp_enqueue_script( 'sor-main', $uri . '/assets/js/main.js', array( 'jquery', 'slick' ), SOR_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'sor_assets' );

/**
 * カスタム投稿タイプ：パーソナリティー / 番組
 * 静的HTMLの personality_list・program_list に対応する
 */
function sor_post_types() {
	register_post_type( 'personality', array(
		'labels'        => array(
			'name'          => __( 'パーソナリティー', 'sor' ),
			'singular_name' => __( 'パーソナリティー', 'sor' ),
		),
		'public'        => true,
		// 一覧は固定ページ（/personality/）に一本化するため、アーカイブは無効。
		// 有効だと /personality/ をアーカイブが先取りし、固定ページに到達できなくなる。
		'has_archive'   => false,
		'menu_icon'     => 'dashicons-microphone',
		'rewrite'       => array( 'slug' => 'personality' ),
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
		'show_in_rest'  => true,
	) );

	register_post_type( 'program', array(
		'labels'        => array(
			'name'          => __( '番組', 'sor' ),
			'singular_name' => __( '番組', 'sor' ),
		),
		'public'        => true,
		// 同上。一覧は固定ページ（/program/）を使う。
		'has_archive'   => false,
		'menu_icon'     => 'dashicons-format-audio',
		'rewrite'       => array( 'slug' => 'program' ),
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
		'show_in_rest'  => true,
	) );
}
add_action( 'init', 'sor_post_types' );

/**
 * 番組の曜日タクソノミー（静的HTMLの program-tag「火」「18:00」に相当）
 */
function sor_taxonomies() {
	register_taxonomy( 'program_day', 'program', array(
		'labels'       => array( 'name' => __( '放送曜日', 'sor' ) ),
		'hierarchical' => true,
		'public'       => true,
		'show_in_rest' => true,
		'rewrite'      => array( 'slug' => 'day' ),
	) );
}
add_action( 'init', 'sor_taxonomies' );

/**
 * SEO：静的HTMLで実装したメタを出力する
 * ※Yoast等のSEOプラグインを入れる場合は、二重出力になるのでこの関数を無効化すること
 */
function sor_seo_meta() {
	if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) ) {
		return; // SEOプラグインがある場合は任せる
	}

	$desc = sor_meta_description();
	$title = wp_get_document_title();
	$url  = is_front_page() ? home_url( '/' ) : get_permalink();
	$img  = get_template_directory_uri() . '/assets/img/home/SHIZUOKA-OCEANS-RADIO-1a-dark.svg';
	if ( is_singular() && has_post_thumbnail() ) {
		$img = get_the_post_thumbnail_url( null, 'full' );
	}
	$type = is_singular( 'post' ) ? 'article' : 'website';

	echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
	echo '<meta name="robots" content="index, follow">' . "\n";
	echo '<link rel="canonical" href="' . esc_url( $url ) . '">' . "\n";
	echo '<meta property="og:type" content="' . esc_attr( $type ) . '">' . "\n";
	echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $desc ) . '">' . "\n";
	echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
	echo '<meta property="og:image" content="' . esc_url( $img ) . '">' . "\n";
	echo '<meta property="og:locale" content="ja_JP">' . "\n";
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta name="twitter:description" content="' . esc_attr( $desc ) . '">' . "\n";
	echo '<meta name="twitter:image" content="' . esc_url( $img ) . '">' . "\n";
}
add_action( 'wp_head', 'sor_seo_meta', 1 );

/**
 * ページごとの description を組み立てる
 */
function sor_meta_description() {
	if ( is_singular() ) {
		$excerpt = get_the_excerpt();
		if ( $excerpt ) {
			return wp_trim_words( wp_strip_all_tags( $excerpt ), 60, '…' );
		}
		return wp_trim_words( wp_strip_all_tags( get_the_content() ), 60, '…' );
	}
	if ( is_post_type_archive( 'personality' ) ) {
		return get_bloginfo( 'name' ) . 'に出演するパーソナリティー一覧。担当番組やプロフィールをご紹介します。';
	}
	if ( is_post_type_archive( 'program' ) ) {
		return get_bloginfo( 'name' ) . 'の番組一覧。曜日・時間帯ごとの放送番組と出演パーソナリティーをご紹介します。';
	}
	if ( is_home() || is_category() || is_archive() ) {
		return get_bloginfo( 'name' ) . 'からのお知らせ一覧。イベント・番組改編など最新情報をお届けします。';
	}
	$desc = get_bloginfo( 'description' );
	return $desc ? $desc : get_bloginfo( 'name' ) . '（静岡のインターネットラジオ）公式サイト。';
}

/**
 * 構造化データ（RadioStation / WebSite / BreadcrumbList）
 */
function sor_structured_data() {
	$home = home_url( '/' );
	$logo = get_template_directory_uri() . '/assets/img/home/SHIZUOKA-OCEANS-RADIO-1a-dark.svg';

	if ( is_front_page() ) {
		$data = array(
			'@context' => 'https://schema.org',
			'@graph'   => array(
				array(
					'@type'       => 'RadioStation',
					'@id'         => $home . '#station',
					'name'        => get_bloginfo( 'name' ),
					'url'         => $home,
					'logo'        => $logo,
					'description' => get_bloginfo( 'description' ),
					'areaServed'  => array( '@type' => 'AdministrativeArea', 'name' => '静岡県' ),
				),
				array(
					'@type'      => 'WebSite',
					'@id'        => $home . '#website',
					'url'        => $home,
					'name'       => get_bloginfo( 'name' ),
					'publisher'  => array( '@id' => $home . '#station' ),
					'inLanguage' => 'ja',
				),
			),
		);
	} elseif ( is_singular() || is_archive() ) {
		$items = array(
			array( '@type' => 'ListItem', 'position' => 1, 'name' => 'TOP', 'item' => $home ),
			array( '@type' => 'ListItem', 'position' => 2, 'name' => wp_strip_all_tags( sor_current_title() ) ),
		);
		$data = array( '@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => $items );
	} else {
		return;
	}
	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'sor_structured_data', 2 );

/**
 * 現在のページ名（パンくず・ページヘッダー共通で使う）
 */
function sor_current_title() {
	if ( is_singular() ) {
		return get_the_title();
	}
	if ( is_post_type_archive() ) {
		return post_type_archive_title( '', false );
	}
	if ( is_home() ) {
		return 'お知らせ';
	}
	return wp_strip_all_tags( get_the_archive_title() );
}

/**
 * ページヘッダー（アクアの帯）の英字ラベルを返す
 */
function sor_header_label() {
	if ( is_home() || is_singular( 'post' ) || is_category() ) {
		return array( 'NEWS', 'お知らせ' );
	}
	if ( is_post_type_archive( 'personality' ) || is_singular( 'personality' ) ) {
		return array( 'PERSONALITY', 'パーソナリティー' );
	}
	if ( is_post_type_archive( 'program' ) || is_singular( 'program' ) ) {
		return array( 'PROGRAM', '番組一覧' );
	}
	if ( is_page() ) {
		return array( 'PAGE', get_the_title() );
	}
	return array( 'ARCHIVE', sor_current_title() );
}

/**
 * 固定ページの URL を返す
 *
 * スラッグで見つからないとき（日本語スラッグのままにしている等）は
 * $template を割り当てたページを探す。それも無ければ /$slug/ をそのまま返す。
 */
function sor_page_url( $slug, $template = '' ) {
	$page = get_page_by_path( $slug );

	if ( ! $page && $template ) {
		$found = get_pages( array(
			'meta_key'   => '_wp_page_template',
			'meta_value' => $template,
			'number'     => 1,
		) );
		if ( $found ) {
			$page = $found[0];
		}
	}

	return $page ? get_permalink( $page ) : home_url( '/' . $slug . '/' );
}

/**
 * 投稿タイプの「一覧ページ」URL
 *
 * CPT のアーカイブは無効化し、一覧は固定ページに一本化している。
 * get_post_type_archive_link() は has_archive => false だと false を返すので、
 * 一覧へ戻るリンクは全てこの関数を通すこと。
 */
function sor_list_url( $post_type ) {
	// お知らせは「設定→表示設定→投稿ページ」の指定を優先する
	if ( 'post' === $post_type ) {
		$posts_page = get_option( 'page_for_posts' );
		return $posts_page ? get_permalink( $posts_page ) : sor_page_url( 'news' );
	}
	return sor_page_url( $post_type );
}

/**
 * プライバシーポリシーページの URL
 *
 * 「設定→プライバシー」で指定されていればそれを最優先する。
 * WP 標準機能で作るとスラッグが privacy-policy（ハイフン入り）になり、
 * 静的HTML由来の privacypolicy と食い違うため。
 */
function sor_privacy_url() {
	$url = get_privacy_policy_url();
	return $url ? $url : sor_page_url( 'privacypolicy' );
}

/**
 * ヘッダーナビ用ウォーカー
 *
 * 静的HTMLの <a><span>NEWS</span>お知らせ</a> という構造を再現する。
 * 英字ラベルはメニュー項目の「説明」欄に入れる（外観→メニューの
 * 「表示オプション」で説明欄を出す必要がある）。空なら英字なしで出力。
 */
class SOR_Nav_Walker extends Walker_Nav_Menu {

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$en = trim( (string) $item->description );

		$output .= '<li><a href="' . esc_url( $item->url ) . '">';
		if ( '' !== $en ) {
			$output .= '<span>' . esc_html( $en ) . '</span>';
		}
		$output .= esc_html( $item->title ) . '</a>';
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= '</li>';
	}
}

/**
 * ヘッダーの左右ナビを出力する
 *
 * 外観→メニューで割り当てがあればそれを使い、無ければ静的HTMLと同じ既定項目を出す。
 * <ul> はテンプレート側にあるので items_wrap で <li> だけを出力させる。
 *
 * @param string $side 'left' または 'right'
 */
function sor_nav( $side ) {
	$location = 'primary_' . $side;

	if ( has_nav_menu( $location ) ) {
		wp_nav_menu( array(
			'theme_location' => $location,
			'container'      => false,
			'items_wrap'     => '%3$s',
			'depth'          => 1,
			'walker'         => new SOR_Nav_Walker(),
		) );
		return;
	}

	$items = array(
		'left'  => array(
			array( 'NEWS', 'お知らせ', sor_list_url( 'post' ) ),
			array( 'PROGRAM', '番組一覧', sor_list_url( 'program' ) ),
		),
		'right' => array(
			array( 'PERSONALITY', 'パーソナリティー', sor_list_url( 'personality' ) ),
			array( 'REQUEST', 'リクエスト', sor_page_url( 'request' ) ),
		),
	);

	foreach ( $items[ $side ] as $it ) {
		printf(
			'<li><a href="%s"><span>%s</span>%s</a></li>',
			esc_url( $it[2] ), esc_html( $it[0] ), esc_html( $it[1] )
		);
	}
}

/**
 * カードのサムネイル出力（アイキャッチが無ければプレースホルダー）
 * 静的HTMLと同じ見た目を保つため
 */
function sor_thumbnail( $size = 'sor-card', $alt = '' ) {
	if ( has_post_thumbnail() ) {
		the_post_thumbnail( $size, array( 'alt' => esc_attr( $alt ? $alt : get_the_title() ) ) );
		return;
	}
	$w = ( 'sor-square' === $size ) ? 250 : 400;
	$h = ( 'sor-square' === $size ) ? 250 : 225;
	printf(
		'<img src="data:image/svg+xml,%%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 %1$d %2$d\'%%3E%%3Crect width=\'%1$d\' height=\'%2$d\' fill=\'%%23d9d9d9\'/%%3E%%3C/svg%%3E" alt="%3$s">',
		$w, $h, esc_attr( $alt ? $alt : get_the_title() )
	);
}

/**
 * パーソナリティーの追加項目（ふりがな・英字名・出演曜日）の入力欄
 *
 * ACFを入れなくても編集できるよう、テーマ側で最小限のメタボックスを持つ。
 * ふりがなは表示だけでなく、一覧の五十音順ソートにも使う。
 */
function sor_personality_metabox() {
	add_meta_box(
		'sor_personality_fields',
		__( 'パーソナリティー情報', 'sor' ),
		'sor_personality_metabox_html',
		'personality',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'sor_personality_metabox' );

function sor_personality_metabox_html( $post ) {
	wp_nonce_field( 'sor_personality_save', 'sor_personality_nonce' );
	$fields = array(
		'sor_name_kana' => array( 'ふりがな', 'さとう みさき', 'ひらがなで入力。一覧の並び順（五十音順）に使われます。' ),
		'sor_name_en'   => array( '英字名', 'Misaki Sato', '氏名の下に小さく表示されます。' ),
		'sor_days'      => array( '出演曜日', '火曜, 木曜', 'カンマ区切り。詳細ページにタグとして表示されます。' ),
	);
	echo '<table class="form-table">';
	foreach ( $fields as $key => $f ) {
		printf(
			'<tr><th><label for="%1$s">%2$s</label></th><td><input type="text" id="%1$s" name="%1$s" value="%3$s" class="regular-text" placeholder="%4$s"><p class="description">%5$s</p></td></tr>',
			esc_attr( $key ),
			esc_html( $f[0] ),
			esc_attr( get_post_meta( $post->ID, $key, true ) ),
			esc_attr( $f[1] ),
			esc_html( $f[2] )
		);
	}
	echo '</table>';
}

function sor_personality_save( $post_id ) {
	if ( ! isset( $_POST['sor_personality_nonce'] ) ||
		! wp_verify_nonce( sanitize_key( $_POST['sor_personality_nonce'] ), 'sor_personality_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	foreach ( array( 'sor_name_kana', 'sor_name_en', 'sor_days' ) as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}
}
add_action( 'save_post_personality', 'sor_personality_save' );

/**
 * 番組の追加項目（出演者・放送時間）の入力欄
 *
 * 出演者はテキスト自由入力にすると、パーソナリティー詳細の「担当番組」が
 * 氏名の LIKE 一致で拾えなくなる（誤字・表記ゆれで一致しない）。
 * そのためパーソナリティーCPTから選ぶチェックボックス方式にしている。
 */
function sor_program_metabox() {
	add_meta_box(
		'sor_program_fields',
		__( '番組情報', 'sor' ),
		'sor_program_metabox_html',
		'program',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'sor_program_metabox' );

function sor_program_metabox_html( $post ) {
	wp_nonce_field( 'sor_program_save', 'sor_program_nonce' );

	$cast     = get_post_meta( $post->ID, 'sor_cast', true );
	$selected = array_filter( array_map( 'trim', explode( '／', (string) $cast ) ) );
	$people   = get_posts( sor_personality_query_args( array( 'numberposts' => 100 ) ) );

	echo '<table class="form-table"><tr><th>' . esc_html__( '出演者', 'sor' ) . '</th><td>';
	if ( $people ) {
		foreach ( $people as $p ) {
			printf(
				'<label style="display:inline-block;margin:0 16px 8px 0"><input type="checkbox" name="sor_cast_ids[]" value="%1$s"%2$s> %1$s</label>',
				esc_attr( $p->post_title ),
				in_array( $p->post_title, $selected, true ) ? ' checked' : ''
			);
		}
		echo '<p class="description">' . esc_html__( 'パーソナリティーから選びます。ここで選ぶと、その人の詳細ページの「担当番組」に自動で表示されます。', 'sor' ) . '</p>';
	} else {
		echo '<p class="description">' . esc_html__( '先に「パーソナリティー」を登録・公開してください。', 'sor' ) . '</p>';
	}
	echo '</td></tr>';

	printf(
		'<tr><th><label for="sor_time">%1$s</label></th><td><input type="text" id="sor_time" name="sor_time" value="%2$s" class="regular-text" placeholder="18:00"><p class="description">%3$s</p></td></tr>',
		esc_html__( '放送時間', 'sor' ),
		esc_attr( get_post_meta( $post->ID, 'sor_time', true ) ),
		esc_html__( 'カードに曜日と並べて表示されます。放送曜日は右の「放送曜日」で設定してください。', 'sor' )
	);
	echo '</table>';
}

function sor_program_save( $post_id ) {
	if ( ! isset( $_POST['sor_program_nonce'] ) ||
		! wp_verify_nonce( sanitize_key( $_POST['sor_program_nonce'] ), 'sor_program_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$ids = isset( $_POST['sor_cast_ids'] ) ? (array) wp_unslash( $_POST['sor_cast_ids'] ) : array();
	$ids = array_map( 'sanitize_text_field', $ids );
	update_post_meta( $post_id, 'sor_cast', implode( '／', $ids ) );

	if ( isset( $_POST['sor_time'] ) ) {
		update_post_meta( $post_id, 'sor_time', sanitize_text_field( wp_unslash( $_POST['sor_time'] ) ) );
	}
}
add_action( 'save_post_program', 'sor_program_save' );

/**
 * パーソナリティーをふりがなの五十音順で取得する WP_Query 引数を返す
 *
 * タイトル順だと日本語は文字コード順になり、読みの順にならないため
 * ふりがな（sor_name_kana）で並べる。
 *
 * 注意：meta_key を指定するだけだと INNER JOIN になり、
 * ふりがな未入力のパーソナリティーが一覧から丸ごと消える。
 * EXISTS / NOT EXISTS の OR で結合し、未入力でも必ず表示されるようにしている。
 */
function sor_personality_query_args( $extra = array() ) {
	return array_merge( array(
		'post_type'  => 'personality',
		'meta_query' => array(
			'relation' => 'OR',
			'kana'     => array( 'key' => 'sor_name_kana', 'compare' => 'EXISTS' ),
			'no_kana'  => array( 'key' => 'sor_name_kana', 'compare' => 'NOT EXISTS' ),
		),
		'orderby'    => array( 'kana' => 'ASC', 'title' => 'ASC' ),
	), $extra );
}

/**
 * Contact Form 7：リクエストフォームの「番組を選択」を番組CPTから動的に生成する
 *
 * CF7側は選択肢を空にした [select program] を置くだけでよい。
 * 番組が増減しても、フォーム定義を編集する必要がなくなる。
 */
function sor_cf7_program_options( $tag ) {
	if ( ! class_exists( 'WPCF7_FormTag' ) ) {
		return $tag;
	}
	$tag = new WPCF7_FormTag( $tag );

	if ( 'program' !== $tag->name ) {
		return $tag;
	}

	$programs = get_posts( array(
		'post_type'   => 'program',
		'numberposts' => 100,
		'orderby'     => 'title',
		'order'       => 'ASC',
	) );

	$options = array();
	foreach ( $programs as $p ) {
		$options[] = $p->post_title;
	}
	$options[] = 'その他';

	// $tag は WPCF7_FormTag オブジェクト（ArrayAccess）。
	// $tag['values'][] = ... は offsetGet がコピーを返すため追記が効かない。
	// 必ずプロパティに配列ごと代入すること。
	$tag->raw_values = $options;
	$tag->values     = $options;
	$tag->labels     = $options;

	return $tag;
}
add_filter( 'wpcf7_form_tag', 'sor_cf7_program_options', 10, 1 );

/**
 * Contact Form 7 が入っていない場合に、リクエストのページ本文へ貼るショートコードを
 * 管理画面で案内する（テンプレート側のダミー表示と対になる注意喚起）
 */
function sor_cf7_admin_notice() {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}
	if ( defined( 'WPCF7_VERSION' ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || 'themes' !== $screen->id ) {
		return;
	}
	echo '<div class="notice notice-warning"><p><strong>SHIZUOKA OCEANS RADIO テーマ</strong>：リクエストフォームは Contact Form 7 を前提にしています。未導入のため、リクエストページは送信できないダミー表示になります。</p></div>';
}
add_action( 'admin_notices', 'sor_cf7_admin_notice' );
