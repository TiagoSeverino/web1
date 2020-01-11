DROP DATABASE IF EXISTS PlaceU;
CREATE DATABASE PlaceU;

USE PlaceU;

SET SQL_MODE='ALLOW_INVALID_DATES';

DROP TABLE IF EXISTS Booking;
DROP TABLE IF EXISTS Room;
/* DROP TABLE IF EXISTS Building; */
DROP TABLE IF EXISTS OrganizationMember;
DROP TABLE IF EXISTS Organization;

CREATE TABLE Organization
(
    ID INT NOT NULL AUTO_INCREMENT,
    Name VARCHAR(64) NOT NULL,
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (ID)
);

CREATE TABLE OrganizationMember
(
    OrganizationID INT NOT NULL,
    UserID INT NOT NULL,
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (UserID) REFERENCES User (ID) ON DELETE CASCADE,
    FOREIGN KEY (OrganizationID) REFERENCES Organization (ID) ON DELETE CASCADE,
    CONSTRAINT UC_OrganizationMember UNIQUE (OrganizationID,UserID)
);

/*
CREATE TABLE Building
(
    ID INT NOT NULL AUTO_INCREMENT,
    OrganizationID INT NOT NULL,
    Name VARCHAR(64) NOT NULL,
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (OrganizationID) REFERENCES Organization (ID) ON DELETE CASCADE,
    PRIMARY KEY (ID)
)

CREATE TABLE Room
(
    ID INT NOT NULL AUTO_INCREMENT,
    BuildingID INT NOT NULL,
    Name VARCHAR(64) NOT NULL,
    Chairs INT NOT NULL,
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (BuildingID) REFERENCES Building (ID) ON DELETE CASCADE,
    PRIMARY KEY (ID)
);
*/


CREATE TABLE Room
(
    ID INT NOT NULL AUTO_INCREMENT,
    OrganizationID INT NOT NULL,
    Name VARCHAR(64) NOT NULL,
    Chairs INT NOT NULL,
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (OrganizationID) REFERENCES Organization (ID) ON DELETE CASCADE,
    PRIMARY KEY (ID)
);

CREATE TABLE Booking
(
    ID INT NOT NULL AUTO_INCREMENT,
    RoomID INT NOT NULL,
    UserID INT NOT NULL,
    CheckIn TIMESTAMP NOT NULL,
    CheckOut TIMESTAMP NOT NULL, 
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (UserID) REFERENCES User (ID) ON DELETE CASCADE,
    FOREIGN KEY (RoomID) REFERENCES Room (ID) ON DELETE CASCADE,
    PRIMARY KEY (ID)
);