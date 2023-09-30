<?php 
   session_start();
   
   // Redirect to login page if email session variable is not set.
   if (!isset($_SESSION['email'])) {
   header("Location: index.php");
   exit();
   }
   
   if (!isset($_SESSION['forgot'])== true) {
   header('Location:index.php');
   exit;
   }else{
   
   $email = $_SESSION['email'];
   
   require_once "includes/dbh.inc.php";
   
   if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // get user input
      $securityQuestion = $_POST["security_question"];
      $securityAnswer = $_POST["security_answer"];
   
      // fetch security answer for the selected security question
      $stmt = $conn->prepare("SELECT security_answer FROM user_security_tbl WHERE security_question = ?");
      $stmt->execute([$securityQuestion]);
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
   
      // compare entered answer with the fetched answer
      if (count($result) > 0) {
          $row = $result[0];
          if ($securityAnswer === "") {
            header("Location: forgotPass.php?error-email=Please provide a security answer.");
            exit();
        }
          elseif ($securityAnswer == $row["security_answer"]) {
              // redirect to password reset page
              header("Location: resetPassword.php?success=Security answer is correct. You can now reset your password.");
              exit();
          } else {
            header("Location: forgotPass.php?error-email=Security answer does not match. Please try again.");

              exit();
          }
      }
   }
   
   }
   
   
   
   
   
   
   ?>
<DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>Reset</title>
      <meta content="width=device-width, initial-scale=1.0" name="viewport">
      <meta content="" name="keywords">
      <meta content="" name="description">
      <link rel="stylesheet" href="assets/fontawesome-free-6.3.0-web/css/all.css">
      <link rel="stylesheet" href="/assets/fontawesome-free-6.3.0-web/css/fontawesome.css">
      <link rel="stylesheet" href="assets/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/css/login.css">
      <script src="assets/js/jquery-ui.min.js"></script>
      <script src="assets/js/general/jquery.min.js"></script>
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script>
         hideErrorMessage();     
         disableBackButton();
      </script>
      <script src="assets/js/script.js"></script>
      <!-- Favicon -->
      <!-- Google Web Fonts -->
      <link href="css/bootstrap.min.css" rel="stylesheet">
   </head>
   <style>
      /* Your existing styles */
      body {
      font-family: 'OpenSans', sans-serif;
      background: white;
      }
      /*------------ Reset container ------------*/
      .box-area {
      max-width: 930px;
      width: 100%;
      margin: 0 auto; /* Center the container horizontally */
      }
      /*------------ Right box ------------*/
      .right-box {
      padding: 40px 30px 40px 40px;
      }
      /*------------ Custom Placeholder ------------*/
      ::placeholder {
      font-size: 14px;
      color: #a0a1a2 !important;
      }
      /* Rounded classes */
      .rounded-4 {
      border-radius: 20px;
      }
      .rounded-5 {
      border-radius: 30px;
      }
      .login {
      color: #444444;
      }
      /* Media query for tablet devices */
      @media (max-width: 768px) {
      #logo {
      max-width: 100px; /* Adjust the max-width as needed */
      }
      .container {
      width: 100%;
      padding-right: var(--bs-gutter-x, -1.25rem) !important;
      padding-left: var(--bs-gutter-x, -1.25rem) !important;
      margin-right: auto;
      margin-left: auto;
      }
      }
      /* Media query for screens with a width between 769px and 991px */
      @media (min-width: 769px) and (max-width: 991px) {
      .box-area {
      margin: 0 10px;
      }
      .vertical.bg-white.mx-3 {
      margin: 0 2px !important;
      }
      img {
      max-width: 105px !important;
      height: auto !important;
      }
      h5.text-white {
      font-size: 20px !important;
      }
      .text-center {
      font-size: 14px !important;
      }
      .col-md-8 {
      flex: 0 0 auto;
      width: 98% !important;
      }
      }
      /* Media query for screens with a maximum width of 768px */
      @media only screen and (max-width: 768px) {
      .box-area {
      margin: 0 10px;
      }
      .featured-image {
      display: none;
      }
      .col-md-4 {
      }
      }
      .left-box {
      height: auto;
      overflow: hidden;
      border-top-right-radius: 10px;
      border-bottom-left-radius: 0px !important;
      }
      .right-box {
      padding: 30px 30px 62px 30px;
      }
      .row-logo {
      flex-direction: row;
      align-items: center;
      justify-content: center;
      }
      .col-md-6 {
      display: flex;
      flex-direction: row;
      justify-content: center;
      }
      .vertical {
      display: none;
      }
      .row-logo {
      display: flex;
      align-items: center;
      }
      .col-md-6 {
      display: flex;
      flex-direction: column;
      justify-content: center;
      }
      .row {
      width: 100%;
      }
      .vertical {
      width: 2px;
      }
      /* Adjust the size of the image */
      #logo {
      max-width: 100%;
      height: auto;
      }
      /* Adjust the font size for tables */
      table {
      font-size: 14px;
      }
      /* Button styles */
      .button-login {
      color: white;
      background-color: #09418c;
      }
      .button-login:hover {
      background-color: #06397d !important;
      color: white;
      }
      /* Notification animation */
      .notification {
      width: 100%!important;
      animation: shake 0.5s ease-in-out 0.1s;
      transform-origin: bottom right;
      }
      /* Form control focus styles */
      .form-control:focus {
      border-color: #4f70e9 !important;
      box-shadow: 0 0 5px rgba(66, 76, 222, 0.5) !important;
      color: gray;
      font-weight: 600;
      background-color: white;
      }.shake{
      width: 100%;
      animation: shaking 0.5s ease-in-out 0.1s;
      transform-origin: bottom right;
      }.forgotpass:hover{
      color:#254464 !important;
      } .input-field {
      position: relative;
      }
      .password-toggle-btn {
      position: absolute;
      top: 0;
      right: 0;
      transform:translate(-63%, -4%);
      border:none;
      background:transparent;
      }
      @keyframes shake {
      0%, 100% {
      transform: translateX(0);
      }
      25%, 75% {
      transform: translateX(-10px);
      }
      50% {
      transform: translateX(10px);
      }
      }
      @keyframes shaking {
      0%, 100% {
      transform: translateX(0);
      }
      25%, 75% {
      transform: translateX(-10px);
      }
      50% {
      transform: translateX(10px);
      }
      }/* Add this CSS to position the back arrow in the top-left corner */
