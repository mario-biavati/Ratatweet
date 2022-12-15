-- *********************************************
-- * SQL MySQL generation                      
-- *--------------------------------------------
-- * DB-MAIN version: 11.0.2              
-- * Generator date: Sep 14 2021              
-- * Generation date: Fri Jun  3 12:24:06 2022 
-- * LUN file: C:\Users\fabbr\OneDrive\Documenti\Università\anno_2\Basi di dati\Elaborato\DATABASE_PROJECT_rel.lun 
-- * Schema: GESTIONALE_PER_AGENZIE_Rel/1-1-1 
-- ********************************************* 

-- Database Section
-- ________________ 
drop database if exists ratatweet;

create database if not exists RATATWEET;
use RATATWEET;


-- Tables Section
-- _____________ 

create table USER (
     IDuser bigint not null,
     username varchar(50) not null,
     password varchar(50) not null, 
     bio varchar(50),
     pic varchar(50) default "default_user.png",
     date DATETIME DEFAULT CURRENT_TIMESTAMP,
     constraint IDUSERS_1 unique (username),
     constraint IDUSERS primary key (IDuser));

create table RECIPE (
     IDpost bigint not null,
     ingredients varchar(150) not null,
     method varchar(150) not null,
     PRIMARY KEY (IDPost));

create table POST (
     IDPost bigint not null,
     pic varchar(50),
     title varchar(50) not null, 
     description varchar(150) not null, 
     date DATETIME DEFAULT CURRENT_TIMESTAMP,
     IDuser bigint not null,
     IDrecipe bigint,
     FOREIGN KEY (IDuser) references USER(IDuser),
     FOREIGN KEY (IDrecipe) references USER(IDuser),
     PRIMARY KEY (IDPost));

alter table RECIPE add constraint FKIDpost
     foreign key (IDpost)
     references POST (IDpost);

create table COMMENT (
     IDcomment bigint not null,
     text varchar(50),
     date DATETIME DEFAULT CURRENT_TIMESTAMP,
     IDpost bigint not null,
     IDuser bigint not null,
     IDparent bigint,
     FOREIGN KEY (IDparent) references COMMENT(IDcomment),
     PRIMARY KEY (IDcomment));

create table RATING (
     IDuser bigint not null,
     IDpost bigint not null,
     rating int not null,
     date DATETIME DEFAULT CURRENT_TIMESTAMP,
     FOREIGN KEY (IDuser) references USER(IDuser),
     FOREIGN KEY (IDpost) references POST(IDpost),
     PRIMARY KEY (IDuser, IDpost));

create table FOLLOWER (
     IDfollower bigint not null,
     IDfollowed bigint not null,
     notification boolean default '1',
     PRIMARY KEY (IDfollower, IDfollowed));

create table NOTIFICATION (
     IDnotification bigint not null,
     type ENUM ('Follow','Comment','Post', 'Recipe'),
     IDuser bigint not null,  
     notifier bigint not null,  
     IDpost bigint,  
     date DATETIME DEFAULT CURRENT_TIMESTAMP,
     FOREIGN KEY (IDuser) references USER(IDuser),
     FOREIGN KEY (notifier) references USER(IDuser),
     FOREIGN KEY (IDpost) references POST(IDpost),
     PRIMARY KEY (IDnotification));