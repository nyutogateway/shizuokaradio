<?php
/**
 * パーソナリティーカード（.personality-post）
 * @package sor
 */
$kana = get_post_meta( get_the_ID(), 'sor_name_kana', true );
$en   = get_post_meta( get_the_ID(), 'sor_name_en', true );
?>
<div class="col-md-3 col-6 mb-4">
  <a href="<?php the_permalink(); ?>" class="personality-post">
    <div class="personality-post__thumb"><?php sor_thumbnail( 'sor-square' ); ?></div>
    <?php if ( $kana ) : ?><p class="f-14 mt-2 mb-0 personality-post__kana"><?php echo esc_html( $kana ); ?></p><?php endif; ?>
    <p class="h-18 f-bold <?php echo $kana ? 'mb-1' : 'mt-2 mb-1'; ?>"><?php the_title(); ?></p>
    <?php if ( $en ) : ?><p class="f-14 personality-post__en"><?php echo esc_html( $en ); ?></p><?php endif; ?>
  </a>
</div>
