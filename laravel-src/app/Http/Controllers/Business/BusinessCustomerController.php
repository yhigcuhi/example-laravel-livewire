<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Stripe\StripeClient;

class BusinessCustomerController extends Controller
{
    // Stripe バックエンドAPI クライアント
    private readonly StripeClient $stripe;

    // コンストラクタ
    public function __construct()
    {
        // injection
        $this->stripe = new StripeClient(config('stripe.api.secret'));
    }

    public function test()
    {
        return response()->json($this->stripe->checkout->sessions->retrieve('cs_test_c1resMNQGotwJuCFVI33cckmoOfRE7tQSZMlvXQOIC879Vu57TyVtM8PA9', [], ['stripe_account' => 'acct_1OaandQkI4xFNpEu']));
    }
    /**
     * @param int id 事業所
     * @return \Inertia\Response 事業所 顧客一覧画面
     */
    public function index(): \Inertia\Response
    {
        // 顧客一覧
        $customers = $this->stripe->customers->all(['limit' => 10], ['stripe_account' => 'acct_1OaandQkI4xFNpEu']);
        return Inertia::render('Customer/List', compact('customers'));
    }

    /**
     * 方法1 checkout sessions mode=setup 決済登録
     * @return \Inertia\Response 事業所 顧客 支払い方法追加画面
     */
    public function setupByCheckoutSession(string $customer_id): \Inertia\Response
    {
        // 方法1 checkout sessions mode=setup https://stripe.com/docs/payments/save-and-reuse?platform=web&ui=embedded-checkout&client=html
        // 顧客の支払い方法追加 申請
        $checkout_session = $this->stripe->checkout->sessions->create(
            [
                'payment_method_types' => [ 'card' ],
                'mode' => 'setup',
                'ui_mode' => 'embedded',
                'customer' => $customer_id,
                'return_url' => route('business.customers.setup.success', compact('customer_id')).'?session_id={CHECKOUT_SESSION_ID}',
            ],
            ['stripe_account' => 'acct_1OaandQkI4xFNpEu']
        );
        // 画面描画
        return Inertia::render('Customer/CheckoutSession', compact('checkout_session'));
    }
    // Checkout Session return_url
    public function setupSuccessByCheckoutSession(Request $request)
    {
        // クエリパラメーター から ?session_id=cs_test_c1resMNQGotwJuCFVI33cckmoOfRE7tQSZMlvXQOIC879Vu57TyVtM8PA9 を保持
        // 結果から checkout_session → setup_intent → payment_method (決済方法オブジェクト) 取得
        $checkout_session = $this->stripe->checkout->sessions->retrieve($request->get('session_id'), [], ['stripe_account' => 'acct_1OaandQkI4xFNpEu']); // フォーム登録内容取得s
        $setup_intent = $this->stripe->setupIntents->retrieve($checkout_session->setup_intent, [], ['stripe_account' => 'acct_1OaandQkI4xFNpEu']); // setup_intent取得
        $payment_method = $this->stripe->paymentMethods->retrieve($setup_intent->payment_method, [], ['stripe_account' => 'acct_1OaandQkI4xFNpEu']); // 決済方法 取得 （setup_intent->payment_methodを顧客に紐付け保存）
        // カード番号全文は表示できないが、last4 「*** 3184」の最後とれる
        return response()->json($payment_method);
    }

    /**
     * 方法2 setup intent 決済登録
     * @return \Inertia\Response 事業所 顧客 支払い方法追加画面
     */
    public function setup(string $customer_id)
    {
        // 方法2 setup intent https://stripe.com/docs/payments/save-and-reuse-cards-only?locale=ja-JP&client=react
        $setup_intent = $this->stripe->setupIntents->create(
            [
                'customer' => $customer_id,
                'payment_method_types' => ['card'],
                // 'automatic_payment_methods' => [ 'enabled' => true ]
            ],
            ['stripe_account' => 'acct_1OaandQkI4xFNpEu']
        );
        // 画面描画
        // return view('test', compact('setup_intent', 'customer_id'));
        return Inertia::render('Customer/SetupIntent', compact('setup_intent', 'customer_id'));
    }
    // setup intent return_url
    public function setupSuccess(Request $request)
    {
        logger($request);
        return response()->json($request->all());
    }
}
