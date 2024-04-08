<?php

$user = file_get_contents("https://mysql.lavro.ru/call.php?db=311780&pname=ShowGames");
$user = json_decode($user);
if (isset($user->error[0])) {
    echo json_encode(array('success' => 0, 'error' => $user->error[0]));
}
else {
    echo json_encode(array('success' => 1, 'games' => $user->games));
}

    

?>