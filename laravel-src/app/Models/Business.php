<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 事業所 モデル
 */
class Business extends Model
{
    use HasFactory;
    // テーブル名
    protected $table = 'businesses';
    // 値変更 可能項目
    protected $fillable = [
        'name',
        'stripe_account_id',
    ];
}
