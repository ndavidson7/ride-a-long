<?php
spl_autoload_register(function($classname) {
  include "classes/$classname.php";
});

$db = new mysqli(Config::$db["host"], Config::$db["user"], Config::$db["pass"], Config::$db["database"]);

$db->query("set foreign_key_checks = 0;");

$db->query("drop table if exists project_user;");
$db->query("create table project_user (
              id int not null auto_increment,
              first_name nvarchar(255) not null,
              last_name nvarchar(255) not null,
              email varchar(255) not null,
              password nvarchar(255) not null,
              year varchar(30) default '',
              studying varchar(50) default '',
              bio varchar(150) default '',
              primary key (id),
              unique (email)
            );");

$db->query("drop table if exists project_ride;");
$db->query("create table project_ride (
              id int not null auto_increment,
              user int not null, -- the user id who posted this ride
              date date not null,
              time time not null,
              orig_addr varchar(255) not null,
              orig_lat decimal(9,6) not null,
              orig_long decimal(9,6) not null,
              dest_addr varchar(255) not null,
              dest_lat decimal(9,6) not null,
              dest_long decimal(9,6) not null,
              seats_total int not null,
              seats_open int not null,
              info varchar(255),
              primary key (id),
              foreign key (user) references project_user(id) on delete cascade on update cascade
            );");

$db->query("drop table if exists project_riders;");
$db->query("create table project_riders (
              ride int not null,
              user int not null,
              primary key (ride, user),
              foreign key (ride) references project_ride(id) on delete cascade on update cascade,
              foreign key (user) references project_user(id) on delete cascade on update cascade
            );");

$db->query("drop table if exists project_requests;");
$db->query("create table project_requests (
              ride int not null,
              user int not null,
              primary key (ride, user),
              foreign key (ride) references project_ride(id) on delete cascade on update cascade,
              foreign key (user) references project_user(id) on delete cascade on update cascade
            );");

$db->query("drop table if exists project_responses;");
$db->query("create table project_responses (
              ride int not null,
              user int not null,
              response varchar(10) not null,
              primary key (ride, user),
              foreign key (ride) references project_ride(id) on delete cascade on update cascade,
              foreign key (user) references project_user(id) on delete cascade on update cascade
            );");

$db->query("set global event_scheduler = on;");
$db->query("drop event if exists purgeExpiredRides;");
$db->query("create event purgeExpiredRides
              on schedule every 1 hour
              do delete from project_ride where date >= current_date and time >= current_time;"
          );

$db->query("set foreign_key_checks = 1;");
