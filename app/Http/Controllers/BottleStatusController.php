<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bottle;
use App\Models\StatusChange;

class BottleStatusController extends Controller
{
    const BODY_NUMBER_LENGTH = 5;

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('status');
    }


    public function updateStatus(Request $request)
    {
        $codes = $request->input('vases', []);
        $userName = auth()->user()->name;
        $status = $request->input('status');
        foreach ($codes as $code) {
            $bodyNumber = substr($code, self::BODY_NUMBER_LENGTH); 
            $bottle = Bottle::where('body_number', $bodyNumber)->first();

            if ($bottle) {
                // 更新瓶子状态为 'infected'
                StatusChange::create([
                    'bottle_id' => $bottle->id,
                    'status' => $status,
                    'change_date' => now(),
                    'user_name' => $userName // 记录操作用户
                ]);
            }
        }

        return response()->json(['message' => '瓶子状态更新完成']);
    }
}
