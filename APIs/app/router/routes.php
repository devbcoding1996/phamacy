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
  '/drugInformation/listProductAll'   => 'DrugInformationService@listProductAll',
  '/drugInformation/list/{id}'   => 'DrugInformationService@listById',
  '/drugInformation/listName'   => 'DrugInformationService@listByName',
  '/drugInformation/listKeyword'   => 'DrugInformationService@listByKeyword',
  '/drugInformation/update/{id}' => 'DrugInformationService@update',
  '/drugInformation/remove/{id}' => 'DrugInformationService@remove',

  '/drugType'             => 'DrugTypeService@index',
  '/drugType/create'      => 'DrugTypeService@create',
  '/drugType/list/{id}'   => 'DrugTypeService@listById',
  '/drugType/update' => 'DrugTypeService@update',
  '/drugType/remove/{id}' => 'DrugTypeService@remove',

  '/package'             => 'PackageService@index',
  '/package/create'      => 'PackageService@create',
  '/package/list/{id}'   => 'PackageService@listById',
  '/package/update/{id}' => 'PackageService@update',
  '/package/remove/{id}' => 'PackageService@remove',

  '/category'             => 'CategoryService@index',
  '/category/create'      => 'CategoryService@create',
  '/category/list/{id}'   => 'CategoryService@listById',
  '/category/update'      => 'CategoryService@update',
  '/category/remove/{id}' => 'CategoryService@remove',

  '/customer'             => 'CustomerService@index',
  '/customer/create'      => 'CustomerService@create',
  '/customer/listUserId'      => 'CustomerService@listByUserId',
  '/customer/list/{id}'   => 'CustomerService@listById',
  '/customer/update/{id}' => 'CustomerService@update',
  '/customer/remove/{id}' => 'CustomerService@remove',

  '/userCustomer'             => 'UserCustomerService@index',
  '/userCustomer/create'      => 'UserCustomerService@create',
  '/userCustomer/listCustomerId'   => 'UserCustomerService@listByCustomerId',
  '/userCustomer/list/{id}'   => 'UserCustomerService@listById',
  '/userCustomer/update/{id}' => 'UserCustomerService@update',
  '/userCustomer/updateStatus' => 'UserCustomerService@updateStatus',
  '/userCustomer/remove/{id}' => 'UserCustomerService@remove',

  '/orderDrug'             => 'OrderDrugService@index',
  '/orderDrug/create'      => 'OrderDrugService@create',
  '/orderDrug/listUserId'  => 'OrderDrugService@listByUserId',
  '/orderDrug/listCustomerId/{id}'  => 'OrderDrugService@listByCustomerId',
  '/orderDrug/list/{id}'   => 'OrderDrugService@listById',
  '/orderDrug/update'      => 'OrderDrugService@update',
  '/orderDrug/remove/{id}' => 'OrderDrugService@remove',

  '/orderDrugDetail'             => 'OrderDrugDetailService@index',
  '/orderDrugDetail/create'      => 'OrderDrugDetailService@create',
  '/orderDrugDetail/add'      => 'OrderDrugDetailService@add',
  '/orderDrugDetail/list/{id}'   => 'OrderDrugDetailService@listById',
  '/orderDrugDetail/update' => 'OrderDrugDetailService@update',
  '/orderDrugDetail/remove/{id}' => 'OrderDrugDetailService@remove',
];
