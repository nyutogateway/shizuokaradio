<?php
/**
 * NOW ON AIR バー（濃紺）。現在放送中の番組を表示する
 * ※実運用では番組表プラグインやACFの値に差し替える想定
 *
 * @package sor
 */
$onair = get_theme_mod( 'sor_onair_text', '07.14 TUE 13:30 – 16:30　MIDDAY LOUNGE' );
?>
<div class="comp-onair">
  <div class="container">
    <div class="d-md-flex align-items-center">
      <h3 class="comp-onair__title d-flex align-items-center mb-0"><img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/common/icon-onair.svg' ); ?>" alt="">NOW ON AIR</h3>
      <div class="comp-onair__onair"><?php echo esc_html( $onair ); ?></div>
    </div>
  </div>
</div>
