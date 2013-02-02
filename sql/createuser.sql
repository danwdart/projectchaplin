CREATE USER 'chaplin'@'localhost' IDENTIFIED BY 'chaplin';
CREATE DATABASE Chaplin;
GRANT ALL PRIVILEGES ON Chaplin.* TO 'chaplin'@'localhost';
FLUSH PRIVILEGES;