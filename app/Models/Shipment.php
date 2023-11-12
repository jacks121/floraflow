<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $fillable = ['body_number', 'variety_id', 'status_history'];

    public static function createWithStatusHistory($bodyNumber)
    {
        // 找到对应的瓶子
        $bottle = Bottle::where('body_number', $bodyNumber)->first();

        if (!$bottle) {
            throw new \Exception("Bottle with body number {$bodyNumber} not found.");
        }

        // 获取瓶子的所有状态变更记录
        $statusChanges = $bottle->statusChanges()
                                ->orderBy('change_date', 'asc')
                                ->get(['status', 'change_date'])
                                ->toArray();

        // 转换为JSON
        $statusHistoryJson = json_encode($statusChanges);

        // 创建并返回新的Shipment实例
        return self::create([
            'body_number' => $bodyNumber,
            'variety_number' => $bottle->variety_number, // 确保Bottle模型有这个字段
            'status_history' => $statusHistoryJson
        ]);
    }
}
