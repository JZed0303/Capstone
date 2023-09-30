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
    $count =0;
    $totalExpenses = 0; // Initialize the total expenses variable

        if(isset($_POST['searchFilter'])){

         $startDate = $_POST['start_date'];
         $end = $_POST['end_date'];
     
      $prevChildAccountName = null;
      $sql = "SELECT
      coa.child_account_number AS 'Account No.',
      coa.child_account_name AS 'Child Account Name',
      cat.expense_name AS 'Expense Category',
      cat.expense_child_number AS 'Expense Child Number',
      COALESCE(SUM(CASE WHEN cat.expense_child_number = elt.expense_child_number THEN elt.amount ELSE 0 END), 0) AS 'Total Expenses ($)',
      cof.date AS 'Date'
      FROM
      chart_of_accounts AS coa
      LEFT JOIN
      expense_list_tbl AS elt ON coa.child_account_number = elt.expense_parent_number
      LEFT JOIN
      expense_category AS cat ON coa.child_account_number = cat.expense_parent_number
      LEFT JOIN
      cashoutflow_tbl AS cof ON elt.transacId = cof.id
      WHERE
      coa.child_account_number > 5000 AND coa.child_account_number < 5999 AND  cof.status =0
     
      GROUP BY
      coa.child_account_number, coa.child_account_name, cat.expense_name, cat.expense_child_number
      ORDER BY
      coa.child_account_number,cat.expense_child_number, coa.child_account_name, cat.expense_name;
      ";
    
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':startDate', $startDate, PDO::PARAM_STR);
    $stmt->bindParam(':endDate', $end, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->rowCount();
          
    }else if(isset($_POST['searchAll'])){

      $startDate = $_POST['start_date'];
      $end = $_POST['end_date'];
  
   $prevChildAccountName = null;
   $sql = "SELECT
   coa.child_account_number AS 'Account No.',
   coa.child_account_name AS 'Child Account Name',
   cat.expense_name AS 'Expense Category',
   cat.expense_child_number AS 'Expense Child Number',
   COALESCE(SUM(CASE WHEN cat.expense_child_number = elt.expense_child_number THEN elt.amount ELSE 0 END), 0) AS 'Total Expenses ($)',
     cof.date AS 'Date'
FROM
   chart_of_accounts AS coa
LEFT JOIN
   expense_list_tbl AS elt ON coa.child_account_number = elt.expense_parent_number
LEFT JOIN
   expense_category AS cat ON coa.child_account_number = cat.expense_parent_number
   LEFT JOIN
cashoutflow_tbl AS cof ON elt.transacId = cof.id
WHERE
   coa.child_account_number > 5000 AND coa.child_account_number < 5999 AND elt.status=
GROUP BY
   coa.child_account_number, coa.child_account_name, cat.expense_name, cat.expense_child_number
ORDER BY
   coa.child_account_number,cat.expense_child_number, coa.child_account_name, cat.expense_name 


;
   ";
 
 
 $stmt = $conn->prepare($sql);

 $stmt->execute();
 $count = $stmt->rowCount();

}else if(isset($_POST['resetSearch'])){
   $count = 0;

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
      <link rel="stylesheet" href="assets/css/cashinflow/cashInflow.css">
      <!-- DATA TABLES  cs-->
      <!-- <link rel="stylesheet" href="assets/datatable/css/datatables.min.css"> -->
      <!-- <link rel="stylesheet" href="assets/datatable/css/dataTables.dateTime.min.css"> -->
      <link rel="stylesheet" href="assets/datatable/css/bootstrap.min.css">
      <script src="assets/datatable/js/jquery-3.6.0.min.js"></script>
      <link rel="stylesheet" href="assets/css/bootstrap.min.css.map">

      <!-- media Print -->
      <!-- <link rel="stylesheet" href="assets/css/mediaPrintIncome.css"> -->
      <!-- DATA TABLE JS -->
      <!-- <script src="assets/datatable/css/bootstrap.bundle.min.js"></script> -->
      <script src="assets/datatable/js/datatables.min.js"></script>   
      <!-- <script src="assets/datatable/js/vfs_fonts.js"></script> -->
      <!-- DATA TABLES DATE FILTER -->
      <script src="assets/js/bootstrap.min.js"></script>
      <script src="assets/datatable/js/dataTables.dateTime.min.js"></script>
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
      }table{
         border:collapse;
         border-color:gray;
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
      border: 0.5px solid var(--dark);
      padding: 8px;
      }
      th {
      text-align: left;
      background-color:white !important;
      color:black;
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
      .half-width-td {
      width: 1%; /* Set a small initial width */
      white-space: nowrap; /* Prevent text from wrapping */   
      }.header-2{
      background:#005daa !important;
      color:white!important;
      font-weight:bolder!important;
      }.header-1{
      background:var(--darkblue) !important;
      font-weight:bolder;
      color:white!important;
      }
   </style>
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
            <div class="container-fluid pt-3 px-md-3 px-lg-4 px-3 ">
               <div class="filter">
                  <div class=" row  mt-1">
                     <span class="px-4 pt-2"><strong><i class="fa-solid fa-filter me-1"></i>Filter</strong></span>
                  </div>
                  <div class="row g-3 mx-1 mx-md-12 mx-lg-4 p-md-3 p-lg-3 p-2">
    <div class="col-sm-12 col-md-6 col-xl-12 d-flex justify-content-center">
        <div class="container">
            <form method="POST" class="d-flex justify-content-between align-items-center">
            <div class="d-flex justify-content-between align-items-center">
            <div class="form-group me-5">
                    <label for="start_date">Start Date:</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo isset($_POST['start_date']) ? $_POST['start_date'] : ''; ?>">                </div>
                <div class="form-group">
                    <label for="end_date">End Date:</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo isset($_POST['end_date']) ? $_POST['end_date'] : ''; ?>">
                </div>
            </div>  
           
      </div>
      <div class="d-flex align-items-center">
      <div class="form-group mx-3">
                    <button type="submit" style="white-space:nowrap;"  name="searchAll" class="btn btn-sm btn-primary w-100">Show All</button>
                </div>
                <div class="form-group">
                    <button type="submit" name="searchFilter" class="btn btn-sm btn-primary">Filter</button>
                </div>
                <div class="form-group">
            <button type="submit" class="btn btn-sm btn-secondary" name="resetSearch" id="resetButton">Reset</button>
            
        </div>
      </div>
            </form>

        </div>
    </div>
</div>

               </div>
               <div class="container-fluid wrapper px-3">
                  <div class="row g-2 mx-1 mb-3">
                     <div class="col-12 rounded-3 px-md-4">
                        <div class="dataTable_wrapper income col-12 pt-3 px-0 pb-0 px-4">
                           <div class="bg-white rounded h-100  mb-0 pb-0  " >
                              <?php 
                              
                              if($count === 0){
                                 ?>

<table id="expensesTable" class="lg-shadow">
                                 <thead>
                                 <hr   >
                                    <div class="text-center p-2" style="background: #034983;color: white;font-weight: bold;">
                                   
                                       ROTARACT CLUB OF CARMONA <br>
                                       STATEMENT OF FUNCTIONAL EXPENSES
                                     
                                    </div>
                                    <tr>
                                       <th colspan="2">Account No.</th>
                                       <th >Expense Category
                                       <th style="width: 1%;">Total Expense</th>
                                       </th >
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php 

                                       ?>
                                 </tbody>
                                 <div>
                                 </div>
                                 <tfoot class="">
                                    <tr class="text-right" style="border-top: 4px solid #101830;">
                                       <th class="text-center" style="background-color:gray!important; color:white;" colspan="4">Search with Date Filter or Show All Data..</th>
                                    </tr>
                                 </tfoot>
                              </table>         
                              <hr>                        
                                 <?php 
                              
                              
                              }else{

                              ?>
<?php

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
?>
                              
                                 <table id="expensesTable" class="lg-shadow">
                                 <thead>
                                 <hr>
                                 <div class="text-center p-3" style="border-top-left-radius: 5px;border-top-right-radius: 5px;background: #034983;color: white;font-weight: bold;">
    
    ROTARACT CLUB OF CARMONA <br>
    STATEMENT OF FUNCTIONAL EXPENSES
    <?php if (isset($_POST['start_date']) && isset($_POST['end_date'])): ?>
        <div class="dateDiv">
        <?php echo $start_date_in_words; ?> <span id="to"></span> <?php echo $end_date_in_words; ?>        </div>
    <?php endif; ?>
</div>

                                    <tr>
                                       <th colspan="2">Account No.</th>
                                       <th >Expense Category
                                       <th style="width: 1%;">Total Expense</th>
                                       </th >
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php 
                                 
                                           while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                       echo '<tr>';
                                       
                                       // Check if the current "Child Account Name" is different from the previous one
                                       if ($row["Child Account Name"] !== $prevChildAccountName) {
                                       echo '<td class="header-1" style="width: 10px;
                                       " >  ' . $row["Account No."] . '</td>';
                                       echo '<td class="header-2" style="font-weight:bolder"; colspan="3">' . $row["Child Account Name"] . '</td>';
                                       } else {
                                       // If it's the same as the previous, just print empty columns
                                       
                                       
                                       }
                                       
                                       echo '</tr>';
                                       echo '<tr>';
                                       echo '<td class=""></td>';
                                       echo '<td class="" style="width: 6%;text-align:right;color:black;"' . ($row["Expense Child Number"] ? 'half-width-td' : 'half-width-td bg-secondary') . '">' . ($row["Expense Child Number"] ? $row["Expense Child Number"] : 'N/A') . '</td>';
                                       echo '<td class="' . ($row["Expense Category"] ? '' : 'bg-secondary') . '">' . ($row["Expense Category"] ? $row["Expense Category"] : 'N/A') . '</td>';
                                       $totalExpenses += $row["Total Expenses ($)"];
                                       echo '<td class="text-right">' . formatValuePeso($row["Total Expenses ($)"]) . '</td>';
                                       echo '</tr>';
                                       
                                       
                                       // Update the previous "Child Account Name" for the next iteration
                                       $prevChildAccountName = $row["Child Account Name"];
                                       }
                                       ?>
                                 </tbody>
                                 <div>
                                 </div>
                                 <tfoot class="">
                                    <tr class="text-right" style="border-top: 4px solid #101830;">
                                       <th style="background-color:#034983!important; color:white;" colspan="3">Grand Total:</th>
                                       <th  style="text-align:right;background-color:#034983!important; color:white;"><?php echo formatValuePeso($totalExpenses); ?></th>
                                    </tr>
                                 </tfoot>
                              </table>
                              <?php } ?>
                             
                            
                            
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
 
      <!-- Modal for Delete Confirmation End -->
      <!-- Content End -->
      <!-- Back to Top -->
      <!-- <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top" style="z-index:9"><i class="fa fa-arrow-up"></i></a> -->
      </div>
      <!-- charts -->
      <script>
$(document).ready(function() {
    $("#resetButton").click(function() {
        // Reset the date fields
        $("#start_date").val("");
        $("#end_date").val("");
    });
});
</script>
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
</html>