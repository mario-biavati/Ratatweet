-- Fill Database Ratatweet
-- _________
use RATATWEET;

INSERT INTO `USER`(IDuser, username,password)
VALUES (8,"Asprosapore","ciao"),
(2,"LERCIO","ciao"),
(3,"CakeSlayer_69","ciao"),
(4,"Luigi","ciao"),
(5,"Marco","ciao"),
(6,"G","ciao"),
(7,"Eritropioetina","ciao");

INSERT INTO RECIPE(IDrecipe, IDuser, ingredients, method)
VALUES (1, 8, '{"Sale":"NO", "Zucchero":"150g"}', "Butta su tutto e cuoci. fine."),
(2, 3, '{"Pomodori":"TUTTI"}', "Spappola i pomodori e poi divorali.");

INSERT INTO POST(IDpost, pic, title, description, IDuser, IDrecipe)
VALUES (1,"default_post.png","Crema Catalana","buonissima cremina dolce",8,1),
(2,"default_post.png","Crema Catalana","Crema non buonissima, di più!",2,1),
(3,"default_post.png","Pasta col Sugo","Un grande classico, semplice ma efficace.",3,2);

INSERT INTO SAVED_RECIPE(IDuser, IDpost)
VALUES (4, 1),
(4, 2);

INSERT INTO COMMENT(text, IDpost, IDuser)
VALUES ("Molto buono!", 1, 8),
("Fantastico", 2, 8),
("Molto buono!", 3, 4),
("Molto buono!", 1, 4),
("Un pò troppo salato", 1, 3);

INSERT INTO RATING(IDuser, IDpost, rating)
VALUES (8, 1, 5),
(2, 1, 4),
(2, 2, 3),
(4, 1, 2),
(4, 2, 2);

INSERT INTO NOTIFICATION(type, IDuser, notifier, IDpost) 
VALUES ("Comment",8,8,1), 
("Comment",8,8,1), 
("Comment",8,8,1);