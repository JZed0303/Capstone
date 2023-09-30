<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>Dashboard</title>
      <meta content="width=device-width, initial-scale=1.0" name="viewport">
      <meta content="" name="keywords">
      <meta content="" name="description">
      <link rel="stylesheet" href="assets/fontawesome-free-6.3.0-web/css/all.css">
      <link rel="stylesheet" href="/assets/fontawesome-free-6.3.0-web/css/fontawesome.css">
      <link rel="stylesheet" href="assets/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/css/style.css">
      <script src="assets/js/jquery-ui.min.js"></script>
      <script src="assets/js/general/jquery.min.js"></script>
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
        font-family: 'OpenSans', sans-serif;
        background: #ececec;
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
    background: linear-gradient(to top, #a9a9a9, #ffffff) !important;
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
        width: 100%;
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

    /* Hover effect for notification error */
    .notification-error:hover {
        cursor: pointer;
    }

    /* Form control focus styles */
    .form-control:focus {
        border-color: #4f70e9 !important;
        box-shadow: 0 0 5px rgba(66, 76, 222, 0.5) !important;
        color: gray;
        font-weight: 600;
        background-color: white;
    }
</style>

   <body>
      <!----------------------- Main Container -------------------------->
      <div class="container d-flex justify-content-center align-items-center min-vh-100">
         <!----------------------- Login Container -------------------------->
         <div class="row  p-1 ">
            
            <!--------------------------- Left Box ----------------------------->
            <div class="col-md-8 shadow-lg d-flex justify-content-center align-items-center flex-column left-box" style="background: #0d4287; border-top-left-radius:10px;border-bottom-left-radius:10px;">

               <div class="featured-image mb-3">
                  <div class="row ms-2">
                     <div class="col-md-4 d-flex flex-column justify-content-center align-items-center">
                        <div class="logo-club d-flex flex-wrao">
                           <!-- Add CSS styles to make the logo smaller and responsive -->
                           <img id="logo" class="img-fluid"  src="assets/img/logo-1-img.png" alt="" style="max-width: 150px; height: auto;">
                           <div class="vertical bg-white mx-3"></div>
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
            <div class="col-md-4  right-box bg-white " style="background: linear-gradient(to bottom, #f2f2f2, #cbcaca);">
               <div class="row ms-1">
                  <div class="notification col-12 w-100 p-3 d-flex justify-content-center ml-2 mt-4 mt-md-0 mt-lg-0">
                     <?php include 'template/notification/error.php'; ?>
                     <?php include 'template/notification/success.php'; ?>
                  </div>
                  <div class="header-text mb-4">
                  
      
                     <h2 class="login mt-5 text-center">Login</h2>
       
                    
                     <div class="d-flex justify-content-center">
                        <i class="mx-auto">
                           <svg xmlns="http://www.w3.org/2000/svg" width="4em" height="4em" viewBox="0 0 24 24">
                              <path fill="#808080"
                                 d="M12 12q-1.65 0-2.825-1.175Q8 9.65 8 8q0-1.65 1.175-2.825Q10.35 4 12 4q1.65 0 2.825 1.175Q16 6.35 16 8q0 1.65-1.175 2.825Q13.65 12 12 12Zm-8 8v-2.8q0-.85.438-1.563q.437-.712 1.162-1.087q1.55-.775 3.15-1.163Q10.35 13 12 13t3.25.387q1.6.388 3.15 1.163q.725.375 1.162 1.087Q20 16.35 20 17.2V20Z" />
                           </svg>
                        </i>
                        <hr class="bg-secondary w-75 position-absolute" style="top:44%;">
                     </div>
                  </div>
                  <!-- form -->
                  <!-- includes/user.inc.php -->
                  <!-- method post -->
               </div>
               <div class="mt-4">
                  <form class="text-center" action="includes/user.inc.php" method="POST">
                     <div class="d-flex justify-content-center align-items-center mb-3"></div>
                     <div class="mb-3">
                        <input class="form-control shadow form-control-md " type="text" name="email" placeholder="Username/Email" autocomplete="off" />
                     </div>
                     <div class="mb-3">
                        <div class="input-group">
                           <input class="form-control shadow    form-control-md " type="password" name="password" placeholder="Password" />
                           <div class="input-group-append">
                              <span class="input-group-text">
                              <i class="fas fa-eye-slash toggle-password" id="togglePassword"></i>
                              </span>
                           </div>
                        </div>
                     </div>
                     <div class="mb-3">
                        <button id="btn-submit" style="background-color:rgb(22, 90, 178)" class="button-login btn fw-bold  d-block w-100 rounded-3 mt-4" type="submit" name="submit">Login</button>
                     </div>
                     <a href="enterEmail.php" class="forgot mt-2 text-decoration-none text-muted fw-bold">Forgot your password?</a>
                  </form>
               </div>
            </div>
         </div>
      </div>
      </div>
   </body>
   <script src="assets/js/script.js"></script>
   <script>
      $(document).ready(function() {
       var togglePass = ^ 
         $('.toggle-password').click(function() {
            $(this).toggleClass('fa-eye fa-eye');
            var input = $(this).closest('.input-group').find('input');
            if (input.attr('type') === 'password') {
               input.attr('type', 'text');
              intput.removeClass('fas fa-eye-slash');
              intput.addClass('fas fa-eye');
               
            } else {
               input.attr('type', 'password');
            }
         });
      
        //  // Toggle password visibility on eye icon hover
        //  $('.toggle-password').hover(function() {
        //     var input = $(this).closest('.input-group').find('input');
        //     input.attr('type', 'text');
        //  }, function() {
        //     var input = $(this).closest('.input-group').find('input');
        //     input.attr('type', 'password');
        //  });
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
          function triggerErrorAnimation() {
              // You can customize the error message here
              var errorMessage = 'Invalid credentials. Please try again.';
      
              // Set the error message content
              $('.error-message').html(errorMessage);
      
              // Show and slide down the error message
              showError();
      
              // After a certain delay (e.g., 5 seconds), hide the error message
              setTimeout(function () {
                  hideError();
              }, 5000); // 5000 milliseconds (5 seconds) - you can adjust the delay as per your requirement
          }
      
          // Example usage (you can trigger this animation after form submission in your PHP code)
          // For demonstration purposes, let's trigger the animation when the page loads after 2 seconds
          setTimeout(function () {
              triggerErrorAnimation();
          }, 2000); // 2000 milliseconds (2 seconds) - you can remove this line from your actual implementation
      });
   </script>
</html>