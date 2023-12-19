<?php

class OrderDrug extends Database
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
      if($is_admin){
        $stm = $this->pdo->prepare("SELECT * FROM order_drug INNER JOIN customer ON(order_drug.customer_id = customer.customer_id) ORDER BY order_update DESC");
        $stm->execute();
        if($stm->rowCount() > 0) {
          $customer = $stm->fetchAll(PDO::FETCH_ASSOC);
          $res = [];
          // Check if the size column is equal to an empty string
          foreach ($customer as $info) {
              $checkArray = [
                "id" => $info['id'],
                "customerId" => intval($info['customer_id']),
                "total" => floatval($info['total']),
                "status" => $this->checkNull($info['status']),
                "orderDate" => $this->checkNull($info['order_date']),
                "orderUpdate" => $this->checkNull($info['order_update']),
                "fName" => $this->checkNull($info['f_name']),
                "lName" => $this->checkNull($info['l_name']),
                "address" => $this->checkNull($info['address']),
                "phoneNumber" => $this->checkNull($info['phone_number']),
                "email" => $this->checkNull($info['email']),
              ];
              array_push($res,$checkArray);
          }
          return $res;
        } else {
          return [];
        }
      }
      $stm = $this->pdo->prepare("SELECT * FROM order_drug ORDER BY order_update DESC");
      $stm->execute();
      if($stm->rowCount() > 0) {
        $customer = $stm->fetchAll(PDO::FETCH_ASSOC);
        $res = [];
        // Check if the size column is equal to an empty string
        foreach ($customer as $info) {
            $checkArray = [
              "id" => $info['id'],
              "customerId" => intval($info['customer_id']),
              "total" => floatval($info['total']),
              "status" => $this->checkNull($info['status']),
              "orderDate" => $this->checkNull($info['order_date']),
              "orderUpdate" => $this->checkNull($info['order_update'])
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

  public function listByUserId($userId)
  {
    try {
        if (!$userId) {
          return false;
        }
        $stm = $this->pdo->prepare("SELECT order_drug.* FROM order_drug INNER JOIN user_customer ON(order_drug.customer_id = user_customer.customer_id) WHERE user_customer.user_id = '$userId' ORDER BY order_update DESC");
        $stm->execute();
        if($stm->rowCount() > 0) {
          $customer = $stm->fetchAll(PDO::FETCH_ASSOC);
          $res = [];
          // Check if the size column is equal to an empty string
          foreach ($customer as $info) {
              $checkArray = [
                "id" => $info['id'],
                "customerId" => intval($info['customer_id']),
                "total" => floatval($info['total']),
                "status" => $this->checkNull($info['status']),
                "orderDate" => $this->checkNull($info['order_date']),
                "orderUpdate" => $this->checkNull($info['order_update'])
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

  public function listByCustomerId($customerId)
  {
    try {
        if (!$customerId) {
          return false;
        }
        $stm = $this->pdo->prepare("SELECT * FROM order_drug WHERE customer_id = '$customerId' ORDER BY order_update DESC");
        $stm->execute();
        if($stm->rowCount() > 0) {
          $customer = $stm->fetchAll(PDO::FETCH_ASSOC);
          $res = [];
          // Check if the size column is equal to an empty string
          foreach ($customer as $info) {
              $checkArray = [
                "id" => $info['id'],
                "customerId" => intval($info['customer_id']),
                "total" => floatval($info['total']),
                "status" => $this->checkNull($info['status']),
                "orderDate" => $this->checkNull($info['order_date']),
                "orderUpdate" => $this->checkNull($info['order_update'])
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
      $resChk = $this->checkOrderByCustomerId($data[0]);
      if($resChk){
        return ['status'=>true,'orderId'=>$resChk];
      }

      $sql = "INSERT INTO order_drug (`customer_id`,`total`,`status`,`order_date`,`order_update`) 
      VALUES (:customer_id,:total,:status,:order_date,:order_update)";
     
      $stmt = $this->pdo->prepare($sql);
      $Now = new DateTime('now');
      $currentDate = $Now->format('Y-m-d');
      $currentDateTime = $Now->format('Y-m-d H:i:s');
      $checkStatus= empty($data[2])? 'OD':$data[2] ;
      $total = floatval($data[1]);
      // Bind the parameters
      $stmt->bindParam(':customer_id', $data[0]);
      $stmt->bindParam(':total',$total);
      $stmt->bindParam(':status', $checkStatus);
      $stmt->bindParam(':order_date', $currentDate);
      $stmt->bindParam(':order_update',  $currentDateTime);
      // Execute the INSERT statement
      $stmt->execute();
      $stmt2 = $this->pdo->prepare("SELECT id FROM `order_drug` ORDER BY order_update DESC LIMIT 1;");
      $stmt2->execute();
      $last_index = $stmt2->fetch(PDO::FETCH_COLUMN);
      return ['status'=>true,'orderId'=>$last_index];
    } catch (PDOException $err) {
      return ['status'=>false,'orderId'=>""];;
    }
  }

  public function checkOrderByCustomerId($customerId)
  {
    try {
      $stmt = $this->pdo->prepare("SELECT id FROM `order_drug` WHERE customer_id = :id AND `status` IN ('OD','WP') ORDER BY order_update DESC LIMIT 1;");
      // Bind the parameters
      $stmt->bindParam(':id', $customerId);
      $stmt->execute();
      if($stmt->rowCount() > 0){
        return $stmt->fetch(PDO::FETCH_COLUMN);
      } else {
        return false;
      }
    } catch (PDOException $err) {
      return false;
    }
  }

  public function listById($data)
  {
    try {
      $stmt = $this->pdo->prepare("SELECT * FROM order_drug WHERE id = :id;");
      // Bind the parameters
      $stmt->bindParam(':id', $data);
      // Execute the SELECT statement
      $stmt->execute();
      
      if($stmt->rowCount() > 0){
        $orderDrug = $stmt->fetch(PDO::FETCH_ASSOC);
        $checkArray = [
              "id" => $orderDrug['id'],
              "customerId" => intval($orderDrug['customer_id']),
              "total" => floatval($orderDrug['total']),
              "status" => $this->checkNull($orderDrug['status']),
              "orderDate" => $this->checkNull($orderDrug['order_date']),
              "orderUpdate" => $this->checkNull($orderDrug['order_update'])
            ];
       return $checkArray;
      }
    } catch (PDOException $err) {
      return false;
    }
  }

  public function update($data) 
  {
    try {
      // Prepare the UPDATE statement
      $sql = "UPDATE order_drug SET `customer_id` = :customer_id,`total` = :total,`status` = :status,`order_update` = :order_update WHERE id = :id;";
      $stmt = $this->pdo->prepare($sql);

      $Now = new DateTime('now');
      $currentDateTime = $Now->format('Y-m-d H:i:s');
      $total = floatval($data[2]);
      // Bind the parameters
      $stmt->bindParam(':order_update', $currentDateTime);
      $stmt->bindParam(':status', $data[3]);
      $stmt->bindParam(':total', $total);
      $stmt->bindParam(':customer_id', $data[1]);
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
      $stmt = $this->pdo->prepare("DELETE FROM order_drug WHERE id = :id;");
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

  public function listLastAction()
  {
    try {
      $stmt = $this->pdo->prepare("SELECT id FROM order_drug WHERE status = 'OD' ORDER BY order_update DESC LIMIT 1;");
      // Bind the parameters
      // $stmt->bindParam(':id', $data[0]);

      // Execute the SELECT statement
      $stmt->execute();
      
      if($stmt->rowCount() > 0){
        return $stmt->fetch(PDO::FETCH_COLUMN);
      } else {
        return false;
      }
    } catch (PDOException $err) {
      return false;
    }
  }
}