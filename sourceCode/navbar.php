<?php
session_start();

// Function to check if the user is logged in
function isUserLoggedIn() {
return isset($_SESSION["user"]);
}

?>
<nav class="navbar navbar-expand-lg navbar-light fixed-top bg-light">
    <a class="navbar-brand" href="#">BookEcho</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
      <ul class="navbar-nav">
        <li class="nav-item active">
          <a class="nav-link" href="index.php">Home<span class="sr-only"></span></a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Catregory</a>
          <div class="dropdown-menu" aria-labelledby="dropdown01">
            <a class="dropdown-item" href="classic.php">Classical Literature</a>
            <a class="dropdown-item" href="sci-fi.php">Science Fiction</a>
            <a class="dropdown-item" href="fantasy.php">Fantasy</a>
            <a class="dropdown-item" href="self-help.php">Self-Help</a>
            <a class="dropdown-item" href="adventure.php">Adventure</a>   
            <a class="dropdown-item" href="travel.php">Travel</a>  
            <a class="dropdown-item" href="#"></a>
          </div>
        </li>      
        <li class="nav-item">
          <a class="nav-link" href="user_profile.php">My Profile</a>
        </li>        
          <?php if (isUserLoggedIn()) : ?>
            <li class="nav-item">
            <a class = "nav-link" href="logout.php?action=logout">Logout</a>
            </li>
          <?php else : ?>               
          <li class="nav-item">
          <a class ="nav-link" href="login.php">Login</a>
          </li>
          <li class="nav-item">
          <a class = "nav-link" href="register.php">Register</a>
          </li>
         <?php endif; ?>      
      </ul>
      <form class="d-flex" role="search">
          <input class="form-control me-2" id = "search" name = "search" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form>  
</nav>