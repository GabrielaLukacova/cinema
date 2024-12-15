# cinema
A web-based cinema management app built with PHP and PDO, featuring both an admin panel and a cinema website. The admin panel allows managing cinema details, movie showtimes, bookings, and news updates. It uses a MySQL database and prepared statements for secure and efficient data management.




## Table of Contents
- [Features](#features)
- [Coding languages](#coding-languages)
- [Database schema](#database-schema)


--------------------------------------------------------

## Features

- **User authentication:**
  - Registration, login, and logout functionality.
  - User profiles with personal details and booked tickets.

- **Movie management:**
  - Display movies based on tags, movie calendar, and showtime with details.

- **Seat reservation:**
  - Interactive seat selection interface.
  - Limit of 5 seat reservation per booking.

- **Admin panel:**
  - Manage movies, showtimes, news and cinema details.


---

## Coding languages

- **Frontend:**
  - HTML, CSS, Bootstrap.
  
- **Backend:**
  - PHP for server-side logic.
  - MySQL for database management.
  
---

## Database schema

- PostalCode - links postal codes to cities.
- Cinema - contains cinema details, including address and contact information.
- OpeningHours - manages daily operating times for cinemas.
- User: -stores user accounts and login data.
- Movie - includes information about movies, including genres, ratings, and image.
- ShowTime - Tracks movie showtimes, rooms, and pricing.
- Seat - Maps seat rows and numbers to individual showtimes.
- Booking - Links users to showtime reservations.
- Reserves - Manages booked seats and timestamps.
- News - Stores promotional content and news articles.

Tables are interlinked via foreign keys. See the cinemaDB.sql file in the /includes/database/cinemaDB.sql