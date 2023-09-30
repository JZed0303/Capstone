<?php
   session_start();
   
   if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Redirect the user to the login page
    header('Location: index.php');
    exit();
   }
   
   
   require 'includes/dbh.inc.php';
   
   $role =  $_SESSION['role'];
   
   // Output the value of $role in a script tagnextTransactionID
   echo "<script>var role = '" . $role . "';</script>";
   
   $sql = "SELECT * FROM cashoutflow_tbl";
   $stmt = $conn->prepare($sql);
   $sql = "SELECT MAX(id) AS currentTransactionID FROM cashoutflow_tbl";
   $stmt = $conn->query($sql);
   
   // Fetch the data from the first row (currentTransactionID should be on the first row)
   $row = $stmt->fetch(PDO::FETCH_ASSOC);
  
       $currentTransactionID = (int) $row['currentTransactionID'];
   
   // Increment the currentTransactionID by 1 to get the next transaction ID
   $nextTransactionID = $currentTransactionID + 1;
   


   ?>

<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>Expense</title>
      <meta content="width=device-width, initial-scale=1.0" name="viewport">
      <meta content="" name="keywords">
      <meta content="" name="description">
      <!-- css content -->
      <link rel="stylesheet" href="assets/fontawesome-free-6.3.0-web/css/all.css">
      <link rel="stylesheet" href="assets/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/css/budgetManagement.css">
      <!-- DATA TABLES  cs-->


      <link href="css/bootstrap.min.css" rel="stylesheet">
      <link href="css/style.css" rel="stylesheet">
      <!-- general -->
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
         height: 3px!important; /* Adjust the height to make it thin */
        }
        ::-webkit-scrollbar-thumb:horizontal {
         background-color: white;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:horizontal:hover {
            background-color: var(--azure);
        }

    
        body {
            margin: 0;
            padding: 0;
            font-family: 'Open Sans', Arial, sans-serif!important;
            background-color:var(--white);
   
        }
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
         }
         .invalid-feedback {
         color: var(--deam);
         }
         .scroll-btn {
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
         table-layout: auto;
         /* or fixed */
         width: 100%;
         /* or a specific value */
         }
         .table-container th,
         .table-container td {
         width: auto;
         /* or a specific value */
         white-space: nowrap;
         /* prevent wrapping of long content */
         }
         #expenseTableBody td:nth-child(1) {
         word-break: break-word;
         }
         .table {
         font-size:1rem;
         /* Adjust the font size as needed */
         }
         .table th,
         .table td {
         padding: 5px;
         /* Adjust the padding as needed */
         }
         /* Change the color of the select dropdown */
         select#fundCategory,
         select#expenseParentCategory,
         select#accountTitle,
         select#categoryFund {
         color: white;
         font-weight: 550;
         font-size: 14px;
         background-color: rgba(12, 60, 140, 0.9);
         border-radius: 9px;
         }
         select#fundCategory option,
         select#expenseParentCategory option,
         select#accountTitle option {
         padding: 20px;
         }
         .back-button {
         display: inline-block;
         background: none;
         border: none;
         font-size: 24px;
         color: azure;
         text-decoration: none;
         transition: 0.3s ease;
         }
         .back-button:hover {
         transform: scale(1.2);
         cursor: pointer;
         }
         .financeThrough {
         display: none;
         }
         #selectedOptionsTable td:nth-child(2) {
         width: 100px;
         /* Adjust the width as needed */
         }.hide-buttons {
         display: none;
         }/* Add this CSS to your stylesheet */
         #dynamicTable .amount-cell {
         text-align: right;
         }
         /* Add this CSS to your stylesheet */
         /* For mobile devices (less than 576px width) */
         @media (max-width: 575.98px) {
         .btn-edit,
         .btn-delete {
         display: block;
         width: 100%;
         margin-bottom: 5px;
         }
         }
        input#budget,td {
         text-align: end;
         }
         .title-cell {
         text-align: center;
         }th{
            color:white;
            background-color:var(--azure)!important;
         }  .underline {
    border-bottom: 2px solid var(--gold); /* Change the color and size as desired */
    display: inline;
   font-weight:1000;
   font-family:Open sans-serif;
         } 
    .remarks-container {
      border-bottom: 2px solid var(--gold);

    padding: 10px;
    background-color: #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

/* Styling for the remarks text */
.remarks {
    font-size: 14px;
    color: #333;
    margin: 0;

}.container-form{
   padding:0px 55px 0px 49px;
}
/* Default font size for larger screens */
body {
  font-size: 16px;
}

label {
  font-size: 14px;
}

input[type="text"],
input[type="number"],
input[type="email"],
textarea {
  font-size: 14px;
}

/* Media query for screens up to 500px */
@media (max-width: 500px) {
  body,table,#date,select{
    font-size: 0.9rem!important;
  }.container-form{

padding:19px!important;
  }

  label {
    font-size: 0.9rem!important;
  }
  
  input[type="text"],
  input[type="number"],
  input[type="email"],
  textarea {
    font-size: 0.9rem!important;
  }
}

/* Media query for screens up to 768px */
@media (max-width: 768px) {
  body {
    font-size: 12px;
  }
  
  label {
    font-size: 10px;
  }
  
  input[type="text"],
  input[type="number"],
  input[type="email"],
  textarea {
    font-size: 10px;
  }
}

/* Media query for screens up to 992px */
@media (max-width: 992px) {
  body {
    font-size: 14px;
  }
  
  label {
    font-size: 12px;
  }
  
  input[type="text"],
  input[type="number"],
  input[type="email"],
  textarea {
    font-size: 12px;
  }
}

/* Media query for screens up to 1200px */
@media (max-width: 1200px) {
  body {
    font-size: 16px;
  }
  
  label {
    font-size: 14px;
  }
  
  input[type="text"],
  input[type="number"],
  input[type="email"],
  textarea {
    font-size: 14px;
  }
}     /* Default styles for landscape mode */
.rotate-prompt {
      display: none;
    }

    /* Media query to apply styles only in portrait mode */
    @media (orientation: portrait) {
      .rotate-prompt {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8); /* Semi-transparent background */
        z-index: 9999; /* Ensure it's displayed above other content */
      }
.wrap{
   background-color: rgba(255, 255, 255, 0.5); /* 0.5 represents 50% opacity */
}
      .rotate-prompt div {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
        text-align: center;
      }

      .rotate-prompt p {
        margin: 0;
        font-size: 18px;
        color: #333;
      }

      .close-button {
        margin-top: 20px; /* Adjust this for spacing */
        cursor: pointer;
        color: #007bff; /* Blue color for the close button */
        text-decoration: underline;
        border-radius:10px;
      }
    }

      .close-button {
      
        cursor: pointer;
        color:white!important;
        text-decoration: none;
        background-color:var(--azure);
      } .close-button:hover{
color:white;
background-color:var(--darkblue);
      }.table td, .table th{
         border:none!important;
      }td.validate{
         border-bottom: solid 1px #61646780 !important;
      }

      </style>
   </head>
   <body>
   <div class="rotate-prompt " id="rotate-prompt">
   <div class="mx-4 wrap">
   <p> Please rotate your device to landscape mode for a better experience.</p>
   </div>
   
    <p class="close-button btn btn-sm btn-primary mt-3" onclick="closeRotatePrompt()">Stay in Portrait</p>
  </div>

   <div class="container-fluid min-vh-100 position-relative d-flex p-0">
     
      <div class="container-fluid min-vh-100 position-relative d-flex p-0">
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
          <?php include 'template/modal.php'?>
            <!-- Navbar End -->
            <!-- Blank Start -->
            <div class="container-fluid">
               <div class="wrapper">
                  <div class="filter px-lg-2 pb-lg-2 px-md-2 pb-md-2">
                     <div class="modal-header d-flex justify-content-center mt-3">
                        <a href="javascript:history.back()" class="back-button"><i
                           class="fa-solid fa-circle-arrow-left fa-shake"></i></a>
                        <p class="modal-title" style="margin-left:auto;font-weigth:700;" id="addIncomeModalLabel">
                           LIST OF EXPENSE
         </p>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                     </div>
                     <div class="container-form container-fluid shadow-lg ">
                        <div class="">
                           <form action="CRUD/create/addExpensesDetail.php"  enctype="multipart/form-data" id="addExpenseForm" method="POST">
                           <div class="p-2 mt-2 text-end">
  <strong> Transaction ID:  <?php echo 'TXN-RO-' . str_pad($nextTransactionID, 3, '0', STR_PAD_LEFT);?></strong>
