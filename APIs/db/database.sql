create database if not exists pharmacy_db;

use pharmacy_db;

create table if not exists users (
  id char(36) not null default uuid(),
  name varchar(255) not null,
  mobileNumber varchar(10) not null,
  email varchar(100) not null,
  passwd varchar(8) not null,
  primary key(id)
);
