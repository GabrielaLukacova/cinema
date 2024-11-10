<?php
$db_dbname = "mysql:dbname=CinemaDB";
$db_host = "host=localhost";
$db_charset = "charset=utf8";

if (!defined('DSN')) {
    define("DSN", "$db_dbname; $db_host; $db_charset");
}

if (!defined('DB_USER')) {
    define("DB_USER", "Gabriela");
}

if (!defined('DB_PASS')) {
    define("DB_PASS", "123456");
}