<?php

  /**
   * This class will hold all validation methods and 
   * their error messages.
   */
  class Validations {
    /**
     *  @var array $errorMsg
     *    Stores error messages of input fields after validating.
     */
    public array $errorMsg = [
      "nameErr" => "",
      "genderErr" => "",
      "interestErr" => "",
      "phoneErr" => "",
      "emailErr" => "",
      "passwordErr" => "",
      "cnfPasswordErr" => ""
    ];

    /**
     *  @var int $dataValid
     *    Stores 1 if all data fields are valid, 0 if not.
     */
    public int $dataValid = 1;

    /**
     * Function to validate full name.
     * 
     *  @param string $name
     *    Holds full name of user.
     * 
     *  @return bool
     *    True if format valid, false if not.
     */
    public function validateName(string $name) {
      // Check if input field is empty.
      if (empty($name)) {
        $this->errorMsg["nameErr"] = "Name field cannot be empty.";
        $this->dataValid = 0;
        return FALSE;
      }
      // Check if pattern matched or not.
      elseif (!preg_match("/^[a-zA-Z-' ]+$/", $name)) {
        $this->errorMsg["nameErr"] = "Only characters are allowed!";
        $this->dataValid = 0;
        return FALSE;
      }
      else {
        return TRUE;
      }
    }

    /**
     * Function to check gender value.
     * 
     *  @param array $user_data
     *    Contains user all data.
     */
    public function validateGender(array $user_data) {
      // Check if data has some values or not.
      if (!isset($user_data["gender"])) {
        $this->errorMsg["genderErr"] = "Gender is required";
        $this->dataValid = 0;
      }
    }

    /**
     * Function to check whether field contains one value or not.
     * 
     *  @param array $user_data
     *    Hold user all data.
     */
    public function validateInterest(array $user_data) {
      // Check if data has some values or not.
      if (!isset($user_data["genre"])) {
        $this->errorMsg["interestErr"] = "Please select at least 1 genre";
        $this->dataValid = 0;
      }
    }

    /**
     * Function to validate phone number format.
     * 
     *  @param string $phone
     *    Contains phone number.
     * 
     *  @return bool
     *   Return true if format valid, false if not.
     */
    public function validateContact(string $phone) {
      // Check if phone number is empty.
      if (empty($phone)) {
        $this->errorMsg["phoneErr"] = "Phone number is required";
        $this->dataValid = 0;
        return FALSE;
      }
      // Check if phone number pattern matched or not.
      elseif (!preg_match("/^(\+91)[0-9]{10}$/", $phone)) {
        $this->errorMsg["phoneErr"] = "Invalid phone number!";
        $this->dataValid = 0;
        return FALSE;
      }
      else {
        return TRUE;
      }
    }

    /**
     * Function to check email format.
     * 
     *  @param string $email
     *    Contains email address.
     */
    public function validateEmail(string $email) {
      // Check if email is empty or not.
      if (empty($email)) {
        $this->errorMsg["emailErr"] = "Email is required";
        $this->dataValid = 0;
      }
      // Check for email format validation.
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $this->errorMsg["emailErr"] = "Invalid email format!";
        $this->dataValid = 0;
      }
    }

    /**
     * Function to check password pattern.
     *  
     *  @param string $password
     *    Contains password entered by user.
     */
    public function validatePassword(string $password) {
      $pattern = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,15}$/";
      // Check if password is not empty.
      if (empty($password)) {
        $this->errorMsg["passwordErr"] = "Password cannot be empty.";
        $this->dataValid = 0;
      }
      // Check for password length limit.
      elseif (!strlen($password) >= 8 && strlen($password) <= 15) {
        $this->errorMsg["passwordErr"] = "Password length must be greater than 8 characters.";
        $this->dataValid = 0;
      }
      // Check if password matched with pattern or not.
      elseif (!preg_match($pattern, $password)) {
        $this->errorMsg["passwordErr"] = "Password must contain at least one lower, one upper, one numeric and one special character";
        $this->dataValid = 0;
      }
      else {
        $this->errorMsg["passwordErr"] = "";
      }
    }

    /**
     * Function to match password and confirm password.
     * 
     *  @param string $password
     *    Contains user password.
     * 
     *  @param string $cnfpassword
     *    Contains user confirm password.
     * 
     *  @return bool
     *    True if password match, false if not.
     */
    public function matchPassword(string $password, string $cnfpassword) {
      // Check if confirm password is empty or not.
      if (empty($cnfpassword)) {
        $this->errorMsg["cnfPasswordErr"] = "Confirm password cannot be empty";
        $this->dataValid = 0;
        return FALSE;
      }
      // Check if password matched with confirm password or not.
      elseif ($password != $cnfpassword) {
        $this->errorMsg["cnfPasswordErr"] = "Password do not match.";
        $this->dataValid = 0;
        return FALSE;
      }
      else {
        return TRUE;
      }
    }

    /**
     * Function to check user registration data.
     * 
     *  @param array $user_data
     *    Contains all user information.
     * 
     *  @return bool
     *    True if all fields are valid, false if not.
     */
    public function checkRegistration(array $user_data) {
      $this->validateName($user_data["name"]);
      $this->validateGender($user_data);
      $this->validateInterest($user_data);
      $this->validateContact($user_data["phone"]);
      $this->validateEmail($user_data["email"]);
      $this->validatePassword($user_data["password"]);
      $this->matchPassword($user_data["password"], $user_data["cnfPassword"]);
      // Check if all input data fields are valid or not after validation.
      if ($this->dataValid) {
        return TRUE;
      }
      return FALSE;
    }

  }
?>
