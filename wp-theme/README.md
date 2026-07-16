# WordPressテーマ：SHIZUOKA OCEANS RADIO

静的HTML（リポジトリ直下の `*.html`）をWordPressテーマ化したもの。
**静的HTMLはそのまま残してあり、GitHub Pagesの公開も従来どおり動きます。**

## 導入

```
wp-theme/shizuoka-oceans-radio/  →  wp-content/themes/ へコピー
```

管理画面の「外観 > テーマ」で有効化。

## 初期設定

| 項目 | 場所 |
|---|---|
| トップページの指定 | 設定 > 表示設定 > ホームページ＝固定ページ（`front-page.php` が自動適用） |
| お知らせ一覧のURL | 設定 > 表示設定 > 投稿ページ を `/news/` に |
| メニュー | 外観 > メニュー（ヘッダー／フッター／フッター小 の3箇所。未設定なら既定項目を出力） |
| NOW ON AIR・各種URL | 外観 > カスタマイズ（`sor_onair_*` / `sor_listen_url` / `sor_spotify_url` / `sor_address`） |

## 固定ページのテンプレート

固定ページの編集画面 **右サイドバー > ページ属性 > テンプレート** から選べます。

**どのページでどれを選ぶか**（1ページにつき1つだけ選びます）

| 作るページ | 選ぶテンプレート |
|---|---|
| パーソナリティー一覧 | **パーソナリティー一覧** |
| 番組一覧 | **番組一覧** |
| リクエスト | **リクエストフォーム** |
| 会社概要 | **会社概要（左メニュー付き）** |
| プライバシーポリシー | **1カラム（幅せまめ・規約向け）** |
| その他（自由レイアウト） | **1カラム（全幅）** |

**テンプレートの中身**

| テンプレート | レイアウト |
|---|---|
| **パーソナリティー一覧** | パーソナリティーCPTをカード表示。**ふりがなの五十音順** |
| **番組一覧** | 番組CPTをカード表示 |
| **リクエストフォーム** | 本文を全幅で表示。Contact Form 7 前提で、番組の選択肢はCPTから自動生成 |
| **会社概要（左メニュー付き）** | 左に追従MENU（`col-lg-3`）＋右に本文（`col-lg-9`）。本文のH2からMENUを自動生成 |
| **1カラム（幅せまめ・規約向け）** | 本文幅を `col-lg-8` に絞って読みやすく。規約など長文向け |
| **1カラム（全幅）** | `container` 全幅。ブロックエディタで自由に組む用 |
| （未選択＝既定） | `page.php`＝「1カラム（幅せまめ・規約向け）」と同じ |

### 「リクエストフォーム」テンプレートの使い方

**Contact Form 7 が必要です。** 元テーマのCSSがCF7の出力するclass（`.wpcf7-list-item` / `.wpcf7-spinner`）に
当てて書かれているため、CF7を使うと既存のデザインがそのまま効きます。

未導入の場合、リクエストページは**送信できないダミー表示**になります
（編集権限のある人にだけ、その旨の注意書きが出ます）。

**手順**

1. Contact Form 7 を有効化
2. 「お問い合わせ > 新規追加」で下記のフォーム定義を貼り付けて保存
3. 固定ページ「リクエスト」を作成 → テンプレートに **リクエストフォーム** を選択
4. 本文に発行されたショートコード `[contact-form-7 id="..." title="..."]` を貼る

**フォーム定義（フォームタブに貼る）**

```
<p class="mb-4">お好きな楽曲のリクエスト・メッセージをお送りください。番組内でご紹介します。</p>

<div class="form-group">
  <label class="label">ラジオネーム<span class="label__required">必須</span></label>
  [text* radio-name class:form-control class:mt-3 placeholder "ラジオネーム"]
</div>

<div class="form-group">
  <label class="label">曲名<span class="label__required">必須</span></label>
  [text* song class:form-control class:mt-3 placeholder "曲名"]
</div>

<div class="form-group">
  <label class="label">アーティスト名<span class="label__required">必須</span></label>
  [text* artist class:form-control class:mt-3 placeholder "アーティスト名"]
</div>

<div class="form-group">
  <label class="label">番組を選択<span class="label__required">必須</span></label>
  [select* program class:form-select class:mt-3]
</div>

<div class="form-group">
  <label class="label">メッセージ</label>
  [textarea message class:form-control class:mt-3 rows:6 placeholder "メッセージ・エピソードなど"]
</div>

<div id="request-sc1-privacy" class="py-4">
  [acceptance privacy-agree] <a href="/privacypolicy/">プライバシーポリシー</a>に同意する [/acceptance]
</div>

<div id="request-sc1-btn" class="pt-4 text-center">
  [submit class:btn "送信する"]
</div>
```

**番組の選択肢は書かなくてよい**：`[select* program]` は選択肢を空にしておけば、
`functions.php` の `sor_cf7_program_options()` が**番組CPTから自動で生成**します。
番組が増減してもフォーム定義を編集する必要はありません。

**メール設定（メールタブ）**

CF7の初期値のままだと
`Reply-To 項目に不正なメールボックス構文が見られます` というエラーが出ます。
初期値の**追加ヘッダーに `Reply-To: [your-email]` が入っている**のに、
このフォームには**メールアドレスの項目が無い**ためです。

**「メール」タブは上から順に次の入力欄が並んでいます。**
初期値は問い合わせフォーム用なので、**すべて下記に置き換えてください**。

