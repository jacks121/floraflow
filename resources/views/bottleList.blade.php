<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="http://127.0.0.1:8000/css/bootstrap.min.css">
    <title>我的列表</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            font-size: 16px;
        }

        .container {
            padding: 20px;
        }

        .title {
            font-size: 24px;
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            background-color: #fff;
            border-collapse: collapse;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            text-align: left;
            padding: 12px 15px;
            font-size: 16px;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f5f5f5;
        }

        tr:hover {
            background-color: #f0dbdb;
        }

        .back-btn {
            background-color: #003366;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            margin-bottom: 20px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .back-btn:hover {
            background-color: #002244;
        }

        @media (max-width: 480px) {
            table, th, td {
                font-size: 14px;
            }

            .container {
                padding: 15px;
            }

            .title {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- 添加返回按钮 -->
        
        <div class="title">我的列表</div>
        <table>
            <thead>
                <tr>
                    <th>编号</th>
                    <th>品种ID</th>
                    <th>编号</th>
                    <th>状态</th>
                </tr>
            </thead>
            <tbody>
                <!-- 使用Blade模板遍历每一行数据 -->
                @foreach ($bottles as $bottle)
                    <tr>
                        <td>{{ $bottle->id }}</td>
                        <td>{{ $bottle->variety->name }}</td>
                        <td>{{ $bottle->body_number }}</td>
                        <td>{{ $bottle->latestStatusChange->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination-container">
            {{ $bottles->links('pagination::simple-tailwind') }}
        </div>
        <div class="btn-container text-right" style="margin-top: 20px;">
            <button class="back-btn" onclick="window.history.back();">返回</button>
        </div>
    </div>
</body>
</html>
