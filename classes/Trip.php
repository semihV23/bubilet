<?php

enum City: string {
    case ADANA = "Adana";
    case ADIYAMAN = "Adıyaman";
    case AFYONKARAHISAR = "Afyonkarahisar";
    case AGRI = "Ağrı";
    case AMASYA = "Amasya";
    case ANKARA = "Ankara";
    case ANTALYA = "Antalya";
    case ARTVIN = "Artvin";
    case AYDIN = "Aydın";
    case BALIKESIR = "Balıkesir";
    case BILECIK = "Bilecik";
    case BINGOL = "Bingöl";
    case BITLIS = "Bitlis";
    case BOLU = "Bolu";
    case BURDUR = "Burdur";
    case BURSA = "Bursa";
    case CANAKKALE = "Çanakkale";
    case CANKIRI = "Çankırı";
    case CORUM = "Çorum";
    case DENIZLI = "Denizli";
    case DIYARBAKIR = "Diyarbakır";
    case EDIRNE = "Edirne";
    case ELAZIG = "Elazığ";
    case ERZINCAN = "Erzincan";
    case ERZURUM = "Erzurum";
    case ESKISEHIR = "Eskişehir";
    case GAZIANTEP = "Gaziantep";
    case GIRESUN = "Giresun";
    case GUMUSHANE = "Gümüşhane";
    case HAKKARI = "Hakkari";
    case HATAY = "Hatay";
    case ISPARTA = "Isparta";
    case MERSIN = "Mersin";
    case ISTANBUL = "İstanbul";
    case IZMIR = "İzmir";
    case KARS = "Kars";
    case KASTAMONU = "Kastamonu";
    case KAYSERI = "Kayseri";
    case KIRKLARELI = "Kırklareli";
    case KIRSEHIR = "Kırşehir";
    case KOCAELI = "Kocaeli";
    case KONYA = "Konya";
    case KUTAHYA = "Kütahya";
    case MALATYA = "Malatya";
    case MANISA = "Manisa";
    case KAHRAMANMARAS = "Kahramanmaraş";
    case MARDIN = "Mardin";
    case MUGLA = "Muğla";
    case MUS = "Muş";
    case NEVSEHIR = "Nevşehir";
    case NIGDE = "Niğde";
    case ORDU = "Ordu";
    case RIZE = "Rize";
    case SAKARYA = "Sakarya";
    case SAMSUN = "Samsun";
    case SIIRT = "Siirt";
    case SINOP = "Sinop";
    case SIVAS = "Sivas";
    case TEKIRDAG = "Tekirdağ";
    case TOKAT = "Tokat";
    case TRABZON = "Trabzon";
    case TUNCELI = "Tunceli";
    case SANLIURFA = "Şanlıurfa";
    case USAK = "Uşak";
    case VAN = "Van";
    case YOZGAT = "Yozgat";
    case ZONGULDAK = "Zonguldak";
    case AKSARAY = "Aksaray";
    case BAYBURT = "Bayburt";
    case KARAMAN = "Karaman";
    case KIRIKKALE = "Kırıkkale";
    case BATMAN = "Batman";
    case SIRNAK = "Şırnak";
    case BARTIN = "Bartın";
    case ARDAHAN = "Ardahan";
    case IGDIR = "Iğdır";
    case YALOVA = "Yalova";
    case KARABUK = "Karabük";
    case KILIS = "Kilis";
    case OSMANIYE = "Osmaniye";
    case DUZCE = "Düzce";
}

class Trip
{
    private ?string $id = null;
    private ?string $companyId = null;
    private ?City $destinationCity = null;
    private ?DateTimeImmutable $arrivalTime = null;
    private ?DateTimeImmutable $departureTime = null;
    private ?City $departureCity = null;
    private ?int $price = null;
    private ?int $capacity = null;
    private ?DateTimeImmutable $createdAt = null;

    public function __construct(
        ?string $id = null,
        ?string $companyId = null,
        ?City $destinationCity = null,
        ?DateTimeImmutable $arrivalTime = null,
        ?DateTimeImmutable $departureTime = null,
        ?City $departureCity = null,
        ?int $price = null,
        ?int $capacity = null,
        ?DateTimeImmutable $createdAt = null
    ) {
        $this->id = $id;
        $this->companyId = $companyId;
        $this->destinationCity = $destinationCity;
        $this->arrivalTime = $arrivalTime;
        $this->departureTime = $departureTime;
        $this->departureCity = $departureCity;
        $this->price = $price;
        $this->capacity = $capacity;
        $this->createdAt = $createdAt;
    }