</div>
  
                           <input type="hidden" id="financeThroughList" name="financeThroughList" value="">      
                              <input type="hidden" id="expenseDataList" name="expenseDataList" value="">     
                              <input type="hidden" id="originalTotalBudget" name="setBudget" value="">
                              <input type="hidden" id="transactionID" name="transactionID" value="<?php echo $nextTransactionID; ?>">
                              <input type="hidden" id="totalExpenseAmount" name="totalExpenseAmount" value="">
                              <input type="hidden" name="receiptPath" id="receiptPath" value="<?php echo $receiptPath;?>">
                              <input type="hidden" id="totalAnnualBudget" name="totalAnnualBudget" value="">
                           
                              <div class="form-row mt-4">
                                 <div class="form-group col-12 col-md-3 col-lg-3">
                                    <label for="date" style="color: #555;">Date:</label>
                                    <input type="date" class="form-control col-12 " style="width:100%;" required id="date" name="date"
                                       placeholder="Enter the Location...">
                                 </div>
                                 <div class="form-group col-12 col-md-9 col-lg-9 mt-2">
                                    <label for="date" style="color: #555;">Location:</label>
                                    <input   type="text" id="location" name="project_location"  required class="col-12 form-control form-control-sm location mt-2" id="location" value="" required "
                                       placeholder="Enter the location...">
                                 </div>
                              </div>
                              <hr>
                              <div class="form-row mt-4">
                                 <div class="form-group col-12 col-md-4 col-lg-4 mx-auto d-flex flex-column align-items-center">
                                    <label for="expenseParentCategory" style="font-size: ; color: #555;">Expense
                                    Category:</label>
                                    <select class="form-control" id="expenseParentCategory" name="expenseParentCategory" required onchange="getExpenseData()">        
                                       <option style="color: #555;" value="" selected>Select Expense
                                          Category...
                                       </option>
                                       <?php require 'sqlGetData/getExpensesCategory.php'; ?>
                                    </select>
                                 </div>
                                 <div class="form-group col-12 col-md-7 col-lg-7  ml-lg-3  ml-md-3">
                                    <label style="color: #555;">Title:</label>
                                    <input type="text" class="form-control" required id="expenseTitle"
                                       name="expenseTitle" placeholder="Enter Title...">
                                 </div>
                              </div>
                              <hr class="full-width-hr">
                              <div class="form-row pl-md-3 mt-md-4 pl-lg-3 mt-lg-4 ">
                                                                  <div class="form-group col-12 col-md-4 col-lg- mx-auto px-md-4 px-lg-4">
                                       <label for="category_fund" style="color: #555;">Fund:</label>
                                       <select class="form-control" disabled id="fundCategory" onchange="toggleCategoryFund(); showAlert(this);" name="fundCategory">
                                          <option value="" selected>Select a Fund Category title...</option>
                                          <?php include 'sqlGetData/getEquity.php'; ?>
                                       </select>
                                    </div>         
                                 <div class="form-group col-12 col-md-8 col-lg-8 p-0" id="clubFundTbl" style="display:none;">
                                    <div class="float-right p-2">
                                       <span id="edit-table" onclick="showModalAndActions()" class="btn btn-sm btn-success float-right w-"><i class="fa fa-pencil"></i></span>
                                    </div>
                                    =
                                    <table id="dynamicTable" class="table mt-3 bg-white m-0 p0" style="width: 100%; font-size: 14px">
                                       <thead style="background-color: #005daa">
                                          <tr>
                                             <th style="color: white;">Fund</th>
                                             <th style="color: white;">Amount</th>
                                          </tr>
                                       </thead>
                                       <tbody></tbody>
                                       <tfoot>
                                          <tr>
                                             <td>Total</td>
                                             <td><span id="budget" name="budget" class="expense-budget mt-2"></span></td>
                                          </tr>
                                       </tfoot>
                                    </table>
                                 </div>
                              </div>
                              <hr class="full-width-hr" style="background-color: #f5BE5A;">
                              <div class="form- mt-4  ">
                                    <div class="d-flex flex-lg-row flex-sm-column col-12 justify-content-center align-items-center col-12">
                                    <div class="col-sm-6 col-6 d-flex flex-row justify-content-center align-items-end">
                                       
                                    <div class="TotalCurrentClubFund d-none">

                                    <span style=" color: #555;white-space:nowrap;" class="d-flex flow-row">Total Current Fund:  <strong><span class="ms-3" style="border-bottom:blue 1px solid;">
                                       <?php include 'sqlGetData/getTotalCurrentClubFund.php';?></span></strong>  
                                       <strong class="text-secondary">
                                          <span id="currentFund">
                                        
                                          </span>
                                          </strong>
                                       </span>
                                    </div>
                                    <div class="TotalCurrentNotClubFund d-none">
                                     <span style=" color: #555;white-space:nowrap;" class="notClubFund d-flex flow-row">Total Current Fund: &nbsp; <strong><span class="ms-3" style="border-bottom:blue 1px solid;">
                                       <p style="border-bottom:blue 1px solid;" class="notClubFundValue"></p>
                                       <strong class="text-secondary">
                                          <span id="currentFund">
                                        
                                          </span>
                                          </strong>
                                       </span>
                                     </div>
                                     <div class="TotalCurrentFund">
                                     <span style=" color: #555;white-space:nowrap;" class="d-flex flow-row">Total Current Fund:  <strong><span class="ms-3" style="border-bottom:blue 1px solid;">
                                       <?php include 'sqlGetData/getTotalCurrentFund.php';?></span></strong>  
                                       <strong class="text-secondary">
                                          <span id="currentFund">
                                        
                                          </span>
                                          </strong>
                                       </span>
                                     </div>
                                   
                     
                                       </div>
                                       <div class="col-12 mx-sm-3 mt-3 col-lg-12 col-md-12 col-sm-12">
                                          <label style="font-size: ; color: #555;">Budget:</label>
                                          <input type="text" disabled class="form-control form-control-sm setBudget col-12 mt-2 px-1 " id="totalBudgetInput" name="totalBudgetInput" value="" oninput="formatInputBudget(this)">
                                       </div>
                                    </div>
                                     
                                 <hr class="full-width-hr" style="background-color: #f5BE5A;">
                                 <div class="form-row m-0 p-0 col-12 mt-3 pt-3">
                                    <div class="col-12">
                                       <div class="form-group " style="width: 100%;">
                                          <div class="d-flex flex-column  flex-md-row flex-lg-row ">
                                             <div class="col-sm-8 col-md-6 col-lg-6 ">
                                                <label style="font-size: ; color: #555;">Account Title : 
                                                   <div id="result">
                                    
                                                    </div>
                                                      </label>
                                                
                                                
                                                <select class="form-control" style="width:100%;" id="accountTitle" name="accountTitle" required onchange="">
                                                   <option style="" name="expense_amount[]" value="" selected>
                                                      Select Expense Category...
                                                      
               
                                                </select>
                                             </div>
                                             <div class="col-sm-8 mt-2  col-md-6 col-lg-6 d-flex  justify-content-center align-items-center">
                                                <label class="mt-3 me-2" style="font-size: ; color: #555; ">Amount:</label>
                                                <input type="text" required style="width:60%;" class="form-control form-control-sm expense-amount mt-2" name="expense_amount[]" id="expenseAmountInput" value="" required oninput="formatInputExpense(this)">
                                                <button type="button" class="btn-sm ms-2 btn btn-primary" onclick="addExpenseItem()">Add</button>
                                              </div>
                                             
                                          </div>
                                       </div>
                                       <div class="">
                                       <div id="expenseTableContainer" class="table-responsive px-1">
   <div class="row px-1 col- mt-4">
      <table class="table mx-1 table-striped bg-white" style="width: 100%;" ui-jq="dataTable">
         <thead style="background-color:#005daa">
            <tr>
               <th style="color:white;text-align:center;">Expense</th>
               <th style="color:white;text-align:right;">Amount</th>
               <th style="color:white;text-align:center;">FinanceThrough</th>
               <th style="color:white;text-align:right">Balance</th>
               <th style="color:white;text-align:center; ">Actions</th>
               <!-- Added Actions column -->
            </tr>
         </thead>
         <tbody id="expenseTableBody">
            <!-- Expense items will be dynamically added here -->
         </tbody>
         <tfoot>
            <td class="bg-secondary"></td>
            <td>
         
               <span class="" colspan="3" id="totalAmount">0.00</span>
            </td>
            <td class="bg-secondary"></td>
            <td class="bg-secondary" id="totalAmountBalance">
        
            </td>
            <td class="bg-secondary"></td>
            </tr>
         </tfoot>
      </table>
   </div>
</div>

                                       </div>
                                      
                                    </div>
                                    <hr style="background-color:#f5BE5A;">
                                    <div class="form-row">
                                       <!-- Fund and category fund select inputs -->
                                    </div>
                                    <hr>
                                    <div class="form-group col-md-12 ">
                                       <hr style="background-color:#f5BE5A;">
                                       <label for="remarks" style=" color: #555;">Remarks:</label>
                                       <textarea class="form-control" id="remarks" name="remarks"
                                          placeholder="Enter the remarks..."></textarea>
                                    </div>
                                    <div class="form-group col-md-12">
                           <label for="editReceipt">Receipt:</label>
                           <div id="successMessage" class="alert alert-success" style="display: none;">
   Receipt file successfully upload.
</div>
                           <div class="custom-file">
                              <!-- Hide the actual file input -->
                              <input type="file" class="custom-file-input" id="newReceipt" name="newReceipt"  accept=".png, .jpeg, .jpg, .gif" style="display: none;">
                              <!-- Add this input field to store the existing receipt path -->
                              <!-- Display the custom label for the existing receipt -->
                              
                              <!-- Display a separate label for the new receipt -->
                              <label class="custom-file-label" for="newReceipt" id="receiptName">Choose a File</label>
                              <!-- Add a message div to display the success message -->


                           </div>
                           
                                             </div>
                   
                                 </div>
                                 <div class="form-group col-md-12 mt-3">
                                 <button type="button" class="btn btn-primary float-right" id="submitButton">Submit</button>
                                 </div>
                           </form>
                           </div>
                           <!-- Scroll to Bottom Button -->
                         
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!-- modal -->
            <!-- The Modal -->
            <div id="categoryFundModal" class="modal" style="display: none;">
            <div id="notificationError" class="alert alert-danger m-0" style="z-index: 9999;
    display: none;
    text-align: center;
    position: absolute;
    width: 100%;"

    >
    <!-- Your error message goes here -->
   <strong>Please enter a valid amount. The amount must not be zero or exceed the current total fund.</strong>
