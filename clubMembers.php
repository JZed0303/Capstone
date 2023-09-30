<?php
   session_start();
   
   if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Redirect the user to the login page
    header('Location: index.php');
    exit();
   }
   
      require 'includes/dbh.inc.php';
   
   $email = $_SESSION['email'];
   $role =  $_SESSION['role'];
   
   // Output the value of $role in a script tag
   echo "<script>var role = '" . $role . "';</script>";
   
   
   ?>
<!DOCTYPE html>
<style>
   th.sorting{
   width: 10px!important;
   }body{
   color:black;
   } th[data-cell="nameMember"] {
   width: 100px!important;
   }.progress{
   background:#b2c9f3!important;
   }.progress-bar{
   background:#0e4591!important;
   font-weight:bolder!important;
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
   }div#dataTablesIncome_filter {
    position: relative;
    display: flex;
    justify-content: flex-end;
    color: white;
    top: 12px;
    left:-22px;
}.buttonsContainer {
  position: relative;
  right: 0; /* Initial position, no offset */
}
</style>
</style>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>Club Members </title>
      <meta content="width=device-width, initial-scale=1.0" name="viewport">
      <meta content="" name="keywords">
      <meta content="" name="description">
      <!-- css content -->
      <link rel="stylesheet" href="assets/fontawesome-free-6.3.0-web/css/all.css">
      <link rel="stylesheet" href="assets/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/css/budgetManagement.css">
      <!-- DATA TABLES  cs-->
      <link rel="stylesheet" href="assets/datatable/css/datatables.min.css">
      <link rel="stylesheet" href="assets/datatable/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/datatable/css/jquery.dataTables.min.css">
      <link rel="stylesheet" href="assets/css/cashinflow/cashInflow.css">
      <link rel="stylesheet" href="assets/datatable/js/cdn.datatables.net_buttons_2.4.1_css_buttons.dataTables.min.css">
      <!-- media Print -->
      <!-- DATA TABLE JS -->
      <script src="assets/datatable/js/jquery-3.6.0.min.js"></script>
      <script src="assets/datatable/css/bootstrap.bundle.min.js"></script>
      <script src="assets/datatable/js/datatables.min.js"></script>   
      <script src="assets/datatable/js/vfs_fonts.js"></script>
      <!-- DATA TABLES DATE FILTER -->
      <script src="assets/datatable/js/moment.min.js"></script>
      <script src="assets/datatable/js/clubMembers.js"></script>
      <!-- general -->
      <script src="assets/js/popper.min.js"></script>
      <script src="assets/js/bootstrap.min.js"></script>
      <script src="assets/js/moment.min.js"></script>
      <script src="assets/js/select2.min.js"></script>
      <!-- <script src="assets/js/script.js"></script> -->
      <!-- fixed header -->
      <!-- Google Web Fonts -->
      <link href="css/bootstrap.min.css" rel="stylesheet">
      <link href="css/style.css" rel="stylesheet">
      <link href="assets/css/cashinflow/cashInflow.css">
      <!-- general -->
   </head>
   <!-- START BODY -->
   <body>
      <div class="container-fluid position-relative d-flex p-0">
      <!-- Spinner Start -->
      <!-- <div id="spinner" class="show bg-primary position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
         <div class="spinner-border text-white" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
         </div>
         </div> -->
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
         <div class="container-fluid pt-4 px-md-3 px-lg-4 px-md-3 px-lg-3  mt-3">
            <div class="filter">
               <div class=" row g-3 mx-md-4 mx-lg-4   mx-1 p-md-3 p-lg-3 p-2 ">
                  <!-- filter row -->
                  <div class="col-sm-12 col-md-6 col-xl-4 d-flex justify-content-center">
                     <div class="categ col-lg-12 col-sm-10 d-flex flex-column ">
                        <span><strong class="">Type</strong></span>
                        <div class="form-group row">
                           <select class="type-box col-sm-12 col-md-10 form-control form-control-sm">
                              <option selected >Select Type</option>
                              <option  >Member</option>
                              <option  >Officer</option>
                           </select>
                        </div>
                     </div>
                  </div>
                  <div class="ms-lg-0 ms-md-0 col-sm-12 col-md-6 col-xl-4 d-flex align-items-center ">
                     <span class="mt-2"><strong style="position:absolute; top:-22px;"></strong></span>
                     <div class="categ col-lg-12 col-sm-10 d-flex flex-column ">
                        <span><strong class="">Category</strong></span>
                        <div class="form-group row">
                           <select class="category-box col-sm-12 col-md-10 form-control form-control-sm">
                              <option selected >Select Category</option>
                              <option  >Student</option>
                              <option  >Working</option>
                           </select>
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-12 col-xl-3 ms-sm-3">
                     <span  class=""><strong class="">Search</strong></span>
                     <div class="form-group row mx-0 ">
                        <div class="col-12">
                           <div class="row">
                              <div class="col-sm-9 col-8 ">
                                 <input type="text" class="form-control form-control-sm" autocomplete="off" placeholder="Search Keyword" id="search-input">                          
                              </div>
                              <div class="col-sm-3 col-4">
                                 <button type="button" class="filter-btn btn-primary border-0 btn-sm d-flex w-auto " id="reset-btn"><i class="mt-1 mr-2  fa fa-refresh"></i><span>Reset</span></button>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- end filter row -->
               </div>
            </div>
         </div>
         <hr class="mx-4">
         <div class="container-fluid wrapper">
            <div class="row g-2 mx-1 mb-3">
               <div class="col-12 shadow-lg rounded-3 px-md-2">
                  <div class="dataTable_wrapper income col-12 pt-3 px-0 pb-0">
                     <div class="bg-white rounded h-100  mb-0 pb-0">
                        <div class="table-responsive">
                           <table id="dataTablesIncome" class="table bg-white shadow-lg no-vertical-borders">
                              <thead>
                                 <tr>
                                    <th class="d-none" data-cell = "id" >NO.</th>
                                    <th data-cell = "emailMember" id="center">Email</th>
                                    <th data-cell = "nameMember" id="center">Name</th>
                                    <th data-cell = "categoryMember"> <span></span> TYPE</th>
                                    <th data-cell = "fundMember" >CATEGORY</th>
                                    <th data-cell = "duesMember" >Dues Percent</th>
                                    <th data-cell = "" id="right-corner">Action</th>
                                 </tr>
                              <tbody>
                                 <?php 
                                    require 'includes/dbh.inc.php';
                                    $sql = "
                                    SELECT
                                        m.member_id,
                                        m.name,
                                        m.email,
                                        m.category,
                                        m.type,
                                        SUM(d.amount) AS total_amount
                                    FROM
                                        club_members_tbl m
                                    LEFT JOIN
                                        monthly_dues_tbl d ON m.member_id = d.member_id
                                    GROUP BY
                                        m.member_id, m.name, m.email, m.category, m.type
                                    ";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute();
                                    
                                    if ($stmt->rowCount() > 0) {
                                       while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                     
                                          $maxDues = ($row['type'] === 'Student') ? 1200 : 2400; // Set the max dues based on type
                                    
                                          // Calculate the dues percentage
                                          $duesPercentage = ($row['total_amount'] / $maxDues) * 100;
                                          $duesPercentage = number_format($duesPercentage, 0); // Format with 0 decimal places
                                          
                                          
                                       
                                          
                                          echo "<tr>";
                                          echo "<td class='d-none'>" . $row["member_id"] . "</td>";
                                          echo "<td>" . $row["email"] . "</td>";
                                          echo "<td>" . $row["name"] . "</td>";
                                          echo "<td>" . $row["category"] . "</td>";
                                          echo "<td>" . $row["type"] . "</td>";
                                    
                                          echo "<td>";
                                          echo '<div class="progress">';
                                          echo '<div class="progress-bar" role="progressbar" style="width: ' .number_format($duesPercentage, 0) . '%" aria-valuenow="' .number_format($duesPercentage, 0) . '" aria-valuemin="0" aria-valuemax="100">' . number_format($duesPercentage, 0) . '%</div>';
                                          echo '</div>';
                                          echo "</td>";
                                          echo "<td class='text-center d-flex flex-row justify-content-center '>";
                                          echo '<a href="#" class="btn btn-sm btn-primary edit-member-button"
                                              data-toggle="modal"
                                    
                                              data-member-id="' . $row['member_id'] . '"
                                              data-email="' . $row['email'] . '"
                                              data-name="' . $row['name'] . '"
                                              data-category="' . $row['category'] . '"
                                              data-type="' . $row['type'] . '">
                                              <i class="fas fa-edit"></i>
                                          </a>
                                          ';
                                          echo ' ';
                                          echo '<a href="#" class="btn btn-sm btn-danger mx-3 delete-member-button"
                                          data-toggle="modal"
                                    
                                          data-member-id="' . $row['member_id'] . '">
                                          <i class="fas fa-trash"></i>
                                      </a>';  
                                      
                                      echo '<a href="clubDues.php?member_id=' . $row['member_id'] . '" class="btn btn-sm btn-primary">
                                    <i class="fas fa-piggy-bank"></i>
                                    </a>';
                                    echo "</td>";
                                          echo "</tr>";
                                          
                                      }
                                    } 
                                    
                                    ?>
                              </tbody>
                              <tfoot>
                                 <tr>
                                    <td class="bg-secondary text-white" colspan="7"></td>
                                 </tr>
                              </tfoot>
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
      <div class="modal fade" id="createAccountMember" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
               <div class="modal-header text-center">
                  <h5 class="modal-title text-center" id="ConfirmationModalLabel">New Acount</h5>
                 
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body px-5 pb-2 pt-4 text-center">
                  <form action="CRUD/create/addClubMember.php" method="POST">
                     <input type="hidden" id="delete_member" name="member_id" value="">
                     <input type="hidden" id="createEmail" name="createEmail" value="">
                     <input type="hidden" id="createName" name="createName" value="">
                     <input type="hidden" id="createCategory" name="createCategory" value="">
                     <input type="hidden" id="createType" name="createType" value="">
                     <p>Do you want to Create an account for this member?</p>
               </div>
               <div class="d-flex justify-content-center align-items-center">
               <button type="button" class="btn btn-md btn-primary mx-1" id="createNewAccount">Yes</button>
               <button type="submit" class="btn btn-md btn-danger" id="" id="createOnlyMember">No </button>
               </div>
               <div class="modal-footer">
               </div>
               </form>
            </div>
         </div>
      </div>
      <!-- Delete Confirmation Modal -->
      <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                  <button type="button" class="close" data-dismiss="modal"  aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body px-5 pb-2 pt-4 text-center">
                  <form action="CRUD/delete/deleteClubMember.php" id="deleteForm" method="GET">
                     <input type="hidden" id="delete_member_id" name="member_id" value="">
                     <p>Are you sure you want to delete this member?</p>
                     <p>this will also delete all the Payment Dues Associated?</p>
               </div>
               <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-dismiss="modal"  id="close" >Cancel</button>
               <button type="button" class="btn btn-danger" id="confirmDeleteButton">Delete</button>
               </div>
               </form>
            </div>
         </div>
      </div>
      <!-- edit modal Start-->
      <div class="modal fade" id="updateMemberModal" tabindex="-1" role="dialog" aria-labelledby="updateMemberModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered modal-lg p-3 p-lg-0 p-md-0" role="document">
            <div class="modal-content mx-4 w-100">
               <div class="modal-header d-flex justify-content-center">
                  <h5 class="modal-title" style="margin-left:auto;" id="updateMemberModalLabel">Update Member</h5>
                  <button type="button" class="close" data-dismiss="modal"  id="closeUpdate"  aria-label="Close">
                  <span aria-hidden="true">×</span>
                  </button>
               </div>
               <div class="modal-body pt-4 pb-3 px-5">
                  <form action="CRUD/update/updateClubMember.php" method="POST">
                     <!-- Replace "update_member.php" with your update handler -->
                     <input type="hidden" id="update_member_id" name="member_id" value="">
                     <!-- Add hidden input field to store the member ID for updating -->
                     <div class="form-group">
                        <label for="update_email">Email:</label>
                        <input type="email" class="form-control" id="update_email" name="email" placeholder="Enter email" required>
                     </div>
                     <div class="form-group">
                        <label for="update_name">Name:</label>
                        <input type="text" class="form-control" id="update_name" name="name" placeholder="Enter Name" required>
                     </div>
                     <div class="form-group">
                        <label for="update_category">Category:</label>
                        <select class="form-control" id="update_category" name="category" required>
                           <option value="Member">Member</option>
                           <option value="Officer">Officer</option>
                        </select>
                     </div>
                     <div class="form-group">
                        <label for="update_type">Type:</label>
                        <select class="form-control" id="update_type" name="type" required>
                           <option value="Student">Student</option>
                           <option value="Working">Working</option>
                        </select>
                     </div>
                     <!-- You can add more fields as needed for member updates -->
                     <div class="form-group text-right">
                        <div class="modal-footer">
                           <!-- Close button -->
                           <button type="button" class="btn btn-secondary close-modal" id="closeUpdate"  >Close</button>
                           <!-- Submit button -->
                           <button type="submit" class="btn btn-primary">Update Member</button>
                        </div>
                     </div>   
                  </form>
               </div>
            </div>
         </div>
      </div>
      <!--edit modal end -->
      <!-- Details Modal Start -->
      <div class="modal fade" id="addClubMemberModal" tabindex="-1" role="dialog" aria-labelledby="addClubMemberLabel" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered modal-lg p-3 p-lg-0 p-md-0" role="document">
            <div class="modal-content">
               <div class="modal-header d-flex justify-content-center">
                  <h5 class="modal-title" style="margin-left:auto; font-weight: bold;" id="editIncomeModalLabel">Add Club Member </h5>
                  <button type="button" class="close" data-dismiss="modal"  id="closeAdAccount"  aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="row col-12"></div>
                  <div class="modal-body px-5 px-lg-5 px-md-5 ">
                     <form action="CRUD/create/addClubMember.php" method="POST">
                        <!-- Replace "add_member.php" with your form submission handler -->
                        <div class="form-group mt-3">
                           <label for="email">Email:</label>
                           <input type="email" class="form-control" id="email" name="email" require placeholder="Enter email" required>
                        </div>
                        <div class="form-group">
                           <label for="email">Name:</label>
                           <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" required>
                        </div>
                        <div class="form-group">
                           <label for="category">Category:</label>
                           <select class="form-control" id="category" name="category" required>
                              <option value="" selected >Choose Category....</option>
                              <option value="Member">Member</option>
                              <option value="Officer">Officer</option>
                           </select>
                        </div>
                        <div class="form-group">
                           <label for="category">Type:</label>
                           <select class="form-control" id="type" name="type" required>
                              <option value="" selected >Choose Type....</option>
                              <option value="Student">Student</option>
                              <option value="Working">Working</option>
                           </select>
                        </div>
                        <div class="form-group text-right d-flex justify-content-end">
                           <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" id="closeAddMember" data-dismiss="modal">Close</button>
                              <button type="button" class="btn btn-md btn-primary"  id="addMemberButton" disabled>Add Member</button>
                           </div>
   
                     </form>
                     </div>
               </div>
            </div>
         </div>
         <!-- edit modal Start-->
         <!-- Details Modal End -->
         <!-- ADD MODAL -->
         <!-- Add Member Modal -->
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
                                 <input type="text" class="form-control" id="nameAccount" name="name" placeholder="Enter the name...">
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label for="email">Email:</label>
                                 <input type="email" class="form-control" id="emailAccount" name="email" placeholder="Enter the email...">
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
                              <input type="text" class="form-control" id="security_answer" name="security_answer" placeholder="Enter a security answer..." autocomplete="username">
                           </div>
                           <div class="form-group">
                              <label for="password">Password:</label>
                              <div class="input-group">
                                 <input type="password" class="form-control" id="passwordCreate" name="password" placeholder="Enter a password..." autocomplete="new-password">
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
                                    <input type="password" class="form-control" id="confirmpasswordCreate" name="confirmpassword" autocomplete="newpassword" placeholder="Confirm password...">
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
                           <button type="button" class="btn btn-secondary"  data-dismiss="modal">Cancel</button>
                           <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- charts -->
   </body>
   <!-- END BODY -->
   <!-- Template Javascript -->
   <script src="js/main.js"></script>
   <script src="js/cdn.jsdelivr.net_npm_bootstrap@5.0.0_dist_js_bootstrap.bundle.min.js"></script>
   <script>
      $(document).ready(function() {
         // When "Yes" button in the first modal is clicked
         $("#createNewAccount").click(function() {
            // Get values from the first modal
            const createEmail = $("#createEmail").val();
            const createName = $("#createName").val();
      
      
           
         });
      });
       
   </script>
   <script>
      $(document).ready(function() {
         // Get references to the input fields and the submit button
         const $emailInput = $('#email');
         const $nameInput = $('#name');
         const $categoryInput = $('#category');
         const $typeInput = $('#type');
         const $addButton = $('#addMemberButton');
      
         // Function to check if any input is empty and toggle the button state
         function toggleButtonState() {
            const emailValue = $emailInput.val().trim();
            const nameValue = $nameInput.val().trim();
            const categoryValue = $categoryInput.val().trim();
            const typeValue = $typeInput.val().trim();
      
            // Enable the button if all inputs have values, otherwise disable it
            if (emailValue && nameValue && categoryValue && typeValue) {
               $addButton.prop('disabled', false);
            } else {
               $addButton.prop('disabled', true);
            }
         }
      
         // Attach event listeners to input fields to check for changes
         $emailInput.on('input', toggleButtonState);
         $nameInput.on('input', toggleButtonState);
         $categoryInput.on('input', toggleButtonState);
         $typeInput.on('input', toggleButtonState);
      
         // Initial check to set the button state on page load
         toggleButtonState();
      });
   </script>
   <script>
      // JavaScript/jQuery to open the update modal and populate form fields
      
      // JavaScript/jQuery to open the update modal and populate form fields
      $(document).on("click", ".edit-member-button", function () {
      // Get the member ID and other information from the row
      var memberId = $(this).data("member-id");
      var email = $(this).data("email");
      var name = $(this).data("name");
      var category = $(this).data("category");
      var type = $(this).data("type");
      
      // Populate the form fields with the retrieved data
      $("#update_member_id").val(memberId);
      $("#update_email").val(email);
      $("#update_name").val(name);
      $("#update_category").val(category);
      $("#update_type").val(type);
      
      // Open the update modal
      $("#updateMemberModal").modal("show");
      });
      
          
      // JavaScript/jQuery code to handle delete confirmation and AJAX request
      $(document).on("click", ".delete-member-button", function () {
      // Get the member ID from the data attribute
      var memberId = $(this).data("member-id");
      
      // Set the member ID in the hidden input field
      $("#delete_member_id").val(memberId);
      
      // Show the delete confirmation modal
      $("#deleteConfirmationModal").modal("show");
      });
      
      // Handle the delete confirmation and send an AJAX request
      $("#confirmDeleteButton").on("click", function () {
      // Get the member ID from the hidden input field
      var memberId = $("#delete_member_id").val();
      // Assuming you have a form with an ID "deleteForm"
      $("#deleteForm").submit();
      });
      
   </script>
    <script>
