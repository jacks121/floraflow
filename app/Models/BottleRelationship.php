<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BottleRelationship extends Model
{
    protected $fillable = ['parent_bottle_id', 'child_bottle_id'];

    // 在这里定义其他必要的关联或方法
}