</div>
               <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                  <div class="modal-content">
                     
                     <div class="modal-header">
                        
                        <h5 class="modal-title">Finance Through:</h5>
                        
                        <button id="categoryFundCloseButton" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                     </div>
                     <div class="modal-body p-1 m-0">
                        
                        <div class="form-group col-12" id="categoryFundContainer" style="display: none;">
                           <label for="category" style="font-size:; color: #555;">Finance Through:</label>
                           <select class="form-control p-2 btn-sm btn-primary" id="categoryFund" name="categoryFund[]" multiple onchange="">
                           <?php require 'sqlGetData/getRevenueCategory.php'; ?>
                           </select>
                           <div>
                           </div>
                        </div>
                     </div>
                     <div class="modal-footer">
                        <button id="categoryFundCancelButton" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                     </div>
                  </div>
               </div>
            </div>
            <!-- Modal -->
            <!-- Add modal -->
            <!-- Add the modal Amounyy Budget code -->
            <div class="modal fade" id="amountModal" tabindex="-1" role="dialog" aria-labelledby="amountModalLabel" aria-hidden="true">
               <div class="modal-dialog modal-dialog-centered " role="document">
                  <div class="modal-content">
                     <div class="modal-header">
                        <h5 class="modal-title" id="amountModalLabel">Enter Amount</h5>
                        </button>
                     </div>
                     <div class="modal-body" style="height: 388px!important;">
                        <div class="form-group">
                           <div>
                              <label for="">Finance Through</label>
                              <p id="financeThroughTitle"></p>
                              <p style="font-size: 14px; color: #555;">Current Fund:
                                 <span style="border-bottom: blue 1px solid;">
                                 <strong class="text-secondary" id = "currentFundModal">
                                 <script>
  document.addEventListener("DOMContentLoaded", function() {
    var financeThroughTitle = document.getElementById("financeThroughTitle");

    var observer = new MutationObserver(function(mutationsList) {
        for (var mutation of mutationsList) {
            if (mutation.type === "childList" && mutation.target === financeThroughTitle) {
                var newValue = financeThroughTitle.textContent.trim();
                if (newValue === "Initial Fund") {
                  updateAmountForInitial()
                } else {
                    updateCurrentFund(newValue);
                }
            }
        }
    });

    observer.observe(financeThroughTitle, { childList: true });
});

function updateAmountForInitial(){
   $.ajax({
                url: 'sqlGetData/getInitialBalance.php', // Change to your PHP script's path
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Update the initial fund value on the page
                    $('#currentFundModal').text(formatAmountWithCommas((parseFloat(data.initialFund))));
                },
                error: function() {
                    $('#currentFundModal').text('Error fetching data');
                }
            });
}

function updateCurrentFund(value) {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "sqlGetData/getCurrentAmountFinance.php?newValue=" + encodeURIComponent(value), true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = xhr.responseText; // Get the response from the PHP script
            var currentFundModal = document.getElementById("currentFundModal");
            currentFundModal.innerHTML = response;
        }
    };
    xhr.send();
}


</script>
                                 </strong>
                                 </span>
                              </p>
                           </div>
                           <label for="amountInput">Amount:</label>
                           <input type="text" class="form-control form-control-sm setBudget mt-2 px-1" id="amountInput" name="totalBudgetInput" value="" oninput="formatInput(this)" onkeydown="checkEnter(event)">
                           <table id="selectedOptionsTable2" class="table  table-striped mt-3 bg-white" style="width: 100%; font-size: 14px;" ui-jq="dataTable">
                              <thead style="background-color:#005daa">
                                 <tr>
                                    <th style="color:white;">Fund</th>
                                    <th style="color:white;">Amount</th>
                                    <th class="action-col" style="color:white;">Action</th>
                                 </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                 <tr>
                                    <td>Total</td>
                                    <td colspan='' id="totalAmountCell"></td>
                                 </tr>
                              </tfoot>
                           </table>
                        </div>
                     </div>
                     <div class="modal-footer mt-5 ">
                        <button type="button" class="btn btn-secondary" onclick="closeAmount()" data-dismiss="modal">Back</button>
                        <button type="button" class="btn btn-primary" onclick="saveAmount('both')">Add</button>                  
                     </div>
                  </div>
               </div>
               <!-- Add a modal dialog HTML (hidden by default) -->
            </div>

            <!-- Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" style="min-width: 65%; padding-bottom: 20px;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmModalLabel">Confirm Submission</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        
        </button>
      </div>
      <div class="modal-body d-flex row">
        <p class="text-center"><strong>Are you sure you want to submit the form with the following information?</strong></p>
        <hr>
        <div id="modalData"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="cancelSubmit()">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="submitForm()">Submit</button>
      </div>
    </div>
  </div>
</div>

            <!-- Blank End -->
            <!-- Footer Start -->
            <!-- Footer End -->
         </div>
         <!-- Content End -->
         <!-- Back to Top -->
      </div>
      <!-- JavaScript Libraries -->
      <script src="assets/js/jquery.js"></script>
      <script src="/assets/js/"></script>
      <script src="lib/chart/chart.min.js"></script>
      <script src="lib/easing/easing.min.js"></script>
      <script src="lib/waypoints/waypoints.min.js"></script>
      <script src="lib/owlcarousel/owl.carousel.min.js"></script>
      <script src="lib/tempusdominus/js/moment.min.js"></script>
      <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
      <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
      <!-- Template Javascript -->
      <script src="js/cdn.jsdelivr.net_npm_bootstrap@5.0.0_dist_js_bootstrap.bundle.min.js"></script>

      <script src="js/main.js"></script>
   </body>  
   





 

 
   <script>


  function showErrorModal(errorMessage) {
      var errorModal = new bootstrap.Modal(document.getElementById("errorModal"));
      var errorMessageElement = document.querySelector("#errorModal .modal-body p");
      errorMessageElement.textContent = errorMessage;
      errorModal.show();
      var closeButton = document.querySelector("#errorModal .btn-secondary");
       closeButton.addEventListener("click", closeModal);
      }

function calculateTotalDynamic() {

var total = 0;
  var amountCells = document.querySelectorAll("span.editable");

  amountCells.forEach(function (cell) {
      var amount = parseFloat(removeNonNumericCharacters(cell.textContent));
      if (!isNaN(amount)) {
          total += amount;
      }
  });

  
  var totalAmountCell = document.getElementById("totalAmountCell");
  totalAmountCell.textContent = formatAmountWithCommas(total);
}


      var selectedData = [];
  var selectedExpenseData = [];
  var optionsData=[];

var errorFound = false; // Flag to track if an error is found


function updateHiddenInputs() {
    selectedData = []; // Clear the selectedData array
    var totalExpenseAmount = 0;

    // Get the corresponding expense title and amounts from the expenseTable
    var expenseTableBody = document.getElementById('expenseTableBody');
    var selectedFund = document.getElementById('fundCategory').value;

    var expenseTableContainerBody = document.getElementById('expenseTableContainer').getElementsByTagName('tbody')[0];
    var dynamicTableBody = document.getElementById('dynamicTable').getElementsByTagName('tbody')[0];



    // Iterate through the rows of the dynamic table
    for (var i = 0; i < dynamicTableBody.rows.length; i++) {
        var dynamicTableRow = dynamicTableBody.rows[i];
        var dynamicTableText = dynamicTableRow.cells[0].textContent.trim();

        var matchFound = false; // Flag to track if a match is found in the expense table


  
        // Iterate through the rows of the expense table
        for (var j = 0; j < expenseTableBody.rows.length; j++) {
            var expenseTableRow = expenseTableBody.rows[j];
            var expenseTableText = expenseTableRow.cells[2].querySelector('select').value;

            if (dynamicTableText === expenseTableText) {
                matchFound = true;
                break; // Exit the inner loop once a match is found
            }
        }

        if (!matchFound) {
            // Display an error message because a match was not found
            errorFound = true;
            return errorFound;
        }
    }

    if (!errorFound) {
      for (var i = 0; i < optionsData.length; i++) {
      var optionItem = optionsData[i];
        
      selectedData.push({

        category_fund: optionItem.category, // Use category as expenseTitle
        amount: optionItem.amount // Use amount as amount
      });
    }
    
    for (var k = 0; k < expenseTableBody.rows.length; k++) {
        var row = expenseTableBody.rows[k];
        var expenseTitle = row.cells[0].textContent.trim();
        var amountCell = row.cells[1];
        var amountCellValue = amountCell.textContent;
        var amount = parseFloat(removeNonNumericCharacters(amountCellValue));
      
        if (selectedFund == "Club Fund") {
            var expenseAmount = parseFloat(removeNonNumericCharacters(row.cells[1].textContent)); // Assuming the dropdown is in the third cell of each 
            var dropdownCell = row.cells[2]; // Assuming the dropdown is in the third cell of each row
            var amountCell = row.cells[3]; // Assuming the amount is in the fourth cell of each row
            var selectedCategory = dropdownCell.querySelector('select').value;
            var selectedAmount = parseFloat(removeNonNumericCharacters(amountCell.textContent)); // Parse the amount from the amount cell
            var existingIndex = selectedExpenseData.findIndex(item => item.expenseTitle === expenseTitle);
            if (existingIndex !== -1) {
                selectedExpenseData[existingIndex].amount = expenseAmount; // Update amount if category exists
                selectedExpenseData[existingIndex].financeCategory = selectedCategory; // Update financeCategory
                selectedExpenseData[existingIndex].financeThrough = selectedAmount; // Update financeThrough amount
            } else {
                selectedExpenseData.push({
                    expenseTitle: expenseTitle,
                    amount: amount,
                    financeCategory: selectedCategory,
                    financeThrough: selectedAmount,
                });
            }
        } else {
            var existingIndex = selectedExpenseData.findIndex(item => item.expenseTitle === expenseTitle);
            if (existingIndex !== -1) {
                selectedExpenseData[existingIndex].amount = amount; // Update amount if category exists
            } else {
                selectedExpenseData.push({
                    expenseTitle: expenseTitle,
                    amount: amount,
                });
            }
        }

        totalExpenseAmount += amount; // Calculate the total expense amount
    }

    // Update the hidden input fields with the JSON representation of the selected data and expense data
    document.getElementById('financeThroughList').value = JSON.stringify(selectedData);
    document.getElementById('expenseDataList').value = JSON.stringify(selectedExpenseData);
    document.getElementById('totalExpenseAmount').value = totalExpenseAmount;

    // Call deleteExpenseItem for any deleted rows
    var rowsToDelete = Array.from(expenseTableBody.querySelectorAll('tr.deleted'));
    rowsToDelete.forEach(row => deleteExpenseItem(row));
    }else {
    // Display an error message because a match was not found
    showErrorModal("One or more items in set budget do not have a corresponding entry in the expense");
  }
}


