<?php
   session_start();
   
   
   
   if (empty($_SESSION['role'])) {
      header('Location: index.php'); 
      exit;
   
      
   }else if (!isset($_SESSION['logged_in'])){
   
   header('Location: index.php'); 
   exit;
   }else{
   $role = $_SESSION["role"];
   $id = $_SESSION["id"];
   }
   
   
   include 'CHARTJS/chart.php';
   include 'CHARTJS/chartCashouflow.php';
   
   include 'sqlGetData/getBreakDownCashOutFlow.php';
   include 'sqlGetData/getClubCapital.php';
   include 'sqlGetData/getExpensesListDetails.php';
   //    include 'sqlGetData/allExpenseDataSpecific.php';
   
   
   if ($_SERVER['REQUEST_METHOD'] === 'GET') {
   
      // Define the start and end date range you want to cover
      $start_date = '2023-01';
      $end_date = '2023-12';
   
      // Initialize an array to store the months
      $months = [];
   
      // Generate an array of months within the date range
      $current_date = $start_date;
      while ($current_date <= $end_date) {
          $months[] = $current_date;
          // Move to the next month
          $current_date = date('Y-m', strtotime($current_date . '-01 +1 month'));
      }
   
      // Query to retrieve cash inflow data
      $cashinflow_sql = "SELECT DATE_FORMAT(date, '%Y-%m') AS month, SUM(amount) AS total_amount FROM cashinflow_tbl GROUP BY DATE_FORMAT(date, '%Y-%m') ORDER BY DATE_FORMAT(date, '%Y-%m')";
      $cashinflow_result = $conn->query($cashinflow_sql);
   
      // Initialize an array to store the cash inflow amounts
      $cashinflow_data = [];
   
      // Loop through the result set for cash inflow
      while ($cashinflow_data_row = $cashinflow_result->fetch(PDO::FETCH_ASSOC)) {
          // Store the amount in the corresponding month, using the month as the key
          $cashinflow_data[$cashinflow_data_row['month']] = $cashinflow_data_row['total_amount'];
      }
   
      // Query to retrieve cash outflow data
      $cashoutflow_sql = "SELECT DATE_FORMAT(date, '%Y-%m') AS month, SUM(amount) AS total_amount FROM cashoutflow_tbl GROUP BY DATE_FORMAT(date, '%Y-%m') ORDER BY DATE_FORMAT(date, '%Y-%m')";
      $cashoutflow_result = $conn->query($cashoutflow_sql);
   
      // Initialize an array to store the cash outflow amounts
      $cashoutflow_data = [];
   
      // Loop through the result set for cash outflow
      while ($cashoutflow_data_row = $cashoutflow_result->fetch(PDO::FETCH_ASSOC)) {
          // Store the amount in the corresponding month, using the month as the key
          $cashoutflow_data[$cashoutflow_data_row['month']] = $cashoutflow_data_row['total_amount'];
      }
   
      // Create an array to store the final dataset with all months and amounts
      $final_data = [
          'months' => $months,
          'cashinflow' => [],
          'cashoutflow' => [],
      ];
   
      // Fill in the cash inflow and cash outflow amounts
      foreach ($months as $month) {
          $final_data['cashinflow'][] = isset($cashinflow_data[$month]) ? $cashinflow_data[$month] : 0;
          $final_data['cashoutflow'][] = isset($cashoutflow_data[$month]) ? $cashoutflow_data[$month] : 0;
      }
   
   } else {
      // Handle other request methods if needed
      http_response_code(405); // Method Not Allowed
      echo "Unsupported request method.";
   }
   
   ?>
