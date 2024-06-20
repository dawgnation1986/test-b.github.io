<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>文本聊天室</title>
    <style>
        body {
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
    font-family: Arial, sans-serif;
    background-color: rgba(245, 245, 245, 0.3); /* 修改背景色为半透明 */
}

#chat-box {
    width: 80%;
    max-height: 60vh;
    overflow-y: scroll;
    border: 2px solid #ccc; /* 加深边框 */
    border-radius: 5px;
    padding: 10px;
    background-color: rgba(255, 255, 255, 0.8); /* 设置半透明背景 */
}

input[type="text"] {
    width: 50%;
    padding: 10px;
    border: 2px solid #666; /* 加深输入框边框 */
    border-radius: 5px;
    margin-top: 10px;
    background-color: rgba(255, 255, 255, 0.8); /* 设置半透明背景 */
}

button {
    padding: 10px 20px;
    border: none;
    border-radius: 20px;
    margin: 10px;
    background-color: rgba(66, 133, 244, 0.5); /* 设置半透明背景 */
    color: white;
    cursor: pointer;
}

button.register-button {
    padding: 5px 10px;
    font-size: 14px;
    background-color: rgba(66, 133, 244, 0.5); /* 设置半透明背景 */
}

/* 其他样式保持不变 */

        @media (max-width: 768px) {
            #chat-box {
                width: 90%;
            }

            input[type="text"] {
                width: 90%;
            }
            /* 弹窗样式 */
        #welcomePopup {
            display: none;
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            border: 1px solid #ccc;
            background-color: white;
            z-index: 1000;
        }
        /* 关闭按钮样式 */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        }
    </style>
<style>
    body, html {
      margin: 0;
      padding: 0;
      width: 100%;
      height: 100%;
      overflow: hidden;
      touch-action: none;
    }
    .particle {
      position: absolute;
      color: #f00; /* 粒子颜色 */
      font-size: 24px; /* 字体大小 */
      opacity: 0; /* 初始不透明度 */
      transform: scale(1); /* 初始大小 */
      animation: fadeOutAndScale 3s ease-in forwards; /* 动画持续时间5秒，ease-in淡入效果 */
      pointer-events: none; /* 忽略粒子的鼠标事件 */
    }
    @keyframes fadeOutAndScale {
      from {
        opacity: 1;
        transform: scale(1);
      }
      to {
        opacity: 0;
        transform: scale(1.5); /* 淡出时放大 */
      }
    }
  </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
</head>
<body>
<div id="welcomePopup">
        <span class="close">&times;</span>
        <h2>欢迎访问我们的网站！</h2>
        <p>我们很高兴您能来到这里。</p>
        <p>屏幕左下方小紫条点开可以播放音乐哦。</p>
    </div>
    <script>
        // JavaScript 代码将放在这里
        window.onload = function() {
    // 页面加载完成后显示弹窗
    document.getElementById('welcomePopup').style.display = 'block';

    // 添加关闭按钮的点击事件
    var closeBtn = document.querySelector('.close');
    closeBtn.onclick = function() {
        document.getElementById('welcomePopup').style.display = 'none';
    };

    // 可以添加一个事件监听器来关闭弹窗，当用户点击弹窗外部时
    window.onclick = function(event) {
        if (event.target == document.getElementById('welcomePopup')) {
            document.getElementById('welcomePopup').style.display = 'none';
        }
    };
};
    </script>
     <h1>先注册后发言,否则为匿名用户</h1>
<button onclick="loginUser()" class="register-button">登录</button>
<script>
function loginUser() {
    const username = prompt('请输入您的用户名：');
    const password = prompt('请输入您的密码：');
    if (username && password) {
        $.ajax({
            url: 'login1.php', // PHP登录文件的路径
            method: 'POST',
            data: { username: username, password: password },
            success: function(response) {
                if (response === "登录成功") {
                    alert("登录成功！");
                    // 这里可以设置cookie或localStorage来记录用户已登录状态
                    // document.cookie = "username=" + username;
                } else {
                    alert(response); // 显示登录结果，即 "用户名或密码不能为空" 或 "用户名或密码错误"
                }
            }
        });
    }
}
</script>
    <button onclick="registerUser()" class="register-button">注册用户名</button>
    <div id="chat-box"></div>
    <input type="text" id="message" placeholder="输入消息...">
    <button onclick="sendMessage()">发送</button>
    <script>
        let isFirstRegister = true;

        function refreshChat() {
            $.ajax({
                url: 'get_chat.php',
                success: function(data) {
                    $('#chat-box').html(data);
                    $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
                }
            });
        }

        setInterval(refreshChat, 1000);

// 定义一个包含违规词和特定词的数组
const sensitiveWords = {
    badWords: ["傻逼", "滚", "去死", "废物", "啊狗"],
    dhmWords: ["获取兑换码"]
};

// 创建一个函数来检查消息中是否包含违规词或特定词
function checkMessageForSensitiveWords(message) {
    const { badWords, dhmWords } = sensitiveWords;
    
    // 首先检查是否包含违规词
    for (const word of badWords) {
        if (message.includes(word)) {
            return { isBadWord: true };
        }
    }
    
    // 然后检查是否包含特定词
    for (const word of dhmWords) {
        if (message.trim() === word) {
            return { isDhmWord: true };
        }
    }
    
    return { isBadWord: false, isDhmWord: false };
}

