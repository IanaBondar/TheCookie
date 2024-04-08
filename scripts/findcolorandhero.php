<?php
$l = $_COOKIE["token"];
$s = $_COOKIE["gameid"];
$game = file_get_contents("https://sql.lavro.ru/call.php?db=311780&pname=FindColorAndHero&p1=$l&p2=$s");
$game = json_decode($game);

//echo "done";

if (isset($game->RESULTS[0] ->error)) {
    echo json_encode(array('success' => 0, 'error' => $game->RESULTS[0] ->error[0]));
} else {
    
    //setcookie('hero', $h, time() + 3600, '/~s311780/TheCookie' );
    echo json_encode(array('success' => 1, 'color' => $game->RESULTS[0]->color[0], 'hero' => $game->RESULTS[1]->hero[0]));
}
?>
