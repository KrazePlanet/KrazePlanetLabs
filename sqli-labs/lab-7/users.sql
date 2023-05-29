CREATE TABLE users (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(50) NOT NULL,
    Referer VARCHAR(255) NOT NULL
);

INSERT INTO users (Username, Referer) VALUES ('John Doe', 'http://example.com');