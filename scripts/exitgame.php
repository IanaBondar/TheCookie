<?php
$l = $_COOKIE["login"];
$s = $_COOKIE["gameid"];
$game = file_get_contents("https://mysql.lavro.ru/call.php?db=311780&pname=ExitGame&p1=$l&p2=$s");
$game = json_decode($game);

//echo "done";

if (isset($game->error)) {
    echo json_encode(array('success' => 0, 'error' => $game->error[0]));
} else {
    setcookie($s, '', time() - 3600, '/~s311780/TheCookie');
    echo json_encode(array('success' => 1));
}
?>
