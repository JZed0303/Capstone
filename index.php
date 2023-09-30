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
                    <form class="text-center" id="login-form" data-ajax="includes/user.inc.php" method="POST">
        <h4 class="title mb-3">Sign in</h4>
     
        <i class="mx-auto">
                           <svg xmlns="http://www.w3.org/2000/svg" width="4em" height="4em" viewBox="0 0 24 24">
                              <path fill="#808080"
                                 d="M12 12q-1.65 0-2.825-1.175Q8 9.65 8 8q0-1.65 1.175-2.825Q10.35 4 12 4q1.65 0 2.825 1.175Q16 6.35 16 8q0 1.65-1.175 2.825Q13.65 12 12 12Zm-8 8v-2.8q0-.85.438-1.563q.437-.712 1.162-1.087q1.55-.775 3.15-1.163Q10.35 13 12 13t3.25.387q1.6.388 3.15 1.163q.725.375 1.162 1.087Q20 16.35 20 17.2V20Z" />
                           </svg>
                  
        </i>    

        <div class="input-field shadow-lg">
        <i class="fas fa-user"></i>
        <input class="" type="text" name="email" placeholder="Username/Email" autocomplete="off" />
    </div>

    <div class="input-field shadow-lg">
        <i class="fas fa-lock"></i>
        <input class="" type="password" name="password" id="password-input" placeholder="Password" />
        <button type="button" id="toggle-password" class="password-toggle-btn">
            <i class="fas fa-eye-slash  "  id="togglePassword"></i>
        </button>
    </div>

    <div class="mx-3">
        <button id="btn-submit" style="background-color: rgb(22, 90, 178)" class="button-login btn fw-bold d-block rounded-3 mt-4" type="button">Login</button>
    </div>

    <a href="enterEmail.php" class="forgotpass social-text " style="color: #7c8288; cursor: pointer; text-decoration: none; font-size: 14px!important">Forgot Password</a>
</form>
          
        </div>
      </div>

      <div class="panels-container">
        <div class="panel left-panel d-flex jsutify-content-center align-items-center">
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
    
$(document).ready(function() {


    $("#login-form input").keypress(function(event) {
        if (event.which === 13) { // Check if Enter key is pressed
            event.preventDefault(); // Prevent form submission
            $("#btn-submit").click(); // Trigger button click event
        }
    });
    $("#btn-submit").on("click", function() {
   

            
        var form = $("#login-form");
        var formData = form.serialize();
        var url = form.data("ajax");
        
        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            success: function(response) {
                const msg = JSON.parse(response);
                var status = msg.status;
                var msgAlert =  msg.message;
                if (status === "success") {
    window.location.href = "dashboard.php";
} else {
                var errorMessage = msgAlert;
                var newURL = window.location.pathname + "?error=" + encodeURIComponent(errorMessage);
                // Use the History API to change the URL without reloading the page
                history.pushState({}, '', newURL);

                showErrorModal(msg.message);

                // Add a class to trigger the shake animation
                $('.notification').addClass('shake');

                // Remove the class after the animation duration (adjust as needed)
                setTimeout(function() {
                    $('.notification').removeClass('shake');
                }, 1000); // Adjust the duration as needed (1 second in this example)
            }
        },
            error: function(xhr, status, error) {
        
            }
        });
    });
});

function showErrorModal(errorMessage) {

            // Set the error message
            
            $("#error-message").text(errorMessage);
            
            // Show the error modal
            $('.notification').removeClass('d-none');
            $('.notification').addClass('shake');

            setTimeout(function() {
        $('.notification').addClass('d-none'); // Hide the error message
    }, 3000); // 3000 milliseconds = 3 seconds

        }

</script>


</html>