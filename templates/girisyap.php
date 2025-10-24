<?php
require_once __DIR__ . "/../utils/format.php";
require_once __DIR__ . "/../classes/User.php";

echo arrayToTable($_SESSION);

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty($_POST["eposta"]) or empty($_POST["parola"])){
        echo "Tüm alanlar doldurulmalıdır.";
        exit;
    }

    $user = new User();
    $result = $user->getUserByEmail($_POST["eposta"]);
    if(!$result){
        echo "Kullanıcı adı veya şifre hatalı. (E-posta buluanamdı.)";
        exit;
    }
    if(password_verify($_POST["parola"], $user->password)){
        $user->setSession();
    }else{
        echo "Kullanıcı adı veya şifre hatalı.";
    }
}

?>

<html>
    <title>Giriş Yap</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <body>
        <form action="" method="post">
            <h1>Giriş Yap</h1>
            <div class="form-wrapper">
                <label for="eposta">E-posta</label>
                <input type="email" name="eposta" id="kullanici-eposta">
                <label for="parola">Parola</label>
                <input type="password" name="parola" id="kullanici-pass">
                <input type="submit" value="Giriş yap">
            </div>
        </form>
    </body>
</html>