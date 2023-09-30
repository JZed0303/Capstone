<?php
   session_start();
   
   include 'sqlGetData/formatValuePeso.php';   
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
      $sql = "SELECT * FROM log_list ORDER BY date_time DESC";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
   
   // Fetch data from the audit_trail table
   $sql = "SELECT * FROM audit_trail ORDER BY id DESC";
   $stmt = $conn->prepare($sql);
   $stmt->execute();
   $auditTrailResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
   
    }
    
      
    
    
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>Manage Accounts</title>
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
      <script src="assets/datatable/js/activityLogs.js"></script>
      <script src="assets/js/script.js"></script>
      <!-- Google Web Fonts -->
      <link href="css/bootstrap.min.css" rel="stylesheet">
      <link href="css/style.css" rel="stylesheet">
      <!-- general -->
   </head>
   <style>
      @media (max-width:  550px) {
      body,table,tr,th{
      font-size:1rem;
      }
      }
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
      }.dataTables_length label{
      color:black!important;
      }#dataTablesIncome{
      padding: 13px 11px 0px 16px!important;
      }.dataTables_wrapper .dataTables_filter input{
      display:none;
      }tr,td,th{
      white-space:nowrap;
      }
   </style>
   <style>
      /* Custom CSS to adjust modal width */
      .modal-dialog.add, .edit {
      max-width: 800px; /* Set the maximum width for the modal */
      }
   </style>
   <style>
      .table-responsive{
      border-radius:10px!important;
      }
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
      }
      @media (max-width: 550px) {
      table {
      font-size: 1rem; /* Adjust font size */
      }.table-responsive{
      border-radius:10px!important;
      }
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
               <div class="container-fluid wrapper">
                  <div class="row  border border-0 ">
                  <div class="dataTable_wrapper col-12  px-0 pb-0 "style="position:relative;top:-2x;">
                        <div class=" bg-white border-0 p-2 p-md-2 mb-3">
                           <div class="card  d-flex justify-content-center shadow-lg" style="background:#607D8B;">
                              <h5 class=" mt-1 text-center ">Audti Trail</h5 >
                           </div>
                           <div class="table-responsive px-3 mt-3   ">
                           <div class="d-flex col-12 px-5 "> 
                           <div class="form-group col-md-6">
    <label for="startDate">Start Date:</label>
    <input type="date" class="form-control form-control-sm" id="startDate" name="startDate" placeholder="Enter the start date...">
</div>

<div class="form-group col-md-6">
    <label for="endDate">End Date:</label>
    <input type="date" class="form-control form-control-sm" id="endDate" name="endDate" placeholder="Enter the end date...">
</div>

                           </div>
                           <hr class="bg-primary">
                           <table id="auditTrail" class="table shadow-lg pr-2 px-3 pb-3 pt-3 pl-2 bg-white w-100 wrap hover dataTable no-footer">
        <thead>
            <tr>
                <th>TransacID</th>
                <th>Date</th>
                <th>CATEGORY</th>
                <th>AMOUNT</th>
                <th>TYPE</th>
                <th>Action</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $increment = 1;
            foreach ($auditTrailResult as $row) {
            ?>
         
                <tr>
                <td><?php echo  'TXN-RI-' . str_pad($row['transacId'], 3, '0', STR_PAD_LEFT);'' ?></td>
                    <td><?php echo $row['date'] ?></td>
                
                    <td><?php echo $row['category'] ?></td>
                    <td><?php echo  formatValuePeso($row['amount']); ?></td>
                    <td><?php echo $row['type'] ?></td>
                    <td><?php echo $row['action'] ?></td>
                    <td><?php echo $row['role'] ?></td>
                </tr>
                <?php $increment++; ?>
            <?php } ?>
        </tbody>
    </table>
                           </div>
                           <div class="">
                              <hr class="bg-primary p-1">
                           </div>
                        </div>
                     </div>
 
                     <div class="dataTable_wrapper col-12  px-0 pb-0 "style="position:relative;top:-2x;">
                        <div class=" bg-white border-0 p-2 p-md-2 mb-3">
                           <div class="card  d-flex justify-content-center shadow-lg" style="background:#607D8B;">
                              <h5 class=" mt-1 text-center ">Log List</h5 >
                           </div>
                           <div class="table-responsive px-3 mt-3 shadow-lg  ">
                           <div class="d-flex col-12 px-5 "> 
                           <div class="form-group col-md-6">
    <label for="startDateLog">Start Date:</label>
    <input type="date" class="form-control form-control-sm" id="startDateLog" name="startDateLog" placeholder="Enter the start date...">
</div>

<div class="form-group col-md-6">
    <label for="endDateLog">End Date:</label>
    <input type="date" class="form-control form-control-sm" id="endDateLog" name="endDateLog" placeholder="Enter the end date...">
</div>

                           </div>
                           <hr class="bg-primary">
                              <table id="logList" class="table mx- shadow-lg pr-2 px-3 pb-3 pt-3 pl-2 bg-white w-100 wrap hover ">
                                 <thead>
                                    <tr>
                                       <th style="display:none;" id="">ID</th>
                                       <th>Date and Time</th>
                                       <th>Email</th>
                                       <th>Role</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php
                                       $increment = 1;
                                       foreach ($result as $row) {
                                       ?>
                                    <tr>
                                       <td style="display:none;background-color:red;"><?php echo $row['ID'] ?></td>
                                       <td><?php echo $row['date_time'] ?></td>
                                       <td><?php echo $row['email'] ?></td>
                                       <td><?php echo $row['role'] ?></td>
                                    </tr>
                                    <?php $increment++; ?>
                                    <?php } ?>
                                 </tbody>
                              </table>
                           <div class="">
                              <hr class="bg-primary p-1">
                           </div>
                        </div>
                     </div>
                     <!-- end -->
                  </div>
               </div>
            </div>
         </div>
      </div>
      </div>
      </div>
      </section>
      <?php
         include 'template/modal.php';
         ?>
      <script>
         function clearErrorUrl() {
               var url = window.location.href;
               var errorParamIndex = url.indexOf("?error=");
               if (errorParamIndex !== -1) {
                  var newUrl = url.substring(0, errorParamIndex);
                  window.location.href = newUrl;
               }
            }
            function clearSuccessUrl() {
               var url = window.location.href;
               var successParamIndex = url.indexOf("?success=");
               if (successParamIndex !== -1) {
                  var newUrl = url.substring(0, successParamIndex);
                  window.location.href = newUrl;
               }
            }       function clearDeleteUrl() {
               var url = window.location.href;
               var deleteParamIndex = url.indexOf("?delete=");
               if (deleteParamIndex !== -1) {
                  var newUrl = url.substring(0, deleteParamIndex);
                  window.location.href = newUrl;
               }
            }
         
            $('#data-tables').resize(function() {
                 table.columns.adjust().draw();
                 console.log("expand");
             });
            
      </script>    
      </div>
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
      $(document).ready(function () {
      var showButtons;
      
      // Check the value of the `role` property of the `window` object to determine button visibility
      if (window.role == "guest") {
        showButtons = false;
      } else {
        showButtons = true;
      }
      
      const urlParams = new URLSearchParams(window.location.search);
      const sortParam = urlParams.get('sort');
      
      var table = $("#logList").DataTable({
       
        "order": [[0, "desc"]],
        "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        "order": [[0, "desc"]], // Sort by column index 0 (ID) in ascending order by default
        dom: '<"d-flex justify-content-between mb-0 align-items-center"l' + (showButtons ? '<"filter-buttons">' : '') + '>rtip',
      
      });

      // Add event listener for date inputs
$('#startDateLog, #endDateLog').on('change', function () {
    var startDate = $('#startDateLog').val();
    var endDate = $('#endDateLog').val();

    // Apply date range filtering
    table.column(1).search(startDate, false, true).draw();
    table.column(1).search(endDate, false, true).draw();
});
      });
      
   </script>
   </script>
   <!-- js -->
</html>
