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
        case "sefer-olustur":
            if (
                empty($_POST["departure_city"]) ||
                empty($_POST["destination_city"]) ||
                empty($_POST["departure_time"]) ||
                empty($_POST["arrival_time"]) ||
                empty($_POST["price"]) ||
                empty($_POST["capacity"])
            ) {
                print_r($_POST);
                echo "Tüm alanlar doldurulmalıdır.";
                exit;
            }

            $trip = new Trip(
                id: null,
                companyId: $userRepo->findById($_SESSION["user_id"])->getCompanyId(),
                departureCity: City::from($_POST["departure_city"]),
                destinationCity: City::from($_POST["destination_city"]),
                departureTime: new DateTimeImmutable($_POST["departure_time"]),
                arrivalTime: new DateTimeImmutable($_POST["arrival_time"]),
                price: (int)$_POST["price"],
                capacity: (int)$_POST["capacity"]
            );

            try {
                $tripRepo->create($trip);
                header("Location: /firma/seferler");
                exit;
            } catch (Exception $e) {
                echo "Sefer oluşturulamadı: " . $e->getMessage();
            }
            break;

        case "sefer-duzenle":
            if (
                empty($_POST["id"]) ||
                empty($_POST["company_id"]) ||
                empty($_POST["departure_city"]) ||
                empty($_POST["destination_city"]) ||
                empty($_POST["departure_time"]) ||
                empty($_POST["arrival_time"]) ||
                empty($_POST["price"]) ||
                empty($_POST["capacity"])
            ) {
                echo "Tüm alanlar doldurulmalıdır.";
                exit;
            }

            $trip = $tripRepo->findById($_POST["id"]);
            if (!$trip) {
                echo "Sefer bulunamadı.";
                exit;
            }

            $trip->setCompanyId($_POST["company_id"]);
            $trip->setDepartureCity(City::from($_POST["departure_city"]));
            $trip->setDestinationCity(City::from($_POST["destination_city"]));
            $trip->setDepartureTime(new DateTimeImmutable($_POST["departure_time"]));
            $trip->setArrivalTime(new DateTimeImmutable($_POST["arrival_time"]));
            $trip->setPrice((int)$_POST["price"]);
            $trip->setCapacity((int)$_POST["capacity"]);

            try {
                $tripRepo->update($trip);
                header("Location: /firma/seferler");
                exit;
            } catch (Exception $e) {
                echo "Sefer güncellenemedi: " . $e->getMessage();
            }
            break;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["action"])) {
    switch ($_GET["action"]) {
        case "sefer-sil":
            if (empty($_GET["id"])) {
                echo "Sefer ID eksik.";
                exit;
            }
            try {
                $tripRepo->delete($_GET["id"]);
                header("Location: /admin/trips");
                exit;
            } catch (Exception $e) {
                echo "Sefer silinemedi: " . $e->getMessage();
            }
            break;

        case "sefer-duzenle":
            if (empty($_GET["id"])) {
                echo "Sefer ID eksik.";
                exit;
            }
            break;
    }
}

