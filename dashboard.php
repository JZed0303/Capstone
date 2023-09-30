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
   
   $sql = "SELECT * FROM (
      SELECT
          c.date,
          c.fundAccTitle AS account_title,
          coa.child_account_name AS category,
          'cashinflow' AS type,
          c.amount
      FROM cashinflow_tbl c
      LEFT JOIN chart_of_accounts coa
          ON c.account_number = coa.child_account_number
          WHERE c.status=0
      UNION ALL
      SELECT
          date,
          fund,
          expense_category AS category,
          'cashoutflow' AS type,
          amount
      FROM cashoutflow_tbl
      WHERE cashoutflow_tbl.status=0
  ) AS combined_data
  ORDER BY date DESC
  LIMIT 4";

  // Prepare and execute the query
  $stmt = $conn->prepare($sql);
  $stmt->execute();

   ?>
<!DOCTYPE html><!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>Dashboard</title>
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
      /* Define your custom scrollbar styles for vertical scrollbar */
      /* Webkit-based browsers */
      /* Define your custom scrollbar styles for vertical scrollbar */
      /* Webkit-based browsers */
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
      }.chartbox{
      width:90%!important;
      height:45%!important;
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
      min-height:93%;
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
      top: 91px;
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
      top: 91px;
      left: 32px;
      background: #995e0b!important;
      } .bg-amount-dakren-in{
      opacity: 0.2;
      border-radius:10px;
      position: absolute;
      padding: 18px;
      width: 57%;
      top: 91px;
      left: 32px;
      background: #0b3665!important;
      }  
      .column-chart{
      background: white!important;
      padding:10px 25px 10px 0px!important;
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
      }.timeline-desc{
         font-size:15px;   
      }.timeline-time {
         font-size:14px;

      }.fw-semibold{
         color:#444040!important;
      }.card-body.p-4{
         background:white;
      }h6,.amount-text{
         color:#444040!important;
      }thead{
         border-bottom: 2px solid #9fc0eb;
         color:#444040!important;
      }
    .slide-down {
    animation: slideDown 0.5s ease-out;
    display: block !important; /* Override display: none; */
}

/* Define the slide-down animation */
@keyframes slideDown {
    0% {
        transform: translateY(-100%);
        opacity: 0;
    }
    100% {
        transform: translateY(0);
        opacity: 1;
    }
}
   </style>
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
      <?php 
         include 'template/modal.php';
         ?>
      <div class="content">

      <div class="modal-container containe-fluid ">
         <div class="modal-col col-10 mx-auto col-md-5 d-none col-lg-6 container-fluid m-3 p-0  p-md-0 mx-md-5 mx-lg-5px modal-content-center w-100 bg-white rounded shadow">
            <div class="modal-header d-flex">
               <a href="includes/logout.inc.php" class="btn btn-light rounded-circle">
               <i class="fas fa-arrow-left"></i>
               </a>
               <p class="modal-title" style="font-family: 'Open Sans', sans-serif; font-weight: bold;">INITIAL CAPITAL CLUB</p>
               <hr>
            </div>
            <div class="modal-body px-md-5 mx-lg-5">
               <form action="CRUD/create/totalFund.php" method="POST" class="px-md-5 mx-lg-5 " >
                  <hr class="m-0">
                  <input type="text" class="modal-input w-100 mx-auto <?php echo isset($_GET['error']) ? 'error' : ''; ?>" id="capital_amount" name="capital_amount" placeholder="Enter Amount" oninput="formatAmount(this)" style="text-align: center;">
                  <?php if (isset($_GET['error']) && $_GET['error'] === 'InvalidAmount') : ?>
                  <div class="text-danger text-star font-weight-bold">Amount must be greater than 0</div>
                  <?php endif; ?>
                  <div class="modal-footer justify-content-center align-items-center p-0">
                     <button id="btn-submit" style="color:white;" class="modal-submit p-2 button-login btn-primary btn-sm d-block mx-auto mt-4 rounded-3" type="submit" name="submit">
                     Submit
                     </button>
                  </div>
               </form>
            </div>
         </div>
      </div>
      <!-- Navbar Start -->
      <?php include 'template/navbar.php'?>
      <!-- Navbar End -->
      <div class="col-md-3 col-xl-4">
      </div>
      <?php
