<?php
/**
 * Template Name: お知らせ一覧
 * Template Post Type: page
 *
 * お知らせ（投稿）の一覧。
 *
 * ※WordPressの標準は「設定 > 表示設定 > 投稿ページ」で一覧を出す方法で、
 *   その場合は home.php が使われる（こちらのテンプレートは不要）。
 *   このテンプレートは、他の一覧（パーソナリティー／番組）と同じく
 *   固定ページで揃えて運用したい場合に使う。どちらか一方でよい。
 *
 * @package sor
 */
get_header();

$paged = max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
$q = new WP_Query( array(
	'post_type'      => 'post',
	'posts_per_page' => 12,
	'paged'          => $paged,
	'ignore_sticky_posts' => true,
) );
?>
<main class="pages">
  <?php get_template_part( 'template-parts/page-header' ); ?>
  <div class="pages-body py-md">
    <div class="container">
      <?php
      // 固定ページ本文にリード文があれば先に出す
      while ( have_posts() ) : the_post();
        if ( trim( wp_strip_all_tags( get_the_content() ) ) ) {
          echo '<div class="mb-4">'; the_content(); echo '</div>';
        }
      endwhile;
      ?>
      <div class="row">
        <?php
        if ( $q->have_posts() ) :
          while ( $q->have_posts() ) : $q->the_post();
            get_template_part( 'template-parts/card', 'post' );
          endwhile;
        else : ?>
          <p class="f-14"><?php esc_html_e( 'お知らせはまだありません。', 'sor' ); ?></p>
        <?php endif; ?>
      </div>
      <?php if ( $q->max_num_pages > 1 ) : ?>
        <div class="post-nav text-center mt-5">
          <?php echo wp_kses_post( paginate_links( array(
            'total'    => $q->max_num_pages,
            'current'  => $paged,
            'mid_size' => 2,
          ) ) ); ?>
        </div>
      <?php endif; ?>
      <?php wp_reset_postdata(); ?>
    </div>
  </div>
</main>
<?php get_footer(); ?>
