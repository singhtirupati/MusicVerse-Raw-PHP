<?php
  session_start();
  require './classes/UserDb.php';
  require './classes/Validations.php';
  require './classes/Email.php';
  // Check if user is already logged in or not.
  // If logged in then redirect to welcome page, else load home page.
  if (!isset($_SESSION["loggedIn"])) {
    header('Location: ./index.php');
  }
  $database = new UserDb();
  $validation = new Validations();
  $email = new Email();
  $userMusic = $database->getUserMusic($_SESSION["userId"]);
  $userProfile = $database->fetchUserProfile($_SESSION["email"]);
  $userFavourite = $database->getFavourite($_SESSION["userId"]);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
  </head>
  <body>

    <!-- Main container -->
    <div class="main-container">

      <!-- Navbar container -->
      <div class="navbar">
        <div class="page-wrapper">
          <div class="nav">
            <div class="nav-logo">
              <a href="./welcome.php"><img src="./assets/img/logo.svg" alt="Music Verse">MUSIC VERSE</a>
            </div>
            <ul class="nav-ul">
              <li><a href="./dashboard.php"><?php if (isset($_SESSION["username"])) { echo $_SESSION["username"];}?></a></li>
              <li><a href="./welcome.php">Home</a></li>
              <li><a href="./addmusic.php">Add Music</a></li>
              <li><a href="./signout.php">Sign Out</a></li>
            </ul>
          </div>
        </div>
      </div>
