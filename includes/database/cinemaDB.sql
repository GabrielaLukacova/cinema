USE c63r2psk6_cinema;

DROP TABLE IF EXISTS News;
DROP TABLE IF EXISTS Reserves;
DROP TABLE IF EXISTS Seat;
DROP TABLE IF EXISTS Booking;
DROP TABLE IF EXISTS ShowTime;
DROP TABLE IF EXISTS Movie;
DROP TABLE IF EXISTS User;
DROP TABLE IF EXISTS OpeningHours;
DROP TABLE IF EXISTS Cinema;
DROP TABLE IF EXISTS PostalCode;



CREATE TABLE PostalCode (
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
    FOREIGN KEY (postalCode) REFERENCES PostalCode(postalCode)
);

CREATE TABLE OpeningHours (
    openingHoursID INT AUTO_INCREMENT PRIMARY KEY,
    dayOfWeek VARCHAR(10) NOT NULL, 
    openingTime TIME,
    closingTime TIME,
    cinemaID INT,
    FOREIGN KEY (cinemaID) REFERENCES Cinema(cinemaID)
);

CREATE TABLE User (
    userID INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(80) NOT NULL,
    lastName VARCHAR(80) NOT NULL,
    email VARCHAR(100) NOT NULL,
    userPicture VARCHAR(255),
    phoneNumber VARCHAR(11),
    password VARCHAR(100) NOT NULL,
    street VARCHAR(100),
    postalCode VARCHAR(4),
    FOREIGN KEY (postalCode) REFERENCES PostalCode(postalCode)
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
    movieTag ENUM('None', 'Hot New Movie', 'Movie of the Week') DEFAULT 'None'
);

CREATE TABLE ShowTime (
    showTimeID INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    time TIME NOT NULL,
    room VARCHAR(3) NOT NULL,
    price DECIMAL NOT NULL,
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
    category ENUM('Review', 'Interview', 'Event', 'Promotion') NOT NULL,
    article TEXT NOT NULL, 
    cinemaID INT,
    imagePath VARCHAR(255),
    FOREIGN KEY (cinemaID) REFERENCES Cinema(cinemaID)
);


INSERT INTO PostalCode (postalCode, city) VALUES 
('6700', 'Esbjerg'),
('6740', 'Esbjerg'),
('6760', 'Esbjerg');

INSERT INTO Cinema (name, phoneNumber, email, street, postalCode, description) VALUES
('Dream Screen', '1234567890', 'contact@dreamscreen.com', 'Citygade 55', '6700', 'description of the cinema');

INSERT INTO OpeningHours (cinemaID, dayOfWeek, openingTime, closingTime)
VALUES
(1, 'Monday', '14:00:00', '22:00:00'),
(1, 'Tuesday', '14:00:00', '22:00:00'),
(1, 'Wednesday', '14:00:00', '22:00:00'),
(1, 'Thursday', '14:00:00', '22:00:00'),
(1, 'Friday', '14:00:00', '22:00:00'),
(1, 'Saturday', '10:00:00', '23:00:00'),
(1, 'Sunday', '10:00:00', '23:00:00');

INSERT INTO News (title, category, article, cinemaID)
VALUES
    ('Movie Review: The Great Adventure', 'Review', 'The Great Adventure is a cinematic masterpiece with breathtaking visuals and an emotional storyline. Critics and audiences have given it stellar reviews.', 1),
    ('Interview with Director James', 'Interview', 'We sat down with James, the director of "The Great Adventure," to talk about his creative process and what inspired the movie.', 1),
    ('The Grand Premiere Event', 'Event', 'The Grand Premiere of "The Great Adventure" was a star-studded event that brought together celebrities, critics, and fans alike.', 1),
    ('Special Promotion: Movie Tickets', 'Promotion', 'Get 50% off movie tickets for "The Great Adventure" this weekend! Don’t miss out on this limited-time offer.', 1),
    ('Upcoming Movie Release: Hero', 'Review', 'Hero Journey is set to be a box-office hit. Its unique blend of action and heartwarming moments will leave audiences cheering.', 1),
    ('Interview with Actor John Doe', 'Interview', 'John Doe shares his experiences working on the set of Hero Journey and his thoughts on the evolving film industry.', 1),
    ('Exclusive Event: Movie Marathon', 'Event', 'Join us for a movie marathon featuring all the best adventure films, including The Great Adventure and Hero Journey.', 1),
    ('"Action Heroes" Movie Review', 'Review', 'Action Heroes is an adrenaline-pumping thrill ride that keeps you on the edge of your seat from start to finish.', 1),
    ('Behind the Scenes: Making of "Action Heroes"', 'Interview', 'A deep dive into the making of "Action Heroes" with the crew, stunt coordinators, and actors.', 1),
    ('The Best Movie Event of the Year', 'Event', 'Our annual movie event features screenings, celebrity panels, and much more! Be part of this unforgettable experience.', 1),
    ('Ticket Promotion: Buy One Get One Free', 'Promotion', 'Buy one movie ticket for "Action Heroes" and get another free! Limited-time offer only for this weekend.', 1);