?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <title>Sefer Yönetimi</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
    <h1>Sefer Yönetimi</h1>

    <!-- Sefer Oluşturma Formu -->
    <?php if ($get_action !== "sefer-duzenle"): ?>
        <div>
            <h2>Yeni Sefer Ekle</h2>
            <form action="" method="post">
                <div class="form-wrapper">
                    <label for="company-name">Firma</label>
                    <input type="text" name="company-name" value="<?= $companyRepo->findById($userRepo->findById($_SESSION["user_id"])->getCompanyId())->getName() ?>" disabled>

                    <label for="departure_city">Kalkış Şehri</label>
                    <select name="departure_city" required>
                        <option value="">-- Şehir Seç --</option>
                        <?php foreach (City::cases() as $city): ?>
                            <option value="<?= htmlspecialchars($city->value) ?>">
                                <?= htmlspecialchars($city->value) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label for="destination_city">Varış Şehri</label>
                    <select name="destination_city" required>
                        <option value="">-- Şehir Seç --</option>
                        <?php foreach (City::cases() as $city): ?>
                            <option value="<?= htmlspecialchars($city->value) ?>">
                                <?= htmlspecialchars($city->value) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label for="departure_time">Kalkış Zamanı</label>
                    <input type="datetime-local" name="departure_time" required>

                    <label for="arrival_time">Varış Zamanı</label>
                    <input type="datetime-local" name="arrival_time" required>

                    <label for="price">Fiyat (TL)</label>
                    <input type="number" name="price" min="1" required>

                    <label for="capacity">Kapasite</label>
                    <input type="number" name="capacity" min="1" max="100" required>

                    <input type="hidden" name="action" value="sefer-olustur">
                    <input type="submit" value="Sefer Oluştur">
                </div>
            </form>
        </div>
    <?php endif; ?>

    <!-- Sefer Düzenleme Formu -->
    <?php if ($get_action === "sefer-duzenle"): ?>
        <?php $trip = $tripRepo->findById($_GET["id"]); ?>
        <?php if (!$trip): ?>
            <p style="color:red;">Sefer bulunamadı.</p>
        <?php else: ?>
            <div>
                <h2>Sefer Düzenle</h2>
                <form action="" method="post">
                    <div class="form-wrapper">
                        <label for="id">ID</label>
                        <input type="text" value="<?= htmlspecialchars($trip->getId()) ?>" disabled>
                        <input type="hidden" name="id" value="<?= htmlspecialchars($trip->getId()) ?>">

                        <label for="company_id">Firma</label>
                        <select name="company_id" required>
                            <option value="">-- Firma Seç --</option>
                            <?php foreach ($companyRepo->findAll() as $company): ?>
                                <option value="<?= htmlspecialchars($company->getId()) ?>"
                                    <?= $trip->getCompanyId() === $company->getId() ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($company->getName()) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <label for="departure_city">Kalkış Şehri</label>
                        <select name="departure_city" id="departure_city" required>
                            <option value="">-- Şehir Seç --</option>
                            <?php foreach (City::cases() as $city): ?>
                                <option value="<?= htmlspecialchars($city->value) ?>"
                                    <?= $trip->getDepartureCity()->value === $city->value ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($city->value) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <label for="destination_city">Varış Şehri</label>
                        <select name="destination_city" id="destination_city" required>
                            <option value="">-- Şehir Seç --</option>
                            <?php foreach (City::cases() as $city): ?>
                                <option value="<?= htmlspecialchars($city->value) ?>"
                                    <?= $trip->getDestinationCity()->value === $city->value ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($city->value) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <label for="departure_time">Kalkış Zamanı</label>
                        <input type="datetime-local" name="departure_time" value="<?= $trip->getDepartureTime()->format('Y-m-d H:i:s') ?>" required>

                        <label for="arrival_time">Varış Zamanı</label>
                        <input type="datetime-local" name="arrival_time" value="<?= $trip->getArrivalTime()->format('Y-m-d H:i:s') ?>" required>

                        <label for="price">Fiyat (TL)</label>
                        <input type="number" name="price" min="1" value="<?= $trip->getPrice() ?>" required>

                        <label for="capacity">Kapasite</label>
                        <input type="number" name="capacity" min="1" max="100" value="<?= $trip->getCapacity() ?>" required>

                        <input type="hidden" name="action" value="sefer-duzenle">
                        <input type="submit" value="Güncelle">
                    </div>
                </form>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Sefer Listesi -->
    <div>
        <h2>Tüm Seferler</h2>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
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
                            <td><?= htmlspecialchars($trip->getId()) ?></td>
                            <td><?= htmlspecialchars($company->getName()) ?></td>
                            <td><?= htmlspecialchars($trip->getDepartureCity()->value) ?></td>
                            <td><?= htmlspecialchars($trip->getDestinationCity()->value) ?></td>
                            <td><?= $trip->getDepartureTime()->format('d.m.Y H:i') ?></td>
                            <td><?= $trip->getArrivalTime()->format('d.m.Y H:i') ?></td>
                            <td><?= number_format($trip->getPrice()) ?> TL</td>
                            <td><?= $trip->getCapacity() ?> kişi</td>
                            <td><?= $trip->getCreatedAt()->format('d.m.Y H:i') ?></td>
                            <td>
                                <a href="?action=sefer-duzenle&id=<?= urlencode($trip->getId()) ?>">[Düzenle]</a>
                                <a href="?action=sefer-sil&id=<?= urlencode($trip->getId()) ?>"
                                    onclick="return confirm('Bu seferi silmek istediğinizden emin misiniz?')">[Sil]</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>