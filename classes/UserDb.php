<?php

  // Including Dotenv to access env variables.
  require './vendor/autoload.php';
  use Dotenv\Dotenv;
  $dotenv = Dotenv::createImmutable("./");
  $dotenv->load();

  /**
   * UserDb class hold database data.
   * This class have methods to insert and update data in databse.
   */
  class UserDb {
    /**
     *  @var string $dbName
     *    Contains database name.
     */
    private string $dbName;

    /**
     *  @var string $dbUsername
     *    Contains database username.
     */
    private string $dbUsername;

    /**
     *  @var string $dbPassword
     *    Stores database user password.
     */
    private string $dbPassword;

    /**
     *  @var object $connectionData
     *    Holds database connection object.
     */
    public object $connectionData;

    /**
     * Constructor to initialize UserDb class with databasename, username and 
     * password.
     */
    public function __construct() {
      $this->dbName = $_ENV['DBNAME'];
      $this->dbUsername = $_ENV['USERNAME'];
      $this->dbPassword = $_ENV['PASSWORD'];
      $this->databaseConnet();
    }

    /**
     * Function to connect database.
     */
    public function databaseConnet() {
      try {
        $this->connectionData = new PDO("mysql:host=localhost;dbname=$this->dbName", $this->dbUsername, $this->dbPassword);
        $this->connectionData->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      }
      catch (PDOException $e) {
        echo "Error while connecting database: " . $e->getMessage();
      }
    }

    /**
     * Function to close database connection.
     */
    public function disconnectDb() {
      $this->connectionData = NULL;
    }

    /**
     * Function to check whether login email and password exist in the database
     * or not.
     * 
     *  @param string $username
     *    Contains user email used for login.
     * 
     *  @param string $password
     *    Contains user login password
     * 
     *  @return bool
     *    Return TRUE if data exists in database, if not then return FALSE.
     */
    public function checkLogin(string $username, string $password) {
      try {
        $query = $this->connectionData->prepare("SELECT * FROM admin WHERE user_email = :username AND user_password = :password");
        $query->bindParam(':username', $username);
        $query->bindParam(':password', $password);
        $query->execute();
        // Check how many rows are returned
        if ($query->rowCount() == 1) {
          return TRUE;
        }
        return FALSE;
      }
      catch (PDOException $e) {
        echo $e;
        return FALSE;
      }
    }

    /**
     * Function to check whether username or email exists in the database or
     * not.
     * 
     *  @param string $email
     *    Contains user email used for login.
     * 
     *  @return bool
     *    Return TRUE if data exists in database, if not then return FALSE.
     */
    public function checkUserNameExists(string $email) {
      try {
        $query = $this->connectionData->prepare("SELECT * FROM admin WHERE user_email = :email");
        $query->bindParam(':email', $email);
        $query->execute();
        // Check row count.
        if ($query->rowCount() == 1) {
          return TRUE;
        }
        return FALSE;
      }
      catch (PDOException $e) {
        echo $e;
        return FALSE;
      }
    }

    /**
     * Function to check whether phone number exists or not.
     * 
     *  @param string $phone
     *    Holds phone number to check for.
     * 
     *  @return bool
     *    Return true if phone number exists, false if not.
     */
    public function checkUserContactExists(string $phone) {
      try {
        $query = $this->connectionData->prepare("SELECT * FROM user_info WHERE user_phone = :phone");
        $query->bindParam(':phone', $phone);
        $query->execute();
        // Check row count.
        if ($query->rowCount() == 1) {
          return TRUE;
        }
        return FALSE;
      }
      catch (PDOException $e) {
        echo $e;
        return FALSE;
      }
    }

    /**
     * Function to update user password in the database table.
     * 
     *  @param string $email
     *    Contains user login email.
     *
     *  @param string $newPassword
     *    Contains user login new password.
     *
     *  @return bool
     *    It will return TRUE if query has been executed successfully,
     *    FALSE if not.
     */
    public function updateCredentials(string $email, string $newPassword) {
      try {
        $query = $this->connectionData->prepare("UPDATE admin SET user_password = :newPassword WHERE user_email = :email");
        $query->bindParam(':newPassword', $newPassword);
        $query->bindParam(':email', $email);
        return $query->execute();
      }
      catch (PDOException $e) {
        echo $e;
        return FALSE;
      }
    }

    /**
     * Function to update user profile in the database table.
     * 
     *  @param string $email
     *    Contains user current email.
     * 
     *  @param array $newData
     *    Contains user profile new information data to be updated.
     *
     *  @return bool
     *    It will return TRUE if query has been executed successfully,
     *    FALSE if not.
     */
    public function updateProfile(string $email, array $newData) {
      $genre = $this->genreString($newData["genre"]);
      try {
        $query = $this->connectionData->prepare("UPDATE admin
          INNER JOIN user_info
          ON user_info.user_id = admin.user_id
          SET user_email = :newEmail,
            user_phone = :newContact,
            user_interest = :newInterest
          WHERE admin.user_email = :email");
  
        $query->bindParam(':newEmail', $newData["email"]);
        $query->bindParam(':newContact', $newData["phone"]);
        $query->bindParam(':newInterest', $genre);
        $query->bindParam(':email', $email);
        return $query->execute();
      }
      catch (PDOException $e) {
        echo $e;
        return FALSE;
      }
    }

    /**
     * Function to register new user data into table.
     *  
     *  @param array $user_data
     *    Contains user all data.
     * 
     *  @return bool
     *    It will return TRUE if user data has been insert, FALSE if not.
     */
    public function registerUser(array $user_data) {
      $genre = $this->genreString($user_data["genre"]);
      try {
        $query = $this->connectionData->prepare("INSERT INTO admin (user_email, user_password)
         VALUES (:email, :password)");
        $query->bindParam(':email', $user_data["email"]);
        $query->bindParam(':password', $user_data["password"]);
        $query->execute();
        $query1 = $this->connectionData->prepare("INSERT INTO user_info(user_name, user_gender, user_phone, user_interest)
        VALUES (:name, :gender, :phone, :interest)");
        $query1->bindParam(':name', $user_data["name"]);
        $query1->bindParam(':gender', $user_data["gender"]);
        $query1->bindParam(':phone', $user_data["phone"]);
        $query1->bindParam(':interest', $genre);
        $query1->execute();
        return ($query && $query1);
      }
      catch (PDOException $e) {
        echo $e;
        return FALSE;
      }
    }

    /**
     * Function to get username from database.
     * 
     *  @param string $email
     *    Contains email id.
     * 
     *  @return mixed
     *    Return result in array if found any record else false.
     */
    public function getUsername(string $email) {
      try {
        $query = $this->connectionData->prepare("SELECT user_name FROM user_info
          INNER JOIN admin
          ON user_info.user_id = admin.user_id
          WHERE user_email = :email");
        $query->bindParam(':email', $email);
        $query->execute();
        return $query->fetchColumn();
      }
      catch (PDOException $e) {
        echo $e;
        return FALSE;
      }
    }

    /**
     * Function to get user id from database.
     * 
     *  @param string $email
     *    Contains email id.
     * 
     *  @return mixed
     *    Return a column, else false if no records found.
     */
    public function getUserId(string $email) {
      try {
        $query = $this->connectionData->prepare("SELECT user_id FROM admin WHERE user_email = :email");
        $query->bindParam(':email', $email);
        $query->execute();
        return $query->fetchColumn();
      }
      catch (PDOException $e) {
        echo $e;
        return FALSE;
      }
    }

    /**
     * Function to retrieve user data from database.
     * 
     *  @param string $email
     *    Contains email id.
     *    
     *  @return mixed
     *    Return records in array if exists else return false.
     */
    public function fetchUserProfile(string $email) {
      try {
        $query = $this->connectionData->prepare("SELECT user_email, user_phone, user_name, user_gender, user_interest FROM admin
          INNER JOIN user_info
          ON admin.user_id = user_info.user_id
          WHERE user_email = :email");
        $query->bindParam(':email', $email);
        $query->execute();
        $response = $query->fetch();
        return $this->isEmpty($response);
      }
      catch (PDOException $e) {
        echo $e;
        return FALSE;
      }
    }

    /**
     * Function to concatinate string.
     * 
     *  @param array $genreArr
     *    Contains genre data in array.
     *  
     *  @return string
     *   Return string after concatinating.
     */
    public function genreString(array $genreArr) {
      $genre = "";
      foreach($genreArr as $checked) {
        $value = $checked;
        $genre = $value . ", " . $genre;
      }
      return $genre;
    }

    /**
     * Function to remove whitespaces from filename.
     * 
     *  @param string $filename
     *    Contains filename.
     *  
     *  @return string
     *    Return filename.
     */
    public function trimFileName(string $filename) {
      $str = explode(" ", $filename);
      foreach($str as $value) {
        $str = $value;
      }
      return $str;
    }

    /**
     * Function to check whether passed data is empty or not. If empty then
     * return false, if not then return data itself.
     * 
     *  @param mixed $data
     *    Holds values need to be checked.
     * 
     *  @return mixed
     *    Return false if empty, if not then return data parameter itself.
     */
    public function isEmpty(mixed $data) {
      if(empty($data)) {
        return FALSE;
      }
      return $data;
    }

    /**
     * Function to add music in database.
     * 
     *  @param array $musicData
     *    Contains music name, singer and genre.
     * 
     *  @param string $link
     *    Contains music link.
     * 
     *  @param string $coverImage
     *    Contains music cover image.
     * 
     *  @param int $userMusicId
     *    Contains usermusic id.
     * 
     *  @return bool
     *    True if query executed successfully.
     */
    public function addMusic(array $musicData, string $link, string $coverImage, int $userMusicId) {
      $genres = $this->genreString($musicData["genre"]);
      try {
        // Check if music cover image empty. If music cover not uploaded then
        // upload default cover.
        if($coverImage == "") {
          $query = $this->connectionData->prepare("INSERT INTO music(name, singer, genre, link, user_music_id)
            VALUES (:name, :singer, :genre, :link, :user_music_id)");
        }
        // Upload user uploaded music cover image.
        else {
          $query = $this->connectionData->prepare("INSERT INTO music(name, singer, genre, link, cover_img, user_music_id)
            VALUES (:name, :singer, :genre, :link, :coverImage, :user_music_id)");
          $query->bindParam(':coverImage', $coverImage);
        }
        $query->bindParam(':name', $musicData["music-name"]);
        $query->bindParam(':singer', $musicData["singer"]);
        $query->bindParam(':genre', $genres);
        $query->bindParam(':link', $link);
        $query->bindParam(':user_music_id', $userMusicId);
        return $query->execute();
      }
      catch(PDOException $e) {
        echo $e;
        return FALSE;
      }
    }

    /**
     * Function to add music in database and user table.
     *
     *  @param int $userId
     *    Contains user id.
     * 
     *  @param array $musicData
     *    Contains music name, singer and genre.
     * 
     *  @param string $link
     *    Contains music link.
     * 
     *  @param string $coverImage
     *    Contains music cover image.
     * 
     *  @return bool
     *    True if query executed successfully, false if not.
     */
    public function addUserMusic(int $userId, array $musicData, string $link, string $coverImage) {
      $genres = $this->genreString($musicData["genre"]);
      try {
        // Check if music cover image empty. If music cover not uploaded then 
        // upload default cover.
        if($coverImage == "") {
          $query = $this->connectionData->prepare("INSERT INTO user_music(user_id, name, singer, genre, link)
            VALUES (:userId, :name, :singer, :genre, :link)");
        }
        else {
          $query = $this->connectionData->prepare("INSERT INTO user_music(user_id, name, singer, genre, link, cover_img)
            VALUES (:userId, :name, :singer, :genre, :link, :coverImage)");
          $query->bindParam(':coverImage', $coverImage);
        }
        $query->bindParam(':userId', $userId);
        $query->bindParam(':name', $musicData["music-name"]);
        $query->bindParam(':singer', $musicData["singer"]);
        $query->bindParam(':genre', $genres);
        $query->bindParam(':link', $link);
        return $query->execute();
      }
      catch(PDOException $e) {
        return FALSE;
      }
    }

    /**
     * Function to fetch music from database.
     * 
     *  @return mixed
     *    Return records in array if exists else return false.
     */
    public function requestMusic() {
      try {
        $query = $this->connectionData->prepare("SELECT * FROM music");
        $query->execute();
        $response = $query->fetchAll();
        return $this->isEmpty($response);
      }
      catch (PDOException $e) {
        echo $e;
        return FALSE;
      }
    }

    /**
     * Function to fetch all music from database with limit.
     * 
     *  @return mixed
     *    Return array if data is present, else return false.
     */
    public function musicList() {
      try {
        $limit_per_page = 8;
        $page = "";
        // Check if page number is set.
        if(isset($_POST["page_no"])) {
          $page = $_POST["page_no"];
        }
        // If not set then set page number to 1.
        else {
          $page = 1;
        }
        $offsets = ($page - 1) * $limit_per_page;
        $query = $this->connectionData->prepare("SELECT * FROM music LIMIT {$offsets}, {$limit_per_page}");
        $query->execute();
        $response = $query->fetchAll();
        return $this->isEmpty($response);
      }
      catch (PDOException $e) {
        echo $e;
        return FALSE;
      }
    }

    /**
     * Function to calculate number of records returned in query.
     * 
     *  @param string $table_name
     *    Contains table name.
     * 
     *  @param int $userId
     *    Contains current user id.
     * 
     *  @return mixed
     */
    public function calculateRows(string $table_name, int $userId) {
      try {
        // If user id is 0 then load all music from database
        if($userId == 0) {
          $query = $this->connectionData->prepare("SELECT * FROM {$table_name}");
        }
        // If not then load only user uploaded music.
        else {
          $query = $this->connectionData->prepare("SELECT * FROM music
            INNER JOIN user_music
            ON user_music.user_music_id = music.user_music_id
            WHERE user_id = :userId");
          $query->bindParam(':userId', $userId);
        }
        $query->execute();
        return $query->rowCount();
      }
      catch (PDOException $e) {
        echo $e;
        return FALSE;
      }
    }    

    /**
     * Function to get user added music from database.
     * 
     *  @param int $userId
     *    Holds user id.
     * 
     *  @return mixed
     *    Return array if data is present, else return false.
     */
    public function getUserMusic(int $userId) {
      try {
        $limit_per_page = 8;
        $page = "";
        // Check if page number is set or not.
        if (isset($_POST["page_no"])) {
          $page = $_POST["page_no"];
        }
        // If page number is not set then set page number to 1.
        else {
          $page = 1;
        }
        $offsets = ($page - 1) * $limit_per_page;
        $query = $this->connectionData->prepare("SELECT * FROM music
          INNER JOIN user_music
          ON user_music.user_music_id = music.user_music_id
          WHERE user_id = :userId
          LIMIT {$offsets}, {$limit_per_page}");
        $query->bindParam(':userId', $userId);
        $query->execute();
        $response = $query->fetchAll();
        return $this->isEmpty($response);
      }
      catch (PDOException $e) {
        echo $e;
        return FALSE;
      }
    }

    /**
     * Function to get music by music id from database.
     * 
     *  @param int $musicId
     *    Holds music id.
     * 
     *  @return mixed
     *    Return array if data is present, else return false.
     */
    public function getMusicById($musicId) {
      try {
        $query = $this->connectionData->prepare("SELECT * FROM music WHERE music_id = :musicId");
        $query->bindParam(':musicId', $musicId);
        $query->execute();
        $response = $query->fetch();
        return $this->isEmpty($response);
      }
      catch (PDOException $e) {
        echo $e;
        return FALSE;
      }
    }

    /**
     * Function to get music from database by music name and singer name.
     * 
     *  @param string $musicName
     *    Holds music name.
     * 
     *  @param string $singer
     *    Holds singer name value.
     * 
     *  @return mixed
     */
    public function fetchMusicByName(string $musicName, string $singer) {
      try {
        $query = $this->connectionData->prepare("SELECT user_music_id FROM user_music WHERE name = :musicName AND singer = :singer");
        $query->bindParam(':musicName', $musicName);
        $query->bindParam(':singer', $singer);
        $query->execute();
        $response = $query->fetchColumn();
        return $response;
      }
      catch (PDOException $e) {
        echo $e;
        return FALSE;
      }
    }

    /**
     * Function to check whether music exists or not in database.
     *  
     *  @param int $userId
     *    Contains user id.
     * 
     *  @param string $musicName 
     *    Contains music name. 
     * 
     *  @param string $singer
     *    Contains music singer value.
     * 
     *  @return bool
     *    True if music exists, false if not.
     */
    public function isMusicExists(int $userId, string $musicName, string $singer) {
      try {
        // Check for user uploaded music.
        if($userId) {
          $query = $this->connectionData->prepare("SELECT name FROM music WHERE name = :musicName AND singer = :singer");
        }
        else {
          $query = $this->connectionData->prepare("SELECT name FROM user_music WHERE user_id = :userId AND name = :musicName AND singer = :singer");
          $query->bindParam(':userId', $userId);
        }
        $query->bindParam(':musicName', $musicName);
        $query->bindParam(':singer', $singer);
        $query->execute();
        // Check record row count.
        if($query->rowCount() >= 1) {
          return TRUE;
        }
        return FALSE;
      }
      catch (PDOException $e) {
        echo $e;
        return FALSE;
      }
    }

    /**
     * Function to add music to favourite database.
     * 
     *  @param int $userId
     *    Contains user id.
     * 
     *  @param int $musicId
     *    Contains music id.
     * 
     *  @return bool
     *    True if music added to favourite, false if not.
     */
    public function addFavourite(int $userId, int $musicId) {
      try {
        $query = $this->connectionData->prepare("INSERT INTO favourites(user_id, music_id)
          VALUES(:userId, :musicId)");
        $query->bindParam(':userId', $userId);
        $query->bindParam(':musicId', $musicId);
        return $query->execute();
      }
      catch(PDOException $e) {
        return FALSE;
      }
    }

    /**
     * Function to remove music from favourite database.
     * 
     *  @param int $userId
     *    Contains user id.
     * 
     *  @param int $musicId
     *    Contains music id.
     * 
     *  @return bool
     *    True if music removed from favourite, false if not.
     */
    public function removeFavourite(int $userId, int $musicId) {
      try {
        $query = $this->connectionData->prepare("DELETE FROM favourites
          WHERE user_id = :userId AND music_id = :musicId");
        $query->bindParam(':userId', $userId);
        $query->bindParam(':musicId', $musicId);
        return $query->execute();
      }
      catch(PDOException $e) {
        return FALSE;
      }
    }

    /**
     * Function to check whether music is favourite or not.
     * 
     *  @param int $userId
     *    Contains user id.
     * 
     *  @param int $musicId
     *    Contains music id.
     * 
     *  @return bool
     *    True if music exists, false if not.
     */
    public function isFavourite(int $userId, int $musicId) {
      try {
        $query = $this->connectionData->prepare("SELECT fav_id FROM favourites WHERE user_id = :userId AND music_id = :musicId");
        $query->bindParam(':userId', $userId);
        $query->bindParam(':musicId', $musicId);
        $query->execute();
        // Check record row count.
        if($query->rowCount() >= 1) {
          return TRUE;
        }
        return FALSE;
      }
      catch (PDOException $e) {
        echo $e;
        return FALSE;
      }
    }

    /**
     * Function to get user favourite music from database.
     * 
     *  @param int $userId
     *    Contains user id.
     * 
     *  @return mixed
     *    Return array if data is present, else return false.
     */
    public function getFavourite(int $userId) {
      try {
        $limit_per_page = 8;
        $page = "";
        // Check if page number is set or not.
        if (isset($_POST["page_no"])) {
          $page = $_POST["page_no"];
        }
        // If not set page number then set it to 1.
        else {
          $page = 1;
        }
        $offsets = ($page - 1) * $limit_per_page;
        $query = $this->connectionData->prepare("SELECT m.music_id, m.name, m.singer, m.genre, m.link, m.cover_img 
          FROM favourites f
          INNER JOIN music m
          ON f.music_id = m.music_id
          WHERE user_id = :userId
          LIMIT {$offsets}, {$limit_per_page}");
        $query->bindParam(':userId', $userId);
        $query->execute();
        $response = $query->fetchAll();
        return $this->isEmpty($response);
      }
      catch (PDOException $e) {
        echo $e;
        return FALSE;
      }
    }

    /**
     * Function to get count of user favourite music from database.
     * 
     *  @param int $userId
     *    Contains user id.
     * 
     *  @return bool
     *    True if music exists, false if not.
     */
    public function getFavouriteCount(int $userId) {
      try {
        $query = $this->connectionData->prepare("SELECT m.music_id
          FROM favourites f
          INNER JOIN music m
          ON f.music_id = m.music_id
          WHERE user_id = :userId");
        $query->bindParam(':userId', $userId);
        $query->execute();
        return $query->rowCount();
      }
      catch (PDOException $e) {
        echo $e;
        return FALSE;
      }
    }

    /**
     * Function to toggle favourite music button. It will add to favourite if it
     * is not favourite and vise-versa.
     * 
     *  @param int $userId
     *    Contains user id.
     * 
     *  @param int $musicId
     *    Contains music id.
     * 
     *  @return bool
     */
    public function favourite(int $userId, int $musicId) {
      $isFav = $this->isFavourite($userId, $musicId);
      // Check if music is already favourite or not.
      if($isFav) {
        $this->removeFavourite($userId, $musicId);
        return FALSE;
      }
      $this->addFavourite($userId, $musicId);
      return TRUE;
    }

  }
?>
