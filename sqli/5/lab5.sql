CREATE TABLE lab5 (
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(30) NOT NULL,
  city_id INT(6) NOT NULL,
  item_id INT(6) NOT NULL
);

INSERT INTO lab5 (name, city_id, item_id)
VALUES ('John Doe', 1, 1), ('Jane Smith', 1, 2), ('Bob Johnson', 2, 1);