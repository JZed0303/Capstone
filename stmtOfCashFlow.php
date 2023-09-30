<?php
   session_start();
   if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
       // Redirect the user to the login page
       header('Location: index.php');
       exit();
   } else {
       include 'sqlGetData/formatValuePeso.php';
       require 'includes/dbh.inc.php';
   
       $email = $_SESSION['email'];
   
       $role =  $_SESSION['role'];
       $id = $_SESSION['id'];
   
       // Output the value of $role in a script tag
       echo "<script>var role = '" . $role . "';</script>";
   
       // Retrieve data from the database using PDO
       $sql = "SELECT user_id, name, email, role, password FROM user_tbl";
       $stmt = $conn->prepare($sql);
       $stmt->execute();
       $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
   }
   $totalExpenses = 0;
       $totalCashin = 0;
   
   if (isset($_POST['searchFilter'])) {
       $startDate = $_POST['start_date'];
       $end = $_POST['end_date'];
   
       // SQL query for cashin
       $sqlCashin = "SELECT
           coa.child_account_name AS 'Child Account Name',
           COALESCE(SUM(CASE WHEN coa.child_account_number = cif.account_number THEN cif.amount ELSE 0 END), 0) AS 'Total Income',
           cif.date AS 'Date'
           FROM chart_of_accounts AS coa
           LEFT OUTER JOIN cashinflow_tbl AS cif ON coa.child_account_number = cif.account_number
           WHERE coa.child_account_number > 4000 AND coa.child_account_number < 4999 AND cif.status=0
           AND cif.date BETWEEN :startDate AND :endDate
           GROUP BY coa.child_account_number, coa.child_account_name
           ORDER BY coa.child_account_number, coa.child_account_name;";
   
       // SQL query for expenses
       $sqlExpenses = "SELECT
           coa.child_account_number AS 'Account No.',
           coa.child_account_name AS 'Child Account Name',
           COALESCE(SUM(CASE WHEN coa.child_account_number = elt.expense_parent_number THEN elt.amount ELSE 0 END), 0) AS 'Total Expenses',
           cof.date AS 'Date'
           FROM chart_of_accounts AS coa
           LEFT OUTER JOIN expense_list_tbl AS elt ON coa.child_account_number = elt.expense_parent_number
           LEFT OUTER JOIN cashoutflow_tbl AS cof ON elt.transacId = cof.id
           WHERE coa.child_account_number > 5000 AND coa.child_account_number < 5999 AND cof.status=0
           AND cof.date BETWEEN :startDate AND :endDateive=0
           AND cof.date BETWEEN :startDate AND :endDate
           GROUP BY coa.child_account_number, coa.child_account_name
           ORDER BY coa.child_account_number, coa.child_account_name;";
   
       // SQL query to retrieve initial balance
       $sqlInitialBalance = "SELECT amount FROM rise.cashinflow_tbl
           WHERE id = (SELECT MIN(id) FROM rise.cashinflow_tbl)";
   
       // Execute the query for initial balance
       $stmt = $conn->prepare($sqlInitialBalance);
       $stmt->execute();
       $row = $stmt->fetch(PDO::FETCH_ASSOC);
   
       if ($row) {
           $initialBalance = $row['amount']; // Save the 'amount' in a variable
       } else {
           echo "No data found.";
       }
   
       // Prepare the SQL statement for cashin
       $stmtCashin = $conn->prepare($sqlCashin);
       $stmtCashin->bindParam(':startDate', $startDate);
       $stmtCashin->bindParam(':endDate', $end);
       $stmtCashin->execute();
       $rowsCashin = $stmtCashin->fetchAll(PDO::FETCH_ASSOC);
   
       // Prepare the SQL statement for expenses
       $stmtExpenses = $conn->prepare($sqlExpenses);
       $stmtExpenses->bindParam(':startDate', $startDate);
       $stmtExpenses->bindParam(':endDate', $end);
       $stmtExpenses->execute();
       $rows = $stmtExpenses->fetchAll(PDO::FETCH_ASSOC);
   
       // Initialize variables
       
      function dateToWords($dateString) {
         // Months in words
         $months = [
             '01' => 'January', '02' => 'February', '03' => 'March',
             '04' => 'April',   '05' => 'May',      '06' => 'June',
             '07' => 'July',    '08' => 'August',   '09' => 'September',
             '10' => 'October', '11' => 'November', '12' => 'December'
         ];
      
         // Check if a date string is provided
         if ($dateString) {
             // Split the date string into parts
             list($year, $month, $day) = explode('-', $dateString);
      
             // Convert month to word
             $monthInWords = $months[$month];
      
             // Format the date in words
             $dateInWords = $monthInWords . ' ' . $day . ', ' . $year;
      
             return $dateInWords;
         } else {
             // Handle the case when no date string is provided
             return '';
         }
      }
      // Example usage with your date variables
      $start_date_in_words = dateToWords($_POST['start_date']);
      $end_date_in_words = dateToWords($_POST['end_date']);
       
      }
   
   
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>Statement Of CashFlow</title>
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
      <link rel="stylesheet" href="mediaPrint/print.css" type="text/css" media="print" >
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
      }.table{
      color:white!important;
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
      } 
   <style>
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
      border: 0.5px solid var(--dark)!import;
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
      color: var(--deam);
      }
      table {
      margin-bottom: 20px;
      }td.text-right{
      font-weight:bolder!important;
      }@media print {
      thead {
      background-color: #005daa!important;
      }.table-responsive{
      background:white!important; 
      }
      /* Rest of your print-specific styles go here */
      }td{
      border:none!important;
      border-bottom:0.5px solid rgb(171, 181, 243)!important;
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
                  <div class="container">
                     <div class="row g-3 p-md-3 p-lg-3 p-2">
                        <div class="col-12 col-md-6 col-lg-12">
                           <form method="POST" class="d-flex flex-wrap justify-content-between align-items-center">
                              <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
                                 <div class="form-group me-md-5 mb-2 mx-3">
                                    <label for="start_date">Start Date:</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo isset($_POST['start_date']) ? $_POST['start_date'] : ''; ?>">
                                 </div>
                                 <div class="form-group mb-2">
                                    <label for="end_date">End Date:</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo isset($_POST['end_date']) ? $_POST['end_date'] : ''; ?>">
                                 </div>
                              </div>
                              <div class="d-flex justify-content-center align-items-center mb-2">
                                 <div class="form-group me-md-3 mx-2 mb-2">
                                 </div>
                                 <div class="form-group me-md-3 mx-2  mb-2">
                                    <button type="submit" name="searchFilter" class="btn btn-sm btn-primary w-100">Filter</button>
                                 </div>
                                 <div class="form-group mb-2">
                                    <button type="submit" class="btn btn-sm btn-dark w-100" name="resetSearch" id="resetButton">Reset</button>
                                 </div>
                                 <div class="form-group mx-2 mb-2">
                                    <button type="button" class="btn btn-sm btn-secondary w-100" id="printButton">Print</button>
                                 </div>
                                 <div class="form-group mb-2">
                                    <button type="button" class="btn btn-sm btn-success w-100" id="excelExportButton">Excel</button>
                                 </div>
                              </div>
                           </form>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <hr>
            <div class="container-fluid wrapper">
               <div class="row g-2 mx-1 mb-3">
                  <div class="wrapper col-12 rounded-3 px-md-2">
                     <div class="dataTable_wrapper income col-12 pt-3 px-0 pb-0 px-4">
                        <div class="bg-white rounded h-100  mb-0 pb-0  " >
                           <div class="">
                              <?php 
                                 if(isset($_POST['searchFilter'])){
                                 ?>
                              <div class="table-print">
                                 <div class="headerGroup d-none m-0 p-0">
                                    <div class="header-block m-0 p-0 ">
                                       <img class = "m-0 p-0" src="assets/img/header.png" alt="">
                                    </div>
                                 </div>
                                 <div class=" bg-white">
                                    <div class=" bg-white">
                                       <table class="table-print-container">
                                       <thead class="print-container  d-none">
                                          <tr>
                                             <td class="p-0 m-0" style="border: none!important;">
                                                <div class="header-space"><img class ="p-0 m-0" src="assets/img/header.png" alt=""></div>
                                             </td>
                                          </tr>
                                       </thead>
                                       <tbody>
                                          <tr>
                                             <td class="print-container p-0 " style="border:none!important;">
                                                <div class="text-center p-3" style="border-top-left-radius: 5px;border-top-right-radius: 5px;color: #34363a;font-weight: bold;">
                                                   ROTARACT CLUB OF CARMONA <br>
                                                   STATEMENT OF CASHFLOW
                                                   <?php if (isset($_POST['start_date']) && isset($_POST['end_date'])): ?>
                                                   <div class="dateDiv">
                                                      <?php echo $start_date_in_words; ?> <span id="to">-</span> <?php echo $end_date_in_words; ?>        
                                                   </div>
                                                   <?php endif; ?>
                                                </div>
                                                <table>
                                                   <tr>
                                                   <thead>
                                                      <tr>
                                                         <th></th>
                                                         <th>Cash Inflows</th>
                                                         <th>Cash Outflows</th>
                                                      </tr>
                                                   </thead>
                                                   <tbody>
                                                      <tr>
                                                         <td colspan="1">Operating Activities</td>
                                                         <td colspan="1"></td>
                                                         <td></td>
                                                      </tr>
                                                      <?php 
                                                         if (isset($rowsCashin)) {
                                                         foreach ($rowsCashin as $row) {
                                                           echo "<tr>";
                                                           echo "<td>" . $row['Child Account Name'] . "</td>";
                                                           echo "<td class='text-right'>" . formatValuePeso($row['Total Income']) . "</td>";
                                                           echo '<td></td>';
                                                           $totalCashin += $row['Total Income'];
                                                         }
                                                         }
                                                         ?>    
                                                      <tr>
                                                      <tr>
                                                         <td>Total Operating Activities   </td>
                                                         <?php echo "<td class='text-right'>" .  formatValuePeso($totalCashin) . "</td>";?>
                                                         <td></td>
                                                      </tr>
                                                      <tr>
                                                         <td colspan="3" style="background: #034983;color:white;font-weight:600;" >Financial Activities</td>
                                                      </tr>
                                                      <tr>
                                                         <?php 
                                                            if (isset($rows)) {
                                                                  foreach ($rows as $row) {
                                                                      echo "<tr>";
                                                                      echo "<td>" . $row['Child Account Name'] . "</td>";
                                                                      echo '<td></td>';
                                                                      echo "<td class='text-right'>" . formatValuePeso($row['Total Expenses']). "</td>"; 
                                                                      $totalExpenses += $row['Total Expenses'];
                                                                  }
                                                               }
                                                              ?>         
                                                      </tr>
                                                      <tr>
                                                         <td>Total Financing Activities</td>
                                                         <td></td>
                                                         <?php 
                                                            if(isset($_POST['searchFilter'])){
                                                             echo "<td class='text-right'>" . formatValuePeso($totalExpenses) . "</td>";
                                                              }
                                                            ?>
                                                      </tr>
                                                      <tr>
                                                         <td colspan="">Cash and Cash Equivalents, Beginning of Period</td>
                                                         <?php 
                                                            if(isset($_POST['searchFilter'])){
                                                            echo "<td class='text-right'>" . formatValuePeso($initialBalance). "</td>"; 
                                                            }
                                                            ?>
                                                         <td></td>
                                                      </tr>
                                                      <tr style="border-top: 4px solid #101830;">
                                                         <td colspan="2" style="font-weight:bolder;background-color:#034983!important;color:white">Cash and Cash Equivalents, End of Period</td>
                                                         <?php 
                                                            if (isset($_POST['searchFilter'])) {
                                                            echo "<td class='text-right' style='font-weight:bolder;;background-color:#034983!important;color:white;'>" . formatValuePeso((($totalCashin - $totalExpenses) + $initialBalance)) . 
                                                            "</td>";}
                                                            
                                                            ?>
                                                      </tr>
                                                   </tbody>
                                                </table>
                                    </div>
                                 </div>
                              </div>
                              <?php 
                                 }else{
                                    
                                     ?>
                           </div>
                           <div class="bg-white">
                           <table>
                           <thead>
                           <hr   >
                           <div class="text-center p-2" style="background: #034983;color: white;font-weight: bold;">
                           ROTARACT CLUB OF CARMONA <br>
                           STATEMENT OF CASHFLOW
                           </div>
                           <tr>
                           <th class="text-center no-table" style="background-color: gray!important;
                              color: white;
                              border: none;" colspan="4">Search with Date Filter or Show All Data..</th>
                           </th >
                           </tr>
                           </thead>
                           </table>
                           </div>
                           <?php 
                              }
                              ?>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Container End -->
      </div>
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
      $(document).ready(function() {
          $("#resetButton").click(function() {
              // Reset the date fields
              $("#start_date").val("");
              $("#end_date").val("");
          });
      });
   </script>
   <script>
      // Get a reference to the print button by its id
      var printButton = document.getElementById("printButton");
      
      // Add a click event listener to the button
      printButton.addEventListener("click", function () {
          // Use the window.print() method to trigger the print dialog
          window.print();
      });
   </script>
   <script>
      document.addEventListener("DOMContentLoaded", function () {
          // Get the "Print" button element by its ID
          var printButton = document.getElementById("printButton");
      
          // Get the start date and end date input elements by their IDs
          var startDateInput = document.getElementById("start_date");
          var endDateInput = document.getElementById("end_date");
      
          // Function to check if start date and end date are empty
          function areDatesEmpty() {
              return startDateInput.value === "" || endDateInput.value === "";
          }
      
          // Disable the "Print" button initially if dates are empty
          if (areDatesEmpty()) {
              printButton.disabled = true;
          }
      
          // Add event listeners to start date and end date inputs to enable/disable the "Print" button
          startDateInput.addEventListener("input", function () {
              printButton.disabled = areDatesEmpty();
          });
      
          endDateInput.addEventListener("input", function () {
              printButton.disabled = areDatesEmpty();
          });
      });
   </script>
   <!-- js -->
</html>