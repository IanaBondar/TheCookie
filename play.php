<?php
if (!isset($_COOKIE["token"])) {
    header('Location: login.html');
     exit();
}
?>  
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Игра</title>
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
            margin: 0;
            overflow: hidden;
            background-color: #FFEDD7;
            font-family: 'Exo 2', sans-serif;
            color: rgba(132, 94, 51, 1);
            
        }

        header {
            background-color: #FFBA69;
            padding: 10px;
            padding-left: 30px;
            display: flex;
            align-items: center;
        }

        header img {
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }

        header h1 {
            font-size: 16px;
            color: #52391E;
            font-weight: bold;
            margin: 0;
        }

        main {
            background-color: rgba(247, 230, 208, 0.8);
            padding: 20px;
            margin: 30px;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: left;
            justify-content: left;
            flex: 1;
            
        }

        input {
            width: 100%;
            max-width: 400px;
            padding: 10px;
            
            border: none;
            border-bottom: 2px solid #845E33;
            background-color: transparent;
            font-size: 16px;
        }

        input:focus {
            outline: none;
            border-bottom: 2px solid #FFBA69;
        }

        label {
            font-size: 12px;
            color: #845E33;
            margin-bottom: 20px;
        }

        svg {
            width: 20px;
            height: 20px;
            fill: #845E33;
            cursor: pointer;
        }

        .container {  
           
            display: grid;
            grid-template-columns: 2fr 3fr 2fr;
            grid-template-rows: 1fr 0.9fr 2fr;
            gap: 0px 20px;
            grid-auto-flow: row;
            grid-template-areas:    
                "left up right"
                "left time right"
                "left down right";
            height: 100%;
        }

        .right {  
            
            display: grid;
            grid-template-columns: 1fr;
            grid-template-rows: 0.8fr 1.15fr;
            gap: 20px 0px;
            grid-auto-flow: row;
            grid-template-areas:
                "right-up"
                "right-down";
            grid-area: right;
        }

        .right-up { 
            grid-area: right-up;
            background-color: rgba(242, 228, 95, 1);
            
            padding: 20px; /* Расстояние от края контейнера до карточек */
            padding-bottom: 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Тень для контейнера */
            border-radius: 10px; /* Скругление уголков контейнера */
            justify-content: space-around; /* Равномерное распределение по горизонтали */
        }

        .ru{
            
        }

        .right-up-cards, .right-down-cards, .left-up-cards, .left-down-cards{
            display: flex;
            justify-content: space-around; /* Равномерное распределение по горизонтали */
            flex-wrap: wrap;
            
        }

        .up-cards{
            display: flex;
            justify-content: space-around; /* Равномерное распределение по горизонтали */
        }

        .right-down { 
            grid-area: right-down;
            background-color: rgba(117, 105, 255, 1);
            
            padding: 20px; /* Расстояние от края контейнера до карточек */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Тень для контейнера */
            border-radius: 10px; /* Скругление уголков контейнера */
            justify-content: space-around; /* Равномерное распределение по горизонтали */
         }
        
         .rd{
            
         }

         

        .left {  
            
            display: grid;
            grid-template-columns: 1fr;
            grid-template-rows: 0.80fr 1.15fr;
            gap: 20px 0px;
            grid-auto-flow: row;
            grid-template-areas:
                "left-up"
                "left-down";
            grid-area: left;
            height:100%;
        }

        .left-up {
            grid-area: left-up;
            background-color: rgba(150, 198, 225, 1);
            

            justify-content: space-around; /* Равномерное распределение по горизонтали */
            padding: 20px; /* Расстояние от края контейнера до карточек */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Тень для контейнера */
            border-radius: 10px; /* Скругление уголков контейнера */
        }

        .lu{
            
        }

        .left-down { 
            grid-area: left-down;
            background-color: rgba(103, 202, 107, 1);
            
            justify-content: space-around; /* Равномерное распределение по горизонтали */
            padding: 20px; /* Расстояние от края контейнера до карточек */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Тень для контейнера */
            border-radius: 10px; /* Скругление уголков контейнера */
            
        }

        .ld{
            
        }

        .up { 
            grid-area: up; 
            background-color: rgba(204, 108, 177, 1);
            
            justify-content: space-around; /* Равномерное распределение по горизонтали */
            padding: 20px; /* Расстояние от края контейнера до карточек */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Тень для контейнера */
            border-radius: 10px; /* Скругление уголков контейнера */
            
        }

        .u{
            
        }

        .time { 
            font-family: nico moji;
            font-size: 60px;
            grid-area: time;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .down { 
            grid-area: down;
            display: flex;
            background-color: rgba(255, 186, 105, 1);
            flex-direction: column;
            padding: 1em; /* Расстояние от края контейнера до карточек */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Тень для контейнера */
            border-radius: 10px; /* Скругление уголков контейнера */
         }

         .d{
           
         }
        
        .down-small{
            display: flex;
            justify-content: space-around;
            margin-top:0.5em;
        }

        .down-big{
            display: flex;
            margin-top: 2em;
            margin-bottom: 1em;
            justify-content: space-around;
            
            
        }

        .card {
            display:flex;
            width: 60px; /* Ширина карточки */
            height: 90px; /* Высота карточки */
            justify-content: center;
            align-items: center;
            margin: 10px; /* Расстояние между карточками */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Тень для карточек */
            border-radius: 8px; /* Скругление уголков карточек */
            
        }
        .bigCard{
            width: 90px; /* Ширина карточки */
            height: 135px; /* Высота карточки */
            justify-content: center;
            align-items: center;
            display:flex;
            margin: 10px; /* Расстояние между карточками */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Тень для карточек */
            border-radius: 8px;

        }

        .card img{
            width: 100%;
        }

        .bigCard img{
             width: 100%;
        }

        p {
            text-align: center  ;
            font-size: x-large;
            color: black;
            margin:0;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: rgba(255, 186, 105, 1);
            color: white;
            padding: 1em 2em;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
            height: 1vw;
        }

        .header-img {
            margin: 0.1em 1em 0.1em 3em;
            cursor: pointer;
        }

        .header-text {
            padding-left: 1em;
            padding-bottom: 0.1em;
            color: rgba(132, 94, 51, 1);
            font-size: 1.5em;
            font-weight: 600;
        }

        .stats {
            display: flex;
            align-items: center;
        }

        .right-header {
            display: flex;
            align-items: center;
            padding: 0 4em;
        }

        .settings-icon {
            font-family: 'Nico Moji';
            color: rgba(132, 94, 51, 1);
            font-size: xx-large;
        }

        .popup {
            display: none;
            position: fixed;
            
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(247, 230, 208, 0.5);
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                backdrop-filter: blur(5px);
                -webkit-backdrop-filter: blur(5px);
            padding: 40px;
            text-align: center;
            border-radius: 10px;
            color: rgba(132, 94, 51, 1);
        }

        .popup button{
            width: 5em;
            height: 2em;
            margin: 2em;
            font-size: 1.3em;
            font-weight:600;
            color: rgba(132, 94, 51, 1);
            background-color: rgba(255, 163, 56, 0.6);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
            text-align: center;
            border: none;
            border-radius: 5px;
            font-family: 'Exo 2', sans-serif;
        }

        .popup button:hover{
            background-color: rgba(255, 163, 56, 0.4);
        }

        .popup p{
            color: rgba(132, 94, 51, 1);
            margin-bottom: 1em;
        }

        .modal {
            display:none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.1);
            text-align: center;
        }

        .modal-content {
            display:flex;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(247, 230, 208, 0.5);
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                backdrop-filter: blur(5px);
                -webkit-backdrop-filter: blur(5px);
            padding: 20px;
            border-radius: 10px;
        }

        .close {
            color: rgba(132, 94, 51, 1);
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: black;
        }

        .y{
            background-color: rgba(242, 228, 95, 1);
        }

        .s{
            background-color: rgba(117, 105, 255, 1);
        }

        .f{
            background-color: rgba(204, 108, 177, 1);
        }

        .k{
            background-color: rgba(150, 198, 225, 1);
        }

        .z {
            background-color: rgba(103, 202, 107, 1);
        }

        .o {
            background-color: rgba(255, 186, 105, 1);
        }

        #user1, #user2, #user3, #user4, #user5 {
            color: rgba(132, 94, 51, 1);
            font-size: 1.5em;
            font-weight: 600;
        }

        .ellipce{
            width:1em;
            
        }

        .guess-img{
            width:25em;
            margin-left:2em;
            cursor: pointer;
        }

        

        .ktokogo{
            display:none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
            text-align: center;
        }

        .popup-guess-content{
            display:flex;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(247, 230, 208, 0.5);
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                backdrop-filter: blur(5px);
                -webkit-backdrop-filter: blur(5px);
            padding: 20px;
            
            border-radius: 10px;
        }

        #close-guess {
            color: rgba(132, 94, 51, 1);
            float: right;
            font-size: 3em;
            font-weight: bold;
            cursor: pointer;
            margin-left:1em;
            
        }

        #close-guess:hover {
            color: black;
        }



        


        @media screen and (min-width: 600px) {
            header h1 {
                font-size: 20px;
            }

            main {
                
                
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="stats">
            <div><img class="header-img" src="images/arrow.svg" id="backButton" /></div>
            <div class="header-text"></div>
        </div>
        <div class="right-header">
            <div class="settings-icon" id="session-id">id: <?php echo $_COOKIE["gameid"]?></div>
        </div>

    </header>

    <div id="confirmationPopup" class="popup">
        <p>Вы уверены, что хотите сдаться?</p>
        <button id="yesButton">Да</button>
        <button id="noButton">Нет</button>
    </div>

    <div id="ktokogo" class="ktokogo">
        <div class="popup-guess-content">
            <img class="guess-img" src="images/ktokogo.png"/>
            <span id="close-guess" onclick="closeGuess()">&times;</span>
        </div>

    </div>
    
    <div class="container">
        <div class="right">
            <div class="right-up flex-container" ondrop="drop(event)" ondragover="allowDrop(event)">
                <p id="user1">@User</p>
                <div class="right-up-cards " >
                    <!--<div class="card ru"></div>-->
                    
                </div>
                
            </div>
            <div class="right-down flex-container" ondrop="drop(event)" ondragover="allowDrop(event)">
                <p id="user2">@User</p>
                <div class="right-down-cards " >
                    <!--<div class="card rd"></div>-->
                    
                </div>
            </div>
        </div>
        <div class="left">
            <div class="left-up flex-container" ondrop="drop(event)" ondragover="allowDrop(event)">
            <p id="user3">@User</p>
                <div class="left-up-cards " >
                    <!--<div class="card lu"></div>-->
                    
                </div>
                
            </div>
            <div class="left-down flex-container" ondrop="drop(event)" ondragover="allowDrop(event)">
                <p id="user4">@User</p>
                <div class="left-down-cards " >
                    <!--<div class="card ld"></div>-->
                    
                </div>
            </div>
        </div>
        <div class="up flex-container" ondrop="drop(event)" ondragover="allowDrop(event)">
            <p id="user5">@User</p>
            <div class="up-cards " >
                <!--<div class="card u"></div>-->
                
            </div>
            
        </div>
        <div class="time">
            <img src="" class="ellipce">
            <!--<svg viewBox="0 0 41 41" width="1000" height="1000">
                <image x=0 y=0 href="ellipce.svg" width="100%" height="100%"/>
            </svg>-->
            <div id="timer">00:59</div>
            <img src="images/vopros.svg" class="ellipce" onclick="showGuess()">
            <!--<svg viewBox="0 0 1000 1000">
                <image x=0 y=0 href="vopros.svg" width="150%" height="150%"/>
            </svg>-->
        </div>
        <div class="down">
            
            <div class="down-small">
                <!--<div class="card"></div>-->
                
            </div>
            <div class="down-big">
                <div class="bigCard d" id="hero" onclick="showCardSelection()"></div>
                <div class="bigCard d sword" id="sword" draggable="true" ondragstart="drag(event, 'sword')"></div>
                <div class="bigCard d shield" id="shield"  draggable="true" ondragstart="drag(event, 'shield')"></div>
                <div class="bigCard d holm" id="holm" onclick="makeMove('Холм')"></div>
            </div>
        </div>

        <div id="modal" class="modal">
            <div class="modal-content">
                
                <div class="bigCard d Cookie" id="Cookie" onclick="makeMove('Печенька')"></div>
                <div class="bigCard d Blue" id="Blue" onclick="makeMove('Синий')"></div>
                <div class="bigCard d Str" id="Str" onclick="makeMove('Стронций')"></div>
                <div class="bigCard d trs" id="trs" onclick="makeMove('37')"></div>
                <div class="bigCard d Cos" id="Cos" onclick="makeMove('Косинус')"></div>
                <div class="bigCard d Pers" id="Pers" onclick="makeMove('Персы')"></div>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
        </div>

    </div>
     
    <script>
    var logins = null;

    function closePopup() {
            
            document.querySelector('.rules-container').style.display = 'none';
            
            document.body.style.overflow = 'auto';
    }

        function showCardSelection() {
            var modal = document.getElementById('modal');
            modal.style.display = 'block';
        }
        function closeModal() {
            var modal = document.getElementById('modal');
            modal.style.display = 'none';
        }

        function closeGuess() {
            var guess = document.getElementById('ktokogo');
            guess.style.display = 'none';
        }

        function showGuess() {
            var guess = document.getElementById('ktokogo');
            guess.style.display = 'block';
        }

        document.getElementById('backButton').addEventListener('click', function() {
            
            document.getElementById('confirmationPopup').style.display = 'block';
        });

        document.getElementById('noButton').addEventListener('click', function() {
            
            document.getElementById('confirmationPopup').style.display = 'none';
        });

        document.getElementById('yesButton').addEventListener('click', function() {
            // Переход на другую страницу при нажатии "Да"
            //window.location.href = 'menu.php';

            
            document.cookie = 'hero=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/~s311780/TheCookie;';
            document.cookie = 'gameid=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/~s311780/TheCookie;';
            $.ajax({

                method: "POST",
                url: 'scripts/exitgame.php',
                data: {},
                success: function (response) {
                    let jsonData = JSON.parse(response);

                    if (jsonData.success == "1") {
                        location.href = 'menu.php';
                    }
                    else {
                        location.href = 'menu.php';
                        //document.getElementById("result").innerText = jsonData.error;
                    }
                }
            });
           
            localStorage.removeItem('dataLoaded');
        });
        function activateInput(id) {
            document.getElementById(id).readOnly = false;
        }

        function drag(event, str) {
            event.dataTransfer.setData("id", str);
            console.log(str);
        }

        function allowDrop(event) {
            event.preventDefault();
        }

        function drop(event) {
            event.preventDefault();
            var data = event.dataTransfer.getData("id");
            var draggedElement = document.getElementById(data);
            var destinationContainer = event.target;

            if (destinationContainer.classList.contains("flex-container")) {
                let pElement = destinationContainer.querySelector('p');
                selectedItemID = pElement.innerText.slice(1);
                console.log(data);
                if (data == 'sword') {
                    setCookie('selected', 'Меч', 1);
                }
                else if (data == 'shield') {
                    setCookie('selected', 'Щит', 1);
                }
                sendTurn();
                //destinationContainer.appendChild(draggedElement);
                //draggedElement.style.width = "60px";
                //draggedElement.style.height = "90px";
            }
        }

        function setCookie(cookieName, cookieValue, expirationDays) {
            const d = new Date();
            d.setTime(d.getTime() + (expirationDays * 24 * 60 * 60 * 1000)); 

            const expires = "expires=" + d.toUTCString();
            document.cookie = cookieName + "=" + cookieValue + ";" + expires + ";path=/~s311780/TheCookie";
        }

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

        function loadDataElem(element, color, login) {
                loadDataElemWithoutLogin(element, color);
            
            
                let pElement = element.querySelector('p');
                pElement.innerText = '@' + login;
                
            
        }

        function loadDataElemWithoutLogin(element, color){
            switch (color) {
                case 'Синий':
                    element.style.backgroundColor = 'rgba(117, 105, 255, 0.4)';
                    cardElements = element.querySelectorAll(".card");
                    for (var i = 0; i < cardElements.length; i++) {
                        cardElements[i].style.backgroundColor = 'rgba(117, 105, 255, 1)';
                    }
                    bigcardElements = element.querySelectorAll(".bigCard");
                    for (var i = 0; i < bigcardElements.length; i++) {
                        bigcardElements[i].style.backgroundColor = 'rgba(117, 105, 255, 1)';
                    }
                    break;
                case 'Фиолетовый':
                    element.style.backgroundColor = 'rgba(204, 108, 177, 0.4)';
                    cardElements = element.querySelectorAll(".card");
                    for (var i = 0; i < cardElements.length; i++) {
                        cardElements[i].style.backgroundColor = 'rgba(204, 108, 177, 1)';
                    }
                    bigcardElements = element.querySelectorAll(".bigCard");
                    for (var i = 0; i < bigcardElements.length; i++) {
                        bigcardElements[i].style.backgroundColor = 'rgba(204, 108, 177, 1)';
                    }
                    break;
                case 'Красный':
                    element.style.backgroundColor = 'rgba(150, 198, 225, 0.4)';
                    cardElements = element.querySelectorAll(".card");
                    for (var i = 0; i < cardElements.length; i++) {
                        cardElements[i].style.backgroundColor = 'rgba(150, 198, 225, 1)';
                    }
                    bigcardElements = element.querySelectorAll(".bigCard");
                    for (var i = 0; i < bigcardElements.length; i++) {
                        bigcardElements[i].style.backgroundColor = 'rgba(150, 198, 225, 1)';
                    }
                    break;
                case 'Оранжевый':
                    element.style.backgroundColor = 'rgba(255, 186, 105, 0.4)';
                    cardElements = element.querySelectorAll(".card");
                    for (var i = 0; i < cardElements.length; i++) {
                        cardElements[i].style.backgroundColor = 'rgba(255, 186, 105, 1)';
                    }
                    bigcardElements = element.querySelectorAll(".bigCard");
                    for (var i = 0; i < bigcardElements.length; i++) {
                        bigcardElements[i].style.backgroundColor = 'rgba(255, 186, 105, 1)';
                    }
                    break;
                case 'Желтый':
                    element.style.backgroundColor = 'rgba(242, 228, 95, 0.4)';
                    cardElements = element.querySelectorAll(".card");
                    for (var i = 0; i < cardElements.length; i++) {
                        cardElements[i].style.backgroundColor = 'rgba(242, 228, 95, 1)';
                    }
                    bigcardElements = element.querySelectorAll(".bigCard");
                    for (var i = 0; i < bigcardElements.length; i++) {
                        bigcardElements[i].style.backgroundColor = 'rgba(242, 228, 95, 1)';
                    }
                    break;
                case 'Зеленый':
                    element.style.backgroundColor = 'rgba(103, 202, 107, 0.4)';
                    cardElements = element.querySelectorAll(".card");
                    for (var i = 0; i < cardElements.length; i++) {
                        cardElements[i].style.backgroundColor = 'rgba(103, 202, 107, 1)';
                    }
                    bigcardElements = element.querySelectorAll(".bigCard");
                    for (var i = 0; i < bigcardElements.length; i++) {
                        bigcardElements[i].style.backgroundColor = 'rgba(103, 202, 107, 1)';
                    }
                    break;
                default:
                    console.log('Неизвестный цвет');
            }

            
        }

        function GiveUp(){

        }

        $(document).ready(function () {
            let $id = "" + <?php echo $_COOKIE["gameid"]?>;
            insertImageIntoDiv('Cookie', 'images/cookie.svg');
            insertImageIntoDiv('Blue', 'images/blue.svg');
            insertImageIntoDiv('Str', 'images/str.svg');
            insertImageIntoDiv('trs', 'images/37.svg');
            insertImageIntoDiv('Pers', 'images/pers.svg');
            insertImageIntoDiv('Cos', 'images/cos.svg');
            insertImageIntoDiv('sword', 'images/sword.svg');
            insertImageIntoDiv('shield', 'images/shield.svg');
            insertImageIntoDiv('holm', 'images/holm.svg');
                $.ajax({
                    method: "POST",
                    url: 'scripts/getlogins.php', 
                    data: {game: $id},
                    success: function (response) {
                        let jsonData = JSON.parse(response);

                        if (jsonData.success == "1") {
                            

                            logins = jsonData.logins;
                            const userIndex = logins.indexOf('<?php echo $_COOKIE["login"]?>');

                            if (userIndex !== -1) {
                                const removedLogins = logins.splice(0, userIndex);
                                logins.push(...removedLogins);

                                console.log(logins);
                            } else {
                                console.log('Логин не найден в массиве.');
                            }

                            let elementsArray = [
                                document.getElementsByClassName("right-down")[0],
                                document.getElementsByClassName("right-up")[0],
                                document.getElementsByClassName("up")[0],
                                document.getElementsByClassName("left-up")[0],
                                document.getElementsByClassName("left-down")[0]
                            
                            ];
                            let i = 1;
                            let down = document.getElementsByClassName("down")[0];
                            elementsArray.forEach(element => {
                                
                                let x = logins[i];
                                $.ajax({

                                    method: "POST",
                                    url: 'scripts/findcolor.php',
                                    data: {log: x},
                                    success: function (resp) {
                                        let rData = JSON.parse(resp);
                                        
                                        if (rData.success == "1") {
                                            console.log(rData.color);
                                            loadDataElem(element, rData.color, x);
                                        }
                                        else {
                                            
                                        }
                                    }
                                });

                                i++;
                                
                            });
                            $.ajax({

                                    method: "POST",
                                    url: 'scripts/findcolorandhero.php',
                                    data: {},
                                    success: function (resp) {
                                        let rData = JSON.parse(resp);

                                        if (rData.success == "1") {
                                            setCookie('hero', rData.hero, 1);
                                            var element = document.getElementsByClassName("modal")[0];
                                            var bigCardElements = element.getElementsByClassName("bigCard");

                                            
                                            for (var i = 0; i < bigCardElements.length; i++) {
                                                if (bigCardElements[i].id === 'Cookie' && getCookie('hero')=== 'Печенька'||
                                                    bigCardElements[i].id === 'Blue' && getCookie('hero')=== 'Синий' ||
                                                    bigCardElements[i].id === 'Str' && getCookie('hero')=== 'Стронций' ||
                                                    bigCardElements[i].id === 'trs' && getCookie('hero')=== '37' ||
                                                    bigCardElements[i].id === 'Pers' && getCookie('hero')=== 'Персы' ||
                                                    bigCardElements[i].id === 'Cos' && getCookie('hero')=== 'Косинус' ) {
                                                    
                                                    bigCardElements[i].remove();
                                                    break; 
                                                }
                                            }
                                            var heroCard = document.getElementById('hero');
                                            switch (getCookie('hero')) {
                                                    case 'Печенька':
                                                    heroCard.innerHTML = '<img src="images/cookie.svg" >';
                                                    break;
                                                    case 'Синий':
                                                    heroCard.innerHTML = '<img src="images/blue.svg" >';
                                                    break;
                                                    case 'Стронций':
                                                    heroCard.innerHTML = '<img src="images/str.svg" >';
                                                    break;
                                                    case '37':
                                                    heroCard.innerHTML = '<img src="images/37.svg" >';
                                                    break;
                                                    case 'Персы':
                                                    heroCard.innerHTML = '<img src="images/pers.svg" >';
                                                    break;
                                                    case 'Косинус':
                                                    heroCard.innerHTML = '<img src="images/cos.svg" >';
                                                    break;
                                                    default:
                                                        console.log('Неизвестный персонаж');
                                            }
                                                        
                                             
                                            loadDataElemWithoutLogin(down, rData.color);
                                        }
                                        else {
                                            
                                        }
                                    }
                                });
                                startTimer();
                            
                        } else {
                            // Дополнительный код, если jsonData.success не равен "1"
                        }
                    }
                });
            
             updateTimer(); 
             
        });

        function updateTimerDisplay(timerValue) {
            if (timerValue% 60 >= 10 ){
                $("#timer").text("0" + Math.floor(timerValue / 60)+ ":" + timerValue% 60);
            }
            else {
                $("#timer").text("0" + Math.floor(timerValue / 60)+ ":0" + timerValue% 60);
            }
            
        }

        function startTimer() {
            setCookie('selected', 0, 1);
            $.ajax({
                method: "POST",
                url: 'scripts/starttimer.php',
                data: {},
                success: function (resp) {
                    let rData = JSON.parse(resp);

                    if (rData.success == "1") {
                        //updateTimerDisplay(getCookie('timer'));
                            
                    }
                    else {
                        console.log('Таймер не запущен');              
                    }
                }
            });

           
        }

        function waitOneSecond() {
            return new Promise(resolve => {
                setTimeout(resolve, 1000);
            });
}       var intervalId;
        async function updateTimer(){
            //await waitOneSecond();
            let value = 0;
            intervalId = setInterval(async function() {
                
                $.ajax({
                method: "POST",
                url: 'scripts/updatetimer.php',
                data: {},
                success: function (resp) {
                    let rData = JSON.parse(resp);

                    if (rData.success == "1") {
                        
                        value = rData.time;
                        updateTimerDisplay(value);
                        if (rData.newTurn == "1"){
                            updateTable();
                            /*var bigCardElements = document.getElementsByClassName("bigCard");
                       
                            for (var i = 0; i < bigCardElements.length; i++) {
                                if (bigCardElements[i].id === 'Cookie' && getCookie('selected')=== 'Печенька'||
                                    bigCardElements[i].id === 'Blue' && getCookie('selected')=== 'Синий' ||
                                    bigCardElements[i].id === 'Str' && getCookie('selected')=== 'Стронций' ||
                                    bigCardElements[i].id === 'trs' && getCookie('selected')=== '37' ||
                                    bigCardElements[i].id === 'Pers' && getCookie('selected')=== 'Персы' ||
                                    bigCardElements[i].id === 'Cos' && getCookie('selected')=== 'Косинус' ||
                                    bigCardElements[i].id === 'sword' && getCookie('selected')=== 'Меч' ||
                                    bigCardElements[i].id === 'shield' && getCookie('selected')=== 'Щит' ||
                                    bigCardElements[i].id === 'holm' && getCookie('selected')=== 'Холм' ) {
                                        
                                    bigCardElements[i].remove();
                                    break; 
                                }
                            }*/
                        }
                        if (rData.gameEnded == "1"){
                            //КОНЕЦ ИГРЫ
                            let win = false;
                            let points = 0;
                            $.ajax({
                                method: "POST",
                                url: 'scripts/saveresult.php',
                                data: {},
                                success: function (resp) {
                                    let rData = JSON.parse(resp);

                                    if (rData.success == "1") {
                                        console.log('Результат сохранен'); 
                                        if (rData.win == "1") win = true;
                                        points = rData.points;
                                        console.log(win);
                                        console.log(points);
                                        ShowCloseWindow(win, points);
                                    }
                                    else {
                                        console.log(rData.error);              
                                    }
                                }
                
                             });
       
                        }
                    }
                    else {
                        console.log('Таймер не запущен');              
                    }
                }
                
                });
       
            }, 1000); 
        }

        function ShowCloseWindow(win, points) {
            var message;
            if (win === true) {
                message = "Победа! ";
            } else {
                message = "";
            }
            
            if (points > 4) {
                message += "Вы набрали " + points + " очков!";
            } else if (points > 1) {
                 message += "Вы набрали " + points + " очка!";
            } else if (points === 1) {
                 message += "Вы набрали 1 очко!";
            } 
            else {
                message = "Вы не набрали ни одного очка. Не расстраивайтесь!";
            }

            clearInterval(intervalId);

            

        

            var modal = document.createElement("div");
            modal.style.position = "fixed";
            modal.style.top = "0";
            modal.style.left = "0";
            modal.style.width = "100%";
            modal.style.height = "100%";
            modal.style.backgroundColor = "rgba(0,0,0,0.25)";
            modal.style.display = "flex";
            modal.style.alignItems = "center";
            modal.style.justifyContent = "center";
            //modal.style.zIndex = "9999";


            var modalContent = document.createElement("div");
            modalContent.style.backgroundColor = "rgba(247, 230, 208, 0.5)";
            modalContent.style.padding = "40px";
            modalContent.style.borderRadius = "5px";
            modalContent.style.textAlign = "center";
            //modalContent.style.transform = 'translate(-50%, -50%)';
            modalContent.style.boxShadow = '0 0 10px rgba(0, 0, 0, 0.1)';
            modalContent.style.backdropFilter = 'blur(5px)';
            modalContent.style.webkitBackdropFilter = 'blur(5px)';
            modalContent.style.color = 'rgba(132, 94, 51, 1)';


            var closeButton = document.createElement("button");
            closeButton.textContent = "Завершить игру";
            closeButton.style.padding = "10px 20px";
            closeButton.style.marginTop = "10px";
            closeButton.style.cursor = "pointer";

            closeButton.style.fontSize = '1.3em';
            closeButton.style.fontWeight = '600';
            closeButton.style.color = 'rgba(132, 94, 51, 1)';
            closeButton.style.backgroundColor = 'rgba(255, 163, 56, 0.6)';
            closeButton.style.backdropFilter = 'blur(5px)';
            closeButton.style.webkitBackdropFilter = 'blur(5px)';
            closeButton.style.boxShadow = '0 3px 10px rgba(0, 0, 0, 0.15)';
            closeButton.style.textAlign = 'center';
            closeButton.style.border = 'none';
            closeButton.style.borderRadius = '5px';
            closeButton.style.fontFamily = "'Exo 2', sans-serif";

            closeButton.onclick = function() {
                //modal.parentNode.removeChild(modal);
                
                document.cookie = 'hero=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/~s311780/TheCookie;';
                document.cookie = 'gameid=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/~s311780/TheCookie;';
                $.ajax({

                    method: "POST",
                    url: 'scripts/exitgame.php',
                    data: {},
                    success: function (response) {
                        let jsonData = JSON.parse(response);

                        if (jsonData.success == "1") {
                            location.href = 'menu.php';
                        }
                        else {
                            location.href = 'menu.php';
                            //document.getElementById("result").innerText = jsonData.error;
                        }
                    }
                });


            };

            var messageElement = document.createElement("p");
            messageElement.textContent = message;

            modalContent.appendChild(messageElement);
            modalContent.appendChild(closeButton);
            modal.appendChild(modalContent);

            document.body.appendChild(modal);
        }

        

        function updateTable(){

            let i = 0; 
            let elementsArray = [
                                document.getElementsByClassName("down-small")[0],
                                document.getElementsByClassName("right-down-cards")[0],
                                document.getElementsByClassName("right-up-cards")[0],
                                document.getElementsByClassName("up-cards")[0],
                                document.getElementsByClassName("left-up-cards")[0],
                                document.getElementsByClassName("left-down-cards")[0]
                            
                            ];
            elementsArray.forEach(element => {
            let x = logins[i];

            $.ajax({
                method: "POST",
                url: 'scripts/showtable.php',
                data: {log: x},
                success: function (resp) {
                    let rData = JSON.parse(resp);
                    var bigCardElements = document.getElementsByClassName("bigCard");
                    if (rData.success == "1") {
                        
                         element.innerHTML = '';   
                         let f=false;
                        if (x === getCookie("login")) {
                            f=true;
                            if (rData.sword == "0"){
                                for (var i = 0; i < bigCardElements.length; i++) {
                                        if (bigCardElements[i].id === 'sword' ) {
                                                
                                            bigCardElements[i].remove();
                                            break; 
                                        }
                                    }
                            }
                            if (rData.shield == "0"){
                                for (var i = 0; i < bigCardElements.length; i++) {
                                        if (bigCardElements[i].id === 'shield' ) {
                                                
                                            bigCardElements[i].remove();
                                            break; 
                                        }
                                    }
                            }
                        }
                        
                        for (var j = 0; j < rData.cardvalue.length; j++) {
                            var newElement = document.createElement('div');
                            //newElement.textContent = rData.cardvalue[j];
                            switch (rData.cardvalue[j]) {
                                case 'Печенька':
                                newElement.innerHTML = '<img src="images/cookie.svg" >';
                                if (f) {
                                    for (var i = 0; i < bigCardElements.length; i++) {
                                        if (bigCardElements[i].id === 'Cookie' ) {
                                                
                                            bigCardElements[i].remove();
                                            break; 
                                        }
                                    }
                                }
                                break;
                                case 'Синий':
                                newElement.innerHTML = '<img src="images/blue.svg" >';
                                if (f) {
                                    for (var i = 0; i < bigCardElements.length; i++) {
                                        if (bigCardElements[i].id === 'Blue') {   
                                            bigCardElements[i].remove();
                                            break; 
                                        }
                                    }
                                }
                                break;
                                case 'Стронций':
                                newElement.innerHTML = '<img src="images/str.svg" >';
                                if (f) {
                                    for (var i = 0; i < bigCardElements.length; i++) {
                                        if (bigCardElements[i].id === 'Str' ) {
                                                
                                            bigCardElements[i].remove();
                                            break; 
                                        }
                                    }
                                }
                                break;
                                case 37:
                                newElement.innerHTML = '<img src="images/37.svg" >';
                                if (f) {
                                    for (var i = 0; i < bigCardElements.length; i++) {
                                        if (bigCardElements[i].id === 'trs') {
                                                
                                            bigCardElements[i].remove();
                                            break; 
                                        }
                                    }
                                }
                                break;
                                case 'Персы':
                                newElement.innerHTML = '<img src="images/pers.svg" >';
                                if (f) {
                                    for (var i = 0; i < bigCardElements.length; i++) {
                                        if (bigCardElements[i].id === 'Pers') {
                                                
                                            bigCardElements[i].remove();
                                            break; 
                                        }
                                    }
                                }
                                break;
                                case 'Косинус':
                                newElement.innerHTML = '<img src="images/cos.svg" >';
                                if (f) {
                                    for (var i = 0; i < bigCardElements.length; i++) {
                                        if (bigCardElements[i].id === 'Cos') {
                                                
                                            bigCardElements[i].remove();
                                            break; 
                                        }
                                    }
                                }
                                break;
                                case 'Меч':
                                newElement.innerHTML = '<img src="images/sword.svg" >';
                                break;
                                case 'Щит':
                                newElement.innerHTML = '<img src="images/shield.svg" >';
                                break;
                                case 'Холм':
                                newElement.innerHTML = '<img src="images/holm.svg" >';
                                if (f) {
                                    for (var i = 0; i < bigCardElements.length; i++) {
                                        if (bigCardElements[i].id === 'holm') {
                                                
                                            bigCardElements[i].remove();
                                            break; 
                                        }
                                    }
                                }
                                break;
                                default:
                                    console.log('Неизвестный персонаж');
                            }
                            newElement.classList.add('card');
                            switch (rData.cardcolor[j]) {
                            case 'Синий':
                                newElement.classList.add('s');
                                break;
                            case 'Фиолетовый':
                                newElement.classList.add('f');
                                break;
                            case 'Красный':
                                newElement.classList.add('k');
                                break;
                            case 'Оранжевый':
                                newElement.classList.add('o');
                                break;
                            case 'Желтый':
                                newElement.classList.add('y');
                                break;
                            case 'Зеленый':
                                newElement.classList.add('z');
                                break;
                            default:
                                console.log('Неизвестный цвет');
                            }
                            element.appendChild(newElement);
                        }
                        
                    }
                    else {
                        console.log(rData.error);              
                    }
                }
                
            });
            i++;
            });
        }

        var selectedItemID = null;

        function sendTurn(){
            let sel = getCookie('selected');
            console.log(sel);
            $.ajax({
                method: "POST",
                url: 'scripts/savemove.php',
                data: {chosenUser: selectedItemID, selected: sel},
                success: function (resp) {
                    let rData = JSON.parse(resp);
                     
                    if (rData.success == "1") {

                        /*var downSmallElement = document.querySelector('.down-small');
                        downSmallElement.innerHTML = '';
                        for (var i = 0; i < rData.cardvalue.length; i++) {
                            var newElement = document.createElement('div');
                            newElement.textContent = rData.cardvalue[i];
                            newElement.classList.add('card');
                            downSmallElement.appendChild(newElement);
                        }*/
                        //updateTimerDisplay(getCookie('timer'));
                        
                            
                    }
                    else {
                        console.log(rData.error);              
                    }
                    //setCookie('selected', 0, 1);
                        selectedItemID = null;
                }
            });
            
        }
            
        
        function makeMove(cardName){
            closeModal();
            //if (getCookie('selected') == '0'){
                setCookie('selected', cardName, 1);
                
                if (cardName == "Меч" || cardName == "Щит"){
                    selectedItemID = null;
                    let ru = document.getElementsByClassName('right-up')[0];
                    ru.addEventListener('click', handleItemClick);
                    let rd = document.getElementsByClassName('right-down')[0];
                    rd.addEventListener('click', handleItemClick);
                    let u = document.getElementsByClassName('up')[0];
                    u.addEventListener('click', handleItemClick);
                    let lu = document.getElementsByClassName('left-up')[0];
                    lu.addEventListener('click', handleItemClick);
                    let ld = document.getElementsByClassName('left-down')[0];
                    ld.addEventListener('click', handleItemClick);
                    alert("Нажмите на поле любого врага");

                }
                else {
                    sendTurn();
                }
                
                
                    
                
            //}
            
        }
            
        function handleItemClick() {
            
            
            let pElement = this.querySelector('p');
            selectedItemID = pElement.innerText.slice(1);
            
            let ru = document.getElementsByClassName('right-up')[0];
            ru.removeEventListener('click', handleItemClick);
            let rd = document.getElementsByClassName('right-down')[0];
            rd.removeEventListener('click', handleItemClick);
            let u = document.getElementsByClassName('up')[0];
            u.removeEventListener('click', handleItemClick);
            let lu = document.getElementsByClassName('left-up')[0];
            lu.removeEventListener('click', handleItemClick);
            let ld = document.getElementsByClassName('left-down')[0];
            ld.removeEventListener('click', handleItemClick);
            alert("Вы выбрали элемент с ID: " + selectedItemID);
            sendTurn();
        }

        function insertImageIntoDiv(divId, imagePath) {
          
          var divElements = document.querySelectorAll('.' + divId);
          divElements.forEach(function(divElement) {
            var imgElement = document.createElement("img");
            imgElement.src = imagePath;
            divElement.innerHTML='';
            divElement.appendChild(imgElement);

            });
        }


        /*window.onerror = function (msg, url, line) {
                alert("Message : " + msg);
                alert("url : " + url);
                alert("Line number : " + line);
            }*/
    </script>

</body>
</html>