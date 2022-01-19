<?php
  session_start();
  require 'config/config.php';
  if(!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]){
    if ( isset($_POST['username']) && isset($_POST['password']) ) {
      if ( empty($_POST['username']) || empty($_POST['password']) ) {
        $error = "Please enter username and password.";
      }
      else {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if($mysqli->connect_errno) {
          echo $mysqli->connect_error;
          exit();
        }
        
        $passwordInput = hash("sha256", $_POST["password"]);

        $sql = "SELECT * FROM users
              WHERE username = '" . $_POST['username'] . "' AND password = '" . $passwordInput . "';";
        
        $results = $mysqli->query($sql);
        $array = $results->fetch_assoc();
        
        if(!$results) {
          echo $mysqli->error;
          exit();
        }

        // If we get a result back, it means username/pw was correct
        if($results->num_rows > 0) {
          $_SESSION["logged_in"] = true;
          $_SESSION["username"] = $_POST["username"];
          $_SESSION["video"] = $array["stored_video"];
          $_SESSION["id"] = $array["id"];
          $_SESSION["delete-error"] = "";
          // If logged in, redirect them to the home page
          header("Location: ./index.php");
        }
        else {
          $error = "Invalid username or password.";
        }
      } 
    }
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
    <title>Focus | Login</title>
</head>
<body>
  <!-- INSERT NAV BAR -->
  <?php include 'nav.php'; ?>

  <div id="box1">
    <form action="login.php" method="POST" id ="question">
      <!-- if not logged in prompt to sign in -->
      <?php if(!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"] ): ?>
        <p id="header">Welcome Back!</p>
        <div id="error-msg">
          <?php
            if ( isset($error) && !empty($error) ) {
              echo $error;
            }
          ?>
        </div>
        <div class="break"></div>
        <input type="text" class="text-box" id="email-field" placeholder="Username" name="username">
        <div class="break"></div>
        <input type="password" class="text-box" id="pw-field" placeholder="Password" name="password">
        <div id="buttons">
          <button type="submit" class="btn btn-primary" id="login">LOG IN</button>
        </div>
        <a href="create_account.php" id="create-account">New? Create an account!</a>
      <!-- if logged in -->
      <?php else: ?>
        <div id="helloUser">Hello <?php echo $_SESSION["username"];?>!</div>
        <div id="buttons">
          <a id="logout" href="./logout.php" role="button">LOGOUT</a>
        </div>
      <?php endif; ?>
    </form>
    <video id="background-video" autoplay loop muted>
        
    </video>
  </div>

  <!-- SCRIPTS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  <!-- JQUERY -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

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

    // BACKGROUND VIDEO
    let word = "<?php echo $_SESSION["word"] ?>";
    if(word != ""){
      $.ajax({
          url: "https://api.pexels.com/videos/search",
          method: "GET",
          headers: {
            "Authorization":
              "Bearer 563492ad6f917000010000011d4d5ab88f31487b9f98257bf6b09e34",
          },
          data: {
              query: word,
              orientation: "landscape",
          }
        })
        .done(function(results){
          displayResults(results);
        })
        .fail(function(results){
            console.log("API request failed");
      });
    }

    // display the video on the screen
    function displayResults(results){
      let bgVideo = document.getElementById("background-video");
      bgVideo.pause();

      // Remove current source
      if(bgVideo.hasChildNodes()){
        bgVideo.removeChild(bgVideo.lastChild);
      }

      // Create new source
      let source = document.createElement("source");
      source.id = "video-source";
      // length of video array
      let videoLength = results.videos.length;
      // if there are video results from the API, change the video
      if(videoLength > 0){
        let videoLink = results.videos[Math.floor(Math.random()*videoLength)].video_files[0].link;
        // Add attributes to source
        source.setAttribute('src', videoLink);
        source.setAttribute('type', 'video/mp4');
        bgVideo.appendChild(source);
        
        // Reload video
        bgVideo.load();
        bgVideo.play();
      }
    }
  </script>
</body>
</html>