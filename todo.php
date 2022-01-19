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
    <title>Focus | To Do List</title>
</head>
<body>
  <!-- INSERT NAV BAR -->
  <?php include 'nav.php'; ?>

  <div id="box1">
    <div class="question" id="todo-box">
      <p id="box-header">To Do List</p>

      <form id="form">
        <input type="text" class="text-box" id="todo-text" placeholder="Add to-do item here" autocomplete="off"/>
      </form>
      <p id="instructions">Press Enter key to add to list</p>

      <ul id="todo-list">
          <!-- todo list here -->
      </ul>
    </div>
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
    document.getElementById('profile').classList.remove("selected");
    document.getElementById('todo').classList.add("selected");
    document.getElementById('star').classList.remove("selected");
    document.getElementById('shared').classList.remove("selected");
    // do the same for mobile
    document.getElementById('mobile-home').classList.remove("active");
    document.getElementById('mobile-login').classList.remove("active");
    document.getElementById('mobile-todo').classList.add("active");
    document.getElementById('mobile-recommended').classList.remove("active");
    document.getElementById('mobile-shared').classList.remove("active");


    // TODO LIST
    // load local storage tasks
    let savedList = localStorage.getItem('todo');
    let lastIndex = 0;
    let tmpString = "";
    let isLastWord = false; //checker to see if we reached the last word
    // if there was stored tasks, parse & print it
    if(savedList != null && savedList != ""){
      let length = savedList.length;
      for(let i = 0; i < length; i++){
        // parse string
        lastIndex = savedList.indexOf(",");
        tmpString = savedList.substr(0, lastIndex);
        // if comma not found, but there's still a word, add it
        if(lastIndex == -1 && savedList != ""){
          tmpString = savedList;
          isLastWord = true;
        }
        
        // update savedList to the next word
        savedList = savedList.substr(lastIndex+1);

        // If task isn't empty, add to list
        if(tmpString != ""){
          $("#todo-list").append('<li><ion-icon class="check" name="checkmark-circle-outline"></ion-icon><ion-icon class="trash" name="trash-bin-outline"></ion-icon><span class="text">'+ tmpString+'</span></li>');
        }
  
        // if we reached last word, break
        if(isLastWord){
          break;
        }
      }
    }
    // if nothing's in the list, then put "example task"
    else{
      $("#todo-list").append('<li><ion-icon class="check" name="checkmark-circle-outline"></ion-icon><ion-icon class="trash" name="trash-bin-outline"></ion-icon><span class="text">Example task</span></li>');
    }

    // finish task
    $("#todo-list").on("click", "li > .check", function(event){
      event.stopPropagation();
      event.currentTarget.parentElement.classList.add("strike-through");
    });

    // remove task
    $("#todo-list").on("click", "li > .trash", function(event){
      event.stopPropagation();
      // store the task
      let task = event.currentTarget.nextElementSibling.textContent;
      //fade the item
      $(this).parent().fadeOut(300, function(){
          //remove
          event.currentTarget.parentElement.remove();
      });

      // remove task from storage
      let storedList = localStorage.getItem('todo');
      let wordIndex = storedList.indexOf(task);
      if(wordIndex != -1){
        let firstPart = storedList.substr(0, wordIndex-1);
        let lastPart = storedList.substr(wordIndex + (task.length));
        if(firstPart + lastPart != ",,"){
          localStorage.setItem('todo', firstPart+lastPart);
        }
      }

    });

    // add task
    $("#form").on("submit", function(event){
      event.preventDefault();
      let input = $("#todo-text").val().trim();

      //add to to do list if not empty
      if(input != ""){
        $("#todo-list").append('<li><ion-icon class="check" name="checkmark-circle-outline"></ion-icon><ion-icon class="trash" name="trash-bin-outline"></ion-icon><span class="text">'+ input+'</span></li>');

        // add to local storage
        let currentList = [];
        currentList.push(localStorage.getItem('todo'));
        if(input != ""){
          currentList.push(input);
        }
        localStorage.setItem('todo', currentList);

        //empty the box after submitting
        $("#todo-text").val("");
      }
    });

    // Background video
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