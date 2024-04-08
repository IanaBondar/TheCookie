<?php
if (!isset($_COOKIE["token"])) {
    header('Location: login.html');
}
?>  
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New game</title>
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

        

            input:active {
                border: none;
            }

        .error-message {
            color: red;
            margin-top: -15px;
            margin-bottom: 10px;
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
            display: flex;
            background-color: rgba(247, 230, 208, 0.5);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            height: 75%;
            margin: 2.5em 8em;
        }

        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield;
            color: rgba(132, 94, 51, 1);
            width: 2em;
            
            background-color: rgba(132, 94, 51, 0);
            border: none;
            font-family: 'Exo 2', sans-serif;
            font-weight:800;
            text-align:center;
            font-size: xx-large;
            
            
        }


        .quantity-block, .quantity-block-2 {
            background-color: rgba(255, 186, 105, 0.5);
            box-shadow: inset 0 3px 5px 0 rgba(0, 0, 0, 0.15);
            border-radius: 5px;
            display:flex;

        }

        .quantity-arrow-minus,
        .quantity-arrow-plus, .quantity-arrow-minus-2,
        .quantity-arrow-plus-2 {
            cursor: pointer;
            width: 1.5em;
            height: 2.8em;
            border-radius: 4px;
            outline: none;
            margin: 0.1em;
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
            margin: 2em 5em;
        }

        .right-content {
            
        }

        .text {
            width: 15em;
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
            <div class="header-text">Создание новой игры</div>
        </div>
        <div class="right">
            <!--<div class="settings-icon" onclick="location.href = \'profile.html\';" ><img src="images/settings.svg" /></div>-->
        </div>
        
    </header>

    <form class="content" id="newGameForm">
        <div class="left-content">
            <div class="amount">
                <div class="text">Количество игроков: </div>
                <div class="quantity-block">
                    <button class="quantity-arrow-minus" type="button"><img src="images/arrow-left.svg"/></button>
                    <input class="quantity-num" id="pCol" type="number" value="6" />
                    <button class="quantity-arrow-plus" type="button"><img src="images/arrow-right.svg" /></button>
                </div>
            </div>
            <div class="seconds">
                <div class="text">Количество секунд на ход: </div>
                <div class="quantity-block-2">
                    <button class="quantity-arrow-minus-2" type="button"><img src="images/arrow-left.svg" /></button>
                    <input class="quantity-num-2" id="secs" type="number" value="30" />
                    <button class="quantity-arrow-plus-2" type="button"><img src="images/arrow-right.svg" /></button>
                </div>
            </div>
            <!--<div class="code">
                <div class="text" id="gameCode">Введите уникальный код игры: </div>
                <div class="quantity-block">
                    <div class="quantity-num" > XXXXX </div>
                </div>
            </div>-->
        </div>
        <div class="right-content">
            <div class="play">
                <button class="play-button" type="submit" >
                    <img src="images/play.svg" />
                </button>
            </div>
        </div>
    </form>
    

    <script>
        

        

        $(function () {

            (function quantityProducts() {
                var $quantityArrowMinus = $(".quantity-arrow-minus");
                var $quantityArrowPlus = $(".quantity-arrow-plus");
                var $quantityNum = $(".quantity-num");

                $quantityArrowMinus.click(quantityMinus);
                $quantityArrowPlus.click(quantityPlus);

                function quantityMinus() {
                    if ($quantityNum.val() > 6) {
                        $quantityNum.val(+$quantityNum.val() - 1);
                    }
                }

                function quantityPlus() {
                    if ($quantityNum.val() < 6) {
                        $quantityNum.val(+$quantityNum.val() + 1);
                    }
                    
                }
            })();

        });

        $(function () {

            (function quantityProducts2() {
                var $quantityArrowMinus = $(".quantity-arrow-minus-2");
                var $quantityArrowPlus = $(".quantity-arrow-plus-2");
                var $quantityNum = $(".quantity-num-2");

                $quantityArrowMinus.click(quantityMinus);
                $quantityArrowPlus.click(quantityPlus);

                function quantityMinus() {
                    if ($quantityNum.val() > 30) {
                        $quantityNum.val(+$quantityNum.val() - 10);
                    }
                }

                function quantityPlus() {
                    if ($quantityNum.val() < 90) {
                        $quantityNum.val(+$quantityNum.val() + 10);
                    }
                    
                }
            })();

        });

        $(document).ready(function () {
            $('#newGameForm').submit(function (e) {
                e.preventDefault();
                const data = {
                  p: $("#pCol").val(),
                  s: $("#secs").val(),
                };
                $.ajax({

                    method: "POST",
                    url: 'scripts/startgame.php',
                    data: data,
                    success: function (response) {
                        let jsonData = JSON.parse(response);
                        
                        if (jsonData.success == "1") {
                            location.href = 'waiting.php';
                            
                        }
                        else {
                            
                            document.getElementById("gameCode").innerText = jsonData.error;
                        }
                    }
                });
            });
        });



        window.onerror = function (msg, url, line) {
            alert("Message : " + msg);
            alert("url : " + url);
            alert("Line number : " + line);
        }
    </script>

</body>
</html>
