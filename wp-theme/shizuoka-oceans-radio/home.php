<?php
/**
 * 一覧（お知らせ／各アーカイブ共通のフォールバック）
 * @package sor
 */
get_header(); ?>
<main class="pages">
  <?php get_template_part( 'template-parts/page-header' ); ?>
  <div class="pages-body py-md">
    <div class="container">
      <div class="row">
        <?php
        if ( have_posts() ) :
        	while ( have_posts() ) : the_post();
        		$pt = get_post_type();
        		if ( 'program' === $pt )          { get_template_part( 'template-parts/card', 'program' ); }
        		elseif ( 'personality' === $pt )  { get_template_part( 'template-parts/card', 'personality' ); }
        		else                              { get_template_part( 'template-parts/card', 'post' ); }
        	endwhile;
        else : ?>
        	<p class="f-14"><?php esc_html_e( '記事がありません。', 'sor' ); ?></p>
        <?php endif; ?>
      </div>
      <div class="post-nav text-center mt-5"><?php the_posts_pagination( array( 'mid_size' => 2 ) ); ?></div>
    </div>
  </div>
</main>
<?php get_footer(); ?>
