<?php
class Movie {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllMovies() {
        $query = "SELECT * FROM Movie";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMovieById($id) {
        $query = "SELECT * FROM Movie WHERE movieID = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addMovie($data) {
        $query = "INSERT INTO Movie (title, genre, runtime, language, languageFlagPath, ageRating, description, imagePath, movieTag)
                  VALUES (:title, :genre, :runtime, :language, :languageFlagPath, :ageRating, :description, :imagePath, :movieTag)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    public function updateMovie($data) {
        $query = "UPDATE Movie SET 
                    title = :title, 
                    genre = :genre, 
                    runtime = :runtime, 
                    language = :language, 
                    languageFlagPath = :languageFlagPath, 
                    ageRating = :ageRating, 
                    description = :description, 
                    imagePath = :imagePath, 
                    movieTag = :movieTag
                  WHERE movieID = :movieID";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    public function deleteMovie($id) {
        $query = "DELETE FROM Movie WHERE movieID = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
