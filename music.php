<?php
  session_start();
  require './classes/UserDb.php';
  $database = new UserDb();
  $music = $database->musicList();
  $num_of_rows = $database->calculateRows("music", 0);
  $_SESSION["musicList"] = $music;
  $page = "";
  // Check if page number has been set or not.
  if (isset($_POST["page_no"])) {
    $page = $_POST["page_no"];
  }
  // If not set then assign page number to 1.
  else {
    $page = 1;
  }
  $total_pages = ceil($num_of_rows/8);
  // Check whether music array is empty or not.
  if (!empty($music)) {
    foreach($music as $value) {
?>

<div class="music-box">
  <div class="music-cover-img">
    <img src="./assets/<?php echo $value['cover_img']; ?>" alt="<?php $value['name'] ?>">
    <div class="play-btn">
      <a href="./playmusic.php?id=<?php echo $value['music_id']; ?>" id="<?php echo $value['music_id']; ?>"><img src="./assets/img/play-btn.svg" alt="Play Now"></a>
    </div>
  </div>
  <div class="music-details">
    <h3><?php echo $value['name'] ?></h3>
    <h4>Singer: <?php echo $value['singer'] ?></h4>
    <p>Genre: <?php echo $value['genre'] ?></p>
  </div>
</div>

<?php
    }
  }
  else {
?>

<div class="music-box">
  <h2>No music found</h2>
</div>

<?php
  }
?>

<div class="page-num" id="pagination">
  <?php
    // To show total page number.
    for ($i = 1; $i <= $total_pages; $i++) {
      // If current value of i matches with page number then assign it a active 
      // class.
      if ($i == $page) {
        $class_name = "active";
      }
      // If not then assign it inactive class.
      else {
        $class_name = "inactive";
      }
  ?>
  <a class='<?php echo $class_name ?>' id='<?php echo $i ?>' href=''><?php echo $i ?></a>
  <?php
    }
  ?>
</div>