if(isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'index.php') !== false) {
    echo '<div id="success-message" style="background-color:#9cb4ec;" class="alert alert-success slide-down">';
    echo '<h5 class="text-center">Welcome' . " " . $name . '!</h5>';
    echo '</div>';
}
?>
      <div class="container-fluid px-md-4 pt-sm-1 px-sm-1">
     
      <div class="card-wrap gx-md-4 px-3 px-md-0 px-lg-0">
         <div class="row mt-2 g-3">
            <div class="col-sm-12 col-xl-4 col-md-4 col-lg-4 mb-3 mb-lg-0 mb-md-0">
            <div class="card-box bg-c-green mx-2 mb-5 card-box-2 rounded d-flex align-items-center justify-content-between p-4">
    <div class="d-flex flex-column">
        <span class="peso-sign" style="font-size: 20px;"></span>
        <div class="bg-amount-dakren bg-secondary col-12"></div>
        <p class="text-start fw-bold mx-2" style="white-space: nowrap;">Total Fund</p>
        <span class="amount mt-2 mx-2">
            <p class="amount total-fund" id="totalFund" style="white-space: nowrap;"><?php include 'sqlGetData/totalCurrentFund.php'?></p>
        </span>
       
        <hr class="line-amount bg-white">
        <div id="changeMessage" class="text-nowrap" style="position: absolute;font-weight: 700;color: white;top: 73%;font-size:14px" ></div>
    </div>

    <img src="assets/img/circle.953c9ca0.svg" alt="" srcset="">
    
    <div class="card-info ms-3">
        <div></div>
        <i class="icon-box fa fa-wallet fa-3x mt-3"></i>
        <div class="d-flex"></div>
    </div>