INSERT INTO User (firstName, lastName, email, phoneNumber, password, street, postalCode) 
VALUES
('Milan', 'Dober', 'mil@outlook.com', '45 60 88 00', '123', 'Yellow 33', '6700'),
('Alice', 'Brown', 'hruska@email.com', '3456789012', '123', 'Pine St 303', '6740'),
('Jane', 'Smith', 'jane.smith@email.com', '2345678901', '123', 'Oak St 202', '6760');


INSERT INTO Movie (title, genre, languageFlagPath, language, ageRating, runTime, movieTag, description)
VALUES 
('A Journey Beyond', 'Sci-Fi', '../media/flags/english_flag.jpg', 'English', '12', 120, 'None', 'After a hidden relationship during their final year of school, Muslim teenager Saja secretly takes her best friends to meet Charlie and his private school mates for a night out at Australia’s most infamous party, Schoolies Week. With their Romeo and Juliet romance blossoming, Saja and Charlie wake to discover a double murder that jolts them to their core… then sends their tribes to war.'),
('Mystery of the Woods', 'Thriller', '../media/flags/danish_flag.png', 'Danish', '12', 95, 'Hot New Movie', 'After a hidden relationship during their final year of school, Muslim teenager Saja secretly takes her best friends to meet Charlie and his private school mates for a night out at Australia’s most infamous party, Schoolies Week. With their Romeo and Juliet romance blossoming, Saja and Charlie wake to discover a double murder that jolts them to their core… then sends their tribes to war.'),
('Love & Laughter', 'Comedy', '../media/flags/english_flag.jpg', 'English', '18', 110, 'Movie of the Week', 'After a hidden relationship during their final year of school, Muslim teenager Saja secretly takes her best friends to meet Charlie and his private school mates for a night out at Australia’s most infamous party, Schoolies Week. With their Romeo and Juliet romance blossoming, Saja and Charlie wake to discover a double murder that jolts them to their core… then sends their tribes to war.');



INSERT INTO ShowTime (movieID, date, time, room, price)
VALUES 

-- 2024-11-22
(1, '2024-11-22', '14:00:00', '1', 120),
(1, '2024-11-22', '17:00:00', '1', 120),
(1, '2024-11-22', '18:00:00', '2', 120),
(1, '2024-11-22', '19:00:00', '2', 120),
(1, '2024-11-22', '20:00:00', '3', 120),
(2, '2024-11-22', '14:00:00', '2', 120),
(2, '2024-11-22', '17:00:00', '2', 120),
(2, '2024-11-22', '18:00:00', '2', 120),
(2, '2024-11-22', '19:00:00', '2', 120),
(2, '2024-11-22', '20:00:00', '2', 120),
(3, '2024-11-22', '14:00:00', '2', 120),
(3, '2024-11-22', '17:00:00', '2', 120),
(3, '2024-11-22', '18:00:00', '2', 120),
(3, '2024-11-22', '19:00:00', '2', 120),
(3, '2024-11-22', '20:00:00', '2', 120);



INSERT INTO Booking (paymentMethod, userID, showTimeID) VALUES
('cash', 1, 1),
('cash', 2, 2),
('creditCard', 3, 3);

-- 10 rows (A to J) with 12 seats each
INSERT INTO Seat (seatNumber, seatRow, isBooked)
SELECT seatNumber, seatRow, FALSE
FROM (
    SELECT
        t1.number AS seatNumber,
        CHAR(64 + t2.seatRowNum) AS seatRow
    FROM 
        (SELECT 1 AS number UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12) AS t1
    CROSS JOIN
        (SELECT 1 AS seatRowNum UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10) AS t2
) AS seatGrid;

UPDATE Seat
SET isBooked = TRUE
WHERE (seatRow = 'A' AND seatNumber = 1) 
   OR (seatRow = 'B' AND seatNumber = 5)  
   OR (seatRow = 'C' AND seatNumber = 8);


INSERT INTO Reserves (bookingID, seatID, bookingDate, bookingTime) VALUES
(1, 1, '2024-11-30', '15:00:00'),
(2, 2, '2024-11-25', '16:00:00'),
(3, 3, '2024-11-21', '15:00:00');