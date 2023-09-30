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
               header("Location: ./index.php?error-email=Maximum number of attempts reached. Please try again in $remainingTime seconds.");
               exit();
           }
   
           // Wait time has passed, reset the attempt counter and maximum attempts
           $_SESSION['attemptCount'] = 1;
           $maxAttempts = 5;
           $_SESSION['lastAttemptTime'] = time();
   
           // Redirect to error page with unusual activity message
           header("Location: ./index.php?error-email=Unusual activity detected");
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
           header("Location: enterEmail.php?error-email=Email Does not Exist");
           exit();
       }
   }
   
   ?>

<DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>Login</title>
      <meta content="width=device-width, initial-scale=1.0" name="viewport">
      <meta content="" name="keywords">
      <meta content="" name="description">
      <link rel="stylesheet" href="assets/fontawesome-free-6.3.0-web/css/all.css">
      <link rel="stylesheet" href="/assets/fontawesome-free-6.3.0-web/css/fontawesome.css">
      <link rel="stylesheet" href="assets/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/css/login.css">
      <link rel="stylesheet" href="assets/css/style.css">
      <link rel="stylesheet" href="assets/css/cashinflow/cashInflow.css">
      
      <script src="assets/js/jquery-ui.min.js"></script>
      <script src="assets/js/general/jquery.min.js"></script>
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    
   </head>
   <style>
    /* Your existing styles */
    body {
        font-family: 'OpenSans', sans-serif;
        background: white;
    }

    /*------------ Login container ------------*/
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
}p{
    padding: 10px;
    margin-top: 0;
    margin-bottom: 1rem;
    white-space: nowrap;
    font-weight: 700;
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
.getError{
    top: -410px!important;
    left: 53%;
    width: 100%;
    display: block;
    animation: errorEmail 0.5s ease-in-out 0.1s;

}



@keyframes errorEmail {
    0%, 100% {
        transform: translateX(0);
    }
    25%, 75% {
        transform: translateX(-10px);
    }
    50% {
        transform: translateX(10px);
    }
}@media (max-width: 870px) {
    .signin-signup,
  .container.sign-up-mode .signin-signup {
    top:81%;
    left: 50%;
  }

}@media (max-width: 420px) {
    .modal {
    position: fixed!important;
    top: -14px!important;
    padding: 9px;
    left: -8px;
}p{
    white-space:break-spaces;
}
}.back-arrow {

position: absolute;
top: 66px;
left: 21px;
z-index: 1;
font-size: 26px;

}.back-arrow:hover{
transform: scale(1.06)!important; /* Scale up the icon when hovering over the card-box */
}


</style>
<style>
    .alert-danger {
        background-color: #F2DEDE; /* Background color for error message */
        border-color: #EED3D7; /* Border color for error message */
    }

    .icon {
        font-size: 24px; /* Adjust the size of the times icon */
        color: #5CB85C; /* Color of the times icon (red) */
    }

    #errorMessage {
        font-size: 18px; /* Adjust the font size of the error message */
        color: #D9534F; /* Color of the error message (red) */
    }i.fas.fa-times-circle{
        color: #ff6464!important; /* Color of the error message (red) */
    }
</style>


<style>
    .alert-success {
        background-color: #DFF0D8; /* Background color for success message */
        border-color: #D6E9C6; /* Border color for success message */
    }

    .icon {
        font-size: 24px; /* Adjust the size of the checkmark icon */
        color: #5CB85C; /* Color of the checkmark icon (green) */
    }

    #successMessage {
        font-size: 18px; /* Adjust the font size of the success message */
        color: #5CB85C; /* Color of the success message (green) */
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
                  <form id="forgotPasswordForm w-100"  method="post">
        <h4 class="title mb-3">Account Recovery</h4>
     
        <i class="mx-auto">
                           <svg xmlns="http://www.w3.org/2000/svg" width="4em" height="4em" viewBox="0 0 24 24">
                              <path fill="#808080"
                                 d="M12 12q-1.65 0-2.825-1.175Q8 9.65 8 8q0-1.65 1.175-2.825Q10.35 4 12 4q1.65 0 2.825 1.175Q16 6.35 16 8q0 1.65-1.175 2.825Q13.65 12 12 12Zm-8 8v-2.8q0-.85.438-1.563q.437-.712 1.162-1.087q1.55-.775 3.15-1.163Q10.35 13 12 13t3.25.387q1.6.388 3.15 1.163q.725.375 1.162 1.087Q20 16.35 20 17.2V20Z" />
                           </svg>
                  
        </i>    

        <div class="input-field shadow-lg">
        <i class="fas fa-envelope"></i>
        <input class="" type="text" name="email" placeholder="Email" autocomplete="off" />
    </div>



    <div class="mx-3">
  
        <button id="btn-submit" type="submit" style="background-color: rgb(22, 90, 178)" class="button-login btn fw-bold d-block rounded-3 mt-4" type="button">Submit</button>
    </div>

