<?php
require ("constants.php");
try {
    $db = new PDO(DSN, DB_USER, DB_PASS);
} catch (PDOException $e){
    echo "connection is not working". $e->getMessage();
}