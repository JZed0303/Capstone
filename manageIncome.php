<?php
   session_start();
   
   if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Redirect the user to the login page
    header('Location: index.php');
    exit();
   }
   
      require 'includes/dbh.inc.php';
      include 'sqlGetData/formatValuePeso.php';
   
   $email = $_SESSION['email'];
   $role =  $_SESSION['role'];
   $name = $_SESSION['name'];
   // Output the value of $role in a script tag
   echo "<script>var role = '" . $role . "';</script>";
   echo "<script>var name = '" . $name . "';</script>";
   
   $sql = "SELECT 
    ci.id,
    ci.date,
    ci.fundAccTitle,
    ci.account_number,
    coa.child_account_name,
    ci.amount,
    ci.type,
    ci.remarks,
    ci.receipt,
    ci.edit_time
   FROM 
    cashinflow_tbl ci
   LEFT JOIN 
    chart_of_accounts coa ON ci.account_number = coa.child_account_number WHERE ci.status=0;
    
   ";
   $stmt = $conn->query($sql);
   $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
   
   
   $sql = "SELECT MAX(id) AS currentTransactionID FROM cashinflow_tbl";
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
      <title>Manage Income </title>
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
      
      <!-- DATA TABLE JS -->
      <script src="assets/datatable/js/jquery-3.6.0.min.js"></script>
      <!-- <script src="assets/datatable/css/bootstrap.bundle.min.js"></script> -->
      <script src="assets/datatable/js/datatables.min.js"></script>   
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
      <script src="assets/js/script.js"></script>
      <!-- fixed header -->
      <link href="css/bootstrap.min.css" rel="stylesheet">
      <link href="css/style.css" rel="stylesheet">

      <!-- general -->
   </head>
   <!-- START BODY -->
   <style>
      #changeTargetButton:hover{
      cursor: pointer;
      }  .arrow {
      opacity:0;
      position: absolute;
      bottom: 42px;
      left: 57%;
      margin-left: -7px;
      width: 0;
      height: 0;
      border-left: 10px solid transparent;
      border-right: 10px solid transparent;
      border-top: 10px solid rgb(14, 103, 224);
      }td{
      border:none!important;
      border-bottom:0.1px solid rgb(171, 181, 243)!important;
      }div.dataTables_wrapper {
      bottom: 28px;
      position: relative;
      }div#dataTablesIncome_filter {
      position: relative;
      display: flex;
      justify-content: flex-end;
      color: white;
      top: 5px;
      }.buttonsContainer {
  position: relative;
  right: 0; /* Initial position, no offset */
}.no-select {
    user-select: none;
    -moz-user-select: none;
    -webkit-user-select: none;
    -ms-user-select: none;
}
   </style>
     <style id="custom-styles"></style>
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
            <div class="container-fluid pt-4 px-md-3 px-lg-4 px-3 ">
               <div class="filter">
                  <div class=" row g-3 mx-md-4 mx-lg-4   mx-1 p-md-3 p-lg-3 p-2 ">
                     <!-- filter row -->
                     <div class="ms-3 ms-lg-0 ms-md-0 col-sm-12 col-md-6 col-xl-4 d-flex align-items-center ">
                        <span><strong style="position:absolute; top:-22px;"><i class="fa-solid fa-filter me-1"></i>Filter</strong></span>
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
                     <div class="col-sm-12 col-md-6 col-xl-4 d-flex justify-content-center">
                        <div class="categ col-lg-12 col-sm-10 d-flex flex-column mx-sm-3">
                           <span><strong class="">Category</strong></span>
                           <div class="form-group row">
                              <select class="category-box col-sm-12 col-md-10 form-control form-control-sm">
                                 <option selected >Select Category</option>
                                 <?php require 'sqlGetData/getRevenueCategory.php';?>
                              </select>
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-12 col-xl-4">
                        <span  class=""><strong class="">Search</strong></span>
                        <div class="form-group row mx-0 ">
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
            <div class="container-fluid wrapper">
               <div class="row g-2 mx-1 mb-3">
                  <div class="col-12    rounded-3 px-md-2">
                     <div class="dataTable_wrapper income col-12 pt-3 px-0 pb-0">
                        <div class="bg-white rounded h-100  mb-0 pb-0">
                           <div class="table-responsive">
                              <table id="dataTablesIncome" class="display nowrap table table-striped hover bg-white dataTable">
                                 <thead>
                                    <tr>
                                       <th data-cell = "id" >NO.</th>
                                       <th data-cell = "date" id="center">DATE</th>
                                       <th data-cell = "fund" >FUND</th>
                                       <th data-cell = "category"  style="width:1%!important;"> <span></span> ACCOUNT NO.</th>
                                       <span class="fa fa-" id="changeTargetButton" style="position: relative;width: 9px;z-index: 99;top: 98px;left: 31%;width: 10%;">
                                          <div>
                                             <div class="message-box no-select">
                                                <div class="arrow .no-select"></div>
                                                <div class="clickme no-select " style="opacity: 0;background: rgb(14 103 224);white-space: nowrap;position: relative;left: -43px;width: 198px;color: rgb(243, 243, 243);padding: 9px 13px 9px 2px;border-radius: 5px;font-weight: 800;font-size: 14px;text-align: center;top: -71px;font-family: Open sans-serif;">
                                                   (Double Click) &nbsp;Account name
                                                </div>
                                             </div>
                                          </div>
                           </div>
                           </span>
                           <th data-cell = "category"><span style="margin-left:10px;"></span> CATEGORY</th>
                           <th data-cell = "amount">AMOUNT</th>
                           <th data-cell = "type" >TYPE</th>
                           <th data-cell = "remarks" >REMARKS </th>
                           <?php 
                              $cssClass = ($role === "guest") ? "d-none" : "";
                              
                              // Generate the <th> element with the class attribute
                              echo '<th class="' . $cssClass . '" data-cell="receipt">RECEIPT</th>';
                              
                              ?>
                           <?php 
                              $cssClass = ($role === "guest" || $role === "" ) ? "d-none" : "";
                              
                              // Generate the <th> element with the class attribute
                              echo '<th class="' . $cssClass . '" ">ACTION</th>';
                              
                              ?> 
                           <th>TimeStamp</th>
                           </tr>
                           <tbody>
                           <?php
                              require 'includes/dbh.inc.php';
                              $total = 0;
                              foreach ($result as $row) {
                                 $total += $row['amount'];
                                 $categoryAccount = $row['account_number'];    // Check if an image was uploaded without any errors
                                 
                              
                                 $query = "SELECT child_account_number, child_account_name FROM chart_of_accounts WHERE child_account_name = :categoryAccount";
                                 $stmt = $conn->prepare($query);
                                 $stmt->bindParam(':categoryAccount', $categoryAccount);
                                 $stmt->execute();
                                 $account = $stmt->fetch(PDO::FETCH_ASSOC);
                                 $accountNumber = $account ? $account['child_account_number'] : 'N/A';
                                 $accountName = $row ? $row['child_account_name'] : 'N/A';
                                 $isAnnualFund = $row['fundAccTitle'] === 'Annual Fund';
                                 $isClubFund = $row['fundAccTitle'] === 'Club Fund';
                                 $receiptPath = $row['receipt'];
                                 // Move this line inside the loop to get the correct receipt path for each row
                                 $filename = basename($receiptPath);
                              
                              ?>
                           <tr class="clickable-tr"  data-receipt="<?php echo $receiptPath; ?>">
                           <td style="white-space: nowrap;" class="clickable-td text-center align-middle" data-cell="Transaction ID">
                           <?php echo 'TXN-RI-' . str_pad($row['id'], 3, '0', STR_PAD_LEFT); ?>
                           </td>
                           <td data-cell ="date" class="hidden-td align-middle"><?php echo $row['date'] ? $row['date'] : 'N/A'; ?></td>
                           <td data-cell ="fundAccTitle"  class="hidden-td align-middle" ><?php echo $row['fundAccTitle'] ? $row['fundAccTitle'] : 'N/A'; ?></td>
                           <td data-cell ="category"  class="hidden-td align-middle"><?php echo $row['account_number'] ? $row['account_number'] : 'N/A'; ?></td>
                           <td data-cell ="accountNumber" class="hidden-td align-middle" >
                           <span class="account-details account-details-tooltip" style="" data-toggle="tooltip" data-placement="top" title="<?php echo $accountName; ?>">
                           </span>
                           <span class="align-middle"><?php echo !empty($accountName) ? $accountName : 'N/A'; ?>
                           </span>                                        
                           </td>
                           <td data-cell="amount" data-sort="<?php echo $row['amount']; ?>" class="hidden-td align-middle currency-td">
                           <span>₱&nbsp;&nbsp;</span><?php echo number_format($row['amount'], 2); ?>
                           </td>
                        </div>
                        </td>
                        <td data-cell ="type" class="hidden-td align-middle" ><?php echo $row['type'] ? $row['type'] : 'N/A'; ?></td>
                        <td data-cell ="remarks"  class="hidden-td align-middle"><?php echo $row['remarks'] ? $row['remarks'] : 'N/A'; ?></td>
                        <?php 
                           $cssClass = ($role === "guest" || $role === "" ) ? "d-none" : "";
                           
                           // Generate the <th> element with the class attribute
                           echo '<td class="' . $cssClass . ' hidden-td align-middle"  data-cell ="receipt"  </td>';
                           
                           ?>
                        <?php
                           if (!empty($receiptPath)) {
                              $uploadDirectory = "CRUD/create/uploadsCashin/"; // Relative path to the "uploads" directory
                              $imageUrl = $uploadDirectory . $filename;
                              echo"<div class='receipt-container d-flex justify-content-center align-items-center' >";
                              echo "<a href='$imageUrl' target='_blank'><i style='margin-top:5px; color: #0a346a;font-size:19px;' class='fas fa-file-image fa-1x'></i></a>";
                              $hideSpan = ($role !== 'admin' && $role !== 'treasurer');
                              echo $hideSpan ? '<span class="d-none"></span>' :
                                  "<span class='fa fa-trash ms-3 delete-icon' style='font-size:12px; color:#e93838;cursor:pointer;' data-filename='$filename' data-id='{$row['id']}' data-toggle='modal' data-target='#deleteConfirmationModal'></span>";                              echo "</div>";
                              } else {
                              echo "N/A";
                           }
                           ?>
                     </div>
                     <?php 
                        $cssClass = ($role === "guest" || $role === "auditor" ) ? "d-none" : "";
                           
                           // Generate the <th> element with the class attribute
                           echo '<td class="' . $cssClass . ' hidden-td align-middle"  p-0" >';
                        
                           ?>
<div class="d-flex menu-action d-none d-lg-none d-md-none">
  <a class="btn btn-sm  w-100 px-1 pt-1 m-0 mt-0 d-flex justify-content-center" data-toggle="modal" data-target="#detailsModal" onclick="showTransactionDetails(this);" data-id="<?php echo $row['id']; ?>" data-receipt="<?php echo $filename ?>">
    <i class="fa fa-eye"></i> <span>Details</span>
  </a>
  <a class="btn btn-sm w-100 px-1 pt-1 m-0 mt-0 edit-button" data-toggle="modal" data-target="#editIncomeModal" onclick="editTransaction(this)" data-id="<?php echo $row['id']; ?>" data-receipt="<?php echo $filename ?>" data-date="<?php echo $row['date']; ?>" data-fund="<?php echo $row['fundAccTitle']; ?>">
    <i class="fa fa-edit"></i> <span>Edit</span>
  </a>
</div>

<div class="d-flex justify-content-center align-items-center p-1  ">
  <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#detailsModal" onclick="showTransactionDetails(this);" data-id="<?php echo $row['id']; ?>" data-receipt="<?php echo $filename ?>">
    <i class="fa fa-eye"></i>
  </button>
  <button class="btn btn-sm btn-success ms-2" type="button" onclick="editTransaction(this)" data-id="<?php echo 'TXN-RI-' . str_pad($row['id'], 3, '0', STR_PAD_LEFT); ?>" data-receipt="<?php echo $filename ?>" data-date="<?php echo $row['date']; ?>" data-fund="<?php echo $row['fundAccTitle']; ?>" data-category="<?php echo $category ?>" data-type="<?php echo $row['type']; ?>" data-amount="<?php echo number_format($row['amount'], 2); ?>" data-remarks="<?php echo $row['remarks']; ?>">
    <i class="fa fa-edit"></i>
  </button>
  <button class="btn btn-sm btn-danger ms-2" type="button" data-bs-toggle="modal" data-bs-target="#archiveConfirmationModal" data-transac-id="<?php echo $row['id']; ?>" data-receipt="<?php echo $filename ?>">
    <i class="fa fa-trash"></i>
</button>
</div>

                     </td>
                     <td><?php echo $row['edit_time']; ?></td>
                     </tr>
                     <?php } ?>
                     </tbody>
                     <tfoot>
                     <tr>
                     <td class="bg-secondary text-white" colspan="5"></td>
                     <td class="d-flex float-right" style="text-align: center; white-space: nowrap;"><span class="font-weight:bold; color:white; font-size:10px;">Total Cash : &nbsp;</span><strong style="font-size:16.5px;font-family:sans-serif;" class="" id="totalAmount"></strong></td>
                     <td style="border:none;" colspan="4" class="bg-secondary";></td>
                     <td style="border:none;" ></td>
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
      <div class="modal fade p-4 p-md-0 p-lg-0" id="editIncomeModal" tabindex="-1" role="dialog" aria-labelledby="editIncomeModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content w-100 vh-100">
               <div class="modal-header d-flex justify-content-center">
                  <h5 class="modal-title" style="margin-left:auto;"  id="editIncomeModalLabel">Edit CashinFlow Transaction</h5>
                  <button type="button" class="close" id="closeEdit" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="row col-12">
                  <p class="transac fw-bolder text-end p-2  m-0  ">Transaction ID: <span id="currentTransactionId" class="d-inline-block"></span></p>
               </div>
               <div class="modal-body px-5">
                  <form action="CRUD/update/updateCashInFlow.php" method="POST" enctype="multipart/form-data" class="row col-12" id="updateForm">
                     <input type="hidden" id="role" name="role"value="<?php echo $role?>">
                     <input type="hidden" id="email" name="email" value="<?php echo $email?>">
                     <input type="hidden" id="currentSortColumn" name="currentSortColumn">
                     <input type="hidden" id="currentSortDirection" name="currentSortDirection">
                     <input type="hidden" id="currentPage" name="currentPage">
                     <input type="hidden" name="rowId" id="editRowId">
                     <input type="hidden" name="receiptPath" id="receiptPath" value="<?php echo $receiptPath;?>">
                     <div class="row mx-auto">
                        <div class="form-group col-md-12 ">
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
                        <input type="text" class="form-control" id="editAmount" oninput="formatAmount(this)"  name="amount" placeholder="Enter the amount...">
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
                        <button type="submit" id="updateTransac" onclick="updateTransaction()" name="update" class="btn btn-primary float-right">Update</button>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <!--edit modal end -->
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
                        <button type="submit" id="archiveCashinflow" name="archiveCashinflow" class="btn btn-danger" >Yes</button>
                     </form>

                  </div>
               </div>
            </div>
         </div>
      <!-- Details Modal Start -->
      <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered modal-lg p-3 p-lg-0 p-md-0" role="document">
            <div class="modal-content">
               <div class="modal-header d-flex justify-content-center">
                  <h5 class="modal-title" style="margin-left:auto; font-weight: bold;" id="editIncomeModalLabel">Details CashinFlow Transaction</h5>
                  <button type="button" class="close" id="closeDetail" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="row col-12">
                  <p class="transac fw-bolder text-end p-2  m-0  ">Transaction ID: <span id="currentTransactionIdDetails" class="d-inline-block"></span></p>
               </div>
               <div class="modal-body px-5 px-lg-5 px-md-5 ">
                  <form class="mt-4" action="#" method="POST" enctype="multipart/form-data">
                     <hr  class="underline position-absolute">
                     <div class="col-12 d-flex justify-content-center align-items-center">
                        <div class="form-group col-md-6 ">
                           <label for="detailDate"><strong>Date:</strong></label>
                           <input type="date" class="form-control" id="detailDate" name="date" placeholder="Enter the date..." disabled>
                        </div>
                        <div class="form-group col-md-6">
                           <label for="detailAccountTitle"><strong>Fund:</strong></label>
                           <input type="text" class="form-control" id="detailAccountTitle" name="accountTitle" disabled>
                        </div>
                     </div>
                     <hr>
                     <div class="row">
                        <div class="form-group col-md-6">
                           <label for="detailCategory"><strong>Category:</strong></label>
                           <div class="d-flex">
                              <input type="text" class="form-control col-3 text-center" id="detailCategoryNumber" name="category" disabled> <span class="mt-1">:</span>
                              <input type="text" class="form-control" id="detailCategory" name="category" disabled> 
                           </div>
                        </div>
                        <div class="form-group col-md-6">
                           <label for="detailAmount"><strong>Amount:</strong></label>
                           <input type="text" class="form-control" id="detailAmount"  name="amount" disabled>
                        </div>
                     </div>
                     <hr>
                     <div class="form-group">
                        <label for="detailType"><strong>Type:</strong></label>
                        <input type="text" class="form-control" id="detailType" name="type" disabled>
                     </div>
                     <hr >
                     <div class="form-group">
                        <label for="detailRemarks"><strong>Remarks:</strong></label>
                        <textarea class="form-control pt-4 pb-4" id="detailRemarks" name="remarks" placeholder="Enter the remarks..." disabled></textarea>
                     </div>
                     <div class="form-group">
                        <label for="detailReceipt"><strong>Receipt:</strong></label>
                        <img class="img-fluid shadow-lg" src="" id="detailReceiptImage" style="width: 50%; display: block; margin: 10px auto;">
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <!-- Details Modal End -->
      <!-- ADD MODAL -->
      <div class="modal fade" id="addIncomeModal" tabindex="-1" role="dialog" aria-labelledby="addIncomeModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered modal-lg p-3 p-lg-0 p-md-0" role="document">
            <div class="modal-content w-100">
               <div class="modal-header d-flex justify-content-center">
                  <h5 class="modal-title" style="margin-left:auto;" id="addIncomeModalLabel">CashinFlow Transaction</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="row col-12">
                  <p class="transac fw-bolder text-end p-2  m-0  ">Transaction ID:  <span class="d-inline-block"> <?php echo 'TXN-RI-' . str_pad($nextTransactionID, 3, '0', STR_PAD_LEFT); ?></span></p>
               </div>
               <div class="modal-body p-3">
                  <form action="CRUD/create/addCashInflow.php" method="POST"  enctype="multipart/form-data" class="row col-12">
                     <input type="hidden" id="rowId" name="rowId" value ="<?php echo $nextTransactionID;?>">
                     <input type="hidden" id="role" name="role" value ="<?php echo $role;?>">
                     <input type="hidden" id="email" name="email" value ="<?php echo $email; ?>">
                     <div class="row mx-auto">
                        <div class="form-group col-md-12">
                           <label for="date">Date:</label>
                           <input type="date" class="form-control" required id="date" name="date" placeholder="Enter the date...">
                        </div>
                     </div>
                     <div class="form-group col-md-6">
                        <label for="accountTitle">Fund:</label>
                        <select class="form-control" id="accountTitle" name="accountTitle">
                           <option value="" selected>Select Where Fund...</option>
                           <?php include 'sqlGetData/getCapitalCategory.php';?>
                        </select>
                     </div>
                     <div class="form-group col-md-6">
                        <label for="category">Category:</label>
                        <select class="form-control" id="category" name="category">
                           <option value="" selected >Select an Fund Category title...</option>
                           <?php require 'sqlGetData/getRevenueCategory.php';?>
                        </select>
                     </div>
                     <div class="form-group col-md-6">
                        <label for="amount">Amount:</label>
                        <input type="text" class="form-control"  oninput="formatAmount(this)" id="amount" name="amount" placeholder="Enter the amount...">
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
         <div class="modal-dialog modal-dialog-centered p-3 p-lg-0 pmd-0" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="deleteConfirmationModalLabel">Delete Receipt</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body p-4">
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
$(document).ready(function() {
    // Add a click event listener to both the close button and topClose element
    $("#closebtn, #topClose").click(function() {
        // Trigger the modal's close action
        $("#archiveConfirmationModal").modal("hide");
    });
    $("#closeEdit").click(function() {
        // Trigger the modal's close action
        $("#editIncomeModal").modal("hide");
    });
    $("#closeDetail").click(function() {
        // Trigger the modal's close action
        $("#detailsModal").modal("hide");
    });
});
</script>

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
       
          
      if (category === 'Membership Dues') {
        // Redirect to clubDues.php
        window.location.href = 'clubDues.php';
      }else{
      
          $('#editIncomeModal').modal('show');
          document.getElementById('editCategory').value =category ;
          document.getElementById('editDate').value = date;
          document.getElementById('editAccountTitle').value = fund;
          document.getElementById('editAmount').value = amount;
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
      
             receiptLabel.textContent = file.name;
      
             var reader = new FileReader();
      reader.onload = function(e) {
        receiptImageElement.src = e.target.result;
        receiptImageElement.style.display = "block"; // Show the image
      };
      reader.readAsDataURL(file);
      
      
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
       }
      
      
      
   </script>


   <script>
