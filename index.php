<?php
  session_start();
  // Check if user is already logged in or not.
  // If logged in then redirect to welcome page, else load home page.
  if (isset($_SESSION["loggedIn"])) {
    header('Location: ./welcome.php');
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MUSIC VERSE | Listen to your music..</title>
    <link rel="icon" href="./assets/img/logo.svg" type="image/icon type">
    <link rel="stylesheet" href="./assets/css/home.css">
  </head>
  <body>
    
    <!-- Main container -->
    <div class="main-container">

      <!-- Navbar container -->
      <div class="navbar">
        <div class="page-wrapper">
          <div class="nav">
            <div class="nav-logo">
              <a><img src="./assets/img/logo.svg" alt="Music Verse">MUSIC VERSE</a>
            </div>
            <ul class="nav-ul">
              <li><a href="./login.php">Sign In</a></li>
              <li><a href="./register.php">Sign Up</a></li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Banner container -->
      <div class="banner-container">
        <div class="page-wrapper banner-wrap">
          <div class="banner-content">
            <h1>Listen to music on <strong>MUSIC VERSE</strong> anytime, anywhere.</h1>
          </div>
        </div>
      </div>

<?php
  require './footer.php';
?>
