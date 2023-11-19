<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>用户首页</title>
    <link rel="stylesheet" href="http://127.0.0.1:8000/css/bootstrap.min.css">
    <style>
        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #d4e6d1;
            font-family: 'Arial', sans-serif;
        }

        .dashboard-container {
            max-width: 500px;
            padding: 30px;
            border-radius: 15px;
        }

        .info-box {
            padding: 20px;
            border: 1px solid #eaeaea;
            margin-bottom: 20px;
            text-align: center;
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <h2 class="text-center mb-5">首页</h2>
        <div class="info-box">
            <h4>姓名：{{auth()->user()->name ?? ''}}</h4>
        </div>
        <div class="info-box">
            <h4>本月分配花苗总数：{{ $totalBottlesThisMonth ?? 0 }}</h4>
        </div>
        <div class="info-box">
            <h4>本月感染率：{{ $infectionRate ?? 0}}%</h4>
        </div>
        <a href="{{ route('bottle') }}" class="btn btn-primary btn-block">开始分配花苗</a>
        <a href="{{ route('status') }}" class="btn btn-primary btn-block">标记状态</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
