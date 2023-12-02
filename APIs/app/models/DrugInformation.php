<?php

class DrugInformation extends Database
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
  public function list(): array
  {
    try {
      $stm = $this->pdo->prepare("SELECT * FROM drug_information ORDER BY id DESC");
      $stm->execute();
      if($stm->rowCount() > 0) {
        $drug_information = $stm->fetchAll(PDO::FETCH_ASSOC);
        $res = [];
        // Check if the size column is equal to an empty string
        foreach ($drug_information as $info) {
            $checkArray = [
              "id" => $info['id'],
              "name" => $this->checkNull($info['name']),
              "size" => $this->checkNull($info['size']),
              "useMedicine" => $this->checkNull($info['use_medicine']),
              "contraindications" => $this->checkNull($info['contraindications']),
              "properties" => $this->checkNull($info['properties']),
              "drugTypeId" => $this->checkNull($info['drug_type_id']),
              "categoryId" => $this->checkNull($info['category_id']),
              "packageId" => $this->checkNull($info['package_id']),
              "quantity" => $this->checkNull($info['quantity']),
              "productionDate" => $this->checkNull($info['production_date']),
              "expirationDate" => $this->checkNull($info['expiration_date']),
              "price" => $this->checkNull($info['price']),
              "keyword" => $this->checkNull($info['keyword']),
              "linkImages" => $this->checkNull($info['link_images']),
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
        $drug_information = $stm->fetchAll(PDO::FETCH_ASSOC);
        $res = [];
        // Check if the size column is equal to an empty string
        foreach ($drug_information as $info) {
            $checkArray = [
              "id" => $info['id'],
              "name" => $this->checkNull($info['name']),
              "size" => $this->checkNull($info['size']),
              "useMedicine" => $this->checkNull($info['use_medicine']),
              "contraindications" => $this->checkNull($info['contraindications']),
              "properties" => $this->checkNull($info['properties']),
              "drugTypeId" => $this->checkNull($info['drug_type_id']),
              "categoryId" => $this->checkNull($info['category_id']),
              "packageId" => $this->checkNull($info['package_id']),
              "quantity" => $this->checkNull($info['quantity']),
              "productionDate" => $this->checkNull($info['production_date']),
              "expirationDate" => $this->checkNull($info['expiration_date']),
              "price" => $this->checkNull($info['price']),
              "keyword" => $this->checkNull($info['keyword']),
              "linkImages" => $this->checkNull($info['link_images']),
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
      $sql = "UPDATE drug_information SET `name` = :name, `size` = :size, 
      use_medicine = :use_medicine, 
      contraindications = :contraindications, 
      properties = :properties, 
      drug_type_id = :drug_type_id, 
      category_id = :category_id, 
      package_id = :package_id, 
      quantity = :quantity, 
      production_date = :production_date, 
      expiration_date = :expiration_date, 
      price = :price, 
      keyword = :keyword, 
      link_images = :link_images
      WHERE id = :id";
      
      $stmt = $this->pdo->prepare($sql);

      // Bind the parameters
      $stmt->bindParam(':name', $data[1]);
      $stmt->bindParam(':size', $data[2]);
      $stmt->bindParam(':use_medicine', $data[3]);
      $stmt->bindParam(':contraindications', $data[4]);
      $stmt->bindParam(':properties', $data[5]);
      $stmt->bindParam(':drug_type_id', $data[6]);
      $stmt->bindParam(':category_id', $data[7]);
      $stmt->bindParam(':package_id', $data[8]);
      $stmt->bindParam(':quantity', $data[9]);
      $stmt->bindParam(':production_date', $data[10]);
      $stmt->bindParam(':expiration_date', $data[11]);
      $stmt->bindParam(':price', $data[12]);
      $stmt->bindParam(':keyword', $data[13]);
      $stmt->bindParam(':link_images', $data[14]);      

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