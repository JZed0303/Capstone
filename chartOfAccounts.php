<?php
   session_start();
   if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
      // Redirect the user to the login page
      header('Location: index.php');
      exit();
   }else{


      if (!isset($_SESSION['tableOpen'])) {
         $_SESSION['tableOpen'] = array();
     }
      require 'includes/dbh.inc.php';
   
      $email = $_SESSION['email'];
      $role = $_SESSION['role'];
      $id = $_SESSION['id'];
      
      // Output the value of $role in a script tag
      echo "<script>var role = '" . $role . "';</script>";
      
      // Retrieve data from the database using PDO
      $dataQuery = "SELECT * FROM parent_accounts_tbl";
      $dataStmt = $conn->query($dataQuery);
      $allData = $dataStmt->fetchAll(PDO::FETCH_ASSOC);
      
      // Retrieve the maximum account number from the database
      $maxAccountQuery = "SELECT MAX(account_number) AS max_account_number FROM parent_accounts_tbl";
      $maxAccountStmt = $conn->query($maxAccountQuery);
      $row = $maxAccountStmt->fetch(PDO::FETCH_ASSOC);
      $maxAccountNumber = $row['max_account_number'];
      
   $newAccountNumber = $maxAccountNumber + 1000;
    
      
   
      
   }
      
   
   
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>Chart Of Accounts</title>
      <meta content="width=device-width, initial-scale=1.0" name="viewport">
      <meta content="" name="keywords">
      <meta content="" name="description">
      <!-- css content -->
      <link rel="stylesheet" href="assets/fontawesome-free-6.3.0-web/css/all.css">
      <link rel="stylesheet" href="assets/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/css/budgetManagement.css">
      <!-- DATA TABLES  cs-->
      <link rel="stylesheet" href="assets/datatable/css/jquery.dataTables.min.css">
      <!-- DATA TABLE JS -->
      <script src="assets/datatable/js/jquery-3.6.0.min.js"></script>
      <script src="assets/js/bootstrap.min.js"></script>
      <!-- Favicon -->
      <!-- Google Web Fonts -->
      <link href="css/bootstrap.min.css" rel="stylesheet">
      <link href="css/style.css" rel="stylesheet">
      <!-- general -->
   </head>
   <style>
      .table>:not(caption)>*>*{
      border-left:0px;
      }
      .modal-content {
      border: none;
      border-radius: 8px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      background-color: var(--gray);
      }
      /* Modal Header Styling */
      .modal-header {
      border-bottom: none;
      background-color:var(--azure);
      color: var(--white);
      border-top-left-radius: 8px;
      border-bottom:var(--gold) 6px solid;
      border-top-right-radius: 8px;
      }
      /* Modal Body Styling */
      .modal-body {
      background-color:var(--white);
      }    @media (max-width: 550px) {
         body,table{
            font-size:1rem!important;
         }
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
   </style>
   <style>
      /* Adjust the font size and margin based on screen size */
      /* Default font size for the entire document */
      html {
      font-size: 16px;
      }
      /* Adjust font sizes for different elements */
      body {
      font-size: 1rem; /* 1rem is equivalent to 16px (default) */
      }
      h1 {
      font-size: 2rem; /* 2rem is equivalent to 32px */
      }
      h2 {
      font-size: 1.75rem; /* 1.75rem is equivalent to 28px */
      }
      h3 {
      font-size: 1.5rem; /* 1.5rem is equivalent to 24px */
      }
      /* Adjust button font size */
      .btn {
      font-size: 1rem;
      }
      /* Adjust navigation font size */
      .nav-link {
      font-size: 1rem;
      }
      /* Adjust form input and label font size */
      input, label {
      font-size: 1rem;
      }
      /* Media query for all screen sizes */
      @media (max-width: 1200px) {
      html {
      font-size: 14px;
      }
      } @media (max-width: 380px) {
      .table.roundedCorners {
      font-size: 14px;
      }.card-body{
      padding:0px!important;
      }table{
      }#tableContentExpenseAccount{
      border:0px!important;
      border:collapse;
      }
      }
      .table.tableExpenses td:first-child,.expense-accounts td:first-child {
      display: none;
      }td{
         border:none!important;
         border-bottom: 0.5px solid rgb(171, 181, 243)!important;
      }
   </style>
   <!-- START BODY -->
   <body>
      <div class="container-fluid position-relative d-flex p-0">
     
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
         <div class="row border border-0 ml-1 mx-3">
            <div class="dataTable_wrapper col-12 pt-3 px-0 pb-0">
               <div class="data_table w-100  bg-white mb-2  border-0">
                  <div class="col-12 w-100 px-sm-0 px-md-2 px-lg-2">
                     <div class="p-0">
                        <div class="shadow-lg">
                        </div>
                        <div class="card-body col-12  ">
                           <div class="mt-4">
                              <?php
                                 if (  $role == "guess") {
                                    # code...
                                 } else{
                                    echo'
                                    <button type="button" class="btn btn-primary float-right mb-2 mx-4" data-toggle="modal" data-target="#addHeadModal">
                                 
                                    <i class="fas fa-plus me-2"></i> Add New  
                                    </button';   
                                 }
                                 
                                 ?> 
                           </div>
                           <div class=" px-4 mt-2">
                              <table class="table table-hover table-striped bg-white col-12 mt-3" style="width: 100%; margin-top:10px!important;" ui-jq="dataTable">
                                 <thead>
                                    <tr>
                                       <th>Code Name</th>
                                       <th>Account Name</th>
                                       <th>Action</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php
                                       $rowCounter = 0;
                                       if ($allData && count($allData) > 0) {
                                          foreach ($allData as $row) {
                                             $rowCounter++;
                                             $hideActions = $rowCounter <= 5? 'd-none' : ''; // Hide actions for the first rows
                                             $actionsContent = $hideActions ? 'Default' : ''; // Content for the actions column
                                             
                                               ?>
                                    <tr>
                                       <td><?php echo $row['account_number']; ?></td>
                                       <td><?php echo $row['account_title']; ?></td>
                                       <td>
                                          <!-- BUTTONS ACTIONS -->
                                          <div class="dropdown d-flex justify-content-center p-0">
                                             <?php if ($hideActions) { ?>
                                             <span class="default-action"><?php echo $actionsContent; ?></span>
                                             <?php } else { ?>
                                             <li class="list-inline-item">
                                                <button class="edit-btn btn btn-success btn-sm rounded-0" type="button" data-toggle="modal" data-placement="top" title="Edit" onclick="openEditModal('<?php echo $row['account_number']; ?>', '<?php echo $row['account_title']; ?>','<?php echo $row['id']; ?>')">
                                                <i class="fa fa-edit"></i>
                                                </button>
                                             </li>
                                             <li class="list-inline-item">
                                                <button class="deleteChartHeadBtn btn btn-danger btn-sm rounded-0" type="button" data-toggle="modal" data-placement="top" title="Delete"
                                                   data-account-number="<?php echo $row['account_number']; ?>"
                                                   data-account-title="<?php echo $row['account_title']; ?>"
                                                   data-account-id="<?php echo $row['id']; ?>">
                                                <i class="fa fa-trash"></i>
                                                </button>
                                             </li>
                                             <?php } ?>
                                          </div>
                                       </td>
                                    </tr>
                                    <?php
                                       }
                                       } else {
                                       ?>
                                    <tr class="text-center">
                                       <td colspan="3">
                                          <span>No data available.</span>
                                       </td>
                                    </tr>
                                    <?php
                                       }
                                       ?>
                                 </tbody>
                              </table>
                           </div>
                           <!-- first table head charts -->
                           <!-- Delete Modal -->
                           <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered">
                                 <div class="modal-content">
                                    <div class="modal-header">
                                       <h5 class="modal-title" id="deleteModalTitle">Delete Account</h5>
                                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                       <span aria-hidden="true">&times;</span>
                                       </button>
                                    </div>
                                    <div class="modal-body" id="deleteModalBody">
                                       <!-- Content will be populated dynamically via JavaScript -->
                                    </div>
                                    <div class="modal-footer">
                                       <button type="button" id="closeModalButton" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                       <form id="deleteForm" action="CRUD/delete/deleteChartHead.php" method="post"> 
                                       <input type="hidden" name="scroll_position" id="scroll_position" value = "" >
                                          <input type="hidden" name="accountTitle" id="accountTitle" value = "" >
                                          <input type="hidden" name="accountNumber" id="accountNumber" value = "" >
                                          <input type="hidden" name="delete_id" id="idAccount" value = "" >
                                          <button type="submit" name="deleteChartHead"   id="deleteAccount" class="btn btn-danger">Delete</button>
                                       </form>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <!-- edit chart heads -->
                           <!-- Edit Chart Heads Modal -->
                           <div class="modal fade" id="editHeadChartModal" tabindex="-1" role="dialog" aria-labelledby="editHeadChartModal" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered" role="document">
                                 <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                       <h5 class="modal-title" id="editHeadChartModal">Edit Account</h5>
                                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                       <span aria-hidden="true">&times;</span>
                                       </button>
                                    </div>
                                    <div class="modal-body">
                                       <!-- Display the ID value within the modal -->
                                       <span id="  modalId"></span>
                                       <!-- Add your form elements for editing the account here -->
                                       <!-- For example, you can use an HTML form -->
                                       <form action="CRUD/update/updateChartHead.php" method="GET">
                                          <div class="form-group">
                                             <input type="hidden" name="id" id="id" value="">
                                             <label for="accountNumber">Account Number:</label>
                                             <input type="hidden"  class="form-control" id="account_number_hidden" name="account_number_hidden">
                                             <input type="text" disabled class="form-control" id="account_number" name="account_number">
                                          </div>
                                          <div class="form-group">
                                             <label for="accountCode">Account Code:</label>
                                             <input type="text" class="form-control" id="account_title" name="account_title">
                                          </div>
                                          <div class="modal-footer">
                                             <button id="closeModalButtonEdit" type="button" class="btn btn-secondary">Close</button>
                                             <button type="submit" class="btn btn-primary">Save</button>
                                          </div>
                                          <!-- Add more form fields as needed -->
                                       </form>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="modal fade" id="editHeadChartModalExpense" tabindex="-1" role="dialog" aria-labelledby="editHeadChartModal" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered" role="document">
                                 <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                       <h5 class="modal-title" id="editHeadChartModal">Edit Account</h5>
                                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                       <span aria-hidden="true">&times;</span>
                                       </button>
                                    </div>
                                    <div class="modal-body">
                                       <!-- Display the ID value within the modal -->
                                       <span id="  modalId"></span>
                                       <!-- Add your form elements for editing the account here -->
                                       <!-- For example, you can use an HTML form -->
                                       <form action="CRUD/update/updateExpenseAccounts.php" method="GET">
                                          <div class="form-group">
                                             <input type="hidden" name="id" id="id" value="">
                                             <label for="accountNumber">Account Number:</label>
                                             <input type="hidden"  class="form-control" id="parent_number" name="parent_number">
                                             <input type="hidden"  class="form-control" id="account_number_hiddenExpense" name="account_number_hiddenExpense">
                                             <input type="text" disabled class="form-control" id="account_numberExpense" name="account_numberExpense">
                                          </div>
                                          <div class="form-group">
                                             <label for="accountCode">Account Code:</label>
                                             <input type="text" class="form-control" id="account_titleExpense" name="account_titleExpense">
                                          </div>
                                          <div class="modal-footer">
                                             <button id="closeModalButtonEditExpense" type="button" class="btn btn-secondary">Close</button>
                                             <button type="submit" class="btn btn-primary">Save</button>
                                          </div>
                                          <!-- Add more form fields as needed -->
                                       </form>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <!-- JavaScript code to open the edit modal and populate the form fields -->
                           <script>
                              function openEditModal(accountNumber, accountCode,id) {
                                 // Set the account number and account code in the form fields
                                 $('#id').val(id);
                                 $('#account_number').val(accountNumber);
                                 $('#account_title').val(accountCode);
                                 $('#account_number_hidden').val(accountNumber);
                                 // Open the modal
                                 $('#editHeadChartModal').modal('show');
                              }
                              function openEditModalExpense(parent_number,accountNumber, accountCode,id,) {
                              
                                 
                                 // Set the account number and account code in the form fields
                                 $('#idExpense').val(id);
                                 $('#parent_number').val(parent_number);
                                 $('#account_numberExpense').val(accountNumber);
                                 $('#account_titleExpense').val(accountCode);
                                 $('#account_number_hiddenExpense').val(accountNumber);
                                 // Open the modal
                                 $('#editHeadChartModalExpense').modal('show');
                              }
                           </script>       
                           <div class="container p-0 p-md-4 p-lg-4 ">
                              <?php 
                                 $sql = "SELECT * FROM parent_accounts_tbl";
                                 $stmt = $conn->prepare($sql);
                                 $stmt->execute();
                                 $allData = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                 
                                 foreach ($allData as $row) {
                                   ?>
                              <div class="row">
                                 <div class="col px-4 px-md-0 px-lg-0">
                                    <div class="table ">
                                       </button>
                                       <div class="d-flex justify-content-center">
                                          <table class="table tableExpenses table-stripped table-hover bg-white" style="width: 100%;">
                                             <thead style="border-radius: 10px;" class="dropdown-heading" id="head" data-toggle="collapse" data-target="#tableContent<?php echo $row['id']?>" aria-expanded="false">
                                                <tr style="">
                                                   <th colspan="3" style="border-radius: 10px; ">
                                                      <button id="addButton<?php echo $row['id']; ?>" class="addButton btn btn-primary btn-sm rounded-pill shadow-lg"  style="display:block;position: relative;top: 31px; left: 101%;" type="button" data-toggle="modal" data-target="#chartOfAccounts<?php echo $row['account_number']?>" data-row-id="<?php echo $row['id'] ?>" data-child-account-number="<?php echo $innerRow['child_account_number']?>" data-placement="top" title="Add">
                                                      <i class="fa fa-plus"></i>           
                                                      <button id="toggleButton" class="btn btn rounded-pill shadow-lg d-flex justify-content-between" style="width: 100%; border-radius: 10px;background: #1c649f;color:white">
                                                         <div class="text-center mx-auto">
                                                            <strong >  <?php echo $row['account_title']?> </strong>
                                                         </div>
                                                         <i id="arrowIcon" class="mt-1 me-2 fa fa-caret-down" aria-hidden="true"></i>
                                                      </button>
                                                   </th>
                                                </tr>
                                                <script></script>
                                             </thead>
                                             <tbody id="tableContent<?php echo $row['id']; ?>" class="tableAccount collapse bg-white <?php if (isset($_SESSION['tableOpen'][$row['id']])) echo 'show'; ?>">
                                                <?php
                                                   $innerSql = "SELECT  parent_account_number,child_account_number, child_account_name FROM chart_of_accounts WHERE parent_account_number = :accountNumber";
                                                   $innerStmt = $conn->prepare($innerSql);
                                                   $innerStmt->bindParam(':accountNumber', $row['account_number']);
                                                   $innerStmt->execute();
                                                   $innerResult = $innerStmt->fetchAll(PDO::FETCH_ASSOC);
                                                   
                                                   if (count($innerResult) === 0) {
                                                   echo '<tr><td colspan="3">No data</td></tr>';
                                                    } else {
                                                       foreach ($innerResult as $innerRow) {
                                                          ?>
                                                <tr class="chartAccountsList bg-white text-center">
                                                   <?php foreach($innerRow as $key => $value) { ?>
                                                   <td class="col-7"><?php echo $value;?></td>
                                                   <?php } ?>
                                                   <td class="d-flex flex-row col-12" style="border-right: 0;border-left: 0;">
                                                      <button class="mx-2 editChartOfAccount btn btn-success btn-sm rounded-0" type="button" data-toggle="modal" data-row-id="<?php echo $row['id']; ?>"  data-target="#editChartOfAccountModal<?php echo $innerRow['child_account_number']?>" title="Edit">
                                                      <i class="fa fa-edit"></i>
                                                      </button>
                                                      <button class="deleteChartAccountBtn btn btn-danger btn-sm rounded-0" type="button" data-toggle="modal" data-target="#deleteChartOfAccountModal<?php echo $innerRow['child_account_number']?>" data-placement="top" title="Delete">
                                                      <i class="fa fa-trash"></i>
                                                      </button>
                                                   </td>
                                                </tr>
                                                <!-- Modal -->
                                                <div class="modal fade" id="editChartOfAccountModal<?php echo $innerRow['child_account_number']?>" tabindex="-1" role="dialog" aria-labelledby="editChartOfAccountLabel" aria-hidden="true">
                                                   <div class="modal-dialog modal-dialog-centered" role="document">
                                                      <div class="modal-content">
                                                         <div class="modal-header">
                                                            <h5 class="modal-title" id="editChartOfAccountLabel">Edit Account - ID: <span id="edit <?php echo $row['id'];?>" ></span> <?php echo $row['account_number']?></h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                            </button>
                                                         </div>
                                                         <div class="modal-body">
                                                            <form method="GET" action="CRUD/update/updateChartOfAccount.php">
                                                            <input type="hidden" name="scroll_position" value="">
                                                               <input type="hidden" name="rowId" value="<?php echo $row['id'] ?>">
                                                               <input type="hidden" name="parent_account_number" value="<?php echo $innerRow['parent_account_number'] ?>">
                                                               <input type="hidden" name="id" value="<?php echo $innerRow['child_account_number'] ?>">
                                                               <div class="form-group">
                                                                  <label for="accountCode">Account Code</label> 
                                                                  <input type="text" class="form-control" id="child_account_number" name="child_account_number" value="<?php echo $innerRow['child_account_number'] ?>" readonly>
                                                               </div>
                                                               <div class="form-group">
                                                                  <label for="accountName">Account Name</label>
                                                                  <input class="form-control" id="child_account_code" name="child_account_code" value="<?php echo $innerRow['child_account_name'] ?>" placeholder="Enter Account Name" required>
                                                               </div>
                                                               <div class="modal-footer">
                                                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                  <button type="submit" class="btn btn-primary">Save changes</button>
                                                               </div>
                                                            </form>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                                <!-- Modal -->
                                                <!-- Modal -->
                                                <div class="modal fade" id="deleteChartOfAccountModal<?php echo $innerRow['child_account_number']?>" tabindex="-1" role="dialog" aria-labelledby="editChartOfAccountLabel" aria-hidden="true">
                                                   <div class="modal-dialog modal-dialog-centered" role="document">
                                                      <div class="modal-content">
                                                         <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteChartModalLabel<?php echo $innerRow['child_account_number']?>">Delete Item</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                            </button>
                                                         </div>
                                                         <div class="modal-body">
                                                            <form action="CRUD/delete/deleteChartOfAccount.php" method="POST" >
                                                            <input type="hidden" name="rowId" value="<?php echo $row['id']?>">
                                                             <input type="hidden" name="scroll_position" value="">
                                                               <input type="hidden" name="child_account_number" value="<?php echo $innerRow['child_account_number'] ?>">
                                                               <input type="hidden" name="child_account_name" value="<?php echo $innerRow['child_account_name'] ?>">
                                                               <p class="text-center">Are you sure you want to delete this item?</p>
                                                               <div class="d-flex flex-column justify-content-center align-items-center mb-3 ">
                                                                  <span> <?php echo "Account No: "."<strong>".$innerRow['child_account_number']?>
                                                                  </span>  
                                                                  <span>                                                               <?php echo "Account Name: "."<strong>".$innerRow['child_account_name']?>
                                                                  </span>
                                                               </div>
                                                               <div class="modal-footer">
                                                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                  <button type="submit" name="deleteChartAccount" class="btn btn-danger">Delete</button>
                                                               </div>
                                                            </form>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                                <?php
                                                   }
                                                   }
                                                   ?>
                                             </tbody>
                                          </table>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <!-- addd chart of accounts -->
                              <div class="modal fade" id="chartOfAccounts<?php echo $row['account_number']?>" tabindex="-1" role="dialog" aria-labelledby="chartOfAccountsLabel" aria-hidden="true">
                                 <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                       <div class="modal-header">
                                          <h5 class="modal-title" id="chartOfAccountsLabel">Add New Account - ID: 
                                             <span id="account_ref<?php echo $row['id'];?>" >

                                                    <?php echo $row['account_number']?>
                                              </span>
                                          </h5>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                          </button>
                                       </div>
                                       <div class="modal-body">
                                          <form method="GET" id="addChartAccount" action="CRUD/create/addChartOfAccount.php" >
                                          <input type="hidden" name="scroll_position" value="" >
                                          <input type="hidden" name="rowId" value="<?php echo $row['id'] ?>">
                                             <input type="hidden" name="account_number" value="<?php echo $row['account_number'] ?>">
                                             <div class="form-group">
                                                <label for="itemName">Child Account Code</label>
                                                <input type="hidden"  value=""  class="form-control" id="id_child_account_number_hidden<?php echo $row['id'] ?>" name="id_child_account_number_hidden" placeholder="Enter item name" title="Please enter a valid account code in the format parent_code_account" required min="<?php echo $row['account_number'] ?>">
                                                <input type="text"  value="" disabled class="form-control" id="id_child_account_number<?php echo $row['id'] ?>" name="child_account_number" placeholder="Enter item name" title="Please enter a valid account code in the format parent_code_account" required min="<?php echo $row['account_number'] ?>">
                                                <small class="form-text text-muted">Format: </small>
                                             </div>
                                             <div class="form-group">
                                                <label for="itemDescription">Account Name</label>
                                                <input class="form-control" id="child_account_code"  name="child_account_name" rows="3" placeholder="Enter item description" required></input>
                                             </div>
                                             <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" id="saveButton" class="btn btn-primary">Save</button>
    </div>
                                          </form>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <!-- End Modal -->
                              <?php }?>
                           </div>
                          
                           <div class="modal fade" id="addHeadModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered" role="document">
                                 <div class="modal-content">
                                    <div class="modal-header">
                                       <h5 class="modal-title">Add New Item</h5>
                                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                       <span aria-hidden="true">&times;</span>
                                       </button>
                                    </div>
                                    <div class="modal-body">
                                       <!-- Add your form elements for adding a new item here -->
                                       <!-- For example, you can use an HTML form -->
                                       <form action="CRUD/create/addChartHead.php"   method="GET">
                                          <div class="form-group">
                                             <label for="accountNumber">Account Number:</label>
                                             <input type="text" class="form-control" id="accountNumber" name="accountNumber" value="<?php echo $newAccountNumber; ?>" readonly>
                                          </div>
                                          <div class="form-group">
                                             <label for="accountCode">Account Code:</label>
                                             <input type="text" class="form-control" id="accountCode" name="accountCode">
                                          </div>
                                          <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
                                          </div>
                                          <!-- Add more form fields as needed -->
                                       </form>
                                    </div>
                                 </div>
                              </div>
                           </div>


                           
                           <div class="p-0">
                              <div class="    bg-white" style="border-radius:10px;">
                                 <div class="header-category p-3 bg-azure d-flex justify-content-center"><span class="text-white fw-bolder">Expenses Accounts</span></div>
                                 <div class="label ">
                                    <div class="container p-3 rounded-3">
                                       <?php 
                                          $sql = "SELECT c.*
                                          FROM chart_of_accounts AS c
                                          JOIN parent_accounts_tbl AS p ON c.parent_account_number = p.account_number
                                          WHERE p.account_title = 'Expenses'";
                                          $stmt = $conn->prepare($sql);
                                          $stmt->execute();
                                          $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                          
                                          foreach ($result as $row) {
                                                  
                                                  ?>
                                       <div class="row">
                                          <div class="col px-0 px-lg-3 px-md-3">
                                             <div class="table px-4 px-md-0 p-lg-0">
                                                <div class="d-flex justify-content-center">
                                                   <table class="expense-accounts table bg-white table-hover table-white" style="width: 100%;">
                                                      <thead style="border-radius: 10px;" class="dropdown-heading" id="head" data-toggle="collapse" data-target="#tableContentExpenseAccount<?php echo $row['id']?>" aria-expanded="false">
                                                         <tr style="">
                                                            <th colspan="3" style="width: 100%; border-radius: 10px; ">
                                                               <button class="addButtonExpense btn btn-primary btn-sm rounded-pill addButtonRow"
                                                                  id="addbuttonExpense<?php echo $row['id']; ?>"
                                                                  style="display:block;position: relative;top: 31px; left: 101%;"
                                                                  type="button" 
                                                                  data-toggle="modal"
                                                                  data-target="#chartOfAccountsExpense<?php echo $row['id']?>"
                                                                  data-row-id="<?php echo $row['id']; ?>"
                                                                  title="Add">
                                                               <i class="fa fa-plus"></i>
                                                               </button>
                                                               <button id="toggleButtonExpenseAccount" class="btn btn btn-block  rounded-pill shadow-lg d-flex justify-content-between" style="width: 100%; border-radius: 10px;background:#1c649f;color:white;">
                                                                  <div class="text-center mx-auto">
                                                                     <strong><?php echo $row['child_account_name']." "." #".$row['child_account_number']; ?></strong>
                                                                  </div>
                                                                  <i id="arrowIcon" class="fa fa-caret-down" aria-hidden="true"></i>
                                                               </button>
                                                            </th>
                                                         </tr>
                                                      </thead>
                                                      <tbody id="tableContentExpenseAccount<?php echo $row['id']; ?>" class="tableAccount collapse bg-white <?php if (isset($_SESSION['tableOpen'][$row['id']])) echo 'show'; ?>">
                                                         <?php
                                                            $innerSql = "SELECT expense_parent_number,expense_child_number, expense_name FROM expense_category WHERE expense_parent_number = :child_account_number";
                                                            $innerStmt = $conn->prepare($innerSql);
                                                            $innerStmt->bindParam(':child_account_number', $row['child_account_number']);
                                                            $innerStmt->execute();
                                                            $innerResult = $innerStmt->fetchAll(PDO::FETCH_ASSOC);
                                                            
                                                            if (count($innerResult) == 0) {
                                                                echo '<tr><td colspan="3">No data</td></tr>';
                                                            } else {
                                                                foreach ($innerResult as $innerRow) {
                                                            ?>
                                                         <tr class="chartAccountsList bg-white text-center">
                                                            <?php foreach ($innerRow as $key => $value) { ?>
                                                            <td class="col-7"><?php echo $value; ?></td>
                                                            <?php } ?>
                                                            <td class="d-flex flex-row col-12 border-0 justify-content-center ms-1 align-items-center">
                                                               <button class="edit-btn btn btn-success btn-sm rounded-0 " type="button" data-toggle="modal" data-placement="top" title="Edit" onclick="openEditModalExpense('<?php echo $innerRow['expense_parent_number']; ?>','<?php echo $innerRow['expense_child_number']; ?>', '<?php echo $innerRow['expense_name']; ?>','<?php echo $row['id']; ?>')">
                                                               <i class="fa fa-edit"></i>
                                                               </button>
                                                               <button class="deleteExpenseButton btn btn-danger btn-sm ms-2 rounded-0" type="button"
                                                                  data-toggle="modal" data-target="#deleteExpenseModal<?php echo $innerRow['expense_child_number'];?>"
                                                                  data-placement="top" title="Delete"
                                                                  data-account-number="<?php echo $innerRow['expense_child_number'] ?>" data-row-id ="<?php echo $row['id'] ?>"     data-account-title="<?php echo $innerRow['expense_name'] ?> ">
                                                               <i class="fa fa-trash"></i>
                                                               </button>
                                                            </td>
                                                         </tr>
                                                         <!-- Modal -->
                                                         <?php
                                                            }
                                                            }
                                                            ?>
                                                      </tbody>
                                                   </table>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="modal fade" id="editExpenseAccount<?php echo $innerRow['child_account_number']?>" tabindex="-1" role="dialog" aria-labelledby="editChartOfAccountLabel" aria-hidden="true">
                                          <div class="modal-dialog" role="document">
                                             <div class="modal-content">
                                                <div class="modal-header">
                                                   <h5 class="modal-title" id="editChartOfAccountLabel">Edit Account - ID: <?php echo $row['account_number']?></h5>
                                                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                   <span aria-hidden="true">&times;</span>
                                                   </button>
                                                </div>
                                                <div class="modal-body">
                                                   <form method="GET" action="CRUD/update/updateChartOfAccount.php">
                                                      <input type="hidden" name="id" value="<?php echo $innerRow['child_account_number'] ?>">
                                                      <div class="form-group">
                                                         <label for="accountCode">Account Code</label> 
                                                         <input type="text" class="form-control" id="child_account_number" name="child_account_number" value="<?php echo $innerRow['child_account_number'] ?>" readonly>
                                                      </div>
                                                      <div class="form-group">
                                                         <label for="accountName">Account Name</label>
                                                         <input class="form-control" id="child_account_code" name="child_account_code" value="<?php echo $innerRow['child_account_name'] ?>" placeholder="Enter Account Name" required>
                                                      </div>
                                                      <div class="modal-footer">
                                                         <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                         <button type="submit" class="btn btn-primary">Save changes</button>
                                                      </div>
                                                   </form>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                       <!-- Modal -->
                                       <div class="modal fade" id="deleteExpenseModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                          <div class="modal-dialog modal-dialog-centered">
                                             <div class="modal-content">
                                                <div class="modal-header">
                                                   <h5 class="modal-title" id="deleteModalExpenseTitle">Delete Account</h5>
                                                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                   <span aria-hidden="true">&times;</span>
                                                   </button>
                                                </div>
                                                <div class="modal-body" id="deleteModalExpenseBody">
                                                   <!-- Content will be populated dynamically via JavaScript -->
                                                </div>
                                                <div class="modal-footer">
                                                   <button type="button" id="closeModalButtonExpense" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                   <form id="deleteForm" action="CRUD/delete/deleteExpenseAccount.php" method="post"> 
                                                   <input type="hidden" name="rowId" value="<?php echo $row['id'] ?>">                                               
                                                   <input type="hidden" name="scroll_position" id="scroll_position" value = "" >                                                   
                                                      <input type="hidden" name="accountTitleExpense" id="accountTitleExpense" value = "" >
                                                      <input type="hidden" name="accountNumberExpense" id="accountNumberExpense" value = "" >
                                                      <input type="hidden" name="idAccountExpense" id="idAccountExpense" value = "" >
                                                      <button type="submit" name="deleteChartHead"   id="deleteAccount" class="btn btn-danger">Delete</button>
                                                   </form>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    
                                       <!-- d chaddart expense of accounts -->
                                       <div class="modal fade" id="chartOfAccountsExpense<?php echo $row['id']?>" tabindex="-1" role="dialog" aria-labelledby="chartOfAccountsLabel" aria-hidden="true">
                                          <div class="modal-dialog modal-dialog-centered" role="document">
                                             <div class="modal-content">
                                                <div class="modal-header">
                                                <h5 class="modal-title" id="chartOfAccountsLabel">Add New Account - ID: 
                                             <span id="ex_account_number_ref<?php echo $row['id'];?>" >
                                                    <?php echo $row['child_account_number']?>
                                              </span>
                                          </h5>
                                                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                   <span aria-hidden="true">&times;</span>
                                                   </button>
                                                </div>
                                                <div class="modal-body">
                                                   <form method="GET" action="CRUD/create/addChartExpense.php">
                                                      <input type="hidden" name="account_number" value="<?php echo $row['child_account_number'] ?>">
                                                      <div class="form-group">
                                                         <label for="itemName">Child Account Code</label>
                                                         <input type="hidden" name="rowId" value="<?php echo $row['id'] ?>">
                                                         <input type="hidden" name="scroll_position" value=""  class="form-control" id="scroll_position" placeholder="" title="Please enter a valid account code in the format parent_code_account">
                                                         <input type="hidden" name="rowIdExpense" value="" id="rowIdExpenseInput">

                                                         <input type="hidden" name="parent_expense_number" value="<?php echo $row['child_account_number'] ?>"  class="form-control" id="id_expense_account_number_hidden  title="Please enter a valid account code in the format parent_code_account">
                                                         <input type="hidden"  value=""  class="form-control" id="id_expense_account_number_hidden<?php echo $row['id'] ?>" name="expense_parent_number" placeholder="" title="Please enter a valid account code in the format parent_code_account">
                                                         <input type="text"  value="" disabled class="form-control" id="id_expense_account_number<?php echo $row['id'] ?>" name="expense_account_number" placeholder="Enter item name" title="Please enter a valid account code in the format parent_code_account">
                                                         <small class="form-text text-muted">Format: </small>
                                                      </div>
                                                      <div class="form-group">
                                                         <label for="itemDescription">Account Name</label>
                                                         <input class="form-control" id="expense_child_name"  name="expense_child_name" rows="3" placeholder="Enter Account name" required></input>
                                                      </div>
                                                      <div class="modal-footer">
                                                         <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                         <button type="submit" class="btn btn-primary">Save</button>
                                                      </div>
                                                   </form>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="modal fade" id="editExpenseAccountModal<?php echo $innerRow['child_account_number']?>" tabindex="-1" role="dialog" aria-labelledby="editChartOfAccountLabel" aria-hidden="true">
                                          <div class="modal-dialog" role="document">
                                             <div class="modal-content">
                                                <div class="modal-header">
                                                   <h5 class="modal-title" id="editChartOfAccountLabel">Edit Account - ID: <?php echo $row['account_number']?></h5>
                                                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                   <span aria-hidden="true">&times;</span>
                                                   </button>
                                                </div>
                                                <div class="modal-body">
                                                   <form method="GET" action="CRUD/update/updateChartOfAccount.php">
                                                      <input type="hidden" name="id" value="<?php echo $innerRow['child_account_number'] ?>">
                                                      <input type="text" name="rowId" value="<?php echo $row['id'] ?>">
                                                      <input type="hidden" name="scroll_position" value="">
                                                      <input type="hidden" name="id" value="<?php echo $innerRow['child_account_number'] ?>">
                                                      <div class="form-group">
                                                         <label for="accountCode">Account Code</label> 
                                                         <input type="text" class="form-control" id="child_account_number" name="child_account_number" value="<?php echo $innerRow['child_account_number'] ?>" readonly>
                                                      </div>
                                                      <div class="form-group">
                                                         <label for="accountName">Account Name</label>
                                                         <input class="form-control" id="child_account_code" name="child_account_code" value="<?php echo $innerRow['child_account_name'] ?>" placeholder="Enter Account Name" required>
                                                      </div>
                                                      <div class="modal-footer">
                                                         <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                         <button type="submit" class="btn btn-primary">Save changes</button>
                                                      </div>
                                                   </form>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                       <!-- End Modal -->
                                       <?php }?>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- charts -->
   </body>
   <!-- END BODY -->
   <!-- Template Javascript -->
   <script src="js/main.js"></script>
   <script src="js/cdn.jsdelivr.net_npm_bootstrap@5.0.0_dist_js_bootstrap.bundle.min.js"></script>

   <script>
      $(document).ready(function() {
      
         var storedScrollPosition = <?php echo isset($_SESSION['scroll_position']) ? $_SESSION['scroll_position'] : 0; ?>;
         <?php
            unset($_SESSION['tableOpen']);
            unset($_SESSION['scroll_position']);
            ?>
    if (storedScrollPosition > 0) {
        $(window).scrollTop(storedScrollPosition);
    }
   else{
      $(window).scrollTop(0);
   }
    // Scroll to the stored position

    
});

   </script>
   <script>

   
      $(".addButton").click(function() {
  
         var currentScrollPosition = $(window).scrollTop();
         var rowId = $(this).attr("data-row-id");
         var addButton = $("#addButton" + rowId);
         var tableContent = $("#tableContent" + rowId);
         
         $("input[name='scroll_position']").val(currentScrollPosition);
 

        if (tableContent.length > 0) {
            tableContent.collapse("toggle"); // Toggle the collapse state
        }
         
         var accountNumberRef = $("#account_ref"+rowId);
         var newAccountNumberRef =accountNumberRef.html();
         var accountNumberRefFi= (parseFloat(newAccountNumberRef));
         var limit =accountNumberRefFi+900;

        
         if (tableContent.length > 0) {
            var firstRow = tableContent.find("tr:last-child");
            
            if (firstRow.length > 0) {
               var firstCell = firstRow.find("td:first-child");
               var lastCell = firstRow.find("td:nth-child(2)");
               var lastCellValue = parseFloat(lastCell.html());    
         
           
               checkData = (parseFloat(firstCell.html()));
         
               if(parseFloat(lastCellValue) === limit){
                  if(!isNaN(checkData)){
                  $('#id_child_account_number_hidden' + rowId).val(parseFloat(lastCellValue) + 1);
               $('#id_child_account_number' + rowId).val(parseFloat(lastCellValue) + 1);
               }else{
                  $('#id_child_account_number_hidden' + rowId).val(accountNumberRefFi +1 );
               $('#id_child_account_number' + rowId).val(accountNumberRefFi + 1);
               }

               }else{
                  if(!isNaN(checkData)){
                  $('#id_child_account_number_hidden' + rowId).val(parseFloat(lastCellValue) + 100);
               $('#id_child_account_number' + rowId).val(parseFloat(lastCellValue) + 100);
               }else{
                  $('#id_child_account_number_hidden' + rowId).val(accountNumberRefFi + 100);
               $('#id_child_account_number' + rowId).val(accountNumberRefFi + 100);
               }
               }
      

              
              
            }
         }
      });
   </script>

   
