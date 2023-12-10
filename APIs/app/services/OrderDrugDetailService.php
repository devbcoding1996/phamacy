<?php

class OrderDrugDetailService extends Requests
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
          $resList = $orderDrugDetail->list($user->id);
          $result = [
            'quantity' => count($resList),
            'orderDrugDetail' => $resList
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
          $chk = false;
          foreach($body as $row){
            if (!empty($row['drugInfoId']) && !empty($row['orderId']) && !empty($row['quantity']) && !empty($row['value']) && !empty($row['total'])) {

              $create_customer = $orderDrugDetail->create([$row['drugInfoId'],$row['orderId'],$row['quantity'],$row['value'],$row['total']]);

              if ($create_customer) {
                http_response_code(200);
                $result['message'] = "OrderDrugDetail created";
              } else {
                http_response_code(406);
                $result['error'] = "Sorry, something went wrog, verify the fields";
              }
            } else {
              http_response_code(406);
              $result['error'] = "name field is empty";
            }
            $chk = true;
          }

          if($chk){
            echo json_encode($result);
            exit();
          }

          if (!empty($body['drugInfoId']) && !empty($body['orderId']) && !empty($body['quantity']) && !empty($body['value']) && !empty($body['total'])) {

            $create_customer = $orderDrugDetail->create([$body['drugInfoId'],$body['orderId'],$body['quantity'],$body['value'],$body['total']]);

            if ($create_customer) {
              http_response_code(200);
              $result['message'] = "OrderDrugDetail created";
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

          $orderDrugDetail_id = $id[0];
          $orderDrugDetail_exists = $orderDrugDetail->listById($orderDrugDetail_id);

          if ($orderDrugDetail_exists) {
            $result['orderDrugDetail'] = $orderDrugDetail_exists;
          } else {
            http_response_code(404);
            $result['error'] = "OrderDrugDetail not found";
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
              $result['message'] = "OrderDrugDetail updated";
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
            $result['message'] = "OrderDrugDetail deleted";
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