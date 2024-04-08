<?php
$g = $_POST["game"];
$l = $_COOKIE["token"];
$game = file_get_contents("https://mysql.lavro.ru/call.php?db=311780&pname=EnterGame&p1=$g&p2=$l");
$game = json_decode($game);



if (isset($game->error)) {
    
    echo json_encode(array('success' => 0, 'error' => $game->error[0]));
}
else {
    setcookie("gameid", $g, time() + 3600, '/~s311780/TheCookie');
    setcookie("timer", $game->seconds[0], time() + 3600, '/~s311780/TheCookie');
    echo json_encode(array('success' => 1));
    
}
?>