<!DOCTYPE html><!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>Financial Graphs</title>
      <meta content="width=device-width, initial-scale=1.0" name="viewport">
      <meta content="" name="keywords">
      <meta content="" name="description">
      <!-- css content -->
      <link rel="stylesheet" href="assets/fontawesome-free-6.3.0-web/css/all.css">
      <link rel="stylesheet" href="assets/css/bootstrap.min.css">
      <script src="assets/js/popper.min.js"></script>
      <script src="assets/js/bootstrap.min.js"></script>
      <script src="assets/js/moment.min.js"></script>
      <script src="assets/js/select2.min.js"></script>
      <script src="js/code.jquery.com_jquery-3.7.0.js"></script>
      <!-- <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script> -->
      <script src="assets/js/script.js"></script>
      <link href="css/bootstrap.min.css" rel="stylesheet">
      <link href="css/style.css" rel="stylesheet">
      <script src="assets/js/maxcdn.bootstrapcdn.com_bootstrap_4.5.2_js_bootstrap.min.js"></script>
      <!-- general -->
   </head>
   <style>
      ::-webkit-scrollbar {
      width: 3px;
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
      }
      .order-card {
      color: #fff;
      }.bg-c-gray{
      background: linear-gradient(45deg,#e8ebef,#a5adb7)!important;
      }.bg-c-fund{
      background: linear-gradient(45deg,#4099ff,#0f4685)!important;
      color: white!important;
      }
      .bg-c-blue {
      /* background: linear-gradient(32deg,#1f6cc3,#1e78dd,#2e83e9,#4494f5,#7bb4f7,#077cff,#277bd9)!important; */
      /* background: linear-gradient(317deg,#1f6cc3,#1e78dd,#2e83e9,#2485fb,#56a4ff,#077cff,#1573dd)!important; */
      /* background: linear-gradient(90deg,#90caf9,#047edf 99%); */
      background:linear-gradient(32deg,#1f6cc3,#1e78dd,#2e83e9,#4494f5,#55a0f9,#077cff,#277bd9)!important;
      /* background: linear-gradient(42deg,#4c5662,#077cff)!important; */
      }
      .bg-c-green {
      background: linear-gradient(32deg,#1fc3b7,#1ec4dd,#2ebde9,#44b9f5,#55caf9,#07abff,#278bd9)!important;
      /* background:linear-gradient(28deg,#03b599,#03b599,#84d9d2,#03b599); */
      }
      .bg-c-yellow {
      background: linear-gradient(32deg,#f3991b,#fba01f,#ffaf2e,#fdbe6c,#ffa11b,#ffb400)!important;
      /* background: linear-gradient(83deg,#db9b24b3,#ffa500 99%); */
      /* background: linear-gradient(32deg,#f3991b,#fba01f,#ffa92e,#fdc06c,#ffa11b,#ff9500)!important; */
      /* background: linear-gradient(42deg,#cd7d0b,#ffba58)!important; */
      }
      .bg-c-pink {
      /* background: linear-gradient(47deg,#cb1d3a,#f193a3)!important; */
      background: linear-gradient(45deg,#2ed8b6,#59e0c5)!important;
      /* background: linear-gradient(313deg,#e51336,#ff1c41,#f92c4e,#ff284b)!important; */
      /* background: linear-gradient(316deg,#1fd1ad,#3bcfb1,#18cba8,#17d7b1,#2ed8b6,#67edd1,#16dbb3,#2ed1b0,#16dbb3)!important; */
      /* background: linear-gradient(90deg,#ffbf96,#fe7096); */
      }
      .card {
      border-radius: 5px;
      -webkit-box-shadow: 0 1px 2.94px 0.06px rgba(4,26,55,0.16);
      box-shadow: 0 1px 2.94px 0.06px rgba(4,26,55,0.16);
      border: none;
      margin-bottom: 30px;
      -webkit-transition: all 0.3s ease-in-out;
      transition: all 0.3s ease-in-out;
      }
      .card .card-block {
      padding: 25px;
      }
      .order-card i {
      font-size: 26px;
      }
      .f-left {
      float: left;
      }
      .f-right {
      float: right;
      }
   </style>
   <style>
      .body{
      font-family:'Open-sans'!important;
      }  .card-box:hover .amount {
      transform: scale(1.02); /* Increase font size by scaling */font-size: 24px; /* Adjust the font size as needed */
      margin-right:2px;
      transition: transform 0.3s ease-in-out;
      }
      /* Adjust the font size and margin based on screen size */
      /* Extra Small Devices (portrait phones, less than 576px) */
      @media (max-width: 575.98px) {
      .card-wrap {
      margin-top: 1px !important;
      }
      .peso-sign {
      font-size: 14px !important;
      }
      .amount {
      font-size: 18px;
      }
      .dataTables_paginate {
      position: relative;
      top: -14px;
      background-color: white;
      }
      .icon-box {
      font-size: 19px !important;
      }
      td {
      display: grid;
      padding: 0.75rem 1rem;
      grid-template-columns: 4ch auto;
      }
      /* Remove text-right alignment from the last td element */
      td:last-child {
      text-align: center;
      }
      td::before {
      content: attr(data-cell)"";
      font-weight: 700;
      display: block; /* Make the ::before pseudo-element a block-level element */
      text-transform: capitalize;
      }
      td:first-child {
      padding-top: 1rem;
      }
      td:last-child {
      padding-bottom: 1rem;
      }
      th {
      display: none;
      }td.text-right {
      text-align: center!important;
      }
      }
      @media (max-width: 550px) {
      .amount{
      font-size:17px!important;
      }.icon-box{
      font-size:12px!important;
      }.card-box-1:hover .icon-box,.card-box-2:hover .icon-box,.card-box-3:hover .icon-box:hover{
      transform: scale(1)!important; /* Scale up the icon when hovering over the card-box */
      transition: transform 0.8s ease !important;
      padding:3px;
      }p.amount {
      font-size: 27px!important;
      }.chartbox {
      width:90%!important;
      height:45%!important;
      }.incomeBar,.expenseBar{
        display:none!important;
      }
      }
      /* Small Devices (landscape phones, 576px and up) */
      /* Add this CSS inside your media query for (min-width: 580px) and (max-width: 767.98px) */
      @media (min-width: 580px) and (max-width: 767.98px) {
      .card-wrap {
      margin-top: 4px !important;
      }
      .peso-sign {
      font-size: 15px !important;
      }
      .amount {
      font-size: 18px;
      }
      .dataTables_paginate {
      position: relative;
      top: -14px;
      background-color: white;
      }
      }
      /* Medium Devices (tablets, 768px and up) */
      @media (min-width: 768px) and (max-width: 991.98px) {
      .peso-sign {
      font-size: 18px;
      }
      .amount {
      font-size: 22px;
      }
      .dataTables_paginate {
      position: relative;
      top: -14px;
      background-color: white;
      }
      }
      /* Large Devices (desktops, 992px and up) */
      /* Landscape mode with 1169px width and 381px height */
      /* Extra Large Devices (large desktops, 1200px and up) */
      @media (max-width: 1200px) {
      .card-wrap {
      margin-top: 2px !important;
      }
      .peso-sign {
      font-size: 18px;
      }
      .amount {
      font-size: 24px;
      }
      .dataTables_paginate {
      position: relative;
      top: -14px;
      background-color: white;
      }
      } 
      .card-box {
      transition: transform 0.3s;
      min-height:85%;
      } .card-box-1:hover .icon-box,.card-box-2:hover .icon-box,.card-box-3:hover .icon-box {
      padding-left: 9px;
      transform: scale(1.05); /* Scale up the icon when hovering over the card-box */
      transition: transform 0.8s ease; /* Add a smooth transition effect */
      } .card-box-1:hover,.card-box-2:hover,.card-box-3:hover{
      color:white!important;
      }.card-box:hover {
      transform: translateY(-px);
      }
      .card-box:hover .icon-box, .card-box:hover .text-end{
      color:white!important;
      }
      .card-box:hover .amount p {
      color: white!important; /* Change the color to white or any other desired color */
      }.card-info p{
      color:white;
      }.icon-box {
      font-size: 24px!important;
      position: relative;
      top: -39px;
      }
      .icon-box-fund {
      color:white;
      }.card-box-3{
      background-color: #0896af;
      box-shadow: 0px 6px 8px #272727;
      color: #e4e4e4;
      color:white
      }.amount,.icon-box {
      transition: transform 0.3s;
      color:white!important;
      font-weight:700;
      }  .no-data-chart {
      height:332px; /* Set the desired height */
      }/* Styles for the modal container */
      .modal-container {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.7);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999;
      backdrop-filter: blur(8px);
      }
      /* Styles for the modal content */
      .modal-content {
      border: none;
      border-radius: 8px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      /* Responsive width for tablets */
      width: 80%;
      /* Responsive width for phones */
      }
      /* Styles for the modal header */
      .modal-header {
      border-bottom: none;
      padding: 20px;
      background-color: var(--azure);
      color: var(--white);
      border-bottom: var(--gold) 6px solid;
      text-align: center;
      }
      /* Styles for the modal footer */
      .modal-footer {
      text-align: center;
      }
      /* Styles for the modal submit button */
      .modal-submit {
      padding: 10px 20px;
      border-radius: 5px;
      }
      /* Additional styles for the body when the modal is shown */
      .modal-open {
      overflow: hidden;
      }
      /* Styles for the blurred content behind the modal */
      .modal-blur-content {
      filter: blur(8px);
      }  /* Style for the input field container */
      .input-container {
      position: relative;
      width: 100%;
      }
      /* Style for the input field */
      .modal-input {
      width: 100%;
      padding-left:20px;
      border:3px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
      font-family: 'Open Sans', sans-serif;
      outline: none;
      transition: border-color 0.3s ease;
      box-sizing: border-box;
      }
      /* Style for the input field when focused */
      .modal-input:focus {
      border-color: #007bff;
      box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
      }
      /* Style for the error state */
      .modal-input.error {
      border-color: #dc3545;
      }
      /* Style for the error message */
      .error-message {
      color: #dc3545;
      font-size: 14px;
      font-weight: bold;
      }  .table-responsive {
      overflow-x: auto;
      max-width:100%;
      } .fund-box{
      scrollbar-width: thin;
      scrollbar-color: transparent transparent;
      }
      .fund-box::-webkit-scrollbar {
      width: 2px;
      height: 1px;
      }
      .fund-box::-webkit-scrollbar-thumb {
      background-color: transparent;
      }
      .fund-box:hover::-webkit-scrollbar-thumb {
      background-color: var(--white);
      }.amount-fund{
      color:var(--white)!important;
      }.card-wrap{
      min-height:100%!important;
      }.card-box img{
      position: absolute;
      top: 0;
      right: 0;
      height: 100%;
      }.card-box p{
      text-transform:uppercase;
      }p.amount {
      position:relative!important;
      font-size: 22px;
      z-index: 99!important;
      top:4px!important;
      }.line-amount{
      position: absolute;
      z-index: 99;
      top: 60%;
      width: 47%;
      }.bg-amount-dakren{
      opacity: 0.2;
      border-radius:10px;
      position: absolute;
      padding: 18px;
      width: 57%;
      top: 84px;
      left: 32px;
      background: #462f10!important;
      }.amount.mx-2{
      z-index: 99!important;
      } .bg-amount-dakren-out{
      border-radius:6px;
      opacity: 0.2;
      position: absolute;
      padding: 18px;
      width: 57%;
      top: 84px;
      left: 32px;
      background: #995e0b!important;
      } .bg-amount-dakren-in{
      opacity: 0.2;
      border-radius:10px;
      position: absolute;
      padding: 18px;
      width: 57%;
      top: 84px;
      left: 32px;
      background: #0b3665!important;
      }  
      .row{
      background:#e5eaed85!important;
      }.chart.text-center.rounded.p-4.shadow-lg.bg-white {
      border-radius: 9px!important;
      }#legend{
      margin-bottom:20px;
      }p.text-center.pt-2 {
      border-radius: 10px;
      }.fund-col.open{
      max-height:427px!important;
      }td{
      border:none;
      border-bottom:0.5px solid rgb(171, 181, 243);
      }.cashout:hover{
        background-color:#dd950e!important;
      }
   </style>
   <body>
      <div class="container-fluid position-relative d-flex p-0">
         <!-- Spinner Start -->
         <!-- Spinner End -->
         <!-- Sidebar Start -->
         <?php 
         include 'template/all_sidebar.php';
         
         ?>
         <!-- Sidebar End -->
         <!-- Content Start -->
         <?php 
            include 'template/modal.php';
            ?>
         <div class="content">
            <!-- Navbar Start -->
            <?php include 'template/navbar.php'?>
            <!-- Navbar End -->
            <div class="col-md-4 col-xl-3">
            </div>
            <div class="container-fluid px-md-5 pt-sm-1 px-sm-4">
               <div class="card-wrap gx-md-4 px-3 px-md-0 px-lg-0">
                <hr>
               <div class="container text-end justify-content-end mt-3">
        <button class="btn btn-primary btn-sm" onclick="scrollToSection('section-1')"> Cashinflow</button>
        <button class="cashout btn btn text-white btn-sm" style="background-color:#f2a81b;" onclick="scrollToSection('section-2')">Cashoutflow</button>
        <button class="btn btn-secondary btn-sm" onclick="scrollToSection('section-2')">Print</button>
    </div>
  
                  <div class=" pt-2 px-md-0 px-lg-0">
                <section class="section-1">

               
                    <div class="row">
                        
                        <div class="column-chart col-12 m-0  ms-0  m-0   p-1 p-md-3 ps-md-0 p-lg-3 pt-3 pb-3">
                           <div class="chart text-center rounded p-4 shadow-lg" style="background:white!important;">
                              <div class="d-md-flex align-items-center bg-primary justify-content-between mb-4 text-white rounded-3 px-2">
                                 <h6 class="mb-0 pt-3" style="color: white;font-weight:700!important">Monthly Cashoutflow</h6>
                                 <hr class="">
                              </div>
                              <div id="noDataMessage" class="text-primary p-2 rounded-3" style="display: none;">
                              </div>
                              <div class="chartbox">
                                 <canvas id="cashFlowChart" width="1000" height="350"></canvas>
                              </div>
                              <!-- Adjust height to make it shorter -->
                           </div>
                        </div>
                    <div class="row mx-2">
                        
                 
                    <div class="incomeBar column-chart mt-md-3 col-12 col-md-8 col-lg-8 m-0 ps-3  ms-2 ms-md-0 p-0 p-md-2 p-lg-2 pe-md-0 pe-lg-0 pt-3 px-sm-0 mt-1">
                        <div class="chart text-center rounded ps-4  shadow-lg" style="background:white!important;">
                           <div class="d-md-flex align-items-center justify-content-between mb-4 text-white rounded-3 px-2">
                      
                        
                           </div>
                           <div id="noDataMessage" class="text-primary p-2 rounded-3" style="display: none;">
                           </div>
                           <div class="chartbox">
                              <canvas id="incomeChart"  width="1000" height="450"></canvas>
                           </div>
                           <!-- Adjust height to make it shorter -->
                        </div>
                     </div>
                 
                     <div class="column-chart  ps-md-0 ps-lg-0   col-12 col-md-4 col-lg-4 m-0 pb-sm-3  p-md-5 pe-md-2 pt-md-0 ps-3 pb-0 pt-3 mt-0 pt-0  ">
                        <div class="chart text-center rounded-3 p-4 shadow-lg" style="background:white!important;">
                           <div class="d-md-flex align-items-center justify-content-between mb-4 text-white rounded-3 px-2">
                            
                         
                           </div>
                           <div id="noDataMessage" class="text-primary p-2 rounded-3" style="display: none;">
                           </div>
                           <div class="chartbox">
                              <canvas id="incomeChartPie"  width="1000" height="350"></canvas>
                           </div>
                           <!-- Adjust height to make it shorter -->
                        </div>
                     </div>
                  </div>
                    </div>
                        
                     </div>
                  </div>
              
             
 <div class="row mb-4">
                     

               </div>
             
              
               <div class="row">
                 <section class="section-2">
                 <div class="column-chart col-12 m-0 md-m-0  ms-0  m-0   p-1 ">
                           <div class="chart text-center rounded p-4 shadow-lg" style="background:white!important;">
                              <div class="d-md-flex align-items-center bg-primary justify-content-between mb-4 text-white rounded-3 px-2">
                                 <h6 class="mb-0 pt-3 pt-md-0 pt-lg-0" style="color: white;font-weight:700!important">Monthly Cashoutflow</h6>
                                 <hr class="">
                              </div>
                              <div id="noDataMessage" class="text-primary p-2 rounded-3" style="display: none;">
                              </div>
                              <div class="chartbox">
                                 <canvas id="cashOutFlowChart" width="1000" height="350"></canvas>
                              </div>
                              <!-- Adjust height to make it shorter -->
                           </div>
                        </div>
                        <div class="row mt-3">

                        <div class="expenseBar column-chart col-12 col-md-8 col-lg-8 m-0   ms-2 ms-md-0 ps-5 ps-md-2 pe-md-0  pt-3 pb-3">
                           <div class="chart text-center rounded p-4 shadow-lg" style="background:white!important;">
                              <div class="d-md-flex align-items-center justify-content-between mb-4 text-white rounded-3 px-2">
                  
                              </div>
                              <div id="noDataMessage" class="text-primary p-2 rounded-3" style="display: none;">
                              </div>
                              <div class="chartbox">
                                 <canvas id="expenseChart" width="1000" height="500"></canvas>
                              </div>
                              <!-- Adjust height to make it shorter -->
                           </div>
                        </div>
                        <div class="ccolumn-chart pe-md-2  ps-md-0 ps-lg-0 mt-4 mt-md-0 mt-lg-0 col-12 col-md-4 mt-md-2 col-lg-4 m-0 ps-4 pb-sm-3 pe-4 p-5 ps-0 mt-0 pt-0      ">
                        <div class="chart text-center rounded-3 p-4 shadow-lg " style="background:white!important;">
                           <div class="d-md-flex align-items-center justify-content-between mb-4 text-white rounded-3 px-2">
                       
                           </div>
                           <div id="noDataMessage" class="text-primary p-2 rounded-3" style="display: none;">
                           </div>
                           <div class="chartbox">
                              <canvas id="expenseDonutChart"  width="1000" height="350"></canvas>
                           </div>
                           <!-- Adjust height to make it shorter -->
                        </div>
                     </div>
                  </div>
                        </div>

               </div>
            </div>
       
               
            <hr>
            <!-- Recent Sales Start -->
            <!-- Recent Sales End -->
            <!-- Footer Start -->
            <!-- modal -->
            <!-- Footer End -->
         </div>
         <!-- Content End -->
         <!-- Back to Top -->
         <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="fa  fa-arrow-up"></i></a>
      </div>
   </body>
   <!-- JavaScript Libraries -->
   <script>
      function formatAmount(inputElement) {
      const value = inputElement.value.replace(/[^\d.]/g, ''); // Remove any non-digit and non-decimal characters
      const parts = value.split('.'); // Split the value into integer and decimal parts
      
      // Format the integer part with a comma as the thousand separator
      parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
      
      // Limit the decimal part to two decimal places
      if (parts[1] && parts[1].length > 2) {
       parts[1] = parts[1].substring(0, 2);
      }
      
      // Join the formatted parts and set the value back to the input field
      inputElement.value = `₱ ${parts.join('. ')}`;
      }
      
      $(document).ready(function() {
      var modal = $('.modal-container');
      var submitButton = $('.modal-submit');
      var inputField = $('#capital_amount');
      var success = $('.modal-container.modal-success');
      var closeButton = success.find('.modal-close');
      var modalCol= $('.modal-col');
      
      // Show the modal and prevent body scrolling
      function showModal() {
      modal.show();
      $('body').addClass('modal-open');
      $('.content-to-be-blurred').addClass('modal-blur-content');
      modalCol.removeClass('d-none');
      }
      
      // Hide the modal and remove the blur effect from the content
      function hideModal() {
      modal.hide();
      $('body').removeClass('modal-open');
      $('.content-to-be-blurred').removeClass('modal-blur-content');
      }
      hideModal(); // Hide the modal if initialFund has a value
      // Use AJAX to fetch the initialFund value from the PHP script
      $.ajax({
       type: 'GET',
       url: 'sqlGetData/getInitialCapital.php', // Replace with the actual path to your PHP script
       dataType: 'json',
       success: function(response) {
         if (response.initialFund !== undefined) {
           var initialFund = response.initialFund;
         
           // Now you can use the initialFund value in your code
           if (initialFund == 0) {
             showModal(); // Display the modal if initialFund is zero
           } else {
             hideModal(); // Hide the modal if initialFund has a value
           }
         }
       },
       error: function() {
         // Handle the error if the AJAX request fails
         alert('Error fetching data from the server.');
       }
      });
      
      modal.on('click', function(event) {
       event.stopPropagation();
      });
      
      submitButton.on('click', function() {
       var inputText = inputField.val();
       var cleanedText = inputText.replace(/[,₱]/g, '');
      
       if (parseFloat(cleanedText) > 0) {
         modal.hide();
         // Here you can handle the form submission and store the initial capital value using AJAX
         // For example, you can send the value to the PHP script using another AJAX request.
       } else {
         alert("Please enter the correct capital funding amount.");
       }
      });
      
      
      closeButton.on('click', function() {
       success.hide();
      });
      
      $(document).on('click', function(event) {
       if ($(event.target).closest('.modal-container').length === 0) {
         success.hide();
       }
      });
      });
   </script>
   <!-- charts -->
   <script>//
      document.addEventListener('DOMContentLoaded', function () {
          // Check if data is empty or undefined
          const data = <?php echo isset($final_data) ? json_encode($final_data) : 'null'; ?>;
          const ctx = document.getElementById('cashFlowChart').getContext('2d');
      
          if (data && data.cashinflow) { // Check if cashinflow data exists
              const monthNames = [
                  'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                  'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
              ];
      
              const cashFlowChart = new Chart(ctx, {
                  type: 'line', 
                  data: {
                      labels: monthNames,
                      datasets: [
                          {
                              label: 'Cash Inflow',
                              borderColor: '#005daa',
                              backgroundColor: getGradient('rgba(0, 0, 255, 1)', 'blue'), // Call getGradient function with custom color for Cash Inflow
                              data: data.cashinflow,
                              fill: true,
                              tension: 0.2,
                              pointBackgroundColor: 'rgba(139, 183, 239, 1 )',
                              borderWidth: 0.1, // Adjust line thickness for Cash Inflow
                          },
                      ],
                  },
                  options: {
                      maintainAspectRatio: false,
                      scales: {
                          y: {
                              beginAtZero: true,
                              ticks: {
                                  callback: (value, index, values) => '₱ ' + value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
                                  color: '#58585a',
                                  font: {
                                      size: 14,
                                      weight: 'bold'
                                  }
                              },
                              grid: {
                                  color: '#cfcfcf',
                                  drawBorder: false
                              }
                          },
                          x: {
                              ticks: {
                                  color: '#58585a',
                                  font: {
                                      size: 12,
                                      weight: 'bold'
                                  }
                              },
                              grid: {
                                  display: false,
                                  drawBorder: false
                              }
                          }
                      },
                      plugins: {
                          legend: {
                              labels: {
                                  color: '#58585a',
                                  font: {
                                      size: 14,
                                      weight: 'bold'
                                  }
                              }
                          }
                      }
                  }
              });
          } else {
              // Handle the case when data is empty or undefined
              const chartContainer = document.querySelector('.chart-cashoutflow');
              chartContainer.classList.add('no-data-chart');
              const noDataMessage = document.getElementById('noDataMessageCashoutflow');
              noDataMessage.style.display = 'block';
              noDataMessage.innerHTML = '<i class="fa fa-info-circle"></i> No Data Yet <img class="img-fluid d-flex justify-content-center align-items-center mx-auto mt-4" style="height: 165px;" src="img/no-data-yet.png" alt="dd" >';
          }
      });
      
      function getGradient(rgbaColor, borderColor) {
          const ctx = document.getElementById('cashFlowChart').getContext('2d');
          const gradient = ctx.createLinearGradient(0, 0, 0, 300);
          
          if (rgbaColor === 'rgba(0, 0, 255, 1)') {
              gradient.addColorStop(0, rgbaColor); // Start with the specified RGBA color for Cash Inflow
              gradient.addColorStop(1, '#005daa'); // Set the background color to rgba(139, 183, 239, 1) for Cash Inflow
          }
          
          return gradient;
      }
   </script>
   <script>
      document.addEventListener('DOMContentLoaded', function () {
          // Check if data is empty or undefined
          const data = <?php echo isset($final_data) ? json_encode($final_data) : 'null'; ?>;
          const ctx = document.getElementById('cashOutFlowChart').getContext('2d');
      
          if (data && data.cashoutflow) { // Check if cashoutflow data exists
              const monthNames = [
                  'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                  'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
              ];
      
              const cashFlowChart = new Chart(ctx, {
                  type: 'line', 
                  data: {
                      labels: monthNames,
                      datasets: [
                          {
                              label: 'Cash Outflow',
                              borderColor: '#f2a81b',
                              backgroundColor: getGradientCashOut('rgba(255, 140, 0, 1)','#f2a81b'), // Call getGradient function with custom color for Cash Outflow
                              data: data.cashoutflow,
                              fill: true,
                              tension: 0.1,
                              pointBackgroundColor: '#f2a81b',
                              borderWidth: 1, // Adjust line thickness for Cash Outflow
                          },
                      ],
                  },
                  options: {
                      maintainAspectRatio: false,
                      scales: {
                          y: {
                              beginAtZero: true,
                              ticks: {
                                  callback: (value, index, values) => '₱ ' + value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
                                  color: '#58585a',
                                  font: {
                                      size: 14,
                                      weight: 'bold'
                                  }
                              },
                              grid: {
                                  color: '#cfcfcf',
                                  drawBorder: false
                              }
                          },
                          x: {
                              ticks: {
                                  color: '#58585a',
                                  font: {
                                      size: 12,
                                      weight: 'bold'
                                  }
                              },
                              grid: {
                                  display: false,
                                  drawBorder: false
                              }
                          }
                      },
                      plugins: {
                          legend: {
                              labels: {
                                  color: '#58585a',
                                  font: {
                                      size: 14,
                                      weight: 'bold'
                                  }
                              }
                          }
                      }
                  }
              });
          } else {
              // Handle the case when data is empty or undefined
              const chartContainer = document.querySelector('.chart-cashoutflow');
              chartContainer.classList.add('no-data-chart');
              const noDataMessage = document.getElementById('noDataMessageCashoutflow');
              noDataMessage.style.display = 'block';
              noDataMessage.innerHTML = '<i class="fa fa-info-circle"></i> No Data Yet <img class="img-fluid d-flex justify-content-center align-items-center mx-auto mt-4" style="height: 165px;" src="img/no-data-yet.png" alt="dd" >';
          }
      });
      
      function getGradientCashOut(rgbaColor, borderColor) {
          const ctx = document.getElementById('cashOutFlowChart').getContext('2d');
          const gradient = ctx.createLinearGradient(0, 0, 0, 300);
          
          if (rgbaColor === 'rgba(255, 140, 0, 1)') {
              gradient.addColorStop(0, rgbaColor); // Start with the specified RGBA color for Cash Outflow
              gradient.addColorStop(1,'#f2a81b'); // Set the background color to rgba(255, 0, 0, 1) for Cash Outflow
          }
          
          return gradient;
      }
   </script>
   <script>
      document.addEventListener('DOMContentLoaded', function () {
          // Your PHP JSON data (replace this with the actual JSON data you get from your PHP script)
          var jsonData = <?php echo json_encode($data_expense); ?>;
      
          var labels = jsonData.labels;
          var values = jsonData.values;
      
          var ctx = document.getElementById('expenseChart').getContext('2d');
          var expenseChart = new Chart(ctx, {
              type: 'bar',
              data: {
                  labels: labels,
                  datasets: [{
                      label: 'Total Amount',
                      data: values,
                      backgroundColor: '#f2a81b ', // Customize the bar color here
                      borderColor: '#f2a81b',
                      borderWidth: 2,
                      barPercentage: 0.5, // Adjust the width of the bars (0.5 = 50% width)
                      categoryPercentage: 1.0, // Adjust the gap between bars (1.0 = no gap)
                  }]
              },
              options: {
                  scales: {
                      y: {
                          beginAtZero: true,
                          title: {
                              display: true,
                              text: 'Total Amount',
                              color: '#58585a',
                              color: '#58585a',
                                  font: {
                                      size: 12,
                                      weight: 'bold'
                                  }
                          },
                          grid: {
                              display: false, // Remove the y-axis grid lines
                          }
                      },
                      x: {
                          title: {
                              display: true,
                              text: 'Expense Category',
                              color: '#58585a',
                              color: '#58585a',
                                  font: {
                                      size: 12,
                                      weight: 'bold'
                                  }
                          },
                          grid: {
                              display: false, // Remove the x-axis grid lines
                          }
                      }
                  }
              }
          });
      });
      
      document.addEventListener('DOMContentLoaded', function () {
          // Your PHP JSON data (replace this with the actual JSON data you get from your PHP script)
          var jsonData = <?php echo json_encode($data_income); ?>;
      
          var labels = jsonData.labels;
          var values = jsonData.values;
      
          var ctx = document.getElementById('incomeChart').getContext('2d');
          var expenseChart = new Chart(ctx, {
              type: 'bar',
              data: {
                  labels: labels,
                  datasets: [{
                      label: 'Total Amount',
                      data: values,
                      backgroundColor: '#005daa', // Customize the bar color here
                      borderColor: '#005daa',
                      borderWidth: 2,
                      barPercentage: 0.5, // Adjust the width of the bars (0.5 = 50% width)
                      categoryPercentage: 1.0, // Adjust the gap between bars (1.0 = no gap)
                  }]
              },
              options: {
                  scales: {
                      y: {
                          beginAtZero: true,
                          title: {
                              display: true,
                              text: 'Total Amount',
                              color: '#58585a',
                              color: '#58585a',
                                  font: {
                                      size: 12,
                                      weight: 'bold'
                                  }
                                  
                          },
                          grid: {
                              display: false, // Remove the y-axis grid lines
                          }
                      },
                      x: {
                          title: {
                              display: true,
                              text: 'Revenue Category',
                              color: '#58585a',
                              color: '#58585a',
                                  font: {
                                      size: 12,
                                      weight: 'bold'
                                  }
                          },
                          grid: {
                              display: false, // Remove the x-axis grid lines
                          }
                      }
                  }
              }
          });
      });
      
      document.addEventListener('DOMContentLoaded', function () {
      // Your PHP JSON data (replace this with the actual JSON data you get from your PHP script)
      var jsonData = <?php echo json_encode($data_income); ?>;
      
      var labels = jsonData.labels;
      var values = jsonData.values;
      
      var ctx = document.getElementById('incomeChartPie').getContext('2d');
      var incomeChart = new Chart(ctx, {
          type: 'pie', // Change chart type to 'pie'
          data: {
              labels: labels,
              datasets: [{
                  data: values,
                  backgroundColor: ['rgba(54, 162, 235, 0.7)', '#ff5733', '#00a854', '#005daa'], // Customize slice colors here
                  borderColor: '#fff',
                  borderWidth: 2,
                  offset:4,
                  hoverOffset:10,
              }]
          },
          options: {
    plugins: {
        legend: {
            position: 'bottom', // Adjust legend position as needed
        },
    },
    layout: {
        padding: {
            bottom: 15 // Adjust the padding value as needed
        }
    }
}
    
      });
      });
      
   </script>
     <script>
        function scrollToSection(sectionClass) {
            const sections = document.getElementsByClassName(sectionClass);
            if (sections.length > 0) {
                sections[0].scrollIntoView({ behavior: "smooth" });
            }
        }
    </script>
   <script>
      document.addEventListener('DOMContentLoaded', function () {
        var dataSpecificExpenses = <?php echo $dataSpecificExpenses; ?>;
// Extract labels and data from the JSON
var labels = dataSpecificExpenses.map(function (item) {
    return item.expense_category;
});

var data = dataSpecificExpenses.map(function (item) {
    return item.total_amount;
});

// Create a donut chart
var ctx = document.getElementById('expenseDonutChart').getContext('2d');

var donutChart = new Chart(ctx, {
    type: 'doughnut', // Use 'doughnut' for a donut chart
    data: {
        labels: labels,
        datasets: [{
            data: data,
            backgroundColor: [
                '#FF5733',  // A pleasing shade of red
                '#FFC300',  // A pleasing shade of yellow
                '#FF5733',  // Repeating red for variety
                '#FFC300',  // Repeating yellow for variety
                '#FF8000',  // A pleasing shade of orange
                '#FF5733',  // Repeating red for variety
                '#FFC300',  // Repeating yellow for variety
                // Add more pleasing colors here if needed
            ],
            borderWidth: 2,
            hoverOffset: 10,
        }]
    },
    options: {
        plugins: {
            legend: {
                display: true,
                position: 'bottom', // Adjust legend position as needed
            },
            datalabels: {
                color: 'white', // Color of the text inside the segments
                formatter: (value, ctx) => {
                    let sum = 0;
                    let dataArr = ctx.chart.data.datasets[0].data;
                    dataArr.map(data => {
                        sum += data;
                    });
                    let percentage = ((value * 100) / sum).toFixed(2) + '%';
                    return percentage;
                },
            },
        },
        responsive: true,
        maintainAspectRatio: false, // Set to false to control the size via CSS
        title: {
            display: true,
            text: 'Expense Categories',
        },
        layout: {
            padding: {
                bottom: 15, // Adjust the padding value as needed
            },
        },
        onClick: function (event, chartElements) {
            if (chartElements.length > 0) {
                // Get the clicked segment's value
                var clickedValue = chartElements[0].parsed._dataset.data[chartElements[0].parsed._index];

                // Update the center text with the clicked value
                document.getElementById('centerText').textContent = clickedValue;
            }
        },
    },
});


      });
      
   </script>
  
   <script src="js/cdn.jsdelivr.net_npm_bootstrap@5.0.0_dist_js_bootstrap.bundle.min.js"></script>
   <script src="lib/chart/chart.min.js"></script>
   <!-- JavaScript Libraries -->
   <script src="js/code.jquery.com_jquery-3.4.1.min.js"></script>

   <!-- Template Javascript -->
   <script src="js/main.js"></script>
  
</html>