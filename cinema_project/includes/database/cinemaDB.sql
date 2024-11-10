DROP DATABASE IF EXISTS CinemaDB;
CREATE DATABASE CinemaDB;
USE CinemaDB;

CREATE TABLE UserPostalCode (
    postalCode VARCHAR(4) NOT NULL PRIMARY KEY,
    city VARCHAR(80) NOT NULL
);

CREATE TABLE CinemaPostalCode (
    postalCode VARCHAR(4) NOT NULL PRIMARY KEY,
    city VARCHAR(80) NOT NULL
);

CREATE TABLE Cinema (
    cinemaID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phoneNumber VARCHAR(15),
    email VARCHAR(100),
    street VARCHAR(100),
    postalCode VARCHAR(4),
    description TEXT,
    FOREIGN KEY (postalCode) REFERENCES CinemaPostalCode(postalCode)
);

CREATE TABLE User (
    userID INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(80) NOT NULL,
    lastName VARCHAR(80) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phoneNumber VARCHAR(11),
    password VARCHAR(100) NOT NULL,
    street VARCHAR(100),
    postalCode VARCHAR(4),
    FOREIGN KEY (postalCode) REFERENCES UserPostalCode(postalCode)
);

CREATE TABLE Movie (
    movieID INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(60) NOT NULL,
    genre VARCHAR(60) NOT NULL,
    runtime INT NOT NULL,
    language ENUM('English', 'Danish') NOT NULL,
    languageFlagPath VARCHAR(255),
    ageRating VARCHAR(3),
    description TEXT,
    imagePath VARCHAR(255),
    tagType ENUM('None', 'Hot New Movie', 'Movie of the Week') DEFAULT 'None'
);


CREATE TABLE ShowTime (
    showTimeID INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    time TIME NOT NULL,
    room VARCHAR(3) NOT NULL,
    movieID INT,
    FOREIGN KEY (movieID) REFERENCES Movie(movieID) ON DELETE CASCADE
);

CREATE TABLE Booking (
    bookingID INT AUTO_INCREMENT PRIMARY KEY,
    paymentMethod ENUM('CreditCard', 'Cash') NOT NULL,
    userID INT,
    showTimeID INT,
    FOREIGN KEY (userID) REFERENCES User(userID),
    FOREIGN KEY (showTimeID) REFERENCES ShowTime(showTimeID)
);

CREATE TABLE Seat (
    seatID INT AUTO_INCREMENT PRIMARY KEY,
    seatNumber INT NOT NULL,
    seatRow VARCHAR(1) NOT NULL,
    isBooked BOOLEAN DEFAULT FALSE
);

CREATE TABLE Reserves (
    bookingID INT,
    seatID INT,
    bookingDate DATE NOT NULL,
    bookingTime TIME NOT NULL,
    PRIMARY KEY (bookingID, seatID),
    FOREIGN KEY (bookingID) REFERENCES Booking(bookingID),
    FOREIGN KEY (seatID) REFERENCES Seat(seatID)
);


CREATE TABLE News (
    newsID INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    content TEXT NOT NULL, 
    category ENUM('News', 'Update', 'Promotion') NOT NULL,
    cinemaID INT,
    FOREIGN KEY (cinemaID) REFERENCES Cinema(cinemaID)
);


INSERT INTO CinemaPostalCode (postalCode, city) 
VALUES ('6700', 'Esbjerg');

INSERT INTO Cinema (name, phoneNumber, email, street, postalCode) VALUES
('Dream Screen', '1234567890', 'contact@dreamscreen.com', 'Citygade 55', '6700');


INSERT INTO UserPostalCode (postalCode, city) VALUES 
('6700', 'Esbjerg'),
('6740', 'Esbjerg'),
('6760', 'Esbjerg');

INSERT INTO User (firstName, lastName, email, phoneNumber, password, street, postalCode) 
VALUES
('Milan', 'Dober', 'mil@outlook.com', '45 60 88 00', '123456', 'Yellow 33', '6700'),
('Alice', 'Brown', 'alice.b@email.com', '3456789012', 'password_hash_3', 'Pine St 303', '6740'),
('Jane', 'Smith', 'jane.smith@email.com', '2345678901', 'password_hash_2', 'Oak St 202', '6760');


