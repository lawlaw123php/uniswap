-- Admin Tables

create table tbladminprofile (
adminprofileid int primary key auto_increment,
firstname varchar(50) not null,
lastname varchar(50) not null,
gender varchar(10) not null
);

create table tbladminaccount (
adminaccountid int primary key auto_increment,
adminprofileid int not null,
emailadd varchar(100) not null unique,
username varchar(50) not null unique,
password varchar(255) not null,
foreign key (adminprofileid) references tbladminprofile(adminprofileid)
);

-- Sample INSERT for testing

insert into tbladminprofile(firstname,lastname,gender)
values('System','Administrator','Male');

insert into tbladminaccount(adminprofileid,emailadd,username,password)
values(LAST_INSERT_ID(),'admin@uniswap.local','adminmaster','$2b$12$X1TXKuxiKZ7zKV1YCQS4SuflFfPt5T4eYxSdteOmyJVRq4y7cyxie');

-- Updated ERD Note
-- Admin is separate from USER, BUYER, and SELLER.
-- Relationship: tbladminprofile (1) to tbladminaccount (many) using adminprofileid.
