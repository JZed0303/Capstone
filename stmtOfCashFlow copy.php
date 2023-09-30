<?php
   session_start();
   if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
      // Redirect the user to the login page
      header('Location: index.php');
      exit();
    }else{
      require 'includes/dbh.inc.php';
      $email = $_SESSION['email'];
     
      $role =  $_SESSION['role'];
      $id = $_SESSION['id'];
     
      // Output the value of $role in a script tag
      echo "<script>var role = '" . $role . "';</script>";
      
      // Retrieve data from the database
      $sql = "SELECT user_id, name, email, role, password
        FROM user_tbl";
     $stmt = $conn->query($sql);
     $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
     
    }
    
      
    
     
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title> Functional Expenses</title>
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
            color: black;
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
        }

        .edit-account-btn {
            background-color: #007bff; /* Blue background color for the Edit button */
            color: white; /* Text color for the Edit button */
            border: none; /* Remove button border */
            padding: 10px 20px; /* Adjust padding for the button */
            border-radius: 5px; /* Add rounded corners */
            transition: background-color 0.3s; /* Add a smooth transition on hover */
        }

        .edit-account-btn:hover {
            background-color: #0056b3; /* Change background color on hover */
        }

        .deleteAccountModal {
            background-color: #ff6b6b; /* Red background color for the Delete button */
            color: white; /* Text color for the Delete button */
            border: none; /* Remove button border */
            padding: 10px 20px; /* Adjust padding for the button */
            border-radius: 5px; /* Add rounded corners */
            transition: background-color 0.3s; /* Add a smooth transition on hover */
        }

        .deleteAccountModal:hover {
            background-color: #d43737; /* Change background color on hover */
        } .requirements {
            font-size: 14px; /* Set the font size to make it smaller */
            color: gray; /* Set the text color to gray */
        } <style>
     
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
     table {
     border-collapse: collapse;
     width: 100%;
     font-family: 'Open Sans', sans-serif;
     color: var(--dark);
     }
     th, td {
     border: 0.5px solid var(--dark);
     padding: 8px;
     }
     th {
     text-align: left;
     background-color: var(--darkblue) !important;
     color: var(--white);
     }
     td:nth-child(even) {
     background-color: var(--white);
     color: var(--deam);
     }
     td:nth-child(odd) {
     background-color: var(--white);
     color: var(--dark);
     }
     table {
     margin-bottom: 20px;
     }
     </style>
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
            if ($_SESSION['role'] == 'admin') {
               include 'template/admin_sidebar.php';
            } else if  ($_SESSION['role'] == 'auditor') {
            include 'template/auditor_sidebar.php';
            } else if  ($_SESSION['role'] == 'treasurer') {
            include 'template/treasurer.php';
            } else if  ($_SESSION['role'] == 'guest') {
               include 'template/guest_sidebar.php';
            }   
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
                  
                  <div class="">
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

            <div class="container-fluid wrapper px-3">
               <div class="row g-2 mx-1 mb-3">
                  <div class="col-12 rounded-3 px-md-4"> 
                     <div class="dataTable_wrapper income col-12 pt-3 px-0 pb-0 px-4">
                        <div class="bg-white rounded h-100  mb-0 pb-0  " >
                        <table class="table">
   <thead>
   <div class="text-center p-2" style="color:deam;  font-weight: bold;">
                                 <hr   >
                                 ROTARACT CLUB OF CARMONA <br>
                                 STATEMENT CASHFLOW
                                 <div>
                                    AS OF [DATE]
                                 </div>
                              </div>
      <tr>
         <th></th>
         <th>Cash Inflows</th>
         <th>Cash Outflows</th>
      </tr>
   </thead>
   <tbody>
      <tr>
         <td>Operating Activities</td>
         <td></td>
         <td></td>
      </tr>
      <tr>
         <td>&nbsp;&nbsp;&nbsp;&nbsp;Membership Dues (402)</td>
         <td>$2,000</td>
         <td></td>
      </tr>
      <tr>
         <td>&nbsp;&nbsp;&nbsp;&nbsp;Fundraising (401)</td>
         <td>$5,000</td>
         <td></td>
      </tr>
      <tr>
         <td>&nbsp;&nbsp;&nbsp;&nbsp;Donations (403)</td>
         <td>$3,000</td>
         <td></td>
      </tr>
      <tr>
         <td>&nbsp;&nbsp;&nbsp;&nbsp;Sponsorship (404)</td>
         <td>$1,500</td>
         <td></td>
      </tr>
      <tr>
         <td>&nbsp;&nbsp;&nbsp;&nbsp;Pledge (405)</td>
         <td>$1,000</td>
         <td></td>
      </tr>
      <tr>
         <td>Total Operating Activities</td>
         <td>$12,500</td>
         <td></td>
      </tr>
      <tr>
         <td>Investing Activities</td>
         <td></td>
         <td></td>
      </tr>
      <tr>
         <td>Total Investing Activities</td>
         <td>$0</td>
         <td></td>
      </tr>
      <tr>
         <td>Financing Activities</td>
         <td></td>
         <td></td>
      </tr>
      <tr>
         <td>&nbsp;&nbsp;&nbsp;&nbsp;District events (501)</td>
         <td></td>
         <td>$500</td>
      </tr>
      <tr>
         <td>&nbsp;&nbsp;&nbsp;&nbsp;International service (502)</td>
         <td></td>
         <td>$1,000</td>
      </tr>
      <tr>
         <td>&nbsp;&nbsp;&nbsp;&nbsp;Club service (503)</td>
         <td></td>
         <td>$800</td>
      </tr>
      <tr>
         <td>&nbsp;&nbsp;&nbsp;&nbsp;Professional service (504)</td>
         <td></td>
         <td>$300</td>
      </tr>
      <tr>
         <td>&nbsp;&nbsp;&nbsp;&nbsp;Community service (505)</td>
         <td></td>
         <td>$1,200</td>
      </tr>
      <tr>
         <td>&nbsp;&nbsp;&nbsp;&nbsp;Public image (506)</td>
         <td></td>
         <td>$400</td>
      </tr>
      <tr>
         <td>&nbsp;&nbsp;&nbsp;&nbsp;The Rotary Foundation (507)</td>
         <td></td>
         <td>$1,500</td>
      </tr>
      <tr>
         <td>Total Financing Activities</td>
         <td>$0</td>
         <td>$5,700</td>
      </tr>
      <tr>
         <td>Net Increase (Decrease) in Cash</td>
         <td>$12,500</td>
         <td>($5,700)</td>
      </tr>
      <tr>
         <td>Cash and Cash Equivalents, Beginning of Period</td>
         <td>$120,000</td>
         <td></td>
      </tr>
      <tr>
         <td>Cash and Cash Equivalents, End of Period</td>
         <td></td>
         <td>$126 ,800</td>
      </tr>
   </tbody>
</table>
                     </div>   
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
      <div class="modal fade p-3 p-lg-1 p-md-1" id="addAccountModal" tabindex="-1" role="dialog" aria-labelledby="addIncomeModalLabel" aria-hidden="true">
    <div class="modal-dialog add modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addIncomeModalLabel">Add New Account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
      <!-- Modal for Delete Confirmation -->
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
                  <p>Are you sure you want to delete this receipt?</p>
               </div>
               <div class="modal-footer">
                  <!-- Hidden input fields to store the filename and ID values -->
                  <input type="hidden" id="deleteFilename" value="<?php echo $filename; ?>">
                  <input type="hidden" id="deleteRowID" value="">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                  <button type="button" class="btn btn-danger" onclick="deleteReceipt()">Delete</button>
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

   <!-- js -->
   <script>
      // Get a reference to the print button by its id
      var printButton = document.getElementById("printButton");
      
      // Add a click event listener to the button
      printButton.addEventListener("click", function () {
          // Use the window.print() method to trigger the print dialog
          window.print();
      });
   </script>
</html>