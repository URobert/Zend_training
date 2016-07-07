-- You will need load your database schema with this SQL.

CREATE TABLE county (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	name VARCHAR (255) NOT NULL
);

CREATE INDEX "id" ON "county" ("id");