$(document).ready(function() {
var role= "<?php echo $role?>";
var customStyles = '';
    if(role === 'guest' || role === 'auditor'){
        // If the role is 'guest', update the CSS property for the specified selector
        $('table.dataTable tbody td','').css('padding', '10px');

   
        customStyles += '.clickme.open { left: -43px!important; top: -49px!important; }';
            customStyles += '.arrow.open { left: 51%!important; bottom: 39px!important; }';
            customStyles += '.message-box.open { position: relative; z-index: 999; bottom: 23px; left: 91px!important; }';
            customStyles += '.message-box{position:relative;left:143px!important; top: -20!important; }';
            customStyles += '.arrow { bottom: 40px!important; left: 46%!important; }';
            customStyles += '.clickme { left: -34px!important; top: -50px!important; }';
            $('#custom-styles').text(customStyles); // Apply styles to the style tag in the head
    } else{
      $('table.dataTable tbody td','').css('padding', '5px 10px');
      customStyles += '.clickme.open { left: -43px!important; top: -49px!important; }';
            customStyles += '.arrow.open { left: 51%!important; bottom: 39px!important; }';
            customStyles += '.message-box.open { position: relative; z-index: 999; bottom: -20px; left: 37px!important; }';
            customStyles += '.message-box{position:relative;left:3px!important; top: -20!important; }';
            customStyles += '.arrow { bottom: 40px!important; left: 46%!important; }';
            customStyles += '.clickme { left: -34px!important; top: -50px!important; }';
            $('#custom-styles').text(customStyles); // Apply styles to the style tag in the head
    }

});

