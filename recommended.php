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
    FROM recommended
    JOIN users
    WHERE recommended.users_id = users.id";
  $results = $mysqli->query($sql);
  if($results == false){
    echo $mysqli->error;
    exit();
  }
  $mysqli->close();
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
    <title>Focus | Recommended</title>
</head>
<body>
  <!-- INSERT NAV BAR -->
  <?php include 'nav.php'; ?>
    
  <div id="box1">
    <div class="question" id="rec-box">
      <p id="box-header">Recommended Keywords</p>
      <p id="instructions">Try some of these words out! The videos for these are pretty neat.</p>
      <ul id="rec-list">
        <?php while ( $row = $results->fetch_assoc() ) : ?>
          <li class="rec-key">
            <span class="rec-word"><?php echo strtoupper($row['keyword']); ?></span>
            <span class="shared-user"><?php echo $row['username']; ?></span>
          </li>
        <?php endwhile; ?>
      </ul>
    </div>
  </div>

  <!-- SCRIPTS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

  <!-- MAIN JAVASCRIPT -->
  <script>
    //change selected indicator on nav bar
    document.getElementById('home').classList.remove("selected");
    document.getElementById('profile').classList.remove("selected");
    document.getElementById('todo').classList.remove("selected");
    document.getElementById('star').classList.add("selected");
    document.getElementById('shared').classList.remove("selected");
    // do the same for mobile
    document.getElementById('mobile-home').classList.remove("active");
    document.getElementById('mobile-login').classList.remove("active");
    document.getElementById('mobile-todo').classList.remove("active");
    document.getElementById('mobile-recommended').classList.add("active");
    document.getElementById('mobile-shared').classList.remove("active");

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

          // last tried by user
          tempStr = str.substr(30+num);
          num = tempStr.indexOf(">");
          let firstHalf = tempStr.substr(num+1);
          let userStr = firstHalf.substr(0, firstHalf.length-7);
          userList.push(userStr);
        }
        list.removeChild(list.lastChild);
      }
    }
    parseList();

    // API
    function displayResults(results, keyword, user){
      let recList = document.querySelector("#rec-list");

      let photo = results.photos[9].src.original;
      let htmlString = `
        <li class="rec-key">
          <span class="rec-word">${keyword}</span>
          <img src="${photo}" alt="photo" class="photo"/>
          <span class="shared-user">last tried by ${user}</span>
        </li>
      `;
      
      recList.innerHTML += htmlString;
    }
    
    let keyword = "";
    let user = "";
    // Print pictures for each word
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