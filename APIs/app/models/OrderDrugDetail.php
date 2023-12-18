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
  public function list($userId)
  {
    try {
      $authorization = new Authorization();
      $is_admin = $authorization->isAdmin();
      if($is_admin){
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
                "quantity" => intval($info['quantity']),
                "value" => floatval($info['value']),
                "total" => floatval($info['total']),
                "orderDate" => $this->checkNull($info['order_date']),
                "orderUpdate" => $this->checkNull($info['order_update'])
              ];
              array_push($res,$checkArray);
          }
          return $res;
        } else {
          return [];
        }
      }
      $stm = $this->pdo->prepare("SELECT * FROM order_drug_detail  ORDER BY id DESC");
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
              "quantity" => intval($info['quantity']),
              "value" => floatval($info['value']),
              "total" => floatval($info['total']),
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
      $sql = "INSERT INTO order_drug_detail (`drug_info_id`,`order_id`,`quantity`,`value`,`total`,`order_date`,`order_update`) VALUES (:drug_info_id,:order_id,:quantity,:value,:total,:order_date,:order_update)";
      $stmt = $this->pdo->prepare($sql);

      $Now = new DateTime('now');
      $currentDate = $Now->format('Y-m-d');
      $currentDateTime = $Now->format('Y-m-d H:i:s');
      // Bind the parameters
      $stmt->bindParam(':drug_info_id', $data[0]);
      $stmt->bindParam(':order_id', $data[1]);
      $stmt->bindParam(':quantity', $data[2]);
      $stmt->bindParam(':value', $data[3]);
      $stmt->bindParam(':total', $data[4]);
      $stmt->bindParam(':order_date', $currentDate);
      $stmt->bindParam(':order_update', $currentDateTime);

      // Execute the INSERT statement
      $stmt->execute();
      if($stmt->rowCount() > 0){
        return true;
      } else {
        return false;
      }
    } catch (PDOException $err) {
      return false;
    }
  }

  public function listById($id)
  {
    try {
      $stmt = $this->pdo->prepare("SELECT order_drug_detail.*,
      drug_information.name as d_if_name,
      drug_information.drug_type_id,
      drug_information.category_id,
      drug_information.package_id
      FROM order_drug_detail 
      INNER JOIN drug_information ON(order_drug_detail.drug_info_id = drug_information.id)
      WHERE order_id = :order_id");
      // Bind the parameters
      $stmt->bindParam(':order_id', $id);

      // Execute the SELECT statement
      $stmt->execute();
      $res = [];
      
      if($stmt->rowCount() > 0){
        $or_detail = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($or_detail as $info) {
            $checkArray = [
              "id" => $info['id'],
              "orderId" => $this->checkNull($info['order_id']),
              "drugInfoId" => $this->checkNull($info['drug_info_id']),
              "drugInfoName" => $info['d_if_name'],
              "drugTypeId" => $this->checkNull($info['drug_type_id']),
              "categoryId" => $this->checkNull($info['category_id']),
              "packageId" => $this->checkNull($info['package_id']),
              "quantity" => intval($info['quantity']),
              "value" => floatval($info['value']),
              "total" => floatval($info['total']),
              "orderDate" => $this->checkNull($info['order_date']),
              "orderUpdate" => $this->checkNull($info['order_update'])
            ];
            array_push($res,$checkArray);
        }
        return $res;
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
}