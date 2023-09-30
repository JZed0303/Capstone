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
   $sql = "SELECT * FROM cashoutflow_tbl";
   $stmt = $conn->prepare($sql);
   $stmt->execute();
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
      <!-- Favicon -->
      <!-- Google Web Fonts -->
      <link href="css/fontawesome-free-6.3.0-web/css/all.css" rel="stylesheet">
      <link href="css/cdn.jsdelivr.net_npm_bootstrap-icons@1.4.1_font_bootstrap-icons.css" rel="stylesheet">
      <!-- Libraries Stylesheet -->
      <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
      <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
      <!-- Customized Bootstrap Stylesheet -->
      <link href="css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="assets/css/budgetManagement.css">
      <link href="css/style.css" rel="stylesheet">
      <!-- general -->
      <!-- datatables -->
      <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
      <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
      <!--  -->
   </head>
   <style>
      /* Adjust the font size based on screen size */
      @media (max-width: 576px) {
      .amount {
      font-size: 16px;
      }
      }
      @media (min-width: 577px) and (max-width: 768px) {
      .amount {
      font-size: 18px;
      }
      }
      @media (min-width: 769px) and (max-width: 992px) {
      .amount {
      font-size: 20px;
      }
      }
      @media (min-width: 993px) and (max-width: 1200px) {
      .amount {
      font-size: 22px;
      }
      }
      @media (min-width: 1201px) {
      .amount {
      font-size: 24px;
      }
      }
   </style>
   <body>
      <div class="container-fluid position-relative d-flex p-0">
         <!-- Spinner Start -->
         <div id="spinner" class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
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
            <!-- Navbar End -->
            <!-- Sale & Revenue Start -->
            <div class="container-fluid pt-4 px-5 ">
               <div class="filter row g-3 bg-white p-3 shadow-lg ">
                  <div class="col-sm-4 col-xl-4 d-flex mt-4">
                     <div class="d-flex ">
                        <span class="mx-3">
                        <span><strong class="mb-2">Start Date</strong></span> &nbsp; <i class="fa fa-calendar"></i> 
                        <input type="text" class="form-control form-control-sm center" id="minDate" placeholder="Min Date" aria-label="Start date" aria-describedby="start-date-icon">
                        </span>
                        <span>
                        <span><strong class="mb-2">End Date</strong></span> &nbsp; <i class="fa fa-calendar"></i> 
                        <input type="text" class="form-control form-control-sm center " id="maxDate" placeholder="Max Date" aria-label="End date" aria-describedby="end-date-icon">
                        </span>
                     </div>
                  </div>
                  <div class="col-sm-4 col-xl-4 d-flex justify-content-center align-items-center">
                     <div class="col-12 ml-3 px-4 mb-2">
                        <span><strong class="mb-2">Category</strong></span>
                        <div class="form-group row" style="margin-top:3px;">
                           <div class="col-12 mt-2">
                              <select class="category-box form-control form-control-sm">
                                 <option selected>Select Category</option>
                                 <?php require 'sqlGetData/getRevenueCategory.php';?>
                              </select>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-4 col-xl-4">
                     <div class="mb-3">
                        <label for="dataTablesIncome_filter" class="form-label">Search:</label>
                        <input type="search" class="form-control form-control-sm" id="dataTablesIncome_filter" placeholder="Search...">
                     </div>
                  </div>
               </div>
            </div>
            <hr>
            <div class="container-fluid ">
               <div class="row g-2 mx-1 mb-3">
                  <?php
                     if ($role == "guess") {
                         // Add your code for the "guess" role here
                     } else {
                         echo '
                         <div class="add-transaction d-flex justify-content-end align-items-center mt-1 mb-0">
                             <button type="button" class="btn  col-sm-btn-sm btn-primary" data-toggle="modal" data-target="#addIncomeModal">
                                 <span class="fa fa-plus"></span> Add Transaction
                             </button>
                         </div>';
                     }
                     ?>
                  <div class="col-12">
                     <div class="bg-primary rounded h-100 p-2 mb-0 pb-0 mx-3">
                        <div class="table-responsive">
                         
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!-- Content End -->
         <!-- Back to Top -->
         <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="fa fa-arrow-up"></i></a>
      </div>
      <!-- charts -->
   </body>
   <!-- JavaScript Libraries -->
   <script src="js/code.jquery.com_jquery-3.4.1.min.js"></script>
   <script src="js/cdn.jsdelivr.net_npm_bootstrap@5.0.0_dist_js_bootstrap.bundle.min.js"></script>
   <script src="lib/chart/chart.min.js"></script>
   <script src="lib/easing/easing.min.js"></script>
   <script src="lib/waypoints/waypoints.min.js"></script>
   <script src="lib/owlcarousel/owl.carousel.min.js"></script>
   <script src="lib/tempusdominus/js/moment.min.js"></script>
   <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
   <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
   <!-- DATA FILTER -->
   <script src="assets/datatable/js/moment.min.js"></script>
   <script src="assets/datatable/js/dataTables.dateTime.min.js"></script>
   <!-- Template Javascript -->
   <script src="js/main.js"></script>
   <script src="assets/datatable/js/moment.min.js"></script>
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
   <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
   <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
   <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
   <script>
      $(document).ready(function() {
         var table = $('#dataTablesExpenses').DataTable({
            responsive: true
         });
      
         // Search filter function
         $('#dataTablesIncome_filter input').on('keyup', function() {
            table.search(this.value).draw();
         });
      
         // Category filter function
         $('.category-box').on('change', function() {
            var category = $(this).val();
            table.column(3).search(category).draw();
         });
      });
      
      
      
         
   </script>
   <script src="assets/js/general/sidebar.js"></script>
</html>