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
    active Tinyint(1),
    activationDate datetime,
    activationCode char(64),
    resetPass Tinyint(1),
    resetPassExpiry datetime,
    resetPassCode char(64)
) ENGINE = INNODB;

drop database imaginest;
drop table users;
select * from users;


select * from users where now() < creationDate;

		