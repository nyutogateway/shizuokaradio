<?php
/**
 * ヘッダー（PC / スティッキー / SP）
 * 静的HTMLでは全10ファイルに重複していた部分をここへ集約
 *
 * @package sor
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
$sor_logo = get_template_directory_uri() . '/assets/img/home/SHIZUOKA-OCEANS-RADIO-1a-dark.svg';
$sor_name = get_bloginfo( 'name' );

/**
 * 左右のナビ。登録メニューがあればそれを使い、無ければ静的HTMLと同じ既定項目を出す
 */
function sor_nav( $side ) {
	$items = array(
		'left'  => array(
			array( 'NEWS', 'お知らせ', home_url( '/news/' ) ),
			array( 'PROGRAM', '番組一覧', get_post_type_archive_link( 'program' ) ),
		),
		'right' => array(
			array( 'PERSONALITY', 'パーソナリティー', get_post_type_archive_link( 'personality' ) ),
			array( 'REQUEST', 'リクエスト', home_url( '/request/' ) ),
		),
	);
	foreach ( $items[ $side ] as $it ) {
		printf(
			'<li><a href="%s"><span>%s</span>%s</a></li>',
			esc_url( $it[2] ), esc_html( $it[0] ), esc_html( $it[1] )
		);
	}
}
?>

<!-- ===================== HEADER: PC ===================== -->
<nav id="header-pc" class="d-lg-block d-none header-pc">
  <div class="container-fluid">
    <div class="d-flex justify-content-center align-items-center">
      <ul class="menu menu--left list-style-none mb-0 d-flex align-items-center justify-content-end"><?php sor_nav( 'left' ); ?></ul>
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" id="header-pc-logo">
        <img src="<?php echo esc_url( $sor_logo ); ?>" alt="<?php echo esc_attr( $sor_name ); ?>">
      </a>
      <ul class="menu menu--right list-style-none mb-0 d-flex align-items-center justify-content-start"><?php sor_nav( 'right' ); ?></ul>
    </div>
  </div>
</nav>

<!-- ===================== HEADER: STICKY ===================== -->
<nav id="header-sticky" class="d-lg-block d-none header-pc">
  <div class="container-fluid">
    <div class="d-flex justify-content-center align-items-center">
      <ul class="menu menu--left list-style-none mb-0 d-flex align-items-center justify-content-end"><?php sor_nav( 'left' ); ?></ul>
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" id="header-sticky-logo">
        <img src="<?php echo esc_url( $sor_logo ); ?>" alt="<?php echo esc_attr( $sor_name ); ?>">
      </a>
      <ul class="menu menu--right list-style-none mb-0 d-flex align-items-center justify-content-start"><?php sor_nav( 'right' ); ?></ul>
    </div>
  </div>
</nav>

<!-- ===================== HEADER: SP ===================== -->
<nav id="header-sp" class="d-lg-none d-block text-center">
  <div class="container-fluid">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" id="header-sp-logo" class="d-inline-block">
      <img src="<?php echo esc_url( $sor_logo ); ?>" alt="<?php echo esc_attr( $sor_name ); ?>">
    </a>
  </div>
</nav>
<button id="header-sp-toggler" class="d-lg-none" type="button" aria-label="<?php esc_attr_e( 'メニューを開く', 'sor' ); ?>">
  <span class="line line--top"></span>
  <span class="line line--center"></span>
  <span class="line line--bottom"></span>
</button>
<div id="header-sp-menu" class="d-lg-none">
  <div id="header-sp-menu__inner">
    <ul id="header-sp-menu-menu" class="list-style-none text-center mb-5">
      <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><span>TOP</span>トップ</a></li>
      <?php sor_nav( 'left' ); ?>
      <?php sor_nav( 'right' ); ?>
    </ul>
  </div>
</div>
