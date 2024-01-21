import GuestLayout from '@/Layouts/GuestLayout';
import { Head } from '@inertiajs/react';
// Stripe
import {Elements} from '@stripe/react-stripe-js';
import {loadStripe} from '@stripe/stripe-js';
import {PaymentElement} from '@stripe/react-stripe-js';

// 使う環境
const stripePromise = loadStripe('pk_test_51Oa5r9H9LY2x1hijsnJsuTP6GckQJkzC1NGGkWwoEHhJjlm6a3j8CU4LtNetb4iNdHs3uNZiiA4udPruTUY3Ahns00jW8POM7V');

// Checkoutフォーム
const CheckoutForm = () => {
  return (
    <form>
      <PaymentElement />
      <button>Submit</button>
    </form>
  );
};

export default function Show() {
    const options = {
        // yhiguuXXXで作ったやつのデモ環境 公開鍵
        clientSecret: 'pk_test_51Oa5r9H9LY2x1hijsnJsuTP6GckQJkzC1NGGkWwoEHhJjlm6a3j8CU4LtNetb4iNdHs3uNZiiA4udPruTUY3Ahns00jW8POM7V',
      };


    return (
        <GuestLayout>
            <Head title="Stripe 表示" />

            <Elements stripe={stripePromise} options={options}>
              <CheckoutForm />
            </Elements>
        </GuestLayout>
    );
}
