<?php
if (!isset($_COOKIE["token"])) {
    header('Location: login.html');
}
?>  

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Page</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@400;700&display=swap" rel="stylesheet">
    <style>
        @font-face {
            font-family: 'Nico Moji';
            src:url('nico-moji.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            background-color: #FFEDD7;
            font-family: 'Exo 2', sans-serif;
            color: rgba(132, 94, 51, 1);
            margin: 0;
            padding: 0;
            
            height: 100vh;
            background-repeat: repeat;
            background-image: url("images/fonimage.png");
            animation: animate 80s ease infinite;
        }

        .error-message {
            color: red;
            margin-top: -15px;
            margin-bottom: 10px;
        }

        form {
            background-color: rgba(247, 230, 208, 0.8);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            user-select: none;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: rgba(255, 186, 105, 1);
            color: white;
            padding: 1em 2em;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
            height: 3vw;
        }

        .stats {
            display: flex;
            align-items: center;
            margin-left: 2em;
        }

        .stat {
            background-color: rgba(242, 177, 100, 1);
            border-radius: 7px;
            box-shadow: inset 0 0 4px rgba(0, 0, 0, 0.25);
            font-family: 'Nico Moji', sans-serif;
            font-size: 24px;
            padding: 0.25em 0.75em;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: rgba(132, 94, 51, 1);
            margin: 0.1em 1em;
        }

        .stat-img {
            margin-right: 0.5em;
            height: 1.5em;
            
        }

        .right {
            display: flex;
            align-items: center;
            padding: 0 3em;
        }

        .login-name {
            padding-right: 1em;
            color: rgba(132, 94, 51, 1);
            font-size: 2em;
            font-weight:600;

        }

        .settings-icon {
            cursor: pointer;
        }

        .content {
            display: flex;
            justify-content: center;
            gap: 7em;
            padding: 20px;
        }

        .item {
            width: 18em;
            height: 24em;
            background-color: rgba(255, 163, 56, 0.6);
            margin-top: 5em;
            border-radius: 10px;
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        .item img{
            height:10em;
            margin-top: 4em;
            
        }

            .item:hover {
                cursor: pointer;
                background-color: rgba(255, 163, 56, 0.3);
            }

        .text {
            margin-top: 1em;
            font-size: 2em;
            font-weight:600;
        }

        .text-3 {
            margin-top: 1em;
            font-size: 2em;
            font-weight: 600;
        }

        button {
            background-color: rgba(255, 186, 105, 1);
            font-family: 'Exo 2', sans-serif;
            font-size:large;
            color: rgba(132, 94, 51, 1);
            margin-top: 20px;
            padding: 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width:100%;
        }

        button:hover {
            background-color: rgba(255, 186, 105, 0.7);
            color: rgba(132, 94, 51, 0.7);
        }

        

        @keyframes animate {
            0%, 100% {
                background-position: left top;
            }

            25% {
                background-position: right bottom;
            }

            50% {
                background-position: left bottom;
            }

            75% {
                background-position: right top;
            }   
    </style>
</head>
<body>
    <header>
        <div class="stats">
            <div class="stat" id="wins"><img class="stat-img" src="images/wins.svg"/>0</div>
            <div class="stat" id="points"><img class="stat-img" src="images/coin.svg" />0</div>
        </div>
        <div class="right">
            <div class="login-name" id="login-name">Login</div>
            <!--<div class="settings-icon" onclick="location.href = 'profile.html';"><img src="images/settings.svg" /></div>-->
        </div>
        
    </header>

    <div class="content">
        <div class="item" onclick="location.href = 'startnew.php';">
            <img src="images/plus.svg" alt="Play">
            <div class="text">Создать</div>
        </div>
        <!--<div class="item">
            <img src="images/play.svg" alt="Play">
            <div class="text">Играть</div>
        </div>-->
        <div class="item" onclick="location.href = 'enterold.php';">
            <img src="images/hands.svg" alt="Play">
            <div class="text-3">Войти в игру</div>
        </div>
        <div class="item" onclick="location.href = 'rules.php';">
            <img src="images/rules2.svg" alt="Rules">
            <div class="text">Правила</div>
        </div>
    </div>
    

    <script>
        
        $(document).ready(function () {
            $.ajax({

                    method: "POST",
                    url: 'scripts/showwins.php',
                    data: {},
                    success: function (response) {
                        let jsonData = JSON.parse(response);
                        
                        if (jsonData.success == "1") {
                            document.getElementById('wins').innerHTML = "<img class='stat-img' src='images/wins.svg'/>" + jsonData.wins;
                            document.getElementById('points').innerHTML = "<img class='stat-img' src='images/coin.svg' />"+jsonData.points;
                            
                            
                        }
                        else {
                            
                            document.getElementById("errordiv").innerText = jsonData.error;
                        }
                    }
                });
            intervalId = setInterval(async function() {
                $.ajax({

                    method: "POST",
                    url: 'scripts/showwins.php',
                    data: {},
                    success: function (response) {
                        let jsonData = JSON.parse(response);
                        
                        if (jsonData.success == "1") {
                            document.getElementById('wins').innerHTML = "<img class='stat-img' src='images/wins.svg'/>" + jsonData.wins;
                            document.getElementById('points').innerHTML = "<img class='stat-img' src='images/coin.svg' />"+jsonData.points;
                            
                            
                        }
                        else {
                            
                            document.getElementById("errordiv").innerText = jsonData.error;
                        }
                    }
                });
            }, 10000); 
            
        });

        
        function getCookie(name) {
            const cookies = document.cookie.split(';');
            for (const cookie of cookies) {
                const [cookieName, cookieValue] = cookie.split('=');
                if (cookieName.trim() === name) {
                    return decodeURIComponent(cookieValue);
                }
            }
            return null;
        }




        const loginValue = getCookie('login');

        const outputDiv = document.getElementById('login-name');
        if (loginValue !== null) {
            outputDiv.textContent = `Привет, ${loginValue}!`;
        } else {
            outputDiv.textContent = 'Куки с именем "login" не найдено.';
        }

       

        window.onerror = function (msg, url, line) {
            alert("Message : " + msg);
            alert("url : " + url);
            alert("Line number : " + line);
        }
    </script>

</body>
</html>
