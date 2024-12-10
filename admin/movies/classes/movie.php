<?php
class Movie {
    private $db;
    const TABLE_NAME = 'Movie'; 

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllMovies() {
        try {
            $query = "SELECT movieID, title, genre, runtime, language, ageRating, description, imagePath FROM " . self::TABLE_NAME;
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (!$result) {
                throw new Exception('No movies found in the database.');
            }
            return $result;
        } catch (PDOException $e) {
            echo "Error fetching movies: " . $e->getMessage();
            return [];
        }
    }

    public function getMovieByID($movieID) {
        try {
            $query = "SELECT movieID, title, genre, runtime, language, ageRating, description, imagePath FROM " . self::TABLE_NAME . " WHERE movieID = :movieID";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':movieID', $movieID, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                return array_map(fn($val) => htmlspecialchars($val, ENT_QUOTES, 'UTF-8'), $result);
            }
            return null;
        } catch (PDOException $e) {
            throw new Exception("Error fetching movie by ID: " . $e->getMessage());
        }
    }

    public function getMoviesByTag(string $tag, int $limit = 8): array {
        try {
            $query = "SELECT * FROM " . self::TABLE_NAME . " WHERE movieTag = :tag LIMIT :limit";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':tag', $tag, PDO::PARAM_STR);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching movies by tag: " . $e->getMessage());
        }
    }


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

    public function deleteMovie($id) {
        try {
            $query = "DELETE FROM " . self::TABLE_NAME . " WHERE movieID = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error deleting movie: " . $e->getMessage());
        }
    }

    public function canAddToTag($tag) {
        $limit = self::TAG_LIMITS[$tag] ?? null;
        if ($limit === null) {
            return true; // No limit for unspecified tags
        }
        $count = $this->countMoviesByTag($tag);
        return $count < $limit;
    }

    const TAG_LIMITS = [
        'Hot New Movie' => 8,
        'Movie of the Week' => 1,
    ];

    public function countMoviesByTag($tag) {
        try {
            $query = "SELECT COUNT(*) as count FROM " . self::TABLE_NAME . " WHERE movieTag = :tag";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':tag', $tag, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        } catch (PDOException $e) {
            throw new Exception("Error counting movies by tag: " . $e->getMessage());
        }
    }


    public function getFirstMovieByTag($tag) {
        try {
            $query = "SELECT * FROM " . self::TABLE_NAME . " WHERE movieTag = :tag LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':tag', $tag, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching first movie by tag: " . $e->getMessage());
        }
    }




}
?>