<script>
   $(".addButtonExpense").click(function() {

      var currentScrollPosition = $(window).scrollTop();

      var rowId = $(this).attr("data-row-id");
      var tableContent = $("#tableContentExpenseAccount" + rowId);

      $("input[name='scroll_position']").val(currentScrollPosition);
  


  if (tableContent.length > 0) {
        tableContent.collapse("toggle"); // Toggle the collapse state
    }

      var expenseNumberRef = $("#ex_account_number_ref"+rowId);
      var newExpenseNumberRef =expenseNumberRef.html(); 
      var expenseNumberRefFi= (parseFloat(newExpenseNumberRef));
      var limit =expenseNumberRefFi+90;
  


      if (tableContent.length > 0) {
            var firstRow = tableContent.find("tr:last-child");
          
            if (firstRow.length > 0) {
               var firstCell = firstRow.find("td:first-child");
               var lastCell = firstRow.find("td:nth-child(2)");
               var lastCellValue = parseFloat(lastCell.html());    
   
               checkData = (parseFloat(firstCell.html()));
               if(parseFloat(lastCellValue) === limit){
                  if(!isNaN(checkData)){
                  $('#id_expense_account_number_hidden' + rowId).val(parseFloat(lastCellValue) + 1);
               $('#id_expense_account_number' + rowId).val(parseFloat(lastCellValue) + 1);
               }else{
                  $('#id_expense_account_number_hidden' + rowId).val(expenseNumberRefFi +1 );
               $('#id_expense_account_number' + rowId).val(expenseNumberRefFi + 1);
               }
               }else{
                  if(!isNaN(checkData)){
                  $('#id_expense_account_number_hidden' + rowId).val(parseFloat(lastCellValue) + 10);
               $('#id_expense_account_number' + rowId).val(parseFloat(lastCellValue) + 10);
               }else{
                  $('#id_expense_account_number_hidden' + rowId).val(expenseNumberRefFi + 10);
               $('#id_expense_account_number' + rowId).val(expenseNumberRefFi + 10);
               }
              
               }
            
            }
         }
      });
