<?php
  session_start();
  require './classes/Validations.php';
  require './classes/UserDb.php';
  // Check if password reset request has been generated or not. If not then
  // load home page.
  if (!$_SESSION["resetRequest"]) {
    header('Location: ./index.php');
  }
  // Check if password reset form has been submitted or not.
  if (isset($_POST["resetpassword"])) {
    $validation = new Validations();
    $validation->validateEmail($_POST["email"]);
    $validation->validatePassword(($_POST["password"]));
    $validation->matchPassword($_POST["password"], $_POST["cnfPassword"]);
    // Check if all input data are valid or not. If valid then check in database
    // for user email and reset password.
    if ($validation->dataValid) {
      $database = new UserDb();
      // If user exists then update new password in database.
      if ($database->checkUserNameExists($_POST["email"])) {
        // Check if data has been updated in database or not.
        if ($database->updateCredentials($_POST["email"], $_POST["password"])) {
          $msg = "Password reset successfully.";
          $_SESSION["resetRequest"] = FALSE;
        }
      }
      else {
        $msg = "User does not exists.";
      }
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <link rel="icon" href="./assets/img/logo.svg" type="image/icon type">
    <link rel="stylesheet" href="./assets/css/reset.css">
  </head>
  <body>
    <!-- Main container -->
    <div class="main-container">

      <!-- Password reset container -->
      <div class="reset-container">
        <div class="page-wrapper reset-content-wrap">
          <div class="reset-content">
            <div class="error-msg">
              <span><?php if (isset($msg)) {echo $msg;} ?></span>
            </div>
            <div class="logo">
              <img src="./assets/img/logo.svg" alt="Music Verse">
            </div>
            <div class="title-head">
              <h1>Password Reset</h1>
            </div>

            <!-- Form container -->
            <div class="form-container">
              <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-input">
                  <label for="email">Email</label>
                  <input type="text" name="email" id="email" placeholder="Enter your email" onblur="validateEmail()">
                  <span class="error" id="checkEmail"><?php if (isset($validation->errorMsg["emailErr"])) {echo $validation->errorMsg["emailErr"];} ?></span>
                </div>

                <div class="form-input">
                  <label for="password">Password</label>
                  <input type="password" name="password" id="password" placeholder="Password" onblur="validatePassword()">
                  <span class="error" id="checkPass"><?php if (isset($validation->errorMsg["passwordErr"])) {echo $validation->errorMsg["passwordErr"];} ?></span>
                </div>

                <div class="form-input">
                  <label for="password">Confirm Password</label>
                  <input type="password" name="cnfPassword" id="cnfPassword" placeholder="Password" onblur="matchPassword()">
                  <span class="error" id="checkCnfPass"><?php if (isset($validation->errorMsg["cnfPasswordErr"])) {echo $validation->errorMsg["cnfPasswordErr"];} ?></span>
                </div>

                <div class="form-input">
                  <input type="submit" name="resetpassword" id="submit-btn" value="Change Password">
                </div>
              </form>
            </div>
            <div class="back-container">
              <a href="./login.php">Go Back</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="./assets/js/validations.js"></script>
  </body>
</html>
