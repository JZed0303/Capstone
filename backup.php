<?php
   session_start();
   
   if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
      // Redirect the user to the login page
      header('Location: index.php');
      exit();
      
    }else{
        include 'sqlGetData/formatValuePeso.php';
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
   
      
    }
    
   
    $sql = "SELECT 
    ci.id,
    ci.date,
    ci.fundAccTitle,
    ci.account_number,
    coa.child_account_name,
    ci.amount,
    ci.type,
    ci.remarks,
    ci.receipt,
    ci.edit_time,
    ci.status
   FROM 
    cashinflow_tbl ci
   LEFT JOIN 
    chart_of_accounts coa ON ci.account_number = coa.child_account_number WHERE ci.status=1;
   ";
   $stmt = $conn->query($sql);
   $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
     

   $sql = "SELECT cashoutflow_tbl.*, chart_of_accounts.child_account_number
   FROM cashoutflow_tbl
   JOIN chart_of_accounts ON cashoutflow_tbl.expense_category = chart_of_accounts.child_account_name
   WHERE cashoutflow_tbl.status = 1;
   ";
   $stmt = $conn->prepare($sql);
   $stmt->execute();
   $resultCashouflow = $stmt->fetchAll(PDO::FETCH_ASSOC);
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>System Backup </title>
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
      <script src="assets/datatable/js/datatablesBackup.js"></script>
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
      color: #1c1a1a;
      }
      /* Modal Header Styling */
      .modal-header {
      border-bottom: none;
      padding: 20px;
      background-color: var(--azure);
      color: var(--white);
      border-top-left-radius: 8px;
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
      }
      th.sorting {
      width: 500px !important;
      }
      .container-fluid.wrapper {
      position: relative;
      top: 4%;
      }
      .filter {
      font-size: 14px !important;
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
      height: 6px;
      /* Adjust the height to make it thin */
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
      font-family: 'Open Sans', Arial, sans-serif !important;
      background-color: var(--white);
      color: white;
      }
      .dataTables_length label {
      color: black !important;
      }
      #dataTablesIncome {
      padding: 13px 11px 0px 16px !important;
      }
      .dataTables_wrapper .dataTables_filter input {
      display: none;
      }
      input:focus {
      background-color: white !important;
      /* Change to your desired background color */
      }
   </style>
   <style>
      th.sorting {
      width: 500px !important;
      }
      body {
      color: black;
      }
      th[data-cell="nameMember"] {
      width: 100px !important;
      }
      .progress {
      background: #b2c9f3 !important;
      }
      .progress-bar {
      background: #0e4591 !important;
      }
      .no-vertical-borders {
      border-collapse: collapse;
      }
      .no-vertical-borders th,
      .no-vertical-borders td {
      border-left: none !important;
      border-right: none !important;
      }
      th.sorting.sorting_asc {
      width: 10px !important;
      }div#datatablesBackupCashinFlow_filter,div#datatablesBackupCashoutFlow_filter{
        color:white;
    }
        </style>
   <style>
      /* Custom CSS to adjust modal width */
      .modal-dialog.add,
      .edit {
      max-width: 800px;
      /* Set the maximum width for the modal */
      }
   </style>
   <style>
      .d-flex button {
      margin-right: 10px;
      /* Add some spacing between buttons */
      }
      .requirements {
      font-size: 14px;
      /* Set the font size to make it smaller */
      color: gray;
      /* Set the text color to gray */
      }
      table {
      border: none !important;
      border-bottom: 10px solid blue;
      }td{
      border:none!important;
      border-bottom:0.1px solid rgb(171, 181, 243)!important;
      }
      table#datatablesBackup{
      width:100%!important;
      }
   </style>
   <!-- START BODY -->
   <body>
      <div class="container-fluid position-relative d-flex p-0">
         <!-- Spinner Start -->
         <div id="spinner"
            class="show bg-primary position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
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
               <div class="">
                  <div class=" row g-3 mx-1 mx-md-3 mx-lg-4 p-md-3 p-lg-3 p-2 ">
                     <div class="col-sm-12 col-xl-8 m-0">
                        <div class="backup-section">
                           <form action="includes/backupSystem/restoreBackup.php" method="post"
                              enctype="multipart/form-data">
                              <div class="form-group row file-input-container">
                                 <label for="backup-file" class="col-sm-12 col-form-label mb-2">Upload Backup
                                 File</label>
                                 <div class="col-12 col-md-8 col-lg-8 px-sm-5">
                                    <label class="custom-file-label" for="backup-file">Choose File</label>
                                    <input type="file" class="custom-file-input" name="backupFile"
                                       id="backup-file">
                                 </div>
                                 <div
                                    class="col-12 col-md-4 col-lg-4 d-flex justify-content-end align-items-center mt-sm-3 mt-md-0 mt-lg-0">
                                    <button type="submit" class="btn btn-sm btn-primary text-nowrap"
                                       id="backup-button">Upload</button>
                                    <button type="button" class="btn btn-sm btn-primary"
                                       id="reset-button">Reset</button>
                                 </div>
                              </div>
                        </div>
                        </form>
                     </div>
                     <div class="col-4 d-flex justify-content-center align-items-center mt-4">
                        <div>
                           <button type="button" class="btn btn-sm btn-primary" data-target="#confirmation-modal"
                              data-toggle="modal" id="create-backup">Create New Backup</button>
                        </div>
                     </div>
                     <!-- end filter row -->
                  </div>
               </div>
            </div>
            <hr class="mx-5">
 
            <div class="container-fluid wrapper">
                
               <div class="row g-2 mx-1 mb-3">
               <div class="div px-5" style="
               position: relative;
               right: 49px;
               ">
               <div class="px-5  w-5  p-1 shadow-lg" style="
                  width: 27%!important;text-align:center;color:white;background:#396a95;border-top-right-radius:5px;border-bottom-right-radius:5px
                  ">
                  Cashinflow Archive
               </div>
            </div>
                  <div class="col-12 rounded-3 px-md-2">
                     <div class="dataTable_wrapper income col-12  px-0 pb-0">
                        <div class="bg-white rounded h-100  mb-0 pb-0  " >
                           <div class="table-responsive shadow-lg mt-4">
                              <table id="datatablesBackupCashinFlow" class="table bg-white">
                                 <thead class="">
                                    <tr class="">
                                       <th>No.</th>
                                       <th>Category</th>
                                       <th>Amount</th>
                                       <th>Remarks</th>
                                       <th id="" style="width:30px!important;">Action</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php 
                                       $increment = 1;
                                       foreach ($result as $row) {
                                            
                                               ?>
                                    <tr>
                                       <td> <?php echo 'TXN-RI-' . str_pad($row['id'], 3, '0', STR_PAD_LEFT); ?></td>
                                       <td><?php echo $row['fundAccTitle']; ?></td>
                                       <td><?php echo formatValuePeso($row['amount']); ?></td>
                                       <td><?php echo $row['remarks']; ?></td>
                                       <td>
                                          <div class="d-flex d-flex justify-content-center">
                                             <a class="btn btn-sm btn-success border-0 px-3 pt-1 m-0 restore-button" data-transac-id="<?php echo $row['id']?>" data-toggle="modal" data-target="#confirmation-restore-modal">
                                             <i class="fa fa-archive"></i>
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
                  <div class="div px-5" style="
               position: relative;
               right: 49px;
               ">
               <div class="px-5  w-5  p-1 shadow-lg" style="
                  width: 27%!important;text-align:center;color:white;background:#396a95;border-top-right-radius:5px;border-bottom-right-radius:5px
                  ">
                  Cashinflow Archive
               </div>
            </div>
                  <div class="col-12 rounded-3 px-md-2">
                     <div class="dataTable_wrapper income col-12  px-0 pb-0">
                        <div class="bg-white rounded h-100  mb-0 pb-0  " >
                           <div class="table-responsive shadow-lg mt-4">
                              <table id="datatablesBackupCashoutFlow" class="table bg-white">
                                 <thead class="">
                                    <tr class="">
                                       <th>No.</th>
                                       <th>Title</th>
                                       <th>Category</th>
                                       <th>Amount</th>
                                       <th>Budget</th>
                                       <th>Remarks</th>
                                       <th id="" style="width:30px!important;">Action</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php 
                                       $increment = 1;
                                       foreach ($resultCashouflow as $row) {
                                            
                                               ?>
                                    <tr>
                                       <td> <?php echo 'TXN-RI-' . str_pad($row['id'], 3, '0', STR_PAD_LEFT); ?></td>
                                       <td><?php echo $row['account_title']; ?></td>
                                       <td><?php echo $row['expense_category']; ?></td>
                                       <td><?php echo formatValuePeso($row['amount']); ?></td>
                                       <td><?php echo formatValuePeso($row['budgetAlloted']); ?></td>
                                       <td><?php echo $row['remarks']; ?></td>
                                       <td>
                                          <div class="d-flex d-flex justify-content-center">
                                             <a class="btn btn-sm btn-success border-0 px-3 pt-1 m-0 restore-buttonExpense" data-transac-id="<?php echo $row['id']?>" data-toggle="modal" data-target="#confirmation-cashoutflow-modal">
                                             <i class="fa fa-archive"></i>
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
         </div>
      </div>
      </div>
      </div>
      </div>
      <!-- Container End -->
      </div>
      <!-- Confirmation Modal -->
      <div class="modal fade" id="confirmation-modal" tabindex="-1" role="dialog"
         aria-labelledby="confirmation-modal-label" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered " role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="confirmation-modal-label">Confirmation</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  Do you want to create a backup file?
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                  <button type="button" class="btn btn-primary" id="confirm-yes-button">Yes</button>
               </div>
            </div>
         </div>
      </div>
      <div class="modal fade" id="confirmation-restore-modal"  tabindex="-1" role="dialog"
         aria-labelledby="confirmation-modal-label" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered " role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="confirmation-modal-label">Confirmation</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body text-center">
                  <form method="POST" action="sqlGetData/restoreArchive.php">
                     <input type="hidden" name="transacId" id="restore_transac_id" value="">
                     Do you want to restore this transction ?
               </div>
               <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
               <button type="submit" name="restoreCashinflow" class="btn btn-primary" >Yes</button>
               
               </div>
                                       </form>
   
            </div>
         </div>
      </div>
      <div class="modal fade" id="confirmation-cashoutflow-modal"  tabindex="-1" role="dialog"
         aria-labelledby="confirmation-modal-label" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered " role="document">
            
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="confirmation-modal-label">Confirmation</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body text-center">
                  <form method="POST" action="sqlGetData/restoreArchive.php">
                     <input type="hidden" name="transacId" id="restore_transac_cashoutflow" value="">
                     Do you want to restore this transction ?
               </div>
               <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
               <button type="submit" name="restoreCashoutflow" class="btn btn-primary" >Yes</button>
               </div>
                  </form>
        
            </div>
         </div>
      </div>

      
      

      <!-- 
         <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="confirmation-modal-label" aria-hidden="true">
           <div class="modal-dialog modal-dialog-centered " role="document">
             <div class="modal-content">
               <div class="modal-header">
                 <h5 class="modal-title" id="confirmation-modal-label">Confirmation</h5>
                   <span aria-hidden="true">&times;</span>
                 </button>
               </div>
               <div class="modal-body">
              Backup File Created Successlly!
               </div>
         
               </div>
             </div>
           </div>
         </div> -->
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
   <script>
      function showSuccessModal(successMessage) {
      var successModal = new bootstrap.Modal(document.getElementById("successModal"));
      var successMessageElement = document.querySelector("#successModal .modal-body p");
      successMessageElement.textContent = successMessage;
      successModal.show();
      var closeButton = document.querySelector("#successModal .btn-secondary");
       closeButton.addEventListener("click", closeModal);
      }
      $(document).ready(function() {
      $("#reset-button").click(function() {
      // Perform an AJAX request to the PHP script
      $.ajax({
         url: "includes/backupSystem/reset_tables.php", // Change this to the actual path of your PHP script
         type: "POST",
         data: {
             action: "reset"
         }, // You can send any additional data if needed
         success: function(response) {
            showSuccessModal(response);
         },
         error: function(xhr, status, error) {
             // Handle errors here
             console.error(xhr.responseText);
         }
      });
      });
      });
   </script>
   <script>
  
      
  $(document).ready(function() {
       $('.restore-buttonExpense').on("click", function () {
           var transacId = $(this).data("transac-id");
       
           $('#restore_transac_cashoutflow').val(transacId);
       });
      });

      $(document).ready(function() {
       $('.restore-button').on("click", function () {
           var transacId = $(this).data("transac-id");
           $('#restore_transac_id').val(transacId);
       });
      });
      
      
   </script>
    
   <script>
      $(document).ready(function() {
          // Get the backup file input element
          const backupFileInput = $("#backup-file");
      
          // Get the backup and reset buttons
          const backupButton = $("#backup-button");
          const resetButton = $("#reset-button");
          const createBackupButton = $('#create-backup');
          const confirmbutton = $('#confirm-yes-button');
      
          // Add an event listener to the backup button
      
      
          // Add an event listener to the reset button (if needed)
          resetButton.click(function() {
              // Reset any form fields or UI elements as needed
          });
      
      
      
          confirmbutton.click(function() {
              // Change the page location
              window.location.href = "includes/backupSystem/create_backup.php";
      
              setTimeout(function() {
                  $(".btn-secondary").click();
              }, 100);
      
          });
      
      
          const fileInput = document.getElementById("backup-file");
          const label = document.querySelector('.custom-file-label');
      
          // Listen for the change event on the file input element
          fileInput.addEventListener("change", function() {
              // Get the selected file name
              const fileName = this.files[0] ? this.files[0].name : "Choose File";
      
              // Update the label text with the selected file name
              label.textContent = fileName;
          });
      
      
      });
   </script>
</html>