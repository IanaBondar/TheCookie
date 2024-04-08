<?php
$g = $_COOKIE["gameid"];
$t = $_COOKIE["timer"];
$game = file_get_contents("https://mysql.lavro.ru/call.php?db=311780&pname=StartTimer&p1=$g");
$game = json_decode($game);

if (isset($game->error)) {
    
    echo json_encode(array('success' => 0, 'error' => $game->error[0]));
}
else {
    
    echo json_encode(array('success' => 1));
    
}
?>