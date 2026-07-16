<?php
/**
 * 番組カード（.program-post）
 *
 * 静的HTMLの「曜日／時間」の2タグ構成を再現する。
 * 曜日はタクソノミー program_day、時間はメタ sor_time。
 *
 * @package sor
 */
$days = get_the_terms( get_the_ID(), 'program_day' );
$time = get_post_meta( get_the_ID(), 'sor_time', true );
$cast = get_post_meta( get_the_ID(), 'sor_cast', true );
$has_tag = ( $days && ! is_wp_error( $days ) ) || $time;
?>
<div class="col-md-4 col-6 mb-4">
  <a href="<?php the_permalink(); ?>" class="program-post">
    <div class="program-post__thumb"><?php sor_thumbnail( 'sor-card' ); ?></div>
    <?php if ( $has_tag ) : ?>
      <ul class="list-style-none mb-0 mt-2 d-flex flex-wrap program-tag">
        <?php
        if ( $days && ! is_wp_error( $days ) ) {
          foreach ( $days as $d ) {
            echo '<li>' . esc_html( $d->name ) . '</li>';
          }
        }
        if ( $time ) {
          echo '<li>' . esc_html( $time ) . '</li>';
        }
        ?>
      </ul>
    <?php endif; ?>
    <p class="h-18 f-bold mt-2 mb-2"><?php the_title(); ?></p>
    <?php if ( $cast ) : ?><p class="f-14"><?php echo esc_html( $cast ); ?></p><?php endif; ?>
  </a>
</div>
