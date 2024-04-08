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
            
            background-color: rgba(247, 230, 208, 0.5);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            height: 140%;
            margin: 7em 8em;
            padding: 1.5em 6em;
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

         header {
            position: fixed;
              top: 0;
              width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: rgba(255, 186, 105, 1);
            color: white;
            padding: 1em 2em;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
            height: 3vw;
            z-index: 9999;
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
            <div class="header-text">Правила игры "Печенька 2.0"</div>
        </div>
        <div class="right">
            <!--<div class="settings-icon" onclick="location.href = \'profile.html\';" ><img src="images/settings.svg" /></div>-->
        </div>
        
    </header>

    <div class="content" id="newGameForm">
        
        <h2>Цель игры </h2>
        <p>Вам нужно вычислить двух персонажей: того, за кем вы охотитесь, и того, кто охотится за вами. Подсказка всегда напомнит, на кого вам нужно напасть и от кого вам нужно защититься. За успешное нападение, защиту и сохранение карты холма в руке вы будете получать монеты. В конце игры победит тот, кто наберет больше всех монет.</p>
        <img src="images/ktokogo.png" width="40%">
        <h2>Описание раунда</h2>
        <p>В каждом раунде все игроки должны выложить перед собой любую карту со своей руки — персонажа, меч, щит или холм. Затем карты одновременно вскрываются и оказываются на столе того игрока, кому предназначалась та или иная карта. Другие игроки не могут видеть, какую карту вы выбрали для этого хода, но обязаны видеть карты, которые вы уже разыграли. Эти карты не могут быть больше разыграны в этой игре. С каждым новым кругом вы будете собирать все больше информации, и вам все проще будет понять, у кого какая роль. </p>
        <h3>Пример</h3>
        <p>Гомер выложил Печеньку, Барт — Стронция, Лиза — 37, а Мардж — также Стронция. Теперь мы точно знаем, что Гомер не Печенька, Барт<br> и Мардж не Стронций, а Лиза не 37. </p>
        <p>Если среди вскрытых карт на столе оказался меч, то игрок, выложивший его, выбирает, кого он будет атаковать. Для этого он кладет свою карту меча перед игроком, за которым он охотится.</p>
        <p><i><strong>Внимание! </strong></i>Карты щита и меча разыгрываются на других игроков. Во время хода игрок решает, от кого ему защищаться и кого атаковать,<br> и, в зависимости от этого, перетаскивает нужную карту на поле соперника. </p>
        
        <h2>Подсчет монет </h2>
        <p>В конце раунда каждый игрок получает монеты за выполнение следующих условий: </p>
        <p><strong>3 монеты</strong> — за атаку своей цели; </p>
        <p><strong>2 монеты</strong> — за защиту от того, чьей целью он был; </p>
        <p><strong>1 монета</strong> — за сохранение карты холма в руке до конца раунда</p>

    </div>
    

    <script>
        


        window.onerror = function (msg, url, line) {
            alert("Message : " + msg);
            alert("url : " + url);
            alert("Line number : " + line);
        }
    </script>

</body>
</html>
