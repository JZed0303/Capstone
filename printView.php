<?php


   session_start();
   
   if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Redirect the user to the login page
    header('Location: index.php');
    exit();
   }
   
      require 'includes/dbh.inc.php';
   
   
   $role =  $_SESSION['role'];
   
   // Output the value of $role in a script tag
   echo "<script>var role = '" . $role . "';</script>";
   
   $sql = "SELECT * FROM cashinflow_tbl";
   $stmt = $conn->query($sql);
   $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
   
   
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>Manage Income</title>
      <meta content="width=device-width, initial-scale=1.0" name="viewport">
      <meta content="" name="keywords">
      <meta content="" name="description">
      <!-- css content -->
      <link rel="stylesheet" href="assets/fontawesome-free-6.3.0-web/css/all.css">
      <link rel="stylesheet" href="assets/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/css/budgetManagement.css">
      <!-- DATA TABLES  cs-->
      <link rel="stylesheet" href="assets/datatable/css/datatables.min.css">
      <link rel="stylesheet" href="assets/datatable/css/dataTables.dateTime.min.css">
      <link rel="stylesheet" href="assets/datatable/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/datatable/css/jquery.dataTables.min.css">
      <link rel="stylesheet" href="assets/css/cashinflow/cashInflow.css">
      <link rel="stylesheet" href="assets/datatable/js/cdn.datatables.net_buttons_2.4.1_css_buttons.dataTables.min.css">
      <!-- media Print -->
      <link rel="stylesheet" href="assets/css/mediaPrintIncome.css">
      <!-- DATA TABLE JS -->
      <script src="assets/datatable/js/jquery-3.6.0.min.js"></script>
      <script src="assets/datatable/css/bootstrap.bundle.min.js"></script>
      <script src="assets/datatable/js/datatables.min.js"></script>   
      <script src = "assets/datatable/js/cdnjs.cloudflare.com_ajax_libs_pdfmake_0.1.53_pdfmake.min.js"></script>
      <script src = "assets/datatable/js/cdnjs.cloudflare.com_ajax_libs_pdfmake_0.1.53_vfs_fonts.js"></script>
      <script src="assets/datatable/js/vfs_fonts.js"></script>
      <!-- DATA TABLES DATE FILTER -->
      <script src="assets/datatable/js/moment.min.js"></script>
      <script src="assets/datatable/js/dataTables.dateTime.min.js"></script>
      <script src="assets/datatable/js/script.js"></script>
      <!-- general -->
      <script src="assets/js/popper.min.js"></script>
      <script src="assets/js/bootstrap.min.js"></script>
      <script src="assets/js/moment.min.js"></script>
      <script src="assets/js/select2.min.js"></script>
      <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>

      <!-- fixed header -->
      <link rel="stylesheet" href="assets/datatable/css/datatables.net_release-datatables_extensions_FixedHeader_css_fixedHeader.dataTables.css">
      <script src="assets/datatable/js/datatables.net_release-datatables_extensions_FixedHeader_js_dataTables.fixedHeader.js"></script>
      <!-- Google Web Fonts -->
      <link href="css/bootstrap.min.css" rel="stylesheet">
      <link href="css/style.css" rel="stylesheet">
      <!-- general -->
   </head>
   <style>
      @media (max-width:  550px) {
      body{
      font-size:14px!important;
      }
      .tooltip{
      position: absolute;
      top: -74px!important;
      left: 143px!important;
      cursor: pointer;
      }.container-fluid.wrapper{
      margin:0!important;
      padding:0!important;
      }th{
      font-size:14px;
      }
      td[data-cell]::before {
      content: attr(data-cell) ": ";
      font-weight: 600!important;
      font-size:14px;
      text-transform: uppercase;
      font-size:14px!important;
      } td[data-cell="accountNumber"],td[data-cell="amount"],td[data-cell="receipt"] {
      text-align: left!important;
      } th[data-cell="accountNumber"],th[data-cell="fund"] {
      display:none!important;
      }.table-responsive{
      overflow-x:hidden;
      min-width: 100%;
      }tr{
      border: 0px;
      width: 100%; /* Add this to ensure full width background on hover */
      display: block; /* Add this to ensure full width background on hover */
      }tbody{
      border:0.4px solid gray !important;
      font-size:14px!important;
      position:relative;
      top:14px;
      }
      th:nth-child(n+7) {
      display: none;
      }.btn.dropdown-btn{
      display:none;
      }.hidden-td{
      display:none!important;
      transition: opacity 0.3s ease-in-out, 
      }  .show-cell {
      display: grid !important;
      grid-template-columns: 20ch auto;
      padding: 0.6rem 2rem !important;
      gap: 2rem;
      opacity: 1;
      transition: opacity 0.3s ease-in-out, 
      padding 0.2s ease-in-out, gap 0.5s ease-in-out; /* Add smooth transitions for other properties */
      } td[data-cell="remarks"] {
      white-space: normal; /* Allow text to wrap */
      word-wrap: break-word; /* Break words */
      min-width:100%!important;
      max-height: 6em; /* Maximum height before showing scrollbar */
      overflow-y: auto; /* Show vertical scrollbar when content overflows */
      overflow-x: hidden; /* Hide horizontal scrollbar */
      }.categ{
      margin-left:3.rem;
      }.menu-action{
      display:none!important;
      }tfoot{
      position:relative;
      top:20px;
      }.dataTablesIncome th {
      background-color: #f2f2f2; /* Header background color */
      position: sticky;
      top: 0; /* Stick to the top of the viewport */
      z-index: 1; /* Ensure the header is above table content */
      }.odd td{
      background-color:rgb(229, 228, 228)!important;
      color:#333333!important;
      }.even td{
      background-color: rgb(200 209 238)!important;   }
      td{
      display:block!important;
      min-width: 100%!important;
      text-align:left!important;
      font-size:14px!important;
      font-weight:700;
      transition: opacity 0.3s ease-in-out, padding 0.3s ease-in-out, gap 0.3s ease-in-out; /* Add smooth transitions for other properties */
      }.container-fluid.wrapper{
      margin:0!important;
      padding:0!important;
      }#dataTablesIncome td:nth-child(7){
      min-width: 103%!important;
      }
      td[data-cell]::before {
      content: attr(data-cell) ": ";
      font-weight: 500;
      font-size:14px;
      text-transform: uppercase;
      font-size:14px!important;
      } td[data-cell="accountNumber"],td[data-cell="amount"],td[data-cell="receipt"] {
      text-align: left!important;
      } th[data-cell="accountNumber"],th[data-cell="fund"] {
      display:none!important;
      }.table-responsive{
      overflow-x:hidden;
      }td:first-child{
      border-top:4px solid var(--deam);
      }.dropdown.buttons{
      display:none!important;
      }.filter-buttons{
      right:33%;
      z-index:999;
      }.content.open.dropdown.buttons{
      display:none!important;
      }.filter-buttons.content-close {
      display: flex;
      position:relative;
      left:-32%;
      top: 6px;
      z-index: 999;
      opacity: 1; /* Initially set opacity to 0 */
      transition: opacity 0.3s ease; /* Adding a transition for a smooth fade-in effect */
      }
      }
      /* Adjust the font size based on screen size */
      @media (min-width: 577px) and (max-width: 768px) {
      .amount {
      font-size: 18px;
      }.dataTables_paginate{
      position:relative;
      top:-14px;
      background-color:white;
      }   .add {
      position: relative;
      left: 160%;
      }
      }
      @media (min-width: 769px) and (max-width: 992px) {
      .amount {
      font-size: 20px;
      }.dataTables_paginate{
      position:relative;
      top:-14px;
      background-color:white;
      }
      }
      @media (min-width: 993px) and (max-width: 1200px) {
      .amount {
      font-size: 22px;
      }.dataTables_paginate{
      position:relative;
      top:-14px;
      background-color:white;
      }
      }
      @media (min-width: 1201px) {
      .amount {
      font-size: 24px;
      }.dataTables_paginate{
      position:relative;
      top:-14px;
      background-color:white;
      }
      }
   </style>
   <style>
      #dataTablesIncome td:nth-child(7) {
      max-width: 400px; 
      overflow:auto;
      word-wrap: break-word;
      box-sizing: border-box;
      }
      #dataTablesIncome td:nth-child(2) {
      white-space: nowrap;
      overflow: auto;
      }.data_table.expand #dataTablesIncome {
      margin-left:0px;
      }#dataTablesIncome td:nth-child(4) {
      white-space: nowrap;
      overflow: auto;
      }#dataTablesIncome td:nth-child(5) {
      white-space: nowrap;
      width:10px;
      }      #dataTablesIncome td:nth-child(8) {
      white-space: normal;
      word-wrap: break-word;
      max-width: 50px; /* Adjust the value as per your requirement */
      overflow: auto;
      }  .modal-content {
      border: none;
      border-radius: 1px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      }
      /* Modal Header Styling */
      .modal-header {
      border-bottom: none;
      padding: 20px;
      background-color:var(--azure);
      color: var(--white);
      border-top-left-radius: 8px;
      border-bottom:var(--gold) 6px solid;
      border-top-right-radius: 8px;
      }
      /* Modal Body Styling */
      .modal-body {
      padding: 30px;
      background-color:var(--white);
      }
   </style>
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
      /* Modal Content Styling */
      .modal-content {
      border: none;
      border-radius: 19px!important;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      background-color: var(--gray);
      }
      /* Modal Header Styling */
      .modal-header {
      border-bottom: none;
      padding: 17px;
      background-color:var(--azure);
      color: var(--white);
      border-top-left-radius: 8px;
      border-bottom:var(--gold) 6px solid;
      border-top-right-radius: 8px;
      }
      /* Modal Body Styling */
      .modal-body {
      padding: 20px;
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
      border-color: var(--gold)!important;
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
      .custom-file-input:focus ~ .custom-file-label {
      border-color: var(--gold);
      box-shadow: 0 0 0 0.2rem rgba(245, 190, 90, 0.25);
      }
      .invalid-feedback {
      color: var(--deam);
      }  .scroll-btn {
      position: fixed;
      bottom: 20px;
      right: 20px;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background-color: var(--blue);
      color: #fff;
      font-size: 20px;
      line-height: 40px;
      text-align: center;
      cursor: pointer;
      z-index: 9999;
      opacity: 0;
      transition: opacity 0.3s ease;
      }
      .scroll-btn.show {
      opacity: 1;
      }
      .tooltip-inner {
      background-color: var(--azure);
      color: var(--darkblue);
      }
      .tooltip {
      position: absolute;
      top: -64px!important;
      left: -13px!important;
      cursor: pointer;
      }.filter{
      color: black;
      border-radius: 10px;
      background: rgb(254, 254, 254);
      box-shadow: 1px 3px 6px #404040;
      }.dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter, .dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_processing, .dataTables_wrapper .dataTables_paginate{
      font-size:14px;
      padding-left:13px;
      padding-bottom:6px;
      padding-top:1px;
      z-index:100;
      }.dataTables_wrapper .dataTables_length select{
      margin-right: 2px;
      margin-left: 2px;
      font-size: 14px!important;
      padding: 0px!important;
      }.d-flex.justify-content-between{
      padding: -15px!important;
      margin: -14px!important;
      }.dataTables_paginate{
      position:relative;
      top:-14px;
      } .modal{
      position: fixed!important;
      top: -14px!important;
      left: 0;
      }.content.open ul.dropdown-menu.show{
      transform: translate3d(-104px, 9px, 0px)!important;
      } #successMessage {
      transition: opacity 0.5s ease-in-out; /* Use CSS transition for a smooth fade-in and fade-out */
      }.content.open #totalAmount {
      margin-left: auto!important;
      }.filter-buttons.content-close {
      display: flex;
      position:relative;
      left:-32%;
      top: 6px;
      z-index: 999;
      opacity: 1; /* Initially set opacity to 0 */
      transition: opacity 0.3s ease; /* Adding a transition for a smooth fade-in effect */
      }
   </style>
   <style>
      /* Adjust the font size and margin based on screen size */
      /* Small Devices (landscape phones, 576px and up) */
      @media (min-width: 576px) and (max-width: 767.98px) {
      .tooltip{
      position: absolute;
      top: -55px!important;
      left: 136px!important;
      cursor: pointer;
      }
      }
      /* Medium Devices (tablets, 768px and up) */
      @media (min-width: 768px) and (max-width: 991.98px) {
      .add.trans{
      position: absolute;
      left: 79%;
      top: 18px;
      }.tooltip {
      position: absolute;
      top: -64px!important;
      left: 83px!important;
      cursor: pointer;
      }.dataTable_wrapper.income {
      margin-top: -12px;
      }
      }
      /* Large Devices (desktops, 992px and up) */
      @media (min-width: 992px) and (max-width: 1199.98px) {
      /* No specific styles for this breakpoint */
      /* You can add styles here if needed */
      }
      /* Extra Large Devices (large desktops, 1200px and up) */
      @media (min-width: 1200px) {
      }
   </style>
   <style>
      /* responsive table */
      #dataTablesIncome{
      width : min(900px, 100% -3rem)!important;
      margin-inline:auto!important;
      overflow-x:auto!important;
      border:0px!important;
      }
   </style>
   <!-- START BODY -->
   <body>
      <div class="invoice-header">
         Invoice Header
      </div>
      <div class="container-fluid position-relative d-flex p-0">
         <!-- Spinner Start -->
         <div id="spinner" class="show bg-primary position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-white" style="width: 3rem; height: 3rem;" role="status">
               <span class="sr-only">Loading...</span>
            </div>
         </div>
         <!-- Spinner End -->
         <!-- Sidebar Start -->
        
         <!-- Sidebar End -->
         <!-- Content Start -->
         <div class="content open">
            <!-- Navbar Start -->
         
            <!-- Sale & Revenue Start -->
          
            <hr>
            <div class="container-fluid wrapper">
               <div class="row g-2 mx-1 mb-3">
                  <div class="col-12 shadow-lg rounded-3 px-md-2">
                   
                     <div class="dataTable_wrapper income col-12 pt-3 px-0 pb-0">
                      
                           
                            
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
      <div class="modal fade" id="editIncomeModal" tabindex="-1" role="dialog" aria-labelledby="editIncomeModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content w-100 vh-100">
               <div class="modal-header d-flex justify-content-center">
                  <h5 class="modal-title" style="margin-left:auto;" id="editIncomeModalLabel">Edit CashinFlow Transaction</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body p-3">
                  <p class="float-right mr-2">Transaction ID: <span id="currentTransactionId"></span></p>
                  <form action="CRUD/update/updateCashInFlow.php" method="POST" enctype="multipart/form-data" class="row col-12" id="updateForm">
                     <!-- Add these hidden fields to store sort and pagination information -->
                     <input type="hidden" id="currentSortColumn" name="currentSortColumn">
                     <input type="hidden" id="currentSortDirection" name="currentSortDirection">
                     <input type="hidden" id="currentPage" name="currentPage">
                     <input type="hidden" name="rowId" id="editRowId" value="<?php echo $rowId;?>">
                     <input type="hidden" name="receiptPath" id="receiptPath" value="<?php echo $receiptPath;?>">
                     <div class="row mx-auto">
                        <div class="form-group col-md-6 ">
                           <label for="editDate">Date:</label>
                           <input type="date" class="form-control" id="editDate" name="date" placeholder="Enter the date...">
                        </div>
                     </div>
                     <div class="form-group col-md-6">
                        <label for="editAccountTitle" >Fund: </label>
                        <select class="form-control" id="editAccountTitle" name="accountTitle">
                           <option value="" selected>Select Where Fund...</option>
                           <?php include 'sqlGetData/getCapitalCategory.php';?>
                        </select>
                     </div>
                     <div class="form-group col-md-6">
                        <label for="editCategory">Category:</label>
                        <select class="form-control" id="editCategory"  name="category">
                           <option value="" selected >Select an Fund Category title...</option>
                           <?php require 'sqlGetData/getRevenueCategory.php';?>
                        </select>
                     </div>
                     <div class="form-group col-md-6">
                        <label for="editAmount" required>Amount:</label>
                        <span class="position-absolute" style="top:39px;left:7px;">&#8369; </span> <input type="text" class="form-control" id="editAmount" name="amount" placeholder="Enter the amount...">
                     </div>
                     <div class="form-group col-md-6">
                        <label for="editType">Type:</label>
                        <select class="form-control" id="editType" c name="type">
                           <option value="" selected>Choose where to deposit..</option>
                           <option value="Gcash">Gcash</option>
                           <option value="Bank">Bank In</option>
                           <option value="Bank">OnHand</option>
                        </select>
                     </div>
                     <div class="form-group col-md-12">
                        <label for="editRemarks">Remarks:</label>
                        <textarea class="form-control" id="editRemarks" name="remarks" placeholder="Enter the remarks..."></textarea>
                     </div>
                     <div class="form-group col-md-12">
                        <label for="editReceipt">Receipt:</label>
                        <div class="custom-file">
                           <!-- Hide the actual file input -->
                           <input type="hidden" id="oldReceipt" name="oldReceipt" value="">
                           <input type="file" class="custom-file-input" id="newReceipt" name="newReceipt"  accept=".png, .jpeg, .jpg, .gif" style="display: none;">
                           <!-- Display a separate label for the new receipt -->
                           <label class="custom-file-label" for="newReceipt" id="receiptName">Choose a File</label>
                           <!-- Add a message div to display the success message -->
                        </div>
                        <div id="successMessage" class="alert alert-success mt-2 text-center" style="display: none;">
                           <div>
                           </div>
                           <span class="ms-2 mb-2 text-center ">
                           <i class="fa fa-check mx-3" ></i>  Your file is being submitted for upload.
                           </span>
                        </div>
                     </div>
                     <div class="form-group col-md-12">
                        <img class="img-fluid shadow-lg" id="editReceiptImage" style="width: 50%; display: block; margin: 10px auto;">
                     </div>
                     <div class="form-group col-md-12">
                        <button type="submit"  onclick="updateTransaction()" name="update" class="btn btn-primary float-right">Update</button>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <!--edit modal end -->
      <!-- Details Modal Start -->
      <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
               <div class="modal-header d-flex justify-content-center">
                  <h5 class="modal-title" style="margin-left:auto;" id="editIncomeModalLabel">Details CashinFlow Transaction</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <div>
                     <p class="float-right mr-2">Transaction ID: <span id="currentTransactionIdDetails"></span></p>
                  </div>
                  <form class="mt-4" action="#" method="POST" enctype="multipart/form-data">
                     <div class="col-12 d-flex justify-content-center align-items-center">
                        <div class="form-group col-md-6 ">
                           <label for="detailDate">Date:</label>
                           <input type="date" class="form-control" id="detailDate" name="date" placeholder="Enter the date..." disabled>
                        </div>
                        <div class="form-group col-md-6">
                           <label for="detailAccountTitle">Fund:</label>
                           <input type="text" class="form-control" id="detailAccountTitle" name="accountTitle" disabled>
                        </div>
                     </div>
                     <div class="row">
                        <div class="form-group col-md-6">
                           <label for="detailCategory">Category:</label>
                           <div class="d-flex">
                              <input type="text" class="form-control col-3 text-center" id="detailCategoryNumber" name="category" disabled> <span class="mt-1">:</span>
                              <input type="text" class="form-control" id="detailCategory" name="category" disabled> 
                           </div>
                        </div>
                        <div class="form-group col-md-6">
                           <label for="detailAmount">Amount:</label>
                           <input type="text" class="form-control" id="detailAmount" name="amount" disabled>
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="detailType">Type:</label>
                        <input type="text" class="form-control" id="detailType" name="type" disabled>
                     </div>
                     <div class="form-group">
                        <label for="detailRemarks">Remarks:</label>
                        <textarea class="form-control" id="detailRemarks" name="remarks" placeholder="Enter the remarks..." disabled></textarea>
                     </div>
                     <div class="form-group">
                        <label for="detailReceipt">Receipt:</label>
                        <img class="img-fluid shadow-lg " src="" id="detailReceiptImage" style="width: 50% ;  display: block; margin: 10px auto;">
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <!-- Details Modal End -->
      <!-- ADD MODAL -->
      <div class="modal fade" id="addIncomeModal" tabindex="-1" role="dialog" aria-labelledby="addIncomeModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content w-100">
               <div class="modal-header d-flex justify-content-center">
                  <h5 class="modal-title" style="margin-left:auto;" id="addIncomeModalLabel">CashinFlow Transaction</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body p-3">
                  <form action="CRUD/create/addCashInflow.php" method="POST"  enctype="multipart/form-data" class="row col-12">
                     <div class="row mx-auto">
                        <div class="form-group col-md-12">
                           <label for="date">Date:</label>
                           <input type="date" class="form-control" required id="date" name="date" placeholder="Enter the date...">
                        </div>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="accountTitle">Fund:</label>
                        <select class="form-control" id="accountTitle" name="accountTitle">
                           <option value="" selected>Select Where Fund...</option>
                           <?php include 'sqlGetData/getCapitalCategory.php';?>
                        </select>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="category">Category:</label>
                        <select class="form-control" id="category" name="category">
                           <option value="" selected >Select an Fund Category title...</option>
                           <?php require 'sqlGetData/getRevenueCategory.php';?>
                        </select>
                     </div>
                     <div class="form-group col-md-6">
                        <label for="amount">Amount:</label>
                        <input type="text" class="form-control" id="amount" name="amount" placeholder="Enter the amount...">
                     </div>
                     <div class="form-group col-md-6">
                        <label for="type">Type:</label>
                        <select class="form-control" id="type" name="type">
                           <option value="" seleced>Choose where to Deposit..</option>
                           <option value="Gcash">Gcash</option>
                           <option value="Bank">Bank In</option>
                           <option value="Bank">OnHand</option>
                        </select>
                     </div>
                     <div class="form-group col-md-12">
                        <label for="remarks">Remarks:</label>
                        <textarea class="form-control" id="remarks" name="remarks" placeholder="Enter the remarks..."></textarea>
                     </div>
                     <div class="form-group col-md-12">
                        <label for="receipt">Receipt:</label>
                        <div class="custom-file">
                           <input type="file" class="custom-file-input" id="receipt" name="receipt" onchange="displayFileNameAdd()" accept=".png, .jpeg, .jpg, .gif">
                           <label class="custom-file-label" for="receipt" id="receiptLabel">Choose file</label>
                        </div>
                     </div>
                     <div class="form-group col-md-12">
                        <button type="submit" class="btn btn-primary float-right">Submit</button>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <!-- END ADD -->
      <!-- Modal for Delete Confirmation -->
      <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
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
   <script src="js/manageIncome.js"></script>
   <!-- js -->
   <script>
      function editTransaction(button) {
       
          var transactionId = button.getAttribute('data-id');
          var date = button.getAttribute('data-date');
          var fund = button.getAttribute('data-fund');
          var amount = button.getAttribute('data-amount');
          var category = button.getAttribute('data-category');
          var type = button.getAttribute('data-type');
          var filename = button.getAttribute('data-filename');
          var remarks = button.getAttribute('data-remarks');
          var receipt = button.getAttribute('data-receipt');
          var currentTransactionId = document.getElementById('currentTransactionId');
          
          var receiptImageElement = document.getElementById('editReceiptImage');
          currentTransactionId.textContent = transactionId;
          
       
       
          document.getElementById('editDate').value = date;
          document.getElementById('editAccountTitle').value = fund;
          document.getElementById('editAmount').value = amount;
          document.getElementById('editCategory').value = category;
          document.getElementById('editType').value = type;
          document.getElementById('editRemarks').value = remarks;
          document.getElementById('currentTransactionId').value = transactionId;
          
          var receiptLabel = document.getElementById('receiptName');
          var oldReceiptInput = document.getElementById('oldReceipt').value= receipt;
          var newReceiptInput = document.getElementById('newReceipt').value;
      
       
      
          // Check if receipt exists or not and update the label accordingly
          if (receipt) {
            receiptImageElement.src = 'CRUD/create/uploadsCashin/' + receipt;
        } else {
            receiptImageElement.src = ''; // Set the src to an empty string to clear any previous image
        }
      
           // Add an event listener for the newReceipt input element
       document.getElementById('newReceipt').addEventListener('change', function() {
      
          var fileInput = this;
          var file = fileInput.files[0];
      
          var oldReceipt = oldReceiptInput;
         var filename = file.name;
         var fileId = transactionId;
      
         //      }
          if (file) {
            receiptImageElement.style.display="none";
             receiptLabel.textContent = file.name;
             var successMessage = document.getElementById('successMessage');
             successMessage.style.opacity = '1';
             successMessage.style.display = 'block';
      
             // Hide the success message smoothly after 1.5 seconds (1500 milliseconds)
             setTimeout(function() {
                successMessage.style.opacity = '0';
         
                setTimeout(function() {
                   successMessage.style.display = 'none';
                }, 500); // Wait for the fade-out transition to complete before hiding the div
             }, 2500);
          } else {
             receiptLabel.textContent = 'Choose a new File';
          }
       });
      
       }
      
      
      
   </script>
   <script>
      $(document).ready(function () {
      // Your existing code...
      
      // Function to remove the alt attribute from all img tags within the modal
      function removeAltFromImages() {
       $("#detailsModal img").removeAttr("alt");
      }
      
      // Call the function when the modal is shown
      $("#detailsModal").on("show.bs.modal", function () {
       removeAltFromImages();
      });
      });
      function showErrorModal(errorMessage) {
         var errorModal = new bootstrap.Modal(document.getElementById("errorModal"));
         var errorMessageElement = document.querySelector("#errorModal .modal-body p");
         errorMessageElement.textContent = errorMessage;
         errorModal.show();
         var closeButton = document.querySelector("#errorModal .btn-secondary");
          closeButton.addEventListener("click", closeModal);
         }
   </script>
   <!-- GET AMOUNT IN INPUT -->
   <script>
      // Get the amount input element
      var amountInput = document.getElementById('amount');
      
      var
      
      // Add event listener for input changes
      amountInput.addEventListener('input', function(e) {
      // Get the input value and remove commas
      var value = e.target.value.replace(/,/g, '');
      
      // Format the value with commas
      var formattedValue = formatNumberWithCommas(value);
      
      // Update the input value with formatted value
      e.target.value = formattedValue;
      });
      
      // Function to format number with commas
      function formatNumberWithCommas(number) {
      return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
      } 
      $(document).ready(function() {
      <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($errors)): ?>
         // Show the modal if there are validation errors
         $('#addIncomeModal').modal('show');
      
         // Add shake effect to the input fields with validation errors
         <?php if (isset($errors['date'])): ?>
            $('#date').addClass('is-invalid');
            $('#date').effect('shake');
         <?php endif; ?>
      
         <?php if (isset($errors['accountTitle'])): ?>
            $('#accountTitle').addClass('is-invalid');
            $('#accountTitle').effect('shake');
         <?php endif; ?>
      
         // Add similar code for other input fields
      
      <?php endif; ?>
      });
   </script>
  
   <script>
      $(document).ready(function() {
         $(".clickable-td").click(function() {
         const clickedTd = $(this); // Get the clicked td element
        
         const allTableCells = clickedTd.closest('tr').find('td'); // Get all cells in the clicked row
         allTableCells.toggleClass('show-cell'); // Toggle the class for all cells in the row
         $('.menu-action').removeClass('menu-action');
      });
      
         // $(".clickable-td").click(function() {
         //    const clickedTd = $(this); // Get the clicked td element
         //    console.log(clickedTd);
         //    // const allTableCells = document.querySelectorAll('tr, td');
         
         //    // allTableCells.forEach(cell => {
         //    //   cell.classList.toggle('show-cell');
         //  });
        });
        
        $(document).ready(function() {
         $("thead").click(function() {
         
            const allTableCells = $("td"); // Get all <td> elements on the page
            allTableCells.removeClass('show-cell '); // Remove the class for all cells
      });
      
         // $(".clickable-td").click(function() {
         //    const clickedTd = $(this); // Get the clicked td element
         //    console.log(clickedTd);
         //    // const allTableCells = document.querySelectorAll('tr, td');
         
         //    // allTableCells.forEach(cell => {
         //    //   cell.classList.toggle('show-cell');
         //  });
        });
        
      
   </script>
