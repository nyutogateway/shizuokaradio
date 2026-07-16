<?php
/**
 * Template Name: 1カラム（全幅）
 * Template Post Type: page
 *
 * ブロックエディタで自由にレイアウトを組みたいページ用。
 *
 * @package sor
 */
get_header(); ?>
<main class="pages">
  <?php get_template_part( 'template-parts/page-header' ); ?>
  <div class="pages-body py-md">
    <div class="container">
      <?php while ( have_posts() ) : the_post(); the_content(); endwhile; ?>
    </div>
  </div>
</main>
<?php get_footer(); ?>
