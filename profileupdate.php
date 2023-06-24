<?php
  require './header.php'; 
  // Check if user has submitted form or not.
  if (isset($_POST["update-profile"])) {
    $validation->validateEmail($_POST["email"]);
    $validation->validateContact($_POST["phone"]);
    $validation->validateInterest($_POST);
    // Check if all input field data are valid or not.
    if ($validation->dataValid) {
      $email->verifyEmail($_POST["email"]);
      // Check for email validation using api.
      if ($email->emailErr == "") {
        // Check if profile has been update or not in database.
        if ($database->updateProfile($_SESSION["email"], $_POST)) {
          $_SESSION["email"] = $_POST["email"];
          $msg = "Profile updated successfully";
        }
        else {
          $msg = "Your profile could not be updated.";
        }

      }
      else {
        $msg = $email->emailErr;
      }
    }
  }
?>

<!-- User profile container -->
<div class="banner-container">
  <div class="page-wrapper banner-wrap">
    <div class="profile-content">
      <div class="error-msg">
        <span><?php if (isset($msg)) {echo $msg;} ?></span>
      </div>
      <div class="title-head">
        <h1>Profile Update</h1>
      </div>

      <!-- Form container -->
      <div class="form-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <div class="form-input">
            <label for="email">Email</label>
            <input type="text" name="email" id="email" placeholder="Enter your email" onblur="validateEmail()" value="<?php if (isset($userProfile)) { echo $userProfile["user_email"]; } ?>">
            <span class="error" id="checkEmail"><?php if (isset($validation->errorMsg["emailErr"])) { echo $validation->errorMsg["emailErr"]; } ?></span>
          </div>

          <div class="form-input">
            <label for="phone">Contact Number</label>
            <input type="text" name="phone" id="phone" placeholder="Contact Number" onblur="validatePhone()" value="<?php if (isset($userProfile)) { echo $userProfile["user_phone"]; } ?>">
            <span class="error" id="checkPhone"><?php if (isset($validation->errorMsg["phoneErr"])) { echo $validation->errorMsg["phoneErr"]; } ?></span>
          </div>

          <div class="form-input interest-div">
            <label for="">Interests - Genre</label>
            <input type="checkbox" id="pop" name="genre[]" value="Pop" <?php if (isset($_POST["genre"]) && isset($_POST["genre"][0])) { echo "checked"; } ?>>
            <label for="pop">Pop</label>
            <input type="checkbox" id="rock" name="genre[]" value="Rock" <?php if (isset($_POST["genre"]) && isset($_POST["genre"][1])) { echo "checked"; } ?>>
            <label for="rock">Rock</label>
            <input type="checkbox" name="genre[]" id="classic" value="Classic" <?php if (isset($_POST["genre"]) && isset($_POST["genre"][2])) { echo "checked"; } ?>>
            <label for="classic">Classic</label>
            <input type="checkbox" name="genre[]" id="hiphop" value="Hip Hop" <?php if (isset($_POST["genre"]) && isset($_POST["genre"][3])) { echo "checked"; } ?>>
            <label for="hiphop">Hip Hop</label>
            <input type="checkbox" name="genre[]" id="others" value="Others" <?php if (isset($_POST["genre"]) && isset($_POST["genre"][4])) { echo "checked"; } ?>>
            <label for="others">Others</label>
            <span class="error" id="checkInterest"><?php if (isset($validation->errorMsg["interestErr"])) { echo $validation->errorMsg["interestErr"]; } ?></span>
          </div>

          <div class="form-input">
            <input type="submit" name="update-profile" id="submit-btn" value="Update Profile">
          </div>
        </form>
      </div>
      <div class="back-container">
        <a href="./dashboard.php" class="back-btn">Go Back</a>
      </div>
    </div>
  </div>
</div>

<?php
  require './footer.php';
?>
