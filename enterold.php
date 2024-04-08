<?php
if (!isset($_COOKIE["token"])) {
    header('Location: login.html');
}
?>  
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter existing game</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@400;700&display=swap" rel="stylesheet">
    <style>
        @font-face {
            font-family: 'nico moji'; 
            src: url('nico-moji.ttf') format('truetype');
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

        

            input:active {
                border: none;
            }

        .error-message {
            color: red;
            margin-top: -15px;
            margin-bottom: 10px;
        }
        .element-button{
            font-family: nico moji;
            font-size: 2.5em;
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

        .header-img{
            margin: 0.1em 1em 0.1em 3em;
            cursor: pointer;
        }

        .header-text {
            padding-left: 1em;
            color: rgba(132, 94, 51, 1);
            font-size: 2em;
            font-weight: 600;
        }

        .stats {
            display: flex;
            align-items: center;
            
        }

        .right {
            display: flex;
            align-items: center;
            padding: 0 3em;
        }

        .settings-icon {
            cursor: pointer;
        }

        .content {
            display: grid;             
            
            
              grid-template-columns: 1fr 1fr 1fr 1fr 1fr; 
              grid-template-rows: 1fr 1fr 1fr 1fr; 
              gap: 2em 2em;     
              grid-template-areas: 
                ". . . . ."
                ". . . . ."
                ". . . . ."
                ". . . . ."; 
                background-color: rgba(247, 230, 208, 0.5);
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                backdrop-filter: blur(5px);
                -webkit-backdrop-filter: blur(5px);
                height: 70%;
                margin: 2em 8em;
                padding: 2em;
        }

        .container {
          
        }
        

        input[type="text"] {
            -moz-appearance: textfield;
            color: rgba(132, 94, 51, 1);
            width: 5em;
            
            background-color: rgba(132, 94, 51, 0);
            border: none;
            font-family: 'Exo 2', sans-serif;
            font-weight:800;
            text-align:center;
            font-size: xxx-large;
            
            
        }


        .quantity-block {
            background-color: rgba(255, 186, 105, 0.5);
            box-shadow: inset 0 3px 5px 0 rgba(0, 0, 0, 0.15);
            border-radius: 5px;
            display:flex;
            padding:0.3em;
        }

        

        .play {
            
            margin: 5em;
            position: absolute;
            right: 0;
            bottom: 0;
        }

        .amount, .seconds {
            display: flex;
            margin-bottom: 2em;
            align-items:center;
        }

        .left-content{
            margin: 5em 5em;
        }

        .text {
            width: 17em;
            font-size: x-large;
        }

        button {
            background-color: rgba(255, 186, 105, 1);
            font-family: 'Exo 2', sans-serif;
            font-size: large;
            color: rgba(132, 94, 51, 1);

            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
           
        }

        .play-button {
            padding: 0.5em;
            border-radius: 10px;
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
            <div><img class="header-img" src="images/arrow.svg" onclick="location.href = 'menu.php';"/></div>
            <div class="header-text">Вход в существующую игру</div>
        </div>
        <div class="right">
            <!--<div class="settings-icon" onclick="location.href = 'profile.html';"><img src="images/settings.svg" /></div>-->
        </div>
        
    </header>

    <div class="content" id="oldGameForm">

        <!--<div class="left-content">
            <div class="amount">
                <div class="text">Введите уникальный код игры: </div>
                <div class="quantity-block">
                    <input class="quantity-num" type="text" value="XXXXX" id="gameid" />
                </div>
            </div>
            <div id="errordiv"></div>
        </div>
        <div class="right-content">
            <div class="play">
                <button class="play-button">
                    <img src="images/play.svg" />
                </button>
            </div>
        </div>-->
    </div>
    

    <script>
    var games;
        $(document).ready(function () {
            intervalId = setInterval(async function() {
                $.ajax({

                    method: "POST",
                    url: 'scripts/showgames.php',
                    data: {},
                    success: function (response) {
                        let jsonData = JSON.parse(response);
                        
                        if (jsonData.success == "1") {
                            
                            console.log(jsonData.games);
                            displayButtons(jsonData.games);
                            
                        }
                        else {
                            
                            document.getElementById("errordiv").innerText = jsonData.error;
                        }
                    }
                });
            }, 2000); 
            
        });

        function displayButtons(elements) {
            var buttonsContainer = document.getElementById("oldGameForm");
            var buttonsHTML = '';
            //buttonsContainer.innerHTML = '';
            for (var i = 0; i < elements.length; i++) {
                buttonsHTML += '<button class="element-button" data-id="' + elements[i] + '">' + elements[i] + '</button>';
            }
            buttonsContainer.innerHTML = buttonsHTML;

            var buttons = document.getElementsByClassName("element-button");
            for (var j = 0; j < buttons.length; j++) {
                buttons[j].addEventListener("click", function() {
                    var elementId = this.getAttribute("data-id");
                    startThisGame(elementId); // Выполняем другой AJAX запрос при нажатии на кнопку
                });
            }
        }

        function startThisGame(id){
            $.ajax({

                    method: "POST",
                    url: 'scripts/entergame.php',
                    data: {game: id},
                    success: function (response) {
                        let jsonData = JSON.parse(response);
                        
                        if (jsonData.success == "1") {
                            location.href = 'waiting.php';
                            
                        }
                        else {
                            
                            document.getElementById("errordiv").innerText = jsonData.error;
                        }
                    }
            });
        }
        

        

        window.onerror = function (msg, url, line) {
            alert("Message : " + msg);
            alert("url : " + url);
            alert("Line number : " + line);
        }
    </script>

</body>
</html>
