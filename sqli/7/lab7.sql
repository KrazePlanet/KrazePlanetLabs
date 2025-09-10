CREATE TABLE lab7 (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(50) NOT NULL,
    Referer VARCHAR(255) NOT NULL
);

INSERT INTO lab7 (Username, Referer) VALUES ('John Doe', 'http://example.com');