</div>

            </div>
            <div class="col-sm-12 col-xl-4 col-md-4 col-lg-4 mb-3 mb-lg-0 mb-md-0">
               <div class="card-box bg-c-blue mb-5 mx-2 card-box-2 rounded d-flex align-items-center justify-content-between p-4">
                  <div class="d-flex flex-column">
                     <span class="peso-sign" style="font-size: 20px;"></span>   
                     <p class="text-start fw-bold">Cash Inflow</p>
                     <div class="bg-amount-dakren-in bg-secondary col-12 "></div>
                     <span class="amount mt-2 mx-2">
                        <p class="amount cashinflow" id="cashinflow"  style="white-space:nowrap;"><?php include 'sqlGetData/getTotalCashInFlow.php'?></p>
                     </span>
                     <hr class="line-amount bg-white">
                  </div>
                  <img src="assets/img/circle.953c9ca0.svg" alt="" srcset="">
                  <div class="card-info ms-3">
                     <div>
                     </div>
                     <i class="icon-box fa fa-wallet fa-3x mt-3"></i>
                     <div class="d-flex">
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-sm-12 col-xl-4 col-md-4 col-lg-4 mb-3 mb-lg-0 mb-md-0">
               <div class="card-box bg-c-yellow mx-2 mb-5 card-box-2 rounded d-flex align-items-center justify-content-between p-4">
                  <div class="d-flex flex-column">
                     <span class="peso-sign" style="font-size: 20px;"></span>   
                     <p class="text-start fw-bold">Cash Outflow</p>
                     <div class="bg-amount-dakren-out  col-12 "></div>
                     <span class="amount mt-2 mx-2">
                        <p class="amount cashoutflow" id="cashoutflow"  style="white-space:nowrap;"><?php include 'sqlGetData/getTotalCashOutFlow.php'?></p>
                     </span>
                     <hr class="line-amount bg-white">
                  </div>
                  <img src="assets/img/circle.953c9ca0.svg" alt="" srcset="">
                  <div class="card-info ms-3">
                     <div>
                     </div>
                     <i class="icon-box fa-regular fa-chart-line fa-3x mt-3"></i>
                     <div class="d-flex">
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="container-fluid pt-2 mt-2 px-2 px-md-4">
            <div class="row g-4">
               <div class="column-chart col-12 col-md-9 col-lg-9 mt-2 m-0 px-2 ms-2 ms-md-0 pt-1 pb-2">
                  <div class="chart text-center rounded p-4 shadow-lg" style="background:white!important;">
                     <div class="d-md-flex align-items-center justify-content-between mb-4 text-white rounded-3 px-2">
                        <h6 class="mb-0" style="color: black;">Monthly Cashflow</h6>
                        <a href="financialGraphs.php" class="text-primary">
                           <div class="btn btn-sm btn-primary"> Show All</div>
                        </a>
                        <hr class="d-md-none">
                     </div>
                     <div id="noDataMessage" class="text-primary p-2 rounded-3" style="display: none;">
                     </div>
                     <div class="chartbox">
                        <canvas id="cashFlowChart" width="1000" height="250"></canvas>
                     </div>
                     <!-- Adjust height to make it shorter -->
                  </div>
               </div>
               <div class="fund-col  col-12 col-md-3 p-2 m-0 shadow-lg order-first order-md-last mt-2" style="max-height: 339px; overflow-y: auto; background:#adbaff85!important; border-radius:10px;">
                  <div class="fund custom-card-row shadow px-2" style="background-color: #adbaff85!important; border-radius: 10px; box-shadow: 0 7px 15.94px -0.94px rgba(4,26,55,0.16)!important;">
                     <p class="text-center pt-2" style="color: #333333;">Funds</p>
                     <hr class="bg-primary p-0">
                     <div class="fund-box p-0 d-flex justify-content-center align-items-center flex-md-column flex-lg-column " style="max-height: none; overflow-y: auto;">
                        <?php for ($i = 0; $i < count($accountNames); $i++) { ?>
                        <div class="card-box-fund bg-c-fund rounded-3 d-flex mx-2  align-items-center justify-content-between p-4 shadow-lg mb-4" style="width: 100%; background-color: white;">
                           <i class="icon-box-fund fa fa-chart-line fa"></i>
                           <div class="card-info ms-4">
                              <div class="mb-2" style="white-space: nowrap;">
                                 <strong><?php echo $accountNames[$i]; ?></strong>
                              </div>
                              <p class="mb-0">
                                 <span class="amount-fund"><?php echo isset($totalAmounts[$i]) ? number_format($totalAmounts[$i], 2, '.', ',') : "0.00"; ?></span>
                              </p>
                           </div>
                        </div>
                        <?php } ?>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <hr>
         <!-- Recent Sales Start -->
         <div class="row">
            <div class="col-lg-4 d-flex align-items-stretch ">
               <div class="card w-100">
                  <div class="card-body shdadow-lg p-4">
                     <div class="mb-4">
                        <h6 class="card-title fw-semibold text-dark">Recent Transactions</h6>
                     </div>
                     <ul class="timeline-widget mb-0 position-relative mb-n5">
                        <li class="timeline-item d-flex position-relative overflow-hidden">
                           <div class="timeline-time me-2 text-dark flex-shrink-0 text-end">09:30</div>
                           <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                              <span class="timeline-badge border-2 border border-primary flex-shrink-0 my-8"></span>
                              <span class="timeline-badge-border d-block flex-shrink-0"></span>
                           </div>   
                           <div class="timeline-desc text-dark mt-n1">Payment received from John Doe of $385.90</div>
                        </li>
                     </ul>
                  </div>
               </div>
            </div>
            <div class="col-lg-8 d-flex align-items-stretch order-md-first order-lg-firs">
            <div class="card w-100">
              <div class="card-body p-4 shadow-lg ">
                <h6   class="card-title fw-semibold mb-4 text-dark">Recent Transactions</h6>
                <div class="table-responsive">
                  <?php 
           if ($stmt->rowCount() > 0) {
            // Start building the table
            echo '<table class="table text-nowrap mb-0 align-middle table-hover">';
            echo '<thead class="text-dark fs-4 " style="border-radius:10px;">';
            echo '<tr class="text-fs">';
            echo '<th class="border-bottom-0"><h6 class="fw-semibold text-center mb-0 text-white">Date</h6></th>';
            echo '<th class="border-bottom-0"><h6 class="fw-semibold text-center mb-0 text-white">Category</h6></th>';
            echo '<th class="border-bottom-0"><h6 class="fw-semibold  text-center mb-0 text-white text-center">Type</h6></th>';
            echo '<th class="border-bottom-0"><h6 class="fw-semibold text-center mb-0 text-white">Amount</h6></th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
        
            // Fetch and display data row by row
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Determine the background color of the "Type" column
                $typeBgColor = ($row['type'] === 'cashinflow') ? '#0d4287' : (($row['type'] === 'Cashoutflow') ? '#efbe5f' : '
                #f2a81b');
        
                echo '<tr>';
                echo '<td class="border-bottom-0"><p class="text-center  mb-0">' . $row['date'] . '</p></td>';
                echo '<td class="border-bottom-0"><p class="text-center mb-1">' . $row['category'] . '</p></td>';
                echo '<td class="border-bottom-0 text-center"><div class="mx-5" style="background-color: ' . $typeBgColor . ';color:white; border-radius:10px;font-weight:800;"><p class="mb-0 fw-normal">' . $row['type'] . '</p></div></td>';
                echo '<td class="border-bottom-0"><h5 class="amount-text text-center text-md-right text-lg-right  mb-0 fs-6">' . formatValuePeso($row['amount']) . '</h5></td>';
                echo '</tr>';
            }
        
            // Close the table
            echo '</tbody>';
            echo '</table>';
        } else {
            echo 'No data found.';
        }
        
                  ?>
                     
                </div>
              </div>
            </div>
          </div>
         </div>
      
         <!-- Content End -->
         <!-- Back to Top -->
         <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="fa  fa-arrow-up"></i></a>
      </div>
   </body>
   <!-- JavaScript Libraries -->

   
   <script>


