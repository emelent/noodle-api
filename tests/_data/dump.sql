BEGIN TRANSACTION;
CREATE TABLE "users" ("id" integer not null primary key autoincrement, "email" varchar not null, "password" varchar not null, "created_at" datetime null, "updated_at" datetime null);
CREATE TABLE "migrations" ("id" integer not null primary key autoincrement, "migration" varchar not null, "batch" integer not null);
INSERT INTO `migrations` VALUES (16,'2017_02_04_142210_create_users_table',1);
CREATE UNIQUE INDEX "users_email_unique" on "users" ("email");
COMMIT;
