<?php
class Movie {
    private $db;
    const TABLE_NAME = 'Movie'; 

    // Constructor to initialize the database connection
    public function __construct($db) {
        $this->db = $db;
    }

    // Fetch all movies
    public function getAllMovies() {
        try {
            $query = "SELECT * FROM " . self::TABLE_NAME;
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching movies: " . $e->getMessage());
        }
    }

    // Fetch a single movie by ID
    public function getMovieByID($movieID) {
        try {
            $query = "SELECT * FROM " . self::TABLE_NAME . " WHERE movieID = :movieID";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':movieID', $movieID, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching movie by ID: " . $e->getMessage());
        }
    }

    // Add a new movie
    public function addMovie($data) {
        try {
            $query = "INSERT INTO " . self::TABLE_NAME . " (title, genre, runtime, language, ageRating, description, imagePath, movieTag)
                      VALUES (:title, :genre, :runtime, :language, :ageRating, :description, :imagePath, :movieTag)";
            $stmt = $this->db->prepare($query);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            throw new Exception("Error adding movie: " . $e->getMessage());
        }
    }

    // Update an existing movie without image
    public function updateMovie($movieID, $title, $genre, $runtime, $language, $ageRating, $description, $movieTag) {
        try {
            $query = "UPDATE " . self::TABLE_NAME . " SET 
                        title = :title, 
                        genre = :genre, 
                        runtime = :runtime, 
                        language = :language, 
                        ageRating = :ageRating, 
                        description = :description, 
                        movieTag = :movieTag
                      WHERE movieID = :movieID";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':movieID', $movieID, PDO::PARAM_INT);
            $stmt->bindValue(':title', $title, PDO::PARAM_STR);
            $stmt->bindValue(':genre', $genre, PDO::PARAM_STR);
            $stmt->bindValue(':runtime', $runtime, PDO::PARAM_INT);
            $stmt->bindValue(':language', $language, PDO::PARAM_STR);
            $stmt->bindValue(':ageRating', $ageRating, PDO::PARAM_STR);
            $stmt->bindValue(':description', $description, PDO::PARAM_STR);
            $stmt->bindValue(':movieTag', $movieTag, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error updating movie: " . $e->getMessage());
        }
    }

    // Update an existing movie with an image
    public function updateMovieWithImage($movieID, $title, $genre, $runtime, $language, $ageRating, $description, $imagePath, $movieTag) {
        try {
            $query = "UPDATE " . self::TABLE_NAME . " SET 
                        title = :title, 
                        genre = :genre, 
                        runtime = :runtime, 
                        language = :language, 
                        ageRating = :ageRating, 
                        description = :description, 
                        imagePath = :imagePath, 
                        movieTag = :movieTag
                      WHERE movieID = :movieID";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':movieID', $movieID, PDO::PARAM_INT);
            $stmt->bindValue(':title', $title, PDO::PARAM_STR);
            $stmt->bindValue(':genre', $genre, PDO::PARAM_STR);
            $stmt->bindValue(':runtime', $runtime, PDO::PARAM_INT);
            $stmt->bindValue(':language', $language, PDO::PARAM_STR);
            $stmt->bindValue(':ageRating', $ageRating, PDO::PARAM_STR);
            $stmt->bindValue(':description', $description, PDO::PARAM_STR);
            $stmt->bindValue(':imagePath', $imagePath, PDO::PARAM_STR);
            $stmt->bindValue(':movieTag', $movieTag, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error updating movie with image: " . $e->getMessage());
        }
    }

    // Delete a movie by ID
public function deleteMovie($id) {
    try {
        $query = "DELETE FROM " . self::TABLE_NAME . " WHERE movieID = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    } catch (PDOException $e) {
        throw new Exception("Error deleting movie: " . $e->getMessage());
    }
}}
?>