$(document).ready(function() {
    $('.btn.btn-sm.btn-danger').on("click", function() {
        var transacId = $(this).data("transac-id");
        $('#transacIdArchive').val(transacId);
    });
});


      
      $(document).ready(function() {
       $("#print-button").click(function() {
           // Code to run when the button is clicked
           var tableData = $('#dataTablesIncome').DataTable().data().toArray();
      var jsonData = JSON.stringify(tableData);
      $.ajax({
         type: "POST",
         url: "printView.php",
         data: { data: jsonData }
      });
      
       });
      
      
      });
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
         function showSuccessModal(successMessage) {
         var successModal = new bootstrap.Modal(document.getElementById("successModal"));
         var successMessageElement = document.querySelector("#successModal .modal-body p");
         successMessageElement.textContent = successMessage;
         successModal.show();
         var closeButton = document.querySelector("#successModal .btn-secondary");
          closeButton.addEventListener("click", closeModal);
         }
   </script>
   <!-- GET AMOUNT IN INPUT -->
   <script>
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
          // Add a listener to the window resize event
          $(window).on('resize', function() {
              const windowWidth = $(window).width(); // Get the current window width
              const maxWidth = 550; // Your maximum width
      
              if (windowWidth <= maxWidth) {
                  $(".clickable-td").click(function() {
                      const clickedTd = $(this);
                      const allTableCells = clickedTd.closest('tr').find('td');
                  console.log(allTableCells.html());
                     
                      allTableCells.toggleClass('show-cell');
                      $('.menu-action').removeClass('d-none'); // Remove the 'd-none' class
                      allTableCells.closest('tr').find('td:last-child').css('display', '');
                      
                  });
      
                 
                  $("thead").click(function() {
                      const allTableCells = $("td"); // Get all <td> elements on the page
                      allTableCells.removeClass('show-cell');
                  });
              } else {
                  $(".clickable-td").off('click');
                  $("thead").off('click');
              }
          });
      
          // Call the resize event handler initially to set up the behavior
          $(window).trigger('resize');
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
   </script>
</html>