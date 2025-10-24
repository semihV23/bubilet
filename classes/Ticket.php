<?php

require_once __DIR__ . "/BookedSeat.php";
require_once __DIR__ . "/User.php";

/**
 * Bilet durumlarını temsil eden enum.
 */
enum TicketStatus: string
{
    case ACTIVE = "ACTIVE";
    case CANCELLED = "CANCELLED";
    case EXPIRED = "EXPIRED";
}

/**
 * Bir bileti temsil eden entity sınıfı.
 */
class Ticket
{
    private ?string $id = null;
    private ?string $tripId = null;
    private ?string $userId = null;
    private ?TicketStatus $status = null;
    private ?int $totalPrice = null;
    private ?DateTimeImmutable $createdAt = null;
    private ?int $seatNumber = null;

    public function __construct(
        ?string $id = null,
        ?string $tripId = null,
        ?string $userId = null,
        ?TicketStatus $status = null,
        ?int $totalPrice = null,
        ?DateTimeImmutable $createdAt = null,
        ?int $seatNumber = null
    ) {
        $this->id = $id;
        $this->tripId = $tripId;
        $this->userId = $userId;
        $this->status = $status;
        $this->totalPrice = $totalPrice;
        $this->createdAt = $createdAt;
        $this->seatNumber = $seatNumber;
    }

    // Getter'lar
    public function getId(): ?string
    {
        return $this->id;
    }

    public function getTripId(): ?string
    {
        return $this->tripId;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function getStatus(): ?TicketStatus
    {
        return $this->status;
    }

    public function getTotalPrice(): ?int
    {
        return $this->totalPrice;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getSeatNumber(): ?int
    {
        return $this->seatNumber;
    }

    // Setter'lar
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    public function setTripId(?string $tripId): void
    {
        $this->tripId = $tripId;
    }

    public function setUserId(?string $userId): void
    {
        $this->userId = $userId;
    }

    public function setStatus(?TicketStatus $status): void
    {
        $this->status = $status;
    }

    public function setTotalPrice(?int $totalPrice): void
    {
        $this->totalPrice = $totalPrice;
    }

    public function setCreatedAt(?DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setSeatNumber(?int $seatNumber): void
    {
        $this->seatNumber = $seatNumber;
    }
}

/**
 * Ticket veritabanı işlemleri için repository sınıfı.
 */
class TicketRepository
{
    private SQLite3 $db;

    public function __construct()
    {
        // Örnekteki gibi bir Database sınıfınızın olduğunu varsayıyoruz
        $this->db = new Database()->db;
    }

    /**
     * Veritabanı satırını bir Ticket nesnesine dönüştürür.
     */
    private function hydrateTicket(array $row): Ticket
    {
        $bookedSeatRepo = new BookedSeatRepository();
        return new Ticket(
            $row['id'],
            $row['trip_id'],
            $row['user_id'],
            TicketStatus::from($row['status']),
            (int)$row['total_price'],
            new DateTimeImmutable($row['created_at']),
            $bookedSeatRepo->findByTicketId($row['id'])
        );
    }

    /**
     * Veritabanına yeni bir bilet kaydı oluşturur.
     */
    public function create(Ticket $ticket): Ticket
    {
        $bookedSeatRepo = new BookedSeatRepository();
        $userRepo = new UserRepository();

        // ID yoksa otomatik oluştur
        if (!$ticket->getId()) {
            $ticket->setId(uniqid('tkt_', true));
        }

        $stmt = $this->db->prepare("
            INSERT INTO Tickets (
                id, trip_id, user_id, total_price
            ) VALUES (
                :id, :trip_id, :user_id, :total_price
            )
        ");

        $stmt->bindValue(':id', $ticket->getId(), SQLITE3_TEXT);
        $stmt->bindValue(':trip_id', $ticket->getTripId(), SQLITE3_TEXT);
        $stmt->bindValue(':user_id', $ticket->getUserId(), SQLITE3_TEXT);
        $stmt->bindValue(':total_price', $ticket->getTotalPrice(), SQLITE3_INTEGER);

        $result = $stmt->execute();

        if ($result === false) {
            throw new Exception("Bilet oluşturma işlemi başarısız oldu: " . $this->db->lastErrorMsg());
        }

        $bookedSeat = new BookedSeat(ticketId:$ticket->getId(), seatNumber:$ticket->getSeatNumber());
        $bookedSeatRepo->create($bookedSeat);
        
        $user = $userRepo->findById($ticket->getUserId());
        $user->setBalance($user->getBalance() - $ticket->getTotalPrice());
        $userRepo->update($user);
        
        return $ticket;
    }

    /**
     * ID'ye göre bir bilet bulur.
     */
    public function findById(string $id): ?Ticket
    {
        $stmt = $this->db->prepare("SELECT * FROM Tickets WHERE id = :id");
        $stmt->bindValue(':id', $id, SQLITE3_TEXT);
        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);

        return $row ? $this->hydrateTicket($row) : null;
    }

    /**
     * Belirli bir sefere (trip_id) ait tüm biletleri getirir.
     *
     * @param string $tripId
     * @return Ticket[]
     */
    public function findByTripId(string $tripId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM Tickets WHERE trip_id = :trip_id ORDER BY created_at DESC");
        $stmt->bindValue(':trip_id', $tripId, SQLITE3_TEXT);
        $result = $stmt->execute();
        
        $tickets = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $tickets[] = $this->hydrateTicket($row);
        }
        return $tickets;
    }

    /**
     * Belirli bir kullanıcıya (user_id) ait tüm biletleri getirir.
     *
     * @param string $userId
     * @return Ticket[]
     */
    public function findByUserId(string $userId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM Tickets WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmt->bindValue(':user_id', $userId, SQLITE3_TEXT);
        $result = $stmt->execute();
        
        $tickets = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $tickets[] = $this->hydrateTicket($row);
        }
        return $tickets;
    }

    /**
     * Bir biletin bilgilerini günceller.
     * (Genellikle sadece 'status' güncellenir, ancak örnek olması için diğer alanlar da eklendi)
     */
    public function update(Ticket $ticket): bool
    {
        $stmt = $this->db->prepare("
            UPDATE Tickets SET
                trip_id = :trip_id,
                user_id = :user_id,
                status = :status,
                total_price = :total_price
            WHERE id = :id
        ");

        $stmt->bindValue(':id', $ticket->getId(), SQLITE3_TEXT);
        $stmt->bindValue(':trip_id', $ticket->getTripId(), SQLITE3_TEXT);
        $stmt->bindValue(':user_id', $ticket->getUserId(), SQLITE3_TEXT);
        $stmt->bindValue(':status', $ticket->getStatus()->value, SQLITE3_TEXT);
        $stmt->bindValue(':total_price', $ticket->getTotalPrice(), SQLITE3_INTEGER);

        $result = $stmt->execute();

        if ($result === false) {
            throw new Exception("Bilet güncelleme işlemi başarısız oldu: " . $this->db->lastErrorMsg());
        }

        return true;
    }

    /**
     * Bir bileti ID'ye göre siler.
     * (Genellikle bilet silinmez, status 'CANCELLED' olarak güncellenir,
     * ancak TripRepository'de delete olduğu için buraya da eklendi.)
     */
    public function delete(string $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM Tickets WHERE id = :id");
        $stmt->bindValue(':id', $id, SQLITE3_TEXT);
        $result = $stmt->execute();

        if ($result === false) {
            throw new Exception("Bilet silme işlemi başarısız oldu: " . $this->db->lastErrorMsg());
        }

        return true;
    }
}