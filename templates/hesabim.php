<?php
require_once __DIR__ . "/../utils/format.php";
require_once __DIR__ . "/../classes/User.php";
require_once __DIR__ . "/../classes/Ticket.php";
require_once __DIR__ . "/../classes/Trip.php";

echo arrayToTable($_SESSION);

$userRepo = new UserRepository();
$user = $userRepo->findById($_SESSION["user_id"]);

$ticketRepo = new TicketRepository();
$tripRepo = new TripRepository();

?>

<?php require_once __DIR__ . "/../snippets/header.php" ?>

<html>
    <title>Hesabım</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <body>
        <h1>Hesabım</h1>
        <div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Kalkış Zamanı</th>
                            <th>Varış Zamanı</th>
                            <th>Kalkış Şehri</th>
                            <th>Varış Şehri</th>
                            <th>Koltuk Numarası</th>
                            <th>Fiyat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($ticketRepo->findByUserId($_SESSION["user_id"]) as $bilet): ?>
                            <?php $sefer = $tripRepo->findById($bilet->getTripId()) ?>
                            <tr>
                                <td><?= $sefer->getDepartureTime()->format('d.m.Y H:i') ?></td>
                                <td><?= $sefer->getArrivalTime()->format('d.m.Y H:i') ?></td>
                                <td><?= $sefer->getDepartureCity()->value ?></td>
                                <td><?= $sefer->getDestinationCity()->value ?></td>
                                <td><?= $bilet->getSeatNumber() ?></td>
                                <td><?= $bilet->getTotalPrice() ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>