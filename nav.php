<!-- MOBILE NAV -->
<div id="mobile-nav">
  <nav class="navbar navbar-dark darkest">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php"><ion-icon name="aperture-outline" class="focus"></ion-icon></a>
        <button class="navbar-toggler lightest" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
          <ul class="navbar-nav">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Change Theme
              </a>
              <ul class="dropdown-menu" id="theme-menu" aria-labelledby="navbarScrollingDropdown">
                <li class="theme-dropdown" id="mobile-theme1">Cool Night</li>
                <li class="theme-dropdown" id="mobile-theme2">Cool Day</li>
                <li class="theme-dropdown" id="mobile-theme3">Warm Night</li>
                <li class="theme-dropdown" id="mobile-theme4">Warm Day</li>
              </ul>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.php" id="mobile-home">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="login.php" id="mobile-login">Profile</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="todo.php" id="mobile-todo">To Do List</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="recommended.php" id="mobile-recommended">Recommended</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="shared.php" id="mobile-shared">Shared</a>
            </li>
          </ul>
        </div>
      </div>
  </nav>
</div>

<!-- DESKTOP NAV -->
<div id="desktop-nav">
  <div id="logo">
    <p class="focus">F<ion-icon name="aperture-outline"></ion-icon>CUS</p>
  </div>
  <div id="theme">
    <div id="theme-icon">
      <span id="color1"></span>
      <span id="color2"></span>
      <span id="color3"></span>
    </div>
  </div>
  <a href="index.php"><ion-icon name="home-outline" class="icon" id="home"></ion-icon></a>
  <a href="login.php"><ion-icon name="person-circle-outline" class="icon" id="profile"></ion-icon></a>
  <a href="todo.php"><ion-icon name="checkbox-outline" class="icon" id="todo"></ion-icon></a>
  <a href="recommended.php"><ion-icon name="star-outline" class="icon" id="star"></ion-icon></a>
  <a href="shared.php"><ion-icon name="people-circle-outline" class="icon" id="shared"></ion-icon></a>
</div>

<!-- desktop icon labels -->
<p id="change-theme-label">Change Theme</p>
<p id="home-label">Home</p>
<p id="profile-label">Profile</p>
<p id="to-do-label">Task List</p>
<p id="recommended-label">Recommended</p>
<p id="shared-label">Shared</p>

<!-- Pexels logo -->
<a href="https://www.pexels.com/" target="_blank"><img id="pexels-logo" src="./images/pexels-logo.png" alt="pexels logo"/></a>

<!-- BOX APPEARS WHEN CLICK ON THEME -->
<div id="change-theme-box">
  <p id="change-theme">Change Theme</p>
  <div class="themes" id="theme-box-1">
    <span class="theme-colors" id="color1-1"></span>
    <span class="theme-colors" id="color2-1"></span>
    <span class="theme-colors" id="color3-1"></span>
    <span class="theme-colors" id="color4-1"></span>
  </div>
  <div class="themes" id="theme-box-2">
    <span class="theme-colors" id="color1-2"></span>
    <span class="theme-colors" id="color2-2"></span>
    <span class="theme-colors" id="color3-2"></span>
    <span class="theme-colors" id="color4-2"></span>
  </div>
  <div class="themes" id="theme-box-3">
    <span class="theme-colors" id="color1-3"></span>
    <span class="theme-colors" id="color2-3"></span>
    <span class="theme-colors" id="color3-3"></span>
    <span class="theme-colors" id="color4-3"></span>
  </div>
  <div class="themes" id="theme-box-4">
    <span class="theme-colors" id="color1-4"></span>
    <span class="theme-colors" id="color2-4"></span>
    <span class="theme-colors" id="color3-4"></span>
    <span class="theme-colors" id="color4-4"></span>
  </div>
</div>