</script>

<script>
     $(document).ready(function() {
          $('.editChartOfAccount').on('click', function() {

            var currentScrollPosition = $(window).scrollTop();

         var rowId = $(this).attr("data-row-id");
         var tableContent = $("#tableContent" + rowId);
             var currentScrollPosition = $(window).scrollTop();
             $("input[name='scroll_position']").val(currentScrollPosition);
          });
      });
</script>

   <script>
      $(document).ready(function() {
         // Attach click event to the close button within the modal body
         $('#closeModalButton').on('click', function() {
          
            $('#deleteModal ').modal('hide'); // Hide the modal
        
         });
      });
      
      
      $(document).ready(function() {
         // Attach click event to the close button within the modal body
         $('#closeModalButtonEdit','#closeModalButtonEditExpense').on('click', function() {
            $('#editHeadChartModal').modal('hide'); // Hide the modal
            $('#editHeadChartModalExpense').modal('hide'); // Hide the modal
            // $('#deleteModal ').modal('hide'); // Hide the modal
         });
      });
      
      $(document).ready(function() {
         // Attach click event to the close button within the modal body
         $('#closeModalButtonEditExpense').on('click', function() {
            $('#editHeadChartModalExpense').modal('hide'); // Hide the modal
            // $('#deleteModal ').modal('hide'); // Hide the modal
         });
      });
          
      $(document).ready(function() {
         // Attach click event to the close button within the modal body
         $('#closeModalButtonExpense').on('click', function() {
            $('#deleteExpenseModal').modal('hide'); // Hide the modal
         });
      });
      
      $(document).ready(function() {
          $('.deleteChartHeadBtn').on('click', function() {

             var currentScrollPosition = $(window).scrollTop();
    
        
              var accountNumber = $(this).data('account-number');
              var accountTitle = $(this).data('account-title');
              var account_id = $(this).data('account-id');

              $("input[name='scroll_position']").val(currentScrollPosition);

              $('#deleteModalTitle').html('Delete Account: ' + accountTitle);
              $('#deleteModalBody').html(' <p class="text-center">Are you sure you want to delete this Account?</p>' +
                                  '<strong style="font-weight: bold; display: block; text-align: center;">Account Number: ' + accountNumber + '</strong>' +
                                  '<br>' +
                                  '<strong style="font-weight: bold; display: block; text-align: center;">Account Title: ' + accountTitle + '</strong>');
              $('#idAccount').val(account_id);
             $('#accountTitle').val(accountTitle);
             $('#accountNumber').val(accountNumber);
             
      
              $('#deleteModal').modal('show');
          });
      });

      $(document).ready(function() {
          $('.deleteChartAccountBtn').on('click', function() {

            var currentScrollPosition = $(window).scrollTop();
         var rowId = $(this).attr("data-row-id");
         var addButton = $("#addButton" + rowId);
         var tableContent = $("#tableContent" + rowId);
         
      
             var currentScrollPosition = $(window).scrollTop();
             $("input[name='scroll_position']").val(currentScrollPosition);
            
          
      
          
          });
      });
      
      
      
      $(document).ready(function() {
          $('.deleteExpenseButton ').on('click', function() {
            var currentScrollPosition = $(window).scrollTop();
            $("input[name='scroll_position']").val(currentScrollPosition);

              var accountNumber = $(this).data('account-number');
              var accountTitle = $(this).data('account-title');
              var account_id = $(this).data('account-id');
              var rowId = $(this).attr("data-row-id");
   
            
      $("input[name='rowId']").val(rowId);


              $('#deleteModalExpenseBody').html(' <p class="text-center">Are you sure you want to delete this Account?</p>' +
                                  '<strong style="font-weight: bold; display: block; text-align: center;">Account Number: ' + accountNumber + '</strong>' +
                                  '<br>' +
                                  '<strong style="font-weight: bold; display: block; text-align: center;">Account Title: ' + accountTitle + '</strong>');
              $('#idAccountExpense').val(account_id);
             $('#accountTitleExpense').val(accountTitle);
             $('#accountNumberExpense').val(accountNumber);
             
      
              $('#deleteExpenseModal').modal('show');
          });
      });
      
      
      
      
      $(document).ready(function() {
            $('.dropdown-heading').on('click', function() {
            var rowId = $(this).data('target').replace('#tableContent', '');
            var addButton = $('#addButton' + rowId);
            addButton.toggle();
            });
            });
      
           $(document).ready(function() {
         $('.dropdown-heading').on('click', function() {
            var rowId = $(this).data('target').replace('#tableContentExpenseAccount', '');
            var addButton = $('.addButtonRow[data-row-id="' + rowId + '"]');
            addButton.style.display="block";
         });
      });
      
      
      
      $(document).ready(function() {
         $(".dropdown-heading").click(function() {
      
            // Toggle the active-button class on the clicked button
            $(this).find(".btn").toggleClass("active");
           
            // Toggle the arrow icon on the clicked button
            $(this).find("#arrowIcon").toggleClass("fa-caret-down fa-caret-up");
       
         });
      });
      
   </script>

   


</html>