// ------------------------------------------VALIDATION FORM TO SUBMIT-----------------------------------


function getList(){


var budget = document.getElementById('totalBudgetInput').value;

// Set the value to 'totalBudgetAnnual' hidden input
var totalAnnualBudget = document.getElementById('totalAnnualBudget');
totalAnnualBudget.value = removeNonNumericCharacters(budget);

var fileInput = document.getElementById('newReceipt').value;
let filename = fileInput.substring(12);


var selectedDataContent = "<p class='text-center text-dark'> <hr>Budget<hr></p> <div class=''><div class='table-responsive px-0 px-md-5 px-lg-5'><table class='table table-borderless table-striped table-hover px-5'><tr>";

var selectedFund = document.getElementById('fundCategory').value;

if (selectedFund !== "Club Fund") { 
selectedDataContent += `<th class='text-center' colspan='2'>${selectedFund}</th></tr>`;  // Display selectedFund value centered in the first row 
} else {
selectedDataContent += "<th class='text-center'>Category</th><th  class='text-center'>Amount</th></tr>"; // Add Category and Amount headers if selectedFund is "Club Fund"
}

var previousRow = null;

for (var i = 0; i < selectedData.length; i++) {
var currentRow = "";

if (selectedFund === "Club Fund" || selectedFund === "") {
  currentRow += `<td class='validate text-center'>${selectedData[i].category_fund}</td>`;
  currentRow += `<td class="validate">${formatAmountWithCommas(parseFloat(selectedData[i].amount))}</td>`;
} else {
  // Display the selectedFund value centered and in a single column for non-"Club Fund" rows
  currentRow += `<td class='validate text-center' colspan='2'>${selectedData[i][selectedFund]}</td>`;
}

// Check if the current row is the same as the previous row
if (currentRow !== previousRow) {
  selectedDataContent += "<tr>" + currentRow + "</tr>";
}

// Update the previous row
previousRow = currentRow;
}

// Close the table
selectedDataContent += "</table></div></div>";

// Now you can use selectedDataContent as your HTML table content without duplicate rows

selectedDataContent += "</table></div></div>";
selectedDataContent += "</table></div></div>";

// Create an object to store the financeThrough groups
var financeThroughGroups = {};

// Iterate through the selectedExpenseData and group expenses by financeThrough
for (var i = 0; i < selectedExpenseData.length; i++) {
var expense = selectedExpenseData[i];
var financeThrough = expense.financeCategory;

if (!financeThroughGroups[financeThrough]) {
financeThroughGroups[financeThrough] = [];
}

financeThroughGroups[financeThrough].push(expense);
}
var selectedExpenseDataContent = "<p class='text-center text-dark'> <hr>List of Expenses <hr></p><div class='px-0 px-md-5 px-lg-5'><table class='table table-striped table-hover px-5'><tr><th class='text-center' style='width: 30%;'>Title</th><th style='width: 10%;' class='text-center' >Amount</th><th style='width: 30%;'class='text-center'>Finance Through</th><th style='width: 0%;' class='text-center'>Balance</th></tr>";

var previousFinanceCategory = null; // Store the previous finance category

// Iterate through the financeThroughGroups to generate the selectedExpenseDataContent
for (var financeThrough in financeThroughGroups) {
var expenses = financeThroughGroups[financeThrough];

// Sort expenses within each group by highest to lowest amount
expenses.sort((a, b) => a.amount - b.amount);

// Get the expense with the highest balance (first in the sorted array)
var expenseWithHighestBalance = expenses[0];

// Check if the finance category has changed, and if so, add a border-bottom
if (financeThrough !== previousFinanceCategory) {
if (previousFinanceCategory !== null) {
selectedExpenseDataContent += "<tr><td colspan='4' style='padding: 0px!important;background: #0c4778;'></td></tr>";
} 
previousFinanceCategory = financeThrough;
}

// Iterate through expenses in the group and display the highest balance with Finance Through
for (var i = 0; i < expenses.length; i++) {
var expense = expenses[i];
var displayBalance = (expense === expenseWithHighestBalance) ? formatAmountWithCommas(expense.financeThrough) : '';
var financeThroughStyle = (i === 0) ? '' : 'background-color: #f2f2f2;';
var balanceStyle = (displayBalance === '') ? 'background-color: #f2f2f2;' : '';

selectedExpenseDataContent += ` <tr>
<td class='validate text-center '>${expense.expenseTitle}</td>
<td class="validate">${formatAmountWithCommas(expense.amount)}</td>
<td class="validate text-center" style='${financeThroughStyle}'>${(i === 0) ? expense.financeCategory : ''}</td>
<td class="validate text-right balanceThrough" style='${balanceStyle}'>${displayBalance}</td>
</tr>`;

}
}


selectedExpenseDataContent += "</table></div></div>";


// Get the form data
var date = document.getElementById("date").value;
var expenseParentCategory = document.getElementById("expenseParentCategory").value;
var expenseTitle = document.getElementById("expenseTitle").value;
var totalBudgetInput = document.getElementById("totalBudgetInput").value;
var totalExpense = document.getElementById("totalAmount").innerHTML;
var accountTitle = document.getElementById("accountTitle").value;
var expenseAmountInput = document.getElementById("expenseAmountInput").value;
var remarks = document.getElementById("remarks").value;
var remainingBudget =parseFloat(removeNonNumericCharacters(totalBudgetInput)) - parseFloat(removeNonNumericCharacters(totalExpense));


// Create the modal content with the form data
var modalContent = `
<div class="container">
<div class="row p-2 m-0">
<div class="col-12 d-flex justify-content-between">
<p>Date:</p> <p class="me-3"> ${date}</p>
</div>
</div>
<hr>
<div class="row ps-2 m-0">
<div class="col-12 d-flex justify-content-between">
<p>Title:</p>  <p class="me-3">${expenseTitle}</p>
</div>
<div class="col-12 d-flex justify-content-between">
<p>Expense Category:</p>  <p class="me-3">${expenseParentCategory}</p>
</div>
</div>
</div>


${selectedDataContent}

<div class="row">
<p class="text-end " style="position:relative;left: -53px;">Total Budget: &nbsp; <strong class="underline"> ${totalBudgetInput}</strong></p>
</div>

${selectedExpenseDataContent}

<div class="row">
<p class="text-center " style="position:relative;left: -75px;">Total Expense: &nbsp;<strong class="underline"> ${totalExpense}</strong></p>
</div>

<hr>
<div class="remarks-container px-3">
<div class=>
<hr>
<div class=" d-flex flex-row justify-content-between">
<p class="ms-auto me-5">Total Budget:</p> <p class="text-end " style="position:relative;left: -33px;"><strong class="underline"> &nbsp;${totalBudgetInput}</strong></p>
</div>
<div class="d-flex flex-row justify-content-between">
<p class="ms-auto me-5">Total Expense:</p> <p class="text-end ms-2 " style="position:relative;left: -33px;"><strong class="underline">&nbsp; ${totalExpense}</strong></p>
</div>
${remainingBudget >= 0 ? `
<div class=" d-flex flex-row justify-content-between">
<p class="ms-auto me-5 ">Remaining Budget:</p> <p class="text-end " style="position:relative;left: -33px;"><strong class="underline">&nbsp; ${formatAmountWithCommas(remainingBudget)}</strong></p>
</div>
` : ''}
<hr>
<textarea class="form-control font-weight-bold font-italic" style="background-color:#f6f5e9;" disabled>${remarks}</textarea>
</div>
</div>
<div class="p-2 mt-1">
<p id="receiptLabel" >Receipt:   ${filename} </p>
</div>
`;


// Set the modal content
document.getElementById("modalData").innerHTML = modalContent;

// Show the modal
$('#confirmModal').modal('show');

// Prevent form submission for now
return false;
}


// ------------------
function validateForm() {


updateHiddenInputs();


if(!errorFound){
  const selectedFund = document.getElementById('fundCategory').value;
const date = document.getElementById('date').value;
const location  = document.getElementById('location').value;
const tableBody = document.getElementById("expenseTableBody");
const rows = tableBody.getElementsByTagName("tr");

// Check if the Finance Through column has a value in any row
let hasValue = false;
for (let i = 0; i < rows.length; i++) {
  const fourthColumn = rows[i].getElementsByTagName("td")[3];
  if (fourthColumn.textContent.trim() !== "") {
    hasValue = true;
    break;
  }
}

if (!hasValue) {
  showErrorModal("Please Select Finance Through");
  return;
}

if (selectedFund === "Club Fund") {
  if (selectedData.length === 0 || selectedExpenseData.length === 0 || date === "" || location ==="") {
    showErrorModal("Please Fill up all the required fields before proceeding with the submission!");
    return;
  } else {
    getList();
  }
} 
}

}




// ------------------------------------------VALIDATION FORM TO SUBMIT-----------------------------------

