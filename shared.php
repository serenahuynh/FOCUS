<?php
	session_start();
  require 'config/config.php';

  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  if ( $mysqli->connect_errno ) {
    echo $mysqli->connect_error;
    exit();
  }
  $mysqli->set_charset('utf8');
  $sql = "SELECT keyword, users.username
    FROM shared
    JOIN users
    WHERE shared.users_id = users.id;";
  $results = $mysqli->query($sql);
  if($results == false){
    echo $mysqli->error;
    exit();
  }
  $mysqli->close();

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
    <title>Focus | Shared</title>
</head>
<body>
  <!-- INSERT NAV BAR -->
  <?php include 'nav.php'; ?>
  <div id="box1">
    <div class="question" id="rec-box">
      <p id="box-header">Shared Keywords</p>
      <p id="instructions">These are some words the community shared. Try them out!</p>
      <ul id="rec-list">
        <?php while ($row = $results->fetch_assoc() ) : ?>
          <li class="rec-key">
            <span class="rec-word"><?php echo strtoupper($row['keyword']); ?></span>
            <span class="shared-user">shared by <?php echo $row['username']; ?></span>
          </li>
        <?php endwhile; ?>
      </ul>

      <!-- if logged in, display a box where user can search for and delete the keyword they shared -->
      <?php if ( isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] ) : ?>
        <div id="delete-word">
          <input type="text" class="text-box" id="delete-input" placeholder="Find and delete my shared word.."/>
          <a onclick="deleteWord()" href="delete.php?delete_word=" class="btn btn-outline-danger delete-btn" id="delete-btn">Delete</a>
        </div>

        <!-- If the word wasn't found -->
        <?php if ( isset($_SESSION["delete-error"]) && $_SESSION["delete-error"] != "" ) : ?>
          <p id="delete-error"><?php echo $_SESSION["delete-error"]; ?></p>
        <?php elseif ( isset($_SESSION["confirm-delete"]) && $_SESSION["confirm-delete"] != "" ) : ?>
          <p id="confirm-delete"><?php echo $_SESSION["confirm-delete"]; ?></p>
        <?php else : ?>
        <?php endif; ?>

      <!-- end if logged in -->
      <?php else : ?>
      <?php endif; ?>
    </div>
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
    document.getElementById('todo').classList.remove("selected");
    document.getElementById('star').classList.remove("selected");
    document.getElementById('shared').classList.add("selected");
    // do the same for mobile
    document.getElementById('mobile-home').classList.remove("active");
    document.getElementById('mobile-login').classList.remove("active");
    document.getElementById('mobile-todo').classList.remove("active");
    document.getElementById('mobile-recommended').classList.remove("active");
    document.getElementById('mobile-shared').classList.add("active");
    
    // DELETE ON CLICK
    function deleteWord(){
      setLink();
      confirmDelete();
    }
    function setLink(){
      let userInput = document.getElementById('delete-input').value.trim();
      // if user input is not empty, set link
      if(userInput != ""){
        $("#delete-btn").attr("href", "delete.php?delete_word=" + userInput);
      }
      // if it's empty, set error & don't leave
      else{
        <?php $_SESSION["delete-error"] = "The input is empty. Please input a word to delete." ?>
        $("#delete-btn").attr("href", "");
      }
    }
    function confirmDelete(){
      return confirm(`Are you sure you want to remove ${document.getElementById('delete-input').value.trim()} from the list of shared words?`)
    }

    // DISPLAY IMAGES IN THE BOXES
    // Parse list
    let newList = [];
    let userList = [];
    function parseList(){
      let list = document.querySelector("#rec-list");
      while(list.hasChildNodes()){
        let str = list.lastChild.innerHTML;
        if(str != undefined){
          str = str.trim();

          // keyword
          let tempStr = str.substr(23);
          let num = tempStr.indexOf("<");
          let newStr = str.substr(23, num);
          newList.push(newStr);

          // user who shared keyword
          tempStr = str.substr(30+num);
          num = tempStr.indexOf(">");
          let firstHalf = tempStr.substr(num+1);
          let userStr = firstHalf.substr(0, firstHalf.length-7);
          userList.push(userStr);
        }
        list.removeChild(list.lastChild);
      }
    }
    // call this function
    parseList();

    // API
    function displayResults(results, keyword, user){
      let recList = document.querySelector("#rec-list");
      let photo = results.photos[9].src.original;
      let htmlString = `
        <li class="rec-key">
          <span class="rec-word">${keyword}</span>
          <img src="${photo}" alt="photo" class="photo"/>
          <span class="shared-user">${user}</span>
        </li>
      `;
      recList.innerHTML += htmlString;
    }
    
    let keyword = "";
    let user = "";
    // Print picture for each word
    for(let i = 0; i < newList.length; i++){
      keyword = newList[i];
      user = userList[i];
      $.ajax({
        url: "https://api.pexels.com/v1/search",
        method: "GET",
        headers: {
          "Authorization":
            "Bearer 563492ad6f917000010000011d4d5ab88f31487b9f98257bf6b09e34",
        },
        data: {
            query: keyword,
            orientation: "landscape",
        }
      })
      .done(function(results){
        displayResults(results, newList[i], userList[i]);
      })
      .fail(function(results){
          console.log("API request failed");
      });
    }

  </script>
</body>
</html>