$(document).ready(function () {
  const containers = document.getElementsByClassName('table-responsive');

  for (const container of containers) {
    container.addEventListener('scroll', () => {
      const buttonsContainer = document.querySelector('.buttonsContainer');
      buttonsContainer.style.left = `${container.scrollLeft}px`;
    });
  }
});

</script>

   <script>
      $(document).ready(function () {
          // When the button is clicked, show the modal with the ID "updateMemberModal"
          $("#close").click(function () {
              $("#updateMemberModal").modal("hide");
              $("#deleteConfirmationModal").modal("hide");
          });
          
      });
   </script>
   <script>
      $(document).ready(function () {
          // Listen for the "Add Member" button click
          $('#addMemberButton').click(function () {
              // Get data from the fields in addClubMemberModal
              var email = $('#email').val();
              var name = $('#name').val();
              var category = $('#category').val();
              var type = $('#type').val();
      
              // Check if any of the required fields are empty
              if (email === "") {
                  showErrorModal("Please fill in all required fields.");
                  return false; // Prevent the button click event
              }
      
              // Check if the email already exists
              $.ajax({
                  url: 'sqlGetData/checkMemberEmailExist.php', // Replace with the actual server-side script URL
                  method: 'POST',
                  data: { email: email },
                  success: function (response) {
                       var dataResponse = JSON.parse(response);
            
                    var dataResponse = JSON.parse(response);
                      if (dataResponse.status === 'exists') {
                          showErrorModal("Email already exists.");
                      } else {
                          $('#createAccountMember #createEmail').val(email);
                          $('#createAccountMember #createName').val(name);
                          $('#createAccountMember #createCategory').val(category);
                          $('#createAccountMember #createType').val(type);
                          $('#addClubMemberModal').addClass('d-none');
           
                          $('#createAccountMember').modal('show');
                      }
                  },
                  error: function () {
                      showErrorModal("An error occurred while checking the email.");
                  }
              });
          });
      });
      
      
   </script>
      
      <script>
      
      $(document).ready(function() {
    // When "Yes" button in the first modal is clicked
    $("#createNewAccount").click(function() {
   
        // Get values from the first modal
        const createEmail = $("#createEmail").val();
        const createName = $("#createName").val();
        const createCategory = $("#createCategory").val();
        const createType = $("#createType").val();
        alert(createEmail);

        $.ajax({
            url: 'CRUD/create/addClubMember.php', // Replace with the actual server-side script URL
            method: 'GET',
            data: {
               createEmail: createEmail,
               createName: createName,
               createCategory: createCategory,
               createType: createType
            },
            success: function(response) {
                  
            },
            error: function() {
                showErrorModal("An error occurred while checking the email.");
            }
        });


      
        window.location.href = "manageAccounts.php?name=" + encodeURIComponent(createName) + "&email=" + encodeURIComponent(createEmail);
    });
});

      
      
   </script>
   <script>
      function showErrorModal(errorMessage) {
       var errorModal = new bootstrap.Modal(document.getElementById("errorModal"));
       var errorMessageElement = document.querySelector("#errorModal .modal-body p");
       errorMessageElement.textContent = errorMessage;
       errorModal.show();
       var closeButton = document.querySelector("#errorModal .btn-secondary");
        closeButton.addEventListener("click", closeModal);
       }
   </script>
   <script>
      $(document).ready(function () {
          // When the button is clicked, show the modal with the ID "updateMemberModal"
          $(".close-modal").click(function () {
              $("#updateMemberModal").modal("hide");
      
          });
          
      });
   </script>
</html>