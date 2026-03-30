CREATE DATABASE IF NOT EXISTS taken_app;

USE taken_app;

DROP TABLE IF EXISTS doelen;
DROP TABLE IF EXISTS gebruikers;

CREATE TABLE gebruikers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    wachtwoord VARCHAR(255) NOT NULL
);

CREATE TABLE doelen (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gebruiker_id INT NOT NULL,
    tekst VARCHAR(255) NOT NULL,
    voltooid TINYINT(1) NOT NULL DEFAULT 0,
    FOREIGN KEY (gebruiker_id) REFERENCES gebruikers(id)
);