</form>
          
        </div>
      </div>

      <div class="panels-container">
        <div class="panel left-panel d-flex jsutify-content-center align-items-center">
        <div class="back-arrow">
                    <a href="#" class="back-link"><i class="fas fa-arrow-circle-left"></i></a>
                </div>  
          <div class="content">
          <div class="row ms-0 ms-md-0 ms-lg-2">
                     <div class="col-md-4 d-flex flex-column justify-content-center align-items-center">
                        <div class="logo-club d-flex flex-wra me-3">
                           <!-- Add CSS styles to make the logo smaller and responsive -->
                           <img id="logo" class="img-fluid" class="me-3"  src="assets/img/logo-1-img.png" alt="" style="max-width: 150px; height: auto;">
                           <div class="vertical bg-white mx-3"></div>
                        </div>
                     </div>
                     <div class="col-md-6 ml-0 ml-md-3 ml-lg-3" style="border-top-right-radius:10px;border-bottom-right-radius:10px;">
                        <div class="text-light d-flex justify-content-center align-items-center">
                           <strong class="clubName mt-4">
                              <h5 class="text-white" style="font-family: 'Opensans'; white-space: nowrap; font-size:25px; font-weight:600;">
                              Rotaract Club of Carmona
                              

                              <p class="text-center"style="font-family: 'Opensans'; white-space: nowrap; font-size:18px; font-weight: 400;">Finance Management System
                              </h6>
                           </strong>
                        </div>
                     </div>
                  </div>
        </div>
       
      </div>
    </div>

    <script src="app.js"></script>

    <div class="modal fade" id="accountRecoveryModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content mx-2 mx-md-5">
            <div class="modal-header text-white">
                <h5 class="modal-title text-center mx-auto"   id="accountRecoveryModalLabel">Account Recognize !</h5>
                <button type="button" class="btn-close" aria-label="Close" onclick="clearSuccessUrlAndCloseModal()"></button>
            </div>
            <div class="modal-body">
            <div id="successContainer" style="display: none; text-align: center;">
    <div class="alert alert-success p-2" role="alert">
        <span class="icon"><i class="fas fa-check-circle"></i></span> <!-- Checkmark icon from Font Awesome -->
        <span id="successMessage" class="ml-2"></span> <!-- Success message -->
    </div>
</div>


<div id="errorContainer" style="display: none; text-align: center;">
    <div class="alert alert-danger p-2" role="alert">
        <span class="icon"><i class="fas fa-times-circle"></i></span> <!-- Times icon in a circle -->
        <span id="errorMessage" class="ml-2"></span> <!-- Error message -->
    </div>
</div>

