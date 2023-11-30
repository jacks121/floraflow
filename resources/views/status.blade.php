<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>瓶子状态更新</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <script src="{{ asset('js/jquery-1.11.3.min.js') }}"></script>
    <script src="{{ asset('js/quagga.min.js') }}"></script>
    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Arial', sans-serif;
        }

        .btn-primary, .btn-success, .btn-danger {
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }

        .btn-primary {
            background-color: #003366;
        }

        .btn-success {
            background-color: #006633;
        }

        .btn-danger {
            background-color: #990000;
        }

        .delete-btn {
            border: none;
            text-decoration: underline;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 14px;
            position: absolute;
            right: -33px;
            top: 50%;
            transform: translateY(30%);
            width: 60px;
            height: 30px;
        }

        .table {
            width: 100%;
            overflow: hidden;
        }

        .slide-row {
            position: relative;
            overflow: hidden;
        }

        .slide-row > td {
            position: relative;
        }

        .slide-row > td.delete-container {
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
            <select class="form-control mb-4" id="statusSelect" style="margin-left: 33px;width: 81%">
                <option value="in_stock">请选择要修改的状态</option>
                <option value="in_stock">在库</option>
                <option value="infected">感染</option>
                <option value="planted">栽种</option>
            </select>
        </div>
        <p class="text-center text-dark mb-4">操作人员：<span id="currentUserName">{{auth()->user()->name ?? ''}}</span></p>

        <div class="rounded mb-3 mx-auto" style="width: 100%;">
            <div id="preview">
                <div class="overlay">
                    <div class="cutout"></div>
                </div>
                <div class="scanning-area"></div>
            </div>
            <p id="scanResult" style="font-weight: 900">扫描结果:</p>
        </div>

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

        <div class="text-center">
            <button class="btn btn-success btn-lg" id="submitBtn">提交</button>
            <button class="btn btn-info btn-lg" id="startScan">开始扫描</button>
            <button class="btn btn-info btn-lg" id="clear">清空</button>
        </div>
    </div>
    <script>
        $(document).ready(function() {

            loadScannedData();

            if (typeof Quagga === 'undefined') {
                console.error("Quagga library is not loaded. Make sure you've included it.");
                return;
            }

            document.getElementById('startScan').addEventListener('click', function() {
                let scanCounts = {};
                let idealWidth, idealHeight;
                const requiredCounts = 3;

                if (window.orientation === 90 || window.orientation === -90) {
                    idealWidth = Math.min(window.innerWidth, window.innerHeight);
                    idealHeight = Math.max(window.innerWidth, window.innerHeight);
                } else {
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
                            aspectRatio: {min: 1, max: 2}
                        },
                        target: document.querySelector('#preview') 
                    },
                    decoder: {
                        readers: ['code_128_reader', 'ean_reader'],
                        multiple: false
                    }
                }, function(err) {
                    if (err) {
                        console.log(err);
                        return;
                    }
                    Quagga.start();
                });

                Quagga.onDetected(function(result) {
                    var code = result.codeResult.code;

                    scanCounts[code] = (scanCounts[code] || 0) + 1;

                    if (scanCounts[code] === requiredCounts) {
                        updateScanResults(code);
                        scanCounts[code] = 0;
                    }

                    for (let scannedCode in scanCounts) {
                        if (scannedCode !== code) {
                            scanCounts[scannedCode] = 0;
                        }
                    }
                });
            });

            $('tbody').on('touchstart', 'tr', function(e) {
                startPos = e.originalEvent.touches[0].clientX;
                currentTransform = parseInt($(this).find('td').css('transform').split(',')[4]) || 0;
            });
    
            $('tbody').on('touchmove', 'tr', function(e) {
                let endPos = e.originalEvent.touches[0].clientX;
                let move = currentTransform + (endPos - startPos);
    
                if (move <= 0 && move >= -80) {
                    $(this).find('td').css('transform', `translateX(${move}px)`);
                }
            });
    
            $('tbody').on('touchend', 'tr', function() {
                let movedDistance = parseInt($(this).find('td').css('transform').split(',')[4]);
                if (movedDistance < -40) {
                    $(this).find('td').css('transform', 'translateX(-80px)');
                } else {
                    $(this).find('td').css('transform', 'translateX(0)');
                }
            });

            function updateScanResults(code) {
                // 检查是否已经扫描了相同的条码
                let isCodeAlreadyPresent = false;
                $(".bg-white.p-4.mb-3:last").find("tbody tr").each(function() {
                    if ($(this).find('td:nth-child(2)').text() === code) {
                        isCodeAlreadyPresent = true;
                        return false; // 如果找到相同的条码，退出循环
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

                    $(".bg-white.p-4.mb-3:last").find("tbody").append(newRow);
                    saveScannedData();
                }
            }

            // 保存扫描结果到 localStorage
            function saveScannedData() {
                let scannedVases = [];
                $(".bg-white.p-4.mb-3:last").find("tbody tr").each(function() {
                    scannedVases.push($(this).find('td:nth-child(2)').text());
                });
                localStorage.setItem('scannedVases', JSON.stringify(scannedVases));
            }

            // 从 localStorage 加载扫描数据
            function loadScannedData() {
                let scannedVases = JSON.parse(localStorage.getItem('scannedVases')) || [];
                scannedVases.forEach(function(code) {
                    let newRow = `<tr class="slide-row">
                        <td>编号</td>
                        <td>${code}</td>
                        <td class="delete-container">
                            <button class="btn btn-danger btn-sm delete-btn">删除</button>
                        </td>
                    </tr>`;
                    $(".bg-white.p-4.mb-3:last").find("tbody").append(newRow);
                });
            }

            // 清空按钮点击事件处理
            $('#clear').on('click', function() {
                // 弹出确认对话框
                if (confirm("您确定要清空所有扫描的数据吗？")) {
                    // 如果用户确认，则清空 localStorage 和页面上的扫描结果
                    localStorage.removeItem('scannedVases');
                    $(".bg-white.p-4.mb-3:last").find("tbody").empty();
                }
            });

            // 删除按钮的点击事件处理程序
            $('tbody').on('click', '.delete-btn', function() {
                $(this).closest('tr').remove();
            });

            $('#submitBtn').on('click', function() {
                let scannedVases = [];
                let selectedStatus = $('#statusSelect').val();

                $(".bg-white.p-4.mb-3:last").find("tbody tr").each(function() {
                    scannedVases.push($(this).find('td:nth-child(2)').text());
                });

                let dataToSend = {
                    vases: scannedVases,
                    status: selectedStatus
                };

                console.log(JSON.stringify(dataToSend));

                $.ajax({
                    type: 'POST',
                    url: '{{ route("changeStatus") }}',  
                    data: JSON.stringify(dataToSend),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
                    },
                    success: function(response) {
                        // 处理服务器的响应。例如，显示一条消息或重定向用户。
                        console.log(response);
                        
                        requestSucceeded = true;  // 如果没有失败的条码，直接设置成功标志
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
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
                    complete: function() {
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
