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

      $authorization = new Authorization();
      $is_admin = $authorization->isAdmin();

      if ($is_admin) {
          // แสดงหน้าบ้านสำหรับ admin
      } else {
          // แสดงหน้าบ้านสำหรับผู้ใช้ทั่วไป
      }
    } else {
      http_response_code(405);
      $result['error'] = "HTTP Method not allowed";
    }
    
    echo json_encode($result, JSON_UNESCAPED_SLASHES);
  }
}