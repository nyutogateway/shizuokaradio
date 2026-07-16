<?php
/**
 * お知らせカード（.topics-post）
 * @package sor
 */
?>
<div class="col-md-4 col-6 mb-4">
  <a href="<?php the_permalink(); ?>" class="topics-post">
    <div class="topics-post__thumb"><?php sor_thumbnail( 'sor-card' ); ?></div>
    <?php
    $cats = get_the_category();
    if ( $cats ) : ?>
      <ul class="list-style-none topics-cat d-flex mt-2 mb-2">
        <?php foreach ( array_slice( $cats, 0, 1 ) as $c ) : ?>
          <li><?php echo esc_html( $c->name ); ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
    <p class="f-14 mb-1"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></p>
    <p class="h-18 f-bold"><?php the_title(); ?></p>
  </a>
</div>
