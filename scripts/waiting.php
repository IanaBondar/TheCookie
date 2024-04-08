<?php
$id = $_POST["game"];
$game = file_get_contents("https://sql.lavro.ru/call.php?db=311780&pname=ShowAllPlayers&p1=$id");
$game = json_decode($game);

//echo "done";

if (isset($game->RESULTS[0]->error)) {
    echo json_encode(array('success' => 0, 'error' => $game->error[0]));
} else {
    echo json_encode(array('success' => 1, 'array' => $game->RESULTS[0]->array, 'isstarted' => $game->RESULTS[1]->isstarted[0]));
}
?>
