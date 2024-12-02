<?php

session_start();    
require_once(__DIR__."/../config/Directories.php"); //to handle folder specific path
include("../config/DatabaseConnect.php"); //to access database connection

$db = new DatabaseConnect();
$conn = $db->connectDB();

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $bookId = $_POST["id"];

    try {
        $sql = "DELETE FROM `books` WHERE books.id = :p_id"; //delete query here
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':p_id', $bookId);
        $stmt->execute();

        $_SESSION["tama"]="product has been deleted";
        header("location: ".BASE_URL."views/admin/index.php");
        exit;


    } catch (PDOException $e) {
        echo "Connection Failed: " . $e->getMessage();
        $db = null;
    }
}