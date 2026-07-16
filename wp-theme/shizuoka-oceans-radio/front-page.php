<?php
/**
 * トップページ：ヒーロー ＋ NEWS ＋ PROGRAM ＋ PERSONALITY
 * @package sor
 */
get_header();
$uri = get_template_directory_uri();
?>
<main id="home" class="pages">

<!-- ===================== HERO ===================== -->
<div id="home-hero">
  <div id="home-hero-mainvisual">
    <img src="<?php echo esc_url( $uri . '/assets/img/home/hero-wave.svg' ); ?>" alt="" id="home-hero-mainvisual__wave">
    <div id="home-hero-mainvisual__inner" class="d-lg-flex align-items-end justify-content-center text-center">
      <img src="<?php echo esc_url( $uri . '/assets/img/surfer.png' ); ?>" alt="サーフィンをする<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>のキャラクター" id="home-hero-mainvisual__img">
      <div id="home-hero-nowonair" class="d-md-flex align-items-start justify-content-center">
        <div id="home-hero-nowonair__title" class="d-md-block d-flex align-items-center">
          <img src="<?php echo esc_url( $uri . '/assets/img/home/hero-icon-onair.svg' ); ?>" alt="">
          <h2 id="home-hero-nowonair__title__title" class="mt-md-2 mb-0">NOW ON AIR</h2>
        </div>
        <div class="wrap">
          <div class="wrap__date"><?php echo esc_html( get_theme_mod( 'sor_onair_date', '07.14 TUE 13:30 – 16:30' ) ); ?></div>
          <div class="wrap__title mb-2"><?php echo esc_html( get_theme_mod( 'sor_onair_title', 'MIDDAY LOUNGE' ) ); ?></div>
          <div class="d-md-flex align-items-start justify-content-between">
            <div class="d-flex align-items-center wrap__personality">
              <i class="fa-solid fa-microphone pe-1"></i> <?php echo esc_html( get_theme_mod( 'sor_onair_cast', '市川 紗梛' ) ); ?>
            </div>
            <div class="text-end">
              <a href="<?php echo esc_url( get_theme_mod( 'sor_listen_url', '#' ) ); ?>" target="_blank" rel="noopener" id="home-hero-btn">PLAY NOW</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ===================== NEWS ===================== -->
<section id="home-news" class="home-sc pt-lg pb-xl">
  <div class="container">
    <h2 class="home-sc__title mb-5"><span>NEWS</span>お知らせ</h2>
    <div class="row">
      <?php
      $news = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => 6, 'ignore_sticky_posts' => true ) );
      if ( $news->have_posts() ) :
      	while ( $news->have_posts() ) : $news->the_post();
      		get_template_part( 'template-parts/card', 'post' );
      	endwhile;
      	wp_reset_postdata();
      else : ?>
      	<p class="f-14"><?php esc_html_e( 'お知らせはまだありません。', 'sor' ); ?></p>
      <?php endif; ?>
    </div>
    <div class="text-center mt-5"><a href="<?php echo esc_url( sor_list_url( 'post' ) ); ?>" class="btn">お知らせ一覧はこちら</a></div>
  </div>
</section>

<!-- ===================== PROGRAM ===================== -->
<section id="home-program" class="home-sc pb-xl">
  <div class="container">
    <h2 class="home-sc__title mb-5"><span>Program</span>番組一覧</h2>
    <div id="home-program-slider" class="home-slider">
      <?php
      $prog = new WP_Query( array( 'post_type' => 'program', 'posts_per_page' => 14 ) );
      while ( $prog->have_posts() ) : $prog->the_post(); ?>
        <div class="px-2">
          <a href="<?php the_permalink(); ?>" class="program-post">
            <div class="program-post__thumb"><?php sor_thumbnail( 'sor-card' ); ?></div>
            <p class="h-18 f-bold mt-2 mb-2"><?php the_title(); ?></p>
          </a>
        </div>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
    <div class="home-slider-nav d-flex align-items-center justify-content-center mt-5">
      <div class="arrows"></div>
      <div class="home-slider-nav__count"><span class="bar"></span></div>
    </div>
  </div>
</section>

<!-- ===================== PERSONALITY ===================== -->
<section id="home-personality" class="home-sc pb-xl">
  <div class="container">
    <h2 class="home-sc__title mb-5"><span>PERSONALITY</span>パーソナリティー</h2>
    <div id="home-personality-slider" class="home-slider">
      <?php
      $per = new WP_Query( sor_personality_query_args( array( 'posts_per_page' => 12 ) ) );
      while ( $per->have_posts() ) : $per->the_post(); ?>
        <div class="px-2">
          <a href="<?php the_permalink(); ?>" class="personality-post">
            <div class="personality-post__thumb"><?php sor_thumbnail( 'sor-square' ); ?></div>
            <p class="h-18 f-bold mt-2 mb-1"><?php the_title(); ?></p>
          </a>
        </div>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
    <div class="home-slider-nav d-flex align-items-center justify-content-center mt-5">
      <div class="arrows"></div>
      <div class="home-slider-nav__count"><span class="bar"></span></div>
    </div>
  </div>
</section>

</main>
<?php get_footer(); ?>
