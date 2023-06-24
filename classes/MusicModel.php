<?php

  /**
   * This class contains methods to upload music in database.
   */
  class MusicModel {
    /**
     *  @var array $errorMsg
     *    Holds error messages of uploaded music files.
     */
    public array $errorMsg = [
      "uploadOk" => 1,
      "uploadErr" => "",
      "uploadImgErr" => ""
    ];

    /**
     *  @var array $musicData
     *    Stores music information like file location, cover image, extension.
     */
    public array $musicData = [
      "musicFileLocation" => "",
      "musicFileType" => "",
      "imageFileLocation" => "",
      "imageFileType" => ""
    ];

    /**
     * Initializing TARGET_DIR with directory path to upload music.
     */
    const TARGET_DIR = "assets/music/usermusic/";

    /**
     * Initializing IMG_TARGET_DIR with directory path to upload cover image. 
     */
    const IMG_TARGET_DIR = "assets/music/coverimage/";
    
    /**
     * Function to upload music file.
     * 
     *  @param array $musicFile
     *    Contains music file path.
     */
    public function uploadMusic(array $musicFile) {
      $this->musicData["musicFileLocation"] = self::TARGET_DIR . basename($musicFile["name"]);
      $this->musicData["musicFileType"] = strtolower(pathinfo($this->musicData["musicFileLocation"], PATHINFO_EXTENSION));
      // Check file size is not 0.
      if (!$musicFile["size"] == 0) {
        // Check for uploaded file size.
        if ($musicFile["size"] > 10000000) {
          $this->errorMsg["uploadErr"] = "Sorry, music file is too large.";
          $this->errorMsg["uploadOk"] = 0;
        }
        // Check for file extension format.
        if ($this->musicData["musicFileType"] != "mp3"
            && $this->musicData["musicFileType"] != "wav"
            && $this->musicData["musicFileType"] != "ogg") {
          $this->errorMsg["uploadErr"] = "Sorry, only MP3, WAV and OGG music file allowed.";
          $this->errorMsg["uploadOk"] = 0;
        }
      }
      else {
        $this->errorMsg["uploadErr"] = "Please select a music file.";
        $this->errorMsg["uploadOk"] = 0;
      }
    }

    /**
     * Function to upload cover image.
     * 
     *  @param array $musicCover
     *    Holds image path value.
     */
    public function uploadCoverImage(array $musicCover) {
      $this->musicData["imageFileLocation"] = self::IMG_TARGET_DIR . basename($musicCover["name"]);
      $this->musicData["imageFileType"] = strtolower(pathinfo($this->musicData["imageFileLocation"], PATHINFO_EXTENSION));
      // Check file size is not 0.
      if (!$musicCover["size"] == 0) {  
        // Check for uploaded file size.
        if ($musicCover["size"] > 10000000) {
          $this->errorMsg["uploadImgErr"] = "Sorry, image file is too large.";
          $this->errorMsg["uploadOk"] = 0;
        }
        // Check for file extension format.
        if ($this->musicData["imageFileType"] != "jpg"
            && $this->musicData["imageFileType"] != "png"
            && $this->musicData["imageFileType"] != "jpeg"
            && $this->musicData["imageFileType"] != "gif") {
          $this->errorMsg["uploadImgErr"] = "Sorry, only JPG, PNG, JPEG and GIF file allowed.";
          $this->errorMsg["uploadOk"] = 0;
        }
      }
    }

    /**
     * Function check wether input field is empty or not.
     * 
     *  @param string $name
     *    Holds field value.
     * 
     *  @return string
     */
    public function isEmpty(string $name) {

      // Check if file is empty.
      if (empty($name)) {
        $this->errorMsg["uploadOk"] = 0;
        return "Field cannot be empty";
      }
      return "";
    }

  }
?>
