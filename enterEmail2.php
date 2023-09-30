<style>
   :root {
   --blue: #0C3C7C;
   --darkblue: #0D3266;
   --gold: #f5BE5A;
   --metalicgold: #d4af37;
   --azure: #005daa;
   --white: #ffffff;
   --dark: #000000;
   --button: #019fcb;
   --gray: #EDEEEF;
   --deam: #58585a;
   }
   .modal-content {
   border: none;
   border-radius: 20px;
   box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
   background-color: var(--gray);
   }
   /* Modal Header Styling */
   .modal-header {
   border-bottom: none;
   padding: 17px;
   background-color:var(--azure);
   color: var(--white);
   border-top-left-radius: 8px;
   border-bottom:var(--gold) 6px solid;
   border-top-right-radius: 8px;
   }
   /* Modal Body Styling */
   .modal-body {
   padding:12px 34px 10px 34px;
   }
   /* Input Field Styling */
   .form-group label {
   font-weight: 600;
   color: #4a4949;
   }
   .form-control {
   border: none;
   border-bottom: 1px solid var(--azure);
   border-radius: 0;    
   color: var(--dark);
   box-shadow: none;
   transition: border-color 0.3s ease;
   background-color: transparent;
   }
</style>
<?php
   session_start();
   
   
   // Maximum number of allowed attempts
   $maxAttempts = 5;
   
   // Wait time in seconds after the maximum number of attempts is reached
   $waitTime = 30;
   
   // Check if form is submitted
   if ($_SERVER['REQUEST_METHOD'] == 'POST') {
       // Check if the attempt counter is already set in session
       $_SESSION['attemptCount'] = ($_SESSION['attemptCount'] ?? 0) + 1;
   
       // Check if the maximum number of attempts has been reached
       if ($_SESSION['attemptCount'] > $maxAttempts) {
           // Check if the wait time has passed since the last attempt
           if ($_SESSION['lastAttemptTime'] && time() - $_SESSION['lastAttemptTime'] < $waitTime) {
               // Wait time has not passed, redirect to error page with wait message
               $remainingTime = $waitTime - (time() - $_SESSION['lastAttemptTime']);
               header("Location: ./index.php?error=Maximum number of attempts reached. Please try again in $remainingTime seconds.");
               exit();
           }
   
           // Wait time has passed, reset the attempt counter and maximum attempts
           $_SESSION['attemptCount'] = 1;
           $maxAttempts = 5;
           $_SESSION['lastAttemptTime'] = time();
   
           // Redirect to error page with unusual activity message
           header("Location: ./index.php?error=Unusual activity detected");
           exit();
       }
   
       // Retrieve email from form submission
       $email = $_POST['email'];
   
       // Include database connection and function files
       require_once 'includes/dbh.inc.php';
       require_once 'includes/function.inc.php';
   
       // Validate email against database
       $valid = accValidate($conn, $email);
   
    
      
   if ($valid) {
   // Email is valid, set session variables
   $_SESSION["forgot"] = true;
   $_SESSION["email"] = $email;
   
   // Echo a script tag with the email value in a JavaScript variable
   echo '<script>var emailValue = "' . $email . '";</script>';
   echo '<script>$("#accountRecoveryModal").modal("show");</script>';
   
   
   } else {
           // Email is invalid, redirect to enter email page with error message
           header("Location: enterEmail.php?error=Email Does not Exist");
           exit();
       }
   }
   
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>Dashboard</title>
      <meta content="width=device-width, initial-scale=1.0" name="viewport">
      <meta content="" name="keywords">
      <meta content="" name="description">
      <link rel="stylesheet" href="assets/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/css/style.css">
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <link rel="stylesheet" href="css/style.css">
      <script src="assets/js/popper.min.js"></script>
      <script src="assets/js/bootstrap.min.js"></script>
      <script src="assets/js/moment.min.js"></script>
      <script src="assets/js/select2.min.js"></script>
      <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
      <script src="assets/js/script.js"></script>
      <link rel="stylesheet" href="assets/fontawesome-free-6.3.0-web/css/all.css">
      
      <script>
         hideErrorMessage();     
         disableBackButton();
      </script>
      <script src="assets/js/script.js"></script>
      <!-- Favicon -->
      <!-- Google Web Fonts -->
      <link href="css/bootstrap.min.css" rel="stylesheet">
      <!-- general -->
      <title>Boostrap Login | Ludiflex</title>
   </head>
   <style>
      /* Your existing styles */
      body {
      font-family: 'OpenSans';
      background: #ececec;
      }
      /*------------ Login container ------------*/
      .box-area {
      max-width: 930px;
      width: 100%;
      }
      /*------------ Right box ------------*/
      .right-box {
      padding: 40px 30px 40px 40px;
      }
      /*------------ Custom Placeholder ------------*/
      ::placeholder {
      font-size: 14px;
      color: #a0a1a2!important;
      }
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
      }.container{
      width: 100%;
      padding-right: var(--bs-gutter-x, -1.25rem)!important;
      padding-left: var(--bs-gutter-x, -1.25rem)!important;
      margin-right: auto;
      margin-left: auto;
      }
      }
      /*------------ For small screens------------*/
      /*------------ For small screens------------*/
      @media only screen and (max-width: 768px) {
      .box-area {
      margin: 0 10px;
      } 
      .left-box {
      height: auto;
      overflow: hidden;
      border-top-right-radius:10px;
      border-bottom-left-radius:0px!important; 
      }
      .right-box {
      padding: 20px;
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
      }.img-fluid{
      display:none;
      }
      }
      .row-logo {
      display: flex;
      align-items: center;
      }
      .col-md-6 {
      display: flex;
      flex-direction: column;
      justify-content: center;
      }.row{
      width: 100%;
      }.vertical{
      width: 2px;
      }  /* Adjust the size of the image */
      #logo {
      max-width: 100%;
      height: auto;
      }
      /* Adjust the font size for tables */
      table {
      font-size: 14px;
      }
      .button-login{
      color:white;
      }.button-login:hover{
      background-color: #09418c!important;
      color:white;
      }
      .notification {
      width:100%;
      animation: fold_open 0.4s cubic-bezier(0.23, 1, 0.32, 1), slide_in 1s linear 1s forwards;
      }
      @keyframes fold_open {
      from {
      transform-origin: top center;
      transform: scaleY(0.01);
      }
      to {
      transform-origin: top center;
      transform: scaleY(1);
      }
      }
      .notification-error:hover {
      cursor: pointer;
      }.form-control:focus {
      border-color: #4f70e9 !important;
      box-shadow: 0 0 5px rgb(66, 76, 222) !important;
      color:gray;
      font-weight:600;
      background-color:white;
      } .btn-circle {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 20px;
      line-height: 0; /* Adjust line-height to vertically center the icon */
      }
   </style>
   <body>
      <div class="container d-flex justify-content-center align-items-center min-vh-100">
         <!----------------------- Login Container -------------------------->
         <div class="row  p-1 ">
            <!--------------------------- Left Box ----------------------------->
            <div class="col-md-8 shadow-lg d-flex justify-content-center align-items-center flex-column left-box" style="background: #0d4287; border-top-left-radius:10px;border-bottom-left-radius:10px;">
               <a href="#" class="btn btn-circle" id="backButton" style="position: absolute; top: 15px; left: 15px; background-color: #093164; color: white;">
               <i class="fas fa-arrow-left"></i>
               </a>  
               <div class="featured-image mb-3">
                  <div class="row ms-2">
                     <div class="col-md-4 d-flex flex-column justify-content-center align-items-center">
                        <div class="logo-club d-flex flex-wrao">
                           <!-- Add CSS styles to make the logo smaller and responsive -->
                           <img id="logo" class="img-fluid me-3"  src="assets/img/logoLogin.png" alt="" style="max-width: 150px; height: auto;">
                        </div>
                     </div>
                     <div class="col-md-6 ml-0 ml-md-3 ml-lg-3" style="border-top-right-radius:10px;border-bottom-right-radius:10px;">
                        <div class="text-light d-flex justify-content-center align-items-center">
                           <strong class="clubName mt-4">
                              <h5 class="text-white" style="font-family: 'Opensans'; white-space: nowrap; font-size:26px; font-weight:500;">
                              Rotaract Club of Carmona</p>
                              <p class="text-center"style="font-family: 'Opensans'; white-space: nowrap; font-size:18px; font-weight: 400;">Finance Management System
                              </h6>
                           </strong>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!-------------------- ------ Right Box ---------------------------->
            <div class="col-md-4  right-box bg-white pt-0 pt-md-5 pt-lg-5  " style="background: linear-gradient(to bottom, #f2f2f2, #cbcaca);">
               <div class="row ms-1">
                  <div class="notification col-12 w-100 p-3 d-flex justify-content-center ml-2 mt-4 mt-md-0 mt-lg-0">
                     <?php include 'template/notification/error.php'; ?>
                     <?php include 'template/notification/success.php'; ?>
                  </div>
                  <div class="d-flex justify-content-center mt-md-3 mt-lg-4" >
                     <i class="mx-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="4em" height="4em" viewBox="0 0 24 24">
                           <path fill="#808080"
                              d="M12 12q-1.65 0-2.825-1.175Q8 9.65 8 8q0-1.65 1.175-2.825Q10.35 4 12 4q1.65 0 2.825 1.175Q16 6.35 16 8q0 1.65-1.175 2.825Q13.65 12 12 12Zm-8 8v-2.8q0-.85.438-1.563q.437-.712 1.162-1.087q1.55-.775 3.15-1.163Q10.35 13 12 13t3.25.387q1.6.388 3.15 1.163q.725.375 1.162 1.087Q20 16.35 20 17.2V20Z" />
                        </svg>
                     </i>
                     <hr class="bg-secondary w-75 position-absolute" style="top:44%;">
                  </div>
                  <div class="header-text ">
                     <h2 class="login mt-5 text-center">Account recovery</h2>
                     <div class="d-flex justify-content-center">
                        <hr class="bg-secondary w-75 position-absolute" style="top:44%;">
                     </div>
                  </div>
                  <!-- form -->
                  <!-- includes/user.inc.php -->
                  <!-- method post -->
               </div>
               <div class="mt-4">
                  <form id="forgotPasswordForm w-100" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                     <div class="form-group w-100 d-flex flex-column">
                        <div class="container-fluid ">
                        </div>
                        <div class="container-fluid mt-5">
                           <div class="text-left">
                              <label for="securityPassword">Email</label>
                              <input type="text" id="email" name="email" class="form-control shadow w-100" required placeholder="Enter Your Email ">
                           </div>
                        </div>
                     </div>
                     <div class= "text-center mt-2">
                        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <!-- <div class="container d-flex justify-content-center align-items-center min-vh-100">
         <div class="row">
            <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top" style="z-index: 9" onclick="goBack()">
            <i class="fa fa-arrow-left"></i>
            </a>
            <div class=" mt-3 mt-lg-0 mt-md-0 col-md-7 shadow-lg rounded-2 d-flex justify-content-center align-items-center flex-column left-box" style="background: #0d4287;">
               <div class="featured-image mb-3">
                  <div class="row-logo d-flex justify-content-center align-items-center">
                     <div class="col-md-4 d-flex flex-column justify-content-center align-items-center">
                        <div class="logo-club d-flex flex-wrao">
                           <div class="vertical bg-white mx-3"></div>
                        </div>
                     </div>
                     <div class="col-md-6 ml-0 ml-md-3 ml-lg-3">
                        <div class="text-light d-flex justify-content-center align-items-center">
                           <strong class="clubName mt-4">
                              <h5 class="text-white" style="font-family: 'Opensans'; white-space: nowrap; font-size:26px; font-weight:500;">
                              Rotaract Club of Carmona</p>
                              <p class="text-center"style="font-family: 'Opensans'; white-space: nowrap; font-size:18px; font-weight: 400;">Finance Management System
                              </h6>
                           </strong>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-md-4 right-box bg-white mx-auto mb-5">
               <div class="notification col-12 w-100 p-3 d-flex justify-content-center ml-4 mt-4 mt-md-0 mt-lg-0">
                  <?php include 'template/notification/error.php'; ?>
                  <?php include 'template/notification/success.php'; ?>
               </div>
               <div class="d-flex justify-content-center">
                  <div class="header-text">
                     <h2 class="login mt-2 text-center mb-5"></h2>
                     <div class="d-flex justify-content-center">
                        <h4>Forgot Account</h4>
                        <hr class="bg-secondary w-75 position-absolute mt-2" style="top:40%;">
                     </div>
                  </div>
               </div>
               <div class="mt-4">
                
               </div>
            </div>
         </div>
         </div> -->
      </div>
      <div class="modal fade" id="accountRecoveryModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content mx-5">
               <div class="modal-header text-white">
                  <h5 class="modal-title" id="accountRecoveryModalLabel">Account Recovery Options</h5>
                  <button type="button" class="btn-close" aria-label="Close" onclick="clearSuccessUrlAndCloseModal()"></button>
               </div>
               <div class="modal-body">
                  <div id="errorContainer" style="display:none;text-align:center;">
                     <div class="alert alert-danger p-0" role="alert" id="errorMessage">
                        <!-- Error message will be displayed here -->
                     </div>
                  </div>
                  <form id="accountRecoveryForm" method="post" class="text-center" action="includes/otpProcess.php">
                     <input type="hidden" name="email" value="<?php echo $_POST['email']; ?>">
                     <p id="info">Please choose your preferred account recovery option:</p>
                     <button type="button" class="btn btn-primary" id="emailVerificationBtn">Email Verification</button>
                     <button type="button" class="btn btn-success" id="securityQuestionBtn">Security Question</button>
                     <div id="codeInput" style="display: none; mx-4">
                        <strong>
                           <p>Enter the 5 Digit code sent to your email:</p>
                        </strong>
                        <div class="form-group px-5">
                           <input type="text" id="verificationCode" name="code" class="form-control shadow form-control-sm text-center " placeholder="Enter the code..">
                        </div>
                        <div class="text-center">
                           <button type="button" class="btn btn-sm btn-primary" id="submitCodeBtn">Submit Code</button>
                        </div>
                     </div>
                  </form>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" onclick="clearSuccessUrlAndCloseModal()">Close</button>
               </div>
            </div>
         </div>
      </div>
   </body>
   <script>
      // JavaScript function to go back to the previous page
      function goBack() {
          window.history.back();
      }
   </script>
   <script>
      // When the Security Question button is clicked
      $("#securityQuestionBtn").click(function() {
         // Redirect to the forgotPass.php page with the selected option as a parameter
         window.location.href = "forgotPass.php?option=securityQuestion";
      });
   </script>
   <script>
      // Show the modal when the form is submitted
      $(document).ready(function() {
         <?php
         if (isset($_SESSION["forgot"]) && $_SESSION["forgot"] === true) {
            echo '$("#accountRecoveryModal").modal("show");';
            $_SESSION["forgot"] = false;
         }
         ?>
      });
   </script>
   <script>
      $(document).ready(function() {
          $("#emailVerificationBtn").click(function() {
              // Hide the Security Question button
              $("#emailVerificationBtn").hide();
              $("#securityQuestionBtn").hide();
              $("#info").hide();
              $("#codeInput").show();
      
              // Assuming you have the email from somewhere
              var email = "johnzedrickiglesia@gmail.com";
      
              // AJAX request to send email and generate a code on the server
              $.ajax({
                  type: "POST",
                  url: "includes/codeRecovery.php", // Replace with the actual path to your PHP script
                  data: { email: email },
                  success: function(response) {
                      // Handle the response from the server
                      alert(response);
                  }
              });
          });
      });
   </script>
   <script>
      $(document).ready(function() {
      $("#emailVerificationBtn").click(function() {
          // Hide the Security Question button
          $("#emailVerificationBtn").hide();
          $("#securityQuestionBtn").hide();
          $("#info").hide();
      
          // Show the code input field and submit button
          $("#codeInput").show();
      });
      });
   </script>
   <script>
      $(document).ready(function() {
          // Attach a click event handler to the back button
          $("#backButton").click(function() {
              // Redirect to mail.php
              window.location.href = "index.php";
          });
      });
   </script>
   <script>
      $(document).ready(function() {
          $("#submitCodeBtn").click(function() {
              var code = $("#verificationCode").val();
              var email = $("input[name='email']").val();
      
              $.ajax({
                  type: "POST",
                  url: "includes/otpProcess.php",
                  data: {
                      submit: true,
                      code: code,
                      email: email
                  },
                  success: function(response) {
                    $("#errorMessage").text(response);
                    $("#errorContainer").show();
                  },
                  error: function(xhr, status, error) {
                      // Handle errors here
                      console.error(error);
                  }
              });
          });
      });
   </script>
   <script>
      function clearSuccessUrlAndCloseModal() {
      
       $("#accountRecoveryModal").modal("hide");
      }
   </script>
</html>