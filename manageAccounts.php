<?php
   session_start();
   if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
      // Redirect the user to the login page
      header('Location: index.php');
      exit();
    }else{
   
      require 'includes/dbh.inc.php';
      $email = $_SESSION['email'];
      $role = $_SESSION['role'];
      $id = $_SESSION['id'];
      
      // Output the value of $role in a script tag
      echo "<script>var role = '" . $role . "';</script>";
      
      // Retrieve data from the database
      $query = "SELECT user_id, name, email, role, password FROM user_tbl";
      $stmt = $conn->query($query);
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
      
      // Use the $result array as needed
      // ...
   
    }
    
     
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>Manage Accounts </title>
      <meta content="width=device-width, initial-scale=1.0" name="viewport">
      <meta content="" name="keywords">
      <meta content="" name="description">
      <!-- css content -->
      <link rel="stylesheet" href="assets/fontawesome-free-6.3.0-web/css/all.css">
      <link rel="stylesheet" href="assets/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/css/budgetManagement.css">
      <!-- DATA TABLES  cs-->
      <!-- <link rel="stylesheet" href="assets/datatable/css/datatables.min.css"> -->
      <!-- <link rel="stylesheet" href="assets/datatable/css/dataTables.dateTime.min.css"> -->
      <link rel="stylesheet" href="assets/datatable/css/bootstrap.min.css">
      <script src="assets/datatable/js/jquery-3.6.0.min.js"></script>
      <link rel="stylesheet" href="assets/css/bootstrap.min.css.map">
      <link rel="stylesheet" href="assets/datatable/css/jquery.dataTables.min.css">
      <link rel="stylesheet" href="assets/css/manageAccounts.css">
      <link rel="stylesheet" href="assets/datatable/js/cdn.datatables.net_buttons_2.4.1_css_buttons.dataTables.min.css">
      <!-- media Print -->
      <!-- <link rel="stylesheet" href="assets/css/mediaPrintIncome.css"> -->
      <!-- DATA TABLE JS -->
      <!-- <script src="assets/datatable/css/bootstrap.bundle.min.js"></script> -->
      <script src="assets/datatable/js/datatables.min.js"></script>   
      <!-- <script src="assets/datatable/js/vfs_fonts.js"></script> -->
      <!-- DATA TABLES DATE FILTER -->
      <script src="assets/js/bootstrap.min.js"></script>
      <script src="assets/datatable/js/dataTables.dateTime.min.js"></script>
      <script src="assets/datatable/js/manageAccounts.js"></script>
      <script src="assets/js/script.js"></script>
      <!-- Google Web Fonts -->
      <link href="css/bootstrap.min.css" rel="stylesheet">
      <link href="css/style.css" rel="stylesheet">
      <!-- general -->
   </head>
   <style>
      .modal-content {
      border: none;
      border-radius: 8px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      color:#1c1a1a;
      }
      /* Modal Header Styling */
      .modal-header {
      border-bottom: none;
      padding: 20px;
      background-color: var(--azure);
      color: var(--white);
      border-top-left-radius: 8px;
      border-bottom: var(--gold) 6px solid;
      border-top-right-radius: 8px;
      }
      /* Modal Body Styling */
      .modal-body {
      padding: 30px;
      }
      /* Input Field Styling */
      .form-group label {
      font-weight: 600;
      color: var(--dark);
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
      .form-control:focus {
      outline: none;
      border-color: var(--gold);
      }
      .invalid-feedback {
      color: var(--deam);
      }
      /* Button Styling */
      .btn-primary {
      background-color: var(--button);
      border: none;
      }
      .btn-primary:hover,
      .btn-primary:focus {
      background-color: var(--darkblue);
      outline: none;
      box-shadow: none;
      }
      .float-right {
      float: right;
      }
      .custom-file {
      position: relative;
      display: inline-block;
      width: 100%;
      }
      .custom-file-input {
      position: relative;
      z-index: 2;
      width: 100%;
      height: calc(1.5em + 0.75rem + 2px);
      opacity: 0;
      }
      .custom-file-label {
      position: absolute;
      top: 0;
      right: 0;
      left: 0;
      z-index: 1;
      padding: 0.375rem 0.75rem;
      font-weight: 400;
      line-height: 1.5;
      color: #6c757d;
      background-color: #fff;
      border: 1px solid #ced4da;
      border-radius: 0.25rem;
      }
      .custom-file-label::after {
      content: "Browse";
      }
      .custom-file-input:focus~.custom-file-label {
      border-color: var(--gold);
      box-shadow: 0 0 0 0.2rem rgba(245, 190, 90, 0.25);
      }th.sorting{
      width:500px!important;
      }
      .container-fluid.wrapper{
      position: relative;
      top: 4%;
      }.filter{
      font-size:14px!important;
      }
      /* Define your custom scrollbar styles for vertical scrollbar */
      /* Webkit-based browsers */
      ::-webkit-scrollbar {
      width: 4px;
      }
      ::-webkit-scrollbar-thumb {
      background-color: var(--darkblue);
      border-radius: 3px;
      }
      ::-webkit-scrollbar-thumb:hover {
      background-color: var(--azure);
      }
      ::-webkit-scrollbar-horizontal {
      height: 6px; /* Adjust the height to make it thin */
      }
      ::-webkit-scrollbar-thumb:horizontal {
      background-color: gray;
      border-radius: 3px;
      }
      ::-webkit-scrollbar-thumb:horizontal:hover {
      background-color: var(--darkblue);
      }
      body {
      margin: 0;
      padding: 0;
      font-family: 'Open Sans', Arial, sans-serif!important;
      background-color:var(--white);
      color: white;
      }.dataTables_length label{
      color:black!important;
      }#dataTablesIncome{
      padding: 13px 11px 0px 16px!important;
      }.dataTables_wrapper .dataTables_filter input{
      display:none;
      }input:focus {
      background-color: white!important; /* Change to your desired background color */
      }
   </style>
   <style>
      th.sorting{
      width: 500px!important;
      }body{
      color:black;
      } th[data-cell="nameMember"] {
      width: 100px!important;
      }.progress{
      background:#b2c9f3!important;
      }.progress-bar{
      background:#0e4591!important;
      }
      .no-vertical-borders {
      border-collapse: collapse;
      }
      .no-vertical-borders th,
      .no-vertical-borders td {
      border-left: none !important;
      border-right: none !important;
      }th.sorting.sorting_asc{
      width:10px!important;
      }
   </style>
   <style>
      /* Custom CSS to adjust modal width */
      .modal-dialog.add, .edit {
      max-width: 800px; /* Set the maximum width for the modal */
      }
   </style>
   <style>
      .d-flex button {
      margin-right: 10px; /* Add some spacing between buttons */
      } .requirements {
      font-size: 14px; /* Set the font size to make it smaller */
      color: gray; /* Set the text color to gray */
      }table{
      border:none!important;
      border-bottom:10px solid blue;
      }td{
      border:none!important;
      border-bottom:0.1px solid rgb(171, 181, 243)!important;
      }
   </style>
   <!-- START BODY -->
   <body>
      <div class="container-fluid position-relative d-flex p-0">
         <!-- Spinner Start -->
         <div id="spinner" class="show bg-primary position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-white" style="width: 3rem; height: 3rem;" role="status">
               <span class="sr-only">Loading...</span>
            </div>
         </div>
         <!-- Spinner End -->
         <!-- Sidebar Start -->
         <?php 
            include 'template/all_sidebar.php';
            
            ?>
         <!-- Sidebar End -->
         <!-- Content Start -->
         <div class="content">
            <!-- Navbar Start -->
            <?php include 'template/navbar.php'?>
            <?php      include 'template/modal.php'?>
            <!-- Navbar End -->
            <!-- Sale & Revenue Start -->
            <div class="container-fluid pt-4 px-md-3 px-lg-4 px-3 ">
               <div class="filter">
                  <div class=" row  mt-1">
                     <span class="px-4 pt-2"><strong><i class="fa-solid fa-filter me-1"></i>Filter</strong></span>
                  </div>
                  <div class=" row g-3 mx-1 mx-md-3 mx-lg-4 p-md-3 p-lg-3 p-2 ">
                     <div class="col-sm-12 col-md-6 col-xl-4 d-flex justify-content-center">
                        <div class="categ col-lg-12 col-sm-10 d-flex flex-column mx-sm-3">
                           <span><strong class="">Category</strong></span>
                           <div class="form-group row">
                              <select class="category-box col-sm-12 col-md-10 form-control form-control-sm">
                                 <option selected >Select Category</option>
                                 <option>Admin</option>
                                 <option>Auditor</option>
                                 <option>Treasurer</option>
                                 <option>Guest</option>
                              </select>
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-12 col-xl-8">
                        <span  class=""><strong class="">Search</strong></span>
                        <div class="form-group row mx-0 ">
                           <div class="col-12">
                              <div class="row">
                                 <div class="col-sm-9 col-8 ">
                                    <input type="text" class="form-control form-control-sm" autocomplete="off" placeholder="Search Keyword" id="search-input">                          
                                 </div>
                                 <div class="col-sm-3 col-4">
                                    <button type="button" class="filter-btn btn-primary border-0 btn-sm d-flex   w-auto m-0 " id="reset-btn"><i class="mt-1 mr-2  fa fa-refresh"></i><span>Reset</span></button>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <!-- end filter row -->
                  </div>
               </div>
            </div>
            <hr class="mx-5">
            <div class="container-fluid wrapper">
               <div class="row g-2 mx-1 mb-3">
                  <div class="col-12 rounded-3 px-md-2">
                     <div class="dataTable_wrapper income col-12 pt-3 px-0 pb-0">
                        <div class="bg-white rounded h-100  mb-0 pb-0  " >
                           <div class="table-responsive shadow-lg mt-4">
                              <table id="dataTablesIncome" class="table bg-white">
                                 <thead class="">
                                    <tr class="">
                                       <th style ="display:none;" id="">ID</th>
                                       <th>Name</th>
                                       <th>Email</th>
                                       <th>Role</th>
                                       <th id="" style="width:30px!important;">Action</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php 
                                       $increment = 1;
                                       foreach ($result as $row) {
                                            
                                               ?>
                                    <tr>
                                       <td style ="display:none;background-color:red;"><?php echo $row['user_id'] ?></td>
                                       <td><?php echo $row['name']; ?></td>
                                       <td><?php echo $row['email']; ?></td>
                                       <td><?php echo $row['role']; ?></td>
                                       <td>
                                          <div class="d-flex d-flex justify-content-center">
                                             <a  class="btn btn-sm btn-success px-3   mx-2 btn-sm border-0 pt-1 m-0 mt-0 edit-account-btn"
                                                data-toggle="modal" data-target="#editAccountModal"
                                                data-user-id="<?php echo $row['user_id']; ?>"
                                                data-user-name="<?php echo $row['name']; ?>"
                                                data-user-email="<?php echo $row['email']; ?>"
                                                data-user-role="<?php echo $row['role']; ?>"
                                                >
                                             <i class="fa fa-pencil"></i> 
                                             </a>
                                             <a  class="btn btn-sm btn-danger border-0 px-3 pt-1 m-0 deleteConfirmationModal"      data-user-id="<?php echo $row['user_id']?>"   onclick="setDeleteId(this)" data-toggle="modal" data-target="#deleteConfirmationModal">                    <i class="fa fa-trash"></i> 
                                             </a>
                                          </div>
                                       </td>
                                    </tr>
                                    <?php $increment++;?>
                                    <?php } ?>     
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!-- Container End -->
         </div>
         <!--Modals Start -->
         <!-- edit modal Start-->
         <div class="modal fade p-3 p-lg-1 p-md-1" id="addAccountModal" tabindex="-1" role="dialog" aria-labelledby="addAccountModalLabel" aria-hidden="true">
            <div class="modal-dialog add modal-dialog-centered modal-lg" role="document">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="addIncomeModalLabel">Add New Account</h5>
                     <button type="button" class="close" id="closeModal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
                  <div class="modal-body">
                     <form action="CRUD/create/addAccount.php" method="POST">
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label for="name">Name:</label>
                                 <input type="text" class="form-control" id="name" name="name" placeholder="Enter the name...">
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label for="email">Email:</label>
                                 <input type="email" class="form-control" id="email" name="email" placeholder="Enter the email...">
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label for="category">Role:</label>
                                 <select class="form-control" id="role" name="role">
                                    <option value="">Select a Role...</option>
                                    <!-- Add Roles -->
                                    <option value="admin">admin</option>
                                    <option value="auditor">auditor</option>
                                    <option value="treasurer">treasurer</option>
                                    <option value="guest">guest</option>
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label for="security_question">Security Question:</label>
                                 <select class="form-control" id="security_question" name="security_question">
                                    <option value="">Select a security question...</option>
                                    <option value="What is your mother's maiden name?">What is your mother's maiden name?</option>
                                    <option value="What is your favorite color?">What is your favorite color?</option>
                                    <option value="What city were you born in?">What city were you born in?</option>
                                    <option value="Other">Other</option>
                                 </select>
                              </div>
                           </div>
                           <div class="form-group d-none" id="security_question_other">
                              <label for="security_question_custom">Custom Security Question:</label>
                              <input type="text" class="form-control" id="security_question_custom" name="security_question_custom" placeholder="Enter a custom security question...">
                           </div>
                           <div class="form-group">
                              <label for="security_answer">Security Answer:</label>
                              <input type="text" class="form-control" id="security_answer" name="security_answer" placeholder="Enter a security answer...">
                           </div>
                           <div class="form-group">
                              <label for="password">Password:</label>
                              <div class="input-group">
                                 <input type="password" class="form-control" id="passwordCreate" name="password" placeholder="Enter a password...">
                                 <div class="input-group-append">
                                    <div class="input-group-text">
                                       <i class="fas fa-eye-slash" id="togglePassword"></i>
                                    </div>
                                 </div>
                              </div>
                              <div id="passwordRequirementsCreate" style="font-size: 14px;color: #555868;" class="requirements"></div>
                              <div class="form-group mt-3">
                                 <label for="confirmpassword">Confirm Password:</label>
                                 <div class="input-group">
                                    <input type="password" class="form-control" id="confirmpasswordCreate" name="confirmpassword" placeholder="Confirm password...">
                                    <div class="input-group-append">
                                       <span class="input-group-text">
                                       <i class="fas fa-eye-slash" id="toggleConfirmPassword"></i>
                                       </span>
                                    </div>
                                 </div>
                                 <div id="confirmpasswordRequirementsCreate" style="font-size: 14px;color: #555868;" class="requirements"></div>
                              </div>
                           </div>
                        </div>
                        <div class="form-group text-right">
                           <button type="button" id="closeAddAccountModal" class="btn btn-secondary">Cancel</button>
                           <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
         <!-- Edit Modal -->
         <div class="modal fade p-3 p-lg-1 p-md-1  " id="editAccountModal" tabindex="-1" role="dialog" aria-labelledby="editAccountModalLabel" aria-hidden="true">
            <div class="modal-dialog edit px-5 modal-dialog-centered" role="document">
               <div class="modal-content" >
                  <div class="modal-header">
                     <h5 style="color:white;" class="modal-title" id="editAccountModalLabel">Update Account</h5>
                     <span class="edited-email"></span>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
                  <div class="modal-body" >
                     <form action="CRUD/update/updateAccount.php" id="updateAccount" method="POST">
                        <input type="hidden" name="id" id="id" value="">
                        <div class="row">
                           <!-- First Column -->
                           <div class="col-md-12">
                              <div class="form-group">
                                 <label for="name">Name:</label>
                                 <input type="text" class="form-control" id="nameAccount" name="name" value="">
                              </div>
                              <div class="form-group">
                                 <label for="email">Email:</label>
                                 <input type="email" class="form-control" id="emailAccount" name="email" placeholder="">
                              </div>
                              <div class="form-group">
                                 <label for="role">Role:</label>
                                 <select class="form-control" id="roleAccount" name="role" required>
                                    <option value="">Select a role...</option>
                                    <option value="admin">admin</option>
                                    <option value="auditor">Auditor</option>
                                    <option value="treasurer">Treasurer</option>
                                    <option value="guest">Guest</option>
                                 </select>
                              </div>
                           </div>
                           <div clas="form-group d-flex justify-content-end">
                              <button type="button" class="btn btn-primary" id="changePasswordButton">Change Password</button>
                              <!-- Change Password Modal -->
                              <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
                                 <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="col-12 m-5 p-3 col "style="background-color: rgba(13, 66, 135, 0.5) !important;">
                                       <div class="modal-content">
                                          <div class="modal-header">
                                             <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                                             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                             <span aria-hidden="true">&times;</span>
                                             </button>
                                          </div>
                                          <div class="modal-body">
                                             <!-- Password change form -->
                                             <div class="form-group">
                                                <label for="passwordAccount">New Password:</label>
                                                <div class="input-group">
                                                   <input type="password" class="form-control" id="newpasswordAccount" name="newpassword" placeholder="">
                                                   <div class="input-group-append">
                                                      <span class="input-group-text">
                                                      <i class="fas fa-eye-slash" id="toggleNewPassword"></i>
                                                      </span>
                                                   </div>
                                                </div>
                                                <div id="passwordRequirements" style="font-size: 14px;color: #555868;" class="requirements"></div>
                                             </div>
                                             <div class="form-group">
                                                <label for="confirmpasswordAccount">Confirm Password:</label>
                                                <div class="input-group">
                                                   <input type="password" class="form-control" id="newconfirmpasswordAccount" name="confirmpassword" placeholder="">
                                                   <div class="input-group-append">
                                                      <span class="input-group-text">
                                                      <i class="fas fa-eye-slash" id="togglenewPasswordConfirm"></i>
                                                      </span>
                                                   </div>
                                                </div>
                                                <div id="confirmpasswordRequirements" style="font-size: 14px;color: #555868;" class="requirements"></div>
                                             </div>
                                          </div>
                                          <div class="modal-footer">
                                             <button type="button" class="btn btn-secondary" id="closePasswordChange" >Close</button>
                                             <button type="submit" class="btn btn-primary"  name="update" id="savePasswordChanges">Save Changes</button>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <!-- Second Column -->
                        </div>
                        <div class="modal-footer">
                           <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                           <button type="submit" name="update" class="btn btn-primary">Save changes</button>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
         <!--edit modal end -->
         <!-- END ADD -->
         <div class="modal fade p-3 p-lg-1 -md-1" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="deleteConfirmationModalLabel">Delete Receipt</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
                  <div class="modal-body">
                     <!-- Show the ID and Filename in the modal body -->
                     <p>Filename: <span id="modalFilename"></span></p>
                     <p>Are you sure you want to delete this Account?</p>
                  </div>
                  <div class="modal-footer" >
                     <form action="CRUD/delete/deleteAccount.php" method="POST">
                        <input type="hidden" id="deleteMemberId" name="delete_id" value>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="deleteAccount" class="btn btn-danger" >Delete</button>
                     </form>
                  </div>
               </div>
            </div>
         </div>
         <!-- Modal for Delete Confirmation End -->
         <!-- Content End -->
         <!-- Back to Top -->
         <!-- <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top" style="z-index:9"><i class="fa fa-arrow-up"></i></a> -->
      </div>
      <!-- charts -->
   </body>
   <!-- END BODY -->
   <!-- Template Javascript -->
   <script src="js/main.js"></script>
   <script src="js/cdn.jsdelivr.net_npm_bootstrap@5.0.0_dist_js_bootstrap.bundle.min.js"></script>
   <!-- END BODY -->
   <!-- Template Javascript -->
   <script src="assets/js/general/sidebar.js"></script>
   <script src="assets/js/managementAccounts/manageAccounts.js"></script>
   <script>
      function setDeleteId(button) {
              var userId = button.getAttribute("data-user-id"); // Get the user_id from the button
              document.getElementById("deleteMemberId").value = userId; // Set it in the existing input field
          }
          $(document).ready(function () {
              // Add click event listener to the "Edit" buttons
              $(".edit-account-btn").click(function () {
      
              
                  // Retrieve data attributes from the clicked button
                  var userId = $(this).data("user-id");
                  var userName = $(this).data("user-name");
                  var userEmail = $(this).data("user-email");
                  var userRole = $(this).data("user-role");
              
                  // Set the values of the modal input fields
                  $("#id").val(userId);
                  $("#nameAccount").val(userName);
                  $("#emailAccount").val(userEmail);
                  $("#roleAccount").val(userRole);
              });
          });
   </script>
   <script>
      // Function to show the "Change Password" modal when the button is clicked
      document.getElementById('changePasswordButton').addEventListener('click', function () {
          $('#changePasswordModal').modal('show');
          document.getElementById('editModal').style.opacity="0.5";
      });
      
      // Function to handle password change form submission
      
      document.getElementById('closePasswordChange').addEventListener('click', function () {
          $('#changePasswordModal').modal('hide');
          
      });
   </script>
   <script>
      $(document).ready(function () {
          $("#toggleNewPassword").click(function () {
              var passwordField = $("#newpasswordAccount");
              if (passwordField.attr("type") === "password") {
                  passwordField.attr("type", "text");
              } else {
                  passwordField.attr("type", "password");
              }
          });
      
          $("#togglenewPasswordConfirm").click(function () {
            var confirmPasswordField = $("#newconfirmpasswordAccount");
              if (confirmPasswordField.attr("type") === "password") {
                  confirmPasswordField.attr("type", "text");
              } else {
                  confirmPasswordField.attr("type", "password");
              }
             
          });
      });
   </script>
   <script>
      const newPasswordAccount = document.getElementById('newpasswordAccount');
      const newConfirmPasswordAccount = document.getElementById('newconfirmpasswordAccount');
      const updateAccountForm = document.getElementById('updateAccount'); // Replace 'myForm' with the actual form ID.
      
      newPasswordAccount.addEventListener('input', validatePassword);
      newConfirmPasswordAccount.addEventListener('input', validatePassword);
      
      updateAccountForm.addEventListener('submit', function (event) {
          // Validate the password when the form is submitted.
          validatePassword();
      
          // Check if there are validation errors related to the password.
          const passwordRequirements = document.getElementById('passwordRequirements');
          const confirmpasswordRequirements = document.getElementById('confirmpasswordRequirements');
      
          if (
              (passwordRequirements.textContent !== '✔ Password is valid.' && newPasswordAccount.value !== '') ||
              (confirmpasswordRequirements.textContent !== '✔ Passwords match.' && newConfirmPasswordAccount.value !== '')
          ) {
              passwordRequirements.textContent = 'Password must be at least 6 characters long and contain at least one number, one uppercase letter, and one special character.';
              passwordRequirements.style.color = 'red';
              event.preventDefault();
          }
      });
      
      function validatePassword() {
          const newPassword = newPasswordAccount.value;
          const newConfirmPassword = newConfirmPasswordAccount.value;
          const passwordPattern = /^(?=.*[0-9])(?=.*[A-Z])(?=.*[!@#$%^&*]).{6,}$/;
          const passwordRequirements = document.getElementById('passwordRequirements');
          const confirmpasswordRequirements = document.getElementById('confirmpasswordRequirements');
      
          // Display password requirements message
          if (newPassword.length === 0) {
              passwordRequirements.textContent = '';
          } else if (passwordPattern.test(newPassword)) {
              passwordRequirements.textContent = '✔ Password is valid.';
              passwordRequirements.style.color = 'green';
          } else {
              passwordRequirements.textContent = 'Password must be at least 6 characters long and contain at least one number, one uppercase letter, and one special character.';
              passwordRequirements.style.color = 'gray';
          }
      
          // Check if passwords match
          if (newPassword === newConfirmPassword && newPassword.length > 0) {
              confirmpasswordRequirements.textContent = '✔ Passwords match.';
              confirmpasswordRequirements.style.color = 'green';
              confirmpasswordRequirements.setCustomValidity('');
          } else if (newPassword.length > 0 && newConfirmPassword.length > 0) {
              // Display an error message when passwords do not match and both fields are not empty.
              confirmpasswordRequirements.textContent = 'Passwords do not match.';
              confirmpasswordRequirements.style.color = 'red';
              confirmpasswordRequirements.setCustomValidity('Passwords do not match');
          } else {
              // If Confirm Password is empty, don't display any message
              confirmpasswordRequirements.textContent = '';
              confirmpasswordRequirements.style.color = 'black';
              confirmpasswordRequirements.setCustomValidity('');
          }
      }
   </script>
   <script>
      $(document).ready(function () {
        $("#togglePassword").click(function () {
          var passwordField = $("#passwordCreate");
          var fieldType = passwordField.attr("type");
          if (fieldType === "password") {
            passwordField.attr("type", "text");
            $("#togglePassword").removeClass("fa-eye-slash").addClass("fa-eye");
          } else {
            passwordField.attr("type", "password");
            $("#togglePassword").removeClass("fa-eye").addClass("fa-eye-slash");
          }
        });
      
        $("#toggleConfirmPassword").click(function () {
          var confirmPasswordField = $("#confirmpasswordCreate");
          var fieldType = confirmPasswordField.attr("type");
          if (fieldType === "password") {
            confirmPasswordField.attr("type", "text");
            $("#toggleConfirmPassword").removeClass("fa-eye-slash").addClass("fa-eye");
          } else {
            confirmPasswordField.attr("type", "password");
            $("#toggleConfirmPassword").removeClass("fa-eye").addClass("fa-eye-slash");
          }
        });
      
        // Add CSS for changing eye icon color on hover
        $("#togglePassword, #toggleConfirmPassword").hover(function () {
          $(this).css("color", "blue"); // Change the color to your desired hover color
        }, function () {
          $(this).css("color", ""); // Remove the color on hover out
        });
      });
   </script>
   <!-- 
      <script>
               const newpasswordInput = document.getElementById('passwordAccount');
              const passwordRequirements = document.getElementById('passwordRequirements');
       
      
              newpasswordInput.addEventListener('input', validatePassword);
      
              function validatePassword() {
                  const password = newpasswordInput.value;
                  const passwordPattern = /^(?=.*[0-9])(?=.*[A-Z])(?=.*[!@#$%^&*]).{6,}$/;
      
                  if (passwordPattern.test(password)) {
                      passwordValidationMessage.textContent = '✔ Password is valid.';
                      passwordValidationMessage.style.color = 'green';
                  } 
      
                  // Display password requirements message
                  if (password.length === 0) {
                      passwordRequirements.textContent = ''; // Clear the message if no input
                  } else {
                      passwordRequirements.textContent = 'Password must be at least 6 characters long and include numbers, uppercase letters, and special characters.';
                  }
              } 
          </script> -->
   <script>
      const passwordCreate = document.getElementById('passwordCreate');
      const confirmPasswordCreate = document.getElementById('confirmpasswordCreate');
      const passwordRequirementsCreate = document.getElementById('passwordRequirementsCreate');
      
      const confirmpasswordRequirementsCreate = document.getElementById('confirmpasswordRequirementsCreate');
      const togglePassword = document.getElementById('togglePassword');
      const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
      
      passwordCreate.addEventListener('input', validatePassword);
      confirmPasswordCreate.addEventListener('input', validatePassword);
      
      function validatePassword() {
      const password = passwordCreate.value;
      const confirmPassword = confirmPasswordCreate.value;
      const passwordPattern = /^(?=.*[0-9])(?=.*[A-Z])(?=.*[!@#$%^&*]).{6,}$/;
      
      // Display password requirements message
      if (password.length === 0) {
      passwordRequirementsCreate.textContent = '';
      } else if (passwordPattern.test(password)) {
      passwordRequirementsCreate.textContent = '✔ Password is valid.';
      passwordRequirementsCreate.style.color = 'green';
      } else {
      passwordRequirementsCreate.textContent = 'Password must be at least 6 characters long and contain at least one number, one uppercase letter, and one special character.';
      passwordRequirementsCreate.style.color = 'gray';
      }
      
      // Check if passwords match
      if (password === confirmPassword && password.length > 0) {
      console.log("Passwords match");
      confirmpasswordRequirementsCreate.textContent = '✔ Passwords match.';
      confirmpasswordRequirementsCreate.style.color = 'green';
      confirmPasswordCreate.setCustomValidity('');
      } else {
      
      }
      }
      
      
   </script>
   <script>

     
      
      passwordCreate.addEventListener('input', validatePasswordNew);
      
      function validatePasswordNew() {
          const password = passwordCreate.value;
          const passwordPattern = /^(?=.*[0-9])(?=.*[A-Z])(?=.*[!@#$%^&*]).{6,}$/;
      
          if (passwordPattern.test(password)) {
              passwordRequirementsCreate.textContent = '✔ Password is valid.';
              passwordRequirementsCreate.style.color = 'green';
          } else {
              // Display password requirements message
              if (password.length === 0) {
                  passwordRequirementsCreate.textContent = ''; // Clear the message if no input
              } else {
                  passwordRequirementsCreate.textContent = 'Password must be at least 6 characters long and contain at least one number, one uppercase letter, and one special character.';
                  passwordRequirementsCreate.style.color = 'gray'; // Set the color to red for invalid password
              }
          }
      }
   </script>
   <script>
$(document).ready(function(){
  // Get the member_id from the URL query parameter
  const urlParams = new URLSearchParams(window.location.search);
  const email = urlParams.get('email');
  const name = urlParams.get('name');

  // Check if memberId exists and is not empty
  if (email) {
    // Set the value of your dropdown or input field with the memberId
    document.getElementById("name").value = name;
    document.getElementById("email").value = email;
    
    $("#addAccountModal").modal("show");
    
    // Remove the memberId from the URL without refreshing the page
    const newUrl = window.location.pathname; // Get the current path without query parameters
    window.history.pushState({}, document.title, newUrl); // Update the URL
  }     
});

$("#closeAddAccountModal").click(function() {
    $('#addAccountModal').modal("hide");
});

</script>
   <!-- js -->
</html>