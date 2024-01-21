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

export default function Customers({ auth, customers }) {
    console.log('??', customers)

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">事業所 顧客一覧</h2>}
        >
            <Head title="事業所 顧客一覧" />
            {/* メインコンテンツ */}
            <div className='w-full'>
                {/* 従業員 一覧 */}
                <Card className="w-full" title="顧客 一覧">
                    {/* 一覧表 */}
                    <Table className="my-6 border border-gray-200" header={<THead />} headerClassName='bg-gray-200'>
                        {/* 一覧表: ボディ */}
                        {
                            map(customers.data, ({id, name}) => {
                                // 画面描画
                                return (
                                    // 各行 表示
                                    <tr key={id} className="border-b hover:bg-gray-100">
                                        <TH>{id}</TH>
                                        <TH>{name}</TH>
                                        <TD className='flex justify-end items-center gap-4'>
                                            <AppendButton href={route('business.customers.setup', {customer_id: id})}>支払い方法追加</AppendButton>
                                            <AppendButton href={route('business.customers.payment.methods', {customer_id: id})}>支払い</AppendButton>
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
