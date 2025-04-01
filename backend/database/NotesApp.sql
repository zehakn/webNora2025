CREATE DATABASE NotesApp;
USE NotesApp;

CREATE TABLE User (
    UserID INT(10) PRIMARY KEY AUTO_INCREMENT,
    Username VARCHAR(255) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    FullName VARCHAR(225) NOT NULL,
    Email VARCHAR(255) NOT NULL UNIQUE,
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Category (
    CategoryID INT(10) PRIMARY KEY AUTO_INCREMENT,
    CategoryName VARCHAR(255) NOT NULL
);


CREATE TABLE Status (
    StatusID INT(10) PRIMARY KEY,
    StatusName VARCHAR(50) NOT NULL 
);


CREATE TABLE Priority (
    PriorityID INT(10) PRIMARY KEY,
    PriorityLevel VARCHAR(50) NOT NULL 
);

CREATE TABLE Notes (
    NoteID INT(10) PRIMARY KEY AUTO_INCREMENT,
    Title VARCHAR(255) NOT NULL,
    Content TEXT NOT NULL,
    UserID INT(10),
    CategoryID INT(10),
    PriorityID INT(10),
    StatusID INT(10),
    FOREIGN KEY (UserID) REFERENCES User(UserID) ON DELETE CASCADE,
    FOREIGN KEY (CategoryID) REFERENCES Category(CategoryID) ON DELETE SET NULL,
    FOREIGN KEY (PriorityID) REFERENCES Priority(PriorityID) ON DELETE SET NULL,
    FOREIGN KEY (StatusID) REFERENCES Status(StatusID) ON DELETE SET NULL
);


INSERT INTO Status (StatusID, StatusName) VALUES
(1, 'New'),
(2, 'In Progress'),
(3, 'Completed');


INSERT INTO Priority (PriorityID, PriorityLevel) VALUES
(1, 'High'),
(2, 'Medium'),
(3, 'Low');
