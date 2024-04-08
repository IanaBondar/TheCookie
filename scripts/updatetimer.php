<?php
$g = $_COOKIE["gameid"];
$game = file_get_contents("https://sql.lavro.ru/call.php?db=311780&pname=UpdateTimer&p1=$g");
$game = json_decode($game);

if (isset($game->RESULTS[0]->error)) {
    
    echo json_encode(array('success' => 0, 'error' => $game->RESULTS[0]->error[0]));
}
else {
    if (isset($game->RESULTS[1]->newTurn)){
        if (isset($game->RESULTS[2]->gameEnded)){
            echo json_encode(array('success' => 1, 'time' => $game->RESULTS[0]->seconds_diff[0], 'newTurn' => 1, 'gameEnded' => 1));
        }
        else {
            echo json_encode(array('success' => 1, 'time' => $game->RESULTS[0]->seconds_diff[0], 'newTurn' => 1, 'gameEnded' => 0));
        }
        
    }
    else {
        echo json_encode(array('success' => 1, 'time' => $game->RESULTS[0]->seconds_diff[0], 'newTurn' => 0, 'gameEnded' => 0));
    }
    
    
}
?>