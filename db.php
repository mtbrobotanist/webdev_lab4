<?php


function openDB(){ 
    try 
    {
        $server = "localhost";
        $username = "lab4_user";
        $password = "Lab4!";
    
        $conn = new PDO("mysql:host=$server;dbname=lab4", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }
    catch(PDOException $e)
    {   
        die("Connection failed: " . $e->getMessage());
    }

}