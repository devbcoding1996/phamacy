<?php 
class HomeService extends Requests
{
  public function index()
  {
    $method = $this->getMethod();
    $result = [];

    if($method == 'GET') {
      http_response_code(200);
      $result = [
        "message" => "Hey There! 🦍",
        "guide" => "wakeupcoding/pharmacyAPI"
      ];
    } else {
      http_response_code(405);
      $result['error'] = "HTTP Method not allowed";
    }
    
    echo json_encode($result, JSON_UNESCAPED_SLASHES);
  }
}