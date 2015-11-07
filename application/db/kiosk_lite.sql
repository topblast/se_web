PRAGMA synchronous = OFF;
PRAGMA journal_mode = MEMORY;
BEGIN TRANSACTION;
CREATE TABLE "ingredients" (
  "ing_id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "ing_name" varchar(64) NOT NULL,
  "ing_available" tinyint(1) NOT NULL,
  "ing_stock" int(11) NOT NULL
);
CREATE TABLE "log_ingredients" (
  "log_id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "log_staff_id" int(11) NOT NULL,
  "log_datetime" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "log_type" text  NOT NULL,
  "ing_id" int(11) NOT NULL,
  "ing_name" varchar(64) NOT NULL,
  "ing_available" tinyint(1) NOT NULL,
  "ing_stock" int(11) NOT NULL,
  CONSTRAINT "log_ingredients_ibfk_2" FOREIGN KEY ("log_staff_id") REFERENCES "staff" ("staff_id")
);
CREATE TABLE "log_menu_ingredients" (
  "log_id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "log_staff_id" int(11) NOT NULL,
  "log_datetime" datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "log_type" text  NOT NULL,
  "rel_id" int(11) NOT NULL,
  "menu_id" int(11) NOT NULL,
  "ing_id" int(11) NOT NULL,
  CONSTRAINT "log_menu_ingredients_ibfk_2" FOREIGN KEY ("log_staff_id") REFERENCES "staff" ("staff_id")
);
CREATE TABLE "log_menu_items" (
  "log_id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "log_staff_id" int(11) NOT NULL,
  "log_datetime" datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "log_type" text  NOT NULL,
  "menu_id" int(11) NOT NULL,
  "menu_name" varchar(64) NOT NULL,
  "menu_price" float NOT NULL,
  "menu_image" float,
  CONSTRAINT "log_menu_items_ibfk_2" FOREIGN KEY ("log_staff_id") REFERENCES "staff" ("staff_id")
);
CREATE TABLE "menu_ingredients" (
  "rel_id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "menu_id" int(11) NOT NULL,
  "ing_id" int(11) NOT NULL,
  CONSTRAINT "menu_ingredients_ibfk_1" FOREIGN KEY ("menu_id") REFERENCES "menu_items" ("menu_id") ON DELETE CASCADE,
  CONSTRAINT "menu_ingredients_ibfk_2" FOREIGN KEY ("ing_id") REFERENCES "ingredients" ("ing_id")
);
CREATE TABLE "menu_items" (
  "menu_id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "menu_name" varchar(64) NOT NULL,
  "menu_price" float NOT NULL,
  "menu_image" float DEFAULT NULL
);
CREATE TABLE "staff" (
  "staff_id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "staff_username" varchar(64) NOT NULL,
  "staff_firstname" varchar(64) NOT NULL,
  "staff_lastname" varchar(64) NOT NULL,
  "staff_passwordhash" varchar(128) NOT NULL,
  "staff_salt" blob NOT NULL,
  "staff_admin" tinyint(1) NOT NULL,
  "staff_lastlogged" datetime DEFAULT NULL
);
INSERT INTO "staff" VALUES (0,'test','Rashawn','Clarke','098f6bcd4621d373cade4e832627b4f6','',1,NULL);
CREATE INDEX "log_ingredients_log_staff_id" ON "log_ingredients" ("log_staff_id","log_datetime","log_type","ing_id");
CREATE INDEX "log_ingredients_ing_id" ON "log_ingredients" ("ing_id");
CREATE INDEX "log_menu_ingredients_log_staff_id" ON "log_menu_ingredients" ("log_staff_id","log_datetime","log_type","rel_id","menu_id","ing_id");
CREATE INDEX "log_menu_ingredients_rel_id" ON "log_menu_ingredients" ("rel_id");
CREATE INDEX "ingredients_ing_available" ON "ingredients" ("ing_available","ing_stock");
CREATE INDEX "staff_staff_username" ON "staff" ("staff_username");
CREATE INDEX "log_menu_items_log_staff_id" ON "log_menu_items" ("log_staff_id","log_datetime","log_type","menu_id");
CREATE INDEX "log_menu_items_menu_id" ON "log_menu_items" ("menu_id");
CREATE INDEX "menu_ingredients_menu_id" ON "menu_ingredients" ("menu_id","ing_id");
CREATE INDEX "menu_ingredients_ing_id" ON "menu_ingredients" ("ing_id");
/*
CREATE PROCEDURE del_inglink(id integer, staff INT) AS
BEGIN
	INSERT INTO log_menu_ingredients (log_staff_id,log_type,rel_id,menu_id,ing_id) SELECT staff as s , 'delete' as t, id as ident, i.menu_id, i.ing_id FROM menu_ingredients AS i WHERE i.rel_id = id;

	DELETE FROM menu_ingredients WHERE rel_id = id;
END;

CREATE PROCEDURE del_ingredients(id INT, staff INT)
  AS
BEGIN
	INSERT INTO log_ingredients (log_staff_id,log_type,ing_id,ing_name,ing_available,ing_stock) SELECT staff as s , 'delete' as t, id as ident, i.ing_name, i.ing_available, i.ing_stock FROM ingredients AS i WHERE i.ing_id = id;

	DELETE FROM ingredients WHERE ing_id = id;
END;

CREATE PROCEDURE mod_inglink(id INT, m_id INT, i_id INT)
  AS
BEGIN
	UPDATE menu_ingredients SET menu_id=m_id, ing_id=i_id WHERE rel_id = id;

	INSERT INTO log_menu_ingredients (log_staff_id,log_type,rel_id,menu_id,ing_id) VALUES (staff, 'modify', id, m_id, i_id);
END;

CREATE PROCEDURE mod_ingredients(id int, ig_name VARCHAR(64), available BOOLEAN, stock INT, staff INT)
  AS
BEGIN
	UPDATE ingredients SET ing_name=ig_name, ing_available=available, ing_stock=stock WHERE ing_id = id;

	INSERT INTO log_ingredients (log_staff_id,log_type,ing_id,ing_name,ing_available,ing_stock) VALUES (staff, 'modify', id, ig_name, available, stock);
END

CREATE PROCEDURE new_inglink(m_id INT, i_id INT)
  AS
BEGIN	 
	INSERT INTO menu_ingredients (menu_id, ing_id) VALUES (m_id, i_id);

	INSERT INTO log_menu_ingredients (log_staff_id,log_type,rel_id,menu_id,ing_id) VALUES (staff, 'insert', LAST_INSERT_ID(), m_id, i_id);
END

CREATE PROCEDURE new_ingredients(ig_name VARCHAR(64), available BOOLEAN, stock INT, staff INT)
  AS
BEGIN	 
	INSERT INTO ingredients (ing_name, ing_available, ing_stock) VALUES (ig_name, available, stock);

	INSERT INTO log_ingredients (log_staff_id,log_type,ing_id,ing_name,ing_available,ing_stock) VALUES (staff, 'insert', LAST_INSERT_ID(), ig_name, available, stock);
END
*/
END TRANSACTION;
