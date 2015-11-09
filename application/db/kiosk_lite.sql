PRAGMA synchronous = FULL;
PRAGMA journal_mode = MEMORY;
PRAGMA foreign_keys = ON;

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

END TRANSACTION;
