<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * ユーザー 所属 事業所
 */
class UserBelongBusiness extends Model
{
    use HasFactory;
    // テーブル名
    protected $table = 'user_belong_businesses';
    // 値変更 可能項目
    protected $fillable = [
        'user_id',
        'business_id',
    ];
}
