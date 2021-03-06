-- Dump of table authors
-- ------------------------------------------------------------
DROP TABLE IF EXISTS authors;

CREATE TABLE authors (
  id INTEGER PRIMARY KEY,
  name TEXT NOT NULL,
  password TEXT NULL
  email TEXT NOT NULL,
  role_id INTEGER NOT NULL
);

INSERT INTO authors (id,name,email,role_id)
VALUES (1,'Jonathan Geiger','jonathan@jonathan-geiger.com',1);


-- Dump of table categories
-- ------------------------------------------------------------
DROP TABLE IF EXISTS categories;

CREATE TABLE categories (
  id INTEGER PRIMARY KEY,
  name TEXT NOT NULL,
  parent_id INTEGER NOT NULL
);

INSERT INTO categories (id,name,parent_id)
VALUES (1,'Category One',0);

INSERT INTO categories (id,name,parent_id)
VALUES (2,'Category Two',0);

INSERT INTO categories (id,name,parent_id)
VALUES (3,'Category Three',1);


-- Dump of table categories_posts
-- ------------------------------------------------------------
DROP TABLE IF EXISTS categories_posts;

CREATE TABLE categories_posts (
  category_id INTEGER NOT NULL,
  post_id INTEGER NOT NULL
);

INSERT INTO categories_posts (category_id,post_id)
VALUES (1,1);
	
INSERT INTO categories_posts (category_id,post_id)
VALUES (2,1);

INSERT INTO categories_posts (category_id,post_id)
VALUES (3,1);

INSERT INTO categories_posts (category_id,post_id)
VALUES (2,2);

INSERT INTO categories_posts (category_id,post_id)
VALUES (1,3);

INSERT INTO categories_posts (category_id,post_id)
VALUES (2,3);


-- Dump of table posts
-- ------------------------------------------------------------
DROP TABLE IF EXISTS posts;

CREATE TABLE posts (
  id INTEGER PRIMARY KEY,
  name TEXT NOT NULL,
  slug TEXT NOT NULL,
  status TEXT NOT NULL,
  created INTEGER NULL,
  updated INTEGER NULL,
  published INTEGER NULL,
  author_id INTEGER NOT NULL,
  approved_by INTEGER NULL
);

INSERT INTO posts (id,name,slug,status,created,updated,published,author_id,approved_by)
VALUES (1,'First Post','first-post','draft',1264985737,1264985737,1264985737,1,NULL);

INSERT INTO posts (id,name,slug,status,created,updated,published,author_id)
VALUES (2,'Second Post','second-post','review',1264985737,1264985737,1264985737,1,1);

-- Dump of table roles
-- ------------------------------------------------------------
DROP TABLE IF EXISTS roles;

CREATE TABLE roles (
  id INTEGER PRIMARY KEY,
  name TEXT NOT NULL
);

INSERT INTO roles (id,name)
VALUES (1,'Staff');

INSERT INTO roles (id,name)
VALUES (2,'Freelancer');
