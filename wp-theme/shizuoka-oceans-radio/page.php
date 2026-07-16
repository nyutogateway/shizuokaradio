<?php
/**
 * 固定ページ（会社概要 / プライバシーポリシー / リクエスト等）
 * @package sor
 */
get_header(); ?>
<main class="pages">
  <?php get_template_part( 'template-parts/page-header' ); ?>
  <div class="pages-body py-md">
    <div class="container">
      <div class="col-lg-8 mx-auto">
        <?php
        while ( have_posts() ) : the_post();
        	the_content();
        endwhile;
        ?>
      </div>
    </div>
  </div>
</main>
<?php get_footer(); ?>
