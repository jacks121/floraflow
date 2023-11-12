<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CultivationBottlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $numOfRecords = 100;

        $existingCodes = [];

        for ($i = 0; $i < $numOfRecords; $i++) {
            $code = str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);  // 生成8位随机数字

            // 检查该code是否已存在于$existingCodes数组中
            while (in_array($code, $existingCodes)) {
                $code = str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);  // 如果已存在，则重新生成
            }

            // 保存新生成的code到数组中
            $existingCodes[] = $code;

            DB::table('cultivation_bottles')->insert([
                'variety_id' => rand(11111, 99999),  // 随机品种 ID, 根据你的数据修改
                'parent_id' => 2114653,  // 没有指定具体的逻辑, 所以默认为 null
                'code' => $code,  // 使用生成的唯一code
                'user_id' => 2,  // 假设有 10 个用户, 根据你的数据修改
                'remarks' => 'Remark ' . $i,  // 示例备注
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

    }
}
