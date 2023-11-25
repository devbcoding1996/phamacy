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

            $create_book = $drugInfo->create([$body['name'],
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

            if ($create_book) {
              http_response_code(200);
              $result['message'] = "DrugInfo created";
            } else {
              http_response_code(406);
              $result['error'] = "Sorry, something went wrog, verify the fields";
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
          $book_exists = $drugInfo->listById([$drugInfo_id]);

          if ($book_exists) {
            $result['drugInfo'] = $book_exists;
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

  public function update($id)
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

          if (!empty($body['title']) && !empty($body['year'])) {

            $book_id = $id[0];
            $user_id = $user->id;

            $updated = $drugInfo->update([$body['title'], $body['year'], $book_id, $user_id]);

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

    $drugInfo = new DrugInformation();

    $jwt = new JWT();
    $authorization = new Authorization();

    $result = [];

    if ($method == 'DELETE') {
      $token = $authorization->getAuthorization();

      if ($token) {
        $user = $jwt->validateJWT($token);

        if ($user) {

          $book_id = $id[0];
          $user_id = $user->id;

          $delete_book = $drugInfo->remove([$book_id, $user_id]);

          if ($delete_book) {
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