function submitForm() {
  // Hide the modal
  $('#confirmModal').modal('hide');

  // Submit the form
  document.getElementById('addExpenseForm').submit();
}

   </script>


  <script>


document.getElementById("submitButton").addEventListener("click", function() {
  
   
  validateForm();

});

   </script>



<script>
document.getElementById("addExpenseButton").addEventListener("click", handleAddExpense);
function handleAddExpense() {
  // Get the necessary elements and values
  const amountInput = document.getElementById("expenseAmountInput");
  const totalBudgetInput = document.getElementById("totalBudgetInput"); // Assuming you have this element
  const expenseAmountElements = document.querySelectorAll('td.text-right.expenseAmount');
 
  
}
</script>


<script>



   function displayFileNameAdd() {
    var fileInput = document.getElementById('newReceipt');
    var fileName = fileInput.files[0].name;
    var receiptLabel = document.getElementById('receiptLabel');
    receiptLabel.innerText = fileName;
}
</script>

<!-- Place this in the <head> section of your HTML -->
<!-- Assuming you have two select elements with IDs 'expenseParentCategory' and 'accountTitle' -->
<!-- Assuming you have two select elements with IDs 'expenseParentCategory' and 'accountTitle' -->
<!-- Assuming you have two select elements with IDs 'expenseParentCategory' and 'accountTitle' -->
<script>

$(document).ready(function() {
  var selectionCount = 0;    // To track the number of selections

  $('#fundCategory').on('change', function() {
    selectionCount++;
    if (selectionCount === 2) {
      deleteAllRowsFromDynamicTable();

      deleteAllRowsFromTableSelection();
      selectionCount = 0; // Reset the count after deleting rows
    }
  });
});




function checkEnter(event) {
  if (event.key === "Enter") {
 
    // The Enter key was pressed, so call the saveAmount function with 'both' as the argument.
    saveAmount('both');
    
    // Prevent the default form submission behavior (if this input is inside a form).
    event.preventDefault();
  }
}



  function getExpenseData() {

   var accountTitle = document.getElementById("accountTitle");
   var expenseParentCategory = document.getElementById("expenseParentCategory");
        var fundCategory = document.getElementById("fundCategory");

      
      // Check if fundCategory doesn't have a value initially
if (fundCategory.value === "") {
  fundCategory.selectedIndex = 0; // Set selectedIndex to 0
}

        if (expenseParentCategory.value !== "") {
            fundCategory.disabled = false;
        } else {
            fundCategory.disabled = true;
        }
 
   
    var selectedOption = document.getElementById("expenseParentCategory").value;
   

    var data = {
      selectedOption: selectedOption
    };
    
    // Send the AJAX request to the PHP file
    $.ajax({
      url: "sqlGetData/getChildExpenseList.php",
      type: "POST",
      data: data,
      success: function(response) {
  const options = response ? response.split(',') : []; // Check if response is null or empty

  // Clear previous options in 'accountTitle' select element
  const selectElement = document.getElementById("accountTitle");
  selectElement.innerHTML = '';

  function createOptions() {
    let optionHTML = '';
    if (options.length === 0) {
      // If 'options' array is empty, display "No data" option
      optionHTML = '<option value="no_data">No data Yet</option>';
    } else {
      for (const option of options) {
        // Check if the option is not empty or whitespace-only
        if (option.trim() !== '') {
          optionHTML += `<option value="${option}">${option}</option>`;
        }
      }
    }
    return optionHTML;
  }

  // Insert the option elements into the select element
  selectElement.innerHTML = createOptions();
},
      error: function(xhr, status, error) {
        // Handle errors, if any
        console.error("Error: " + error);
      }
    });
  }
</script>




 
 <script>
  

  
  function deleteAllRowsFromExpense() {

var expenseTableContainerBody = document.getElementById('expenseTableContainer').getElementsByTagName('tbody')[0];
var rowCountExpense = expenseTableContainerBody.rows.length;

// alert("expense numbre"+rowCountExpense);
for (var i = rowCountExpense - 1; i >= 0; i--) {
  expenseTableContainerBody.deleteRow(i);
}

// After deleting all rows, you may want to recalculate the total for the expense table
calculateTotalAmount();
}

// After deleting all rows, you may want to recalculate the total for the expense table

function deleteAllRowsFromTableSelection() {
  var selectedOptionsTable2Body = document.getElementById('selectedOptionsTable2').getElementsByTagName('tbody')[0];
  var rowCountSelectedOptions2 = selectedOptionsTable2Body.rows.length;

  for (var i = rowCountSelectedOptions2 - 1; i >= 0; i--) {
    selectedOptionsTable2Body.deleteRow(i);
  }

  // After deleting all rows, you may want to perform other tasks
}

function deleteAllRowsFromDynamicTable() {
      
      var  expenseTableContainerBody = document.getElementById('expenseTableContainer').getElementsByTagName('tbody')[0]; 
      var rowCountExpense = expenseTableContainerBody.rows.length;
   
     var dynamicTableBody = document.getElementById('dynamicTable').getElementsByTagName('tbody')[0];
     var rowCount = dynamicTableBody.rows.length;
   
     for (var i = rowCount - 1; i >= 0; i--) {
       dynamicTableBody.deleteRow(i);
     }
   
     for (var i = rowCountExpense - 1; i >= 0; i--) {
      expenseTableContainerBody.deleteRow(i);
     }
   
     // After deleting all rows, you may want to recalculate the total for the dynamic table
     calculateTotal('dynamicTable');
     calculateTotalAmount();
   }




       document.getElementById('newReceipt').addEventListener('change', function() {
    var fileInput = this;
    var file = fileInput.files[0];
    var receiptLabel = document.getElementById('receiptName');
    
    if (file) {
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
       }, 1500);
    } else {
       receiptLabel.textContent = 'Choose File';
    }
 });
   </script>

   
   <script>
      document.addEventListener("DOMContentLoaded", function() {
        var fundCategorySelect = document.getElementById('fundCategory');
        var defaultOptionValue = "default"; // Value of the new default option
      
        // Add a change event listener to the select element
        fundCategorySelect.addEventListener('change', function() {
          // Check if the selected option value is equal to the default option value
          if (fundCategorySelect.value === defaultOptionValue) {
            // Reset the select element to the default option (empty value)
            fundCategorySelect.selectedIndex = 0;
          }
      
          // Call the toggleCategoryFund() function to handle the change event
          toggleCategoryFund();
        });
      });
   </script>
   <script>
  // ... (other script code)

  



</script>


   <script>

    function closeRotatePrompt() {
      const rotatePrompt = document.querySelector(".rotate-prompt");
      if (rotatePrompt) {
        rotatePrompt.style.display = "none";
      }
    }

      var currentFundModal = document.getElementById('currentFundModal');
      var currentFund = document.getElementById('currentFund');
               
      currentFund.innerHTML = currentFundModal.innerHTML;
      var disabledInput = document.querySelector("input[name='totalBudgetInput']");

// Store the original value of the total budget in the hidden input
  
   </script>
   <script>
      // ---------------------------------------------------------------------------------------------------
            var budgetAmount = parseFloat(document.getElementById("budget").value);
            // FORMAT ALL NUMBER INTO PESO WITH DECIMAL AND COMMA
            function formatAmountWithCommas(amount) {
              var formattedAmount = amount.toLocaleString('en-PH', {
                style: 'currency',
                currency: 'PHP',
                minimumFractionDigits: 2,
              });
            
              // Add non-breaking spaces before the currency symbol
              var spaceBeforeCurrency = '\u00A0'; // Non-breaking space character
              var indexOfCurrencySymbol = formattedAmount.indexOf('PHP');
              if (indexOfCurrencySymbol > 0) {
                formattedAmount = formattedAmount.replace('PHP', spaceBeforeCurrency + 'PHP');
              }
            
              return formattedAmount;
            }
               
            function removeNonNumericCharacters(inputString) {
              return inputString.replace(/[^\d.-]/g, '');
            }
      
            function formatInput(input) {
            var value = removeNonNumericCharacters(input.value); // Remove non-numeric characters from the input
            var amount = parseFloat(value); // Convert the input value to a number
            
            if (!isNaN(amount) || value === '') {
            var formattedAmount = formatAmountWithCommas(amount);
            input.value = formattedAmount; // Set the formatted value back to the input field
            } else {
            input.value = ""; // Set the input value without formatting (non-numeric input)
            }
            }
            
         
            function formatInputBudget(input) {
      
            var value = removeNonNumericCharacters(input.value); // Remove non-numeric characters from the input
            var amount = parseFloat(value); // Convert the input value to a number
      
            if (!isNaN(amount) || value === '') {
              var formattedAmount = formatAmountWithCommas(amount);
              input.value = formattedAmount; // Set the formatted value back to the input field
            } else {
              input.value = ""; // Set the input value without formatting (non-numeric input)
            }
          }
      
            function formatInputExpense(input) {
            var value = removeNonNumericCharacters(input.value); // Remove non-numeric characters from the input
            var amount = parseFloat(value); // Convert the input value to a number
              var totalAmountElement = document.getElementById("totalAmount");
        var totalAmount = parseFloat(removeNonNumericCharacters(totalAmountElement.textContent));
      
        // Check if the total amount is zero, and set the input value to zero
       
            if (!isNaN(amount) || value === '') {
            var formattedAmount = formatAmountWithCommas(amount);
            input.value = formattedAmount; // Set the formatted value back to the input field
            } else {
            input.value = ""; // Set the input value without formatting (non-numeric input)
            }
            
            }
      
            // END OF ALL THE FUNCTION TO FORMAT THEINPUT AND OUTPUT NUMBERS
      
      // ---------------------------------------MAKE THE INPUT IN EXPENSE ZERO VALIDATION------------------------------------------------------------
      
        // Function to periodically check and update the expense input value
      // Cache the elements outside of the function to avoid repeated DOM queries
