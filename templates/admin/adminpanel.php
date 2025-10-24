<?php
require_once __DIR__ . "/../../classes/Company.php";
require_once __DIR__ . "/../../utils/format.php";

$post_action = $_POST["action"] ?? false;
$get_action = $_GET["action"] ?? false;

$userRepo = new UserRepository();
$companyRepo = new CompanyRepository();

if($_SERVER["REQUEST_METHOD"] == "POST" and isset($_POST["action"])){
    switch ($_POST["action"]){
        case "kullanici-duzenle":
            if(empty($_POST["kullanici-id"]) or empty($_POST["kullanici-isim"]) or empty($_POST["kullanici-eposta"]) or empty($_POST["kullanici-rol"])){
                echo "Tüm alanlar doldurulmalıdır.";
                exit;
            }
            $kullanici_id = $_POST["kullanici-id"];
            $kullanici_isim = $_POST["kullanici-isim"];
            $kullanici_eposta = $_POST["kullanici-eposta"];
            try{
                $kullanici_rol = Role::from($_POST["kullanici-rol"]);
            } catch (Exception $e){
                print_r("Rol bulunamadı: " . $e);
            }
            $kullanici = $userRepo->findById($kullanici_id);
            $kullanici->setFullName($kullanici_isim);
            $kullanici->setEmail($kullanici_eposta);
            $kullanici->setRole($kullanici_rol);
            $result = $userRepo->update($kullanici);
        case "firma-admini-yetkilendir":
            if(empty($_POST["kullanici-id"]) or empty($_POST["firma-id"])){
                echo "Tüm alanlar doldurulmalıdır.";
                exit;
            }
            $firmaId = $_POST["firma-id"];
            $firmaRol = Role::COMPANY;
            if($firmaId == "temizle"){$firmaId = null; $firmaRol = Role::USER;}
            $kullanici = $userRepo->findById($_POST["kullanici-id"]);
            $kullanici->setCompanyId($firmaId);
            $kullanici->setRole($firmaRol);
            $result = $userRepo->update($kullanici);

    }

}

if($_SERVER["REQUEST_METHOD"] == "GET" and isset($_GET["action"])){
    switch($_GET["action"]){
        case "kullanici-sil":
            if(empty($_GET["kullanici-id"])){
                echo "Kullanıcı ID eksik.";
                exit;
            }
            $kullaniciId = $_GET["kullanici-id"];
            $userRepo->delete($kullaniciId);
            header("Location: /admin/panel");
        case "kullanici-duzenle":
            if(empty($_GET["kullanici-id"])){
                echo "Kullanıcı ID eksik.";
                exit;
            }
        case "firma-admini-yetkilendir":
            if(empty($_GET["kullanici-id"])){
                echo "Kullanıcı ID eksik.";
                exit;
            }
    }
}

?>

<html>
    <title>Admin Paneli</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <body>
        <h1>Admin Paneli</h1>

        <?php if($get_action == "kullanici-duzenle"){ ?>
        <?php $kullanici = $userRepo->findById($_GET["kullanici-id"]);?>
        <div>
            <h2>Kullanıcı Düzenle</h2>
            <form action="" method="post">
                <div class="form-wrapper">
                    <label for="kullanici-id">ID</label>
                    <input type="text" value="<?php print_r($kullanici->getId()) ?>" disabled>
                    <input type="hidden" name="kullanici-id" value="<?php print_r($kullanici->getId()) ?>">
                    <label for="kullanici-isim">Tam İsim</label>
                    <input type="text" name="kullanici-isim" value="<?php print_r($kullanici->getFullName()) ?>">
                    <label for="kullanici-eposta">E-Posta</label>
                    <input type="email" name="kullanici-eposta" value="<?php print_r($kullanici->getEmail()) ?>">
                    <label for="kullanici-rol">Rol</label>
                    <select name="kullanici-rol">
                        <?php
                            $selected = "";
                            foreach(Role::cases() as $rol){
                                if($rol->value == $kullanici->getRole()){$selected = "selected";}
                                else{$selected = "";}
                                echo "<option value='{$rol->value}' {$selected}>{$rol->value}</option>";
                            }
                        ?>
                    </select>
                    <input type="hidden" name="action" value="kullanici-duzenle">
                    <input type="submit" value="Güncelle">
                </div>
            </form>
        </div>
        <?php } ?>

        <?php if($get_action == "firma-admini-yetkilendir"){ ?>
        <?php $kullanici = $userRepo->findById($_GET["kullanici-id"]);?>
        <div>
            <h2>Firma Admini Yetkilendir</h2>
            <form action="" method="post">
                <div class="form-wrapper">
                    <label for="kullanici-id">ID</label>
                    <input type="text" value="<?php print_r($kullanici->getId()) ?>" disabled>
                    <input type="hidden" name="kullanici-id" value="<?php print_r($kullanici->getId()) ?>">
                    <label for="kullanici-isim">Tam İsim</label>
                    <input type="text" name="kullanici-isim" value="<?php print_r($kullanici->getFullName()) ?>">
                    <label for="kullanici-eposta">E-Posta</label>
                    <input type="email" name="kullanici-eposta" value="<?php print_r($kullanici->getEmail()) ?>">
                    <label for="firma-id">Firma</label>
                    <select name="firma-id">
                        <option value="temizle">Yok</option>
                    <?php foreach($companyRepo->findAll() as $company){ ?>
                        <option value="<?php echo $company->getId() ?>"><?php echo $company->getName() ?></option>
                    <?php } ?>
                    </select>
                    <input type="hidden" name="action" value="firma-admini-yetkilendir">
                    <input type="submit" value="Güncelle">
                </div>
            </form>
        </div>
        <?php } ?>

        <div>
            <h2>Firmalar</h2>
            <div class="table-wrapper" >
                <?php
                echo "<table>";
                echo "<thead>";
                echo "<tr>";
                echo "<th>ID</th>";
                echo "<th>Tam İsim</th>";
                echo "<th>E-Posta</th>";
                echo "<th>Rol</th>";
                echo "<th>Firma</th>";
                echo "<th>Bakiye</th>";
                echo "<th>Oluşuturulma Tarihi</th>";
                echo "<th>İşlem</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";

                foreach($userRepo->findAll() as $row){
                    $id = urlencode($row->getId());
                    echo "<tr>";
                    echo "<td>" . $row->getId() . "</td>";
                    echo "<td>" . $row->getFullName() . "</td>";
                    echo "<td>" . $row->getEmail() . "</td>";
                    echo "<td>" . $row->getRole() . "</td>";
                    echo "<td>" . $companyRepo->findById($row->getCompanyId())->getName() . "</td>";
                    echo "<td>" . $row->getBalance() . "</td>";
                    echo "<td>" . $row->getCreatedAt()->format('d.m.Y H:i') . "</td>";
                    echo "<td>";
                    echo "<a href='?action=kullanici-sil&kullanici-id={$id}'>[Sil]</a>";
                    echo "<a href='?action=kullanici-duzenle&kullanici-id={$id}'>[Düzenle]</a>";
                    echo "<a href='?action=firma-admini-yetkilendir&kullanici-id={$id}'>[Firma Yetkilisi Yap]</a>";
                    echo "</td>";
                    echo "</tr>";
                }

                echo "</tbody>";
                echo "</table>";
                ?>
            </div>
        </div>
    </body>
</html>