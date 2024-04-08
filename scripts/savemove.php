<?php
$g = $_COOKIE["gameid"];
$l = $_COOKIE["token"];
$s = $_POST["selected"];
$uid = $_POST["chosenUser"];
if ($s != '0'){
    $user = file_get_contents("https://mysql.lavro.ru/call.php?db=311780&pname=SaveCardForTurn&p1=$l&p2=$g&p3=$s&p4=$uid");
    $user = json_decode($user);


    if (isset($user->error[0])) {
        $err = $user->error[0];
        echo json_encode(array('success' => 0, 'error' => $err));
    }


}

    $user = file_get_contents("https://sql.lavro.ru/call.php?db=311780&pname=ShowTable&p1=$l&p2=$g");
    $user = json_decode($user);
    if (isset($user->error[0])) {
        echo json_encode(array('success' => 0, 'error' => $user->error[0]));
    }
    else {
        echo json_encode(array('success' => 1, 'cardcolor' => $user->RESULTS[0]->cardcolor, 'cardvalue'=>$user->RESULTS[0]->cardvalue, 'color' => $user->RESULTS[0]->color));
    }

    

?>