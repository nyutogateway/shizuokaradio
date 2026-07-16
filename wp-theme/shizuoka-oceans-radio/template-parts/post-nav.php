<?php
/**
 * 前後ナビ（.post-single-nav）
 * @package sor
 */
$prev = get_previous_post();
$next = get_next_post();
$pt   = get_post_type();
$archive = ( 'post' === $pt ) ? home_url( '/news/' ) : get_post_type_archive_link( $pt );
$size = ( 'personality' === $pt ) ? 'sor-square' : 'sor-card';
if ( ! $prev && ! $next ) { return; }
?>
<div class="post-single-nav d-flex align-items-center justify-content-between">
  <?php if ( $prev ) : ?>
    <a href="<?php echo esc_url( get_permalink( $prev ) ); ?>" class="wrap wrap--prev d-flex align-items-center">
      <div class="wrap--prev__thumb"><?php echo get_the_post_thumbnail( $prev, $size, array( 'class' => 'wrap__thumb__img', 'alt' => '' ) ); ?></div>
      <div class="wrap__txt"><i class="fa-solid fa-arrow-left-long pe-2"></i><?php echo esc_html( get_the_title( $prev ) ); ?></div>
    </a>
  <?php else : ?><span></span><?php endif; ?>

  <a href="<?php echo esc_url( $archive ); ?>" class="btn d-none d-md-inline-block">一覧へ</a>

  <?php if ( $next ) : ?>
    <a href="<?php echo esc_url( get_permalink( $next ) ); ?>" class="wrap wrap--next d-flex align-items-center text-end">
      <div class="wrap__txt"><?php echo esc_html( get_the_title( $next ) ); ?><i class="fa-solid fa-arrow-right-long ps-2"></i></div>
      <div class="wrap--next__thumb"><?php echo get_the_post_thumbnail( $next, $size, array( 'class' => 'wrap__thumb__img', 'alt' => '' ) ); ?></div>
    </a>
  <?php else : ?><span></span><?php endif; ?>
</div>
