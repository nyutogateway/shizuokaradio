<?php
/**
 * Template Name: リクエストフォーム
 * Template Post Type: page
 *
 * 静的HTMLの request.html を再現するテンプレート。
 *
 * 本文（エディタ）に Contact Form 7 のショートコードを貼ると、そのフォームを表示する。
 * 貼っていない場合は、項目や見た目を確認できるようにダミーのフォームを描画する
 * （ダミーは送信できない。CF7を入れるまでの確認用）。
 *
 * CF7に貼り付けるフォーム定義は README を参照。
 * 元テーマのCSS（#request-sc1 / .wpcf7-list-item 等）がCF7前提のため、
 * ラッパーの id を静的HTMLと合わせている。
 *
 * @package sor
 */
get_header(); ?>
<main class="pages">
  <?php get_template_part( 'template-parts/page-header' ); ?>
  <div class="pages-body py-md">
    <div class="container">
      <?php
      while ( have_posts() ) :
        the_post();
        $content = get_the_content();
        $has_cf7 = ( false !== strpos( $content, '[contact-form-7' ) );
      ?>

      <?php if ( trim( wp_strip_all_tags( $content ) ) || $has_cf7 ) : ?>
        <div id="request-sc1"><?php the_content(); ?></div>
      <?php endif; ?>

      <?php if ( ! $has_cf7 ) : ?>
        <?php if ( ! trim( wp_strip_all_tags( $content ) ) ) : ?>
          <p class="mb-4">お好きな楽曲のリクエスト・メッセージをお送りください。番組内でご紹介します。</p>
        <?php endif; ?>

        <?php if ( current_user_can( 'edit_pages' ) ) : ?>
          <div class="mb-4 f-14" style="border:1px solid #d30101;padding:12px 16px;border-radius:5px">
            <strong>※編集者にのみ表示</strong>：Contact Form 7 のショートコードが本文にありません。
            以下は見た目確認用のダミーで、<strong>送信できません</strong>。
            CF7を導入し、本文にショートコードを貼ってください（フォーム定義は wp-theme/README.md 参照）。
          </div>
        <?php endif; ?>

        <?php // ---- ダミーフォーム（CF7導入までの確認用） ---- ?>
        <div id="request-sc1">
          <div class="form-group">
            <label class="label">ラジオネーム<span class="label__required">必須</span></label>
            <input type="text" class="form-control mt-3" placeholder="ラジオネーム" disabled>
          </div>
          <div class="form-group">
            <label class="label">曲名<span class="label__required">必須</span></label>
            <input type="text" class="form-control mt-3" placeholder="曲名" disabled>
          </div>
          <div class="form-group">
            <label class="label">アーティスト名<span class="label__required">必須</span></label>
            <input type="text" class="form-control mt-3" placeholder="アーティスト名" disabled>
          </div>
          <div class="form-group">
            <label class="label">番組を選択<span class="label__required">必須</span></label>
            <?php
            // 番組はCPTから取得する（静的HTMLでは番組名が直書きだった）
            $programs = get_posts( array( 'post_type' => 'program', 'numberposts' => 50, 'orderby' => 'title', 'order' => 'ASC' ) );
            ?>
            <select class="form-select mt-3" disabled>
              <?php foreach ( $programs as $p ) : ?>
                <option><?php echo esc_html( $p->post_title ); ?></option>
              <?php endforeach; ?>
              <option>その他</option>
            </select>
            <?php if ( ! $programs && current_user_can( 'edit_posts' ) ) : ?>
              <p class="f-14 mt-2" style="color:#d30101">
                ※編集者にのみ表示：公開済みの「番組」が0件のため、選択肢が「その他」だけになっています。
                管理画面の「番組」で追加し、<strong>下書きではなく公開</strong>してください。
              </p>
            <?php endif; ?>
          </div>
          <div class="form-group">
            <label class="label">メッセージ</label>
            <textarea class="form-control mt-3" rows="6" placeholder="メッセージ・エピソードなど" disabled></textarea>
          </div>
          <div id="request-sc1-privacy" class="py-4">
            <label class="d-flex align-items-center justify-content-center">
              <input type="checkbox" disabled>
              <span class="wpcf7-list-item-label"><a href="<?php echo esc_url( sor_privacy_url() ); ?>">プライバシーポリシー</a>に同意する</span>
            </label>
          </div>
          <div id="request-sc1-btn" class="pt-4 text-center">
            <button class="btn" type="button" disabled>確認画面へ</button>
          </div>
        </div>
      <?php endif; ?>

      <?php endwhile; ?>
    </div>
  </div>
</main>
<?php get_footer(); ?>
