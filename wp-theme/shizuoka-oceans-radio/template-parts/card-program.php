<?php
/**
 * 番組カード（.program-post）
 * @package sor
 */
$days = get_the_terms( get_the_ID(), 'program_day' );
$cast = get_post_meta( get_the_ID(), 'sor_cast', true );
?>
<div class="col-md-4 col-6 mb-4">
  <a href="<?php the_permalink(); ?>" class="program-post">
    <div class="program-post__thumb"><?php sor_thumbnail( 'sor-card' ); ?></div>
    <?php if ( $days && ! is_wp_error( $days ) ) : ?>
      <ul class="list-style-none mb-0 mt-2 d-flex flex-wrap program-tag">
        <?php foreach ( $days as $d ) : ?><li><?php echo esc_html( $d->name ); ?></li><?php endforeach; ?>
      </ul>
    <?php endif; ?>
    <p class="h-18 f-bold mt-2 mb-2"><?php the_title(); ?></p>
    <?php if ( $cast ) : ?><p class="f-14"><?php echo esc_html( $cast ); ?></p><?php endif; ?>
  </a>
</div>
