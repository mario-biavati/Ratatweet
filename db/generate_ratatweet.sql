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

create table `USER` (
     IDuser bigint not null auto_increment,
     username varchar(50) not null,
     password varchar(50) not null, 
     bio varchar(50),
     pic mediumblob,
     date DATETIME DEFAULT CURRENT_TIMESTAMP,
     constraint IDUSERS_1 unique (username),
     constraint IDUSERS primary key (IDuser));

create table RECIPE (
     IDrecipe bigint not null auto_increment,
     ingredients varchar(150) not null,
     method TEXT not null,
     IDuser bigint not null,
     FOREIGN KEY (IDuser) references USER(IDuser),
     PRIMARY KEY (IDrecipe));

create table POST (
     IDPost bigint not null auto_increment,
     pic mediumblob,
     title char(50) not null, 
     description char(500), 
     date DATETIME DEFAULT CURRENT_TIMESTAMP,
     IDuser bigint not null,
     IDrecipe bigint not null,
     FULLTEXT KEY title (title, description), 
     FOREIGN KEY (IDuser) references USER(IDuser),
     FOREIGN KEY (IDrecipe) references RECIPE(IDrecipe),
     PRIMARY KEY (IDPost));

create table COMMENT (
     IDcomment bigint not null auto_increment,
     text TINYTEXT,
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
     notification boolean default TRUE,
     FOREIGN KEY (IDfollower) references USER(IDuser),
     FOREIGN KEY (IDfollowed) references USER(IDuser),
     PRIMARY KEY (IDfollower, IDfollowed));

create table NOTIFICATION (
     IDnotification bigint not null auto_increment,
     type ENUM ('Follow','Comment','Post', 'Recipe'),
     IDuser bigint not null,  
     notifier bigint not null,  
     IDpost bigint,  
     date DATETIME DEFAULT CURRENT_TIMESTAMP,
     FOREIGN KEY (IDuser) references USER(IDuser),
     FOREIGN KEY (notifier) references USER(IDuser),
     FOREIGN KEY (IDpost) references POST(IDpost),
     PRIMARY KEY (IDnotification));

create table CATEGORY (
    IDcategory bigint not null auto_increment,
    name varchar(50) not null, 
    pic varchar(50) default "default_category.png",
    PRIMARY KEY (IDcategory));

create table CATEGORY_RECIPE (
    IDcategory bigint not null,
    IDrecipe bigint not null,
    FOREIGN KEY (IDcategory) references CATEGORY(IDcategory),
    FOREIGN KEY (IDrecipe) references RECIPE(IDrecipe),
    PRIMARY KEY (IDcategory, IDrecipe));

create table SAVED_RECIPE (
     IDuser bigint not null,
     IDpost bigint not null,
     FOREIGN KEY (IDuser) references USER(IDuser),
     FOREIGN KEY (IDpost) references POST(IDpost),
     PRIMARY KEY (IDuser, IDpost));

create table `LIKES` (
     IDcomment bigint not null,
     IDuser bigint not null,
     FOREIGN KEY (IDuser) references USER(IDuser),
     FOREIGN KEY (IDcomment) references COMMENT(IDcomment),
     PRIMARY KEY(IDcomment,IDuser));

-- CREATE VIEW INFOPOST AS 
--      SELECT P.IDpost, COALESCE(AVG(P.rating), 0) as avgRating, COUNT(C.IDcomment) as numComments 
--      FROM (POST JOIN RATING ON POST.IDpost = RATING.IDpos) AS P, COMMENT AS C 
--      GROUP BY P.IDpost
--      HAVING P.IDpost = C.IDpost;
    
CREATE VIEW INFOPOST AS
     SELECT A.IDpost, A.avgRating, B.numComments
     FROM (SELECT POST.IDpost, COALESCE(AVG(rating), 0) as avgRating
           FROM POST LEFT JOIN RATING ON POST.IDpost = RATING.IDpost
           GROUP BY POST.IDpost) AS A,
          (SELECT POST.IDpost, COUNT(IDcomment) as numComments
           FROM POST LEFT JOIN COMMENT ON POST.IDpost = COMMENT.IDpost
           GROUP BY POST.IDpost) AS B
     WHERE A.IDpost = B.IDpost;

CREATE VIEW INFOCOMMENT AS
     SELECT IDcomment, COUNT(IDuser) AS numLikes
     FROM `LIKES`
     GROUP BY IDcomment;