/* import inertia */
import { Head } from '@inertiajs/react';
/* import layout */
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
/* import stripe */
import {loadStripe} from '@stripe/stripe-js';
import { EmbeddedCheckoutProvider, EmbeddedCheckout } from '@stripe/react-stripe-js';

// Stirpe インスタンス
const stripePromise = loadStripe('pk_test_51Oa5r9H9LY2x1hijsnJsuTP6GckQJkzC1NGGkWwoEHhJjlm6a3j8CU4LtNetb4iNdHs3uNZiiA4udPruTUY3Ahns00jW8POM7V', {stripeAccount: 'acct_1OaandQkI4xFNpEu'});
export default function CheckoutSession({ auth, checkout_session }) {

    const options = {clientSecret: checkout_session.client_secret};
    console.log('さて１。。。１', options);
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">支払い方法登録 by CheckoutSession</h2>}
        >
            <Head title="支払い方法登録 by CheckoutSession" />

            <div id='checkout' className='w-full'>
                <EmbeddedCheckoutProvider stripe={stripePromise} options={options}>
                    <EmbeddedCheckout />
                </EmbeddedCheckoutProvider>
            </div>
        </AuthenticatedLayout>
    )
}
