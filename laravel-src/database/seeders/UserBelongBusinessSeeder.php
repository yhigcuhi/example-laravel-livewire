<?php

namespace Database\Seeders;

use App\Models\UserBelongBusiness;
use Illuminate\Database\Seeder;

class UserBelongBusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // テストさん テスト事業所 所属
        $list = [
            [
                'id' => 1,
                'user_id' => 1,
                'business_id' => 1,
            ]
        ];
        // ユーザー所属 事業所作成
        UserBelongBusiness::upsert($list, ['id']);
    }
}
