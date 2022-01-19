<?php
	session_start();
	session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
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
    <title>Focus | Logout</title>
</head>
<body>
  <!-- INSERT NAV BAR -->
  <?php include 'nav.php'; ?>

  <div id="box1">
      <div id="question">
          <div id="helloUser">You are now logged out.</div>
          <div id="buttons">
              <a id="login" href="./login.php" role="button">LOG BACK IN</a>
            </div>
      </div>
  </div>

  <!-- SCRIPTS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

  <!-- MAIN JAVASCRIPT -->
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