<script>
  var r = document.querySelector(':root');

  //when loaded
  window.onload = () => {
    // If there's a theme already set, load it
    if (localStorage.getItem('color-one')) {
      r.style.setProperty('--color-one', localStorage.getItem('color-one'));
      r.style.setProperty('--color-two', localStorage.getItem('color-two'));
      r.style.setProperty('--color-three', localStorage.getItem('color-three'));
      r.style.setProperty('--color-four', localStorage.getItem('color-four'));
    }
    // if not, load default theme (1) & reload
    else{
      localStorage.setItem('color-one', '#E0FBFC');
      localStorage.setItem('color-two', '#9DB4C0');
      localStorage.setItem('color-three', '#5C6B73');
      localStorage.setItem('color-four', '#253237');
      window.location.reload();
    }
  }

  // hover over icons -> display labels
  // Change Theme
  document.getElementById('theme').onmouseover = function(){
    document.getElementById('change-theme-label').style.opacity = "100%";
  }
  document.getElementById('theme').onmouseleave = function(){
    document.getElementById('change-theme-label').style.opacity = "0%";
    document.getElementById('theme').classList.remove("selected");
  }
  // Home
  document.getElementById('home').onmouseover = function(){
    document.getElementById('home-label').style.opacity = "100%";
  }
  document.getElementById('home').onmouseleave = function(){
    document.getElementById('home-label').style.opacity = "0%";
  }
  // Profile
  document.getElementById('profile').onmouseover = function(){
    document.getElementById('profile-label').style.opacity = "100%";
  }
  document.getElementById('profile').onmouseleave = function(){
    document.getElementById('profile-label').style.opacity = "0%";
  }
  // Todo list
  document.getElementById('todo').onmouseover = function(){
    document.getElementById('to-do-label').style.opacity = "100%";
  }
  document.getElementById('todo').onmouseleave = function(){
    document.getElementById('to-do-label').style.opacity = "0%";
  }
  // Recommended
  document.getElementById('star').onmouseover = function(){
    document.getElementById('recommended-label').style.opacity = "100%";
  }
  document.getElementById('star').onmouseleave = function(){
    document.getElementById('recommended-label').style.opacity = "0%";
  }
  // Shared
  document.getElementById('shared').onmouseover = function(){
    document.getElementById('shared-label').style.opacity = "100%";
  }
  document.getElementById('shared').onmouseleave = function(){
    document.getElementById('shared-label').style.opacity = "0%";
  }

  // Open change theme box
  document.getElementById('theme').onclick = function(){
    document.getElementById('theme').classList.add("selected");
    //if the box isn't open, display it
    if(document.getElementById('change-theme-box').style.display != "inline"){
      document.getElementById('change-theme-box').style.display = "inline";
    }
    //if it is open, close it
    else{
      document.getElementById('change-theme-box').style.display = "none";
    }
  }
  // hovering over each theme, change the title to the theme name
  // Cool Night
  document.getElementById('theme-box-1').onmouseover = function(){
    document.getElementById('change-theme').textContent = "Cool Night";
  }
  document.getElementById('theme-box-1').onmouseleave = function(){
    document.getElementById('change-theme').textContent = "Change Theme";
  }
  // Cool Day
  document.getElementById('theme-box-2').onmouseover = function(){
    document.getElementById('change-theme').textContent = "Cool Day";
  }
  document.getElementById('theme-box-2').onmouseleave = function(){
    document.getElementById('change-theme').textContent = "Change Theme";
  }
  // Warm Night
  document.getElementById('theme-box-3').onmouseover = function(){
    document.getElementById('change-theme').textContent = "Warm Night";
  }
  document.getElementById('theme-box-3').onmouseleave = function(){
    document.getElementById('change-theme').textContent = "Change Theme";
  }
  // Warm Day
  document.getElementById('theme-box-4').onmouseover = function(){
    document.getElementById('change-theme').textContent = "Warm Day";
  }
  document.getElementById('theme-box-4').onmouseleave = function(){
    document.getElementById('change-theme').textContent = "Change Theme";
  }


  // When user clicks on theme to change it
  // THEME ONE
  document.querySelector("#theme-box-1").onclick = function(){
    r.style.setProperty('--color-one', '#E0FBFC');
    r.style.setProperty('--color-two', '#9DB4C0');
    r.style.setProperty('--color-three', '#5C6B73');
    r.style.setProperty('--color-four', '#253237');

    // store in local storage
    localStorage.setItem('color-one', '#E0FBFC');
    localStorage.setItem('color-two', '#9DB4C0');
    localStorage.setItem('color-three', '#5C6B73');
    localStorage.setItem('color-four', '#253237');
    document.getElementById('change-theme-box').style.display = "none";
  }
  // THEME ONE: MOBILE
  document.querySelector("#mobile-theme1").onclick = function(){
    r.style.setProperty('--color-one', '#E0FBFC');
    r.style.setProperty('--color-two', '#9DB4C0');
    r.style.setProperty('--color-three', '#5C6B73');
    r.style.setProperty('--color-four', '#253237');

    // store in local storage
    localStorage.setItem('color-one', '#E0FBFC');
    localStorage.setItem('color-two', '#9DB4C0');
    localStorage.setItem('color-three', '#5C6B73');
    localStorage.setItem('color-four', '#253237');
    document.getElementById('change-theme-box').style.display = "none";
    document.getElementById('theme').classList.remove("selected");
  }

  // THEME TWO
  document.querySelector("#theme-box-2").onclick = function(){
    r.style.setProperty('--color-one', '#EDE7E3');
    r.style.setProperty('--color-two', '#FFA62B');
    r.style.setProperty('--color-three', '#489FB5');
    r.style.setProperty('--color-four', '#16697A');
    
    // store in local storage
    localStorage.setItem('color-one', '#EDE7E3');
    localStorage.setItem('color-two', '#FFA62B');
    localStorage.setItem('color-three', '#489FB5');
    localStorage.setItem('color-four', '#16697A');
    document.getElementById('change-theme-box').style.display = "none";
  }
  // THEME TWO: MOBILE
  document.querySelector("#mobile-theme2").onclick = function(){
    r.style.setProperty('--color-one', '#EDE7E3');
    r.style.setProperty('--color-two', '#FFA62B');
    r.style.setProperty('--color-three', '#489FB5');
    r.style.setProperty('--color-four', '#16697A');
    
    // store in local storage
    localStorage.setItem('color-one', '#EDE7E3');
    localStorage.setItem('color-two', '#FFA62B');
    localStorage.setItem('color-three', '#489FB5');
    localStorage.setItem('color-four', '#16697A');
    document.getElementById('change-theme-box').style.display = "none";
    document.getElementById('theme').classList.remove("selected");
  }

  // THEME THREE
  document.querySelector("#theme-box-3").onclick = function(){
    r.style.setProperty('--color-one', '#ddce89');
    r.style.setProperty('--color-two', '#AD8350');
    r.style.setProperty('--color-three', '#8d4938');
    r.style.setProperty('--color-four', '#2A1A1F');
    
    // store in local storage
    localStorage.setItem('color-one', '#ddce89');
    localStorage.setItem('color-two', '#AD8350');
    localStorage.setItem('color-three', '#8d4938');
    localStorage.setItem('color-four', '#2A1A1F');
    document.getElementById('change-theme-box').style.display = "none";
  }
  // THEME THREE: MOBILE
  document.querySelector("#mobile-theme3").onclick = function(){
    r.style.setProperty('--color-one', '#ddce89');
    r.style.setProperty('--color-two', '#AD8350');
    r.style.setProperty('--color-three', '#8d4938');
    r.style.setProperty('--color-four', '#2A1A1F');
    
    // store in local storage
    localStorage.setItem('color-one', '#ddce89');
    localStorage.setItem('color-two', '#AD8350');
    localStorage.setItem('color-three', '#8d4938');
    localStorage.setItem('color-four', '#2A1A1F');
    document.getElementById('change-theme-box').style.display = "none";
    document.getElementById('theme').classList.remove("selected");
  }

  // THEME FOUR
  document.querySelector("#theme-box-4").onclick = function(){
    r.style.setProperty('--color-one', '#ECE4B7');
    r.style.setProperty('--color-two', '#fcc78a');
    r.style.setProperty('--color-three', '#76bda7');
    r.style.setProperty('--color-four', '#e28d50');
    
    // store in local storage
    localStorage.setItem('color-one', '#ECE4B7');
    localStorage.setItem('color-two', '#fcc78a');
    localStorage.setItem('color-three', '#76bda7');
    localStorage.setItem('color-four', '#e28d50');
    document.getElementById('change-theme-box').style.display = "none";
  }
  // THEME FOUR: MOBILE
  document.querySelector("#mobile-theme4").onclick = function(){
    r.style.setProperty('--color-one', '#ECE4B7');
    r.style.setProperty('--color-two', '#fcc78a');
    r.style.setProperty('--color-three', '#76bda7');
    r.style.setProperty('--color-four', '#e28d50');
    
    // store in local storage
    localStorage.setItem('color-one', '#ECE4B7');
    localStorage.setItem('color-two', '#fcc78a');
    localStorage.setItem('color-three', '#76bda7');
    localStorage.setItem('color-four', '#e28d50');
    document.getElementById('change-theme-box').style.display = "none";
    document.getElementById('theme').classList.remove("selected");
  }
</script>