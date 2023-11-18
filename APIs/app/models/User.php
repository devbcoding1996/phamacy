<?php

class User extends Database
{
  
  private $pdo;

  public function __construct()
  {
    $this->pdo = $this->getConnection();
  }

  public function list($id)
  {
    try {
      $stm = $this->pdo->prepare("SELECT id, name,mobileNumber, email, isAdmin FROM users WHERE id = ?");
      $stm->execute([$id]);

      if($stm->rowCount() > 0) {
        return $stm->fetch(PDO::FETCH_ASSOC);
      } else{
        return false;
      }
    } catch(PDOException $err) {
      return false;
    }
  }

  public function create($data)
  {
    try {
      $stm = $this->pdo->prepare("INSERT INTO users (name,mobileNumber, email, passwd) VALUES (?, ?, ?, ?)");
      $stm->execute([$data[0], $data[1], $data[2], password_hash($data[3], PASSWORD_DEFAULT)]);

      return true;
    } catch(PDOException $err) {
      return false;
    }
  }

  public function signIn($data) 
  {
    try {
      $stm = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
      $stm->execute([$data[0]]);

      if ($stm->rowCount() > 0) {
        $user = $stm->fetch(PDO::FETCH_ASSOC);

        if (password_verify($data[1], $user['passwd'])) {
          return $user['id'];
        } else {
          return false;
        }
      } else {
        return false;
      }
    } catch (PDOException $err) {
      return false;
    }
  }

  public function emailAlreadyExists($email)
  {
    try {
      $stm = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
      $stm->execute([$email]);

      if($stm->rowCount() > 0) {
        return true;
      } else{
        return false;
      }
    } catch(PDOException $err) {
      return false;
    }
  }

  public function update($data)
  {
    try {
      $stm = $this->pdo->prepare("UPDATE users SET name = ?, passwd = ? WHERE id = ?");
      $stm->execute([$data[0], password_hash($data[1], PASSWORD_DEFAULT), $data[2]]);
      
      if ($stm->rowCount() > 0) {
        return true;
      } else {
        return false;
      }
    } catch (PDOException $err) {
      return false;
    }
  }
  public function isAdmin(string $email): bool {
      $sql = "SELECT isAdmin FROM users WHERE email = :email";
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindParam(':email', $email);
      $stmt->execute();

      if ($user = $stmt->fetch()) {
          return $user['isAdmin'] === 1;
      }

      return false;
  }
}