</html>
<?php
   session_start();
   
   if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Redirect the user to the login page
    header('Location: index.php');
    exit();
   }
   
      require 'includes/dbh.inc.php';
   
   
   $role =  $_SESSION['role'];
   
   // Output the value of $role in a script tag
   echo "<script>var role = '" . $role . "';</script>";
   
   $sql = "SELECT * FROM cashinflow_tbl";
   $stmt = $conn->query($sql);
   $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
   
   
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>Manage Income</title>
      <meta content="width=device-width, initial-scale=1.0" name="viewport">
      <meta content="" name="keywords">
      <meta content="" name="description">
      <!-- css content -->
      <link rel="stylesheet" href="assets/fontawesome-free-6.3.0-web/css/all.css">
      <link rel="stylesheet" href="assets/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/css/budgetManagement.css">
      <!-- DATA TABLES  cs-->
      <link rel="stylesheet" href="assets/datatable/css/datatables.min.css">
      <link rel="stylesheet" href="assets/datatable/css/dataTables.dateTime.min.css">
      <link rel="stylesheet" href="assets/datatable/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/datatable/css/jquery.dataTables.min.css">
      <link rel="stylesheet" href="assets/css/cashinflow/cashInflow.css">
      <link rel="stylesheet" href="assets/datatable/js/cdn.datatables.net_buttons_2.4.1_css_buttons.dataTables.min.css">
      <!-- media Print -->
      <link rel="stylesheet" href="assets/css/mediaPrintIncome.css">
      <!-- DATA TABLE JS -->
      <script src="assets/datatable/js/jquery-3.6.0.min.js"></script>
      <script src="assets/datatable/css/bootstrap.bundle.min.js"></script>
      <script src="assets/datatable/js/datatables.min.js"></script>   
      <script src = "assets/datatable/js/cdnjs.cloudflare.com_ajax_libs_pdfmake_0.1.53_pdfmake.min.js"></script>
      <script src = "assets/datatable/js/cdnjs.cloudflare.com_ajax_libs_pdfmake_0.1.53_vfs_fonts.js"></script>
      <script src="assets/datatable/js/vfs_fonts.js"></script>
      <!-- DATA TABLES DATE FILTER -->
      <script src="assets/datatable/js/moment.min.js"></script>
      <script src="assets/datatable/js/dataTables.dateTime.min.js"></script>

      <!-- general -->
      <script src="assets/js/popper.min.js"></script>
      <script src="assets/js/bootstrap.min.js"></script>
      <script src="assets/js/moment.min.js"></script>
      <script src="assets/js/select2.min.js"></script>
      <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
  
      <!-- fixed header -->
      <link rel="stylesheet" href="assets/datatable/css/datatables.net_release-datatables_extensions_FixedHeader_css_fixedHeader.dataTables.css">
      <script src="assets/datatable/js/datatables.net_release-datatables_extensions_FixedHeader_js_dataTables.fixedHeader.js"></script>
      <!-- Google Web Fonts -->
      <link href="css/bootstrap.min.css" rel="stylesheet">
      <link href="css/style.css" rel="stylesheet">
      <!-- general -->
   </head>
   <style>
      @media (max-width:  550px) {
      body{
      font-size:14px!important;
      }
      .tooltip{
      position: absolute;
      top: -74px!important;
      left: 143px!important;
      cursor: pointer;
      }.container-fluid.wrapper{
      margin:0!important;
      padding:0!important;
      }th{
      font-size:14px;
      }
      td[data-cell]::before {
      content: attr(data-cell) ": ";
      font-weight: 600!important;
      font-size:14px;
      text-transform: uppercase;
      font-size:14px!important;
      } td[data-cell="accountNumber"],td[data-cell="amount"],td[data-cell="receipt"] {
      text-align: left!important;
      } th[data-cell="accountNumber"],th[data-cell="fund"] {
      display:none!important;
      }.table-responsive{
      overflow-x:hidden;
      min-width: 100%;
      }tr{
      border: 0px;
      width: 100%; /* Add this to ensure full width background on hover */
      display: block; /* Add this to ensure full width background on hover */
      }tbody{
      border:0.4px solid gray !important;
      font-size:14px!important;
      position:relative;
      top:14px;
      }
      th:nth-child(n+7) {
      display: none;
      }.btn.dropdown-btn{
      display:none;
      }.hidden-td{
      display:none!important;
      transition: opacity 0.3s ease-in-out, 
      }  .show-cell {
      display: grid !important;
      grid-template-columns: 20ch auto;
      padding: 0.6rem 2rem !important;
      gap: 2rem;
      opacity: 1;
      transition: opacity 0.3s ease-in-out, 
      padding 0.2s ease-in-out, gap 0.5s ease-in-out; /* Add smooth transitions for other properties */
      } td[data-cell="remarks"] {
      white-space: normal; /* Allow text to wrap */
      word-wrap: break-word; /* Break words */
      min-width:100%!important;
      max-height: 6em; /* Maximum height before showing scrollbar */
      overflow-y: auto; /* Show vertical scrollbar when content overflows */
      overflow-x: hidden; /* Hide horizontal scrollbar */
      }.categ{
      margin-left:3.rem;
      }.menu-action{
      display:none!important;
      }tfoot{
      position:relative;
      top:20px;
      }.dataTablesIncome th {
      background-color: #f2f2f2; /* Header background color */
      position: sticky;
      top: 0; /* Stick to the top of the viewport */
      z-index: 1; /* Ensure the header is above table content */
      }.odd td{
      background-color:rgb(229, 228, 228)!important;
      color:#333333!important;
      }.even td{
      background-color: rgb(200 209 238)!important;   }
      td{
      display:block!important;
      min-width: 100%!important;
      text-align:left!important;
      font-size:14px!important;
      font-weight:700;
      transition: opacity 0.3s ease-in-out, padding 0.3s ease-in-out, gap 0.3s ease-in-out; /* Add smooth transitions for other properties */
      }.container-fluid.wrapper{
      margin:0!important;
      padding:0!important;
      }#dataTablesIncome td:nth-child(7){
      min-width: 103%!important;
      }
      td[data-cell]::before {
      content: attr(data-cell) ": ";
      font-weight: 500;
      font-size:14px;
      text-transform: uppercase;
      font-size:14px!important;
      } td[data-cell="accountNumber"],td[data-cell="amount"],td[data-cell="receipt"] {
      text-align: left!important;
      } th[data-cell="accountNumber"],th[data-cell="fund"] {
      display:none!important;
      }.table-responsive{
      overflow-x:hidden;
      }td:first-child{
      border-top:4px solid var(--deam);
      }.dropdown.buttons{
      display:none!important;
      }.filter-buttons{
      right:33%;
      z-index:999;
      }.content.open.dropdown.buttons{
      display:none!important;
      }.filter-buttons.content-close {
      display: flex;
      position:relative;
      left:-32%;
      top: 6px;
      z-index: 999;
      opacity: 1; /* Initially set opacity to 0 */
      transition: opacity 0.3s ease; /* Adding a transition for a smooth fade-in effect */
      }
      }
      /* Adjust the font size based on screen size */
      @media (min-width: 577px) and (max-width: 768px) {
      .amount {
      font-size: 18px;
      }.dataTables_paginate{
      position:relative;
      top:-14px;
      background-color:white;
      }   .add {
      position: relative;
      left: 160%;
      }
      }
      @media (min-width: 769px) and (max-width: 992px) {
      .amount {
      font-size: 20px;
      }.dataTables_paginate{
      position:relative;
      top:-14px;
      background-color:white;
      }
      }
      @media (min-width: 993px) and (max-width: 1200px) {
      .amount {
      font-size: 22px;
      }.dataTables_paginate{
      position:relative;
      top:-14px;
      background-color:white;
      }
      }
      @media (min-width: 1201px) {
      .amount {
      font-size: 24px;
      }.dataTables_paginate{
      position:relative;
      top:-14px;
      background-color:white;
      }
      }
   </style>
   <style>
      #dataTablesIncome td:nth-child(7) {
      max-width: 400px; 
      overflow:auto;
      word-wrap: break-word;
      box-sizing: border-box;
      }
      #dataTablesIncome td:nth-child(2) {
      white-space: nowrap;
      overflow: auto;
      }.data_table.expand #dataTablesIncome {
      margin-left:0px;
      }#dataTablesIncome td:nth-child(4) {
      white-space: nowrap;
      overflow: auto;
      }#dataTablesIncome td:nth-child(5) {
      white-space: nowrap;
      width:10px;
      }      #dataTablesIncome td:nth-child(8) {
      white-space: normal;
      word-wrap: break-word;
      max-width: 50px; /* Adjust the value as per your requirement */
      overflow: auto;
      }  .modal-content {
      border: none;
      border-radius: 1px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      }
      /* Modal Header Styling */
      .modal-header {
      border-bottom: none;
      padding: 20px;
      background-color:var(--azure);
      color: var(--white);
      border-top-left-radius: 8px;
      border-bottom:var(--gold) 6px solid;
      border-top-right-radius: 8px;
      }
      /* Modal Body Styling */
      .modal-body {
      padding: 30px;
      background-color:var(--white);
      }
   </style>
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
      /* Modal Content Styling */
      .modal-content {
      border: none;
      border-radius: 19px!important;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      background-color: var(--gray);
      }
      /* Modal Header Styling */
      .modal-header {
      border-bottom: none;
      padding: 17px;
      background-color:var(--azure);
      color: var(--white);
      border-top-left-radius: 8px;
      border-bottom:var(--gold) 6px solid;
      border-top-right-radius: 8px;
      }
      /* Modal Body Styling */
      .modal-body {
      padding: 20px;
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
      border-color: var(--gold)!important;
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
      .custom-file-input:focus ~ .custom-file-label {
      border-color: var(--gold);
      box-shadow: 0 0 0 0.2rem rgba(245, 190, 90, 0.25);
      }
      .invalid-feedback {
      color: var(--deam);
      }  .scroll-btn {
      position: fixed;
      bottom: 20px;
      right: 20px;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background-color: var(--blue);
      color: #fff;
      font-size: 20px;
      line-height: 40px;
      text-align: center;
      cursor: pointer;
      z-index: 9999;
      opacity: 0;
      transition: opacity 0.3s ease;
      }
      .scroll-btn.show {
      opacity: 1;
      }
      .tooltip-inner {
      background-color: var(--azure);
      color: var(--darkblue);
      }
      .tooltip {
      position: absolute;
      top: -64px!important;
      left: -13px!important;
      cursor: pointer;
      }.filter{
      color: black;
      border-radius: 10px;
      background: rgb(254, 254, 254);
      box-shadow: 1px 3px 6px #404040;
      }.dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter, .dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_processing, .dataTables_wrapper .dataTables_paginate{
      font-size:14px;
      padding-left:13px;
      padding-bottom:6px;
      padding-top:1px;
      z-index:100;
      }.dataTables_wrapper .dataTables_length select{
      margin-right: 2px;
      margin-left: 2px;
      font-size: 14px!important;
      padding: 0px!important;
      }.d-flex.justify-content-between{
      padding: -15px!important;
      margin: -14px!important;
      }.dataTables_paginate{
      position:relative;
      top:-14px;
      } .modal{
      position: fixed!important;
      top: -14px!important;
      left: 0;
      }.content.open ul.dropdown-menu.show{
      transform: translate3d(-104px, 9px, 0px)!important;
      } #successMessage {
      transition: opacity 0.5s ease-in-out; /* Use CSS transition for a smooth fade-in and fade-out */
      }.content.open #totalAmount {
      margin-left: auto!important;
      }.filter-buttons.content-close {
      display: flex;
      position:relative;
      left:-32%;
      top: 6px;
      z-index: 999;
      opacity: 1; /* Initially set opacity to 0 */
      transition: opacity 0.3s ease; /* Adding a transition for a smooth fade-in effect */
      }
   </style>
   <style>
      /* Adjust the font size and margin based on screen size */
      /* Small Devices (landscape phones, 576px and up) */
      @media (min-width: 576px) and (max-width: 767.98px) {
      .tooltip{
      position: absolute;
      top: -55px!important;
      left: 136px!important;
      cursor: pointer;
      }
      }
      /* Medium Devices (tablets, 768px and up) */
      @media (min-width: 768px) and (max-width: 991.98px) {
      .add.trans{
      position: absolute;
      left: 79%;
      top: 18px;
      }.tooltip {
      position: absolute;
      top: -64px!important;
      left: 83px!important;
      cursor: pointer;
      }.dataTable_wrapper.income {
      margin-top: -12px;
      }
      }
      /* Large Devices (desktops, 992px and up) */
      @media (min-width: 992px) and (max-width: 1199.98px) {
      /* No specific styles for this breakpoint */
      /* You can add styles here if needed */
      }
      /* Extra Large Devices (large desktops, 1200px and up) */
      @media (min-width: 1200px) {
      }
   </style>
   <style>
      /* responsive table */
      #dataTablesIncome{
      width : min(900px, 100% -3rem)!important;
      margin-inline:auto!important;
      overflow-x:auto!important;
      border:0px!important;
      }
   </style>
   <!-- START BODY -->
   <body>

      <div class="container-fluid position-relative d-flex p-0">
         <!-- Spinner Start -->
        
         <!-- Spinner End -->
         <!-- Sidebar Start -->
       
         <!-- Sidebar End -->
         <!-- Content Start -->
         <div class="content open">
            <!-- Navbar Start -->
            <?php include 'template/navbar.php'?>
            <?php      include 'template/modal.php'?>
            <!-- Navbar End -->
            <!-- Sale & Revenue Start -->
            <div class="container-fluid pt-4 px-4 ">
               <div class="filter">
                  <div class="filter row g-3 bg-white p-3 shadow-lg ">
                     <!-- filter row -->
                     <div class="col-sm-6 col-md-6 col-xl-4 d-flex align-items-center ">
                        <span><strong style="position:absolute; top:-22px;">Filter</strong></span>
                        <div class="d-flex justify-content-center align-items-center mx-auto">
                           <div class="d-flex flex-row mt-2">
                              <input type="text" class="form-control form-control-sm center" id="min" name="min" name="min" placeholder="Start Date" aria-label="Start date" aria-describedby="start-date-icon">
                              <i class="fa fa-calendar"></i> 
                           </div>
                           <div class="d-flex flex-row mt-2">
                              <!-- <input type="text" id="max" name="max">  <i class="fa fa-calendar ml-2"></i>  -->
                              <input type="text" class="form-control form-control-sm center" id="max" name="max" name="min" placeholder="End Date" aria-label="Start date" aria-describedby="start-date-icon">
                              <i class="fa fa-calendar ml-2"></i> 
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-4 col-md-6 col-xl-4 d-flex">
                        <div class="categ col-lg-12 col-sm-10">
                           <span><strong class="">Category</strong></span>
                           <div class="form-group row">
                              <select class="category-box col-10 col-md-12 form-control form-control-sm">
                                 <option selected >Select Category</option>
                                 <?php require 'sqlGetData/getRevenueCategory.php';?>
                              </select>
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-12 col-xl-4">
                        <span  class=""><strong class="">Search</strong></span>
                        <div class="form-group1 row mx-0 ">
                           <div class="col-12">
                              <div class="row">
                                 <div class="col-8">
                                    <input type="text" class="form-control form-control-sm" autocomplete="off" placeholder="Search Keyword" id="search-input">                          
                                 </div>
                                 <div class="col-4">
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
            <hr>
            <div class="container-fluid wrapper">
               <div class="row g-2 mx-1 mb-3">
                  <div class="col-12 shadow-lg rounded-3 px-md-2">
                     <?php
                        if ($role == "guess") {
                            // Add your code for the "guess" role here
                        } else {
                            echo '
                        <div class="col-sm-5 col-lg-12 col-md-12 " style="z-index:100; position:relative; top:-1px;">
                              <div class="buttons-transaction  d-flex justify-content-end align-items-center p-0 mb-0">
                                    <button type="button" class="add trans order-2 mt-1 float-right btn btn-sm btn-primary" data-toggle="modal" data-target="#addIncomeModal" style="background-color:">
                                       <span class="fa fa-plus"></span>Add Transaction
                                    </button>
                                    <button id="print-button">Print</button>
                              </div>
                        </div>';
                        
                        }
                        ?>
                     <div class="dataTable_wrapper income col-12 pt-3 px-0 pb-0">
                        <div class="bg-white rounded h-100  mb-0 pb-0">
                           <div class="table-responsive">
                              <table id="dataTablesIncome" class="table bg-white">
                                 <thead>
                                    <tr>
                                       <th data-cell = "id" >NO.</th>
                                       <th data-cell = "date" id="center">DATE</th>
                                       <th data-cell = "fund" >FUND</th>
                                       <th data-cell = "category"> <span></span> CATEGORY</th>
                                       <span class="fa fa-" id="changeTargetButton" style="position: relative; width: 9px; z-index: 99; top: 115px; left: 25%">
                                       <i style="color: white;"></i>
                                       <span class="tooltip">Click me to see category</span>
                                       </span>
                                       <th data-cell = "category"><span style="margin-left:10px;"></span> CATEGORY</th>
                                       <th data-cell = "amount">AMOUNT</th>
                                       <th data-cell = "type" >TYPE</th>
                                       <th data-cell = "remarks" >REMARKS </th>
                                       <th data-cell = "receipt" >RECEIPT </th>
                                       <th data-cell = "" id="right-corner">Action</th>
                                       <th>TimeStamp</th>
                                    </tr>
                                 <tbody>
                                    <?php
                                       require 'includes/dbh.inc.php';
                                       $total = 0;
                                       foreach ($result as $row) {
                                           $total += $row['amount'];
                                           $categoryAccount = $row['category_account'];    // Check if an image was uploaded without any errors
                                           
                                       
                                           $query = "SELECT child_account_number, child_account_name FROM chart_of_accounts WHERE child_account_name = :categoryAccount";
                                           $stmt = $conn->prepare($query);
                                           $stmt->bindParam(':categoryAccount', $categoryAccount);
                                           $stmt->execute();
                                           $account = $stmt->fetch(PDO::FETCH_ASSOC);
                                           $accountNumber = $account ? $account['child_account_number'] : 'N/A';
                                           $accountName = $account ? $account['child_account_name'] : 'N/A';
                                           $isAnnualFund = $row['fundAccTitle'] === 'Annual Fund';
                                           $isClubFund = $row['fundAccTitle'] === 'Club Fund';
                                           $receiptPath = $row['receipt'];
                                           // Move this line inside the loop to get the correct receipt path for each row
                                           $filename = basename($receiptPath);
                                       
                                       ?>
                                    <tr data-receipt="<?php echo $receiptPath; ?>">
                                       <td data-cell ="Transaction ID" class="clickable-td" ><?php  echo $row['id']; ?> </td>
                                       <td data-cell ="date" class="hidden-td"><?php echo $row['date'] ? $row['date'] : 'N/A'; ?></td>
                                       <td data-cell ="fundAccTitle"  class="hidden-td" ><?php echo $row['fundAccTitle'] ? $row['fundAccTitle'] : 'N/A'; ?></td>
                                       <td data-cell ="category_account"  class="hidden-td"><?php echo $row['category_account'] ? $row['category_account'] : 'N/A'; ?></td>
                                       <td data-cell ="accountNumber" class="hidden-td" >
                                          <span class="account-details account-details-tooltip" style="" data-toggle="tooltip" data-placement="top" title="<?php echo $accountName; ?>">
                                          </span>
                                          <span   class="account-number "><?php echo $accountNumber; ?></span>
                                       </td>
                                       <td data-cell ="amount" class="hidden-td" ><?php echo number_format ($row['amount'], 2); ?>
                           </div>
                           </td>
                           <td data-cell ="type" class="hidden-td" ><?php echo $row['type'] ? $row['type'] : 'N/A'; ?></td>
                           <td data-cell ="remarks"  class="hidden-td"><?php echo $row['remarks'] ? $row['remarks'] : 'N/A'; ?></td>
                           <td data-cell ="receipt"  class="hidden-td">
                           <?php
                              if (!empty($receiptPath)) {
                                  $uploadDirectory = "CRUD/create/uploadsCashin/"; // Relative path to the "uploads" directory
                                   $imageUrl = $uploadDirectory . $filename;
                                  echo"<div class='d-flex justify-content-center align-items-center'>";
                                  echo "<a href='$imageUrl' target='_blank'><i style='margin-top:5px; color: #0a346a;font-size:19px;' class='fas fa-file-image fa-1x'></i></a>";
                                  echo "<span class='fa-regular fa-x ml-2 delete-icon' style='font-size:12px; color:#e93838;cursor:pointer;' data-filename='$filename' data-id='{$row['id']}' data-toggle='modal' data-target='#deleteConfirmationModal'></i>";
                                  echo "</div>";
                                 } else {
                                  echo "N/A";
                              }
                              ?>
                        </div>
                        <td  class="menu-action hidden-td">
                        <div class="d-flex ms-5 d-none">
                        <button type="button" class="btn w-100 px-1 pt-0 m-0 mt-0" data-toggle="modal" data-target="#detailsModal" onclick="showTransactionDetails(this);" data-id="<?php echo $row['id']; ?>" data-receipt="<?php echo $filename ?>">
                        <a class="dropdown-item" id="view-item" href="#"><i class="fa fa-eye"></i> <span>Details</span></a>
                        </button>
                        <button type="button" class="btn w-100 px-1 pt-0 m-0 mt-0 edit-button" data-toggle="modal"  data-target="#editIncomeModal"  onclick="editTransaction(this)"  data-id="<?php echo $row['id']; ?>"
                           data-receipt="<?php echo $filename ?>"
                           data-date="<?php echo $row['date']; ?>"
                           data-fund="<?php echo $row['fundAccTitle']; ?>"
                           data-category="<?php echo $row['category_account']; ?>"
                           data-type="<?php echo $row['type']; ?>"
                           data-amount="<?php echo number_format($row['amount'], 2);?>"
                           data-remarks="<?php echo $row['remarks']; ?>">
                        <a class="dropdown-item" id="view-item" href="#"><i class="fa fa-edit"></i> <span>Edit</span></a>
                        </button> 
                        </div>
                        <div class="dropdown buttons d-flex justify-content-center p-0">
                        <button class="btn dropdown-btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li class="d-flex">
                        <!-- "Details" Button Triggering "detailsModal" -->
                        <button type="button" class="btn w-100 px-1 pt-0 m-0 mt-0" data-toggle="modal" data-target="#detailsModal" onclick="showTransactionDetails(this);" data-id="<?php echo $row['id']; ?>" data-receipt="<?php echo $filename ?>">
                        <a class="dropdown-item" id="view-item" href="#"><i class="fa fa-eye"></i> <span>Details</span></a>
                        </button>
                        </li>
                        <!-- "Edit" Button Triggering "editIncomeModal" -->
                        <!-- "Edit" Button Triggering "editIncomeModal" -->
                        <li class="d-flex">
                        <button type="button" class="btn w-100 px-1 pt-0 m-0 mt-0 edit-button" data-toggle="modal"  data-target="#editIncomeModal"  onclick="editTransaction(this)"  data-id="<?php echo $row['id']; ?>"
                           data-receipt="<?php echo $filename ?>"
                           data-date="<?php echo $row['date']; ?>"
                           data-fund="<?php echo $row['fundAccTitle']; ?>"
                           data-category="<?php echo $row['category_account']; ?>"
                           data-type="<?php echo $row['type']; ?>"
                           data-amount="<?php echo number_format($row['amount'], 2);?>"
                           data-remarks="<?php echo $row['remarks']; ?>">
                        <a class="dropdown-item" id="view-item" href="#"><i class="fa fa-edit"></i> <span>Edit</span></a>
                        </button> 
                        </li>
                        </ul>
                        </div>
                        </td>
                        <td><?php echo $row['edit_time']; ?></td>
                        </tr>
                        <?php } ?>
                        </tbody>
                        <tfoot>
                        <tr>
                        <td class="bg-secondary text-white" colspan="5"></td>
                        <td class="d-flex" style="text-align: center; white-space: nowrap;"><span class="font-weight:bold; color:white; font-size:5px;">Total Cash : &nbsp;</span><strong style="font-size:14px;font-family:open-sans;" class="" id="totalAmount"></strong></td>
                        <td style="border:none;"></td>
                        <td style="border:none;"></td>
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
      <!-- edit modal Start-->
      <div class="modal fade" id="editIncomeModal" tabindex="-1" role="dialog" aria-labelledby="editIncomeModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content w-100 vh-100">
               <div class="modal-header d-flex justify-content-center">
                  <h5 class="modal-title" style="margin-left:auto;" id="editIncomeModalLabel">Edit CashinFlow Transaction</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body p-3">
                  <p class="float-right mr-2">Transaction ID: <span id="currentTransactionId"></span></p>
                  <form action="CRUD/update/updateCashInFlow.php" method="POST" enctype="multipart/form-data" class="row col-12" id="updateForm">
                     <!-- Add these hidden fields to store sort and pagination information -->
                     <input type="hidden" id="currentSortColumn" name="currentSortColumn">
                     <input type="hidden" id="currentSortDirection" name="currentSortDirection">
                     <input type="hidden" id="currentPage" name="currentPage">
                     <input type="hidden" name="rowId" id="editRowId" value="<?php echo $rowId;?>">
                     <input type="hidden" name="receiptPath" id="receiptPath" value="<?php echo $receiptPath;?>">
                     <div class="row mx-auto">
                        <div class="form-group col-md-6 ">
                           <label for="editDate">Date:</label>
                           <input type="date" class="form-control" id="editDate" name="date" placeholder="Enter the date...">
                        </div>
                     </div>
                     <div class="form-group col-md-6">
                        <label for="editAccountTitle" >Fund: </label>
                        <select class="form-control" id="editAccountTitle" name="accountTitle">
                           <option value="" selected>Select Where Fund...</option>
                           <?php include 'sqlGetData/getCapitalCategory.php';?>
                        </select>
                     </div>
                     <div class="form-group col-md-6">
                        <label for="editCategory">Category:</label>
                        <select class="form-control" id="editCategory"  name="category">
                           <option value="" selected >Select an Fund Category title...</option>
                           <?php require 'sqlGetData/getRevenueCategory.php';?>
                        </select>
                     </div>
                     <div class="form-group col-md-6">
                        <label for="editAmount" required>Amount:</label>
                        <span class="position-absolute" style="top:39px;left:7px;">&#8369; </span> <input type="text" class="form-control" id="editAmount" name="amount" placeholder="Enter the amount...">
                     </div>
                     <div class="form-group col-md-6">
                        <label for="editType">Type:</label>
                        <select class="form-control" id="editType" c name="type">
                           <option value="" selected>Choose where to deposit..</option>
                           <option value="Gcash">Gcash</option>
                           <option value="Bank">Bank In</option>
                           <option value="Bank">OnHand</option>
                        </select>
                     </div>
                     <div class="form-group col-md-12">
                        <label for="editRemarks">Remarks:</label>
                        <textarea class="form-control" id="editRemarks" name="remarks" placeholder="Enter the remarks..."></textarea>
                     </div>
                     <div class="form-group col-md-12">
                        <label for="editReceipt">Receipt:</label>
                        <div class="custom-file">
                           <!-- Hide the actual file input -->
                           <input type="hidden" id="oldReceipt" name="oldReceipt" value="">
                           <input type="file" class="custom-file-input" id="newReceipt" name="newReceipt"  accept=".png, .jpeg, .jpg, .gif" style="display: none;">
                           <!-- Display a separate label for the new receipt -->
                           <label class="custom-file-label" for="newReceipt" id="receiptName">Choose a File</label>
                           <!-- Add a message div to display the success message -->
                        </div>
                        <div id="successMessage" class="alert alert-success mt-2 text-center" style="display: none;">
                           <div>
                           </div>
                           <span class="ms-2 mb-2 text-center ">
                           <i class="fa fa-check mx-3" ></i>  Your file is being submitted for upload.
                           </span>
                        </div>
                     </div>
                     <div class="form-group col-md-12">
                        <img class="img-fluid shadow-lg" id="editReceiptImage" style="width: 50%; display: block; margin: 10px auto;">
                     </div>
                     <div class="form-group col-md-12">
                        <button type="submit"  onclick="updateTransaction()" name="update" class="btn btn-primary float-right">Update</button>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <!--edit modal end -->
      <!-- Details Modal Start -->
      <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
               <div class="modal-header d-flex justify-content-center">
                  <h5 class="modal-title" style="margin-left:auto;" id="editIncomeModalLabel">Details CashinFlow Transaction</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <div>
                     <p class="float-right mr-2">Transaction ID: <span id="currentTransactionIdDetails"></span></p>
                  </div>
                  <form class="mt-4" action="#" method="POST" enctype="multipart/form-data">
                     <div class="col-12 d-flex justify-content-center align-items-center">
                        <div class="form-group col-md-6 ">
                           <label for="detailDate">Date:</label>
                           <input type="date" class="form-control" id="detailDate" name="date" placeholder="Enter the date..." disabled>
                        </div>
                        <div class="form-group col-md-6">
                           <label for="detailAccountTitle">Fund:</label>
                           <input type="text" class="form-control" id="detailAccountTitle" name="accountTitle" disabled>
                        </div>
                     </div>
                     <div class="row">
                        <div class="form-group col-md-6">
                           <label for="detailCategory">Category:</label>
                           <div class="d-flex">
                              <input type="text" class="form-control col-3 text-center" id="detailCategoryNumber" name="category" disabled> <span class="mt-1">:</span>
                              <input type="text" class="form-control" id="detailCategory" name="category" disabled> 
                           </div>
                        </div>
                        <div class="form-group col-md-6">
                           <label for="detailAmount">Amount:</label>
                           <input type="text" class="form-control" id="detailAmount" name="amount" disabled>
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="detailType">Type:</label>
                        <input type="text" class="form-control" id="detailType" name="type" disabled>
                     </div>
                     <div class="form-group">
                        <label for="detailRemarks">Remarks:</label>
                        <textarea class="form-control" id="detailRemarks" name="remarks" placeholder="Enter the remarks..." disabled></textarea>
                     </div>
                     <div class="form-group">
                        <label for="detailReceipt">Receipt:</label>
                        <img class="img-fluid shadow-lg " src="" id="detailReceiptImage" style="width: 50% ;  display: block; margin: 10px auto;">
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <!-- Details Modal End -->
      <!-- ADD MODAL -->
      <div class="modal fade" id="addIncomeModal" tabindex="-1" role="dialog" aria-labelledby="addIncomeModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content w-100">
               <div class="modal-header d-flex justify-content-center">
                  <h5 class="modal-title" style="margin-left:auto;" id="addIncomeModalLabel">CashinFlow Transaction</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body p-3">
                  <form action="CRUD/create/addCashInflow.php" method="POST"  enctype="multipart/form-data" class="row col-12">
                     <div class="row mx-auto">
                        <div class="form-group col-md-12">
                           <label for="date">Date:</label>
                           <input type="date" class="form-control" required id="date" name="date" placeholder="Enter the date...">
                        </div>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="accountTitle">Fund:</label>
                        <select class="form-control" id="accountTitle" name="accountTitle">
                           <option value="" selected>Select Where Fund...</option>
                           <?php include 'sqlGetData/getCapitalCategory.php';?>
                        </select>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="category">Category:</label>
                        <select class="form-control" id="category" name="category">
                           <option value="" selected >Select an Fund Category title...</option>
                           <?php require 'sqlGetData/getRevenueCategory.php';?>
                        </select>
                     </div>
                     <div class="form-group col-md-6">
                        <label for="amount">Amount:</label>
                        <input type="text" class="form-control" id="amount" name="amount" placeholder="Enter the amount...">
                     </div>
                     <div class="form-group col-md-6">
                        <label for="type">Type:</label>
                        <select class="form-control" id="type" name="type">
                           <option value="" seleced>Choose where to Deposit..</option>
                           <option value="Gcash">Gcash</option>
                           <option value="Bank">Bank In</option>
                           <option value="Bank">OnHand</option>
                        </select>
                     </div>
                     <div class="form-group col-md-12">
                        <label for="remarks">Remarks:</label>
                        <textarea class="form-control" id="remarks" name="remarks" placeholder="Enter the remarks..."></textarea>
                     </div>
                     <div class="form-group col-md-12">
                        <label for="receipt">Receipt:</label>
                        <div class="custom-file">
                           <input type="file" class="custom-file-input" id="receipt" name="receipt" onchange="displayFileNameAdd()" accept=".png, .jpeg, .jpg, .gif">
                           <label class="custom-file-label" for="receipt" id="receiptLabel">Choose file</label>
                        </div>
                     </div>
                     <div class="form-group col-md-12">
                        <button type="submit" class="btn btn-primary float-right">Submit</button>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <!-- END ADD -->
      <!-- Modal for Delete Confirmation -->
      <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
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
   <script src="js/manageIncome.js"></script>
   <!-- js -->
   <script>
      function editTransaction(button) {
       
          var transactionId = button.getAttribute('data-id');
          var date = button.getAttribute('data-date');
          var fund = button.getAttribute('data-fund');
          var amount = button.getAttribute('data-amount');
          var category = button.getAttribute('data-category');
          var type = button.getAttribute('data-type');
          var filename = button.getAttribute('data-filename');
          var remarks = button.getAttribute('data-remarks');
          var receipt = button.getAttribute('data-receipt');
          var currentTransactionId = document.getElementById('currentTransactionId');
          
          var receiptImageElement = document.getElementById('editReceiptImage');
          currentTransactionId.textContent = transactionId;
          
       
       
          document.getElementById('editDate').value = date;
          document.getElementById('editAccountTitle').value = fund;
          document.getElementById('editAmount').value = amount;
          document.getElementById('editCategory').value = category;
          document.getElementById('editType').value = type;
          document.getElementById('editRemarks').value = remarks;
          document.getElementById('currentTransactionId').value = transactionId;
          
          var receiptLabel = document.getElementById('receiptName');
          var oldReceiptInput = document.getElementById('oldReceipt').value= receipt;
          var newReceiptInput = document.getElementById('newReceipt').value;
      
       
      
          // Check if receipt exists or not and update the label accordingly
          if (receipt) {
            receiptImageElement.src = 'CRUD/create/uploadsCashin/' + receipt;
        } else {
            receiptImageElement.src = ''; // Set the src to an empty string to clear any previous image
        }
      
           // Add an event listener for the newReceipt input element
       document.getElementById('newReceipt').addEventListener('change', function() {
      
          var fileInput = this;
          var file = fileInput.files[0];
      
          var oldReceipt = oldReceiptInput;
         var filename = file.name;
         var fileId = transactionId;
      
         //      }
          if (file) {
            receiptImageElement.style.display="none";
             receiptLabel.textContent = file.name;
             var successMessage = document.getElementById('successMessage');
             successMessage.style.opacity = '1';
             successMessage.style.display = 'block';
      
             // Hide the success message smoothly after 1.5 seconds (1500 milliseconds)
             setTimeout(function() {
                successMessage.style.opacity = '0';
         
                setTimeout(function() {
                   successMessage.style.display = 'none';
                }, 500); // Wait for the fade-out transition to complete before hiding the div
             }, 2500);
          } else {
             receiptLabel.textContent = 'Choose a new File';
          }
       });
      
       }
      
      
      
   </script>
   <script>
      $(document).ready(function () {
      // Your existing code...
      
      // Function to remove the alt attribute from all img tags within the modal
      function removeAltFromImages() {
       $("#detailsModal img").removeAttr("alt");
      }
      
      // Call the function when the modal is shown
      $("#detailsModal").on("show.bs.modal", function () {
       removeAltFromImages();
      });
      });
      function showErrorModal(errorMessage) {
         var errorModal = new bootstrap.Modal(document.getElementById("errorModal"));
         var errorMessageElement = document.querySelector("#errorModal .modal-body p");
         errorMessageElement.textContent = errorMessage;
         errorModal.show();
         var closeButton = document.querySelector("#errorModal .btn-secondary");
          closeButton.addEventListener("click", closeModal);
         }
   </script>
   <!-- GET AMOUNT IN INPUT -->
   <script>
      // Get the amount input element
      var amountInput = document.getElementById('amount');
      
      var
      
      // Add event listener for input changes
      amountInput.addEventListener('input', function(e) {
      // Get the input value and remove commas
      var value = e.target.value.replace(/,/g, '');
      
      // Format the value with commas
      var formattedValue = formatNumberWithCommas(value);
      
      // Update the input value with formatted value
      e.target.value = formattedValue;
      });
      
      // Function to format number with commas
      function formatNumberWithCommas(number) {
      return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
      } 
      $(document).ready(function() {
      <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($errors)): ?>
         // Show the modal if there are validation errors
         $('#addIncomeModal').modal('show');
      
         // Add shake effect to the input fields with validation errors
         <?php if (isset($errors['date'])): ?>
            $('#date').addClass('is-invalid');
            $('#date').effect('shake');
         <?php endif; ?>
      
         <?php if (isset($errors['accountTitle'])): ?>
            $('#accountTitle').addClass('is-invalid');
            $('#accountTitle').effect('shake');
         <?php endif; ?>
      
         // Add similar code for other input fields
      
      <?php endif; ?>
      });
   </script>
 
   <script>
      $(document).ready(function() {
         $(".clickable-td").click(function() {
         const clickedTd = $(this); // Get the clicked td element
        
         const allTableCells = clickedTd.closest('tr').find('td'); // Get all cells in the clicked row
         allTableCells.toggleClass('show-cell'); // Toggle the class for all cells in the row
         $('.menu-action').removeClass('menu-action');
      });
      
         // $(".clickable-td").click(function() {
         //    const clickedTd = $(this); // Get the clicked td element
         //    console.log(clickedTd);
         //    // const allTableCells = document.querySelectorAll('tr, td');
         
         //    // allTableCells.forEach(cell => {
         //    //   cell.classList.toggle('show-cell');
         //  });
        });
        
        $(document).ready(function() {
         $("thead").click(function() {
         
            const allTableCells = $("td"); // Get all <td> elements on the page
            allTableCells.removeClass('show-cell '); // Remove the class for all cells
      });
      
         // $(".clickable-td").click(function() {
         //    const clickedTd = $(this); // Get the clicked td element
         //    console.log(clickedTd);
         //    // const allTableCells = document.querySelectorAll('tr, td');
         
         //    // allTableCells.forEach(cell => {
         //    //   cell.classList.toggle('show-cell');
         //  });
        });
        
      
   </script>
</html>
