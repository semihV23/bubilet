<?php
require_once __DIR__ . "/../../classes/Company.php";
require_once __DIR__ . "/../../utils/format.php";

// $repo = new CompanyRepository();
// $firma = new Company("Akhisar Turizm");
// $firma = $repo->create($firma);
// print_r($firma);

$post_action = $_POST["action"] ?? false;
$get_action = $_GET["action"] ?? false;

$repo = new CompanyRepository();

if($_SERVER["REQUEST_METHOD"] == "POST" and isset($_POST["action"])){
    switch ($_POST["action"]){
        case "firma-olustur":
            if(empty($_POST["firma-ismi"])){
                echo "Tüm alanlar doldurulmalıdır.";
                exit;
            }
            $firma_ismi = $_POST["firma-ismi"];
            $repo = new CompanyRepository();
            $firma = new Company($firma_ismi);
            $result = $repo->create($firma);
            if($result === false){
                echo "Firma oluşturulamadı.";
                exit;
            }
            header("Location: /admin/firmakontrol");
        
        case "firma-duzenle":
            if(empty($_POST["firma-id"]) or empty($_POST["firma-ismi"])){
                echo "Tüm alanlar doldurulmalıdır.";
                exit;
            }
            $firma_ismi = $_POST["firma-ismi"];
            $firma_id = $_POST["firma-id"];
            $firma = new Company($firma_ismi, $firma_id);
            $repo->update($firma);
    }

}

if($_SERVER["REQUEST_METHOD"] == "GET" and isset($_GET["action"])){
    switch($_GET["action"]){
        case "firma-sil":
            if(empty($_GET["firma-id"])){
                echo "Firma ID eksik.";
                exit;
            }
            $firmaId = $_GET["firma-id"];
            $repo->delete($firmaId);
            header("Location: /admin/firmakontrol");
        case "firma-duzenle":
            if(empty($_GET["firma-id"])){
                echo "Firma ID eksik.";
                exit;
            }
    }
}
?>

<html>
    <title>Firma Kontrol</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <body>
        <h1>Firma Kontrol</h1>

        <?php if(!$get_action){ ?>
        <div>
            <h2>Firma Oluştur</h2>
            <form action="" method="post">
                <div class="form-wrapper">
                    <label for="firma-ismi">Firma İsmi</label>
                    <input type="text" name="firma-ismi">
                    <input type="hidden" name="action" value="firma-olustur">
                    <button type="submit">Firma oluştur</button>
                </div>
            </form>
        </div>
        <?php } ?>

        <?php if($get_action == "firma-duzenle"){ ?>
        <?php $firma = $repo->findById($_GET["firma-id"]); ?>
        <div>
            <h2>Firma Düzenle</h2>
            <form action="" method="post">
                <div class="form-wrapper">
                    <label for="firma-id">Firma ID</label>
                    <input type="text" value="<?php print_r($firma["id"]); ?>" disabled>
                    <input type="hidden" name="firma-id" value="<?php print_r($firma["id"]); ?>">
                    <label for="firma-ismi">Firma İsmi</label>
                    <input type="text" name="firma-ismi" value="<?php print_r($firma["name"]) ?>">
                    <input type="hidden" name="action" value="firma-duzenle">
                    <button type="submit">Güncelle</button>
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
                echo "<th>Firma İsmi</th>";
                echo "<th>Oluşturulma Tarihi</th>";
                echo "<th>İşlem</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";

                foreach($repo->findAll() as $row){
                    $id = urlencode($row["id"]);
                    echo "<tr>";
                    echo "<td>{$row["name"]}</td>";
                    echo "<td>{$row["created_at"]}</td>";
                    echo "<td><a href='?action=firma-sil&firma-id={$id}'>[Sil]</a> <a href='?action=firma-duzenle&firma-id={$id}'>[Düzenle]</a></td>";
                    echo "</tr>";
                }

                echo "</tbody>";
                echo "</table>";
                ?>
            </div>
        </div>
    </body>
</html>