.back-arrow {

    position: absolute;
    top: 66px;
    left: 21px;
    z-index: 1;
    font-size: 26px;

}.back-arrow:hover{

    transform: scale(1)!important; /* Scale up the icon when hovering over the card-box */
}.notification {
    width: 100%!important;
    animation: shake 0.5s ease-in-out 0.1s;
    transform-origin: bottom right;
}

/* Form control focus styles */
.form-control:focus {
    border-color: #4f70e9 !important;
    box-shadow: 0 0 5px rgba(66, 76, 222, 0.5) !important;
    color: gray;
    font-weight: 600;
    background-color: white;
}.shake{
    width: 100%;
    animation: shaking 0.5s ease-in-out 0.1s;
    transform-origin: bottom right;
}.forgotpass:hover{
    color:#254464 !important;
} .input-field {
        position: relative;
    }

    .password-toggle-btn {
        position: absolute;
    top: 0;
    right: 0;
    transform:translate(-63%, -4%);
    border:none;
    background:transparent;
    }


@keyframes shake {
    0%, 100% {
        transform: translateX(0);
    }
    25%, 75% {
        transform: translateX(-10px);
    }
    50% {
        transform: translateX(10px);
    }
}

@keyframes shaking {
    0%, 100% {
        transform: translateX(0);
    }
    25%, 75% {
        transform: translateX(-10px);
    }
    50% {
        transform: translateX(10px);
    }
}


   </style>
   <body>
      <!----------------------- Main Container -------------------------->
      <div class="container">
      <div class="forms-container ">
         <div class="signin-signup ">
            <div class=" col-7 d-flex justify-content-center">
               <?php include 'template/notification/error.php'; ?>
               <?php include 'template/notification/success.php'; ?>  
            </div>
            <form class="text-center" id="forgotPasswordForm"  action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
               <h4 class="title mb-3">Account Recovery</h4>
               <i class="mx-auto">
                  <svg xmlns="http://www.w3.org/2000/svg" width="4em" height="4em" viewBox="0 0 24 24">
                     <path fill="#808080"
                        d="M12 12q-1.65 0-2.825-1.175Q8 9.65 8 8q0-1.65 1.175-2.825Q10.35 4 12 4q1.65 0 2.825 1.175Q16 6.35 16 8q0 1.65-1.175 2.825Q13.65 12 12 12Zm-8 8v-2.8q0-.85.438-1.563q.437-.712 1.162-1.087q1.55-.775 3.15-1.163Q10.35 13 12 13t3.25.387q1.6.388 3.15 1.163q.725.375 1.162 1.087Q20 16.35 20 17.2V20Z" />
                  </svg>
               </i>
               <div class="form-group">
                                 <?php
                                    // fetch security question
                                    $sql = "SELECT security_question
                                            FROM user_security_tbl
                                            WHERE user_id = (SELECT user_id FROM user_tbl WHERE email = :email)
                                            LIMIT 1";
                                    
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bindParam(':email', $email);
                                    $stmt->execute();
                                    
                                    // Create HTML element to display security question
                                    if ($stmt->rowCount() > 0) {
                                        echo '<div class="d-flex justify-content-center">
                                                <label class="mb-2" for="securityPassword" style="font-size:14px;">Security Question</label>
                                            </div>';
                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                        echo  '<strong"><input class="form-control text-center col-12" style="font-weight:600;" type="text" name="security_question" value="' . $row["security_question"] . '" readonly></strong>';
                                    } else {
                                        echo "No security question found.";
                                    }
                                    
                                    ?>
                              </div>
               <div class="input-field shadow-lg mt-2">
                  <i class="fas fa-lock"></i>
                  <input class="" type="password" id="conPassword"  name="security_answer" id="security_answert" placeholder="Answer..." />
                  <button type="button" id="toggle-password" class="password-toggle-btn">
                  <i class="fas fa-eye-slash  "  id="toggleNewPasswordConfirm"></i>
                  </button>
               </div>
     
               <div class="mx-3">
                  <button type="submit" name="submit" id="btn-submit" style="background-color: rgb(22, 90, 178)" class="button-login btn fw-bold d-block rounded-3 mt-4" type="button">Submit</button>
               </div>
            </form>
         </div>
      </div>
      <div class="panels-container">
    <div class="panel left-panel d-flex justify-content-center align-items-center">
    <div class="back-arrow">
                    <a href="#" class="back-link"><i class="fas fa-arrow-circle-left"></i></a>
                </div>  
        <div class="content">
            <div class="row ms-0 ms-md-0 ms-lg-2">
                <div class="col-md-4 d-flex flex-column justify-content-center align-items-center">
                    <div class="logo-club d-flex flex-wrap me-3">
                        <!-- Add CSS styles to make the logo smaller and responsive -->
                        <img id="logo" class="img-fluid" class="me-3" src="assets/img/logo-1-img.png" alt=""
                            style="max-width: 150px; height: auto;">
                        <div class="vertical bg-white mx-3"></div>
                    </div>
                </div>
                <div class="col-md-6 ml-0 ml-md-3 ml-lg-3"
                    style="border-top-right-radius: 10px; border-bottom-right-radius: 10px;">
                    <div class="text-light d-flex justify-content-center align-items-center">
                        <strong class="clubName mt-4">
                            <h5 class="text-white" style="font-family: 'Opensans'; white-space: nowrap; font-size: 25px; font-weight: 600;">
                                Rotaract Club of Carmona
                                <p class="text-center"
                                    style="font-family: 'Opensans'; white-space: nowrap; font-size: 18px; font-weight: 400;">Finance Management System
                                </p>
                            </h5>
                        </strong>
                    </div>
                </div>
                <!-- Add the back circle arrow icon here -->
               
            </div>
        </div>
    </div>