    // Getter'lar (tüm değişkenler nullable olduğu için dönüş tipi ? ile belirtilir)

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getCompanyId(): ?string
    {
        return $this->companyId;
    }

    public function getDestinationCity(): ?City
    {
        return $this->destinationCity;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getArrivalTime(): ?DateTimeImmutable
    {
        return $this->arrivalTime;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getDepartureTime(): ?DateTimeImmutable
    {
        return $this->departureTime;
    }

    public function getDepartureCity(): ?City
    {
        return $this->departureCity;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    // Setter'lar

    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    public function setCompanyId(?string $companyId): void
    {
        $this->companyId = $companyId;
    }

    public function setDestinationCity(?City $destinationCity): void
    {
        $this->destinationCity = $destinationCity;
    }

    public function setArrivalTime(?DateTimeImmutable $arrivalTime): void
    {
        $this->arrivalTime = $arrivalTime;
    }

    public function setDepartureTime(?DateTimeImmutable $departureTime): void
    {
        $this->departureTime = $departureTime;
    }

    public function setDepartureCity(?City $departureCity): void
    {
        $this->departureCity = $departureCity;
    }

    public function setPrice(?int $price): void
    {
        $this->price = $price;
    }

    public function setCapacity(?int $capacity): void
    {
        $this->capacity = $capacity;
    }

    public function setCreatedAt(?DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}

class TripRepository
{
    private SQLite3 $db;

    public function __construct()
    {
        $this->db = new Database()->db;
    }

    private function hydrateTrip(array $row): Trip
    {
        return new Trip(
            $row['id'],
            $row['company_id'],
            City::from($row['destination_city']),
            new DateTimeImmutable($row['arrival_time']),
            new DateTimeImmutable($row['departure_time']),
            City::from($row['departure_city']),
            (int)$row['price'],
            (int)$row['capacity'],
            new DateTimeImmutable($row['created_at'])
        );
    }

    public function create(Trip $trip): Trip
    {
        // ID yoksa otomatik oluştur
        if (!$trip->getId()) {
            $trip->setId(uniqid('trip_', true));
        }

        $stmt = $this->db->prepare("
            INSERT INTO Trips (
                id, company_id, destination_city, arrival_time, 
                departure_time, departure_city, price, capacity
            ) VALUES (
                :id, :company_id, :destination_city, :arrival_time,
                :departure_time, :departure_city, :price, :capacity
            )
        ");

        $stmt->bindValue(':id', $trip->getId(), SQLITE3_TEXT);
        $stmt->bindValue(':company_id', $trip->getCompanyId(), SQLITE3_TEXT);
        $stmt->bindValue(':destination_city', $trip->getDestinationCity()->value, SQLITE3_TEXT);
        $stmt->bindValue(':arrival_time', $trip->getArrivalTime()->format('Y-m-d H:i:s'), SQLITE3_TEXT);
        $stmt->bindValue(':departure_time', $trip->getDepartureTime()->format('Y-m-d H:i:s'), SQLITE3_TEXT);
        $stmt->bindValue(':departure_city', $trip->getDepartureCity()->value, SQLITE3_TEXT);
        $stmt->bindValue(':price', $trip->getPrice(), SQLITE3_INTEGER);
        $stmt->bindValue(':capacity', $trip->getCapacity(), SQLITE3_INTEGER);

        $result = $stmt->execute();

        if ($result === false) {
            throw new Exception("Sefer oluşturma işlemi başarısız oldu.");
        }

        return $trip;
    }

    public function findById(string $id): ?Trip
    {
        $stmt = $this->db->prepare("SELECT * FROM Trips WHERE id = :id");
        $stmt->bindValue(':id', $id, SQLITE3_TEXT);
        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);

        return $row ? $this->hydrateTrip($row) : null;
    }

    /**
     * @return Trip[]
     */
    public function findAll(): array
    {
        $result = $this->db->query("SELECT * FROM Trips ORDER BY created_at DESC");
        $trips = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $trips[] = $this->hydrateTrip($row);
        }
        return $trips;
    }

    /**
     * Şirket ID'sine göre seferleri getirir
     *
     * @param string $companyId
     * @return Trip[]
     */
    public function findByCompanyId(string $companyId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM Trips WHERE company_id = :company_id ORDER BY departure_time ASC");
        $stmt->bindValue(':company_id', $companyId, SQLITE3_TEXT);
        $result = $stmt->execute();
        $trips = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $trips[] = $this->hydrateTrip($row);
        }
        return $trips;
    }

    /**
     * Kalkış ve varış şehirlerine göre arama
     *
     * @param string $departureCity
     * @param string $destinationCity
     * @return Trip[]
     */
    public function findByRoute(string $departureCity, string $destinationCity): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM Trips 
            WHERE departure_city = :departure_city 
              AND destination_city = :destination_city 
            ORDER BY departure_time ASC
        ");
        $stmt->bindValue(':departure_city', $departureCity, SQLITE3_TEXT);
        $stmt->bindValue(':destination_city', $destinationCity, SQLITE3_TEXT);
        $result = $stmt->execute();
        $trips = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $trips[] = $this->hydrateTrip($row);
        }
        return $trips;
    }

/**
     * Belirli bir sefere ait, aktif biletlerdeki rezerve edilmiş
     * koltuk numaralarının bir listesini döndürür.
     *
     * @param string $tripId Seferin ID'si
     * @return int[] Satın alınmış koltuk numaraları dizisi
     * @throws Exception Sorgu başarısız olursa
     */
    public function getBookedSeats(string $tripId): array
    {
        // Booked_Seats tablosunu Tickets tablosu ile birleştiriyoruz (INNER JOIN).
        // Sadece ilgili sefere (t.trip_id = :trip_id) ait olan VE
        // statüsü 'ACTIVE' olan biletlere bağlı koltuk numaralarını (bs.seat_number) seçiyoruz.
        // 'ACTIVE' kontrolü, iptal edilen biletlerdeki koltukların dolu görünmemesi için önemlidir.
        $stmt = $this->db->prepare("
            SELECT bs.seat_number
            FROM Booked_Seats AS bs
            INNER JOIN Tickets AS t ON bs.ticket_id = t.id
            WHERE t.trip_id = :trip_id AND t.status = 'ACTIVE'
        ");

        if ($stmt === false) {
            throw new Exception("SQL sorgusu hazırlanamadı: " . $this->db->lastErrorMsg());
        }

        $stmt->bindValue(':trip_id', $tripId, SQLITE3_TEXT);
        $result = $stmt->execute();

        if ($result === false) {
            throw new Exception("Koltukları getirme sorgusu başarısız oldu: " . $this->db->lastErrorMsg());
        }

        $seats = [];
        // fetchArray(SQLITE3_ASSOC) ile tüm sonuçları döngüye alıyoruz.
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            // Gelen her bir koltuk numarasını integer'a çevirip diziye ekliyoruz.
            $seats[] = (int)$row['seat_number'];
        }

        return $seats;
    }

