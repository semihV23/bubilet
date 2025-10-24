<?php

require_once __DIR__ . "/../classes/User.php";


$userRepo = new UserRepository();
$user = $userRepo->findById($_SESSION["user_id"]);

?>

<div>
    <p><?= $user->getFullName()?></p>
    <p>Bakiye: <?= $user->getBalance()?> TL</p>
</div>