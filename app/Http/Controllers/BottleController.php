<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bottle;
use App\Models\BottleRelationship;
use Exception;

class BottleController extends Controller
{
    const BODY_NUMBER_LENGTH = 5; // 假设体编号的长度

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $userId = auth()->id();
        $bottles = Bottle::with(['variety', 'latestStatusChange'])
        ->where('user_id', $userId)
        ->paginate(10);

        return view('bottleList', ['bottles' => $bottles]);
    }

    public function show() {
        return view('bottle');
    }

    public function saveData(Request $request)
    {
        $successCodes = [];
        $failureCodes = [];
        $type = $request->input('type');
     
        try {
            $originalVases = $request->input('originalVases');
            $newVases = $request->input('newVases');

            // 检查新瓶子是否已存在
            foreach ($newVases as $code) {
                $bodyNumber = substr($code, self::BODY_NUMBER_LENGTH);
                if (Bottle::where('body_number', $bodyNumber)->exists()) {
                    $failureCodes[] = $code;
                }
            }

            // 如果有失败的瓶子，直接返回响应
            if (count($failureCodes) > 0) {
                return $this->buildResponse($successCodes, $failureCodes, "以下瓶子已经存在，请删除后再提交。" . implode(',',$failureCodes));
            }
          
            $parentIds = $this->getParentIds($originalVases);

            if ($type === 'O') {
                foreach ($newVases as $code) {
                    $this->createBottleWithRelation($code, [$parentIds[0]], $successCodes, $failureCodes);
                }
            } elseif ($type === 'N') {
                $this->createBottleWithRelation($newVases[0], $parentIds, $successCodes, $failureCodes);
            } else {
                throw new Exception("Invalid type provided.");
            }

            return $this->buildResponse($successCodes, $failureCodes);
        } catch (Exception $e) {
            return $this->buildResponse($successCodes, $failureCodes, $e->getMessage());
        }
    }

    private function getParentIds($vaseCodes)
    {
        // 提取 body_number 和 variety_number
        $bodyNumbers = [];
        $varietyNumbers = [];
    
        foreach ($vaseCodes as $code) {
            $bodyNumbers[] = substr($code, self::BODY_NUMBER_LENGTH);
            $varietyNumbers[] = substr($code, 0, self::BODY_NUMBER_LENGTH);
        }
    
        // 查询现有的瓶子
        $existingBottles = Bottle::whereIn('body_number', $bodyNumbers)->get()->keyBy('body_number');
    
        foreach ($bodyNumbers as $index => $bodyNumber) {
            // 如果瓶子不存在，则创建新瓶子
            if (!isset($existingBottles[$bodyNumber])) {
                $newBottle = Bottle::createNewBottle($bodyNumber,$varietyNumbers[$index],0);
                $existingBottles[$bodyNumber] = $newBottle;
            }
        }
    
        // 返回所有相关瓶子的 ID
        return $existingBottles->pluck('id')->toArray();
    }
    


    private function buildResponse($successCodes, $failureCodes, $errorMessage = '')
    {
        return response()->json([
            'message' => $errorMessage ?: 'Data processing completed!',
            'successCodes' => $successCodes,
            'failureCodes' => $failureCodes
        ], $errorMessage ? 500 : 200);
    }

    private function createBottleWithRelation($bottleCode, $parentIds, &$successCodes, &$failureCodes)
    {
        $bodyNumber = substr($bottleCode, self::BODY_NUMBER_LENGTH);
        $varietyNumber = substr($bottleCode, 0, self::BODY_NUMBER_LENGTH);

        $bottle = Bottle::createNewBottle($bodyNumber, $varietyNumber);
        if ($parentIds) {
            foreach ($parentIds as $parentId) {
                if (isset($parentId)) {
                    BottleRelationship::create([
                        'parent_bottle_id' => $parentId,
                        'child_bottle_id' => $bottle->id
                    ]);
                }
            }
        $successCodes[] = $bottleCode;
        }
    }

}