    public function update(Trip $trip): bool
    {
        $stmt = $this->db->prepare("
            UPDATE Trips SET
                company_id = :company_id,
                destination_city = :destination_city,
                arrival_time = :arrival_time,
                departure_time = :departure_time,
                departure_city = :departure_city,
                price = :price,
                capacity = :capacity
            WHERE id = :id
        ");

        $stmt->bindValue(':id', $trip->getId(), SQLITE3_TEXT);
        $stmt->bindValue(':company_id', $trip->getCompanyId(), SQLITE3_TEXT);
        $stmt->bindValue(':destination_city', $trip->getDestinationCity(), SQLITE3_TEXT);
        $stmt->bindValue(':arrival_time', $trip->getArrivalTime()->format('Y-m-d H:i:s'), SQLITE3_TEXT);
        $stmt->bindValue(':departure_time', $trip->getDepartureTime()->format('Y-m-d H:i:s'), SQLITE3_TEXT);
        $stmt->bindValue(':departure_city', $trip->getDepartureCity(), SQLITE3_TEXT);
        $stmt->bindValue(':price', $trip->getPrice(), SQLITE3_INTEGER);
        $stmt->bindValue(':capacity', $trip->getCapacity(), SQLITE3_INTEGER);

        $result = $stmt->execute();

        if ($result === false) {
            throw new Exception("Sefer güncelleme işlemi başarısız oldu.");
        }

        return true;
    }

    public function delete(string $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM Trips WHERE id = :id");
        $stmt->bindValue(':id', $id, SQLITE3_TEXT);
        $result = $stmt->execute();

        if ($result === false) {
            throw new Exception("Sefer silme işlemi başarısız oldu.");
        }

        return true;
    }
}

?>