<?php
/**
 * お知らせ記事の詳細
 * @package sor
 */
get_header(); ?>
<main class="pages">
  <?php get_template_part( 'template-parts/page-header' ); ?>
  <div class="pages-body py-md">
    <div class="container">
      <div class="col-lg-8 mx-auto">
        <?php while ( have_posts() ) : the_post(); ?>
          <?php $cats = get_the_category(); if ( $cats ) : ?>
            <ul class="list-style-none topics-cat d-flex mb-3"><li><?php echo esc_html( $cats[0]->name ); ?></li></ul>
          <?php endif; ?>
          <p class="f-14 mb-2"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></p>
          <h1 class="h-26 f-bold mb-4"><?php the_title(); ?></h1>
          <?php if ( has_post_thumbnail() ) : ?>
            <div class="topics-post__thumb mb-4"><?php the_post_thumbnail( 'sor-hero', array( 'alt' => esc_attr( get_the_title() ) ) ); ?></div>
          <?php endif; ?>
          <div class="mb-5"><?php the_content(); ?></div>
        <?php endwhile; ?>

        <?php get_template_part( 'template-parts/post-nav' ); ?>
      </div>
    </div>
  </div>
</main>
<?php get_footer(); ?>
