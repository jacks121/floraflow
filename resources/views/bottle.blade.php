<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>花苗分割操作</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>

    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Arial', sans-serif;
        }

        h1 {
            color: #003366;
            font-size: 1.5rem;
        }

        h5 {
            margin-top: 0.5rem;
        }

        .btn-primary,
        .btn-success,
        .btn-danger {
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #003366;
        }

        .btn-success {
            background-color: #006633;
        }

        .delete-btn {
            border: none;
            text-decoration: underline;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 14px;
            position: absolute;
            right: -33px;
            /* 设置为负值，使按钮开始时被隐藏 */
            top: 50%;
            transform: translateY(30%);
            /* 垂直居中 */
            width: 60px;
            /* 按钮的宽度 */
            height: 30px;
            /* 按钮的高度 */
        }

        .table {
            width: 100%;
            overflow: hidden;
        }

        .slide-row {
            position: relative;
            overflow: hidden;
        }

        .slide-row>td {
            position: relative;
        }

        .slide-row>td.delete-container {
            width: 0;
            padding: 0;
            border: none;
            overflow: visible;
        }

        .delete-container {
            width: 80px;
            position: absolute;
            top: 0;
            right: -30px;
            bottom: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: transparent;
        }

        #preview {
            position: relative;
            width: 100vw;
            height: 360px;
            overflow: hidden;
        }

        .scanning-area {
            position: absolute;
            top: 10%;
            left: 10%;
            right: 10%;
            bottom: 10%;
            border: 2px dashed red;
        }

        .overlay {
            background-color: rgba(0, 0, 0, 0.5);
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
        }

        .overlay .cutout {
            position: absolute;
            top: 10%;
            left: 10%;
            right: 10%;
            bottom: 10%;
            background-color: transparent;
        }

        .container.content-area {
            max-width: 100%;
            padding-left: 0;
            padding-right: 0;
            padding-top: 0;
            padding-bottom: 60px;
        }
    </style>
</head>

