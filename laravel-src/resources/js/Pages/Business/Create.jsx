/* import inertia */
import { Head } from '@inertiajs/react';
/* import layout */
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
/* import 部品 */
import { Card } from '@/Components';
import { BusinessForm } from './Partials';

export default function Dashboard({ auth, account_link }) {
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">事業所 登録</h2>}
        >
            <Head title="事業所 登録" />
            {/* メインコンテンツ */}
            <Card className="w-full" title="基本情報 編集">
                AAA
                {/* <BusinessForm /> */}
            </Card>
        </AuthenticatedLayout>
    );
}