var totalAmountElement = document.getElementById("budget");
var expenseInput = document.getElementById("expenseAmountInput");
var fundCategoryElement = document.getElementById("fundCategory");

// Add an event listener to the fund category element to listen for changes
fundCategoryElement.addEventListener("change", updateExpenseInputValue);

function updateExpenseInputValue() {
    var totalAmountBudget = parseFloat(totalAmountElement.value);
    var fundCategory = fundCategoryElement.value;

    if (fundCategory === "Club Fund") {
        if (isNaN(totalAmountBudget) || totalAmountBudget === 0) {
            expenseInput.value = "0";
            expenseInput.placeholder = "0";
        } else {
            // Handle other cases when fund category is "Club Fund"
        }
    } else {
        // Handle other fund categories
    }
}

// Call the function initially
updateExpenseInputValue();

      // ----------------------------------END OF THE SCRIPT---------------------------------   --------------------------------
      
         
      // ----------------------------------SCRIPT FOR SET BUDGET TABLE-----------------------------------------------------------------
      
            var totalClubFund = $('.TotalCurrentClubFund');
            var notClubFund = $('.TotalCurrentNotClubFund');
            var totalFund = $('.TotalCurrentFund');
            var fundCategory = $('#fundCategory');
              var categoryFundModal = $('#categoryFundModal');
              var closeButton = $('#categoryFundCloseButton');
              var applyButton = $('#categoryFundApplyButton');
              var cancelButton = $('#categoryFundCancelButton');
              var budget = $('#budget');
              var amountModal = $('#amountModal');
              var financeThroughTitle = document.getElementById('financeThroughTitle');
              var totalBudget =  document.getElementById('totalBudgetInput');
            var selectedOptions = [];
            
            function showAlert(selectElement) {
    var selectedValue = selectElement.value;
    
    if (selectedValue ==""){
      totalFund.css('display', 'block'); // Hide totalClubFund
      totalClubFund.addClass('d-none');
      notClubFund.addClass('d-none'); // Remove d-none to show notClubFund
    }
    else if (selectedValue == "Club Fund") {
      notClubFund.addClass('d-none'); // Remove d-none to show notClubFund

      totalFund.css('display', 'none'); 
      totalClubFund.removeClass('d-none');
    } else {


      var xhr = new XMLHttpRequest();

// Define the PHP script URL and specify the HTTP method
var url = 'sqlGetData/getTotalCurrentNotClubFund.php'; // Replace with the actual URL of your PHP script
var method = 'GET';

// Create a query string with the 'fund' variable
var params = selectedValue + encodeURIComponent(selectedValue);

// Open a new AJAX request
xhr.open(method, url + '?' + params, true);

// Set up a callback function to handle the response
xhr.onreadystatechange = function () {
  if (xhr.readyState === 4 && xhr.status === 200) {
    // The request was successful, and the response is in xhr.responseText
    var response = xhr.responseText;
 $('.notClubFundValue').text(formatAmountWithCommas(parseFloat(response))); 
  }
};

// Send the AJAX request
xhr.send();


        totalClubFund.addClass('d-none'); // Add d-none to hide totalClubFund
        totalFund.css('display', 'none'); // Hide totalClubFund
        notClubFund.removeClass('d-none'); // Remove d-none to show notClubFund
    }
}

      // FUNCTION TO SHOW THE MODAL FOR CATEGORY FUND  
            function toggleCategoryFund() {
             
            if (fundCategory.val() === 'Club Fund') {
            totalBudget.disabled = true;
            
            $('#edit-table').on('click', function() {
               amountModal.modal('show');
            });
            
            clubFundTbl.style.display = 'block';
            
            
            var applyButton = $('#categoryFundApplyButton'); // Get the apply button
            
            categoryFundModal.modal('show');
            categoryFundContainer.style.display = 'block';
            // financeThrough.style.display = 'block';
            
            // Disable already chosen options
            } else if (fundCategory.val() != ''){
               totalBudget.disabled = false;
            clubFundTbl.style.display = 'none';
            
            // financeThrough.style.display = 'none';
            }else{
               totalBudget.disabled = true;
               clubFundTbl.style.display = 'none';
            
            }
            
            
              closeButton.on('click', cancelButton.on('click', function() {
                // Event listener for when the modal is hidden
                categoryFundModal.on('hidden.bs.modal', function() {
                  // Reset the selected value to the default option ("<option value="" selected="">Select a Fund Category title...</option>")
                  // fundCategory.val('1');
                });
            
                // Perform any additional actions upon closing the modal without applying the selection
              }));
            }
            
                  document.getElementById('categoryFund').addEventListener('change', function() {
                      selectedOptions = Array.from(this.selectedOptions);
                      var selectedValues = [];
                      selectedOptions.forEach(function (option) {
                      selectedValues.push(option.value); // Store the value of the selected option
                    });
            
            
            
                 $('#financeThroughTitle').text(selectedValues.join(', '));
                                                  // Attach an event listener to the shown.bs.modal event of the amount modal
 // Assuming you have the function removeNonNumericCharacters defined


                      $('#amountModal').modal('show');
                      
                      
            
                  });
            
                  function closeAmount() {
                    $('#amountModal').modal('hide');
                
                categoryFundModal.modal('show');
                  }
                  function cancelSubmit(){
                     $('#confirmModal').modal('hide');
                
                  }
      // save amounts into dynamic and the table ouside  
      function saveAmount() {
  success = false;

  var amountInput = document.getElementById('amountInput');
  var selectedFinanceThrough = document.getElementById('financeThroughTitle').textContent;

  var amount = removeNonNumericCharacters(amountInput.value);
  var formattedAmount = amountInput.value;

  amountInput.value = '';

  if (
    isNaN(amount) ||
    amount <= 0 ||
    parseFloat(amount) > parseFloat(removeNonNumericCharacters(currentFundModal.textContent))
  ) {
    setBudgetError('Please enter a valid amount. The amount must not be zero or exceed the current total fund');
    return;
  } else {
    var tableId = 'selectedOptionsTable2';
    var tableBody = document.getElementById(tableId).getElementsByTagName('tbody')[0];
    var dynamicTableBody = document.getElementById('dynamicTable').getElementsByTagName('tbody')[0];

    // Check if selectedOptions is empty, and if so, populate it from dynamicTable
    if (selectedOptions.length === 0) {
      var selectedFinanceThrough = document.getElementById('financeThroughTitle').textContent;
      selectedOptions.push({ text: selectedFinanceThrough });
    }
   
    selectedOptions.forEach(function (option) {
      var optionText = option.text;
      var isOptionSelected = Array.from(tableBody.getElementsByTagName('td')).some(function (cell) {
        return cell.textContent === optionText;
      });

      if (isOptionSelected) { 
        // Set the error message using the setBudgetError function
        showErrorModal("The selected option already exists in the table.");
        return; // Exit the loop or take appropriate action
      } else {
        // Rest of your code to add the row when there's no error
        var row = tableBody.insertRow();
        var fundCell = row.insertCell(0);
        var amountCell = row.insertCell(1);
        var actionCell = row.insertCell(2);
        amountCell.classList.add('amount-cell');
        fundCell.textContent = optionText;
        amountCell.innerHTML = '<span class="editable" onclick="editAmount(this)">' + formattedAmount + '</span>';

        // Add buttons for each row in the table ('selectedOptionsTable2')
        var editButton = document.createElement('button');
        editButton.classList.add('btn', 'btn-primary', 'btn-sm', 'mr-2');
        editButton.textContent = 'Edit';
        editButton.addEventListener('click', function () {
          editAmount(amountCell);
        });

        var deleteButton = document.createElement('button');
        deleteButton.classList.add('btn', 'btn-danger', 'btn-sm');
        deleteButton.textContent = 'Delete';
        deleteButton.addEventListener('click', function () {
          deleteRow(row);
          // deleteAllRowsFromExpense();
        });

        actionCell.appendChild(editButton);
        actionCell.appendChild(deleteButton);

        // Add the row to the new table 'dynamicTable'
        var newRow = dynamicTableBody.insertRow();
        newRow.insertCell(0).textContent = optionText;
        newRow.insertCell(1).textContent = formattedAmount;
      }
    });

    // After updating both tables, recalculate the total for both tables
    calculateTotalDynamic();
    calculateTotal(tableId);
    calculateTotal('dynamicTable');
    $('#amountModal').modal('hide');
  }
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
            
            function calculateTotal(tableId) {
            var tableBody = document.getElementById(tableId).getElementsByTagName('tbody')[0];
            var totalCell = document.getElementById('budget');
            
            
      
      // Update the value of the input field with the formatted value 
            var totalAmount = 0;
            
            for (var i = 0; i < tableBody.rows.length; i++) {
               var amountCell = tableBody.rows[i].cells[1];
               var amountCellValue = amountCell.querySelector('.editable').textContent; // Extract the inner text without HTML tags
               var amount = parseFloat(removeNonNumericCharacters(amountCellValue));
            
               if (!isNaN(amount)) {
                  totalAmount += amount;
               }
            }
            
            var totalAmountFormatted = formatAmountWithCommas(totalAmount);
            totalCell.textContent = totalAmountFormatted;
            
            document.getElementById('totalBudgetInput').value = totalAmountFormatted;
            document.getElementById('originalTotalBudget').value = removeNonNumericCharacters(totalAmountFormatted);
            

            if (totalAmount === 0) {
               totalCell.style.display = 'none';
            } else {
               totalCell.style.display = 'inline'; // Show the span when there is a total
            }

   
        
            }
            
      // edit dynamic tbale amounts
      function editAmount(cell) {

        // deleteAllRowsFromExpense();
  const fundCell = cell.parentNode.querySelector('td:first-child');
  const fund = fundCell.textContent.trim(); 

  const editableSpan = cell.querySelector('.editable');
  const currentAmount = parseFloat(removeNonNumericCharacters(editableSpan.textContent));

  const editInput = document.createElement('input');
  editInput.type = 'number';
  editInput.step = '0.01';
  editInput.value = currentAmount;
  editInput.style.width = '80px';
 




  function updateValueAndRestoreSpan(newAmount,fund,financeThroughAndBalance) {
   
    const rows = document.querySelectorAll('#expenseTableBody tr');


    rows.forEach((row) => {
    const balanceCell = row.querySelector('.text-right.expenseAmount');
    var financeThroughTitle = row.querySelector('select.finance-through');
    const financeTitle = financeThroughTitle.value;
    const expense = parseFloat(removeNonNumericCharacters(balanceCell.textContent));

    if (financeTitle === fund) {
      // Get the value of the fourth column (Balance)
      const balanceElement = row.querySelector('.text-right.expenseThrough');
      const recentBalance = parseFloat(removeNonNumericCharacters(balanceElement.textContent));
      const balanceText = newAmount - expense;
      
      // Update the value of the fourth column (Balance)
      balanceElement.textContent = formatAmountWithCommas(balanceText); // Assuming you want to format the balance as currency
    }
});



    if (!isNaN(newAmount)) {
         
      editableSpan.textContent = newAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
      const dynamicTable = document.getElementById('dynamicTable');
      const dynamicRow = dynamicTable.querySelector('tbody').children[cell.parentNode.rowIndex - 1];
      dynamicRow.cells[1].textContent = newAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
      const category = dynamicRow.cells[0].textContent.trim();

      const categoryIndex = optionsData.findIndex(item => item.category === category);

if (categoryIndex !== -1) {
  // Update the amount in optionsData based on the matching category
  optionsData[categoryIndex].amount = newAmount;
}

      
      const existingIndex = selectedData.findIndex(item => item.category === category);
      if (existingIndex !== -1) {
        selectedData[existingIndex].amount = newAmount;
      } else {
        selectedData.push({ category: category, amount: newAmount });
      }
    }
    cell.replaceChild(editableSpan, editInput);
    showButtons(cell.parentNode);
    calculateTotalDynamic();
    calculateTotal('selectedOptionsTable2');
    calculateTotal('dynamicTable');
    updateHiddenInputs();

 
  }


  editInput.addEventListener('keydown', function (event) {
    if (event.key === 'Enter') {
   
      const newAmount = parseFloat(editInput.value);
  const financeThroughAndBalance = [];

// Get all the rows in the table body
const rows = document.querySelectorAll('#expenseTableBody tr');

// Iterate through the rows
rows.forEach((row) => {
  // Find the financeThrough select element within the row
  const financeThroughSelect = row.querySelector('select.finance-through');
 
  // Find the balance cell within the row
  const balanceCell = row.querySelector('.text-right.expenseAmount');
  
  if (financeThroughSelect && balanceCell) {
    const financeThrough = financeThroughSelect.value;
    const expense = balanceCell.textContent.trim();

    financeThroughAndBalance.push({ financeThrough, expense });
  }
});




financeThroughAndBalance.forEach((item) => {
  const values = Object.values(item);

  if (values.includes(fund)) {
    foundFundValue = item[fund];
    return;
  }
});

 foundFundValue = financeThroughAndBalance.find((item) => item.financeThrough === fund)?.expense;
  expenseAmounts = parseFloat(removeNonNumericCharacters(foundFundValue));

 if (newAmount <= expenseAmounts ) {
  
  alert("set budget should not be less than the exisiting expense");
   editInput.value = currentAmount; // Assuming you have stored the existing value somewhere
      // Set focus back to the edit input
      editInput.focus();
    return;
} else {

  updateValueAndRestoreSpan(newAmount,fund,financeThroughAndBalance);
  
 
}
  
  
    }
  });

  cell.replaceChild(editInput, editableSpan);
  hideButtons(cell.parentNode);
  editInput.focus();


}

function hideButtons(row) {
  row.querySelectorAll('button').forEach(button => button.style.display = 'none');
}

function showButtons(row) {
  row.querySelectorAll('button').forEach(button => button.style.display = 'inline-block');
}

function deleteRow(row) {
  
  var firstCell = row.cells[0];
  var tableBody = document.getElementById('expenseTableBody');
  var rows = tableBody.getElementsByTagName('tr');

  for (var i = 0; i < rows.length; i++) {
    var currentRow = rows[i];

    // Get the third column (FinanceThrough)
    var financeThroughCell = currentRow.cells[2]; // Assuming FinanceThrough is the third column (index 2)

    // Find the select element within the FinanceThrough cell
    var selectElement = financeThroughCell.querySelector('select.finance-through');

    if (selectElement) {
      // Get the selected option value
      var selectedOptionValue = selectElement.value;

      // Get the value from the first cell in the current row
      var firstCellValue = firstCell.textContent.trim();

      // Check if firstCellValue is equal to the selected option value
      if (firstCellValue === selectedOptionValue) {
        // The value in the first cell exists as an option in the FinanceThrough select element
        showErrorModal("Spent FinanceThrough exists in the expense table. Please delete it from the expense table first.");
        return; // Exit the function and do not delete the row
      }
    }
  }

  // If no error occurred, proceed with row deletion
  var tableId = row.closest('table').id;
  var dynamicTable = document.getElementById('dynamicTable');
  var dynamicRow = dynamicTable.getElementsByTagName('tbody')[0].children[row.rowIndex - 1];
  
  if (tableId === 'selectedOptionsTable2') {
    var rowIndex = row.rowIndex - 1;
    optionsData.splice(rowIndex, 1);
  }


  

  row.parentNode.removeChild(row); // Remove the row from the selectedOptionsTable2
  dynamicRow.parentNode.removeChild(dynamicRow); // Remove the corresponding row from dynamicTable

  calculateTotalDynamic();
  calculateTotal(tableId);
  calculateTotal('dynamicTable');
  console.log(optionsData);
}

      </script>

   <script>
//----------------------------------------FUNCTION TO ADD EXPENSE TO THE DYNAMIC TABLE FOR EXPENSES----------------------------------------
      function addExpenseItem() {
      var expenseTitle = document.getElementById("accountTitle").value;
      var expenseAmountInput = document.querySelector('input[name="expense_amount[]"]');
      var expenseAmount = parseFloat(removeNonNumericCharacters(expenseAmountInput.value)); //input by user each expense
      var totalBudgetInput = document.getElementById('totalBudgetInput');
      var totalAmountFormated = removeNonNumericCharacters(totalBudgetInput.value);// sset budget this
      var currentTotal = calculateTotalAmount();
      var updatedTotal = currentTotal + expenseAmount;
      

               if (expenseAmount <=0){
                  showErrorModal("Expense amount cannot be less than equal to 0!");
               return;
               }
               if (updatedTotal > totalAmountFormated) {
               // Show an error message or take any other action here
               showErrorModal("Expense amount cannot exceed the total budget!");
               return;
               }
               else if(expenseAmount >totalAmountFormated ) {
               
               // Show an error message or take any other action here
               showErrorModal("Expense amount cannot exceed the total budget!");
             
               
               }else{
                  if (expenseTitle && !isNaN(expenseAmount)) {
      var expenseTableBody = document.getElementById("expenseTableBody");
      var expenseTableContainer = document.getElementById("expenseTableContainer");
      expenseTableContainer.style.display = "block";
      
      // Check if the selected option already exists in the table
      if (isDuplicateExpense(expenseTitle)) {
      // Show an error message or take any other action here
      showErrorModal("Expense item already exists in the table!");
  return;
      } 
      }   

      var dynamicTableBody = document.getElementById('dynamicTable').getElementsByTagName('tbody')[0];


for (var j = 0; j < dynamicTableBody.rows.length; j++) {
    var row = dynamicTableBody.rows[j];
    var category = row.cells[0].textContent.trim();
    var amountCell = row.cells[1];
    var amountCellValue = amountCell.textContent;
    var amount = parseFloat(removeNonNumericCharacters(amountCellValue));

    var existingIndex = optionsData.findIndex(item => item.category === category);
    if (existingIndex !== -1) {
        optionsData[existingIndex].amount = amount; // Update amount if category exists
    } else {
        optionsData.push({
            category: category,
            amount: amount,
        });
    }
}

var newRow = document.createElement("tr");

// Create a select element for the dropdown
var dropdownSelect = document.createElement("select");

dropdownSelect.className = "dropdown finance-through btn btn-primary ";

function balanceFinance() {
  const expenseTableContainer = document.getElementById("expenseTableContainer");
  
  expenseTableContainer.addEventListener("change", function(event) {
    const target = event.target;
  
    if (target.tagName === "SELECT" && target.className.includes("finance-through")) {
      const selectedOption = target.value;
      const allRows = document.querySelectorAll("tr");
  
      // Calculate the total amount for the selected option
      const totalAmountForSelectedOption = [...allRows]
        .filter((row) => {
          const select = row.querySelector("select");
          return select && select.value === selectedOption;
        })
        .reduce((total, row) => {
          const amountCell = row.cells[1];
          const amountCellValue = amountCell.textContent;
          const amountInSecondColumn = parseFloat(removeNonNumericCharacters(amountCellValue));
          return total + amountInSecondColumn;
        }, 0);
  
      const selectedAmount = optionsData.find((item) => item.category === selectedOption).amount;
  
      // Check if the selected amount is negative
      if (selectedAmount < totalAmountForSelectedOption) {
        showErrorModal("Cannot have a negative balance.");
        target.value = ''; // Clear the selected value
        return;
      }
  
      // Update the balance element for the specific row
      const balanceElement = target.closest("tr").querySelector('.input-column');
      balanceElement.textContent = formatAmountWithCommas(selectedAmount - totalAmountForSelectedOption);
  
      // Update the selectedData array based on the changes
      const financeThroughTitle = target.closest("tr").querySelector('.title-cell').textContent.trim();
      const selectedDataItem = selectedData.find((item) => item.category === financeThroughTitle);
      if (selectedDataItem) {
        selectedDataItem.amount = selectedAmount - totalAmountForSelectedOption;
      }
    }
  });
}

balanceFinance();




// Add a default option as the first option
var defaultOption = document.createElement("option");
defaultOption.value = ""; // Set an appropriate value if needed
defaultOption.textContent = "Choose Finance Through..."; // Display text for the default option
dropdownSelect.appendChild(defaultOption);

function populateDropdownOptions(selectElement, optionsData) {
    selectElement.innerHTML = ""; // Clear existing options
    selectElement.appendChild(defaultOption.cloneNode(true));
    for (var i = 0; i < optionsData.length; i++) {
        var option = document.createElement("option");
        option.value = optionsData[i].category; // Use category as value
        option.textContent = optionsData[i].category; // Display both category and amount
        selectElement.appendChild(option);
    }
}

populateDropdownOptions(dropdownSelect, optionsData);

var totalBudgetInput = document.getElementById('totalBudgetInput').value;
var fundCategoryElement = document.getElementById('fundCategory');
// Get the selected value from the 'fundCategory' element
var selectedFundCategory = fundCategoryElement.value;
var balanceNotClubFund = parseFloat(removeNonNumericCharacters(totalBudgetInput))-expenseAmount;
// Create other cells as before
// Assuming you have defined the necessary variables like expenseTitle, expenseAmount, selectedFundCategory, dropdownSelect, totalBudgetInput, newRow
// ... Your previous code ...

newRow.innerHTML = `
  <td class="title-cell style="white-space: nowrap;" id="expenseTitle" >${expenseTitle}</td>
  <td class="text-right expenseAmount id="expenseAmount" ">${formatAmountWithCommas(expenseAmount)}</td>
  <td class="text-center" id="fThrough">
    ${selectedFundCategory === 'Club Fund' ? dropdownSelect.outerHTML : 'Annual Fund'}
  </td>
  ${
    selectedFundCategory === 'Club Fund'
      ? `<td class="text-right expenseThrough"><span class="input-column" id="inputColumn" data-total-budget="${totalBudgetInput}"></span></td>`
      : `<td class="text-center id="balance" ">${formatAmountWithCommas(balanceNotClubFund)}</td>`
  }   
  <td class="text-center">
    <button type="button" class="expenses-edit btn btn-sm btn-primary btn-edit">Edit</button>
    <button type="button" class="expenses-delete btn btn-sm btn-danger btn-delete">Delete</button>
  </td>
`;










// Append the new row to the table body
dynamicTableBody.appendChild(newRow);

expenseTableBody.appendChild(newRow);

updateExpenseDetails();
calculateTotalAmount();
attachEditDeleteHandlers(newRow);
               }
      }
//----------------------------------------END FUNCTION TO ADD EXPENSE TO THE DYNAMIC TABLE FOR EXPENSES----------------------------------------
      
      // Function to check if the selected option already exists in the table
      function isDuplicateExpense(expenseTitle) {
      var expenseTableRows = document.querySelectorAll("#expenseTableBody tr");
      for (var i = 0; i < expenseTableRows.length; i++) {
      var titleCell = expenseTableRows[i].querySelector(".title-cell");
      if (titleCell.textContent === expenseTitle) {
      return true;
      }
      }
      return false;
      }

//----------------------------------------FUNCTION TO CALCULATE THE TOTAL AMOUNT IN EXPENSE---------------------------------------

 
      //----------------------------------------END FUNCTION TO CALCULATE THE TOTAL AMOUNT IN EXPENSE---------------------------------------

      //----------------------------------------attach buttondS FOR DYNAMIC TABLE---------------------------------------

      function attachEditDeleteHandlers(row) {
  const editButton = row.querySelector(".btn-edit");
  const deleteButton = row.querySelector(".btn-delete");
  const amountCell = row.querySelector("td:nth-child(2)");
  
  editButton.addEventListener("click", () => editExpenseItem(row));
  deleteButton.addEventListener("click", () => deleteExpenseItem(row));
  amountCell.addEventListener("click", () => editExpenseItem(row));
}

function editExpenseItem(row) {
  

  if (row.classList.contains("editing")) return;

  const amountCell = row.querySelector("td:nth-child(2)");

  // Store the current amount before making any changes
  const currentAmount = parseFloat(removeNonNumericCharacters(amountCell.textContent));
  const totalBudgetInput = document.getElementById('totalBudgetInput');

  row.classList.add("editing");

  const originalContent = parseFloat(removeNonNumericCharacters(amountCell.textContent));
  
  const amountInput = document.createElement("input");
  amountInput.type = "number";
  amountInput.step = "0.01";
  amountInput.value = currentAmount; // Set the input value to the current amount
  amountInput.autofocus = true;

  const editButton = row.querySelector(".btn-edit");
  const deleteButton = row.querySelector(".btn-delete");
  editButton.style.display = "none";
  deleteButton.style.display = "inline-block";
  amountCell.textContent = "";
  amountCell.appendChild(amountInput);

  amountInput.addEventListener("click", (event) => event.stopPropagation());

  function handleEnterKey(event) {
    if (event.key === "Enter" && event.target === amountInput) {
      
      event.preventDefault();
      const updatedAmount = parseFloat(amountInput.value);
      editButton.style.display = "inline-block";

      const totalBudgetValue = parseFloat(removeNonNumericCharacters(totalBudgetInput.value));
      const expenseAmountElements = document.querySelectorAll('td.text-right.expenseAmount');

      let totalAmount = 0;
      expenseAmountElements.forEach((element) => {
        const expenseAmount = parseFloat(removeNonNumericCharacters(element.textContent));
        totalAmount += isNaN(expenseAmount) ? 0 : expenseAmount;
      });

      const totalAmountElement = document.getElementById("totalAmount");
      totalAmountElement.textContent = formatAmountWithCommas(totalAmount);

      const totalExpenseAmount = parseFloat(removeNonNumericCharacters(totalAmountElement.textContent));
      const newTotal = totalExpenseAmount + updatedAmount;

      if (newTotal > totalBudgetValue) {
        showErrorModal("Expense amount cannot exceed the total");
        amountInput.value = originalContent; // Restore the original value
      } else if (!isNaN(updatedAmount) && updatedAmount <= totalBudgetValue) {
        amountCell.textContent = formatAmountWithCommas(updatedAmount);
        calculateTotalAmount();
      } else {
        showErrorModal("Expense amount cannot exceed the total budget or be empty!");
        amountInput.value = currentAmount; // Restore the current amount
      }

      row.classList.remove("editing");
    }
  }

  console.log(selectedData);

  document.addEventListener("keydown", handleEnterKey);
  const inputElement = row.querySelector('.input-column');
  const dropdownElement = row.querySelector('.dropdown.finance-through.btn.btn-primary');

  inputElement.textContent = '';
  dropdownElement.selectedIndex = 0;
}


//----------------------------------------END FUNCTION TO UPDATE OR EDIT THE AMOUNT IN EXPENSE---------------------------------------

      
      
      //---------------------------------------- DELETE EDIT THE AMOUNT IN EXPENSE---------------------------------------
      function deleteExpenseItem(row) {
         
         updateHiddenInputs();
      
         var expenseTitle = row.cells[0].textContent.trim();
    var amountCell = row.cells[1];
    var amountCellValue = amountCell.textContent;
    var amount = parseFloat(removeNonNumericCharacters(amountCellValue));

    // Find the index of the entry to delete in the selectedExpenseData array
    var indexToDelete = selectedExpenseData.findIndex(item => item.expenseTitle === expenseTitle && item.amount === amount);

    if (indexToDelete !== -1) {
        selectedExpenseData.splice(indexToDelete, 1); // Remove the entry from the array
    }

    row.remove();


      
      updateExpenseDetails();
      calculateTotalAmount();
      }
      
      //----------------------------------------END DELETE TO UPDATE OR EDIT THE AMOUNT IN EXPENSE---------------------------------------


      function updateExpenseDetails() {
      var expenseTableRows = document.querySelectorAll("#expenseTableBody tr");
      var noOfExpenses = expenseTableRows.length;
      
      }
      // Event listener for adding expense items
      document.getElementById("expenseAmountInput").addEventListener("keydown", function(event) {
    if (event.keyCode === 13) { // Check if the Enter key is pressed (key code 13)
        event.preventDefault(); // Prevent the default form submission behavior
        addExpenseItem();
    }

      
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
   function calculateTotalAmount() {
    var expenseAmountElements = document.querySelectorAll('td.text-right.expenseAmount');
    var totalAmount = [...expenseAmountElements]
        .map(element => parseFloat(removeNonNumericCharacters(element.textContent)) || 0)
        .reduce((sum, amount) => sum + amount, 0);

    var totalAmountElement = document.getElementById("totalAmount");
    totalAmountElement.textContent = formatAmountWithCommas(totalAmount);

    var expenseTableContainer = document.getElementById("expenseTableContainer");

    expenseTableContainer.style.display = totalAmount === 0 ? "none" : "block";

    return totalAmount;
}
   </script>

   

 <script src="assets/js/general/sidebar.js"></script>
  
   <script>

      function formatNumberWithCommas(number) {
      return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
      } 
   
   </script>
   
</html>