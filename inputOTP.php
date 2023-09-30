<?php 
session_start();
?>


   

<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
      <title>Landing</title>
      <!-- css -->
      <link rel="stylesheet" href="assets/css/responsiveEmail.css">
      <link rel="stylesheet" href="assets/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/css/style.css">
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

      <script src="assets/js/popper.min.js"></script>
      <script src="assets/js/bootstrap.min.js"></script>
      <script src="assets/js/moment.min.js"></script>
      <script src="assets/js/select2.min.js"></script>
      <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
      <script src="assets/js/script.js"></script>
      <link rel="stylesheet" href="assets/fontawesome-free-6.3.0-web/css/all.css">
      <script>
         document.addEventListener('DOMContentLoaded', function() {
             var passwordField = document.getElementById('securityPassword');
             var eyeIcon = document.getElementById('seepass');
         
             eyeIcon.addEventListener('mouseenter', function() {
               eyeIcon.dataset.password = passwordField.value;
               passwordField.type = 'text';
             });
         
             eyeIcon.addEventListener('mouseleave', function() {
               passwordField.type = 'password';
             });
           });
               
      </script>
   </head>
   <style>
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
         padding: 53px 2px 40px 2px;
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
      }.back-to-top{
        position:absolute;
        margin:10px;
        border-radius:50px;
        background-color:#11315d;
      }
   </style>
   <body>
      
      <!----------------------- Main Container -------------------------->
      <div class="container d-flex justify-content-center align-items-center min-vh-100">
   
         <!----------------------- Login Container -------------------------->
         <div class="row">
         <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top" style="z-index: 9" onclick="goBack()">
        <i class="fa fa-arrow-left"></i>
    </a>
            <!--------------------------- Left Box ----------------------------->
            <div class=" mt-3 mt-lg-0 mt-md-0 col-md-7 shadow-lg rounded-2 d-flex justify-content-center align-items-center flex-column left-box" style="background: #0d4287;">
               <div class="featured-image mb-3">
                  <div class="row-logo d-flex justify-content-center align-items-center">
                     <div class="col-md-4 d-flex flex-column justify-content-center align-items-center">
                        <div class="logo-club d-flex flex-wrao">
                           <!-- Add CSS styles to make the logo smaller and responsive -->
                           <!-- <img id="logo" class="img-fluid" src="assets/img/Rotary_logo.png" alt="" style="max-width: 125px; height:120px;"> -->
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
            <!-------------------- ------ Right Box ---------------------------->
            <div class="col-md-4 right-box bg-white mx-auto mb-5">
            <div class="notification col-12 w-100 p-3 d-flex justify-content-center ml-4 mt-4 mt-md-0 mt-lg-0">
                  <?php include 'template/notification/error.php'; ?>
                  <?php include 'template/notification/success.php'; ?>
               </div>
               <div class="d-flex justify-content-center">
                  <div class="header-text">
                     <h2 class="login mt-2 text-center mb-5"></h2>
                     <div class="d-flex justify-content-center">
                     <p class="text-center">Please check your email for the One Time Pin (OTP) and enter it below:</p>
                           
                        <hr class="bg-secondary w-100 position-absolute mt-2" style="top:40%;">
                     </div>
                  </div>
                  <!-- form -->
                  <!-- includes/user.inc.php -->
                  <!-- method post -->
               </div>
               <div class="mt-4">
                <form id="otpForm" action="process_otp.php" method="post">
                    <div class="form-group w-100 d-flex flex-column">
                        <div class="container-fluid">
                        </div>
                        <div class="container-fluid mt-5">
                            <div class="text-left">
                                <label for="otpInput">One-Time PIN (OTP)</label>
                                <input type="text" id="otpInput" name="otp" class="form-control w-100" required placeholder="Enter One-Time PIN">
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-5">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
            </div>
         </div>
      </div>
      </div>

      <!-- Modal for account recovery options -->
<div class="modal fade" id="accountRecoveryModal" tabindex="-1" role="dialog" aria-labelledby="accountRecoveryModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="accountRecoveryModalLabel">Account Recovery Options</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <p>Please choose your preferred account recovery option:</p>
            <button type="button" class="btn btn-primary btn-block" id="emailVerificationBtn">Email Verification</button>
            <button type="button" class="btn btn-secondary btn-block" id="securityQuestionBtn">Security Question</button>
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
   // When the Email Verification button is clicked
   $("#emailVerificationBtn").click(function() {
      // Make a POST request to the PHP file and pass the selected option as a parameter
      $.post("includes/emailOTP.php", { option: "emailVerification" }, function(response) {
         alert(response);
   
      });
   });

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


</html>