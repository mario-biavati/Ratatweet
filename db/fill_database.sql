-- Fill Database Ratatweet
-- _________
use RATATWEET;

INSERT INTO USER(IDuser,username,password)
VALUES (1,"Asprosapore","ciao"),
(2,"LERCIO","ciao"),
(3,"CakeSlayer_69","ciao"),
(4,"Luigi","ciao");

INSERT INTO POST(IDpost, pic, title, description, IDuser, IDrecipe)
VALUES (1,"default_post.png","Crema Catalana","buonissima cremina dolce",1,1),
(2,"default_post.png","Crema Catalana","Crema non buonissima, di più!",2,1),
(3,"default_post.png","Pasta col Sugo","Un grande classico, semplice ma efficace.",3,2);

INSERT INTO RECIPE(IDpost, ingredients, method)
VALUES (1,'{"Sale":"NO","Zucchero":"150g"}', "Butta su tutto e cuoci. fine."),
(2, '{"Pomodori":"TUTTI"}', "Spappola i pomodori e poi divorali.");