INSERT INTO Movie (title, genre, languageFlagPath, language, ageRating, runTime, description)
VALUES 
('A Journey Beyond', 'Sci-Fi', '../media/flags/english_flag.jpg', 'English', '12+', '120', 'After a hidden relationship during their final year of school, Muslim teenager Saja secretly takes her best friends to meet Charlie and his private school mates for a night out at Australia’s most infamous party, Schoolies Week. With their Romeo and Juliet romance blossoming, Saja and Charlie wake to discover a double murder that jolts them to their core… then sends their tribes to war.'),
('Mystery of the Woods', 'Thriller', '../media/flags/danish_flag.png', 'Danish', '12+', '95', 'After a hidden relationship during their final year of school, Muslim teenager Saja secretly takes her best friends to meet Charlie and his private school mates for a night out at Australia’s most infamous party, Schoolies Week. With their Romeo and Juliet romance blossoming, Saja and Charlie wake to discover a double murder that jolts them to their core… then sends their tribes to war.'),
('Love & Laughter', 'Comedy', '../media/flags/english_flag.jpg', 'English', '18+', '110', 'After a hidden relationship during their final year of school, Muslim teenager Saja secretly takes her best friends to meet Charlie and his private school mates for a night out at Australia’s most infamous party, Schoolies Week. With their Romeo and Juliet romance blossoming, Saja and Charlie wake to discover a double murder that jolts them to their core… then sends their tribes to war.');


INSERT INTO ShowTime (movieID, date, time, room)
VALUES 

(1, '2024-11-04', '14:00:00', '2'),
(1, '2024-11-04', '17:00:00', '2'),
(1, '2024-11-04', '18:00:00', '2'),
(1, '2024-11-04', '19:00:00', '2'),
(1, '2024-11-04', '20:00:00', '2'),
(2, '2024-11-04', '14:00:00', '2'),
(2, '2024-11-04', '17:00:00', '2'),
(2, '2024-11-04', '18:00:00', '2'),
(2, '2024-11-04', '19:00:00', '2'),
(2, '2024-11-04', '20:00:00', '2'),
(3, '2024-11-04', '14:00:00', '2'),
(3, '2024-11-04', '17:00:00', '2'),
(3, '2024-11-04', '18:00:00', '2'),
(3, '2024-11-04', '19:00:00', '2'),
(3, '2024-11-04', '20:00:00', '2'),
(1, '2024-11-05', '14:00:00', '1'),
(1, '2024-11-05', '16:00:00', '1'),
(1, '2024-11-05', '17:30:00', '1'),
(1, '2024-11-05', '20:00:00', '1'),
(2, '2024-11-05', '14:00:00', '1'),
(2, '2024-11-05', '16:00:00', '1'),
(2, '2024-11-05', '17:30:00', '1'),
(2, '2024-11-05', '20:00:00', '1'),
(3, '2024-11-05', '14:00:00', '1'),
(3, '2024-11-05', '16:00:00', '1'),
(3, '2024-11-05', '17:30:00', '1'),
(3, '2024-11-05', '20:00:00', '1'),
(1, '2024-11-06', '14:00:00', '1'),
(1, '2024-11-06', '16:00:00', '1'),
(1, '2024-11-06', '17:30:00', '1'),
(1, '2024-11-06', '20:00:00', '1'),
(2, '2024-11-06', '14:00:00', '1'),
(2, '2024-11-06', '16:00:00', '1'),
(2, '2024-11-06', '17:30:00', '1'),
(2, '2024-11-06', '20:00:00', '1'),
(3, '2024-11-06', '14:00:00', '1'),
(3, '2024-11-06', '16:00:00', '1'),
(3, '2024-11-06', '17:30:00', '1'),
(3, '2024-11-06', '20:00:00', '1'),
(2, '2024-11-05', '21:00:00', '2'),
(3, '2024-11-05', '15:00:00', '1'),
(3, '2024-11-05', '16:30:00', '1'),
(3, '2024-11-05', '18:00:00', '1'),
(3, '2024-11-05', '20:00:00', '1'),
(3, '2024-11-05', '21:00:00', '1'),
(1, '2024-11-06', '14:00:00', '1'),
(1, '2024-11-06', '16:00:00', '1'),
(1, '2024-11-06', '17:30:00', '1'),
(1, '2024-11-07', '20:00:00', '1'),
(2, '2024-11-07', '14:00:00', '2'),
(2, '2024-11-07', '17:00:00', '2'),
(2, '2024-11-07', '18:00:00', '2'),
(2, '2024-11-07', '19:00:00', '2'),
(2, '2024-11-08', '20:00:00', '2'),
(2, '2024-11-08', '21:00:00', '2'),
(3, '2024-11-09', '15:00:00', '1'),
(3, '2024-11-09', '16:30:00', '1'),
(3, '2024-11-09', '18:00:00', '1'),
(3, '2024-11-09', '20:00:00', '1'),
(3, '2024-11-10', '21:00:00', '1'),
(3, '2024-11-11', '21:00:00', '1'),
(2, '2024-11-11', '21:00:00', '1'),

