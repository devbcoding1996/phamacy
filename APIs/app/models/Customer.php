<?php

class Customer extends Database
{

  private $pdo;

  public function __construct()
  {
    $this->pdo = $this->getConnection();
  }

  public function list()
  {
    try {
      $authorization = new Authorization();
      $is_admin = $authorization->isAdmin();
      if(!$is_admin){
        return [];
      }
      $stm = $this->pdo->prepare("SELECT * FROM customer ORDER BY customer_id DESC");
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
      $sql = "INSERT INTO customer (`f_name`,`l_name`,`address`,`phone_number`,`email`,`discount`,`status`) VALUES (:f_name,:l_name,:address,:phone_number,:email,:discount,:status)";
      $stmt = $this->pdo->prepare($sql);

      // Bind the parameters
      $stmt->bindParam(':f_name', $data[0]);
      $stmt->bindParam(':l_name', $data[1]);
      $stmt->bindParam(':address', $data[2]);
      $stmt->bindParam(':phone_number', $data[3]);
      $stmt->bindParam(':email', $data[4]);
      $stmt->bindParam(':discount', $data[5]);
      $stmt->bindParam(':status', $data[6]);

      // Execute the INSERT statement
      $stmt->execute();

      $last_index = $this->pdo->lastInsertId();

      $this->createUserCustomer($last_index);

      return true;
    } catch (PDOException $err) {
      return false;
    }
  }

  public function listById($data)
  {
    try {
      $stmt = $this->pdo->prepare("SELECT * FROM customer WHERE id = :id");
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
      $stmt = $this->pdo->prepare("SELECT * FROM customer WHERE `name` = LIKE '%:name%'");
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
      $stmt = $this->pdo->prepare("SELECT * FROM customer WHERE keyword = LIKE '%:keyword%'");
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
      $sql = "UPDATE customer SET `name` = :name WHERE id = :id";
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
      $stmt = $this->pdo->prepare("DELETE FROM customer WHERE id = :id");
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

  public function createUserCustomer($id) 
  {
    try {

      $jwt = new JWT();
      $authorization = new Authorization();
      $token = $authorization->getAuthorization();

      if ($token) {
        $user = $jwt->validateJWT($token);

        if ($user) {
          $user_id = $user->id;

          $sql = "INSERT INTO user_customer (`user_id`,`customer_id`) VALUES (:user_id,:customer_id);";
          $stmt = $this->pdo->prepare($sql);

          // Bind the parameters
          $stmt->bindParam(':user_id', $user_id);
          $stmt->bindParam(':customer_id', $id);

          // Execute the INSERT statement
          $stmt->execute();
          
          if ($stmt->rowCount() > 0) {
            return true;
          } else {
            return false;
          }

        }
      }

      
    } catch (PDOException $err) {
      return false;
    }
  }
}