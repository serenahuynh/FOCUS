<?php
	session_start();
  require 'config/config.php';
  // if there's a word, set session word
  if(isset($_GET["text"])){
    $_SESSION["word"] = $_GET["text"];

    // if user is logged in:
    if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]){
      $_SESSION["delete-error"] = "";
      $_SESSION["error"] = "";
      // check if it's in recommended, if it is, update the "last used by" user
      $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
      if($mysqli->connect_errno) {
          echo $mysqli->connect_error;
          exit();
      } 
      // check if word is in recommended yet
      $statement = $mysqli->prepare("SELECT id FROM recommended WHERE keyword LIKE ?");
      $statement->bind_param("s", $_SESSION["word"]);
      $executed = $statement->execute();
      if(!$executed){
          echo $mysqli->error;
      }
      $statement->store_result();
      $statement->bind_result($result);
      $id = "";
      while($statement->fetch()){
        $id = $result;
      }
      $num_rows = $statement->num_rows;
      $statement->close();
      //If the word is in recommended, update the last used by user
      if($num_rows > 0){
        $statement_rec = $mysqli->prepare("UPDATE recommended SET users_id=? WHERE id=?");
        $statement_rec->bind_param("ii", $_SESSION["id"], $id);
        $executed_rec = $statement_rec->execute();
        if(!$executed_rec){
            echo $mysqli->error;
        }
        $statement_rec->close();
      }
      //end of if logged in statement
    }
    // end of if get text statement
  }
  else if(!isset($_SESSION["word"])){
    $_SESSION["word"] = "";
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="data:,">

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

    <!-- FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <title>Focus</title>
</head>

<body>
  <!-- INSERT NAV BAR -->
  <?php include 'nav.php'; ?>

  <!-- MAIN WINDOW -->
  <div id="box1">
    <form action="index.php" method="GET" id="question">
        <p id="seek">Find a vibe to F<span id="o"><ion-icon name="aperture-outline"></ion-icon></span>CUS with</p>
        <div class="break"></div>
        <input
          type="text"
          class="text-box"
          id="search-id"
          placeholder="ex: sunset"
          name="text"
        >
        <div id="buttons">
            <button type="submit" class="btn btn-primary" id="go">LET'S GO!</button>
        </div>
      </form>
      <video id="background-video" autoplay loop muted>
        
      </video>
      
      <?php if ( isset($_SESSION["error"]) && $_SESSION["error"] != "") : ?>
        <p id="share-error"><?php echo $_SESSION["error"]; ?></p>
      <?php else : ?>
      <?php endif; ?>
          
          
      <!-- if logged in, display share button -->
      <?php if ( isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] ) : ?>
        <div id="share-comp">
          <a href="share_confirmation.php"><ion-icon name="heart-circle-outline" id="share-button"></ion-icon></a>
        </div>
      <?php else : ?>
        <p id="share-error"><a href="login.php">Log in</a> to share words with others!</p>
      <?php endif; ?>
  </div>

  <!-- SCRIPTS -->
  <!-- BOOTSTRAP -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>

  <!-- ICONS -->
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

  <!-- JQUERY -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

  <!-- MAIN JAVASCRIPT -->
  <script>
    //change selected indicator on nav bar
    document.getElementById('home').classList.add("selected");
    document.getElementById('profile').classList.remove("selected");
    document.getElementById('todo').classList.remove("selected");
    document.getElementById('star').classList.remove("selected");
    document.getElementById('shared').classList.remove("selected");
    // do the same for mobile
    document.getElementById('mobile-home').classList.add("active");
    document.getElementById('mobile-login').classList.remove("active");
    document.getElementById('mobile-todo').classList.remove("active");
    document.getElementById('mobile-recommended').classList.remove("active");
    document.getElementById('mobile-shared').classList.remove("active");

    let searchInput = document.querySelector("#search-id").value.trim();
    
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