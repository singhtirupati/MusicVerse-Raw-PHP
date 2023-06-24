<?php
  session_start();
  require './classes/UserDb.php';
  require './classes/Validations.php';
  // Check if user is already logged in or not.
  // If logged in then redirect to welcome page, else load home page.
  if (isset($_SESSION["loggedIn"])) {
    header('Location: ./welcome.php');
  }
  // Check if form has been submitted or not. If so then validate user input
  // data.
  if (isset($_POST["login"])) {
    $validation = new Validations();
    $validation->validateEmail($_POST["email"]);
    $validation->validatePassword($_POST["password"]);
    // Check if all input form data are valid or not.
    if ($validation->dataValid) {
      $database = new UserDb();
      if ($database->checkLogin($_POST["email"], $_POST["password"])) {
        $_SESSION["loggedIn"] = TRUE;
        $_SESSION["userId"] = $database->getUserId($_POST["email"]);
        $_SESSION["username"] = $database->getUsername($_POST["email"]);
        $_SESSION["email"] = $_POST["email"];
        header('Location: ./welcome.php');
      }
      else {
        $msg = "Username or password is incorrect.";
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
    <title>Log In | Enjoy your music...</title>
    <link rel="icon" href="./assets/img/logo.svg" type="image/icon type">
    <link rel="stylesheet" href="./assets/css/login.css">
  </head>
  <body>
    <!-- Main container -->
    <div class="main-container">

    <!-- Login container -->
      <div class="login-container">
        <div class="page-wrapper login-content-wrap">
          <div class="login-content">
            <div class="error-msg">
              <span><?php if (isset($msg)) {echo $msg;} ?></span>
            </div>
            <div class="logo">
              <img src="./assets/img/logo.svg" alt="Music Verse">
            </div>
            <div class="title-head">
              <h1>Login to enjoy your music.</h1>
            </div>

            <!-- Form container -->
            <div class="form-container">
              <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-input">
                  <label for="email">Email</label>
                  <input type="text" name="email" id="email" placeholder="Enter your email" value="<?php if (isset($_POST["email"])) { echo $_POST["email"]; } ?>" onblur="validateEmail()">
                  <span class="error"><?php if (isset($validation->errorMsg["emailErr"])) {echo $validation->errorMsg["emailErr"];} ?></span>
                  <span class="error" id="checkEmail"></span>
                </div>

                <div class="form-input">
                  <label for="password">Password</label>
                  <input type="password" name="password" id="password" placeholder="Password" onblur="validatePassword()">
                  <span class="error"><?php if (isset($validation->errorMsg["passwordErr"])) {echo $validation->errorMsg["passwordErr"];} ?></span>
                  <span class="error" id="checkPass"></span>
                </div>

                <div class="form-input">
                  <a href="./forgetpassword.php">Forgot your password?</a>
                </div>

                <div class="form-input">
                  <input type="submit" name="login" id="submit-btn" value="Sign In">
                </div>
              </form>
            </div>

            <div class="signup-container">
              <p>Don't have an account?</p>
              <a href="./register.php">Sign Up For Free</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="./assets/js/validations.js"></script>
  </body>
</html>
