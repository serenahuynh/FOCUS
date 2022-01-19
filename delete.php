<?php
    session_start();
    require 'config/config.php';

    // Second line of defense - if GET is empty, set error msg
    if(!isset($_GET["delete_word"]) || $_GET["delete_word"] == ""){
        $_SESSION["delete_error"] = "The input is empty. Please input a word to delete.";
    }
    // if not empty, try to find it in database
    else{
         $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
         if($mysqli->connect_errno) {
             echo $mysqli->connect_error;
             exit();
         }
        
         // check if word AND user is in shared
         $statement = $mysqli->prepare("SELECT id FROM shared WHERE keyword LIKE ? AND users_id = ?");
         $statement->bind_param("si", $_GET["delete_word"], $_SESSION["id"]);
         $executed = $statement->execute();
         if(!$executed){
             echo $mysqli->error;
         }
         $statement->store_result();
         $statement->bind_result($result);
         while($statement->fetch()){
             echo $result;
         }
         $num_rows = $statement->num_rows;
         $statement->close();
         //If there is no result, then the user never shared that word
         if($num_rows == 0){
            $_SESSION["delete-error"] = "The word cannot be found. Are you sure you shared it?";
        }
        // If there is a result, delete it from the database & set confirm message
        else{
            $_SESSION["delete-error"] = "";

            $statement_delete = $mysqli->prepare("DELETE FROM shared WHERE id = ?");
            $statement_delete->bind_param("i", $result);
            $executed_delete = $statement_delete->execute();
            if(!$executed_delete){
                echo $mysqli->error;
            }
            $statement_delete->close();
            $_SESSION["confirm-delete"] = "Deleted successfully.";
        }
    }
    // redirect back to shared
    header("Location: ./shared.php");
?>