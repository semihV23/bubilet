<?php

require_once __DIR__ . "/Database.php";

enum Role: string {
    case USER = "user";
    case COMPANY = "company";
    case ADMIN = "admin";
}

class User {
    public $id, $full_name, $email, $role, $password, $balance;

    public function createUser($full_name, $email, Role $role, $password) : bool {
        $this->full_name=$full_name;
        $this->email=$email;
        $this->role=$role->value;
        $this->password=$password;

        // Kullanıcı oluşturma fonksiyonu.
        $db = new Database()->db;
        try {
            $stmt = $db->prepare("INSERT INTO User (id, full_name, email, role, password) VALUES (:id, :full_name, :email, :role, :password)");
            $this->id = uniqid("", true);
            $stmt->bindValue(":id", $this->id, SQLITE3_TEXT);
            $stmt->bindValue(":full_name", $full_name, SQLITE3_TEXT);
            $stmt->bindValue(":email", $email, SQLITE3_TEXT);
            $stmt->bindValue(":role", $role->value, SQLITE3_TEXT);
            $this->password=password_hash($password, PASSWORD_DEFAULT);
            $stmt->bindValue(':password', $this->password, SQLITE3_TEXT);
            $stmt->execute();
            $lastId = $db->lastInsertRowID();
            if (!$lastId){
                print_r("Kullanıcı oluşturulamadı. Bu kullanıcı zaten mevcut.");
                return false;
            }
            return true;
        } catch (Exception $e){
            print_r("Kullanıcı oluşturma hatası: " . $e->getMessage());
            return false;
        }
    }

    public function getUserByEmail($email):bool|User{
        $db = new Database()->db;
        $stmt = $db->prepare("SELECT * FROM User WHERE email = :email");
        $stmt->bindValue(":email", $email);
        $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
        
        if($result === false){
            return false;
        }

        $this->id = $result["id"];
        $this->full_name = $result["full_name"];
        $this->email = $result["email"];
        $this->role = $result["role"];
        $this->password = $result["password"];
        $this->balance = $result["balance"];

        return $this;
    }

    public function setSession(){
        $_SESSION["user_id"] = $this->id;
        $_SESSION["user_full_name"] = $this->full_name;
        $_SESSION["user_email"] = $this->email;
        $_SESSION["user_role"] = $this->role;
        $_SESSION["balance"] = $this->balance;
    }

    public function setBalance($balance):bool{
        $db = new Database()->db;
        $stmt = $db->prepare("UPDATE User SET balance = :balance WHERE email = :email");
        $stmt->bindValue(":balance", $balance);
        $stmt->bindValue(":email", $this->email);
        $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
        if(!$result){
            return false;
        }else{
            $this->balance = $balance;
            return true;
        }
    }
    
}

class User2 {
    private ?string $id;
    private ?string $full_name;
    private ?string $email;
    private ?string $role;
    private ?string $password;
    private ?string $companyId;
    private ?int $balance;
    private ?DateTimeImmutable $created_at;

    public function __construct(
        ?string $id,
        ?string $full_name,
        ?string $email,
        ?Role $role,
        ?string $password,
        ?string $companyId,
        ?int $balance,
        ?DateTimeImmutable $created_at
    ) {
        $this->id = $id;
        $this->full_name = $full_name;
        $this->email = $email;
        $this->role = $role->value;
        $this->password = $password;
        $this->companyId = $companyId;
        $this->balance = $balance;
        $this->created_at = $created_at ?? new DateTimeImmutable();
    }

    public function getId(): ?string {
        return $this->id;
    }

    public function setId(string $id): void {
        $this->id = $id;
    }

    public function getFullName(): string {
        return $this->full_name;
    }

