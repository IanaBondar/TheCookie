<?php
ini_set('display_errors', 1);
$id = $_POST["game"];
$game = file_get_contents("https://sql.lavro.ru/call.php?db=311780&pname=GetLogins&p1=$id");
$game = json_decode($game);

//echo "done";

if (isset($game->RESULTS[0]->error)) {
    echo json_encode(array('success' => 0, 'error' => $game->RESULTS[0]->error[0]));
} else {

    if (isset($game->RESULTS[0]->login)) {
        $logins = $game->RESULTS[0]->login;

        foreach ($logins as $login) {
            $res = file_get_contents("https://sql.lavro.ru/call.php?db=311780&pname=AddCardsToInHand&p1=$login&p2=$id");
        }


        echo json_encode(array('success' => 1, 'logins' => $game->RESULTS[0]->login ));
    }
    else {
        $logins = $game->RESULTS[2]->login;

        foreach ($logins as $login) {
            $res = file_get_contents("https://sql.lavro.ru/call.php?db=311780&pname=AddCardsToInHand&p1=$login&p2=$id");
        }
        echo json_encode(array('success' => 1, 'logins' => $game->RESULTS[2]->login ));
    }
    
}
?>
