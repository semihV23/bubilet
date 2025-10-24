<?php

class BookedSeat
{
    private ?string $id = null;
    private ?string $ticketId = null;
    private ?int $seatNumber = null;
    private ?DateTimeImmutable $createdAt = null;

    public function __construct(
        ?string $id = null,
        ?string $ticketId = null,
        ?int $seatNumber = null,
        ?DateTimeImmutable $createdAt = null
    ) {
        $this->id = $id;
        $this->ticketId = $ticketId;
        $this->seatNumber = $seatNumber;
        $this->createdAt = $createdAt;
    }

    // Getter'lar
    public function getId(): ?string
    {
        return $this->id;
    }

    public function getTicketId(): ?string
    {
        return $this->ticketId;
    }

    public function getSeatNumber(): ?int
    {
        return $this->seatNumber;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    // Setter'lar
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    public function setTicketId(?string $ticketId): void
    {
        $this->ticketId = $ticketId;
    }

    public function setSeatNumber(?int $seatNumber): void
    {
        $this->seatNumber = $seatNumber;
    }

    public function setCreatedAt(?DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}

class BookedSeatRepository
{
    private SQLite3 $db;

    public function __construct()
    {
        $this->db = new Database()->db;
    }

    private function hydrateBookedSeat(array $row): BookedSeat
    {
        return new BookedSeat(
            $row['id'],
            $row['ticket_id'],
            (int)$row['seat_number'],
            new DateTimeImmutable($row['created_at'])
        );
    }

    public function create(BookedSeat $seat): BookedSeat
    {
        // ID yoksa otomatik oluştur
        if (!$seat->getId()) {
            $seat->setId(uniqid('seat_', true));
        }

        $stmt = $this->db->prepare("
            INSERT INTO Booked_Seats (
                id, ticket_id, seat_number
            ) VALUES (
                :id, :ticket_id, :seat_number
            )
        ");

        $stmt->bindValue(':id', $seat->getId(), SQLITE3_TEXT);
        $stmt->bindValue(':ticket_id', $seat->getTicketId(), SQLITE3_TEXT);
        $stmt->bindValue(':seat_number', $seat->getSeatNumber(), SQLITE3_INTEGER);

        $result = $stmt->execute();

        if ($result === false) {
            throw new Exception("Koltuk kaydı oluşturma işlemi başarısız oldu: " . $this->db->lastErrorMsg());
        }

        return $seat;
    }

    public function findById(string $id): ?BookedSeat
    {
        $stmt = $this->db->prepare("SELECT * FROM Booked_Seats WHERE id = :id");
        $stmt->bindValue(':id', $id, SQLITE3_TEXT);
        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);

        return $row ? $this->hydrateBookedSeat($row) : null;
    }

    /**
     * @param string $ticketId
     * @return int
     */
    public function findByTicketId(string $ticketId): int
    {
        $stmt = $this->db->prepare("SELECT * FROM Booked_Seats WHERE ticket_id = :ticket_id");
        $stmt->bindValue(':ticket_id', $ticketId, SQLITE3_TEXT);
        $result = $stmt->execute();
        
        // $seats = [];
        // while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        //     $seats[] = $this->hydrateBookedSeat($row);
        // }
        // return $seats;

        echo "koltuk";
        print_r($result->fetchArray(SQLITE3_ASSOC)["seat_number"]);

        return $result->fetchArray(SQLITE3_ASSOC)["seat_number"];
    }

    public function update(BookedSeat $seat): bool
    {
        $stmt = $this->db->prepare("
            UPDATE Booked_Seats SET
                ticket_id = :ticket_id,
                seat_number = :seat_number
            WHERE id = :id
        ");

        $stmt->bindValue(':id', $seat->getId(), SQLITE3_TEXT);
        $stmt->bindValue(':ticket_id', $seat->getTicketId(), SQLITE3_TEXT);
        $stmt->bindValue(':seat_number', $seat->getSeatNumber(), SQLITE3_INTEGER);

        $result = $stmt->execute();

        if ($result === false) {
            throw new Exception("Koltuk kaydı güncelleme işlemi başarısız oldu: " . $this->db->lastErrorMsg());
        }

        return true;
    }

    public function delete(string $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM Booked_Seats WHERE id = :id");
        $stmt->bindValue(':id', $id, SQLITE3_TEXT);
        $result = $stmt->execute();

        if ($result === false) {
            throw new Exception("Koltuk kaydı silme işlemi başarısız oldu: " . $this->db->lastErrorMsg());
        }

        return true;
    }

    public function deleteByTicketId(string $ticketId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM Booked_Seats WHERE ticket_id = :ticket_id");
        $stmt->bindValue(':ticket_id', $ticketId, SQLITE3_TEXT);
        $result = $stmt->execute();

        if ($result === false) {
            throw new Exception("Bilete ait koltuk kayıtlarını silme işlemi başarısız oldu: " . $this->db->lastErrorMsg());
        }

        return $this->db->changes() > 0;
    }
}