<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>用户首页</title>
    <link rel="stylesheet" href="http://127.0.0.1:8000/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #d4e6d1;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .dashboard-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        .logo {
            background-image: url('ff.png');
            /* 根据需要替换为相应的图片路径 */
            height: 150px;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            margin-bottom: 20px;
        }

        .info-box {
            margin-bottom: 15px;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        h4 {
            color: #333;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            color: white;
            background-color: #333;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            margin-bottom: 10px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-primary {
            background-color: #e63946;
        }
    </style>
</head>

<div class="dashboard-container">
    <div class="logo"></div>
    <h2 class="text-center">首页</h2>
    <div class="info-box">
        <h4>姓名：{{ auth()->user()->name ?? '' }}</h4>
    </div>
    <div class="info-box">
        <h4>本月分配花苗总数：{{ $totalBottlesThisMonth ?? 0 }}</h4>
    </div>
    <div class="info-box">
        <h4>本月感染率：{{ $infectionRate ?? 0 }}%</h4>
    </div>
    <a href="{{ route('bottle') }}" class="btn btn-primary">开始分配花苗</a>
    <a href="{{ route('status') }}" class="btn">标记状态</a>
</div>

</html>