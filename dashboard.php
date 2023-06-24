<?php
  require './header.php';
?>

<!-- Dasboard container -->
<div class="dashboard-container">
  <div class="page-wrapper dashboard-wrap">
    <div class="dashboard-content">
      <div class="user-profile">
        <div class="user-data">
          <div class="info-box">
            <div class="info-flex-box">
              <h3>Name:</h3>
            </div>
            <div class="info-flex-box">
              <h3><?php echo $userProfile["user_name"]; ?></h3>
            </div>
          </div>
          <div class="info-box">
            <div class="info-flex-box">
              <h3>Gender:</h3>
            </div>
            <div class="info-flex-box">
              <h3><?php echo $userProfile["user_gender"]; ?></h3>
            </div>
          </div>
          <div class="info-box">
            <div class="info-flex-box">
              <h3>Phone:</h3>
            </div>
            <div class="info-flex-box">
              <h3><?php echo $userProfile["user_phone"]; ?></h3>
            </div>
          </div>
          <div class="info-box">
            <div class="info-flex-box">
              <h3>Email:</h3>
            </div>
            <div class="info-flex-box">
              <h3><?php echo $userProfile["user_email"]; ?></h3>
            </div>
          </div>
          <div class="info-box">
            <div class="info-flex-box">
              <h3>Interests:</h3>
            </div>
            <div class="info-flex-box">
              <h3><?php echo $userProfile["user_interest"]; ?></h3>
            </div>
          </div>
        </div>
      </div>

      <div class="update-btn">
        <a href="./profileupdate.php">Update Profile</a>
      </div>
    </div>
  </div>
</div>

<!-- User Upload container -->
<div class="music-container upload-container">
  <div class="page-wrapper music-wrap">
    <div class="music-content">
      <div class="music-list" id="uploads">        
      </div>
    </div>
  </div>
</div>

<!-- Favourite container -->
<div class="music-container favourite-container">
  <div class="page-wrapper music-wrap">
    <div class="music-content">
      <div class="music-list favourites" id="favourite">
      </div>
    </div>
  </div>
</div>

<?php
  require './footer.php';
?>
