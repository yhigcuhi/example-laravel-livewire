import { useState } from 'react';
/* import stripe */
import {useStripe, useElements, CardElement} from '@stripe/react-stripe-js';

// デザイン カスタマイズ可
const CARD_ELEMENT_OPTIONS = {
    style: {
      base: {
        color: "#32325d",
        fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
        fontSmoothing: "antialiased",
        fontSize: "16px",
        "::placeholder": {
          color: "#aab7c4",
        },
      },
      invalid: {
        color: "#fa755a",
        iconColor: "#fa755a",
      },
    },
    // 郵便番号 入力不要
    hidePostalCode: true
  };

// stripe setup intent form
export default function SetupIntentForm({ setup_intent, customer_id }) {
    const stripe = useStripe();
    const elements = useElements();
    const [error, setError] = useState();

    const handleSubmit = async (event) => {
        console.log('サブミット...')
        // We don't want to let default form submission happen here,
        // which would refresh the page.
        event.preventDefault();

        if (!stripe || !elements) {
          // Stripe.js hasn't yet loaded.
          // Make sure to disable form submission until Stripe.js has loaded.
          return;
        }

        // 支払い方法登録 実行： フロントエンド → Stripe SetupIntent Confirm API
        const result = await stripe.confirmCardSetup(setup_intent.client_secret, {
            // 支払い方法登録 通信内容
            payment_method: {
                // 支払い方法 カード フォームの内容
                card: elements.getElement(CardElement),
                // client_secret作成時に 顧客指定済み
            },
            // 完了画面
            return_url: route('business.customers.setup.success', {customer_id})
        }, { handleActions: false });
        // TODO:あれ成功時 return_urlに飛ばない? result.setupIntentのobjectが帰ってくる →　自分で飛ばすしかない。。。
        console.log('結果...', result)
        if (result.error) {
            console.log('エラー', result.error)
            setError(result.error)
          // Display result.error.message in your UI.
        } else {
          // The setup has succeeded. Display a success message and send
          // result.setupIntent.payment_method to your server to save the
          // card to a Customer
        }
    };
    return (
        <form onSubmit={handleSubmit}>
            <label>
                Card details
                <CardElement options={CARD_ELEMENT_OPTIONS} />
            </label>
            <button type="submit" disabled={!stripe}>Save Card</button>
            <div className={!error ? 'hidden' : ''}>{error}</div>
        </form>
    )
}