-- 2024-11-12
(1, '2024-11-12', '14:00:00', '2'),
(1, '2024-11-12', '17:00:00', '2'),
(1, '2024-11-12', '18:00:00', '2'),
(1, '2024-11-12', '19:00:00', '2'),
(1, '2024-11-12', '20:00:00', '2'),
(2, '2024-11-12', '14:00:00', '2'),
(2, '2024-11-12', '17:00:00', '2'),
(2, '2024-11-12', '18:00:00', '2'),
(2, '2024-11-12', '19:00:00', '2'),
(2, '2024-11-12', '20:00:00', '2'),
(3, '2024-11-12', '14:00:00', '2'),
(3, '2024-11-12', '17:00:00', '2'),
(3, '2024-11-12', '18:00:00', '2'),
(3, '2024-11-12', '19:00:00', '2'),
(3, '2024-11-12', '20:00:00', '2'),

-- 2024-11-13
(1, '2024-11-13', '14:00:00', '1'),
(1, '2024-11-13', '16:00:00', '1'),
(1, '2024-11-13', '17:30:00', '1'),
(1, '2024-11-13', '20:00:00', '1'),
(2, '2024-11-13', '14:00:00', '1'),
(2, '2024-11-13', '16:00:00', '1'),
(2, '2024-11-13', '17:30:00', '1'),
(2, '2024-11-13', '20:00:00', '1'),
(3, '2024-11-13', '14:00:00', '1'),
(3, '2024-11-13', '16:00:00', '1'),
(3, '2024-11-13', '17:30:00', '1'),
(3, '2024-11-13', '20:00:00', '1'),

-- 2024-11-14
(1, '2024-11-14', '14:00:00', '1'),
(1, '2024-11-14', '16:00:00', '1'),
(1, '2024-11-14', '17:30:00', '1'),
(1, '2024-11-14', '20:00:00', '1'),
(2, '2024-11-14', '14:00:00', '1'),
(2, '2024-11-14', '16:00:00', '1'),
(2, '2024-11-14', '17:30:00', '1'),
(2, '2024-11-14', '20:00:00', '1'),
(3, '2024-11-14', '14:00:00', '1'),
(3, '2024-11-14', '16:00:00', '1'),
(3, '2024-11-14', '17:30:00', '1'),
(3, '2024-11-14', '20:00:00', '1'),

-- 2024-11-15
(1, '2024-11-15', '14:00:00', '2'),
(1, '2024-11-15', '17:00:00', '2'),
(1, '2024-11-15', '18:00:00', '2'),
(1, '2024-11-15', '19:00:00', '2'),
(1, '2024-11-15', '20:00:00', '2'),
(2, '2024-11-15', '14:00:00', '2'),
(2, '2024-11-15', '17:00:00', '2'),
(2, '2024-11-15', '18:00:00', '2'),
(2, '2024-11-15', '19:00:00', '2'),
(2, '2024-11-15', '20:00:00', '2'),
(3, '2024-11-15', '14:00:00', '2'),
(3, '2024-11-15', '17:00:00', '2'),
(3, '2024-11-15', '18:00:00', '2'),
(3, '2024-11-15', '19:00:00', '2'),
(3, '2024-11-15', '20:00:00', '2'),

-- 2024-11-16
(1, '2024-11-16', '14:00:00', '1'),
(1, '2024-11-16', '16:00:00', '1'),
(1, '2024-11-16', '17:30:00', '1'),
(1, '2024-11-16', '20:00:00', '1'),
(2, '2024-11-16', '14:00:00', '1'),
(2, '2024-11-16', '16:00:00', '1'),
(2, '2024-11-16', '17:30:00', '1'),
(2, '2024-11-16', '20:00:00', '1'),
(3, '2024-11-16', '14:00:00', '1'),
(3, '2024-11-16', '16:00:00', '1'),
(3, '2024-11-16', '17:30:00', '1'),
(3, '2024-11-16', '20:00:00', '1'),

