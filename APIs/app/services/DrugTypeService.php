<?php

class DrugTypeService extends Requests
{
  public function index()
  {
    $method = $this->getMethod();

    $drugType = new DrugType();

    $jwt = new JWT();
    $authorization = new Authorization();

    $result = [];

    if ($method == 'GET') {
      $token = $authorization->getAuthorization();

      if ($token) {
        $user = $jwt->validateJWT($token);

        if ($user) {

          $result = [
            'quantity' => count($drugType->list()),
            'drugType' => $drugType->list()
          ];

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

  public function create()
  {
    $method = $this->getMethod();
    $body = $this->parseBodyInput();

    $drugType = new DrugType();

    $jwt = new JWT();
    $authorization = new Authorization();

    $result = [];

    if ($method == 'POST') {
      $token = $authorization->getAuthorization();

      if ($token) {
        $user = $jwt->validateJWT($token);

        if ($user) {

          if (!empty($body['name'])) {

            $create_drugType = $drugType->create([$body['name']]);

            if ($create_drugType) {
              http_response_code(200);
              $result['message'] = "DrugType created";
            } else {
              http_response_code(406);
              $result['error'] = "Sorry, something went wrog, verify the fields";
            }
          } else {
            http_response_code(406);
            $result['error'] = "name field is empty";
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

  public function listById($id)
  {
    $method = $this->getMethod();

    $drugType = new DrugType();

    $jwt = new JWT();
    $authorization = new Authorization();

    $result = [];

    if ($method == 'GET') {
      $token = $authorization->getAuthorization();

      if ($token) {
        $user = $jwt->validateJWT($token);

        if ($user) {

          $drugType_id = $id[0];
          $book_exists = $drugType->listById([$drugType_id]);

          if ($book_exists) {
            $result['drugType'] = $book_exists;
          } else {
            http_response_code(404);
            $result['error'] = "DrugType not found";
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

  public function update()
  {
    $method = $this->getMethod();
    $body = $this->parseBodyInput();

    $drugType = new DrugType();

    $jwt = new JWT();
    $authorization = new Authorization();

    $result = [];

    if ($method == 'PUT') {
      $token = $authorization->getAuthorization();

      if ($token) {
        $user = $jwt->validateJWT($token);

        if ($user) {

          if (!empty($body['id']) && !empty($body['name'])) {

            $updated = $drugType->update([$body['id'], $body['name']]);

            if ($updated) {
              $result['message'] = "DrugType updated";
            } else {
              http_response_code(406);
              $result = [
                'error_01' => "Verify name, try different values",
                'error_02' => "Sorry, something went wrog, verify the ID"
              ];
            }

          } else {
            http_response_code(406);
            $result['error'] = "Title or Year field is empty";
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

  public function remove($id)
  {
    $method = $this->getMethod();

    $drugType = new DrugType();

    $jwt = new JWT();
    $authorization = new Authorization();

    $result = [];

    if ($method == 'DELETE') {
      $token = $authorization->getAuthorization();

      if ($token) {
        $user = $jwt->validateJWT($token);

        if ($user) {

          $drug_type_id = $id[0];

          $delete_drugType = $drugType->remove([$drug_type_id]);

          if ($delete_drugType) {
            $result['message'] = "DrugType deleted";
          } else {
            http_response_code(406);
            $result['error'] = "Sorry, something went wrog, drugType not exists";
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
}