function extractAndConvertToFloat(elementId) {
    var element = document.getElementById(elementId);
    var elementText = element.textContent;

    // Use regular expression to extract numeric characters and decimal point
    var numericString = elementText.replace(/[^0-9.]/g, '');

    // Convert the extracted numeric string to a floating-point number
    var numericValue = parseFloat(numericString);

    // Check if the conversion was successful and numericValue contains a valid number
    if (!isNaN(numericValue)) {
        return numericValue;
    } else {
        console.log("Invalid numeric value in " + elementId);
        return NaN; // or return a default value or handle the error as needed
    }
}
$(document).ready(function () {
    // Select the success message element using jQuery
    var successMessage = $("#success-message");

    setTimeout(function () {
        // Slide up the success message container
        successMessage.slideUp(500, function () {
            // Remove the element from the DOM once the slide-up animation is complete
            successMessage.remove();
        });
    }, 2000); // Adjust the delay (in milliseconds) and slideUp duration as needed
});



        


   </script>
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
  
   
   </script>
   <script>
      document.addEventListener('DOMContentLoaded', function () {
          // Check if data is empty or undefined
          const data = <?php echo isset($final_data) ? json_encode($final_data) : 'null'; ?>;
          const ctx = document.getElementById('cashFlowChart').getContext('2d');
      
          if (data) {
              const monthNames = [
                  'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                  'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
              ];
      
              const cashFlowChart = new Chart(ctx, {
                  type: 'bar', // Change the chart type to 'bar'
                  data: {
                      labels: monthNames,
                      datasets: [
                          {
                              label: 'Cash Inflow',
                              backgroundColor: '#005daa', // Set the background color for Cash Inflow
                              data: data.cashinflow,
                          },
                          {
                              label: 'Cash Outflow',
                              backgroundColor: 'orange', // Set the background color for Cash Outflow
                              data: data.cashoutflow,
                          },
                      ],
                  },
                  options: {
                      maintainAspectRatio: false,
                      scales: {
                          y: {
                              beginAtZero: true,
                              ticks: {
                                  callback: (value, index, values) => 'Php' + value,
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
              // Add the class 'no-data-chart' to the chart container
              const chartContainer = document.querySelector('.chart-cashoutflow');
              chartContainer.classList.add('no-data-chart');
              // If data is empty, show the "No Data Available" message with an icon
              const noDataMessage = document.getElementById('noDataMessageCashoutflow');
              noDataMessage.style.display = 'block';
              // Add your icon here (e.g., FontAwesome or other icon libraries)
              noDataMessage.innerHTML = '<i class="fa fa-info-circle"></i> No Data Yet <img class="img-fluid d-flex justify-content-center align-items-center mx-auto mt-4" style="height: 165px;" src="img/no-data-yet.png" alt="dd" >';
          }
      });
   </script>

<script>

function removeNonNumericCharacters(inputString) {
              return inputString.replace(/[^\d.-]/g, '');
            }
      
  
            $(document).ready(function() {
    var totalFundElement = document.getElementById("totalFund");
    var cashinflowElement = document.getElementById("cashinflow");
    var cashoutflowElement = document.getElementById("cashoutflow");

    var totalFundText = totalFundElement.textContent;
    var cashinflowText = cashinflowElement.textContent;
    var cashoutflowText = cashoutflowElement.textContent;

    var totalFundValue = parseFloat(removeNonNumericCharacters(totalFundText));
    var totalCashinflowValue = parseFloat(removeNonNumericCharacters(cashinflowText));
    var totalCashoutflowValue = parseFloat(removeNonNumericCharacters(cashoutflowText));

    var previousTotalFundValue = 0; // Initialize the previous total fund value

    // Check if previousTotalFundValue has been set before (not the first time)
    if (localStorage.getItem("previousTotalFundValue")) {
        previousTotalFundValue = parseFloat(localStorage.getItem("previousTotalFundValue"));
    }

    // Update the previous total fund value with the current value
    localStorage.setItem("previousTotalFundValue", totalFundValue.toString());

    // Calculate the change in total fund
    var changeInTotalFund = totalFundValue - previousTotalFundValue;

// Calculate the percentage change, only if previousTotalFundValue is not zero
var percentageChange = previousTotalFundValue !== 0 ? (changeInTotalFund / previousTotalFundValue) * 100 : 0;

// Get the change message element
var changeMessageElement = document.getElementById("changeMessage");

if (percentageChange > 0) {
    changeMessageElement.innerHTML = "Total Fund has increased by " + percentageChange.toFixed(2) + "%";
    changeMessageElement.style.color = "white"; // Set the color to green for increase
} else if (percentageChange < 0) {
    changeMessageElement.innerHTML = "Total Fund has decreased by " + Math.abs(percentageChange).toFixed(2) + "%";
    changeMessageElement.style.color = "white"; // Set the color to red for decrease
} else {
    changeMessageElement.innerHTML = "";
    changeMessageElement.style.color = ""; // Set the color to black for no change
}
});

</script>

   <script src="js/cdn.jsdelivr.net_npm_bootstrap@5.0.0_dist_js_bootstrap.bundle.min.js"></script>
   <script src="lib/chart/chart.min.js"></script>
   <!-- JavaScript Libraries -->
   <script src="js/code.jquery.com_jquery-3.4.1.min.js"></script>
   <script src="js/cdn.jsdelivr.net_npm_bootstrap@5.0.0_dist_js_bootstrap.bundle.min.js"></script> 
   <!-- Template Javascript -->
   <script src="js/main.js"></script>
   <script src="js/cdn.jsdelivr.net_npm_bootstrap@5.0.0_dist_js_bootstrap.bundle.min.js"></script>
</html>