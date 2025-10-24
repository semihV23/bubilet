<?php
require_once __DIR__ . "/../utils/format.php";
require_once __DIR__ . "/../classes/User.php";

echo arrayToTable($_SESSION);

$user = new User();
$user->getUserByEmail($_SESSION["user_email"]);

?>

<html>
    <title>Hesabım</title>
    <body>
        <h1>Hesabım</h1>
        <div>
            <span><?php echo "İsim: " . $user->full_name ?></span>
        </div>
        <div>
            <span><?php echo "Rol: " . $user->role ?></span>
        </div>
    </body>
</html>