// 修改 sendMessage 函数
function sendMessage() {
    const message = $('#message').val().trim();
    const checkResult = checkMessageForSensitiveWords(message);

    if (checkResult.isBadWord) {
        alert("消息中包含违规词，您将被踢出聊天室！");
        $('#message').val(''); // 清空输入框
        window.location.href = 'kick.html';
        return; // 阻止发送消息
    } else if (checkResult.isDhmWord) {
        // 弹出提示框
        var userResponse = confirm("生日快乐！宝宝 点击确定进入网站获取你的专属礼物吧！");
        if (userResponse) {
                $('#message').val(''); // 清空输入框
            window.location.href = 'getdhm.php'; // 用户点击确定，跳转到getdhm.html页面
        } else {
            $('#message').val(''); // 清空输入框
        }
        return; // 阻止发送消息
    } else {
        // 如果没有违规词和特定词，正常发送消息
        $.ajax({
            url: 'save_chat.php',
            method: 'POST',
            data: { message: message },
            success: function() {
                $('#message').val('');
            }
        });
    }
}

        function registerUser() {
    const username = prompt('请输入您的用户名：');
    const password = prompt('请输入您的密码：');
    if (username && password) {
        $.ajax({
            url: 'register.php', // PHP注册文件的路径
            method: 'POST',
            data: { username: username, password: password },
            success: function(response) {
                alert(response); // 显示注册结果
                if (response === "注册成功") {
                    document.cookie = "username=" + username; // 设置cookie
                }
            }
        });
    }
}
    </script>
    <h3>当前在线人数：<span id="online-count">0</span></h3>
    <script>
    function fetchOnlineCount() {
    $.ajax({
        url: 'get_online_count.php', // 服务器端用于返回在线人数的文件
        method: 'GET',
        success: function(count) {
            $('#online-count').text(count); // 更新在线人数显示
        },
        error: function() {
            $('#online-count').text('未知');
        }
    });
}

// 页面加载完成后获取初始的在线人数
fetchOnlineCount();

// 每隔一定时间（例如30秒）获取一次在线人数
setInterval(fetchOnlineCount, 1000);
    </script>
   <button id="downloadButton">下载源代码</button>
<script>
    document.getElementById('downloadButton').addEventListener('click', function() {
        window.location.href = 'yuan.zip'; // 替换为实际的文件下载路径
        alert('点击即可下载本网站源代码，点击确定后需要等待下载链接响应。');
    });
</script>
<div id="ipInfoContainer">
    <h3>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;你的IP和地理位置信息组织暂不支持显示</h4>
    <p id="ipInfo"></p>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 调用函数获取IP信息
    getIPInfo();
});

function getIPInfo() {
    // 使用fetch API发送请求到第三方IP定位服务
    fetch('http://ip-api.com/json/?fields=61439')
        .then(response => response.json())
        .then(data => {
            // 检查请求是否成功
            if (data.status === 'success') {
                // 将获取到的信息显示在页面上
                document.getElementById('ipInfo').textContent =
                    'IP地址: ' + data.query + '\n' +
                    '国家: ' + data.country + ' (' + data.countryCode + ')\n' +
                    '地区: ' + data.regionName + ', ' + data.region + '\n' +
                    '城市: ' + data.city + '\n' +
                    '纬度: ' + data.lat + '\n' +
                    '经度: ' + data.lon + '\n' +
                    '时区: ' + data.timezone + '\n' +
                    'ISP: ' + data.isp + '\n' +
                    '组织: ' + data.org;
            } else {
                // 如果请求失败，显示错误信息
                document.getElementById('ipInfo').textContent = '无法获取IP信息：' + data.message;
            }
        })
        .catch(error => {
            // 如果捕获到错误，显示错误信息
            console.error('请求IP信息失败:', error);
            document.getElementById('ipInfo').textContent = '请求IP信息失败';
        });
}
</script>
<script>
    const particles = []; // 存储所有粒子的数组

    document.addEventListener('touchstart', handleTouchStart, false);
    document.addEventListener('touchmove', handleTouchMove, false);
    document.addEventListener('touchend', handleTouchEnd, false);
    document.addEventListener('click', handleClick, false); // 处理点击事件

    function handleTouchStart(e) {
      e.preventDefault();
      for (let i = 0; i < e.touches.length; i++) {
        createParticle(e.touches[i]);
      }
    }

    function handleTouchMove(e) {
      e.preventDefault();
      // 更新粒子位置，并在新位置创建新的粒子
      for (let i = 0; i < e.touches.length; i++) {
        updateParticlePosition(e.touches[i]);
        createParticle(e.touches[i], true);
      }
    }

    function handleTouchEnd(e) {
      // 触摸结束时，粒子将自动淡出
    }

    function handleClick(e) {
      // 点击时创建粒子效果
      createParticle({ clientX: e.clientX, clientY: e.clientY });
    }

    function createParticle(touch, isTail) {
      const particle = document.createElement('span');
      particle.className = 'particle';
      particle.style.left = `${touch.clientX}px`;
      particle.style.top = `${touch.clientY}px`;
      particle.textContent = '🎂';
      document.body.appendChild(particle);
      particles.push(particle);

      if (isTail) {
        // 如果是拖尾粒子，设置一个定时器来移除它
        setTimeout(() => particle.remove(), 5500); // 5.5秒后移除粒子
      }
    }

    function updateParticlePosition(touch) {
      // 更新最后一个粒子的位置
      const lastParticle = particles[particles.length - 1];
      if (lastParticle) {
        lastParticle.style.left = `${touch.clientX}px`;
        lastParticle.style.top = `${touch.clientY}px`;
      }
    }
  </script>
</body>
</html>
