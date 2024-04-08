<?php
$g = $_COOKIE["gameid"];
$l = $_POST["log"];

$user = file_get_contents("https://sql.lavro.ru/call.php?db=311780&pname=ShowUsersTable&p1=$l&p2=$g");
$user = json_decode($user);
if (isset($user->error[0])) {
    echo json_encode(array('success' => 0, 'error' => $user->error[0]));
}
else {
    $sw=0;
    $sh=0;
    if (isset($user->RESULTS[1]->sword[0])) {
        $sw = 1;
    }
    if (isset($user->RESULTS[2]->shield[0])) {
        $sh = 1;
    }
    echo json_encode(array('success' => 1, 'cardcolor' => $user->RESULTS[0]->cardcolor, 'cardvalue'=>$user->RESULTS[0]->cardvalue, 'color' => $user->RESULTS[0]->color, 'sword' => $sw, 'shield' => $sh));
}

    

?>