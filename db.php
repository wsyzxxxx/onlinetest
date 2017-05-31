<?php
    require_once "config.php";
    try {
        $dbh = new PDO(sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', DB_SERVER, DB_DATABASE), DB_USERNAME, DB_PASSWORD);
        // $dbh = new PDO('mysql:host=localhost;charset=utf8mb4')
        // $dbh = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        echo $e->getMessage();
    }
?>
