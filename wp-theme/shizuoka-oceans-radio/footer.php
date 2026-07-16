<?php
/**
 * フッター＋右下の固定ボタン
 *
 * 注意：Bootstrapの .bg-black は !important 付きで自前指定に勝ってしまうため、
 * 静的HTML同様このクラスは使わず #footer に直接スタイルを当てている。
 *
 * @package sor
 */
$uri  = get_template_directory_uri();
$name = get_bloginfo( 'name' );
?>

<!-- ===================== FOOTER ===================== -->
<footer id="footer" class="py-lg">
  <div class="container text-center">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" id="footer-logo" class="d-inline-block mb-4">
      <img src="<?php echo esc_url( $uri . '/assets/img/home/SHIZUOKA-OCEANS-RADIO-1a-dark.svg' ); ?>" alt="<?php echo esc_attr( $name ); ?>">
    </a>

    <p class="mb-4"><?php echo wp_kses_post( get_theme_mod( 'sor_address', $name . '<br>〒000-0000 ○○県○○市○○ 0-0-0<br>TEL：000-000-0000 / FAX：000-000-0000' ) ); ?></p>

    <?php
    if ( has_nav_menu( 'footer' ) ) {
    	wp_nav_menu( array(
    		'theme_location' => 'footer',
    		'menu_id'        => 'footer-menu',
    		'menu_class'     => 'list-style-none d-flex flex-wrap justify-content-center mb-4',
    		'container'      => false,
    		'depth'          => 1,
    	) );
    } else {
    	?>
    	<ul id="footer-menu" class="list-style-none d-flex flex-wrap justify-content-center mb-4">
    	  <li><a href="<?php echo esc_url( home_url( '/news/' ) ); ?>">お知らせ</a></li>
    	  <li><a href="<?php echo esc_url( get_post_type_archive_link( 'program' ) ); ?>">番組一覧</a></li>
    	  <li><a href="<?php echo esc_url( get_post_type_archive_link( 'personality' ) ); ?>">パーソナリティー</a></li>
    	  <li><a href="<?php echo esc_url( home_url( '/request/' ) ); ?>">リクエスト</a></li>
    	</ul>
    	<?php
    }

    if ( has_nav_menu( 'footer_small' ) ) {
    	wp_nav_menu( array(
    		'theme_location' => 'footer_small',
    		'menu_id'        => 'footer-menu-sm',
    		'menu_class'     => 'list-style-none d-flex flex-wrap justify-content-center mb-4',
    		'container'      => false,
    		'depth'          => 1,
    	) );
    } else {
    	?>
    	<ul id="footer-menu-sm" class="list-style-none d-flex flex-wrap justify-content-center mb-4">
    	  <li><a href="<?php echo esc_url( home_url( '/company/' ) ); ?>">会社概要</a></li>
    	  <li><a href="#">広告料金表</a></li>
    	  <li><a href="<?php echo esc_url( home_url( '/privacypolicy/' ) ); ?>">プライバシーポリシー</a></li>
    	</ul>
    	<?php
    }
    ?>

    <div id="footer-credit" class="pt-4">&copy; <?php echo esc_html( $name ); ?>. All Right Reserved.</div>
  </div>
</footer>

<!-- 固定ボタン -->
<a href="<?php echo esc_url( get_theme_mod( 'sor_spotify_url', 'https://open.spotify.com/' ) ); ?>" target="_blank" rel="noopener" id="footer-spotify">
  <img src="<?php echo esc_url( $uri . '/assets/img/common/sticker-spotify.svg' ); ?>" alt="SPOTIFY ON AIR SONGS">
</a>
<a href="<?php echo esc_url( get_theme_mod( 'sor_listen_url', '#' ) ); ?>" id="footer-listen">
  <img src="<?php echo esc_url( $uri . '/assets/img/common/sticker-listen.svg' ); ?>" alt="<?php echo esc_attr( $name ); ?>を今すぐ聴く">
</a>

<?php wp_footer(); ?>
</body>
</html>
