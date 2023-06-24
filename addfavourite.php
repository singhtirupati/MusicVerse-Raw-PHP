<?php
  session_start();
  require './classes/UserDb.php';
  // Check if user is logged in or not.
  if(!isset($_SESSION["loggedIn"])) {
    header('Location: ./index.php');
  }
  $database = new UserDb();
  echo $database->favourite($_SESSION["userId"], $_SESSION["currentMusicId"]);
?>
