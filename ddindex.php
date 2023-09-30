<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Landing</title>
      <!-- css -->
      <link rel="stylesheet" href="assets/fontawesome-free-6.3.0-web/css/all.css">
      <link rel="stylesheet" href="/assets/fontawesome-free-6.3.0-web/css/fontawesome.css">
      <link rel="stylesheet" href="assets/css/responsiveindex.css">
      <link rel="stylesheet" href="assets/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/css/style.css">
      <script src="assets/js/jquery-ui.min.js"></script>
      <script src="assets/js/globalFunctions.js"></script>
      <script src="assets/js/general/jquery.min.js"></script>
      <!-- js -->
      <script>
         hideErrorMessage();     
         disableBackButton();
      </script>
   </head>
<style>
   @media (max-width: 576px){
      .container {
    max-width: 645px;
}
   }

</style>
 

   <body>
      <div class="wrapper">
         <div class="container main min-vh-100">
            <div class="container-fluid ">
               <div class="row1 row mx-auto custom-width" style="width:100%">
                  <div class="first-col mt-md-0 mt-sm-4 mt-xsm-4  col-md-7 d-flex justify-content-center align-items-center side-image" style="background-color: #0d4287; border-bottom:solid 4px #f2a81b; white-space:nowrap;    background-color: #0d4287;
                     border-bottom: solid 4px #f2a81b;
                     white-space: nowrap;
                     border-top-left-radius: 10px;
                     border-bottom-left-radius: 10px;
                     ">
                     <!-- Image -->

                  <div class="logo-container">
                  <div class="row-logo d-flex justify-content-center align-items-center ">
                        <div class="col-md-6  d-flex flex-column justify-content-center align-items-center">
                           <div class="logo-club">
                              <img id="logo" class="img-fluid"  src="assets/img/Rotary_logo.png" alt="">
                             
                           </div>
                           <div class="vertical bg-white float-right" style="height: 154px;"></div>
                        </div>
                        <div class="col-md-6 ">
                           <div class="text-light  d-flex flex-column justify-content-center align-items-center">
                              <strong class="clubName">
                                 <h3 style="font-family: 'Open Sans';">Rotaract Club of Carmona</h3>
                              </strong>
                              <h6 class="text-center">Finance Management System</h6>
                           </div>
                        </div>
                     </div>


                  </div>

                  </div>
                  <div>
                  </div>
                  <div class="col-md-5 right shadow-none">
                     <div class="row d-flex mx-xs-5 mx-md-5 shadow-none ">
                     <div class=" col-12 pt-2 d-flex justify-content-center align-items-center " style="height: 80px;">
   <div class="w-100">
      <?php include 'template/notification/error.php'; ?>
      <?php include 'template/notification/success.php'; ?>
   </div>
</div>
                        <div class="login col-12 mb-5">
                        <div class="form-box p-0 card-body d-flex flex-column align-items-center justify-content-center rounded-pill">
                        <h1 class="login fw-bold mt-5">Login</h1>
                        <i>
                           <svg xmlns="http://www.w3.org/2000/svg" width="4em" height="3em" viewBox="0 0 24 24">
                              <path fill="#808080"
                                 d="M12 12q-1.65 0-2.825-1.175Q8 9.65 8 8q0-1.65 1.175-2.825Q10.35 4 12 4q1.65 0 2.825 1.175Q16 6.35 16 8q0 1.65-1.175 2.825Q13.65 12 12 12Zm-8 8v-2.8q0-.85.438-1.563q.437-.712 1.162-1.087q1.55-.775 3.15-1.163Q10.35 13 12 13t3.25.387q1.6.388 3.15 1.163q.725.375 1.162 1.087Q20 16.35 20 17.2V20Z" />
                           </svg>
                        </i>
                        <hr class=" bg-secondary w-75">
                        <div class="bs-icon-xl bs-icon-circle bs-icon-primary bs-icon">
                           <!-- form -->
                           <!-- includes/user.inc.php -->
                           <!-- method post -->
                           <form class="text-center" action="includes/user.inc.php" method="POST">
    <div class="d-flex justify-content-center align-items-center mb-3"></div>
    <div class="mb-3">
        <input class="form-control form-control-lg " type="text" name="email" placeholder="Username/Email" autocomplete="off" />
    </div>
    <div class="mb-3">
        <div class="input-group">
            <input class="form-control form-control-lg " type="password" name="password" placeholder="Password" />
            <div class="input-group-append">
                <span class="input-group-text">
                    <i class="fas fa-eye toggle-password"></i>
                </span>
            </div>
        </div>
    </div>
    <div class="mb-3">
        <button id="btn-submit" class="button-login btn btn-primary d-block w-100 rounded-3 mt-4" type="submit" name="submit">Login</button>
    </div>
    <a href="enterEmail.php" class="forgot mt-3 text-decoration-none text-muted fw-bold">Forgot your password?</a>
</form>

                        </div>
                     </div>
                        </div>
                     </div>
                
                  </div>
               </div>
            </div>
         </div>
      </div>
   </body>
   <!-- Custom JavaScript -->
   <script>
      $(document).ready(function() {
         // Toggle password visibility on eye icon click
         $('.toggle-password').click(function() {
            $(this).toggleClass('fa-eye fa-eye-slash');
            var input = $(this).closest('.input-group').find('input');
            if (input.attr('type') === 'password') {
               input.attr('type', 'text');
            } else {
               input.attr('type', 'password');
            }
         });
      
         // Toggle password visibility on eye icon hover
         $('.toggle-password').hover(function() {
            var input = $(this).closest('.input-group').find('input');
            input.attr('type', 'text');
         }, function() {
            var input = $(this).closest('.input-group').find('input');
            input.attr('type', 'password');
         });
      });
   </script>
</html>