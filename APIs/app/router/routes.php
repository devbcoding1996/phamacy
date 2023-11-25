<?php

$routes = [
  '/'                  => 'HomeService@index',

  '/users'             => 'UserService@list',
  '/users/create'      => 'UserService@index',
  '/users/login'       => 'UserService@login',
  '/users/logout'       => 'UserService@logout',
  '/users/update'      => 'UserService@update',
  
  '/books'             => 'BookService@index',
  '/books/create'      => 'BookService@create',
  '/books/list/{id}'   => 'BookService@listById',
  '/books/update/{id}' => 'BookService@update',
  '/books/remove/{id}' => 'BookService@remove',

  '/drugInformation'             => 'DrugInformationService@index',
  '/drugInformation/create'      => 'DrugInformationService@create',
  '/drugInformation/list/{id}'   => 'DrugInformationService@listById',
  '/drugInformation/update/{id}' => 'DrugInformationService@update',
  '/drugInformation/remove/{id}' => 'DrugInformationService@remove',
];
