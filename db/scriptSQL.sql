create database imaginest; 
use imaginest; 

create table if not exists users(
	idUser int not null AUTO_INCREMENT primary key,
    mail varchar(40) unique,
    username varchar(16) unique,
    passHash varchar(60),
    userFirstName varchar(60),
    userLastName varchar(120),
    creationDate datetime default current_timestamp,
    lastSignIn datetime,
    removeDate datetime,
    active Tinyint(1)
) ENGINE = INNODB;
drop database imaginest;
drop table users;
select * from users;


