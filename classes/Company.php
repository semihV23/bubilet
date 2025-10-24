<?php
class Company{
    private ?string $id;
    private ?string $name;

    public function __construct(?string $name=null, ?string $id=null)
    {
        $this->id=$id;
        $this->name=$name;
    }

    public function getId():?string{
        return $this->id;
    }
    public function setId(string $id){
        $this->id=$id;
    }
    public function getName():?string{
        return $this->name;
    }
    public function setName(string $name){
        $this->name=$name;
    }
}

class CompanyRepository{
    private SQLite3 $db;

    public function __construct()
    {
        $this->db = new Database()->db;
    }

    public function create(Company $company){
        $stmt = $this->db->prepare("INSERT INTO Bus_Company (id, name) VALUES (:id, :name)");
        $company->setId(uniqid("", true));
        $stmt->bindValue(":id", $company->getId(), SQLITE3_TEXT);
        $stmt->bindValue(":name", $company->getName(), SQLITE3_TEXT);
        $result = $stmt->execute();

        if($result === false){
            throw new Error("Firma oluşturma işlemi başarısız oldu.");
        }

        return $company;
    }

    /**
     * ID ile bir firma bulur ve Company nesnesi olarak döndürür.
     * Bulamazsa null döndürür.
     * @param string $id
     * @return Company|null
     */
    public function findById(?string $id): ?Company
    {
        if(empty($id)){
            return new Company();
        }
        $stmt = $this->db->prepare("SELECT * FROM Bus_Company WHERE id = :id");
        $stmt->bindValue(":id", $id);
        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);

        // Satır bulunamazsa false döner, bu durumda null döndürüyoruz.
        if ($row === false) {
            return null;
        }

        // Veritabanı satırını bir Company nesnesine dönüştür (hydrate)
        return new Company($row['name'], $row['id']);
    }


    /**
     * Tüm firmaları bulur ve Company nesnelerinden oluşan bir dizi döndürür.
     * @return Company[]
     */
    public function findAll(): array
    {
        $result = $this->db->query("SELECT * FROM Bus_Company");
        $companies = []; // Company nesnelerini tutacak dizi
        
        // Her bir satırı Company nesnesine dönüştür
        while ($row = $result->fetchArray(SQLITE3_ASSOC)){
            $companies[] = new Company($row['name'], $row['id']);
        }
        
        return $companies;
    }

    public function update(Company $company){
        $stmt = $this->db->prepare("UPDATE Bus_Company SET name = :name WHERE id = :id");
        $stmt->bindValue(":name", $company->getName());
        $stmt->bindValue(":id", $company->getId());
        $result = $stmt->execute();

        if($result === false){
            throw new Exception("Firma silme işlemi başarısız");
        }

        return true;
    }
    public function delete(string $id){
        $stmt = $this->db->prepare("DELETE FROM Bus_Company WHERE id = :id");
        $stmt->bindValue(":id", $id);
        $result = $stmt->execute();

        if($result === false){
            throw new Error("Firma silme işlemi başarısız.");
        }

        return true;
    }
}
?>