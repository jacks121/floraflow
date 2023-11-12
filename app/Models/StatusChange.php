<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusChange extends Model
{
    protected $fillable = ['bottle_id', 'status', 'change_date','user_name'];

    protected $statusTranslations = [
        'in_stock' => '在库',
        'infected' => '感染',
        'planted' => '栽种',
        'destroyed' => '销毁',
        'sold' => '已售'
    ];
    
    public function getStatusAttribute($value)
    {
        return $this->statusTranslations[$value] ?? $value;
    }

    public function bottle()
    {
        return $this->belongsTo(Bottle::class);
    }
    
    public static function updateStatus($bottleId, $newStatus)
    {
        // 找到与给定瓶子 ID 相关联的最新状态变更记录
        $statusChange = self::where('bottle_id', $bottleId)
                             ->latest()
                             ->first();

        if ($statusChange) {
            // 更新状态并保存
            $statusChange->status = $newStatus;
            $statusChange->change_date = now(); // 更新变更日期为当前时间
            $statusChange->save();

            return $statusChange;
        } else {
            // 抛出异常或以其他方式处理未找到记录的情况
            // 这取决于您希望如何处理这种情况
            throw new \Exception("No status change record found for bottle ID {$bottleId}.");
        }
    }
}
