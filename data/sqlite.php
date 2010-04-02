<?php

// Data for SQLite databases
return array
(
	"DROP TABLE IF EXISTS authors;",
	
	"CREATE TABLE authors (
	  id INTEGER PRIMARY KEY,
	  name TEXT NULL,
	  password TEXT NULL,
	  email TEXT NULL,
	  role_id INTEGER NOT NULL
	);",
	
	"INSERT INTO authors (id,name,email,role_id)
	VALUES (1,'Jonathan Geiger','jonathan@jonathan-geiger.com',1);",
	
	"DROP TABLE IF EXISTS categories;",

	"CREATE TABLE categories (
	  id INTEGER PRIMARY KEY,
	  name TEXT NOT NULL,
	  parent_id INTEGER NOT NULL
	);",

	"INSERT INTO categories (id,name,parent_id)
	VALUES (1,'Category One',0);",

	"INSERT INTO categories (id,name,parent_id)
	VALUES (2,'Category Two',0);",

	"INSERT INTO categories (id,name,parent_id)
	VALUES (3,'Category Three',1);",

	"DROP TABLE IF EXISTS categories_posts;",

	"CREATE TABLE categories_posts (
	  category_id INTEGER NOT NULL,
	  post_id INTEGER NOT NULL
	);",

	"INSERT INTO categories_posts (category_id,post_id)
	VALUES (1,1);",

	"INSERT INTO categories_posts (category_id,post_id)
	VALUES (2,1);",

	"INSERT INTO categories_posts (category_id,post_id)
	VALUES (3,1);",

	"INSERT INTO categories_posts (category_id,post_id)
	VALUES (2,2);",

	"INSERT INTO categories_posts (category_id,post_id)
	VALUES (1,3);",

	"INSERT INTO categories_posts (category_id,post_id)
	VALUES (2,3);",

	"DROP TABLE IF EXISTS posts;",

	"CREATE TABLE posts (
	  id INTEGER PRIMARY KEY,
	  name TEXT NULL,
	  slug TEXT NULL,
	  status TEXT NULL,
	  created INTEGER NULL,
	  updated INTEGER NULL,
	  published INTEGER NULL,
	  author_id INTEGER NULL
	);",

	"INSERT INTO posts (id,name,slug,status,created,updated,published,author_id)
	VALUES (NULL,'First Post','first-post','draft',1264985737,1264985737,1264985737,1);",

	"INSERT INTO posts (id,name,slug,status,created,updated,published,author_id)
	VALUES (NULL,'Second Post','second-post','review',1264985737,1264985737,1264985737,1);",

	"DROP TABLE IF EXISTS roles;",

	"CREATE TABLE roles (
	  id INTEGER PRIMARY KEY,
	  name TEXT NOT NULL
	);",

	"INSERT INTO roles (id,name)
	VALUES (1,'Staff');",

	"INSERT INTO roles (id,name)
	VALUES (2,'Freelancer');",

	"DROP TABLE IF EXISTS files;",

	"CREATE TABLE files (
	  id INTEGER PRIMARY KEY,
	  name TEXT NOT NULL,
	  type TEXT NOT NULL
	);",

	"INSERT INTO files (id,name,type)
	VALUES (1,'Image 1', 'file_image');",

	"INSERT INTO files (id,name,type)
	VALUES (2,'Text File', 'file_text');",
);