<div id="spinnerContainer" style="display: none; text-align: center;">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

                <form id="accountRecoveryForm" method="post" id="verificationCodeForm"class="text-center" action="">
                    
                        <div id="container-error" class="alert alert-danger m-0 p-4 d-none rounded-3"><strong> Please check you Internet Connectivity!</strong></div>
                         <input type="hidden" name="email" id="emailInput" value="<?php echo $_POST['email']; ?>">
                    <p id="info fw-bolder" class="information">Please choose your preferred account recovery option:</p>
                    <button type="button" class="btn btn-primary" id="emailVerificationBtn">Email Verification</button>
                    <button type="button" class="btn btn-success" id="securityQuestionBtn">Security Question</button>
                    <div id="codeInput" style="display: none; mx-4">
                        <strong>
                            <p>Enter the 5 Digit code sent to your email:</p>
                        </strong>
                        <div class="form-group px-4 px-md-5">
                            <input type="text" id="verificationCode" name="code" class="form-control shadow form-control-sm text-center" placeholder="Enter the code..">
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
   <script src="assets/js/script.js"></script>
   <script>
$(document).ready(function() {
    var togglePass = $('#togglePassword');

    $('#toggle-password').click(function() {
        var input = $('#password-input');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            togglePass.removeClass("fa-eye-slash").addClass("fa-eye");
        } else {
            input.attr('type', 'password');
            togglePass.removeClass("fa-eye").addClass("fa-eye-slash");
        }
    });
});

   </script>
   <script>
      $(document).ready(function () {
          // Toggle password visibility on eye icon click
          $('.toggle-password').click(function () {
              // Your existing toggle password code
          });
      
          // Toggle password visibility on eye icon hover
          $('.toggle-password').hover(function () {
              // Your existing toggle password code
          }, function () {
              // Your existing toggle password code
          });
      
          // Show the error message and slide it down when needed
          function showError() {
              $('.error-message').slideDown();
          }
      
          // Hide the error message when needed
          function hideError() {
              $('.error-message').slideUp();
          }
      
          // Function to trigger the error animation (you can call this function whenever you want to show the error message)

      
          // Example usage (you can trigger this animation after form submission in your PHP code)
          // For demonstration purposes, let's trigger the animation when the page loads after 2 seconds
      
      });

   </script>
 
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
        $(".information").hide();
        $("#codeInput").show();
        
        

        // Assuming you have the email from somewhere
        var email = "<?php echo $_POST['email']?>";

        // AJAX request to send email and generate a code on the server
        $.ajax({
            type: "POST",
            url: "includes/codeRecovery.php", // Replace with the actual path to your PHP script
            data: { email: email },
            success: function (response) {
        if (response.status === 'success') {
            // Hide the spinner and display the success message
            $("#spinnerContainer").hide();
            $("#successMessage").html(response.message);
            $("#successContainer").slideDown(); // Slide down the success message container
            setTimeout(function () {
                $("#successContainer").slideUp(); // Slide up the success message container after a delay
            }, 5000); // Adjust the delay (in milliseconds) as needed
        } else {
            alert("ff");
            $("#spinnerContainer").hide();
            $("#codeInput").hide();
            $("#container-error").removeClass('d-none');
          
            // setTimeout(function () {
            //     $("#container-error")hide(); // Slide up the success message container after a delay
            // }, 3000);


        }
    },
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
          $("").hide();
      
          // Show the code input field and submit button
          $("#codeInput").show();
          $("#spinnerContainer").show();
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

        
        $("#accountRecoveryForm").keypress(function(event) {
        if (event.which === 13) { // Check if Enter key is pressed
            event.preventDefault(); // Prevent form submission
            $("#submitCodeBtn").click(); // Trigger button click event
        }
    });
          $("#submitCodeBtn").click(function() {
              var code = $("#verificationCode").val();
              var email ="<?php echo $_POST['email']?>";
  
              $.ajax({
                  type: "POST",
                  url: "includes/otpProcess.php",
                  data: {
                      submit: true,
                      code: code,
                      email: email
                  },
                  success: function(response) {
                 
                   if(response.status !== "error"){
                    
              window.location.href = "resetPassword.php";
                   }else{
                            $("#errorMessage").text(response.message);
                    $("#errorContainer").show();

                    setTimeout(function() {
             
             $('#errorContainer').css('display', 'none');
                 }, 2000);
                   }
            
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
     <script>
    $(document).ready(function() {
        // Attach a click event handler to the back button
        $(".back-arrow").click(function() {
            // Redirect to mail.php
            window.location.href = "index.php";
        });
    });
</script>
    



</html>