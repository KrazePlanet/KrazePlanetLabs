CREATE DATABASE users;

USE users;

CREATE TABLE users (
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(30) NOT NULL,
  password VARCHAR(30) NOT NULL,
  user_agent VARCHAR(255) NOT NULL
);

INSERT INTO users (username, password, user_agent)
VALUES ('user1', 'password1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3');

INSERT INTO users (username, password, user_agent)
VALUES ('user2', 'password2', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0');

INSERT INTO users (username, password, user_agent)
VALUES ('user3', 'password3', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36');
