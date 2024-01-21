/* import inertia */
import { Head } from '@inertiajs/react';
/* import layout */
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
/* import stripe */
import {loadStripe} from '@stripe/stripe-js';
import {Elements } from '@stripe/react-stripe-js';
/* import 部品 */
import SetupIntentForm from './Partials/SetupIntentForm';

// Stirpe インスタンス
const stripePromise = loadStripe('pk_test_51Oa5r9H9LY2x1hijsnJsuTP6GckQJkzC1NGGkWwoEHhJjlm6a3j8CU4LtNetb4iNdHs3uNZiiA4udPruTUY3Ahns00jW8POM7V', {stripeAccount: 'acct_1OaandQkI4xFNpEu'});
export default function SetupIntent({ auth, setup_intent, customer_id }) {
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">支払い方法登録 by SetupIntent</h2>}
        >
            <Head title="支払い方法登録 by SetupIntent" />

            <div id='checkout' className='w-full'>
                <Elements stripe={stripePromise}>
                    <SetupIntentForm setup_intent={setup_intent} customer_id={customer_id}/>
                </Elements>
            </div>
        </AuthenticatedLayout>
    )
}