<body>
    <div class="container mt-5 content-area">
        <div class="text-center">
            <button class="btn btn-primary btn-lg mb-4" id="toggleOperationBtn">花苗分割</button>
        </div>
        <p class="text-center text-dark mb-4">操作人员：<span id="currentUserName">{{auth()->user()->name ?? ''}}</span></p>

        <!-- 扫描框 -->
        <div class="rounded mb-3 mx-auto" style="width: 100%;">
            <div id="preview">
                <div class="overlay">
                    <div class="cutout"></div>
                </div>
                <div class="scanning-area"></div>
            </div>
            <p id="scanResult" style="font-weight: 900">扫描结果:</p>
        </div>

        <!-- 原始花瓶显示区 -->
        <div class="bg-white p-4 mb-3">
            <h5>原始花瓶：</h5>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>花瓶编号</th>
                        <th>条码号</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>

        <!-- 扫描结果显示区 -->
        <div class="bg-white p-4 mb-3">
            <h5>扫描结果：</h5>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>花瓶编号</th>
                        <th>条码号</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>

        <div class="fixed-bottom p-2 bg-light">
            <div class="container d-flex justify-content-between">
                <button class="btn btn-success btn-lg" id="submitBtn">提交</button>
                <button class="btn btn-primary btn-lg" id="startBtn">扫描原始瓶</button>
                <button class="btn btn-info btn-lg" id="startScan">开始扫描</button>
            </div>
        </div>

    </div>
    <script>
        $(document).ready(function () {
            let startPos = 0;
            let currentTransform = 0;
            let currentScanState = 'original';
            let $scanResult = $("#scanResult");

            document.getElementById('startBtn').addEventListener('click', function () {
                if (currentScanState === 'original') {
                    currentScanState = 'new';
                    this.textContent = '扫描新瓶';
                } else {
                    currentScanState = 'original';
                    this.textContent = '扫描原始瓶';
                }
            });

            $('tbody').on('touchstart', 'tr', function (e) {
                startPos = e.originalEvent.touches[0].clientX;
                currentTransform = parseInt($(this).find('td').css('transform').split(',')[4]) || 0;
            });

            $('tbody').on('touchmove', 'tr', function (e) {
                let endPos = e.originalEvent.touches[0].clientX;
                let move = currentTransform + (endPos - startPos);

                if (move <= 0 && move >= -80) {
                    $(this).find('td').css('transform', `translateX(${move}px)`);
                }
            });

            $('tbody').on('touchend', 'tr', function () {
                let movedDistance = parseInt($(this).find('td').css('transform').split(',')[4]);
                if (movedDistance < -40) {
                    $(this).find('td').css('transform', 'translateX(-80px)');
                } else {
                    $(this).find('td').css('transform', 'translateX(0)');
                }
            });

            $('tbody').on('click', '.delete-btn', function () {
                $(this).closest('tr').remove();
            });

            if (typeof Quagga === 'undefined') {
                console.error("Quagga library is not loaded. Make sure you've included it.");
                return;
            }

            document.getElementById('startScan').addEventListener('click', function () {
                let scanCounts = {};
                let idealWidth, idealHeight;
                const requiredCounts = 3;

                if (window.orientation === 90 || window.orientation === -90) {
                    // 横屏
                    idealWidth = Math.min(window.innerWidth, window.innerHeight);
                    idealHeight = Math.max(window.innerWidth, window.innerHeight);
                } else {
                    // 竖屏
                    idealWidth = Math.max(window.innerWidth, window.innerHeight);
                    idealHeight = Math.min(window.innerWidth, window.innerHeight);
                }

                Quagga.init({
                    inputStream: {
                        type: 'LiveStream',
                        constraints: {
                            facingMode: 'environment',
                            width: { min: idealWidth },
                            height: { min: idealHeight / 2 },
                            aspectRatio: { min: 1, max: 2 }
                        },
                        target: document.querySelector('#preview')
                    },
                    decoder: {
                        readers: ['code_128_reader', 'ean_reader'],
                        multiple: false
                    }
                }, function (err) {
                    if (err) {
                        console.log(err);
                        return;
                    }

                    Quagga.start();
                });

                var lastResult;

                Quagga.onDetected(function (result) {
                    var code = result.codeResult.code;

                    scanCounts[code] = (scanCounts[code] || 0) + 1;

                    if (scanCounts[code] === requiredCounts) {
                        $scanResult.text("扫描结果: " + code);

                        if (currentScanState === 'original') {
                            updateOriginalVaseResult(code);
                        } else {
                            if (!isNewVaseCodePresent(code)) {
                                updateNewVaseResults(code);
                            }
                        }

                        scanCounts[code] = 0;
                    }

                    for (let scannedCode in scanCounts) {
                        if (scannedCode !== code) {
                            scanCounts[scannedCode] = 0;
                        }
                    }
                });
            });

            function updateOriginalVaseResult(code) {
                // 检查原始花瓶中是否已经存在该条形码
                let isCodeAlreadyPresent = false;
                $(".bg-white.p-4.mb-3:first").find("tbody tr").each(function () {
                    if ($(this).find('td:nth-child(2)').text() === code) {
                        isCodeAlreadyPresent = true;
                        return false; // 如果找到，退出循环
                    }
                });

                // 如果条形码不存在，则添加新的行
                if (!isCodeAlreadyPresent) {
                    let newRow = `<tr class="slide-row">
                        <td>编号</td>
                        <td>${code}</td>
                        <td class="delete-container">
                            <button class="btn btn-danger btn-sm delete-btn">删除</button>
                        </td>
                    </tr>`;

                    $(".bg-white.p-4.mb-3:first").find("tbody").append(newRow);
                }
            }

            function isNewVaseCodePresent(code) {
                let isNewCodePresent = false;
                $(".bg-white.p-4.mb-3:last").find("tbody tr").each(function () {
                    if ($(this).find('td:nth-child(2)').text() === code) {
                        isNewCodePresent = true;
                        return false;
                    }
                });
                return isNewCodePresent;
            }

            function updateNewVaseResults(code) {
                let newRow = `<tr class="slide-row">
                    <td>编号</td>
                    <td>${code}</td>
                    <td class="delete-container">
                        <button class="btn btn-danger btn-sm delete-btn">删除</button>
                    </td>
                </tr>`;

                $(".bg-white.p-4.mb-3:last").find("tbody").append(newRow);
            }

            // 初始化 type 为 'O'（拆分操作）
            let type = 'O';

            $('#toggleOperationBtn').on('click', function () {
                // 切换按钮文本和 type 值
                if (type === 'O') {
                    $(this).text('花苗合并');
                    type = 'N';
                } else {
                    $(this).text('花苗分割');
                    type = 'O';
                }
            });

            $('#submitBtn').on('click', function () {
                // 构建要发送的数据
                let originalVases = [];
                $(".bg-white.p-4.mb-3:first").find("tbody tr").each(function () {
                    originalVases.push($(this).find('td:nth-child(2)').text());
                });

                let newVases = [];
                $(".bg-white.p-4.mb-3:last").find("tbody tr").each(function () {
                    newVases.push($(this).find('td:nth-child(2)').text());
                });

                // 检查是否有扫描结果
                if (newVases.length === 0) {
                    alert("没有任何数据可以提交。");
                    return; // 阻止继续执行
                }

                let dataToSend = {
                    originalVases: originalVases,
                    newVases: newVases,
                    type: type
                };
                console.log(JSON.stringify(dataToSend));

                let requestSucceeded = false;
                // 使用 $.ajax 发送数据
                $.ajax({
                    type: 'POST',
                    url: '{{ route("saveBottle") }}',  // 使用 Laravel 的 route() 功能来获取 URL
                    data: JSON.stringify(dataToSend),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  // 添加 CSRF 令牌
                    },
                    success: function (response) {
                        // 处理服务器的响应。例如，显示一条消息或重定向用户。
                        console.log(response);

                        requestSucceeded = true;  // 如果没有失败的条码，直接设置成功标志
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        // 处理错误
                        console.error('Error:', errorThrown);

                        // 尝试解析响应文本以获取更详细的错误信息
                        try {
                            var responseJson = JSON.parse(jqXHR.responseText);
                            var errorMessage = responseJson.message || '未知错误';
                            alert('错误: ' + errorMessage);  // 显示错误消息
                        } catch (e) {
                            // 如果解析失败，则显示通用错误信息
                            alert('错误: 发生未知错误');
                        }

                        requestSucceeded = false;  // 设置失败标志
                    },
                    complete: function () {
                        // 如果请求成功，则重定向到 '/bottles'
                        if (requestSucceeded) {
                            window.location.href = '{{ route("bottles") }}';
                        }
                    }
                });

            });
        });
    </script>
</body>

</html>