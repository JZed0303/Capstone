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
      
   
   // Retrieve data from the database
   $sql = "SELECT cashoutflow_tbl.*, chart_of_accounts.child_account_number
   FROM cashoutflow_tbl
   JOIN chart_of_accounts ON cashoutflow_tbl.expense_category = chart_of_accounts.child_account_name
   WHERE cashoutflow_tbl.status = 0;
   ";
   $stmt = $conn->prepare($sql);    
   $stmt->execute();
   $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
   
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>Manage Expenses</title>
      <meta content="width=device-width, initial-scale=1.0" name="viewport">
      <meta content="" name="keywords">
      <meta content="" name="description">
      <!-- css content -->
      <link rel="stylesheet" href="assets/fontawesome-free-6.3.0-web/css/all.css">
      <link rel="stylesheet" href="assets/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/css/budgetManagement.css">
      <!-- <link rel="stylesheet" href="mediaPrint/print.css"> -->
      <!-- DATA TABLES  cs-->
      <link rel="stylesheet" href="assets/datatable/css/datatables.min.css">
      <link rel="stylesheet" href="assets/datatable/css/dataTables.dateTime.min.css">
      <link rel="stylesheet" href="assets/datatable/css/jquery.dataTables.min.css">
      <!-- <link rel="stylesheet" href="assets/css/cashinflow/cashInflow.css"> -->
      <!-- DATA TABLE JS -->
      <script src="assets/datatable/js/jquery-3.6.0.min.js"></script>
      <script src="assets/datatable/css/bootstrap.bundle.min.js"></script>
      <script src="assets/datatable/js/datatables.min.js"></script>   
      <!-- DATA TABLE BUTTONS --> 
      <link rel="stylesheet" href="assets/datatable/css/cdn.datatables.net_responsive_2.5.0_css_responsive.dataTables.min.css">
      <link rel="stylesheet" href="assets/datatable/css/cdn.datatables.net_rowreorder_1.4.1_css_rowReorder.dataTables.min.css">
      <script src="assets/datatable/js/cdn.datatables.net_responsive_2.5.0_js_dataTables.responsive.min.js"></script>
      <script src="assets/datatable/js/cdn.datatables.net_rowreorder_1.4.1_js_dataTables.rowReorder.min.js"></script>
      <script src="assets/datatable/js/pdfmake.min.js"></script>
      <script src="assets/datatable/js/vfs_fonts.js"></script>
      <!-- DATA TABLES DATE FILTER -->
      <script src="assets/datatable/js/moment.min.js"></script>
      <script src="assets/datatable/js/dataTables.dateTime.min.js"></script>
      <script src="assets/datatable/js/manageExpenses.js"></script>
      <!-- general -->
      <script src="assets/js/popper.min.js"></script>
      <script src="assets/js/bootstrap.min.js"></script>
      <script src="assets/js/moment.min.js"></script>
      <script src="assets/js/select2.min.js"></script>
      <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
      <script src="assets/js/script.js"></script>
    
      <!-- Favicon -->
      <!-- Google Web Fonts -->
      <link href="css/bootstrap.min.css" rel="stylesheet">
      <link href="css/style.css" rel="stylesheet">
      <!-- general -->
   </head>
   <style>
      @media (max-width: 500px) { /* Adjust this breakpoint as needed */
      .modal-content {
      padding: 2px; /* Add desired padding for small screens */
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
      }/* Style for the note paper container */
      .note-paper {
      background-color: #f6f5e9; /* Yellowish color */
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      position: relative;
      overflow: hidden;
      }
      /* Style for the lines on the note paper */
      .note-paper::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: repeating-linear-gradient(transparent, transparent 20px, #ddd 20px, #ddd 21px);
      z-index: -1;
      }
   </style>
   <style>
      .modal-content {
      border: none;
      border-radius: 8px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      background-color: var(--gray);
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
      @media (max-width:  550px) {
      .dataTables_length label{
      margin-top: 20px!important;
      margin-left: 10px !important;
      position: relative;
      top: 59px;
      left: -16px;
      }
      #dataTablesExpenses td:nth-child(7) {
      background-color:red!important;
      min-width: 100%!important;
      }.container-fluid{
      padding:1px!important;
      }
      body{
      font-size:14px!important;
      }#dataTablesExpenses thead{
      position: absolute;
      top: 0px;
      left: 0; 
      right: 0;
      
      min-width:100%!important;
      overflow-x: scroll; /* Enable horizontal scroll for thead */
      }.receipt-container{
      justify-content: start!important;
      }.menu-action button{
      font-size:12px!important;
      }
      .tooltip{
      display:none;
      }.container-fluid.wrapper{
      margin:0!important;
      padding:0!important;
      }#dataTablesExpenses th{
      font-size:14px;
      }
      td[data-cell]::before {
      content: attr(data-cell) ": ";
      font-weight: 600!important;
      font-size:14px;
      text-transform: uppercase;
      font-size:14px!important;
      } td[data-cell="accountNumber"],td[data-cell="amount"],td[data-cell="receipt"],td[data-cell="budget"],td[data-cell="location"] {
      text-align: left!important;
      } th[data-cell="accountNumber"],th[data-cell="fund"],td[data-cell="accountCategory"],th[data-cell="account_name"]  {
      display:none!important;
      }#dataTablesExpenses{
      overflow-x:hidden;
      min-width: 100%;
      }#dataTablesExpenses tr{
      border: 0px;
      width: 100%; /* Add this to ensure full width background on hover */
      display: block; /* Add this to ensure full width background on hover */
      }#dataTablesExpenses tbody{
      border:0.4px solid gray !important;
      font-size:14px!important;
      position:relative;
      top:49px;
      z-index: 99;
      left:-14;
      }
      #dataTablesExpenses      th:last-child{
      display: none;
      }.btn.dropdown-btn{
      display:none;
      }#dataTablesExpenses .hidden-td{
      display:none!important;
      transition: opacity 0.3s ease-in-out, 
      } #dataTablesExpenses .show-cell {
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
      overflow-x: auto; /* Show vertical scrollbar when content overflows */
      }.categ{
      margin-left:3.rem;
      }#dataTablesExpenses tfoot{
      position:relative;
      top:49px;
      left:-13;
      }.dataTablesExpenses th {
      background-color: #f2f2f2; /* Header background color */
      position: sticky;
      top: 0; /* Stick to the top of the viewport */
      z-index: 1; /* Ensure the header is above table content */
      }.odd td{
      background-color:rgb(229, 228, 228)!important;
      color:#333333!important;
      }.even td{
      background-color: rgb(200 209 238)!important;   }
      #dataTablesExpenses td{
      display:block!important;
      min-width: 100%!important;
      text-align:left!important;
      font-size:14px!important;
      font-weight:700!important;
      transition: opacity 0.3s ease-in-out, padding 0.3s ease-in-out, gap 0.3s ease-in-out; /* Add smooth transitions for other properties */
      }.container-fluid.wrapper{
      margin:0!important;
      padding:0!important;
      }td[data-cell]::before {
      content: attr(data-cell) ": ";
      font-weight: 500;
      font-size:14px;
      text-transform: uppercase;
      font-size:14px!important;
      }td[data-cell]:first-child::before{
      content: "▼ "attr(data-cell)  ": ";
      }td.show-cell[data-cell]:first-child::before {
      content: attr(data-cell) ": ";
      /* Additional styles for the shown cell */
      } td[data-cell="accountNumber"],td[data-cell="amount"],td[data-cell="receipt"] {
      text-align: left!important;
      }td[data-cell=""]{
      display:none;
      } th[data-cell="accountNumber"],th[data-cell="fund"] {
      display:none!important;
      }.table-responsive{
      overflow-x:auto;
      }#dataTablesExpenses td:first-child{
      border-top:4px solid var(--deam);
      min-width:100%!important;
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
      }.filter{
      margin:31px 10px 5px 10px;
      }
      }
      #changeTargetButton:hover{
      cursor: pointer;
      }  .arrow {
      opacity:0;
      position: absolute;
      bottom: 48px;
      left: 49%;
      margin-left: -7px;
      width: 0;
      height: 0;
      border-left: 10px solid transparent;
      border-right: 10px solid transparent;
      border-top: 10px solid rgb(14, 103, 224);
      }div#dataTablesExpenses_filter {
      margin-top:11px;
      text-align: right;
      display: flex;
      justify-content: flex-end;
      }div.dataTables_wrapper {
      bottom: 28px;
      position: relative;
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
      }.transac{
      color:black  !important;
      }
      /* Modal Content Styling */
      .modal-content {
      border: none;
      border-radius: 20px;
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
      border-top-right-radius: 8px;
      }
      /* Modal Body Styling */
      .modal-body {
      padding:12px 34px 10px 34px;
      }
      /* Input Field Styling */
      .form-group label {
      font-weight: 600;
      color: #4a4949;
      }
      .form-control {
      border: none;
      border-bottom:0.05px solid #9f9fa4;
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
      left: 48px!important;
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
      font-size:14px!important;
      padding:1px!important;
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
      }.detail-modal{
      min-width: 87%!important;
      }input{
      color:dark;
      }.showTile{
      display:none!important;
      }.container {
      position: relative;
      }.container:hover .tooltip {
      opacity: 1;
      visibility: visible;
      }td{
      border:none!important;
      border-bottom:0.1px solid rgb(171, 181, 243)!important;
      }.buttonsContainer {
      position: relative;
      right: 0; /* Initial position, no offset */
      }.edit-btn{
         padding:0px!important;
      }.no-select {
    user-select: none;
    -moz-user-select: none;
    -webkit-user-select: none;
    -ms-user-select: none;
}
   </style>
   <style>
      /* Adjust the font size and margin based on screen size */
      /* Extra Small Devices (portrait phones, less than 576px) */
      @media (max-width: 575.98px) {
      .tooltip{
      position: absolute;
      top: -74px!important;
      left: 143px!important;
      cursor: pointer;
      }
      }
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
        <style id="custom-styles"></style>
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
         <div class="content open">
            <!-- Navbar Start -->
            <?php include 'template/navbar.php'?>
            <?php      include 'template/modal.php'?>
            <!-- Navbar End -->
            <!-- Sale & Revenue Start -->
            <div class="container-fluid pt-4 px-4 px-md-3 px-lg-4 px-2 ">
               <div class="filter mt-5 mt-md-1 mt-lg-1">
                  <div class="row g-3 mx-4 p-md-3 p-lg-3 p-2 ">
                     <!-- filter row -->
                     <div class="ms-3 ms-lg-0 ms-md-0 col-sm-12 col-md-6 col-xl-4 d-flex align-items-center ">
                        <div class="d-flex flex-row mt-2">
                           <span><strong style="position:absolute; top:-22px;left:0;">Filter</strong></span>
                           <input type="text" class="form-control form-control-sm center" id="min" name="min" name="min" placeholder="Start Date" aria-label="Start date" aria-describedby="start-date-icon">
                           <i class="fa fa-calendar"></i> 
                        </div>
                        <div class="d-flex flex-row mt-2">
                           <!-- <input type="text" id="max" name="max">  <i class="fa fa-calendar ml-2"></i>  -->
                           <input type="text" class="form-control form-control-sm center" id="max" name="max" name="min" placeholder="End Date" aria-label="Start date" aria-describedby="start-date-icon">
                           <i class="fa fa-calendar ml-2"></i> 
                        </div>
                     </div>
                     <div class="col-sm-12 col-md-6 col-xl-4 d-flex justify-content-center">
                        <div class="categ col-lg-12 col-sm-10 d-flex flex-column mx-sm-3">
                           <span><strong class="">Category</strong></span>
                           <div class="form-group row">
                              <select class="category-box col-10 col-md-12 form-control form-control-sm">
                                 <option selected >Select Category</option>
                                 <?php require 'sqlGetData/getExpensesCategory.php';?>
                              </select>
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-12 col-xl-4">
                        <span  class=""><strong class="">Search</strong></span>
                        <div class="form-group1 row mx-0 ">
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
            <hr>

            <div class="container-fluid ">
               <div class="row g-2 mx-1 mb-3">
                  <div class="col-12">
                     <div class="dataTable_wrapper income col-12 pt-3 px-0 pb-0">
                        <div class="bg-white rounded h-100  mb-0 pb-0">
                           <div class="table-responsive">
                              <table id="dataTablesExpenses" class="display nowrap table table-striped hover bg-white">
                                 <thead>
                                    <tr>
                                       <th data-cell="Transaction ID">NO.</th>
                                       <th data-cell="date"  id="center" >DATE</th>
                                       <th data-cell="project_title"  style="white-space: normal; word-wrap: break-word; max-width: 100px; overflow: auto;">PROJECT TITLE</th>
                                       <th data-cell="account_name"  style="white-space: normal; word-wrap: break-word; max-width: 100px; overflow: auto;">ACCOUNT NAME</th>
                                       <span class="fa fa-" id="changeTargetButton" style="position: relative;width: 9px;z-index: 99;top: 98px;left: 31%;width: 10%;">
                                          <div>
                                             <div class="message-box no-select">
                                                <div class="arrow no-select"></div>
                                                <div class="clickme no-select " style="opacity: 0;background: rgb(14, 103, 224);white-space: nowrap;position: relative;left: -47px;width: 198px;color: rgb(243, 243, 243);padding: 9px 13px 9px 2px;border-radius: 5px;font-weight: 800;font-size: 14px;text-align: center;top: -58px;font-family: &quot;Open sans-serif&quot;;">
                                                   (Double Click) &nbsp;Account name
                                                </div>
                                             </div>
                                          </div>
                           </div>
                           </span>
                        </div>
                        <th data-cell="acccount_title"  style="white-space: normal; word-wrap: break-word; max-width: 120px; overflow: auto;">ACCOUNT TITLE</th>
                        <th data-cell="amount"  >AMOUNT</th>
                        <th data-cell="budget" >BUDGET</th>
                        <th data-cell="fund" >FUND</th>
                        <th data-cell="location" style="white-space: normal; word-wrap: break-word; max-width: 100px; overflow: auto;">LOCATION</th>
                        <th data-cell="remarks"  >REMARKS</th>
                        <?php 
                           $cssClass = ($role === "guest") ? "d-none" : "";
                           
                           // Generate the <th> element with the class attribute
                           echo '<th class="' . $cssClass . '" data-cell="receipt">RECEIPT</th>';
                           
                           ?>
                        <?php 
                           $cssClass = ($role === "guest" || $role === "auditor" ) ? "d-none" : "";
                           
                           // Generate the <th> element with the class attribute
                           echo '<th class="' . $cssClass . '" ">ACTION</th>';
                           
                           ?> 
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                           require 'includes/dbh.inc.php';
                           $total = 0;
                           foreach ($result as $row) {
                             $total += $row['amount'];
                             $categoryAccount = $row['expense_category'];
                             $query = "SELECT child_account_number, child_account_name FROM chart_of_accounts WHERE child_account_name = :categoryAccount";
                             $stmt = $conn->prepare($query);
                             $stmt->bindParam(':categoryAccount', $categoryAccount);
                               $isAnnualFund = $row['fund'] === 'Annual Fund';
                               $isClubFund = $row['fund'] === 'Club Fund';
                               $receiptPath = $row['receipt'];
                               // Move this line inside the loop to get the correct receipt path for each row
                               $filename = basename($receiptPath);
                             $stmt->execute();
                             $account = $stmt->fetch(PDO::FETCH_ASSOC);
                           
                             // Use the $account data as needed
                             // ...
                           
                           ?>
                        <tr>
                        <td data-cell="Transaction ID" class="clickable-td" > <?php echo 'TXN-RO-' . str_pad($row['id'], 3, '0', STR_PAD_LEFT);?> <span class="showTile ms-4"><?php echo " ".$row['account_title'] ? $row['account_title'] : 'N/A'; ?></span> </td>
                        <td data-cell="date" class=" hidden-td align-middle" ><?php echo $row['date'] ? $row['date'] : 'N/A'; ?></td>
                        <td data-cell="Title" class=" hidden-td align-middle"><?php echo $row['account_title'] ? $row['account_title'] : 'N/A'; ?></td>
                        <td data-cell="accountCategory" class=" hidden-td align-middle" style="white-space: nowrap; word-wrap: break-word; width: 10px; overflow: auto;"><?php echo $row['expense_category'] ? $row['expense_category'] : 'N/A'; ?></td>
                        <td data-cell="accountNumber" class=" hidden-td align-middle"><?php echo $row['child_account_number'] ? $row['child_account_number'] : 'N/A'; ?></td>
                        <td data-cell="amount" class=" hidden-td align-middle"><?php echo number_format($row['amount'], 2); ?></td>
                        <td data-cell="budget" class=" hidden-td align-middle"><?php echo $row['budgetAlloted'] ? $row['budgetAlloted'] : 'N/A'; ?></td>
                        <td data-cell="totalExpense"class=" hidden-td align-middle"><?php echo $row['amount'] ? $row['fund'] : 'N/A'; ?></td>
                        <td data-cell="location" class=" hidden-td align-middle"><?php echo $row['project_location'] ? $row['project_location'] : 'N/A'; ?></td>
                        <td data-cell="remarks"  class=" hidden-td align-middle" style="    white-space: normal;
                           word-wrap: break-word;
                           max-width: 100px;
                           overflow: auto;" ><?php echo $row['remarks'] ? $row['remarks'] : 'N/A'; ?></td>
                        <?php 
                           $cssClass = ($role === "guest" ) ? "d-none" : "";
                           
                           // Generate the <th> element with the class attribute
                        
                           echo '<td class="' . $cssClass . ' hidden-td align-middle"  data-cell ="receipt"  </td>';
 
                           ?>
                        <?php
                           if (!empty($receiptPath)) {
                               $uploadDirectory = "CRUD/create/uploadsCashout/"; // Relative path to the "uploads" directory
                               $imageUrl = $uploadDirectory . $filename;
                              echo "<div class='receipt-container d-flex justify-content-center align-items-center'>";
                               echo "<a href='$imageUrl' target='_blank'><i style='margin-top:5px; color: #0a346a;font-size:19px;' class='fas fa-file-image fa-1x'></i></a>";
                               echo "<span class='fas fa-trash  ml-3' style='font-size: 12px; color: #e93838; cursor: pointer;' data-filename='$filename' data-id='{$row['id']}' data-toggle='modal' data-target='#deleteConfirmationModal'></span>";
                               echo "</div>";
                              } else {
                               echo "N/A";
                           }
                           ?>
                     </div>
                     <?php 
                        $cssClass = ($role === "guest" || $role === "auditor" ) ? "d-none" : "";
                           
                           // Generate the <th> element with the class attribute
                           echo '<td class="' . $cssClass . '"  class="hidden-td align-middle p-0">';
                        
                           ?>
                 <div class="d-flex justify-content-center p-0 hidden-td">
  <button class="btn btn-sm btn-primary" type="button" data-toggle="modal" data-target="#detailsModal" onclick="showTransactionDetails(this);" data-row-id="<?php echo $row['id']; ?>" data-receipt="<?php echo $filename ?>">
    <i class="fa fa-eye"></i>
  </button>
  <button class="btn btn-sm btn-success ms-2" type="button">
    <a class="edit-btn btn btn-sm" id="view-item" href="updateEditExpenses.php?id=<?php echo $row['id']; ?>">
    <i class="fa fa-edit" style="color: white;"></i>
    </a>
  </button>
  <button class="btn btn-sm btn-danger ms-2" type="button" data-bs-toggle="modal" data-bs-target="#archiveConfirmationModal" data-transac-id="<?php echo $row['id']; ?>" data-receipt="<?php echo $filename ?>">
    <i class="fa fa-trash"></i>
</button>
</div>

                     </td>
                     </tr>
                     <?php } ?>
                     </tbody>
                     <tfoot>
                     <tr>
                     <td class="bg-secondary text-white" colspan="5"></td>
                     <td class="d-flex float-right" style="text-align: center; white-space: nowrap;"><span class="font-weight:bold; color:white; font-size:5px;">Total:</span><strong style="font-size:15px;" class="ml-1" id="totalAmount"></strong></td>
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
      <!-- Details Modal Start -->
      <div class="modal fade p-3 p-lg-1 -md-1" id="archiveConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
               <div class="modal-content">
                  <div class="modal-header text-center">
                     <h5 class="modal-title" id="deleteConfirmationModalLabel">Archive</h5>
                     <button type="button" id="topClose" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
                  <form method="POST" action="sqlGetData/addToArchive.php">
                  <div class="modal-body text-center p-4">
                     <input type="hidden" id="transacIdArchive" name="transacId"  value>
                     <p>Are you sure you want to archive this Trasaction?</p>
                  </div>
                  <div class="modal-footer" >
                        <button type="button" id="closebtn" class="btn btn-secondary" data-dismiss="modal" >Cancel</button>
                        <button type="submit" id="archiveCashinflow" name="archiveCashoutflow" class="btn btn-danger" >Yes</button>
                  </div>
                     </form>

                  </div>
               </div>
            </div>
         </div>
      <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered modal-lg" style="min-width:73  `%;" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <div class="">
                     <h5 class="modal-title text-center mt-2 mt-md-0 mt-lg-0" id="detailsModalLabel">CashinFlow Transaction Details</h5>
                  </div>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="row col-12">
                  <p class="transac fw-bolder text-end p-2  m-0  ">Transaction ID: <span id="currentTransactionId" class="d-inline-block"></span></p>
               </div>
               <div class="modal-body">
                  <form action="#" method="POST" enctype="multipart/form-data">
                     <div class="row">
                        <div class="form-group col-md-6">
                           <label for="detailDate">Date</label>
                           <input type="date" class="form-control font-weight-bold" id="detailDate" name="date" placeholder="Enter the date..." disabled>
                        </div>
                        <div class="form-group col-md-6">
                           <label for="detailType">Location:</label>
                           <input type="text" class="form-control font-weight-bold" id="project_location" name="type" disabled>
                        </div>
                     </div>
                     <div class="row">
                        <div class="form-group col-md-12 text-center">
                           <label for="detailCategory">Expense Category:</label>
                           <div class="d-flex text-center">
                              <input type="text" class="form-control col-3 text-center font-weight-bold" id="detailCategoryNumber" name="category" disabled> <span class="mt-1">:</span>
                              <input type="text" class="form-control text-center font-weight-bold" id="detailCategory" name="category" disabled> 
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="form-group col-md-12">
                           <label for="detailAccountTitle">Project Title:</label>
                           <input type="text" class="form-control text-center font-weight-bold" id="detailAccountTitle" name="accountTitle" disabled>
                        </div>
                     </div>
                     <hr>
                     <div class="row d-flex">
                        <!-- Table to display data from the database -->
                        <div class="form-group col-md-6">
                           <label for="detailAmount">Finance Through</label>
                           <div class="financeThroughTable px-5 col-12" id="financeThroughTable"></div>
                        </div>
                        <div class="form-group col-md-6">
                           <label for="detailAmount">Expense </label>
                           <div class="expenseListTable  col-12" id="expenseListTable"></div>
                        </div>
                     </div>
                     <hr>
                     <div class="row pe-5">
                        <div class="col-md-12 text-end pe-4">
                           <p><strong class="text-dark">Total Budget : &nbsp; &nbsp;</strong>  <strong class="text-dark"><span id="totalBudget"></span></strong> </p>
                           <p><strong class="text-dark">Total Expenses:&nbsp;&nbsp;</strong>  <strong class="text-dark"><span id="totalExpense"></span></strong> </p>
                           <!-- Show remaining budget only if it's positive or zero -->
                           <div>
                              <hr style="background: #9f9fa4;width: 44%;margin-left: 62%;height: 2px;opacity:1;">
                              <p><strong class="text-dark">Remaining Budget:&nbsp;&nbsp;</strong>  <strong class="text-dark"><span id="remainingBudget"></span></strong></p>
                           </div>
                        </div>
                     </div>
                     <div class="form-group note-paper">
                        <label for="detailRemarks">Remarks:</label>
                        <textarea class="form-control font-weight-bold font-italic" id="detailRemarks" style="background-color:#f6f5e9;" name="remarks" placeholder="Enter the remarks..." disabled></textarea>
                     </div>
                     <div class="form-group">
                        <label for="detailReceipt">Receipt:</label>
                        <div classs="container-fluid ">
                           <div class="shadow-lg ">
                              <img class="img-fluid " src="" id="detailReceiptImage" style="width: 50% ;  display: block; margin: 10px auto;">
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <!-- Details Modal End -->
      <!-- edit modal Start-->
      <!--edit modal end -->
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
   <script src="js/manageExpenses.js"></script>
   <script src="js/cdn.jsdelivr.net_npm_bootstrap@5.0.0_dist_js_bootstrap.bundle.min.js"></script>
   </script>
   <script>
      $(document).ready(function () {
          // When hovering over changeTargetButton, show clickme
          $("#changeTargetButton").mouseenter(function () {
              $(".clickme").css("opacity", "1");
              $(".arrow").css("opacity", "1");
              
          });
      
          // When mouse leaves changeTargetButton, hide clickme
          $("#changeTargetButton").mouseleave(function () {
              $(".clickme").css("opacity", "0");
              $(".arrow").css("opacity", "0");
          });
      });
   </script>
   <script>
      // function to delete a receipt
      $(document).ready(function () {
          // Function to handle the "Delete Receipt" button click from the trash icon
          function deleteReceipt(button) {
              var filename = $(button).data('filename');
              var rowID = $(button).data('id');
              $('#deleteFilename').val(filename);
              $('#deleteRowID').val(rowID);
      
              // Update the modal body to show the ID and Filename
              $('#modalReceiptID').text(rowID);
              $('#modalFilename').text(filename);
      
              $('#deleteConfirmationModal .btn-danger').on('click', function () {
              var fileIDExpense = $('#deleteRowID').val();
              var fileNameExpense = $('#deleteFilename').val();
      
              // Send the data to the server using AJAX
              $.ajax({
                  type: 'POST',
                  url: 'CRUD/delete/deleteExistReceipt.php', 
                  data: { fileIDExpense: fileIDExpense, fileNameExpense: fileNameExpense },
                  success: function(response) {
                      // Handle the response here if needed
                      console.log('File deleted successfully.');
                               // Close the modal after sending the data
              $('#deleteConfirmationModal').modal('hide');
              location.reload();
                  },
                  error: function(xhr, status, error) {
      
                      console.error('Error deleting the file:', error);
                      alert(response);
                  }
              });
      
      
          });
      
          }
      
          // Add a click event listener to all elements with the class "delete-icon"
          $(document).on('click', '.delete-icon', function () {
              deleteReceipt(this);
          });
      
          // Add a click event listener to the "Delete" button inside the confirmation modal
          $('#deleteConfirmationModal .btn-danger').on('click', function () {
              // Rest of the code for deleting the receipt goes here...
          });
      });
      
      $(document).ready(function() {
    $('.btn.btn-sm.btn-danger').on("click", function() {
        var transacId = $(this).data("transac-id");
        $('#transacIdArchive').val(transacId);
    });
});
$(document).ready(function() {
var role= "<?php echo $role?>";
var customStyles = '';
    if(role === 'guest'){
        // If the role is 'guest', update the CSS property for the specified selector
        $('table.dataTable tbody td','').css('padding', '10px');
        customStyles += '.clickme.open { left: -43px!important; top: -49px!important; }';
            customStyles += '.arrow.open { left: 51%!important; bottom: 39px!important; }';
            customStyles += '.message-box.open { position: relative; z-index: 999; bottom: 23px; left: 40px!important; }';
            customStyles += '.message-box{position:relative;left:69px!important; top: -20!important; }';
            customStyles += '.arrow { bottom: 40px!important; left: 46%!important; }';
            customStyles += '.clickme { left: -34px!important; top: -50px!important; }';
            $('#custom-styles').text(customStyles); // Apply styles to the style tag in the head
        
    

    } else{
      $('table.dataTable tbody td','').css('padding', '10px 10px');
      customStyles += '.clickme.open { left: -43px!important; top: -49px!important; }';
            customStyles += '.arrow.open { left: 51%!important; bottom: 39px!important; }';
            customStyles += '.message-box.open { position: relative; z-index: 999; bottom: 23px; left: 48px!important; }';
            customStyles += '.message-box{position:relative;left:-27px!important; top: -13!important; }';
            customStyles += '.arrow { bottom: 40px!important; left: 46%!important; }';
            customStyles += '.clickme { left: -34px!important; top: -50px!important; }';
            $('#custom-styles').text(customStyles); // Apply styles to the style tag in the head

    }
 
});
      
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
      // Get the amount input element
      var amountInput = document.getElementById('amount');
      
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
      
           // Check if any cell has the class 'show-cell'
           const hasShowCell = $('.show-cell').length > 0;
      
           // Toggle 'd-none' class for showTile based on 'show-cell'
           $('.showTile').toggleClass('d-none', hasShowCell);
           $('.menu-action').toggleClass('d-none', hasShowCell);
        });
      
        $("thead").click(function() {
           const allTableCells = $("td"); // Get all <td> elements on the page
           allTableCells.removeClass('show-cell'); // Remove the class for all cells
      
           // Toggle 'd-none' class for showTile and menu-action based on 'show-cell'
           $('.showTile').toggleClass('d-none', false);
           $('.menu-action').toggleClass('d-none', false);
        });
      });
      
      $(document).ready(function() {
      // Add this code to set the background color to white for the entire print view
      $("body").css("background-color", "white");
      
      // Rest of your code...
      });
           
        
   </script>
</html>