<?php
/**
 * Template Name: 1カラム（幅せまめ・規約/フォーム向け）
 * Template Post Type: page
 *
 * プライバシーポリシーやリクエストフォームなど、
 * 読みやすさ優先で本文幅を絞るページ用。
 *
 * @package sor
 */
get_header(); ?>
<main class="pages">
  <?php get_template_part( 'template-parts/page-header' ); ?>
  <div class="pages-body py-md">
    <div class="container">
      <div class="col-lg-8 mx-auto">
        <?php while ( have_posts() ) : the_post(); the_content(); endwhile; ?>
      </div>
    </div>
  </div>
</main>
<?php get_footer(); ?>
