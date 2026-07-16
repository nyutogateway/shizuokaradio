<?php
/**
 * 下層ページのヘッダー（アクアの帯）＋ NOW ON AIR バー ＋ パンくず
 * 静的HTMLでは各ファイルに重複していた部分
 *
 * @package sor
 */
list( $en, $ja ) = sor_header_label();
$uri = get_template_directory_uri();
?>
<div class="pages-header">
  <div class="container">
    <div class="d-flex align-items-center justify-content-between">
      <h1 class="pages-header__title"><span><?php echo esc_html( $en ); ?></span><?php echo esc_html( $ja ); ?></h1>
      <img src="<?php echo esc_url( $uri . '/assets/img/surfer.png' ); ?>" alt="サーフィンをする<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>のキャラクター" class="pages-header__img">
    </div>
  </div>
</div>

<?php get_template_part( 'template-parts/onair-bar' ); ?>

<div class="pages-breadcrumbs mt-4">
  <div class="container">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">TOP</a> /
    <?php if ( is_singular( array( 'post', 'personality', 'program' ) ) ) : ?>
      <?php
      $pt   = get_post_type();
      $link = sor_list_url( $pt );
      ?>
      <a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $ja ); ?></a> /
      <span class="current-item"><?php the_title(); ?></span>
    <?php else : ?>
      <span class="current-item"><?php echo esc_html( $ja ); ?></span>
    <?php endif; ?>
  </div>
</div>
