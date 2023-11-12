<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bottle extends Model
{
    protected $fillable = ['user_id', 'body_number', 'variety_number'];

    public function parentRelationships()
    {
        return $this->hasMany(BottleRelationship::class, 'parent_bottle_id');
    }

    public function childRelationships()
    {
        return $this->hasMany(BottleRelationship::class, 'child_bottle_id');
    }

    public function variety()
    {
        return $this->belongsTo(Variety::class, 'variety_number', 'variety_number');
    }

    public function statusChanges()
    {
        return $this->hasMany(StatusChange::class);
    }

    public function latestStatusChange()
    {
        return $this->hasOne(StatusChange::class)->latest('created_at');
    }

    /**
     * 获取瓶子的最新状态。
     *
     * @return string|null
     */
    public function getLatestStatus()
    {
        $latestStatusChange = $this->statusChanges()->latest('change_date')->first();
        return $latestStatusChange ? $latestStatusChange->status : null;
    }


    public static function createNewBottle($bodyNumber, $varietyNumber, $parentId = null)
    {
        // 创建瓶子
        $bottle = self::create([
            'body_number' => $bodyNumber,
            'variety_number' => $varietyNumber,
            'user_id' => auth()->id() // 假设当前用户ID是创建者
        ]);
    
        // 创建默认状态
        StatusChange::create([
            'bottle_id' => $bottle->id,
            'status' => 'in_stock',
            'change_date' => now(), // 使用当前日期
            'user_name' => auth()->user()->name
        ]);

        if (isset($parentId)) {
            BottleRelationship::create([
                'parent_bottle_id' => $parentId,
                'child_bottle_id' => $bottle->id
            ]);
        }

        return $bottle;
    }
    
}
