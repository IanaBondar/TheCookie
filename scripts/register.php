<?php
$l = $_POST["regL"];
$p = $_POST["regP"];
$user = file_get_contents("https://mysql.lavro.ru/call.php?db=311780&pname=RegisterUser&p1=$l&p2=$p");
$user = json_decode($user);

$token = $user->token[0];

if (isset($user->error[0])) {
    $err = $user->error[0];
    echo json_encode(array('success' => 0, 'error' => $err));
}
else {
    setcookie("login", $l, time() + 3600, '/~s311780/TheCookie');
    setcookie("token", $token, time() + 3600, '/~s311780/TheCookie');
    echo json_encode(array('success' => 1));
}

?>