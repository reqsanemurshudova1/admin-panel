<?php

try {
    $server = "localhost";
    $username = "root";
    $password = "";
    $databaseName = "final_project";
    $connection = new PDO("mysql:host=$server;dbname=$databaseName;charset=utf8", $username, $password);
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>