## 開始日
+ 10/10

## 1. 機能

### 実装完了機能・品質(10/21更新)

#### フロント(HTML、twig、CSS(SCSS?Bootstrap?)、JS)
  1. グローバルナビゲーションを統一(ヘッダー・フッターも統一予定)
      + 表示がログイン前後で変わる仕様

#### バック(PHP、JS)
  1. トップページ(商品表示機能、ログアウト時でも閲覧可)
    + 商品一覧表示(must)
    + カテゴリー別表示(must)
  
  2. 商品詳細説明(ログアウト時でも閲覧可)
    + ログインしてない時にはカートに入れるボタン非表示。
  
  3. ECカート機能
    + 商品の追加、削除機能(must)
    + ログインセッションを用いてユーザー毎にカートの中身を管理出来る。(10/21new)

  4. サイト内商品検索機能(10/20new)
    + 検索ワードの文字が含まれる商品のみを表示可能。
    + ヒット0件の時のエラーメッセージ表示。
  
  5. メンバーリスト機能・メンバー詳細機能(管理者ユーザのみの表示にする?)
    + 登録されたメンバーリストと詳細を見れる。
    + パスワードは*で伏せて表示。編集ボタン無し。

  6. マイページ機能
    + ログインしている自分のプロフィールを表示。編集ボタン付き。

  7. ログイン機能(メールアドレス・パスワード)
    + ログイン条件
      + DBにアクセスし、メールアドレスとハッシュ化されたパスワードとの照合が出来る。
      + SELECTしたメンバーのの'delete_flg'が立っていない。
    + 空入力のエラーメッセージ表示。
    + 認証失敗時のエラーメッセージ表示。(メールアドレス or パスワードが間違っている)
    + 認証成功後の商品リストページ遷移。
    + SESSION機能。$_SESSION['login_flg']の中身でログイン済みか判別。

  8. ログアウト機能
    + logout.phpアクセス時にセッションクラスのメソッドを呼び出し、session_destroy()でSESSIONを全解除。
    + ログアウト後に商品リストページ遷移。

  9. メンバー登録機能
    + 入力エラーメッセージの表示(バリデーション)
      + メールアドレスチェック(重複禁止チェック、書式チェック)
      + パスワードチェック(条件：半角英大文字小文字数字を組み合わせた8文字以上)
      + その他、空入力チェック(must)
    + 確認ページ(must)
    + パスワードハッシュ化。
  
  10. プロフィールの編集/更新機能(10/18 new)
    + マイページからのみアクセス可能。(パスワード認証を有とするか??)
    + 入力エラーメッセージの表示(バリデーション)
      + メールアドレスチェック("現在の自分のアドレス以外"の重複禁止チェック、書式チェック)
      + パスワードチェック(条件：半角英大文字小文字数字を組み合わせた8文字以上)
      + その他、空入力チェック(must)
    + 確認ページ
    + パスワードハッシュ化。
  
  11. メンバー退会機能
    + 確認画面の表示
    + 現時点ではdelete_flgを1にし、emailの値を空にする事で論理的に削除し、ログイン出来ない状態とする(データベースには残す)
    + 実際にデータごと消去するか要検討。

  12. いいね(お気に入り)登録機能
    + DBへの登録
    + 非同期通信(Ajax)の使用

  13. お問合せ機能
    + 確認ページ
    + DBへの登録
    + 投稿主へのメール送信。
    + サイト管理者へのメール送信。

#### データベース
  + データベース操作はPDOクラスを使用。DMLの関数は各々のクラスファイルから呼び出し。
  + SQLログ取得
    + 日付毎にファイルを分ける形式で取得。
  

### 未実装機能一覧(✅を10月中まで目標)

⭐️必須：いいね、お気に入り登録/解除(もしくは、ブックマーク登録/解除、欲しいモノリストなど)

✅必須：ECカート機能(個数変更、確認ページ、リロードしても個数が増えないように)もしくは、予約機能、購入機能

✅必須：購入履歴表示

✅必須：掲示板(口コミ機能。スレッド新規作成：件名変更不可/内容のみ編集可、コメント投稿(投稿者のみ編集/削除)

✅必須：JavaScript(jQuery)を使い、画像スライダーやモーダルウインドウ表示、ライブプレビュー機能を実装

尚可：サイトの見た目(ヘッダー・フッター統一、CSSデザイン、レスポンシブ対応)

尚可：商品登録(カテゴリー分け/画像投稿)／編集(修正)／削除機能、注文(購入)管理

自案: プロフィール写真登録・表示(マイページのリンクに使用)...image_submitフォルダを参照する

自案: 特定のページ遷移前のパスワード認証(パスワード変更等、退会画面、購入画面等)

尚可：サイト内検索(ページURLの改善、AND検索/OR検索の切り替え、半角スペースで区切ることで複数の検索文字列を検索)


尚可：サイト管理者用ログイン(セッションのlogin_flg or sqlやPHPの権限設定)

自案: メール自動送信機能(新規登録、購入完了)

自案: カード決済(登録)機能 


## 2. 品質

### 品質クリア(10/16更新)

+ PHPとHTMLが(完全に)分離されている
+ 機能毎にソースが分割されている(最低限MVCモデル)
+ クラス(もしくはテンプレート)を1つ以上、自作して使っている
+ データベース操作はPDOクラスを使う
+ namespace(名前空間)が活用されている
+ コントローラのファイルはまとめてフォルダ化。
+ バリデーション処理を細かく
+ データベース(MySQL)を使い、リレーションが活用されている
+ PDOクラスのラッパークラスを自作する
+ コントローラー部分をなるべく簡素化/抽象化(コントローラーが長くならない)。具体的な処理はすべてモデルに書く。

### 未解決

必須：DBへの商品点数や会員登録人数は50点(人)以上


## 3. 可読性・保守性

必須：ソースのインデントが整っている
必須：スペースを適切に空ける
必須：ソースに適切なコメント(説明)を残している
必須：1つの処理が長すぎない(目安：30～40行以内)
必須：インデントが深すぎない(目安：3段以内)