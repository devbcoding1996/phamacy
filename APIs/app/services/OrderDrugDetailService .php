<?php

class OrderDrugService extends Requests
{
  public function index()
  {
    $method = $this->getMethod();

    $orderDrugDetail = new OrderDrugDetail();

    $jwt = new JWT();
    $authorization = new Authorization();

    $result = [];

    if ($method == 'GET') {
      $token = $authorization->getAuthorization();

      if ($token) {
        $user = $jwt->validateJWT($token);

        if ($user) {

          $result = [
            'quantity' => count($orderDrugDetail->list()),
            'orderDrugDetail' => $orderDrugDetail->list()
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

    $orderDrugDetail = new OrderDrugDetail();

    $jwt = new JWT();
    $authorization = new Authorization();

    $result = [];

    if ($method == 'POST') {
      $token = $authorization->getAuthorization();

      if ($token) {
        $user = $jwt->validateJWT($token);

        if ($user) {

          if (!empty($body['fName']) && !empty($body['lName']) && !empty($body['address'])) {

            $create_customer = $orderDrugDetail->create([$body['fName'],$body['lName'],$body['address'],$body['phoneNumber'],$body['email'],$body['discount'],"Active"]);

            if ($create_customer) {
              http_response_code(200);
              $result['message'] = "Category created";
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

    $orderDrugDetail = new OrderDrugDetail();

    $jwt = new JWT();
    $authorization = new Authorization();

    $result = [];

    if ($method == 'GET') {
      $token = $authorization->getAuthorization();

      if ($token) {
        $user = $jwt->validateJWT($token);

        if ($user) {

          $drugType_id = $id[0];
          $book_exists = $orderDrugDetail->listById([$drugType_id]);

          if ($book_exists) {
            $result['orderDrugDetail'] = $book_exists;
          } else {
            http_response_code(404);
            $result['error'] = "Category not found";
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

    $orderDrugDetail = new OrderDrugDetail();

    $jwt = new JWT();
    $authorization = new Authorization();

    $result = [];

    if ($method == 'PUT') {
      $token = $authorization->getAuthorization();

      if ($token) {
        $user = $jwt->validateJWT($token);

        if ($user) {

          if (!empty($body['id']) && !empty($body['name'])) {

            $updated = $orderDrugDetail->update([$body['id'], $body['name']]);

            if ($updated) {
              $result['message'] = "Category updated";
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

    $orderDrugDetail = new OrderDrugDetail();

    $jwt = new JWT();
    $authorization = new Authorization();

    $result = [];

    if ($method == 'DELETE') {
      $token = $authorization->getAuthorization();

      if ($token) {
        $user = $jwt->validateJWT($token);

        if ($user) {

          $drug_type_id = $id[0];

          $delete_customer = $orderDrugDetail->remove([$drug_type_id,]);

          if ($delete_customer) {
            $result['message'] = "Category deleted";
          } else {
            http_response_code(406);
            $result['error'] = "Sorry, something went wrog, customer not exists";
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