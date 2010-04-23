<?php

return array(
	
	"DROP TABLE IF EXISTS authors;",

	"CREATE TABLE authors (
	  id serial,
	  name varchar(255) NULL,
	  password varchar(255) NULL,
	  email varchar(255) NULL,
	  role_id bigint NOT NULL,
	  PRIMARY KEY (id)
	);",

	"INSERT INTO authors (id,name,email,role_id)
	VALUES
		(1,'Jonathan Geiger','jonathan@jonathan-geiger.com',1);",
		
	"SELECT setval('authors_id_seq', (select max(id) + 1 from authors));",

	"DROP TABLE IF EXISTS categories;",

	"CREATE TABLE categories (
	  id serial,
	  name varchar(255) NOT NULL,
	  parent_id bigint NOT NULL,
	  PRIMARY KEY (id)
	);",

	"INSERT INTO categories (id,name,parent_id)
	VALUES
		(1,'Category One',0),
		(2,'Category Two',0),
		(3,'Category Three',1);",
		
	"SELECT setval('categories_id_seq', (select max(id) + 1 from categories));",
	
	"DROP TABLE IF EXISTS categories_posts;",

	"CREATE TABLE categories_posts (
	  category_id bigint NOT NULL,
	  post_id bigint NOT NULL
	);",

	"INSERT INTO categories_posts (category_id,post_id)
	VALUES
		(1,1),
		(2,1),
		(3,1),
		(2,2),
		(1,3),
		(2,3);",

	"DROP TABLE IF EXISTS posts;",

	"CREATE TABLE posts (
	  id serial,
	  name varchar(255) NULL,
	  slug varchar(255) NULL,
	  status varchar(255) NULL,
	  created bigint DEFAULT NULL,
	  updated bigint DEFAULT NULL,
	  published bigint DEFAULT NULL,
	  author_id bigint DEFAULT NULL,
	  approved_by bigint NULL,
	  PRIMARY KEY (id),
	  CHECK (status IN ('draft', 'review', 'published'))
	);",

	"INSERT INTO posts (id,name,slug,status,created,updated,published,author_id,approved_by)
	VALUES
		(1,'First Post','first-post','draft',1264985737,1264985737,1264985737,1,NULL),
		(2,'Second Post','second-post','review',1264985737,1264985737,1264985737,1,1);",

	"SELECT setval('posts_id_seq', (select max(id) + 1 from posts));",

	"DROP TABLE IF EXISTS roles;",

	"CREATE TABLE roles (
	  id serial,
	  name varchar(255) NOT NULL,
	  PRIMARY KEY (id)
	);",

	"INSERT INTO roles (id,name)
	VALUES
		(1,'Staff'),
		(2,'Freelancer');",
		
	"SELECT setval('roles_id_seq', (select max(id) + 1 from roles));",
);