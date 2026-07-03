<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parcel extends Model
{
    protected $table = 'parcels';

    // 允许批量赋值的字段（想一想：为什么不能把 id 放进来？） id 是自动生成的，没必要特意赋值
    protected $fillable = [
        'tracking_no', 'recipient_name', 'address', 'weight', 'status',
    ];
}