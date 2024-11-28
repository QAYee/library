<?php
include(ROOT_DIR.'app/config/DatabaseConnect.php');
    $db = new DatabaseConnect();
    $conn = $db->connectDB();

    $bookList =[];


    try {
        $sql  = "SELECT * FROM `books`"; //select statement here
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $bookList = $stmt->fetchAll();   
        

    } catch (PDOException $e){
       echo "Connection Failed: " . $e->getMessage();
       $db = null;
    }
    ?>