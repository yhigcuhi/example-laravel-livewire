<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_belong_businesses', function (Blueprint $table) {
            // PK
            $table->id();
            // フィールド
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // (誰の)ユーザーID FK
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete(); // (所属先)事業所ID FK
            // 登録更新日時
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            // テーブル論理名
            $table->comment('ユーザー 所属 事業所');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_belong_businesses');
    }
};
