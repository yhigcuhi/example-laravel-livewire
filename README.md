# example-laravel-livewire
Laravel Livewire の実験
## 環境
|項目|バージョン|
|:---|:---:|
|php|8.1|
|laravel|10|
|nginx|とりあえず最新（開発用なので）|
|mysql|できたら Mariadb 10.4|

## 環境構築時の手順 (プロジェクトを新規作成する時の手順)
1. Docker 用意
1. コンテナ起動
1. appコンテナ起動
1. composer create-project laravel/laravel .
1. curl https://www.toptal.com/developers/gitignore/api/vim,react,node,linux,macos,laravel,windows,composer,intellij,sublimetext,visualstudio,visualstudiocode >> .gitignore

## Stripeお試し
### Stripe Connectedアカウント登録(account_onboarding) 実験
ゴール account_onboardingでのアカウント作成から 必要な入力事項整理
結論 ... Create an Account API では入金口座登録できない(Standardでは)ので Stripe OAuthにて 会社・口座登録させるが一番早いかと
#### Create an Account API
入金口座は登録できない →　別途必要

|階層|項目|値|
|:---|:---|:---|
|1|type|standard|
|1|business_profile|下記Object|
|2|name||
|1|business_type|company|
|1|country|JP|
|1|default_currency|jpy|
|1|email||
|1|settings|下記Object|
|2|card_payments|下記Object|
|3|statement_descriptor_prefix|クレカ明細 ローマ字（事業署名省略とか）|
|3|statement_descriptor_prefix_kana|クレカ明細 カナ（事業署名省略とか）|
|3|statement_descriptor_prefix_kanji|クレカ明細 カナ（事業署名省略とか）|
|2|payments|下記Object|
|3|statement_descriptor|クレカ明細 ローマ字（事業署名省略なし）|
|3|statement_descriptor_kana|クレカ明細 カナ（事業署名省略なし）|
|3|statement_descriptor_kanji|クレカ明細 カナ（事業署名省略なし）|

下記の設定 できるのは custom タイプだけだった...  
**※ Standard としては強制的に Weekly Friday delay_days 4日**
|階層|項目|値|
|:---|:---|:---|
|1|settings|下記Object|
|2|payouts|下記Object|
|3|debit_negative_balances|true<br>[Stripe 残高の仕組み活用 公式リファレンス](https://stripe.com/docs/connect/account-balances)|
|3|schedule|下記Object|
|4|delay_days|お客様 クレカ →　Stripe残高（ここに溜めておく日数） →　会社口座 入金確定<br>遅延させる理由は キャンセル時に 返金額として充てるよう|
|4|interval|入金タイミング：日次(daily: デフォルト)・週次(weekly)・月次(monthly)・手動(manual)|
|4|weekly_anchor|入金タイミング 週次の時の曜日（mondayなどの文字）|
|4|monthly_anchor|入金タイミング 日付(25など)|


#### account_onboarding 実験
```php
// Stripe アカウント 登録申請
$account = $this->stripe->accounts->create(['type' => 'standard']);
// 連結アカウントとして 登録申請
// ※　https://dashboard.stripe.com/settings/connect での設定が事前に必要
$account_link = $this->stripe->accountLinks->create([
    'account' => $account->id, // 登録申請したアカウントID (紐付け先)
    'refresh_url' => route('dashboard'), // Stripe アカウント 登録 無効時のリダイレクト先 (有効期限切れ など) TODO:事業所一覧とか
    'return_url' => route('business.registered'), // Stripe アカウント 登録 成功時 リダイレクト先
    'type' => 'account_onboarding', // Stripe提供のアカウント登録フォーム表示
]);

return redirect($account_link->url);
```

入力事項...

|項目|値|任意|
|:---|:---|:---|
|email|stripe ログインアカウントメアド||
|password|stripe ログイン PW||
|SMS 電話番号 2段階認証|stripe アカウント登録として → 2段階認証させられる（SMS 出なくても 他アプリでも代用可能）||
|所在値|日本||
|事業形態|個人・法人||
|法人名|漢字・カナ・アルファベット||
|法人住所|建物まで|番地以降は任意|
|法人番号|||
|代表電話番号|||
|業種|選択必須||
|事業所WEBサイト|||
|商品、サービス内容の詳細|1 ～ 2 行でご説明ください。通常顧客に請求するタイミング (購入中、または 3 日後など) を必ずご記入ください。この情報は、Stripe がお客様のビジネスに対する理解を深めるのに役立ちます。||
|アカウント作成者の名義など情報|代表でも良さそう||
|取締役入力|Stripeとして必須||
|改正割販法に関連する質問|下記表||
|入金口座の情報：口座名義 (カタカナ)|テストアカウントあり：Jenny Rosen||
|入金口座の情報：金融機関|||
|入金口座の情報：支店|||
|入金口座の情報：口座番号|||
|顧客向けの表示情報：店舗名・サービス名|漢字・カナ・ローマ字||
|顧客向けの表示情報：短い表記|領収書向け 漢字・カナ・ローマ字||
|顧客向けの表示情報：お客様問合せ電話番号|||
|気候変動への取り組みを顧客に示す|不要<br>0.5, 1, 1.5%寄付する可(一定金額から寄付するも可)||


改正割販法に関連する質問
|項目|値|任意|
|:---|:---|:---|
|今後、顧客のカード番号を取り扱う予定はありますか？|いいえ||
|今後、お客様の事業で、または他事業の代理として決済を生成する接続アプリケーション (Connect Standard) で、顧客の身元を特定することを目的として、何らかの種類の付加的な検証手段を利用する予定はありますか？|はい||
|今後、お客様の事業で、または他事業の代理として決済を生成する接続アプリケーション (Connect Standard) で、決済ごとにカードのセキュリティコードを要求する予定はありますか？|はい||
|今後、お客様の事業で、または他事業の代理として決済を生成する接続アプリケーション (Connect Standard) で、疑わしい配送先住所を検出するための対策を講じる予定はありますか？|いいえ||
|過去５年間に特定商取引法違反もしくは過去に消費者契約法違反による敗訴判決を受けたことがありますか？|いいえ||

### 支払い方法登録 Elements + Charge　決済
#### 支払い方法 Checkout Sessionの場合...
※ フォームとして 欲しくないやつできる
- メアド必須
- 次回以降の購入をワンクリックにする が聞かれる
- 3Dセキュアは フォーム内で完結できる
