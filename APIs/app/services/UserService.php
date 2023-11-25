<?php

class UserService extends Requests
{
  public function list()
  {
    $method = $this->getMethod();

    $result = [];

    $user_model = new User();
    $jwt = new JWT();
    $authorization = new Authorization();

    if ($method == 'GET') {

      $token = $authorization->getAuthorization();

      if ($token) {
        $user = $jwt->validateJWT($token);

        if ($user) {

          $user_id = $user->id;

          $user_exists = $user_model->list($user_id);

          if ($user_exists) {
            $result['data'] = $user_exists;
          } else {
            http_response_code(401);
            $result['error'] = "Unauthorized, please, verify your credentials";
          }
        } else {
          http_response_code(401);
          $result['error'] = "Unauthorized, please, verify your token";
        }
      } else {
        http_response_code(401);
        $result['error'] = "Unauthorized, please, verify your token";
      }
    } else {
      http_response_code(405);
      $result['error'] = "HTTP Method not allowed";
    }

    echo json_encode($result);
  }
  public function index()
  {
    $method = $this->getMethod();
    $body = $this->parseBodyInput();    

    $user_model = new User();

    $result = [];

    if ($method === 'POST') {
      if (!empty($body['name']) && !empty($body['email']) && !empty($body['mobileNumber']) && !empty($body['password'])) {

        $name = $body['name'];
        $mobileNumber = strval($body['mobileNumber']); //แปลงค่าให้เป็นข้อความ
        $email = $body['email'];
        $password = $body['password'];

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

          if (!$user_model->emailAlreadyExists($email)) {

            $create_user = $user_model->create([$name, $mobileNumber, $email, $password]);

            if ($create_user) {

              http_response_code(200);
              $result = [
                "message" => "Created",
                "login" => BASE_URL . "users/login"
              ];

            } else {
              http_response_code(406);
              $result['error'] = "Sorry, something went wrong, try again";
            }
          } else {
            http_response_code(406);
            $result['error'] = "Email already exists";
          }
        } else {
          http_response_code(406);
          $result['error'] = "Please, enter a valid email";
        }
      } else {
        http_response_code(406);
        $result['error'] = "Name or Email or Password field is empty";
      }
    } else {
      http_response_code(405);
      $result['error'] = "HTTP Method not allowed";
    }

    echo json_encode($result, JSON_UNESCAPED_SLASHES);
  }

  public function login()
  {
    $method = $this->getMethod();
    $body = $this->parseBodyInput();
    
    $result = [];

    $user_model = new User();
    $jwt = new JWT();

    if ($method == 'POST') {

      if (!empty($body['email']) && !empty($body['password'])) {
        $email = $body['email'];
        $password = $body['password'];

        $user = $user_model->signIn([$email, $password]);

        if ($user) {

          $result = [
            "message" => "successfully",
            "token" => $jwt->generateJWT(["id" => $user])
          ];

          //ฟังก์ชัน setcookie() จะตั้งค่าค่า isAdmin เป็นค่า true หรือ false ลงใน Cookies โดยมีเวลาหมดอายุ 1 ชั่วโมง และใช้ได้กับทุกเส้นทางในเว็บไซต์
          $cookies = array();
          $cookies['isAdmin'] = $this->isAdmin($email) ? 'true' : 'false';
          setcookie('isAdmin', $cookies['isAdmin'], time() + 3600, '/');

        } else {
          http_response_code(401);
          $result['error'] = "Unauthorized";
        }
      } else {
        http_response_code(406);
        $result['error'] = "Email or Password field is empty";
      }
    } else {
      http_response_code(405);
      $result['error'] = "HTTP Method not allowed";
    }

    echo json_encode($result);
  }

  public function update()
  {
    $method = $this->getMethod();
    $body = $this->parseBodyInput();

    $result = [];

    $user_model = new User();
    $jwt = new JWT();
    $authorization = new Authorization();

    if ($method == 'PUT') {

      $token = $authorization->getAuthorization();

      if ($token) {
        $user = $jwt->validateJWT($token);

        if ($user) {

          $user_id = $user->id;

          if (!empty($body['name']) && !empty($body['password'])) {
            $name = $body['name'];
            $password = $body['password'];
    
            $update_user = $user_model->update([$name, $password, $user_id]);

            if ($update_user) {
              $result['message'] = "User updated";
            } else {
              http_response_code(401);
              $result['error'] = "Sorry, something went wrong, verify your credentials or fields";
            }
          } else {
            http_response_code(406);
            $result['error'] = "Name or Password field is empty";
          }
        } else {
          http_response_code(401);
          $result['error'] = "Unauthorized, please, verify your token";
        }
      } else {
        http_response_code(401);
        $result['error'] = "Unauthorized, please, verify your token";
      }
    } else {
      http_response_code(405);
      $result['error'] = "HTTP Method not allowed";
    }

    echo json_encode($result);
  }

  public function isAdmin($email) {
    $user_model = new User();
    return $user_model->isAdmin($email);
  }

  public function logout()
  {
      $method = $this->getMethod();

      $result = [];

      if ($method == 'POST') {
          // Remove the isAdmin cookie
          setcookie('isAdmin', '', time() - 3600, '/');

          // Set a success message
          $result['message'] = "Logged out";

          // Send the response
          echo json_encode($result);
      } else {
          http_response_code(405);
          $result['error'] = "HTTP Method not allowed";

          echo json_encode($result);
      }
  }


}