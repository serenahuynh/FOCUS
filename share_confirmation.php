<?php
    session_start();
    require 'config/config.php';
    if( !isset($_SESSION["word"]) || $_SESSION["word"].trim() == "" ){
        $_SESSION["error"] = "Search for a word before sharing.";
    }
    else{
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if($mysqli->connect_errno) {
            echo $mysqli->connect_error;
            exit();
        }
        
        // check if word is in shared yet
        $statement_shared = $mysqli->prepare("SELECT * FROM shared WHERE keyword = ?");
        $statement_shared->bind_param("s", $_SESSION["word"]);
        $executed_shared = $statement_shared->execute();
        if(!$executed_shared){
            echo $mysqli->error;
        }
        $statement_shared->store_result();
        $num_rows = $statement_shared->num_rows;
        $statement_shared->close();
        //If the word has already been shared, set error
        if($num_rows > 0){
            $_SESSION["error"] = "The word has already been shared.";
        }
        else{
            $_SESSION["error"] = "Shared!";
            $statement = $mysqli->prepare("INSERT INTO shared(keyword, users_id) VALUES(?,?)");
            $statement->bind_param("si", $_SESSION["word"], $_SESSION["id"]);
            $executed = $statement->execute();
            if(!$executed){
                echo $mysqli->error;
            }
            $statement->close();
        }
    }
    // redirect back to index
    header("Location: ./index.php");
?>