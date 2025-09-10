CREATE TABLE lab8 (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(50) NOT NULL,
    XForwardedFor VARCHAR(255) NOT NULL
);

INSERT INTO lab8 (Username, XForwardedFor) VALUES ('John Doe', '192.168.0.1');