<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Stripe\StripeClient;

class SetupController extends Controller
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
     * Stripe 支払い方法登録
     */
    public function create()
    {
        // Stripe 連結アカウント ID
        $stripe_account = 'acct_1OaandQkI4xFNpEu';
        // Stripe 顧客ID


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
        // 事業所登録画面 TODO:Stripeの画面飛ぶんじゃね？
        // return Inertia::render('Business/Create', compact('account_link'));
    }

    /**
     * Stripe 登録完了
     */
    public function registered(Request $request)
    {
        // 通信監視
        logger($request);
        // 事業所登録完了画面
        return Inertia::render('Dashboard');
    }

}