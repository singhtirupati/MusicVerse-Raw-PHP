<?php
  require './header.php';
  $_SESSION["currentMusicId"] = $_GET["id"];
  $music = $database->getMusicById($_SESSION["currentMusicId"]);
  $isFav = $database->isFavourite($_SESSION["userId"], $_SESSION["currentMusicId"]);
?>

<!-- Playback container -->
<div class="playback-container" style="background-image: linear-gradient( #5ddcff5e, #3c67e35c, #4e00c070), url('./assets/<?php echo $music["cover_img"]; ?>');">
  <div class="page-wrapper playback-wrap">
    <div class="playback-content">
      <div class="head-text">
        <h1>Now Playing</h1>
      </div>
      <div class="playback">
        <div class="playback-img">
          <img src="./assets/<?php echo $music["cover_img"]; ?>" alt="<?php echo $music["name"]; ?>">
        </div>
        <div class="playback-title">
          <h2 id="<?php echo $music["music_id"] ?>"><?php echo $music["name"]; ?></h2>
          <h3>Singer: <?php echo $music["singer"]; ?></h3>
          <p>Genre: <?php echo $music["genre"]; ?></p>
        </div>
        <div class="play-box">
          <audio controls autoplay>
            <source src="./assets/<?php echo $music['link'] ?>" type="audio/ogg">
          </audio>
        </div>
        <div class="download-btn">
          <a href="" download="./assets/<?php echo $music['link'] ?>">Download</a>
        </div>
        <div class="next-btn">
          <a href="./welcome.php">Back To Playlist</a>
        </div>

        <!-- Favourite button -->
        <?php 
          if ($isFav) {
        ?>
        <div class="favourite-btn" id="fav">
          <a href="" id="fav-btn"><i class="fa fa-heart fa_custom fa-2x liked"></i></a>
        </div>
        <?php
          }
          else {
        ?>
        <div class="favourite-btn" id="fav">
          <a href="" id="fav-btn"><i class="fa fa-heart fa_custom fa-2x unliked"></i></a>
        </div>
        <?php
          }
        ?>
      </div>
    </div>
  </div>
</div>

<?php
  require './footer.php';
?>
