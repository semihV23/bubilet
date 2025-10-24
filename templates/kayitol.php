<?php

require_once __DIR__ . "/../classes/User.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty($_POST["isim"]) or empty($_POST["eposta"]) or empty($_POST["parola"])){
        echo "Tüm alanlar doldurulmalıdır.";
        exit;
    }

    $user = new User();
    $result = $user->createUser($_POST["isim"], $_POST["eposta"], Role::USER, $_POST["parola"]);
    if(!$result){
        exit;
    }
    $user->setSession();
    header("Location: hesabim");
    exit;
}
?>

<html>
    <title>Kayıt Ol</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <body>
        <h1>Kayıt Ol</h1>
        <form action="" method="post">
            <div class="form-wrapper">
                <label for="isim">İsim Soyisim</label>
                <input type="text" name="isim">
                <label for="eposta">E-posta</label>
                <input type="email" name="eposta" id="kullanici-eposta">
                <label for="parola">Parola</label>
                <input type="password" name="parola" id="kullanici-pass">
                <input type="submit" value="Kayıt ol">
            </div>
        </form>
    </body>
</html>
