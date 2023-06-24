<?php
  session_start();
  require './classes/Validations.php';
  require './classes/UserDb.php';
  require './classes/Email.php';
  // Check if forgetpassword form has been submitted or not.
  if (isset($_POST["forgetPassword"])) {
    $validation = new Validations();
    $validation->validateEmail($_POST["email"]);
    // Check if provided email is valid or not.
    if ($validation->dataValid) {
      $database = new UserDb();
      // Check if user already exists or not. If exists then send password rest
      // link to email id.
      if ($database->checkUserNameExists($_POST["email"])) {
        $email = new Email();
        $email->verifyEmail($_POST["email"]);
        // Check if entered email is active or not.
        if ($email->emailErr == "") {
          // Check if email has been sent or not.
          if ($email->sendEmail($_POST["email"])) {
            $msg = "E-mail has been sent!";
            $_SESSION["resetRequest"] = TRUE;
          }
          else {
            $msg = "E-mail could not be sent!";
          }
        }
        else {
          $msg = $email->emailErr;
        }
      }
      else {
        $msg = "User deos not exists!";
      }
    }
    else {
      $msg = "Invalid email id.";
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password</title>
    <link rel="icon" href="./assets/img/logo.svg" type="image/icon type">
    <link rel="stylesheet" href="./assets/css/forget.css">
  </head>
  <body>
    <!-- Main container -->
    <div class="main-container">
  
    <!-- Login container -->
      <div class="forget-container">
        <div class="page-wrapper forget-content-wrap">
          <div class="forget-content">
            <div class="error-msg">
              <span><?php if (isset($msg)) {echo $msg;} ?></span>
            </div>
            <div class="logo">
              <img src="./assets/img/logo.svg" alt="Music Verse">
            </div>
            <div class="title-head">
              <h1>Forget Password</h1>
              <p>Please enter your registered email id to get password reset link.</p>
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
                  <input type="submit" name="forgetPassword" id="submit-btn" value="Send Email">
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
