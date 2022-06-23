-- DDL: Data Definition Language (SQL CREATE TABLE Statements)

CREATE TABLE kategorie
(
    id VARCHAR(2),
    name VARCHAR(30) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE meldung
(
    id INT AUTO_INCREMENT,
    kategorie_id VARCHAR(2) NOT NULL,
    schaden_datum DATE NOT NULL,
    beschreibung VARCHAR(10000) NOT NULL,
    ip VARCHAR(100) NOT NULL,
    browser VARCHAR(500) NOT NULL,
    kundennummer INT NOT NULL,
    dokument_pfad VARCHAR(1000) NOT NULL,
    dokument_dateiname VARCHAR(1000) NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_meldung_kategorie_id FOREIGN KEY (kategorie_id) REFERENCES kategorie(id)
);

CREATE TABLE rolle
(
    id VARCHAR(5),
    name VARCHAR(30),
    PRIMARY KEY(id)
);

CREATE TABLE benutzer
(
    id INT AUTO_INCREMENT,
    rolle_id VARCHAR(5) NOT NULL,
    email VARCHAR(300) NOT NULL,
    passwort VARCHAR(300) NOT NULL,
    vorname VARCHAR(100) NOT NULL,
    nachname VARCHAR(100) NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_benutzer_rolle_id FOREIGN KEY (rolle_id) REFERENCES rolle(id),
    UNIQUE KEY(email)
);