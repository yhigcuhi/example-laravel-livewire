<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeder実行
        $this->callOnce([
            UserSeeder::class, // (システムアカウント)ユーザー 作成
            BusinessSeeder::class, // 事業所
            UserBelongBusinessSeeder::class, // ユーザー所属事業所
        ]);
    }
}
