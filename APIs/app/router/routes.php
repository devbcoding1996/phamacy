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

  '/drugType'             => 'DrugTypeService@index',
  '/drugType/create'      => 'DrugTypeService@create',
  '/drugType/list/{id}'   => 'DrugTypeService@listById',
  '/drugType/update/{id}' => 'DrugTypeService@update',
  '/drugType/remove/{id}' => 'DrugTypeService@remove',

  '/package'             => 'PackageService@index',
  '/package/create'      => 'PackageService@create',
  '/package/list/{id}'   => 'PackageService@listById',
  '/package/update/{id}' => 'PackageService@update',
  '/package/remove/{id}' => 'PackageService@remove',

  '/category'             => 'CategoryService@index',
  '/category/create'      => 'CategoryService@create',
  '/category/list/{id}'   => 'CategoryService@listById',
  '/category/update/{id}' => 'CategoryService@update',
  '/category/remove/{id}' => 'CategoryService@remove',

  '/customer'             => 'CustomerService@index',
  '/customer/create'      => 'CustomerService@create',
  '/customer/list/{id}'   => 'CustomerService@listById',
  '/customer/update/{id}' => 'CustomerService@update',
  '/customer/remove/{id}' => 'CustomerService@remove',

  '/userCustomer'             => 'UserCustomerService@index',
  '/userCustomer/create'      => 'UserCustomerService@create',
  '/userCustomer/list/{id}'   => 'UserCustomerService@listById',
  '/userCustomer/update/{id}' => 'UserCustomerService@update',
  '/userCustomer/remove/{id}' => 'UserCustomerService@remove',
];
