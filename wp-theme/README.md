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

| テンプレート | 用途 | レイアウト |
|---|---|---|
| **会社概要（左メニュー付き）** | 会社概要・広告料金表など | 左に追従MENU（`col-lg-3`）＋右に本文（`col-lg-9`） |
| **1カラム（幅せまめ・規約/フォーム向け）** | プライバシーポリシー、リクエスト | 本文幅を `col-lg-8` に絞って読みやすく |
| **1カラム（全幅）** | ブロックエディタで自由に組む用 | `container` 全幅 |
| （未選択＝既定） | その他 | `page.php`＝幅せまめと同じ |

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
| パーソナリティー | `personality` | `sor_name_en`（英字名）／`sor_days`（出演曜日・カンマ区切り） |
| 番組 | `program` | `sor_cast`（出演者名） |

番組の放送曜日はタクソノミー `program_day` で管理します。
パーソナリティー詳細の「担当番組」は、番組側の `sor_cast` に氏名が含まれるものを自動で拾います。

カスタムフィールドの入力UIが必要な場合は ACF 等の導入を推奨します。

## 注意点

- **Bootstrapの `.bg-black` は `!important` 付き**で自前指定に勝つため、フッターではこのクラスを使わず `#footer` に直接スタイルを当てています。
- SEOメタ・構造化データは `functions.php` で出力しています。**Yoast / Rank Math を入れる場合は二重出力になる**ため、`sor_seo_meta()` は自動で出力を停止します（他プラグインの場合は手動で無効化してください）。
- 画像の `alt` は、装飾目的のもの（波線・NOW ON AIRアイコン・前後ナビのサムネ）は**意図的に空**にしています。隣に同じ情報のテキストがあり、読み上げが重複するためです。
