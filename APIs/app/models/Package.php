<?php

class Package extends Database
{

  private $pdo;

  public function __construct()
  {
    $this->pdo = $this->getConnection();
  }

  public function list()
  {
    try {
      $stm = $this->pdo->prepare("SELECT * FROM package ORDER BY id DESC");
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
      $sql = "INSERT INTO package (`name`) VALUES (:name)";
      $stmt = $this->pdo->prepare($sql);

      // Bind the parameters
      $stmt->bindParam(':name', $data[0]);

      // Execute the INSERT statement
      $stmt->execute();

      return true;
    } catch (PDOException $err) {
      return false;
    }
  }

  public function listById($data)
  {
    try {
      $stmt = $this->pdo->prepare("SELECT * FROM package WHERE id = :id");
      // Bind the parameters
      $stmt->bindParam(':id', $data[0]);

      // Execute the SELECT statement
      $stmt->execute();
      
      if($stmt->rowCount() > 0){
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
      $stmt = $this->pdo->prepare("SELECT * FROM package WHERE `name` LIKE '%:name%'");
      // Bind the parameters
      $stmt->bindParam(':name', $data[0]);

      // Execute the SELECT statement
      $stmt->execute();
      
      if($stmt->rowCount() > 0){
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
      $stmt = $this->pdo->prepare("SELECT * FROM package WHERE keyword LIKE '%:keyword%'");
      // Bind the parameters
      $stmt->bindParam(':keyword', $data[0]);

      // Execute the SELECT statement
      $stmt->execute();
      
      if($stmt->rowCount() > 0){
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
      $sql = "UPDATE package SET `name` = :name WHERE id = :id";
      $stmt = $this->pdo->prepare($sql);

      // Bind the parameters
      $stmt->bindParam(':name', $data[1]);
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
      $stmt = $this->pdo->prepare("DELETE FROM package WHERE id = :id");
      // Bind the parameters
      $stmt->bindParam(':id', $data[0]);

      // Execute the DELETE statement
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
}