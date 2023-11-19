<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Page</title>
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
    .login-container {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        width: 300px;
    }
    .logo {
        background-image: url('ff.png'); /* Replace with your image path */
        height: 150px;
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
    }
    .form-group {
        margin-bottom: 15px;
    }
    label {
        display: block;
        margin-bottom: 5px;
    }
    input[type="text"], input[type="password"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
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
    }
    .btn-primary {
        background-color: #e63946;
    }
    .forgot-password {
        text-align: center;
        display: block;
        margin-top: 15px;
        color: #333;
        text-decoration: none;
    }
</style>
</head>
<body>

<div class="login-container">
    <form action="{{ route('login') }}" method="post">
        <div class="logo"></div>
        @csrf
        <div class="form-group">
            <label for="username">用户名</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" style="width: 90%">
        </div>
        <div class="form-group">
            <label for="password">密码</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" style="width: 90%">
        </div>
        <button type="submit" class="btn btn-primary">登 录</button>
    </form>
</div>

</body>
</html>
