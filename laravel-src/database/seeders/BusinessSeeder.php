<?php

namespace Database\Seeders;

use App\Models\Business;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // テスト事業所
        $list = [
            [
                'id' => 1,
                'name' => 'TEST事業所'
            ]
        ];
        // 事業所作成
        Business::upsert($list, ['id']);
    }
}
