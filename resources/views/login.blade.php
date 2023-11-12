<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登录页面</title>
    <link rel="stylesheet" href="http://127.0.0.1:8000/css/bootstrap.min.css">
    <style>
        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(to bottom right, #667eea, #764ba2);
            font-family: 'Arial', sans-serif;
        }

        .login-container {
            max-width: 400px;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0px 10px 25px 0px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }

        .login-container:hover {
            box-shadow: 0px 15px 30px 0px rgba(0, 0, 0, 0.2);
        }

        .form-group {
            position: relative;
        }

        .form-group label {
            position: absolute;
            top: -10px;
            left: 15px;
            background: #ffffff;
            padding: 0 5px;
            transition: all 0.3s;
        }

        .form-group input:focus {
            border-color: #667eea;
            box-shadow: 0 0 10px rgba(102, 126, 234, 0.5);
        }

        .form-group input:focus + label {
            color: #667eea;
        }

        .btn-primary {
            background-color: #667eea;
            border: none;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: #5a6ed2;
        }

    </style>
</head>

<body>
    <div class="login-container">
        <h2 class="text-center">登录</h2>
        <form action="{{ route('login') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="username">用户名</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">密码</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">登录</button>
        </form>
    </div>

    <!-- 使用 Bootstrap 的 CDN 引入 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
