<?php
require_once __DIR__ . "/../../classes/Trip.php";
require_once __DIR__ . "/../../classes/User.php";
require_once __DIR__ . "/../../classes/Company.php";
require_once __DIR__ . "/../../utils/format.php";

$post_action = $_POST["action"] ?? false;
$get_action = $_GET["action"] ?? false;

$tripRepo = new TripRepository();
$companyRepo = new CompanyRepository();
$userRepo = new UserRepository();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {
    switch ($_POST["action"]) {
        case "bilet-satin-al":
            
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["action"])) {
    switch ($_GET["action"]) {
        case "bilet-satin-al":
            if (empty($_GET["id"])) {
                echo "Sefer ID eksik.";
                exit;
            }
    }
}

?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <title>Bilet Al</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
    <h1>Bilet Al</h1>

    <!-- Sefer Oluşturma Formu -->
    <?php if ($get_action !== "bilet-al"): ?>
        <?php $sefer = $tripRepo->findById($_GET["id"]) ?>
        <div>
            <h2>Sefer Bilgisi</h2>
            <div class="form-wrapper">
                <label for="company-name">Firma</label>
                <input type="text" name="company-name" value="<?= $companyRepo->findById($sefer->getCompanyId())->getName() ?>" disabled>

                <label for="departure_city">Kalkış Şehri</label>
                <input type="text" value="<?= $sefer->getDepartureCity()->value ?>" disabled>

                <label for="destination_city">Varış Şehri</label>
                <input type="text" value="<?= $sefer->getDestinationCity()->value ?>" disabled>

                <label for="departure_time">Kalkış Zamanı</label>
                <input type="datetime-local" name="departure_time" value="<?= $sefer->getDepartureTime()->format('Y-m-d H:i:s') ?>" disabled>

                <label for="arrival_time">Varış Zamanı</label>
                <input type="datetime-local" name="arrival_time" value="<?= $sefer->getArrivalTime()->format('Y-m-d H:i:s') ?>" disabled>

                <label for="price">Fiyat (TL)</label>
                <input type="number" name="price" min="1" value="<?= $sefer->getPrice() ?>" disabled>

                <label for="capacity">Kapasite</label>
                <input type="number" name="capacity" min="1" max="100" value="<?= $sefer->getCapacity() ?>" disabled>

                <h2>Koltuk Seçimi</h2>
                <?php print_r($tripRepo->getBookedSeats($sefer->getId())) ?>

                <input type="hidden" name="action" value="bilet-satin-al">
                <form action="" method="post">
                    <div class="form-wrapper">
                        <label>Koltuk Numarası</label>
                        <select name="koltuk-no">
                            <?php for ($i = 1; $i < $sefer->getCapacity() + 1; $i++): ?>
                                <option value="<?= $i ?>"><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                        <input type="submit" value="Satın Al">
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <!-- Sefer Listesi -->
    <div>
        <h2>Tüm Seferler</h2>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Firma</th>
                        <th>Kalkış</th>
                        <th>Varış</th>
                        <th>Kalkış Zamanı</th>
                        <th>Varış Zamanı</th>
                        <th>Fiyat</th>
                        <th>Kapasite</th>
                        <th>Oluşturulma</th>
                        <th>İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tripRepo->findAll() as $trip): ?>
                        <?php
                        $company = $companyRepo->findById($trip->getCompanyId());
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($company->getName()) ?></td>
                            <td><?= htmlspecialchars($trip->getDepartureCity()->value) ?></td>
                            <td><?= htmlspecialchars($trip->getDestinationCity()->value) ?></td>
                            <td><?= $trip->getDepartureTime()->format('d.m.Y H:i') ?></td>
                            <td><?= $trip->getArrivalTime()->format('d.m.Y H:i') ?></td>
                            <td><?= number_format($trip->getPrice()) ?> TL</td>
                            <td><?= $trip->getCapacity() ?> kişi</td>
                            <td><?= $trip->getCreatedAt()->format('d.m.Y H:i') ?></td>
                            <td>
                                <a href="?action=bilet-satin-al&id=<?= urlencode($trip->getId()) ?>">[Satın Al]</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>