-- 2024-11-17
(1, '2024-11-17', '14:00:00', '1'),
(1, '2024-11-17', '16:00:00', '1'),
(1, '2024-11-17', '17:30:00', '1'),
(1, '2024-11-17', '20:00:00', '1'),
(2, '2024-11-17', '14:00:00', '1'),
(2, '2024-11-17', '16:00:00', '1'),
(2, '2024-11-17', '17:30:00', '1'),
(2, '2024-11-17', '20:00:00', '1'),
(3, '2024-11-17', '14:00:00', '1'),
(3, '2024-11-17', '16:00:00', '1'),
(3, '2024-11-17', '17:30:00', '1'),
(3, '2024-11-17', '20:00:00', '1'),

-- 2024-11-18
(1, '2024-11-18', '14:00:00', '2'),
(1, '2024-11-18', '17:00:00', '2'),
(1, '2024-11-18', '18:00:00', '2'),
(1, '2024-11-18', '19:00:00', '2'),
(1, '2024-11-18', '20:00:00', '2'),
(2, '2024-11-18', '14:00:00', '2'),
(2, '2024-11-18', '17:00:00', '2'),
(2, '2024-11-18', '18:00:00', '2'),
(2, '2024-11-18', '19:00:00', '2'),
(2, '2024-11-18', '20:00:00', '2'),
(3, '2024-11-18', '14:00:00', '2'),
(3, '2024-11-18', '17:00:00', '2'),
(3, '2024-11-18', '18:00:00', '2'),
(3, '2024-11-18', '19:00:00', '2'),

-- 2024-11-19
(1, '2024-11-19', '14:00:00', '1'),
(1, '2024-11-19', '16:00:00', '1'),
(1, '2024-11-19', '17:30:00', '1'),
(1, '2024-11-19', '20:00:00', '1'),
(2, '2024-11-19', '14:00:00', '1'),
(2, '2024-11-19', '16:00:00', '1'),
(2, '2024-11-19', '17:30:00', '1'),
(2, '2024-11-19', '20:00:00', '1'),
(3, '2024-11-19', '14:00:00', '1'),
(3, '2024-11-19', '16:00:00', '1'),
(3, '2024-11-19', '17:30:00', '1'),
(3, '2024-11-19', '20:00:00', '1'),

-- 2024-11-20
(1, '2024-11-20', '14:00:00', '2'),
(1, '2024-11-20', '17:00:00', '2'),
(1, '2024-11-20', '18:00:00', '2'),
(1, '2024-11-20', '19:00:00', '2'),
(1, '2024-11-20', '20:00:00', '2'),
(2, '2024-11-20', '14:00:00', '2'),
(2, '2024-11-20', '17:00:00', '2'),
(2, '2024-11-20', '18:00:00', '2'),
(2, '2024-11-20', '19:00:00', '2'),
(2, '2024-11-20', '20:00:00', '2'),
(3, '2024-11-20', '14:00:00', '2'),
(3, '2024-11-20', '17:00:00', '2'),
(3, '2024-11-20', '18:00:00', '2'),
(3, '2024-11-20', '19:00:00', '2'),
(3, '2024-11-20', '20:00:00', '2'),

-- 2024-11-21
(1, '2024-11-21', '14:00:00', '1'),
(1, '2024-11-21', '16:00:00', '1'),
(1, '2024-11-21', '17:30:00', '1'),
(1, '2024-11-21', '20:00:00', '1'),
(2, '2024-11-21', '14:00:00', '1'),
(2, '2024-11-21', '16:00:00', '1'),
(2, '2024-11-21', '17:30:00', '1'),
(2, '2024-11-21', '20:00:00', '1'),
(3, '2024-11-21', '14:00:00', '1'),
(3, '2024-11-21', '16:00:00', '1'),
(3, '2024-11-21', '17:30:00', '1'),
(3, '2024-11-21', '20:00:00', '1'),

-- 2024-11-22
(1, '2024-11-22', '14:00:00', '2'),
(1, '2024-11-22', '17:00:00', '2'),
(1, '2024-11-22', '18:00:00', '2'),
(1, '2024-11-22', '19:00:00', '2'),
(1, '2024-11-22', '20:00:00', '2'),
(2, '2024-11-22', '14:00:00', '2'),
(2, '2024-11-22', '17:00:00', '2'),
(2, '2024-11-22', '18:00:00', '2'),
(2, '2024-11-22', '19:00:00', '2'),
(2, '2024-11-22', '20:00:00', '2'),
(3, '2024-11-22', '14:00:00', '2'),
(3, '2024-11-22', '17:00:00', '2'),
(3, '2024-11-22', '18:00:00', '2'),
(3, '2024-11-22', '19:00:00', '2'),
(3, '2024-11-22', '20:00:00', '2');

