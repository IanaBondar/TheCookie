<?php

$t = $_COOKIE["token"];
$g = $_COOKIE["gameid"];
$game = file_get_contents("https://sql.lavro.ru/call.php?db=311780&pname=SaveResult&p1=$t&p2=$g");
$game = json_decode($game);



if (!isset($game->RESULTS[0]->wins[0])) {
     
    echo json_encode(array('success' => 0, 'error' => 'неудача'));
}
else {
    
        echo json_encode(array('success' => 1, 'win' => $game->RESULTS[0]->wins[0], 'points' => $game->RESULTS[0]->points[0]));
    
}
?>