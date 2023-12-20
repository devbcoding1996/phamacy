<?php

class Authorization
{
  public function getAuthorization()
  {
    $header = getallheaders();

    if (isset($header['Authorization']) || isset($header['authorization'])) {

      $token =  $header['authorization'] ?? $header['Authorization'];

      return str_replace('Bearer ', '', $token);

    } else {
      return false;
    }
  }
  public function isAdmin()
  {
      $is_admin = $_COOKIE['isAdmin'];
      return $is_admin === 'true';
  }
}