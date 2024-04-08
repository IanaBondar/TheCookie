<?php
$n = $_POST["p"];
$t = $_POST["s"];
$l = $_COOKIE["token"];
$game = file_get_contents("https://mysql.lavro.ru/call.php?db=311780&pname=NewGame&p1=$t");
$game = json_decode($game);



if (!isset($game->session_id)) {
     
    echo json_encode(array('success' => 0, 'error' => 'неудача'));
}
else {
    $game_id = $game->session_id[0];
    $addPlayer = file_get_contents("https://mysql.lavro.ru/call.php?db=311780&pname=EnterGame&p1=$game_id&p2=$l");
    $addPlayer = json_decode($addPlayer);
    if (isset($addPlayer->error)) {
    
        echo json_encode(array('success' => 0, 'error' => error[0]));
    }
    else {
        setcookie("timer", $t, time() + 3600, '/~s311780/TheCookie');
        setcookie("gameid", $game_id, time() + 3600, '/~s311780/TheCookie');
        echo json_encode(array('success' => 1, 'game' => $game_id, 'player' => $addPlayer->player_id));
    }
    
}
?>