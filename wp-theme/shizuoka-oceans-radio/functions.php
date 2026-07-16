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
		'primary'      => __( 'ヘッダー（左右のメインナビ）', 'sor' ),
		'footer'       => __( 'フッター（主要メニュー）', 'sor' ),
		'footer_small' => __( 'フッター（小メニュー）', 'sor' ),
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
		'has_archive'   => true,
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
		'has_archive'   => true,
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
