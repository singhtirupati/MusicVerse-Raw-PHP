<?php
  require './header.php';
  require './classes/MusicModel.php';
  $musicFile = new MusicModel();
  // Check if form has been submitted or not.
  if (isset($_POST["add-music"])) {
    $musicFile->uploadMusic($_FILES["music-file"]);
    $nameErr = $musicFile->isEmpty($_POST["music-name"]);
    $singerErr = $musicFile->isEmpty($_POST["singer"]);
    $validation->validateInterest($_POST);
    // Check if music cover image is not empty.
    if (empty($_FILES["tmp_name"])) {
      $musicFile->uploadCoverImage($_FILES["cover-image"]);
    }
    // Check whether uploaded music data are valid or not.
    if ($musicFile->errorMsg["uploadOk"] && $validation->dataValid) {
      // Check if music already exists in database or not, if not then upload
      // music file.
      if (!$database->isMusicExists($_SESSION["userId"], $_POST["music-name"], $_POST["singer"])) {
        $addUserMusic = $database->addUserMusic($_SESSION["userId"], $_POST,
          $musicFile->musicData["musicFileLocation"], $musicFile->musicData["imageFileLocation"]);
        $uploadId = $database->fetchMusicByName($_POST["music-name"], $_POST["singer"]);
        $musicAdd = $database->addMusic($_POST, $musicFile->musicData["musicFileLocation"],
          $musicFile->musicData["imageFileLocation"], $uploadId);
        // Check for whether music has been uploaded or not.
        if ($addUserMusic && $musicAdd) {
          // Check if uploaded file has been moved to local directory.
          if (move_uploaded_file($_FILES["music-file"]["tmp_name"], $musicFile->musicData["musicFileLocation"])) {
            // Check if cover image file is not empty, then upload file to local directory.
            if(!empty($_FILES["tmp_name"])) {
              move_uploaded_file($_FILES["cover-image"]["tmp_name"], $musicFile->musicData["imageFileLocation"]);
            }
            $msg = "Music uploaded successfully!";
          }
        }
      }
      else {
        $msg = "Music already exists!";
      }
    }
  }
?>

<div class="banner-container">
  <div class="page-wrapper banner-wrap">
    <div class="addmusic-content">
      <div class="error-msg">
        <span><?php if(isset($msg)) {echo $msg;} ?></span>
      </div>
      <div class="title-head">
        <h1>Add Music</h1>
      </div>

      <!-- Form container -->
      <div class="form-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
          <div class="music-img">
            <div class="form-input" id="music">
              <label for="music-file">Music File</label>
              <input type="file" name="music-file" id="music-file" class="music-info">
              <span class="error" id="checkMusic"><?php if(isset($musicFile->errorMsg["uploadErr"])) { echo $musicFile->errorMsg["uploadErr"]; } ?></span>
            </div>
  
            <div class="form-input">
              <label for="cover-image">Cover Image</label>
              <input type="file" name="cover-image" id="cover-image" class="music-info">
              <span class="error" id="checkCover"><?php if(isset($musicFile->errorMsg["uploadImgErr"])) { echo $musicFile->errorMsg["uploadImgErr"]; } ?></span>
            </div>
          </div>

          <div class="form-input">
            <label for="music-name">Music Name</label>
            <input type="text" name="music-name" id="music-name" placeholder="Enter music name" required>
            <span class="error" id="checkMusicName"><?php if(isset($nameErr)) { echo $nameErr; } ?></span>
          </div>

          <div class="form-input">
            <label for="singer">Singer(s)</label>
            <input type="text" name="singer" id="singer" placeholder="Singer(s)" required>
            <span class="error" id="checkSinger"><?php if(isset($singerErr)) { echo $singerErr; } ?></span>
          </div>

          <div class="form-input interest-div">
            <label for="genre[]">Genre</label>
            <input type="checkbox" id="pop" name="genre[]" value="Pop">
            <label for="pop">Pop</label>
            <input type="checkbox" id="rock" name="genre[]" value="Rock">
            <label for="rock">Rock</label>
            <input type="checkbox" name="genre[]" id="classic" value="Classic">
            <label for="classic">Classic</label>
            <input type="checkbox" name="genre[]" id="hiphop" value="Hip Hop">
            <label for="hiphop">Hip Hop</label>
            <input type="checkbox" name="genre[]" id="others" value="Others">
            <label for="others">Others</label>
            <span class="error" id="checkInterest"><?php if (isset($validation->errorMsg["interestErr"])) { echo $validation->errorMsg["interestErr"]; } ?></span>
          </div>

          <div class="form-input">
            <input type="submit" name="add-music" id="submit-btn" value="Upload Music">
          </div>
        </form>
      </div>
      <div class="back-container">
        <a href="./welcome.php" class="back-btn">Go Back</a>
      </div>
    </div>
  </div>
</div>

<?php
  require './footer.php';
?>