**1. 送信先**（1行）
```
局のメールアドレス（例: request@example.com）
```

**2. 送信元**（1行）
```
[_site_title] <wordpress@example.com>
```
※ ドメインは実際のサイトのものに合わせてください（違うと迷惑メール判定されやすくなります）

**3. 題名**（1行）
```
[_site_title] リクエスト：[song] / [artist]
```

**4. 追加ヘッダー**（1行）
```
（空にする）
```
※ 初期値の `Reply-To: [your-email]` を**削除**します。これがエラーの原因です。

**5. メッセージ本文**（「追加ヘッダー」のすぐ下にある大きな複数行の入力欄）
初期値を全て消して、以下を貼り付けます。
```
ラジオネーム: [radio-name]
曲名: [song]
アーティスト名: [artist]
番組: [program]

メッセージ:
[message]

--
このメールは [_site_title] ([_site_url]) のリクエストフォームから送信されました。
```

**6. ファイル添付 / HTML形式のメールを使用** … どちらも変更不要（初期のまま）

**リスナーに返信したい場合**は、フォームにメール項目を追加してください。

```
<div class="form-group">
  <label class="label">メールアドレス</label>
  [email your-email class:form-control class:mt-3 placeholder "example@example.com"]
</div>
```

この場合のみ、追加ヘッダーに `Reply-To: [your-email]` を残せます
（任意項目にすると未入力時に同じエラーが出るため、その場合も追加ヘッダーは空が安全です）。

**注意**：静的HTMLのボタンは「確認画面へ」ですが、**CF7に確認画面はありません**（送信のみ）。
確認画面が必要な場合は、別プラグイン（Contact Form 7 Multi-Step Forms 等）の導入か、
フォームプラグイン自体の変更をご検討ください。

### 「会社概要」テンプレートの使い方

本文に **見出し2（H2）** でセクションを作るだけで、**左のMENUが自動生成**されます
（H2に自動でidが振られ、アンカーリンクになる）。

```
H2: 会社概要      → MENUに「会社概要」
（表など本文）
H2: 広告料金表    → MENUに「広告料金表」
（表など本文）
```

会社情報の表は、静的HTML（`company.html`）と同じく `<table class="comp-table w-100">` を使うと
既存のスタイルがそのまま当たります。

## 構成

```
style.css              テーマ情報（実スタイルは assets/css/custom.css）
functions.php          アセット読込 / CPT / SEO / 構造化データ
header.php footer.php  全ページ共通（静的HTMLでは10ファイルに重複していた部分）
front-page.php         トップ（ヒーロー / NEWS / PROGRAM / PERSONALITY）
index.php archive.php home.php  一覧
single.php             お知らせ記事
single-personality.php パーソナリティー詳細
page.php               固定ページ（既定）
page-templates/        固定ページ用テンプレート（管理画面で選択可）
template-parts/        ページヘッダー・NOW ON AIRバー・各カード・前後ナビ
assets/                CSS / 画像 / JS
```

## カスタム投稿タイプ

| 種別 | slug | 使うカスタムフィールド |
|---|---|---|
| パーソナリティー | `personality` | `sor_name_kana`（ふりがな）／`sor_name_en`（英字名）／`sor_days`（出演曜日・カンマ区切り） |
| 番組 | `program` | `sor_cast`（出演者・チェックで選択）／`sor_time`（放送時間） |

番組の放送曜日はタクソノミー `program_day` で管理します。
パーソナリティー詳細の「担当番組」は、番組側の `sor_cast` に氏名が含まれるものを自動で拾います。

**ACFなどのプラグインは不要です。** パーソナリティー・番組とも、
編集画面に専用の入力欄を用意しています。

| 投稿タイプ | 編集画面の入力欄 |
|---|---|
| パーソナリティー | ふりがな／英字名／出演曜日（テキスト） |
| 番組 | 出演者（**パーソナリティーから選ぶチェックボックス**）／放送時間（テキスト） |

**出演者をチェックボックスにしている理由**：自由入力だと氏名の誤字・表記ゆれで、
パーソナリティー詳細の「担当番組」が拾えなくなるためです（氏名のLIKE一致で照合しているため）。
チェックで選べば必ず一致します。

**ふりがな**は氏名の上に小さく表示され、**一覧の並び順（五十音順）**にも使われます。
未入力でも一覧から消えることはありませんが、並び順が先頭に寄るため入力を推奨します。

### 一覧ページについて

パーソナリティーと番組はCPTのアーカイブを無効にしている（`has_archive => false`）ため、
**一覧は固定ページ＋専用テンプレートで表示します**。
固定ページ「パーソナリティー」「番組一覧」を作り、それぞれ対応するテンプレートを選んでください。
スラッグは `personality` / `program` にすると、フッター等のリンクと一致します。

## 注意点

- **Bootstrapの `.bg-black` は `!important` 付き**で自前指定に勝つため、フッターではこのクラスを使わず `#footer` に直接スタイルを当てています。
- SEOメタ・構造化データは `functions.php` で出力しています。**Yoast / Rank Math を入れる場合は二重出力になる**ため、`sor_seo_meta()` は自動で出力を停止します（他プラグインの場合は手動で無効化してください）。
- 画像の `alt` は、装飾目的のもの（波線・NOW ON AIRアイコン・前後ナビのサムネ）は**意図的に空**にしています。隣に同じ情報のテキストがあり、読み上げが重複するためです。
