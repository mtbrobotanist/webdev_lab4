<?php


function openDB(){ 
    try 
    {
        $server = "localhost";
        $dbname = "lab4";
        $username = "lab4_user"; // note, lab4_user needs to be lab4_user@localhost inside of phpmyadmin
        $password = "Lab4!";
    
        $conn = new PDO("mysql:host=$server;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }
    catch(PDOException $e)
    {   
        die("Connection failed: " . $e->getMessage());
    }

}