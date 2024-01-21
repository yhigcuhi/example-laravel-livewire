/* import inertia */
import { Head } from '@inertiajs/react';
/* import layout */
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
/* import 部品 */
import { Card, Table, TH, TD, AppendButton } from '@/Components';
/* import lodash */
import { map } from 'lodash'

// 表ヘッダー
const THead = ({className = ''}) => (
    <tr className={className}>
        <TH className='w-2/12'>コードとか?</TH>
        <TH className='w-6/12'>名前 (部署とかTODO)</TH>
        <TH className='w-4/12'>
            <div className='flex justify-end'>
                <AppendButton href={route('business.customers.create')}>追加</AppendButton>
            </div>
        </TH>
    </tr>
)

export default function PaymentMethods({ auth, customer_id, payment_methods }) {
    console.log('??', payment_methods)

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">事業所 顧客 支払い方法一覧</h2>}
        >
            <Head title="事業所 顧客 支払い方法一覧" />
            {/* メインコンテンツ */}
            <div className='w-full'>
                {/* 従業員 一覧 */}
                <Card className="w-full" title="顧客 一覧">
                    {/* 一覧表 */}
                    <Table className="my-6 border border-gray-200" header={<THead />} headerClassName='bg-gray-200'>
                        {/* 一覧表: ボディ */}
                        {
                            map(payment_methods.data, ({id, card: {brand, exp_month, exp_year, last4}}) => {
                                // 画面描画
                                return (
                                    // 各行 表示
                                    <tr key={id} className="border-b hover:bg-gray-100">
                                        <TH>{id}</TH>
                                        <TH>{`${brand} ${exp_month}/${exp_year} **** ${last4}`}</TH>
                                        <TD className='flex justify-end items-center gap-4'>
                                            <AppendButton href={route('business.customers.charge', {customer_id, payment_method_id: id})}>これで支払う</AppendButton>
                                        </TD>
                                    </tr>
                                )
                            })
                        }
                    </Table>
                </Card>
            </div>
        </AuthenticatedLayout>
    );
}