    public function setFullName(string $full_name): void {
        $this->full_name = $full_name;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function getRole(): string {
        return $this->role;
    }

    public function setRole(Role $role): void {
        $this->role = $role->value;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function setPassword(string $password): void {
        $this->password = $password;
    }

    public function getBalance(): int {
        return $this->balance;
    }

    public function setBalance(int $balance): void {
        $this->balance = $balance;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getCreatedAt(): ?DateTimeImmutable {
        return $this->created_at;
    }

    /**
     * @param DateTimeImmutable $created_at
     */
    public function setCreatedAt(DateTimeImmutable $created_at): void {
        $this->created_at = $created_at;
    }

    public function getCompanyId(): ?string {
        return $this->companyId;
    }

    public function setCompanyId(?string $companyId): void {
        $this->companyId = $companyId;
    }

    public function setSession(): void {
        $_SESSION["user_id"] = $this->id;
        $_SESSION["user_full_name"] = $this->full_name;
        $_SESSION["user_email"] = $this->email;
        $_SESSION["user_role"] = $this->role;
        $_SESSION["balance"] = $this->balance;
    }
}

class UserRepository {
    private SQLite3 $db;

    public function __construct() {
        $this->db = new Database()->db;
    }

    private function hydrateUser(array $row): User2 {
        $createdAt = new DateTimeImmutable($row['created_at']);
        return new User2(
            $row['id'],
            $row['full_name'],
            $row['email'],
            Role::from($row['role']),
            $row['password'],
            $row['company_id'],
            $row['balance'],
            $createdAt,
        );
    }

    public function create(User $user): User {
        $stmt = $this->db->prepare("INSERT INTO User (id, full_name, email, role, password, balance) VALUES (:id, :full_name, :email, :role, :password, :balance)");
        $user->id = uniqid("", true);
        $stmt->bindValue(":id", $user->id, SQLITE3_TEXT);
        $stmt->bindValue(":full_name", $user->full_name, SQLITE3_TEXT);
        $stmt->bindValue(":email", $user->email, SQLITE3_TEXT);
        $stmt->bindValue(":role", $user->role, SQLITE3_TEXT);
        $stmt->bindValue(":password", password_hash($user->password, PASSWORD_DEFAULT), SQLITE3_TEXT);
        $stmt->bindValue(":balance", $user->balance ?? 0, SQLITE3_NUM);
        $result = $stmt->execute();

        if ($result === false) {
            throw new Exception("Kullanıcı oluşturma işlemi başarısız oldu.");
        }

        return $user;
    }

    public function findById(string $id): ?User2 {
        $stmt = $this->db->prepare("SELECT * FROM User WHERE id = :id");
        $stmt->bindValue(":id", $id, SQLITE3_TEXT);
        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);
        
        return $row ? $this->hydrateUser($row) : null;
    }

    public function findByEmail(string $email): ?User2 {
        $stmt = $this->db->prepare("SELECT * FROM User WHERE email = :email");
        $stmt->bindValue(":email", $email, SQLITE3_TEXT);
        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);

        return $row ? $this->hydrateUser($row) : null;
    }

    /**
     * @return User2[]
     */
    public function findAll(): array {
        $result = $this->db->query("SELECT * FROM User");
        $users = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $users[] = $this->hydrateUser($row);
        }
        return $users;
    }

    public function update(User2 $user): bool {
        $stmt = $this->db->prepare("UPDATE User SET id=:id, full_name=:full_name, email=:email, role=:role, password=:password, company_id=:company_id, balance=:balance, created_at=:created_at  WHERE id = :id");
        $stmt->bindValue(":id", $user->getid(), SQLITE3_TEXT);
        $stmt->bindValue(":full_name", $user->getFullName(), SQLITE3_TEXT);
        $stmt->bindValue(":email", $user->getEmail(), SQLITE3_TEXT);
        $stmt->bindValue(":role", $user->getRole(), SQLITE3_TEXT);
        $stmt->bindValue(":password", $user->getPassword(), SQLITE3_TEXT);
        $stmt->bindValue(":company_id", $user->getCompanyId(), SQLITE3_TEXT);
        $stmt->bindValue(":balance", $user->getBalance() ?? 0, SQLITE3_NUM);
        $stmt->bindValue(":created_at", $user->getCreatedAt()->format('d.m.Y H:i'), SQLITE3_TEXT);
        $result = $stmt->execute();

        if ($result === false) {
            throw new Exception("Kullanıcı güncelleme işlemi başarısız oldu.");
        }

        return true;
    }

    public function updateBalance(string $email, float $balance): bool {
        $stmt = $this->db->prepare("UPDATE User SET balance = :balance WHERE email = :email");
        $stmt->bindValue(":balance", $balance, SQLITE3_NUM);
        $stmt->bindValue(":email", $email, SQLITE3_TEXT);
        $result = $stmt->execute();

        if ($result === false) {
            throw new Exception("Bakiye güncelleme işlemi başarısız oldu.");
        }

        return true;
    }

    public function delete(string $id): bool {
        $stmt = $this->db->prepare("DELETE FROM User WHERE id = :id");
        $stmt->bindValue(":id", $id, SQLITE3_TEXT);
        $result = $stmt->execute();

        if ($result === false) {
            throw new Exception("Kullanıcı silme işlemi başarısız oldu.");
        }

        return true;
    }
}

?>
