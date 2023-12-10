<?php

class DrugInformationService extends Requests
{
  public function index()
  {
    $method = $this->getMethod();

    $drugInfo = new DrugInformation();

    $jwt = new JWT();
    $authorization = new Authorization();

    $result = [];

    if ($method == 'GET') {
      $token = $authorization->getAuthorization();

      if ($token) {
        $user = $jwt->validateJWT($token);

        if ($user) {

          $result = [
            'quantity' => count($drugInfo->list()),
            'drugInformation' => $drugInfo->list()
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

    $drugInfo = new DrugInformation();

    $jwt = new JWT();
    $authorization = new Authorization();

    $result = [];

    if ($method == 'POST') {
      $token = $authorization->getAuthorization();

      if ($token) {
        $user = $jwt->validateJWT($token);

        if ($user) {

          if (!empty($body['name']) && !empty($body['size']) && !empty($body['properties']) && !empty($body['quantity']) && !empty($body['price'])) {

            $create_drugInfo = $drugInfo->create([$body['name'],
             $body['size'],
             $body['useMedicine'],
             $body['contraindications'],
             $body['properties'],
             $body['drugTypeId'],
             $body['categoryId'],
             $body['packageId'],
             $body['quantity'],
             $body['productionDate'],
             $body['expirationDate'],
             $body['price'],
             $body['keyword'],
             $body['linkImages']]);

            if ($create_drugInfo) {
              http_response_code(200);
              $result['message'] = "DrugInfo created";
            } else {
              http_response_code(406);
              $result['error'] = "Sorry, something went wrog, verify the fields";
            }
          } else {
            http_response_code(406);
            $result['error'] = "id or name field is empty";
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

    $drugInfo = new DrugInformation();

    $jwt = new JWT();
    $authorization = new Authorization();

    $result = [];

    if ($method == 'GET') {
      $token = $authorization->getAuthorization();

      if ($token) {
        $user = $jwt->validateJWT($token);

        if ($user) {

          $drugInfo_id = $id[0];
          $drugInfo_exists = $drugInfo->listById([$drugInfo_id]);

          if ($drugInfo_exists) {
            $result['drugInfo'] = $drugInfo_exists;
          } else {
            http_response_code(404);
            $result['error'] = "DrugInfo not found";
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

  public function listByName()
  {
    $method = $this->getMethod();
    $body = $this->parseBodyInput();

    $drugInfo = new DrugInformation();

    $jwt = new JWT();
    $authorization = new Authorization();

    $result = [];

    if ($method == 'POST') {
      $token = $authorization->getAuthorization();

      if ($token) {
        $user = $jwt->validateJWT($token);

        if ($user) {

          if (empty($body['name'])) {
            http_response_code(406);
            $result['error'] = "name field is empty";
          }
          $drugInfo_exists = $drugInfo->listByName($body['name']);

          if ($drugInfo_exists) {
            $result['drugInfo'] = $drugInfo_exists;
          } else {
            http_response_code(404);
            $result['error'] = "DrugInfo not found";
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

  public function listByKeyword()
  {
    $method = $this->getMethod();
    $body = $this->parseBodyInput();

    $drugInfo = new DrugInformation();

    $jwt = new JWT();
    $authorization = new Authorization();

    $result = [];

    if ($method == 'POST') {
      $token = $authorization->getAuthorization();

      if ($token) {
        $user = $jwt->validateJWT($token);

        if ($user) {
          if (empty($body['keyWord'])) {
            http_response_code(406);
            $result['error'] = "keyWord field is empty";
          }
          $drugInfo_exists = $drugInfo->listByKeyword($body['keyWord']);

          if ($drugInfo_exists) {
            $result['drugInfo'] = $drugInfo_exists;
          } else {
            http_response_code(404);
            $result['error'] = "DrugInfo not found";
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

  public function listProductAll()
  {
    $method = $this->getMethod();

    $drugInfo = new DrugInformation();

    $jwt = new JWT();
    $authorization = new Authorization();

    $result = [];

    if ($method == 'GET') {
      $token = $authorization->getAuthorization();

      if ($token) {
        $user = $jwt->validateJWT($token);

        if ($user) {

          $drugInfo_exists = $drugInfo->listProductAll();

          if ($drugInfo_exists) {
            $result['drugInfo'] = $drugInfo_exists;
          } else {
            http_response_code(404);
            $result['error'] = "DrugInfo not found";
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

    $drugInfo = new DrugInformation();

    $jwt = new JWT();
    $authorization = new Authorization();

    $result = [];

    if ($method == 'PUT') {
      $token = $authorization->getAuthorization();

      if ($token) {
        $user = $jwt->validateJWT($token);

        if ($user) {
          
          if (!empty($body['id'])) {
            $updated = $drugInfo->update([$body['id'],
            $body['name'],
            $body['size'],
            $body['useMedicine'],
            $body['contraindications'],
            $body['properties'],
            $body['drugTypeId'],
            $body['categoryId'],
            $body['packageId'],
            $body['quantity'],
            $body['productionDate'],
            $body['expirationDate'],
            $body['price'],
            $body['keyword'],
            $body['linkImages']]);             

            if ($updated) {
              $result['message'] = "DrugInfo updated";
            } else {
              http_response_code(406);
              $result = [
                'error_01' => "Verify title or year, try different values",
                'error_02' => "Sorry, something went wrog, verify the ID"
              ];
            }

          } else {
            http_response_code(406);
            $result['error'] = "Id or Name field is empty";
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

    $drugInfo = new DrugInformation();

    $jwt = new JWT();
    $authorization = new Authorization();

    $result = [];

    if ($method == 'DELETE') {
      $token = $authorization->getAuthorization();

      if ($token) {
        $user = $jwt->validateJWT($token);

        if ($user) {

          $drug_info_id = $id[0];

          $delete_drugInfo = $drugInfo->remove([$drug_info_id]);

          if ($delete_drugInfo) {
            $result['message'] = "DrugInfo deleted";
          } else {
            http_response_code(406);
            $result['error'] = "Sorry, something went wrog, drugInfo not exists";
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