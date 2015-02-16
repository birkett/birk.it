--
-- File generated with SQLiteStudio v3.0.2 on Mon Feb 16 11:56:13 2015
--
-- Text encoding used: windows-1252
--
PRAGMA foreign_keys = off;
BEGIN TRANSACTION;

-- Table: urls
CREATE TABLE urls (original_url VARCHAR (2048) NOT NULL, short_url VARCHAR (50) NOT NULL PRIMARY KEY);
INSERT INTO urls (original_url, short_url) VALUES ('www.a-birkett.co.uk/blog/9', 'bt6h0y53');
INSERT INTO urls (original_url, short_url) VALUES ('www.a-birkett.co.uk/blog/8', 'lg72o6pb');
INSERT INTO urls (original_url, short_url) VALUES ('www.a-birkett.co.uk/blog/7', 'i0fz3qz9');
INSERT INTO urls (original_url, short_url) VALUES ('www.a-birkett.co.uk/blog/5', 'ewgzvbnk');

COMMIT TRANSACTION;
