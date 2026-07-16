<?php
/**
 * パーソナリティー詳細（プロフィール＋担当番組＋前後ナビ）
 * @package sor
 */
get_header(); ?>
<main class="pages">
  <?php get_template_part( 'template-parts/page-header' ); ?>
  <div class="pages-body py-md">
    <div class="container">
      <?php while ( have_posts() ) : the_post();
        $en = get_post_meta( get_the_ID(), 'sor_name_en', true );
        $days = get_post_meta( get_the_ID(), 'sor_days', true );
      ?>
      <div class="row align-items-start">
        <div class="col-md-5 mb-4">
          <div class="personality-post__thumb"><?php sor_thumbnail( 'sor-square', get_the_title() ); ?></div>
        </div>
        <div class="col-md-7 mb-4">
          <?php if ( $en ) : ?><p class="primary-en allcap f-med personality-post__en mb-1"><?php echo esc_html( $en ); ?></p><?php endif; ?>
          <h1 class="h-36 f-bold mb-4"><?php the_title(); ?></h1>
          <?php if ( $days ) : ?>
            <ul class="list-style-none d-flex flex-wrap mb-4 program-tag">
              <?php foreach ( array_map( 'trim', explode( ',', $days ) ) as $d ) : ?><li><?php echo esc_html( $d ); ?></li><?php endforeach; ?>
            </ul>
          <?php endif; ?>
          <div class="f-14 mb-4"><?php the_content(); ?></div>
        </div>
      </div>

      <?php
      // 担当番組：番組側のメタ sor_cast に氏名が含まれるものを拾う
      $programs = new WP_Query( array(
      	'post_type'      => 'program',
      	'posts_per_page' => 6,
      	'meta_query'     => array( array( 'key' => 'sor_cast', 'value' => get_the_title(), 'compare' => 'LIKE' ) ),
      ) );
      if ( $programs->have_posts() ) : ?>
        <div class="title text-center my-5"><span>担当番組</span></div>
        <div class="row">
          <?php while ( $programs->have_posts() ) : $programs->the_post(); get_template_part( 'template-parts/card', 'program' ); endwhile; wp_reset_postdata(); ?>
        </div>
      <?php endif; ?>

      <?php endwhile; ?>
      <?php get_template_part( 'template-parts/post-nav' ); ?>
    </div>
  </div>
</main>
<?php get_footer(); ?>
