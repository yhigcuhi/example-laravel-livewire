<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Stripe\StripeClient;

class BusinessChargeController extends Controller
{
    // Stripe バックエンドAPI クライアント
    private readonly StripeClient $stripe;

    // コンストラクタ
    public function __construct()
    {
        // injection
        $this->stripe = new StripeClient(config('stripe.api.secret'));
    }
    /**
     * @param int id 事業所
     * @return \Inertia\Response 事業所 顧客一覧画面
     */
    public function index(string $customer_id): \Inertia\Response
    {
        // 支払い方法一覧
        $payment_methods = $this->stripe->customers->allPaymentMethods($customer_id, ['limit' => 10], ['stripe_account' => 'acct_1OaandQkI4xFNpEu']);
        return Inertia::render('Customer/PaymentMethods', compact('payment_methods', 'customer_id'));
    }

    // 指定支払い方法で 支払い
    public function charge(string $customer_id, string $payment_method_id, Request $request)
    {
        // TODO:サーバー決済 https://stripe.com/docs/payments/finalize-payments-on-the-server?locale=ja-JP

        /**
         * 方法論 https://stripe.com/docs/payments/payment-intents/migration?charges-cards-migration=paying-saved-cards
         *  1. 注文作成
         *  2. こちらで良いか？を画面で表示
         *  3. OK →　決済
         */
        // 注文作成（指定顧客の支払い方法で）
        $paymentIntent = $this->stripe->paymentIntents->create(
            [
                'amount' => 10000,
                'currency' => 'jpy',
                'customer' => $customer_id,
                'confirm' => true,
                'payment_method' => $payment_method_id,
                'mandate_data' => [
                    'customer_acceptance' => [
                        'type' => 'online',
                        'online' => [
                            'ip_address' => $request->ip(),
                            'user_agent' => $request->header('User-Agent'),
                        ],
                    ]
                ],
                'return_url' => route('business.customers.charge.success', compact('customer_id', 'payment_method_id'))
            ]
            , ['stripe_account' => 'acct_1OaandQkI4xFNpEu']
        );

        // 注文確定(認証なければ) ※ 決済方法が 3Dセキュア認証必要なら画面から実施させる必要がある... → 基本的に画面で利用するAPI
        // if ($paymentIntent->status === 'requires_action') return カード認証 実行画面 (参考：https://stripe.com/docs/payments/paymentintents/lifecycle?locale=ja-JP)
        return response()->json($paymentIntent);
    }

    public function chargeSuccess(Request $request)
    {
        logger($request);
        return response()->json($request->all());
    }
}
