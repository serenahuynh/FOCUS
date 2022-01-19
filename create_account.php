<?php
	session_start();
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
    <title>Focus | Create Account</title>
</head>
<body>
  <!-- INSERT NAV BAR -->
  <?php include 'nav.php'; ?>

  <div id="box1">
    <form action="register_confirmation.php" method="POST" id ="question">
      <p id="header">Create an Account</p>
      <p id="error-msg"></p>
      <div class="break"></div>
      <input type="text" class="text-box" id="username-field" name="username" placeholder="Username">
      <div class="break"></div>
      <input type="text" class="text-box" id="email-field" name="email" placeholder="Email">
      <div class="break"></div>
      <input type="password" class="text-box" id="pw-field" name="password" placeholder="Password">
      <div class="break"></div>
      <input type="password" class="text-box" id="confirm-field" name="confirm-password" placeholder="Confirm Password">
      <div id="buttons">
        <button type="submit" class="btn btn-primary" id="create">SIGN UP</button>
      </div>
      <a href="login.php" id="create-account">Already have an account? Log in!</a>
    </form>
    <video id="background-video" autoplay loop muted>
        
    </video>
  </div>

  <!-- SCRIPTS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
  <!-- ICONS -->
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

    // VALIDATING INPUT
    document.querySelector('form').onsubmit = function(event){
			if ( document.querySelector('#username-field').value.trim().length == 0 ) {
				document.querySelector('#error-msg').innerHTML = "Username field cannot be empty.";
        event.preventDefault();
      }
			else if ( document.querySelector('#email-field').value.trim().length == 0 ) {
				document.querySelector('#error-msg').innerHTML = "Email field cannot be empty.";
        event.preventDefault();
      }
			else if ( document.querySelector('#pw-field').value.trim().length == 0 ) {
				document.querySelector('#error-msg').innerHTML = "Password field cannot be empty.";
        event.preventDefault();
      }
      else if( document.querySelector('#pw-field').value.trim() != document.querySelector('#confirm-field').value.trim()){
        document.querySelector('#error-msg').innerHTML = "Passwords do not match.";
        event.preventDefault();
      }
      else{
        document.querySelector('#error-msg').innerHTML = "";
      }

			return ( !document.querySelectorAll('.is-invalid').length > 0 );
		}

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