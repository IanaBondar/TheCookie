<?php
$l = $_POST["log"];
$s = $_COOKIE["gameid"];
$game = file_get_contents("https://mysql.lavro.ru/call.php?db=311780&pname=FindColor&p1=$l&p2=$s");
$game = json_decode($game);

//echo "done";

if (isset($game->error)) {
    echo json_encode(array('success' => 0, 'error' => error[0]));
} else {
    
    echo json_encode(array('success' => 1, 'color' => $game->color[0] ));
}
?>
