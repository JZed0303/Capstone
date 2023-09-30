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
   
   $sql = "SELECT * FROM cashoutflow_tbl";
   $stmt = $conn->prepare($sql);
   
   
   ?>
<!DOCTYPE html>
<!-- Designined by CodingLab | www.youtube.com/codinglabyt -->
<html lang="en" dir="ltr">
   <head>
      <meta charset="UTF-8">
      <title> Manage CashOutFlow</title>
      <link rel="stylesheet" href="assets/css/sidebar.css">
      <link rel="stylesheet" href="assets/fontawesome-free-6.3.0-web/css/all.css">
      <link rel="stylesheet" href="assets/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/css/budgetManagement.css">
      <!-- DATA TABLES  cs-->
      <link rel="stylesheet" href="assets/datatable/css/datatables.min.css">
      <link rel="stylesheet" href="assets/datatable/css/dataTables.dateTime.min.css">
      <link rel="stylesheet" href="assets/datatable/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/datatable/css/jquery.dataTables.min.css">
      <link rel="stylesheet" href="assets/css/cashinflow/cashInflow.css">
      <!-- DATA TABLE JS -->
      <script src="assets/datatable/js/jquery-3.6.0.min.js"></script>
      <script src="assets/datatable/css/bootstrap.bundle.min.js"></script>
      <script src="assets/datatable/js/datatables.min.js"></script>   
      <!-- DATA TABLE BUTTONS --> 
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
      <script src="assets/js/sidebar.js"></script>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
   </head>
   <style>
      #dataTablesIncome td:nth-child(7) {
      white-space: normal;
      word-wrap: break-word;
      max-width: 100px; /* Adjust the value as per your requirement */
      overflow: auto;
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
      }
   </style>
   <body>
      <!-- Template Header -->
      <?php 
         include 'template/header.php';
         ?>
      <!-- Template Sidebar -->
      <?php 
         include 'template/all_sidebar.php';
         
         ?>
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
         color: var(--white);
         }
         .table-container table {
         table-layout: auto; /* or fixed */
         width: 100%; /* or a specific value */
         }
         .table-container th,
         .table-container td {
         width: auto; /* or a specific value */
         white-space: nowrap; /* prevent wrapping of long content */
         }   #dataTablesExpenses td:nth-child(7) {
         max-width: 100;
         background-color: #d8c9c9;
         overflow: auto;
         word-wrap: break-word;}
         #dataTablesExpenses td:nth-child(7) {
         max-width: 100;
         background-color: #d8c9c9;
         overflow: auto;
         word-wrap: break-word;
         }#expenseTableBody td:nth-child(1) {
         word-break: break-word;
         }  .table {
         font-size: 16px; /* Adjust the font size as needed */
         }
         .table th, .table td {
         padding: 5px; /* Adjust the padding as needed */
         }/* Change the color of the select dropdown */
         select#fundCategory,
         select#expenseParentCategory,
         select#accountTitle,select#categoryFund {
         color: white;
         font-weight:550;
         font-size:14px;
         background-color: rgba(12, 60, 140, 0.9);
         border-radius: 9px;
         }select#fundCategory option,
         select#expenseParentCategory option,select#accountTitle option {
         padding: 20px;
         } .back-button {
         display: inline-block;
         background: none;
         border: none;
         font-size: 24px;
         color: azure;
         text-decoration: none;
         transition:  0.3s ease;
         }
         .back-button:hover {
         transform: scale(1.2); 
         cursor: pointer;
         }
         .financeThrough {
         margin-left:10%;
         display:none;
         }#selectedOptionsTable td:nth-child(2) {
         width: 100px; /* Adjust the width as needed */
         }
      </style>
      <section class="home-section">
         <div class="main-wrapper mt-4">
         <div class="page-wrapper">
            <div class="main-content content container-fluid" id="main-content">
               <div class="row p-2 border border-0 ml-1 p-2">
                  <div class="filter col-12 container-fluid pb-3" style="height:100%; ">
                     <div class="modal-header d-flex justify-content-center mt-3">
                        <a href="javascript:history.back()" class="back-button"><i class="fa-solid fa-circle-arrow-left fa-shake"></i></a>
                        <h5 class="modal-title" style="margin-left:auto;font-weigth:700;"id="addIncomeModalLabel">LIST OF EXPENSE</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                     </div>
                     <div class="container px-3 "style=";">
                        <div class="container px-3">
                           <div class="container vh-50 p-3">
                              <form action="CRUD/create/addExpensesDetail.php" method="POST">
                                 <input type="hidden" name="expense_names" id="expenseNamesInput" value="">
                                 <input type="hidden" name="expense_amounts" id="expenseAmountsInput" value="">
                                 <div class="form-row">
                                    <div class="form-group col-4 d-flex">
                                       <label for="min" style="color: #555;">Date:</label>
                                       <input type="date"  class="form-control col-5" required id="date" name="date" placeholder="Enter the date...">
                                    </div>
                                    <div class="form-group col-3">
                                       <label for="account_title" style="font-size: ; color: #555;">Expense Category:</label>
                                       <select class="form-control" id="expenseParentCategory" name="expenseParentCategory" required>
                                          <option style="color: #555;" value="" selected>Select Expense Category...</option>
                                          <?php include 'sqlGetData/getExpensesCategory.php'?>
                                       </select>
                                    </div>
                                    <div class="form-group col-4 ml-5 ">
                                       <label style="color: #555;">Title:</label>
                                       <input type="text" class="form-control col-12" required id="expenseTitle" name="expenseTitle" placeholder="Enter Title...">
                                    </div>
                                 </div>
                           </div>
                           <hr>
                           <div class="form-row pl-3 mt-4 " >
                           <div class="form-group col-2">
                           <label for="category_fund" style="color: #555;">Fund:</label>
                           <select class="form-control" id="fundCategory" name="fundCategory" onchange="toggleCategoryFund()">
                           <option value="" selected>Select a Fund Category title...</option>
                           <option value="Club Fund">Club Fund</option>
                           <option value="Annual Fund">Annual Fund</option>
                           </select>
                           </div>
                           <div class="form-group col-4" id="categoryFundContainer" style="display: none;">
                           <label for="category" style="font-size:; color: #555;">Finance Through:</label>
                           <select  class="form-control p-2 btn-sm btn-primary" id="categoryFund" name="categoryFund[]" multiple>
                           <?php require 'sqlGetData/getRevenueCategory.php'; ?>
                           </select>
                           <div>
                           <span id="categoryFundCloseButton" class="btn btn-sm btn-secondary">Close</span>
                           </div>
                           </div>     
                           <div class="financeThrough" id="financeThrough">
                           <table id="selectedOptionsTable" class="table roundedCorners border border-none table-striped bg-white" style="width: 100%;" ui-jq="dataTable">
                           <thead style="background-color:#005daa">
                           <tr>
                           <th style="color:white;">Fund</th>
                           <th style="color:white;">Amount</th>
                           <th style="color:white;">Action</th>      
                           </tr>
                           </thead>
                           <tbody></tbody>
                           <tfoot>
                           <tr>
                           <th></th>
                           <td colspan='1' id="totalAmountCell"></td>
                           <th></th>
                           </tr>
                           </tfoot>
                           </table>
                           </div>             
                           </div>
                           <hr>
                           <div class="row mt-4 ">
                           <div class="col">
                           <span style="font-size: ; color: #555;">Current Fund: </span>
                           <span style="position:absolute; left:5%; top:69%; border-bottom:blue 1px solid;"> <strong><?php include 'sqlGetData/getCurrentFund.php'?></strong> </span>
                           <div class="form-group col-md-3">
                           </div>
                           </div>
                           <div class="col">
                           <label style="font-size: ; color: #555;">Budget:</label>
                           <input type="number" id="budget" class="form-control form-control-sm expense-amount mt-2" name="budget" value="">
                           </div>
                           </div>
                           <hr>
                           <div class="form-row col-12 mt-5 pt-3" style="position:relative;left:-45;">
                           <div id="expenseItemsContainer" class="d-flex">
                           <div class="col-7">
                           <div class="form-group" style="width: 100%;">
                           <div class="d-flex flex-row col-12">
                           <div class ="col-6">
                           <label style="font-size: ; color: #555;">Account Title :</label>
                           <select class="form-control" style="width:100%;" id="accountTitle" name="accountTitle" required>
                           <option style="" name="expense_amount[]" value="" selected>Select Expense Category...</option>
                           <option value="Travel Expense">Travel Expense</option>
                           <option value="Food Expense">Food Expense</option>
                           <option value="Registration Fee ">Registration Fee </option>
                           </select>
                           </div>
                           <div class="col-5">
                           <label style="font-size: ; color: #555; ">Amount:</label>
                           <input type="number" style="width:60%;" class="form-control form-control-sm expense-amount mt-2"   name="expense_amount[]" value="">
                           </div>
                           <button type="button" style="height: 30px; width:40x;margin-top:34px;position:relative; left:-60;" class="pt-0 btn btn-primary btn-add-expense btn-sm" style="font-size: 12px;">
                           <strong>+</strong>
                           </button>
                           </div>
                           </div>
                           </div>
                           <div class="row container col-6">
                           <table class="table roundedCorners table-striped bg-white" style="width: 100%;" ui-jq="dataTable">
                           <thead style="background-color:#005daa">
                           <tr>
                           <th style="color:white;">Expense Title</th>
                           <th style="color:white;">Amount</th>
                           <th style="color:white; " >Actions</th> <!-- Added Actions column -->
                           </tr>
                           </thead>
                           <tbody id="expenseTableBody">
                           <!-- Expense items will be dynamically added here -->
                           </tbody>
                           <tfoot>
                           <td></td>
                           <td>
                           </span>    <span class="float-right" style="font-size:16px; "id="expenseTotalAmount"></span>
                           </td>
                           </tr>
                           </tfoot>
                           </table>
                           </div>
                           </div>
                           <hr style="background-color:#f5BE5A;">
                           <div class="form-row">
                           <!-- Fund and category fund select inputs -->   
                           </div>
                           <div class="form-group col-md-12 mt-5 ml-4">
                           <hr style="background-color:#f5BE5A;">
                           <label for="remarks" style=" color: #555;">Remarks:</label>
                           <textarea class="form-control" id="remarks" name="remarks" placeholder="Enter the remarks..."></textarea>
                           </div>
                           <div class="form-group col-md-12">
                           <label for="receipt">Receipt:</label>
                           <div class="custom-file">
                           <input type="file" class="custom-file-input" id="receipt" name="receipt">
                           <label class="custom-file-label" for="receipt">Choose file</label>
                           </div>
                           </div>
                           </div>
                           <div class="form-group col-md-12">
                           <button type="submit" class="btn btn-primary float-right">Submit</button>
                           </div>
                           </form>
                        </div>
                        <div class="scroll-btn" id="scrollToTop">
                           &#8593;
                        </div>
                        <!-- Scroll to Bottom Button -->
                        <div class="scroll-btn" id="scrollToBottom">
                           &#8595;
                        </div>
                        <div class="dataTable_wrapper income col-12 pt-3 px-0 pb-0">
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- MODALS -->
      <!-- Modal -->
      <!-- modal error -->
      <?php
         // Check if an error occurred
         $error = isset($_GET['error']) ? $_GET['error'] : null;
         if ($error) {
             echo "<script>
                     $(document).ready(function(){
                         $('#errorModal').modal('show');
                     });
                   </script>";
         }
         ?>
      <!-- Modal -->
      <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="errorModalLabel">Error!</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <p><?php echo $error; ?></p>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
               </div>
            </div>
         </div>
      </div>
      <!-- EDIT DELETE -->
      <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Delete Item</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  Are you sure you want to delete this item?
               </div>
               <div class="modal-footer">
                  <form id="deleteForm" method="POST" action="CRUD/delete/deleteCashInFlow.php">
                     <input type="hidden" id="rowId" name="rowId" value="">
                     <button type="submit" class="btn btn-danger">Delete</button>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <!-- ERROR -->
      <?php
         // Check if the form is submitted
         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Initialize an empty array to store validation errors
            $errors = array();
         
            // Validate the form fields
            if (empty($_POST['date'])) {
               $errors['date'] = 'Date is required';
            }
         
            if (empty($_POST['accountTitle'])) {
               $errors['accountTitle'] = 'Account Title is required';
            }
         
         
            
               // Perform other validations for the remaining fields
         
            // If there are no errors, process the form submission
            if (empty($errors)) {
               // Process the form data and insert it into the database
         
               // Redirect to a success page or perform other actions
               header('Location: success.php');
               exit();
            }
         }
         ?> 
      <!--  -->
      <!-- ADD -->
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
                  <form action="CRUD/create/"method="POST"  enctype="multipart/form-data" class="row col-12">
                     <div class="row mx-auto">
                        <div class="form-group col-md-12">
                           <label for="date">Date:</label>
                           <input type="date" class="form-control" id="date" name="date" placeholder="Enter the date...">
                        </div>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="accountTitle">Account Title:</label>
                        <select class="form-control" id="accountTitle" name="accountTitle">
                           <option value="" selected >Select an Fund account title...</option>
                           <option value="Club Fund">Club Fund</option>
                           <option value="Annual Fund">Annual Fund</option>
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
                           <input type="file" class="custom-file-input" id="receipt" name="receipt">
                           <label class="custom-file-label" for="receipt">Choose file</label>
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
      <!-- Add the modal Amounyy Budget code -->
      <div class="modal fade" id="amountModal" tabindex="-1" role="dialog" aria-labelledby="amountModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="amountModalLabel">Enter Amount</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <div class="form-group">
                     <label for="amountInput">Amount:</label>
                     <input type="number" class="form-control" id="amountInput" placeholder="Enter amount">
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                  <button type="button" class="btn btn-primary" onclick="saveAmount()">Add</button>
               </div>
            </div>
         </div>
      </div>
      <div class="modal fade" id="editIncomeModal" tabindex="-1" role="dialog" aria-labelledby="editIncomeModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content w-100">
               <div class="modal-header d-flex justify-content-center">
                  <h5 class="modal-title" style="margin-left:auto;" id="editIncomeModalLabel">Edit CashinFlow Transaction</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body p-3">
                  <form action="CRUD/update/updateCashInflow.php" method="POST" enctype="multipart/form-data" class="row col-12">
                     <input type="hidden" name="rowId" id="editRowId" value="<?php echo $rowId;?>">
                     <div class="row mx-auto">
                        <div class="form-group col-md-12">
                           <label for="editDate">Date:</label>
                           <input type="date" class="form-control" id="editDate" name="date" placeholder="Enter the date...">
                        </div>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="editAccountTitle">Account Title:</label>
                        <select class="form-control" id="editAccountTitle" name="accountTitle">
                           <option value="">Select a Fund account title...</option>
                        </select>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="editCategory">Category:</label>
                        <select class="form-control" id="editCategory" name="category">
                           <option value="" selected >Select an Fund Category title...</option>
                           <?php require 'sqlGetData/getRevenueCategory.php';?>
                        </select>
                     </div>
                     <div class="form-group col-md-6">
                        <label for="editAmount">Amount:</label>
                        <input type="text" class="form-control" id="editAmount" name="amount" placeholder="Enter the amount...">
                     </div>
                     <div class="form-group col-md-6">
                        <label for="editType">Type:</label>
                        <select class="form-control" id="editType" name="type">
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
                           <input type="file" class="custom-file-input" id="editReceipt" name="receipt">
                           <label class="custom-file-label" for="editReceipt">Choose file</label>
                        </div>
                     </div>
                     <div class="form-group col-md-12">
                        <button type="submit" name="update" class="btn btn-primary float-right">Update</button>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </body>
   <script>
      var selectedOptions = [];
      
      function toggleCategoryFund() {
      var fundCategory = document.getElementById('fundCategory');
      var categoryFundContainer = document.getElementById('categoryFundContainer');
      var closeButton = document.getElementById('categoryFundCloseButton');
      var budget = document.getElementById('budget');
      if (fundCategory.value === 'Club Fund') {
          categoryFundContainer.style.display = 'block';
          financeThrough.style.display = 'block';
      
         
      } else {
          categoryFundContainer.style.display = 'none';
          financeThrough.style.display = 'none';
          budget.disabled = false; // Disable the input field
         
      }
      
      closeButton.onclick = function() {
          categoryFundContainer.style.display = 'none';
      
      };
      }
      
      document.getElementById('categoryFund').addEventListener('change', function() {
          selectedOptions = Array.from(this.selectedOptions);
          $('#amountModal').modal('show');

          closeButton.onclick = function() {
            $('#amountModal').modal('show');
      
      };
          
      });
      
      function saveAmount() {
      var amountInput = document.getElementById('amountInput');
      var amount = parseFloat(amountInput.value);
      amountInput.value = '';
      
      if (!isNaN(amount)) {
          var tableBody = document.getElementById('selectedOptionsTable').getElementsByTagName('tbody')[0];
          var tableFooter = document.getElementById('selectedOptionsTable').getElementsByTagName('tfoot')[0];
      
          selectedOptions.forEach(function(option) {
              var optionText = option.text;
      
              // Check if the option has already been selected
              var isOptionSelected = Array.from(tableBody.getElementsByTagName('td')).some(function(cell) {
                  return cell.textContent === optionText;
              });
      
              if (!isOptionSelected) {
                  var row = tableBody.insertRow();
                  var fundCell = row.insertCell(0);
                  var amountCell = row.insertCell(1);
                  var actionCell = row.insertCell(2);
      
                  fundCell.innerHTML = optionText;
                  amountCell.innerHTML = '<span class="editable" onclick="editAmount(this)">' + amount.toFixed(2) + '</span>';
      
                  // Add edit and delete buttons
                  var editButton = document.createElement('button');
                  editButton.classList.add('btn', 'btn-primary', 'btn-sm', 'mr-2');
                  editButton.textContent = 'Edit';
                  editButton.addEventListener('click', function() {
                      editAmount(amountCell);
                  });
      
                  var deleteButton = document.createElement('button');
                  deleteButton.classList.add('btn', 'btn-danger', 'btn-sm');
                  deleteButton.textContent = 'Delete';
                  deleteButton.addEventListener('click', function() {
                      deleteRow(row);
                      calculateTotal();
                  });
      
                  actionCell.appendChild(editButton);
                  actionCell.appendChild(deleteButton);
      
                  calculateTotal();
              }
          });
      }
      
      $('#amountModal').modal('hide');
      }
      
      var accountTitle = document.getElementById('accountTitle');
      var expenseTable = document.getElementById('expenseTable');
      
      accountTitle.addEventListener('change', function() {
      if (accountTitle.value === '') {
          expenseTable.style.display = 'none'; // Hide the table
      } else {
          expenseTable.style.display = 'block'; // Show the table
      }
      });
      
      
      
      function calculateTotal() {
      var tableBody = document.getElementById('selectedOptionsTable').getElementsByTagName('tbody')[0];
      var totalCell = document.getElementById('totalAmountCell');
      var totalAmount = 0;
      
      for (var i = 0; i < tableBody.rows.length; i++) {
          var amountCell = tableBody.rows[i].cells[1];
          var amount = parseFloat(amountCell.textContent);
      
          if (!isNaN(amount)) {
              totalAmount += amount;
          }
      }
      
      totalCell.textContent = totalAmount.toFixed(2);
      
      // Update the input field and placeholder
      var expenseAmountInput = document.querySelector('.expense-amount');
      expenseAmountInput.value = totalAmount.toFixed(2);
      expenseAmountInput.placeholder = totalAmount.toFixed(2);
      }
      
      function editAmount(cell) {
      var oldValue = parseFloat(cell.textContent);
      var inputElement = document.createElement('input');
      inputElement.type = 'number';
      inputElement.step = '0.01';
      inputElement.value = oldValue;
      inputElement.classList.add('form-control', 'editable-input');
      
      var saveButton = document.createElement('button');
      saveButton.classList.add('btn', 'btn-success', 'btn-sm');
      saveButton.textContent = 'Save';
      
      saveButton.addEventListener('click', function() {
          var newValue = parseFloat(inputElement.value);
      
          if (!isNaN(newValue)) {
              cell.textContent = newValue.toFixed(2);
              calculateTotal(); // Call calculateTotal() to update the total amount
          } else {
              cell.textContent = oldValue.toFixed(2);
          }
      
          cell.classList.remove('editing');
          cell.removeChild(inputElement);
          cell.appendChild(editButton);
      });
      
      inputElement.addEventListener('blur', function() {
          var newValue = parseFloat(inputElement.value);
      
          if (!isNaN(newValue)) {
              cell.textContent = newValue.toFixed(2);
              calculateTotal(); // Call calculateTotal() to update the total amount
          } else {
              cell.textContent = oldValue.toFixed(2);
          }
      
          cell.classList.remove('editing');
          cell.removeChild(inputElement);
          cell.appendChild(editButton);
      });
      
      cell.textContent = '';
      cell.classList.add('editing');
      cell.appendChild(inputElement);
      cell.appendChild(saveButton);
      inputElement.focus();
      }
      
      
      
      function deleteRow(row) {
      var tableBody = document.getElementById('selectedOptionsTable').getElementsByTagName('tbody')[0];
      tableBody.removeChild(row);
      }
      
   </script>
   <!-- Add the modals -->
   <div id="amountModal" class="modal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title">Enter Amount</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body">
        
               <input type="number" id="amountInput" class="form-control" placeholder="Enter amount" step="0.01">
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-primary" onclick="saveAmount()">Save</button>
               <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
         </div>
      </div>
   </div>
   <div id="editModal" class="modal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title">Edit Amount</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body">
               <div>
                  <strong>Fund:</strong>
                  <span id="editFund"></span>
               </div>
               <div class="mt-2">
                  <strong>Amount:</strong>
                  <input type="number" id="editAmount" class="form-control" placeholder="Enter amount" step="0.01">

                  <input type="number" id="editAmount" class="form-control" placeholder="Enter amount" step="0.01">
               </div>
            </div>
            <div class="modal-footer">
               <button id="editSaveButton" type="button" class="btn btn-primary" onclick="saveEditedAmount()">Save</button>
               <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
         </div>
      </div>
   </div>
   <script>
      // Function to add expense items to the table
      function addExpenseItem() {
      var expenseTitle = document.getElementById("accountTitle").value;
      var expenseAmountInput = document.querySelector('input[name="expense_amount[]"]');
      var expenseAmount = parseFloat(expenseAmountInput.value);
      
      if (expenseTitle && !isNaN(expenseAmount)) {
      var expenseTableBody = document.getElementById("expenseTableBody");
      var newRow = document.createElement("tr");
      newRow.innerHTML = `
      <td>${expenseTitle}</td>
      <td class="text-right">${expenseAmount.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
      <td>
      <button type="button" class="btn btn-sm btn-primary btn-edit">Edit</button>
      <button type="button" class="btn btn-sm btn-danger btn-delete">Delete</button>
      </td> 
      `;
      
      expenseTableBody.appendChild(newRow);
      
      updateExpenseDetails();
      calculateTotalAmount();
      attachEditDeleteHandlers(newRow);
      }
      }
      
      
      // Function to attach event handlers for edit and delete buttons
      // Function to attach event handlers for edit and delete buttons
      function attachEditDeleteHandlers(row) {
      var editButton = row.querySelector(".btn-edit");
      var deleteButton = row.querySelector(".btn-delete");
      
      editButton.addEventListener("click", handleEditButtonClick);
      
      function handleEditButtonClick() {
      var expenseTitleCell = row.cells[0];
      var expenseAmountCell = row.cells[1];
      
      var expenseTitleInput = document.createElement("input");
      expenseTitleInput.type = "text";
      expenseTitleInput.value = expenseTitleCell.textContent;
      
      var expenseAmountInput = document.createElement("input");
      expenseAmountInput.type = "number";
      expenseAmountInput.value = expenseAmountCell.textContent;
      
      expenseTitleCell.innerHTML = "";
      expenseTitleCell.appendChild(expenseTitleInput);
      
      expenseAmountCell.innerHTML = "";
      expenseAmountCell.appendChild(expenseAmountInput);
      
      editButton.textContent = "Save";
      editButton.classList.remove("btn-primary");
      editButton.classList.add("btn-success");
      
      editButton.removeEventListener("click", handleEditButtonClick);
      editButton.addEventListener("click", handleSaveButtonClick);
      }
      
      function handleSaveButtonClick() {
      var expenseTitleCell = row.cells[0];
      var expenseAmountCell = row.cells[1];
      var expenseTitleInput = expenseTitleCell.querySelector("input");
      var expenseAmountInput = expenseAmountCell.querySelector("input");
      
      expenseTitleCell.textContent = expenseTitleInput.value;
      expenseAmountCell.textContent = expenseAmountInput.value;
      
      editButton.textContent = "Edit";
      editButton.classList.remove("btn-success");
      editButton.classList.add("btn-primary");
      
      updateExpenseDetails();
      calculateTotalAmount(); // Recalculate and display the total amount after editing
      
      editButton.removeEventListener("click", handleSaveButtonClick);
      editButton.addEventListener("click", handleEditButtonClick);
      }
      
      deleteButton.addEventListener("click", function() {
      row.remove();
      updateExpenseDetails();
      calculateTotalAmount(); // Recalculate and display the total amount after deleting
      });
      }
      
      
      
      // Function to update the expense details in the hidden input fields
      function updateExpenseDetails() {
         var expenseTableRows = document.querySelectorAll("#expenseTableBody tr");
         var expenseNames = [];
         var expenseAmounts = [];
         
         expenseTableRows.forEach(function(row) {
            var expenseTitle = row.cells[0].textContent;
            var expenseAmount = row.cells[1].textContent;
            
            expenseNames.push(expenseTitle);
            expenseAmounts.push(expenseAmount);
         });
         
         document.getElementById("expenseNamesInput").value = JSON.stringify(expenseNames);
         document.getElementById("expenseAmountsInput").value = JSON.stringify(expenseAmounts);
      }
      
      function calculateTotalAmount() {
      var expenseTableRows = document.querySelectorAll("#expenseTableBody tr");
      var totalAmount = 0;
      
      expenseTableRows.forEach(function(row) {
      var expenseAmountCell = row.cells[1];
      var expenseAmount = parseFloat(expenseAmountCell.textContent.replace(/,/g, ""));
      
      if (!isNaN(expenseAmount)) {
         totalAmount += expenseAmount;
      }
      });
      
      // Display the total amount with commas
      var expenseTotalAmountElement = document.getElementById("expenseTotalAmount");
      expenseTotalAmountElement.textContent =  totalAmount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
      }
      
      
      // Event listener for adding expense items
      document.querySelector(".btn-add-expense").addEventListener("click", addExpenseItem);
   </script>
   <script>
      $(document).ready(function() {
      // Initialize the datepicker for date inputs
      $(".date-input").datepicker({
         format: "yyyy/mm/dd",
         autoclose: true
      });
      });
         
   </script>
   <script>
      $(document).ready(function() {
         $('.account-details').tooltip({
            placement: 'top',
            trigger: 'hover',
            delay: { "show": 0, "hide": 500 },
            animation: false
         });
      });
   </script>
   <script>
      // JavaScript/jQuery code
      $(document).ready(function() {
         // Handle click event of edit button
         $('.edit-button').click(function() {
            // Get the ID from the data attribute
            var rowId = $(this).data('id');
      
            // Set the ID value in the hidden input field of the edit modal
            $('#editRowId').val(rowId);
         });
      });
   </script>
   <script>
      // Set the retrieved values as the default values for the edit form
      
      function editTransaction(button) {
      // Get the closest row element
      var row = button.closest('tr');
      
      // Retrieve the values from the row
      var date = row.cells[1].innerText;
      var accountTitle = row.cells[1].innerText;
      var category = row.cells[2].innerText;
      var amount = row.cells[4].innerText;
      var type = row.cells[4].innerText;
      var remarks = row.cells[6].innerText;
      
      // Set the retrieved values as the default values for the edit form
      document.getElementById('editDate').value = date;
      document.getElementById('editAccountTitle').value = accountTitle;
      document.getElementById('editCategory').value = category;
      document.getElementById('editAmount').value = amount;
      document.getElementById('editType').value = type;
      document.getElementById('editRemarks').value = remarks;
      
      // Open the edit modal
      $('#editIncomeModal').modal('show');
      }
      
      
   </script>
   <script>
      $(document).ready(function() {
              // When delete button is clicked, retrieve the row ID and set it in the hidden input field
              $('.delete-item').click(function() {
                  var rowId = $(this).data('id');
                  $('#rowId').val(rowId);
              });
          });
   </script>
   <script>
      // Scroll to Top Button Functionality
      
      window.onscroll = function() { scrollFunction() };
      
      function scrollFunction() {
      var scrollToTopButton = document.getElementById('scrollToTop');
      var scrollToBottomButton = document.getElementById('scrollToBottom');
      
      if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
      scrollToTopButton.classList.add('show');
      scrollToBottomButton.classList.add('show');
      } else {
      scrollToTopButton.classList.remove('show');
      scrollToBottomButton.classList.remove('show');
      }
      }
      
      function scrollToTop() {
      document.body.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
      
      function scrollToBottom() {
      document.body.scrollIntoView({ behavior: 'smooth', block: 'end' });
      }
      
      document.getElementById('scrollToTop').addEventListener('click', function() {
        window.scrollTo({
          top: 0,
          behavior: 'smooth'
        });
      });
      
      // Scroll to Bottom Button Functionality
      document.getElementById('scrollToBottom').addEventListener('click', function() {
        window.scrollTo({
          top: document.body.scrollHeight,
          behavior: 'smooth'
        });
      });
   </script>
   <script>
      $(document).ready(function() {
      $('.hoverable-cell').hover(
      function() {
      var id = $(this).data('id');
      $(this).attr('title', 'ID: ' + id);
      },
      function() {
      $(this).removeAttr('title');
      }
      );
      });
   </script>
   <script></script>
   <script src="assets/js/general/sidebar.js"></script>
   <!--  -->
   <script>
      var wrapper = document.querySelector(".dataTables_wrapper");
      var table = document.getElementById("dataTablesIncome");
      
      var wrapperWidth = wrapper.parentElement.offsetWidth - minibar.offsetWidth;   
      
      
   </script>
   <!-- <script>
      $(function () {
         $('#datetimepicker3').datetimepicker({
            format: 'LT'
      
         });
      });
      </script> -->
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
</html>