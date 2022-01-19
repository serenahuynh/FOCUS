<?php
    session_start();
    require 'config/config.php';

    if ( !isset($_POST['email']) || empty($_POST['email'])
        || !isset($_POST['username']) || empty($_POST['username'])
        || !isset($_POST['password']) || empty($_POST['password']) ) {
        $error = "Please fill out all required fields.";
    }
    else{
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if($mysqli->connect_errno){
            echo  $mysqli->connect_error;
            exit();
        }
        
        // Before inserting a new user, check if username or email already exists in the table
        $statement_registered = $mysqli->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $statement_registered->bind_param("ss", $_POST["username"], $_POST["email"]);
        $executed_registered = $statement_registered->execute();
        if(!$executed_registered){
            echo $mysqli->error;
        }

        // if account is already in the database, set error
        $statement_registered->store_result();
        $numrows = $statement_registered->num_rows;
        $statement_registered->close();
        if($numrows > 0){
            $error = "Username or email has already been taken. Please choose another one.";
        }
        else{
            // Hash the password
            $password = hash("sha256", $_POST["password"]);

            // Add the user input into the new users table we just created
            $statement = $mysqli->prepare("INSERT INTO users(username, email, password) VALUES(?, ?, ?)");
            $statement->bind_param("sss", $_POST["username"], $_POST["email"], $password);
            $executed = $statement->execute();
            if(!$executed){
                echo $mysqli->error;
            }
            $statement->close();
        }

        $mysqli->close();
    }
    if(isset($_GET["text"])){
        $_SESSION["word"] = $_GET["text"];
    }
    else if(!isset($_SESSION["word"])){
        $_SESSION["word"] = "";
    }
    $_SESSION["delete-error"] = "";
    $_SESSION["error"] = "";
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="data:,">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <title>Focus | Create Account</title>
</head>
<body>
    <!-- INSERT NAV BAR -->
    <?php include 'nav.php'; ?>

	<div id="box1">
        <div id="question">
            <!-- IF THERE'S AN ERROR -->
            <?php if ( isset($error) && !empty($error) ) : ?>
                <div class="text-danger register-error">
                    <?php echo $error; ?>
                    <br/>
                    <a href="create_account.php" role="button" class="btn btn-primary" id="try-again">TRY AGAIN</a>
                </div>
            <?php else : ?>
                <p id="helloUser">Hooray!</p>
                <br/>
                <p><?php echo $_POST['username']; ?> was successfully registered.</p>
                <a href="login.php" role="button" class="btn btn-primary" id="login">LOGIN</a>
            <?php endif; ?>
        </div>
    </div> <!-- .col -->

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script>
        //change selected indicator on nav bar
        document.getElementById('home').classList.remove("selected");
        document.getElementById('profile').classList.add("selected");
        document.getElementById('todo').classList.remove("selected");
        document.getElementById('star').classList.remove("selected");
        document.getElementById('shared').classList.remove("selected");
        // do the same for mobile
        document.getElementById('mobile-home').classList.remove("active");
        document.getElementById('mobile-login').classList.add("active");
        document.getElementById('mobile-todo').classList.remove("active");
        document.getElementById('mobile-recommended').classList.remove("active");
        document.getElementById('mobile-shared').classList.remove("active");
    </script>
</body>
</html>