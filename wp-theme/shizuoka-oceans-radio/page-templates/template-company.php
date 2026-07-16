<?php
/**
 * Template Name: 会社概要（左メニュー付き）
 * Template Post Type: page
 *
 * 左に追従メニュー、右に本文の2カラム。
 * 本文中の <h2> を自動で拾って左メニューを組み立てるので、
 * 編集画面では見出し2（H2）で各セクションを作るだけでよい。
 *
 * @package sor
 */
get_header(); ?>
<main class="pages">
  <?php get_template_part( 'template-parts/page-header' ); ?>
  <div class="pages-body py-md">
    <div class="container">
      <div class="row">
        <?php
        while ( have_posts() ) : the_post();
          $content = apply_filters( 'the_content', get_the_content() );

          // 本文の H2 を拾って id を振り、左メニューの項目にする。
          // ・s フラグ必須：見出しに改行が含まれると (.*?) が跨げず、その見出しを取りこぼす
          // ・既に id があれば（ブロックエディタのHTMLアンカー）それを使う。
          //   無条件に付けると id が重複し、ブラウザが先頭を採用してリンク先がずれる
          $nav = array();
          $content = preg_replace_callback(
            '/<h2([^>]*)>(.*?)<\/h2>/is',
            function ( $m ) use ( &$nav ) {
              $attrs = $m[1];
              $text  = trim( wp_strip_all_tags( $m[2] ) );
              if ( '' === $text ) {
                return $m[0];
              }
              if ( preg_match( '/\bid=["\']([^"\']+)["\']/i', $attrs, $has_id ) ) {
                $nav[] = array( 'id' => $has_id[1], 'text' => $text );
                return $m[0];
              }
              $id = 'sec-' . ( count( $nav ) + 1 );
              $nav[] = array( 'id' => $id, 'text' => $text );
              return '<h2 id="' . esc_attr( $id ) . '"' . $attrs . '>' . $m[2] . '</h2>';
            },
            $content
          );
        ?>
        <div class="col-lg-3">
          <?php if ( $nav ) : ?>
            <div id="company-nav" class="mb-4">
              <h3 id="company-nav__title">MENU</h3>
              <ul id="company-nav__list" class="list-style-none mb-0">
                <?php foreach ( $nav as $n ) : ?>
                  <li><a href="#<?php echo esc_attr( $n['id'] ); ?>"><?php echo esc_html( $n['text'] ); ?></a></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>
        </div>
        <div class="col-lg-9">
          <?php
          // the_content フィルター適用済み。wp_kses_post は地図の iframe を落とすので通さない
          echo $content; // phpcs:ignore WordPress.Security.EscapingOutput.OutputNotEscaped
          ?>
        </div>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
</main>
<?php get_footer(); ?>
