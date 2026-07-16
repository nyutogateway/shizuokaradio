<?php
/**
 * Template Name: パーソナリティー一覧
 * Template Post Type: page
 *
 * パーソナリティーCPTは has_archive=false（一覧は固定ページに一本化）のため、
 * このテンプレートが一覧の描画を担う。
 * 並び順はふりがな（sor_name_kana）の五十音順。
 *
 * @package sor
 */
get_header();

$paged = max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
$q = new WP_Query( sor_personality_query_args( array(
	'posts_per_page' => 24,
	'paged'          => $paged,
) ) );
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
            get_template_part( 'template-parts/card', 'personality' );
          endwhile;
        else : ?>
          <p class="f-14"><?php esc_html_e( 'パーソナリティーはまだ登録されていません。', 'sor' ); ?></p>
        <?php endif; ?>
      </div>
      <?php if ( $q->max_num_pages > 1 ) : ?>
        <div class="post-nav text-center mt-5">
          <?php echo wp_kses_post( paginate_links( array(
            'total'   => $q->max_num_pages,
            'current' => $paged,
            'mid_size' => 2,
          ) ) ); ?>
        </div>
      <?php endif; ?>
      <?php wp_reset_postdata(); ?>
    </div>
  </div>
</main>
<?php get_footer(); ?>
