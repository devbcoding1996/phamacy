<?php

class UserCustomer extends Database
{

  private $pdo;

  public function __construct()
  {
    $this->pdo = $this->getConnection();
  }

  public function list($userId)
  {
    try {
      $authorization = new Authorization();
      $is_admin = $authorization->isAdmin();
      if($is_admin){
        $stm = $this->pdo->prepare("SELECT * FROM user_customer ORDER BY customer_id DESC");
        $stm->execute();
        if($stm->rowCount() > 0) {
          return $stm->fetchAll(PDO::FETCH_ASSOC);
        } else {
          return [];
        }
      }
      $stm = $this->pdo->prepare("SELECT * FROM user_customer WHERE user_id='$userId' ORDER BY customer_id DESC");
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
      $sql = "INSERT INTO user_customer (`user_id`,`customer_id`) VALUES (:user_id,:customer_id)";
      $stmt = $this->pdo->prepare($sql);

      // Bind the parameters
      $stmt->bindParam(':user_id', $data[0]);
      $stmt->bindParam(':customer_id', $data[1]);

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
      $stmt = $this->pdo->prepare("SELECT * FROM user_customer WHERE uc_id = :id");
      // Bind the parameters
      $stmt->bindParam(':id', $data[0]);

      // Execute the SELECT statement
      $stmt->execute();
      
      if($stmt->rowCount() > 0){
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
      } else {
        return false;
      }
    } catch (PDOException $err) {
      return false;
    }
  }

  public function listCustomerId($userId)
  {
    try {
      $stmt = $this->pdo->prepare("SELECT * FROM user_customer WHERE user_id = :id and `status` = 'Y' LIMIT 1;");
      // Bind the parameters
      $stmt->bindParam(':id', $userId);

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
      $stmt = $this->pdo->prepare("SELECT * FROM user_customer WHERE `name` = LIKE '%:name%'");
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
      $stmt = $this->pdo->prepare("SELECT * FROM user_customer WHERE keyword = LIKE '%:keyword%'");
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
      $sql = "UPDATE user_customer SET `user_id` = :user_id,`customer_id` = :customer_id WHERE uc_id = :uc_id";
      $stmt = $this->pdo->prepare($sql);

      // Bind the parameters
      $stmt->bindParam(':user_id', $data[1]);
      $stmt->bindParam(':customer_id ', $data[2]);
      $stmt->bindParam(':uc_id', $data[0]);

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

  public function updateStatus($data) 
  {
    try {
      // Prepare the UPDATE statement
      $sql = "UPDATE user_customer SET `status` = :status WHERE customer_id = :customer_id";
      $stmt = $this->pdo->prepare($sql);

      // Bind the parameters
      $stmt->bindParam(':status', $data[1]);
      $stmt->bindParam(':customer_id', $data[0]);

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
  public function updateStatusN($userId,$customerId) 
  {
    try {
      // Prepare the UPDATE statement
      $sql = "UPDATE user_customer SET `status` = 'N' WHERE user_id = :user_id AND customer_id != :customer_id";
      $stmt = $this->pdo->prepare($sql);

      // Bind the parameters
      $stmt->bindParam(':customer_id', $customerId);
      $stmt->bindParam(':user_id', $userId);

      // Execute the UPDATE statement
      $stmt->execute();
      
    } catch (PDOException $err) {
      return false;
    }
  }

  public function remove($data) 
  {
    try {
      $stmt = $this->pdo->prepare("DELETE FROM user_customer WHERE uc_id = :uc_id");
      // Bind the parameters
      $stmt->bindParam(':uc_id', $data[0]);

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