</div>

      <script src="app.js"></script>
   </body>
   <script src="assets/js/script.js"></script>
   <script>
      $(document).ready(function () {
          $("#toggleNewPassword").click(function () {
           var icon = $("#toggleNewPassword");
          
              var passwordField = $("#password");
              if (passwordField.attr("type") === "password") {
                  passwordField.attr("type", "text");
                  icon.removeClass("fa-eye-slash").addClass("fa-eye");
              } else {
                  passwordField.attr("type", "password");
                  icon.removeClass("fa-eye").addClass("fa-eye-slash");
              }
          });
      
          $("#toggleNewPasswordConfirm").click(function () {
           var icon = $("#toggleNewPasswordConfirm");
            var confirmPasswordField = $("#conPassword");
              if (confirmPasswordField.attr("type") === "password") {
                  confirmPasswordField.attr("type", "text");
                  icon.removeClass("fa-eye-slash").addClass("fa-eye");
              } else {
                  confirmPasswordField.attr("type", "password");
                  icon.removeClass("fa-eye").addClass("fa-eye-slash");
              }
             
          });
      });
   </script>
  
   <script>
      $(document).ready(function() {
          // Attach a click event handler to the back button
          $("#backButton").click(function() {
              // Redirect to mail.php
              window.location.href = "enterEmail.php";
          });
      });
   </script>
      <script>
    $(document).ready(function() {
        // Attach a click event handler to the back button
        $(".back-arrow").click(function() {
            
            window.location.href = "enterEmail.php";
        });
    });
</script>
</html>