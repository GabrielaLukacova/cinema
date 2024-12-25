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
    city CHAR(80) NOT NULL
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
    date DATE,
    time TIME NOT NULL,
    room ENUM('1', '2', '3', '4') NOT NULL,
    price DECIMAL,
    movieID INT,
    FOREIGN KEY (movieID) REFERENCES Movie(movieID) ON DELETE CASCADE
);

CREATE TABLE Booking (
    bookingID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT,
    showTimeID INT,
    FOREIGN KEY (userID) REFERENCES User(userID),
FOREIGN KEY (showTimeID) REFERENCES ShowTime(showTimeID) ON DELETE CASCADE
);

CREATE TABLE Seat (
    seatID INT AUTO_INCREMENT PRIMARY KEY,
    seatNumber INT NOT NULL,
    seatRow VARCHAR(1) NOT NULL,
    isBooked BOOLEAN DEFAULT FALSE,
    showTimeID INT NOT NULL,
    FOREIGN KEY (showTimeID) REFERENCES ShowTime(showTimeID) ON DELETE CASCADE
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

CREATE OR REPLACE VIEW cinema_opening_hours AS
SELECT oh.dayOfWeek, oh.openingTime, oh.closingTime, c.cinemaID
FROM OpeningHours oh
LEFT JOIN Cinema c ON c.cinemaID = oh.cinemaID
ORDER BY FIELD(oh.dayOfWeek, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');

CREATE OR REPLACE VIEW ticket_info_view AS
SELECT 
    b.userID AS userID,
    m.title AS movieTitle,
    m.imagePath AS movieImage,
    st.date AS showDate,
    st.time AS showTime,
    st.room AS roomNumber,
    st.price AS ticketPrice,
    GROUP_CONCAT(CONCAT(s.seatRow, s.seatNumber) ORDER BY s.seatRow, s.seatNumber SEPARATOR ', ') AS seatDetails
FROM Booking b
LEFT JOIN ShowTime st ON b.showTimeID = st.showTimeID
LEFT JOIN Movie m ON st.movieID = m.movieID
LEFT JOIN Reserves r ON b.bookingID = r.bookingID
LEFT JOIN Seat s ON r.seatID = s.seatID
GROUP BY b.bookingID
ORDER BY st.date DESC, st.time DESC;

-- Set pride to 100 if empty
DELIMITER $$
CREATE TRIGGER set_default_price
BEFORE INSERT ON ShowTime
FOR EACH ROW
BEGIN
    IF NEW.price IS NULL THEN
        SET NEW.price = 100;
    END IF;
END $$
DELIMITER ;

-- Set date to today's date if empty
DELIMITER $$
CREATE TRIGGER set_default_date
BEFORE INSERT ON ShowTime
FOR EACH ROW
BEGIN
    IF NEW.date IS NULL THEN
        SET NEW.date = CURDATE();  
    END IF;
END $$
DELIMITER ;

INSERT INTO PostalCode (postalCode, city) VALUES 
('6700', 'Esbjerg'),
('6740', 'Esbjerg'),
('6760', 'Esbjerg');

INSERT INTO Cinema (name, phoneNumber, email, street, postalCode, description) VALUES
('Dream Screen', '1234567890', 'contact@dreamscreen.com', 'Citygade 55', '6700', 'description of the cinema');

INSERT INTO OpeningHours (cinemaID, dayOfWeek, openingTime, closingTime)
VALUES
(1, 'Monday', '14:00', '22:00'),
(1, 'Tuesday', '14:00', '22:00'),
(1, 'Wednesday', '14:00', '22:00'),
(1, 'Thursday', '14:00', '22:00'),
(1, 'Friday', '14:00', '22:00'),
(1, 'Saturday', '10:00', '23:00'),
(1, 'Sunday', '10:00', '23:00');

INSERT INTO News (title, category, article, cinemaID)
VALUES
    ('Movie Review: The Great Adventure', 'Review', 'The Great Adventure is a cinematic masterpiece with breathtaking visuals and an emotional storyline. Critics and audiences have given it stellar reviews.', 1),
    ('Interview with Director James', 'Interview', 'We sat down with James, the director of "The Great Adventure," to talk about his creative process and what inspired the movie.', 1),
    ('The Grand Premiere Event', 'Event', 'The Grand Premiere of "The Great Adventure" was a star-studded event that brought together celebrities, critics, and fans alike.', 1);

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
(1, '2024-11-22', '14:00', '1', 120),
(2, '2024-11-22', '17:00', '2', 120);

-- showtime with NULL price and an empty date to test the trigger
INSERT INTO ShowTime (movieID, date, time, room, price)
VALUES (1, '2024-12-17', '14:00:00', 1, NULL);


INSERT INTO Booking (userID, showTimeID) VALUES
(1, 1),
(1, 2),
(1, 3);

INSERT INTO Seat (seatNumber, seatRow, isBooked, showTimeID)
VALUES
(1, 'A', FALSE, 1),
(5, 'B', FALSE, 1),
(8, 'C', FALSE, 1);

UPDATE Seat
SET isBooked = TRUE
WHERE (seatRow = 'A' AND seatNumber = 1) 
   OR (seatRow = 'B' AND seatNumber = 5)  
   OR (seatRow = 'C' AND seatNumber = 8);