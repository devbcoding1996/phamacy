<?php

class OrderDrugDetail extends Database
{

  private $pdo;

  public function __construct()
  {
    $this->pdo = $this->getConnection();
  }
  public function checkNull($val)
  {
    return empty($val)? "" : $val;
  }
  public function list()
  {
    try {
      $authorization = new Authorization();
      $is_admin = $authorization->isAdmin();
      if(!$is_admin){
        return ["Has rights only for Admin!"];
      }
      $stm = $this->pdo->prepare("SELECT * FROM order_drug_detail ORDER BY id DESC");
      $stm->execute();
      if($stm->rowCount() > 0) {
        $customer = $stm->fetchAll(PDO::FETCH_ASSOC);
        $res = [];
        // Check if the size column is equal to an empty string
        foreach ($customer as $info) {
            $checkArray = [
              "id" => $info['id'],
              "orderId" => $this->checkNull($info['order_id']),
              "drugInfoId" => $this->checkNull($info['drug_info_id']),
              "quantity" => $this->checkNull($info['quantity']),
              "value" => $this->checkNull($info['value']),
              "total" => $this->checkNull($info['total']),
              "order_date" => $this->checkNull($info['order_date'])
            ];
            array_push($res,$checkArray);
        }
        return $res;
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
      $sql = "INSERT INTO order_drug_detail (`f_name`,`l_name`,`address`,`phone_number`,`email`,`discount`,`status`) VALUES (:f_name,:l_name,:address,:phone_number,:email,:discount,:status)";
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
      $stmt = $this->pdo->prepare("SELECT * FROM order_drug_detail WHERE id = :id");
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

  public function update($data) 
  {
    try {
      // Prepare the UPDATE statement
      $sql = "UPDATE order_drug_detail SET `name` = :name WHERE id = :id";
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
      $stmt = $this->pdo->prepare("DELETE FROM order_drug_detail WHERE id = :id");
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

          $sql = "INSERT INTO user_customer (`user_id`,`order_id`) VALUES (:user_id,:order_id);";
          $stmt = $this->pdo->prepare($sql);

          // Bind the parameters
          $stmt->bindParam(':user_id', $user_id);
          $stmt->bindParam(':order_id', $id);

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