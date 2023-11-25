<?php

class DrugInformation extends Database
{

  private $pdo;

  public function __construct()
  {
    $this->pdo = $this->getConnection();
  }

  public function list()
  {
    try {
      $stm = $this->pdo->prepare("SELECT * FROM drug_information ORDER BY id DESC");
      $stm->execute();
      if($stm->rowCount() > 0) {
        return $stm->fetchAll(PDO::FETCH_ASSOC);
      } else {
        return [];
      }
    } catch (PDOException $err) {
      return false;
    }
  }

  public function create($data)
  {
    try {
      $sql = "INSERT INTO drug_information (`name`, `size`, use_medicine, contraindications, properties, drug_type_id, category_id, package_id, quantity, production_date, expiration_date, price, keyword, link_images) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
      $stm = $this->pdo->prepare($sql);
      $stm->execute([$data[0],$data[1], $data[2], $data[3], $data[4], (int) $data[5], (int) $data[6], (int) $data[7], $data[8], $data[9], $data[10],(float) $data[11], $data[12], $data[13]]);

      return true;
    } catch (PDOException $err) {
      return false;
    }
  }

  public function listById($data)
  {
    try {
      $stm = $this->pdo->prepare("SELECT * FROM drug_information WHERE id = ?");
      $stm->execute([$data[0]]);
      
      if($stm->rowCount() > 0){
        return $stm->fetch(PDO::FETCH_ASSOC);
      } else {
        return false;
      }
    } catch (PDOException $err) {
      return false;
    }
  }

  public function listByName($data)
  {
    try {
      $stm = $this->pdo->prepare("SELECT * FROM drug_information WHERE `name` = LIKE '%?%'");
      $stm->execute([$data[0]]);
      
      if($stm->rowCount() > 0){
        return $stm->fetch(PDO::FETCH_ASSOC);
      } else {
        return false;
      }
    } catch (PDOException $err) {
      return false;
    }
  }

  public function listByKeyword($data)
  {
    try {
      $stm = $this->pdo->prepare("SELECT * FROM drug_information WHERE keyword = LIKE '%?%'");
      $stm->execute([$data[0]]);
      
      if($stm->rowCount() > 0){
        return $stm->fetch(PDO::FETCH_ASSOC);
      } else {
        return false;
      }
    } catch (PDOException $err) {
      return false;
    }
  }

  public function update($data) 
  {
    try {
      // Prepare the UPDATE statement
      $sql = "UPDATE drug_information SET `name` = :name, `size` = :size, price = :price WHERE id = :id";
      $stmt = $this->pdo->prepare($sql);

      // Bind the parameters
      $stmt->bindParam(':name', $data[1]);
      $stmt->bindParam(':size', $data[2]);
      $stmt->bindParam(':price', $data[3]);
      $stmt->bindParam(':id', $data[0]);

      // Execute the UPDATE statement
      $stmt->execute();
      
      if ($stmt->rowCount() > 0) {
        return true;
      } else {
        return false;
      }
    } catch (PDOException $err) {
      return false;
    }
  }

  public function remove($data) 
  {
    try {
      $stm = $this->pdo->prepare("DELETE FROM drug_information WHERE id = ?");
      $stm->execute([$data[0]]);
      
      if ($stm->rowCount() > 0) {
        return true;
      } else {
        return false;
      }
    } catch (PDOException $err) {
      return false;
    }
  }
}