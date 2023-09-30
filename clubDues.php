

<?php
    
   session_start();
   
   if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Redirect the user to the login page
    header('Location: index.php');
    exit();
   }
    include 'sqlGetData/formatValuePeso.php';
      require 'includes/dbh.inc.php';
   
   $email = $_SESSION['email'];
   $role =  $_SESSION['role'];
   
   // Output the value of $role in a script tag
   echo "<script>var role = '" . $role . "';</script>";
   $query = "SELECT cm.name as member_name, md.month, md.amount, ci.receipt,cm.type,cm.member_id as id,md.month,md.year,md.transac_id
   FROM club_members_tbl AS cm
   LEFT JOIN monthly_dues_tbl AS md ON cm.member_id = md.member_id
   LEFT JOIN cashinflow_tbl AS ci ON md.transac_id = ci.id";

$stmt = $conn->prepare($query);
 
// Execute the query
$stmt->execute();

// Fetch results into an associative array
$duesData = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Close the database connection (optional, as PDO closes it automatically when the script ends)
$pdo = null;
?>


<!DOCTYPE html>
<style>
   th#right-corner {
    width: 174px!important;
}th.sorting {
    width: 321px!important;
}body{
   color:black;
} th[data-cell="nameMember"] {
   width: 100px!important;
}.progress{
   background:#b2c9f3!important;
}.progress-bar{
   background:#0e4591!important;
}
  .no-vertical-borders {
    border-collapse: collapse;
  
  }th.sorting.sorting_asc{
   width:10px!important;
  }/* Make the second <td> sticky */
td:nth-child(1){
  position: sticky;
  left: 0; /* You can adjust this value to control the stickiness direction */
  text-transform:capitalize;

}.sticky-bg {
  background-color:white!important;
  color:#1a1919;
  font-weight:600;
 
} .table-hover tbody tr:hover {
         font-weight:600;
        }table th:first-child {
  position: sticky!important;
  left: 0!important; /* You can adjust this value to control the stickiness direction */
}table  th{
    text-transform:uppercase;
}.sticky-bg-th{
    background-color:#0e4591!important;
    z-index:99;
}td.text-right:hover{
    cursor:pointer;
}.custom-input {
    display: flex;
    align-items: center;
}

.currency-symbol {
    color:black;
    margin-right: -14px;
    z-index:999;
}.form-control.inputAmount{
padding-left:20px;
}.highlight-red {
    background-color: #cf4e4e!important;
    color: white; /* You can adjust the text color as needed */
}.success-row {
    background-color:#54a2db9e!important;

} /* Style the custom file input */
.custom-file-input {
    cursor: pointer;
    padding: 10px;
    border: 2px solid #007bff; /* Change the border color to your preference */
    border-radius: 5px;
    background-color: transparent;
    color: #007bff; /* Change the text color to your preference */
}.custom-file-input {
    position: relative;
    z-index: 2;
    width: 100%;
    height: calc(1.5em + 0.75rem + 2px);
    opacity: 0;
    top: -34px;
}

/* Style the label text */
.custom-file-label::after {
    content: "Browse"; /* Customize the label text */
}

/* Style when a file is selected */
.custom-file-input:focus {
    border-color: #007bff; /* Change the border color when focused */
    box-shadow: none;
}

/* Style when hovering over the file input */
.custom-file-input:hover {
    background-color: #f8f9fa; /* Change the background color on hover */
}img#receiptImage {
    position: relative;
    top: -4px;
    left: 46px;
}.modal-footer.mt-2 {
    position: relative;
    top: 22px!important;
}.custom-file-input.add{
    position: relative;
    z-index: 2;
    width: 100%;
    height: calc(1.5em + 0.75rem + 2px);
    opacity: 0;
    top: 0px;
}


</style>
</style>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>Club Members </title>
      <meta content="width=device-width, initial-scale=1.0" name="viewport">
      <meta content="" name="keywords">
      <meta content="" name="description">
      <!-- css content -->
      <link rel="stylesheet" href="assets/fontawesome-free-6.3.0-web/css/all.css">
      <link rel="stylesheet" href="assets/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/css/budgetManagement.css">
      <!-- DATA TABLES  cs-->
      <link rel="stylesheet" href="assets/datatable/css/datatables.min.css">

      <link rel="stylesheet" href="assets/datatable/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/datatable/css/jquery.dataTables.min.css">
      <link rel="stylesheet" href="assets/css/cashinflow/clubdues.css">
      <link rel="stylesheet" href="assets/datatable/js/cdn.datatables.net_buttons_2.4.1_css_buttons.dataTables.min.css">
      <!-- media Print -->

      <!-- DATA TABLE JS -->
      <script src="assets/datatable/js/jquery-3.6.0.min.js"></script>
      <script src="assets/datatable/css/bootstrap.bundle.min.js"></script>
      <script src="assets/datatable/js/datatables.min.js"></script>   
      <script src="assets/datatable/js/vfs_fonts.js"></script>
      <!-- DATA TABLES DATE FILTER -->
      <script src="assets/datatable/js/moment.min.js"></script>

      <script src="assets/datatable/js/clubDues.js"></script>
      <!-- general -->
      <script src="assets/js/popper.min.js"></script>
      <script src="assets/js/bootstrap.min.js"></script>
      <script src="assets/js/moment.min.js"></script>
      <script src="assets/js/select2.min.js"></script>
 
      <!-- <script src="assets/js/script.js"></script> -->
      <!-- fixed header -->
    
      <!-- Google Web Fonts -->
      <link href="css/bootstrap.min.css" rel="stylesheet">
      <link href="css/style.css" rel="stylesheet">

      <!-- general -->
   </head>
 
   <!-- START BODY -->
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
         <div class="content open">
            <!-- Navbar Start -->
            <?php include 'template/navbar.php'?>
            <?php      include 'template/modal.php'?>
            <!-- Navbar End -->
            <!-- Sale & Revenue Start -->
            <div class="container-fluid pt-4 px-md-3 px-lg-4 px-md-3 px-lg-3  mt-3">
               <div class="filter">
                  <div class=" row g-3 mx-md-4 mx-lg-4   mx-1 p-md-3 p-lg-3 p-2 ">
                     <!-- filter row -->
                     
                     <div class="col-sm-12 col-md-6 col-xl-4 d-flex justify-content-center">
                        <div class="categ col-lg-12 col-sm-10 d-flex flex-column ">
                           <span><strong class="">Month</strong></span>
                           <div class="form-group row">
                                <select class="type-box col-sm-12 col-md-10 form-control form-control-sm">
                                    <option value="" selected >Choose month...</option>
                                    <?php
                                    $months = [
                                        "July", "August", "September", "October", "November",
                                        "December", "January", "February", "March", "April", "May", "June"
                                    ];

                                  
                                    $counter = 1; // Initialize a counter variable
                                    
                                    foreach ($months as $month) {
                                        // Use the $counter variable as the value attribute
                                        echo '<option value="' . $counter . '">' . $month . '</option>';
                                        
                                        // Increment the counter for the next iteration
                                        $counter++;
                                    }
                                    ?>
                                </select>
                            </div>

                        </div>
                     </div>
                      
                     <div class="ms-lg-0 ms-md-0 col-sm-12 col-md-6 col-xl-4 d-flex align-items-center ">
                        <span class="mt-2"><strong style="position:absolute; top:-22px;"></strong></span>
                        <div class="categ col-lg-12 col-sm-10 d-flex flex-column ">
                           <span><strong class="">Category</strong></span>
                           <div class="form-group row">
                              <select class="category-box col-sm-12 col-md-10 form-control form-control-sm">
                                 <option selected >Select Category</option>
                                 <option  >Student</option>
                                  <option  >Working</option>
                              </select>
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-12 col-xl-3 ms-sm-3">
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
            <hr class="mx-4">
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
                             
                                    <button type="button" class="add trans order-2 mt-1 float-right btn btn-sm btn-primary" data-toggle="modal" data-target="#addTransactionModal" style="background-color:">
                                       <span class="fa fa-plus"></span>Add Transaction
                                    </button>
                                 
                              </div>
                        </div>';
                        
                        }
                        ?>
                     <div class="dataTable_wrapper income col-12 pt-3 px-0 pb-0">
                        <div class="bg-white rounded h-100  mb-0 pb-0">
                           <div class="table-responsive">
                           <table id="dataTablesIncome" class="table table-hover bg-white shadow-lg no-vertical-borders">
    <thead>
    <tr>
    <th id="sticky-th" data-cell="name" class="sticky-bg-th">Name</th>
    <th>Type</th>

    <?php
    $monthNames = [
        "Jan", "Feb", "Mar", "Apr", "May", "Jun",
        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
    ];

    $currentYear = date('Y'); // Get the current year
    $startMonth = 7; // July
    $incrementYear = false; // Initialize a flag to track year increment

    for ($i = $startMonth; $i < $startMonth + 12; $i++) {
        $monthIndex = ($i - 1) % 12; // Convert to 0-based index, wrap around

        if ($monthIndex == 0 && !$incrementYear) {
            $currentYear++; // Decrement the year for January
            $incrementYear = true; // Set the flag to increment the year starting from February
        }

        $month = $monthNames[$monthIndex];
        echo "<th>$month $currentYear</th>";
    }
    ?>

    <th data-cell="payment">Total Payment</th>
    <th data-cell="totalAmount">Total Amount Due</th>
    <th data-cell="balance">Balance</th>
</tr>

    </thead>
    <tbody>
    <?php
$displayedMembers = []; // Initialize an array to keep track of displayed members

foreach ($duesData as $row) {
    // Check if this member has already been displayed, if yes, skip
    if (in_array($row['id'], $displayedMembers)) {
        continue;
    }

    $displayedMembers[] = $row['id']; // Add the member to the displayed list

   // Check if the member has completed the total amount due
   $memberType = htmlspecialchars($row['type']);
   $totalAmountDue = ($memberType === 'Student') ? 1200 : 2400;
   $totalPayment = 0;
   
   foreach ($duesData as $paymentRow) {
       if ($paymentRow['id'] == $row['id']) {
           $totalPayment += $paymentRow['amount'];
       }
   }

   $rowClass = ($totalPayment >= $totalAmountDue) ? 'success-row' : '';

   echo '<tr class="' . $rowClass . '">';

    echo '<td  class="sticky-cell" >' . htmlspecialchars($row['member_name']) . '</td>';
    echo '<td>' . htmlspecialchars($row['type']) . '</td>';
    $totalPayment = 0;
    $currentMonth = date('n'); // Get the current month (1-12)
    for ($i = $startMonth; $i < $startMonth + 12; $i++) {
        $paymentCell = 0;
        $year = $i <= 12 ? $currentYear : $currentYear + 1;

        // Check if the month is before the current month and payment is zero
        if ($year < $currentYear || ($year == $currentYear && $i < $currentMonth)) {
            $cellClass = 'text-right highlight-red'; // Apply red highlighting style
        } else {
            $cellClass = 'text-right'; // Default style
        }

        foreach ($duesData as $paymentRow) {
          
            if ($paymentRow['id'] == $row['id'] && $paymentRow['month'] == $i) {
                $paymentCell = number_format($paymentRow['amount'], 2);
                $totalPayment += $paymentRow['amount'];
                break;
            }
        }

        // Remove red highlighting if the cell value is greater than zero
        if ($paymentCell > 0) {
            $cellClass = 'text-right';
        }

        echo '<td class="' . $cellClass . '"  data-cell-month="' . $i . '"  data-cell-oldReceipt="' . $row['receipt']. '"  data-cell-memberTransacId="' . $paymentRow['transac_id'] . '"  data-cell-transacId="' . $row['transac_id'] . '"  data-cell-type="' . $row['type'] . '" data-cell-id="' . $row['id'] . '">' . formatValuePeso($paymentCell) . '</td>';
    }

    echo '<td class="text-right" >' . formatValuePeso($totalPayment) . '</td>';
    $memberType = htmlspecialchars($row['type']);
    $totalAmountDue = ($memberType === 'Student') ? 1200 : 2400;
    echo '<td class="text-right" >' . formatValuePeso($totalAmountDue) . '</td>';
    $balance = $totalAmountDue - $totalPayment;
    echo '<td class="text-right" >' . number_format($balance, 2) . '</td>';
    echo '</tr>';
}
?>

    </tbody>
    <tfoot>
        <tr>
            <td class="bg-secondary text-dark" colspan="2">Total: </td>
            <?php
            $totalPaymentSum = 0;

            foreach ($displayedMembers as $memberId) {
                $memberTotalPayment = array_sum(array_column(array_filter($duesData, function ($paymentRow) use ($memberId) {
                    return $paymentRow['id'] == $memberId;
                }), 'amount'));
                $totalPaymentSum += $memberTotalPayment;
            }

            echo '<td class=" bg-secondary  text-right  " style="color: #363232;font-size: large;font-weight: 900!important;" colspan="13">' . formatValuePeso($totalPaymentSum) . '</td>';

            $totalAmountDueSum = array_sum(array_map(function ($memberId) use ($duesData) {
                $memberType = htmlspecialchars(array_column(array_filter($duesData, function ($row) use ($memberId) {
                    return $row['id'] == $memberId;
                }), 'type')[0]);
                return ($memberType === 'Student') ? 1200 : 2400;
            }, $displayedMembers));

            echo '<td class="bg-secondary" style="text-align:right;color: #363232;font-size: large;font-weight: 900!important;">' . formatValuePeso($totalAmountDueSum) . '</td>';
            ?>
            <td class="bg-secondary text-white"></td> <!-- Empty cell for balance -->
        </tr>
    </tfoot>
</table>

                            <!-- Modal for adding transactions -->
            <div id="addTransactionModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addTransactionModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addTransactionModalLabel">Add Transaction</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body p-4">
                            <!-- Form to add transaction -->
                            <form id="addTransactionForm" action="CRUD/create/addPaymentDues.php" enctype="multipart/form-data" method="POST">


                                <div class="form-group">
                                <input type="hidden" class="form-control" name="dateText" value="" id="dateText">
                                <input type="hidden" class="form-control" name="memberNameAdd" value="" id="memberNameAdd">
                                    <label for="memberDropdown">Member</label>
                                    <select class="form-control" name="memberId" id="memberDropdown">
                <!-- Populate member options here -->
                <option value="" selected>Choose Member...</option>
                <?php
                $queryMember = "SELECT member_id, name FROM club_members_tbl";

                $stmt = $conn->prepare($queryMember);

                // Execute the query
                $stmt->execute();

                // Fetch results into an associative array
                $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($members as $member) {
                    // Use data attributes to store member ID and name
                    echo '<option value="' . $member['member_id'] . '" data-member-name="' . htmlspecialchars($member['name']) . '">' . htmlspecialchars($member['name']) . '</option>';
                }
                ?>
            </select>

                                </div>
                                <div class="form-group">
                                    <label for="monthDropdown">Month</label>
                                    <select class="form-control" id="monthDropdown" name="selectedMonth">
                                    <option value="" seletced>Choose Month....</option>
                                        <?php
                                    $monthNamesFull = [
                                        "January", "February", "March", "April", "May", "June",
                                        "July", "August", "September", "October", "November", "December"
                                    ];
                                    

                                    for ($i = $startMonth; $i < $startMonth + 12; $i++) {
                                        $monthIndex = ($i - 1) % 12; // Convert to 0-based index, wrap around
                                    
                                        // If it's January, set the flag to increment the year
                                        if ($monthIndex == 0) {
                                            $incrementYear = true;
                                        }
                                    
                                        if ($incrementYear) {
                                            $year = $currentYear + 1; // Increment the year
                                        } else {
                                            $year = $currentYear; // Keep the current year
                                        }
                                    
                                        $month = $monthNamesFull[$monthIndex];
                                        
                                        echo '<option value="' . $i . '" data-month-text="'. $month.'">' . $month. '</option>';
                                    }
                                    
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="amountInput">Amount</label>
                                    <input type="number" class="form-control amountInput" name="amount" id="amountInput" placeholder="Enter amount" oninput="validateAmount(this)">                                </div>
                                <div class="form-group col-md-12"> 
                <label for="editReceipt">Receipt:</label>
                <div class="d-flex justify-content-center">
                <img class=" mb-3 img-fluid " id="receiptImageAdd" src="" style="width:329px; display:none;" alt="Receipt Image">
                </div>
                
                <div class="custom-file">
                    
                <label class="custom-file-label" for="receiptName" id="receiptName">Choose an image file</label>
<input type="file" class="custom-file-input add" id="receipt" name="receipt" accept="image/png">



              </div>
           
            </div>

                            </form>
                        </div>
                        <div class="modal-footer ">
                            <button type="button" id="closeEdit" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button class="btn btn-primary" id="submitFormButton">Submit</button>

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



                               <!-- Modal for adding transactions -->
<div id="editTransactionModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editTransactionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTransactionModalLabel">Edit Transaction</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <!-- Form to add transaction -->
                <form id="editTransactionForm" action="CRUD/update/updatePaymentDues.php" enctype="multipart/form-data" method="POST">
                <input type="hidden" class="form-control" name="memberId" id="id">
                <input type="hidden" class="form-control" name="memberName" id="memberName">
                <input type="hidden" class="form-control" name="monthText" id="monthText">
                <input type="hidden" class="form-control" name="transacIdPayment" id="transacIdPayment">
                <input type="hidden" class="form-control" name="membertransacid" id="membertransacid">

                <div class="form-group">
    <label for="memberDropdown">Member</label>
    <select class="form-control" name="memberUpdate" id="memberUpdate">
        <?php
        $queryMember = "SELECT member_id, name FROM club_members_tbl";
        $stmt = $conn->prepare($queryMember);
        $stmt->execute();
        $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($members as $member) {
            $memberId = $member['member_id'];
            $memberName = htmlspecialchars($member['name']);
            echo '<option value="'  . $memberId . '" data-edit-member="'  . $memberName . '"    >' . $memberName . '</option>';
        }
        ?>
    </select>
</div>

                    <div class="form-group">
                        <label for="monthDropdown">Month</label>
                        <select class="form-control" id="monthsSelected" name="selectedMonth">
                           <option value="" >Choose Month....</option>
                            <?php
                         $monthNamesFull = [
                            "January", "February", "March", "April", "May", "June",
                            "July", "August", "September", "October", "November", "December"
                        ];
                        

                        for ($i = $startMonth; $i < $startMonth + 12; $i++) {
                            $monthIndex = ($i - 1) % 12; // Convert to 0-based index, wrap around
                        
                            // If it's January, set the flag to increment the year
                            if ($monthIndex == 0) {
                                $incrementYear = true;
                            }
                        
                            if ($incrementYear) {
                                $year = $currentYear + 1; // Increment the year
                            } else {
                                $year = $currentYear; // Keep the current year
                            }
                        
                            $month = $monthNamesFull[$monthIndex];
                            
                            echo '<option value="' . $i . '" data-edit-month="'  . $month . '" >' . $month. '</option>';
                        }
                        
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="amountInput">Amount</label>
                        <div class="custom-input">
    <span class="currency-symbol">₱</span>
    <input type="text" class="form-control inputAmount" oninput="checkAmountInput(this);" name="amount" id="dues" placeholder="Enter amount"></div>
            </div>
            <div class="form-group col-md-12 ">
                
                        <label for="editReceipt">Receipt:</label>
                        <div id="successMessage" class="alert alert-success mt-2 text-center" style="display:none;">
                           <div>
                           </div>
                           <span class="ms-2 mb-2 text-center ">
                           <i class="fa fa-check mx-3" ></i>  Your file is being submitted for upload.
                           </span>
                        </div>
                        <input type="file" class="custom-file-input" id="newReceipt" name="newReceipt" accept="image/png">
                    <label class="custom-file-label" for="receiptName" id="receiptName">Choose an image file</label>
                    <img class="pt-3 mt-3 img-fluid" class="" id="receiptImage" src="" style="width:329px;" alt="Receipt Image">

                        <div class="custom-file">
                           <!-- Hide the actual file input -->
                           <input type="hidden" id="oldReceipt" name="oldReceipt" value="">
                          
                           <!-- Add a message div to display the success message -->
                        </div>
                       
                     </div>
            <div class="modal-footer mt-2">
                <button type="button" onclick="closeModal()" class="btn btn-secondary" >Close</button>
                <button class="btn btn-primary" id="submitFormButtonEdit">Submit</button>
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
      <!-- Container End -->
      </div>
      <!--Modals Start -->
      <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Success</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="successMessageUpdate">Your request was successful!</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>




               <!-- edit modal Start-->
             

          
      <!-- Details Modal End -->
      <!-- ADD MODAL -->
     <!-- Add Member Modal -->


    
      </div>
      <!-- charts -->
   </body>
   <!-- END BODY -->
   <!-- Template Javascript -->
   <script src="js/main.js"></script>
   <script src="js/cdn.jsdelivr.net_npm_bootstrap@5.0.0_dist_js_bootstrap.bundle.min.js"></script>
   <script>
document.addEventListener("DOMContentLoaded", function () {
    // Get a reference to the submit button
    var submitButton = document.getElementById("submitFormButton");

    // Get a reference to the form
    var form = document.getElementById("addTransactionForm");

    // Add a click event listener to the submit button
    submitButton.addEventListener("click", function () {
       
        // Trigger the form submission when the button is clicked
        form.submit();
    });
});
</script>


   <!-- js -->
   <script>
    const tableContainer = document.querySelector('.table-responsive');
    const stickyCells = document.getElementsByClassName("sticky-cell");
    const stickyth = document.getElementById("sticky-th");
    console.log(stickyth);

    tableContainer.addEventListener('scroll', function () {
      
        if (tableContainer.scrollLeft > 0) {
                stickyth.classList.add("sticky-bg-th");
               
            } else {
                stickyth.classList.remove("sticky-bg-th");
     
            }


        for (let i = 0; i < stickyCells.length; i++) {
            if (tableContainer.scrollLeft > 0) {
                stickyth.classList.add("sticky-bg-th");
                stickyCells[i].classList.add("sticky-bg");
            } else {
                stickyth.classList.remove("sticky-bg-th");
                stickyCells[i].classList.remove("sticky-bg");
            }
        }
    });
</script>
<script>
    var receiptImageElement = document.getElementById('receiptImage');
    var receiptInput = document.getElementById('receipt');
    var successMessage = document.getElementById('successMessage');
    var fileName = document.getElementById('receiptName');

    receiptInput.addEventListener('change', function() {
        var file = receiptInput.files[0];

        if (file) {
            // Assuming you want to display the selected image
            var reader = new FileReader();

            reader.onload = function(e) {
                // Get the img element
                var receiptImageElement = document.getElementById('receiptImage');

                // Set the src attribute of the img element
                receiptImageElement.src = e.target.result;
                receiptImageElement.style.display = 'block'; // Show the image
            };

            reader.readAsDataURL(file);
        } else {
            // Handle the case when no file is selected
            var receiptImageElement = document.getElementById('receiptImage');
            receiptImageElement.src = ''; // Set the src to an empty string to clear any previous image
        }
    });

    // Check if there is a receipt (update 'hasReceipt' with your logic)
    var hasReceipt = true; // Replace with your logic for checking if there's a receipt

    if (!hasReceipt) {
        // If there's no receipt, hide the image
        receiptImageElement.style.display = 'none';
    }

</script>

<script>
    
    var receiptImageElement = document.getElementById('receiptImageAdd');

    var fileInputAdd = document.getElementById('receipt');

    // Add an event listener for the "change" event
    fileInputAdd.addEventListener('change', function() {
        // Check if a file has been selected
        var fileInput = this;
    var file = fileInput.files[0];

    if (file) {
        
      var filename = file.name;
 
    
      // Assuming you want to display the selected image
      var reader = new FileReader();
      reader.onload = function(e) {
        
        receiptImageElement.src = e.target.result;
        receiptImageElement.style.display = "block"; // Show the image
      };
      reader.readAsDataURL(file);

    } else {
 
      receiptImageElement.style.display = "none";
    }

 
        
    });
</script>

<script>
 
</script>
<script>
$(document).ready(function(){
  // Get the member_id from the URL query parameter
  const urlParams = new URLSearchParams(window.location.search);
  const memberId = urlParams.get('member_id');

  // Check if memberId exists and is not empty
  if (memberId) {
    // Set the value of your dropdown or input field with the memberId
    document.getElementById("memberDropdown").value = memberId;
    document.getElementById("memberDropdown").selected = memberId;
    
    $("#addTransactionModal").modal("show");
    
    // Remove the memberId from the URL without refreshing the page
    const newUrl = window.location.pathname; // Get the current path without query parameters
    window.history.pushState({}, document.title, newUrl); // Update the URL
  }
});

</script>
<script>
    // Add an event listener to the memberDropdown select element
    document.getElementById("memberDropdown").addEventListener("change", function () {
        // Get the selected option
        var selectedOption = this.options[this.selectedIndex];
        var month = selectedOption.getAttribute("data-month-text");

        // Get the member name from the data-member-name attribute
        var memberName = selectedOption.getAttribute("data-member-name");

        document.getElementById("memberNameAdd").value = memberName;
    });
</script>

<script>

    document.getElementById("monthsSelected").addEventListener("change", function () {
        // Get the selected option
        var selectedOption = this.options[this.selectedIndex];
        var month = selectedOption.getAttribute("data-edit-month");
    
        document.getElementById("dateText").value = month;
    });
</script>
    


<script>

    document.getElementById("monthDropdown").addEventListener("change", function () {
     
        // Get the selected option
        var selectedOption = this.options[this.selectedIndex];
        var month = selectedOption.getAttribute("data-month-text");
    
        document.getElementById("dateText").value = month;

   });
</script>


<script>
    // Add an event listener to the memberDropdown select element
    document.getElementById("memberUpdate").addEventListener("change", function () {
        // Get the selected option
        var selectedOption = this.options[this.selectedIndex];
        var member = selectedOption.getAttribute("data-edit-member");
  
        document.getElementById("memberName").value = member;
    });
</script>


<script>
    // Add an event listener to the memberDropdown select element
    document.getElementById("monthsSelected").addEventListener("change", function () {
                // Get the selected option
        var selectedOption = this.options[this.selectedIndex];
        var month = selectedOption.getAttribute("data-edit-month");
  
        document.getElementById("monthText").value = month;
    });
</script>

<script>

function calculateTotalPayment(memberId) {
            const paymentCells = document.querySelectorAll(`[data-cell-id="${memberId}"][data-cell-month]`);
            let totalPayment = 0;

            paymentCells.forEach(cell => {
                const payment = parseFloat(cell.textContent.replace(/[^0-9.-]+/g,"")); // Parse the payment value
                if (!isNaN(payment)) {
                    totalPayment += payment;
                }
            });

            return totalPayment;
        }

        function calculateTotalAmountDue(memberId) {
            const typeCell = document.querySelector(`[data-cell-id="${memberId}"][data-cell-type]`);
            const memberType = typeCell.getAttribute("data-cell-type");
            const totalAmountDue = (memberType === 'Student') ? 1200 : 2400;

            return totalAmountDue;
        }
        function getMemberType(memberId) {
    const totalAmountDue = calculateTotalAmountDue(memberId);

    // Determine member type based on the total amount due
    if (totalAmountDue === 1200) {
        return 'Student';
    } else if (totalAmountDue === 2400) {
        return 'Working';
    } else {
        return 'Unknown'; // Handle cases where totalAmountDue doesn't match known types
    }
}
    document.addEventListener("DOMContentLoaded", function() {
        const amountInput = document.getElementById("amountInput");
        const memberDropdown = document.getElementById("memberDropdown");

        // Function to calculate the total payment for a specific member
      

        // Function to calculate the total amount due for a specific member
       

        // Function to enable or disable the amount input based on the total payment
        // Function to enable or disable the amount input based on the total payment
    // Function to enable or disable the amount input based on the total payment
    function toggleAmountInput() {
            const selectedMemberId = memberDropdown.value;
            if (selectedMemberId) {
                const totalPayment = calculateTotalPayment(selectedMemberId);
                const totalAmountDue = calculateTotalAmountDue(selectedMemberId);

                // Get the selected month, you need to adjust this part according to how you select the month
                const member = document.getElementById("memberDropdown").value;
          

                if (member) {
                    // Get the member type
                    const memberType = getMemberType(selectedMemberId);

                    // Set a new placeholder when the input is disabled
                    if (totalPayment >= totalAmountDue) {
                        amountInput.disabled = true;
                        amountInput.placeholder = "All the monthly dues have been paid";
                    } else {
                        amountInput.disabled = false;
                        amountInput.placeholder = "Enter amount";
                    }
                }
            } else {
                // Disable the input if no member is selected
                amountInput.disabled = true;
            }
        }

        // Attach an event listener to the member dropdown to toggle the amount input
        memberDropdown.addEventListener("change", toggleAmountInput);

        // Call the toggleAmountInput function initially to set the initial state
        toggleAmountInput();

        amountInput.addEventListener("input", function () {
        validateAmount(amountInput, memberDropdown);
    });


    function calculateTotalAmountDue(memberId) {
        const typeCell = document.querySelector(`[data-cell-id="${memberId}"][data-cell-type]`);
        const memberType = typeCell.getAttribute("data-cell-type");
        const totalAmountDue = (memberType === 'Student') ? 1200 : 2400;

        return totalAmountDue;
    }
    // Function to get the member type based on the selected member ID




    // Function to validate the input amount
   function validateAmount(input, memberDropdown) {
    var value = removeNonNumericCharacters(input.value); // Remove non-numeric characters from the input
    var amount = parseFloat(value); // Convert the input value to a number
    var selectedMemberId = memberDropdown.value;
    


    if (isNaN(amount)) {
            input.value= 0;
    } else if (amount <= 0) {
        alert("Amount must be greater than zero.");
    } else {
        // Check the member type and perform specific validation
        var memberType = getMemberType(selectedMemberId);
        if (memberType === 'Student' && amount > 1200) {
            showErrorModal("Amount exceeds the maximum allowed for students.");
            input.value=0;  
        } else if (memberType === 'Working' && amount > 2400) {
            showErrorModal("Amount exceeds the maximum allowed for working members.");
            input.value= 0;
        } 
    }
}





    });

    function checkAmountInput(inputElement) {
    
    const amount = inputElement.value;
    const selectedMemberId = document.getElementById("memberUpdate").value;
 
    if (selectedMemberId) {
        const totalPayment = calculateTotalPayment(selectedMemberId);
        const totalAmountDue = calculateTotalAmountDue(selectedMemberId);
        
        
        const enteredAmount = parseFloat(inputElement.value);
        

        if (isNaN(amount)) {
            inputElement.value= 0;
    } else if (amount <= 0) {
        alert("Amount must be greater than zero.");
    } else {
        // Check the member type and perform specific validation
        var memberType = getMemberType(selectedMemberId);
        if (memberType === 'Student' && amount > 1200) {
            showErrorModal("Amount exceeds the maximum allowed for students.");
            inputElement.value=0;  
        } else if (memberType === 'Working' && amount > 2400) {
            showErrorModal("Amount exceeds the maximum allowed for working members.");
            inpinputElementut.value= 0;
        } 
    }

        
    }


}
</script>

<script>

$(document).ready(function () {
    


    $("td.text-right").click(function () {


        var cellContent = $(this).text(); // Use .text() to get the text content of the clicked cell
        var id = $(this).data("cell-id");
        var month = $(this).data("cell-month");
        var type = $(this).data("cell-type");
        var transac_id = $(this).data("cell-transacid");
        var oldReceipt = $(this).data("data-cell-oldReceipt");
        var receiptImageElement = document.getElementById('receiptImage');
        var membertransacid = $(this).data("cell-membertransacid");
     
        $.ajax({
    url: 'sqlGetData/getDuesDetail.php',
    method: 'GET',
    data: {
        id: id,
        month: month,
        transac_id: transac_id,
    },
    success: function (data) {
    console.log(data);
    data = JSON.parse(data); // Parse the JSON response

    var idMember = id;
    var memberId = $("#memberDropdown").val(); // Get selected member ID
    var memberName = $("#memberDropdown option:selected").data("member-name"); // Get selected member name
    var transactionMonth = data.month;
    var transactionAmount = parseFloat(data.amount);
    var receiptData = data.receipt;

    $("#id").val(idMember);
    $("#id").val(idMember);
    $("#idMember").val(memberName);
    $("#monthsSelected").val(transactionMonth);
    $("#dues").val(isNaN(transactionAmount.toFixed(2)) ? "" : transactionAmount.toFixed(2));
    $("#transacIdPayment").val(transac_id);

    $("#membertransacid").val(membertransacid);

    // Extract the file name from the receiptData
    var fileName = receiptData.split("/").pop();
    
    if (fileName) {
        // If there is a filename, set the src attribute of the img element to the receipt URL
        $("#receiptImage").attr("src", "CRUD/create/uploadsCashin/" + fileName);
        $("#receiptImage").show(); // Show the image
    } else {
        // If there is no filename, hide the image
        $("#receiptImage").hide();
    }

    $("#editTransactionModal").modal("show");
},

    error: function () {
        alert("Error fetching data from the server.");
    }
});



var receiptImageElement = document.getElementById('receiptImage');
document.getElementById('newReceipt').addEventListener('change', function() {

    
    var fileInput = this;
    var file = fileInput.files[0];

    if (file) {
        
      var filename = file.name;
      var fileId = transac_id; // You'll need to define transac_id or replace it with an appropriate value
    
      // Assuming you want to display the selected image
      var reader = new FileReader();
      reader.onload = function(e) {
        
        receiptImageElement.src = e.target.result;
        receiptImageElement.style.display = "block"; // Show the image
      };
      reader.readAsDataURL(file);

    } else {
      // Handle the case when no file is selected
      receiptImageElement.style.display = "none";
    }

    
  });

    });
});

</script>
<script>

function showErrorModal(errorMessage) { 
      var errorModal = new bootstrap.Modal(document.getElementById("errorModal"));
      var errorMessageElement = document.querySelector("#errorModal .modal-body p");
      errorMessageElement.textContent = errorMessage;
      errorModal.show();
      var closeButton = document.querySelector("#errorModal .btn-secondary");
       closeButton.addEventListener("click", closeModal);
      }
  $(document).ready(function() {
    
    $('#dues').on('input', function() {
        $('.currency-symbol').hide(); // Hide the currency symbol
        $('.format-control').hide(); // Hide the currency symbol
    });
  });
</script>
<script>
    $(document).ready(function () {
        // When the button is clicked, show the modal with the ID "updateMemberModal"
        $("#closeEdit").click(function () {
      
            $("#addTransactionModal").modal("hide");

        });
        
    });
</script>
<script>

    function closeModal(){
        $("#editTransactionModal").modal('hide');
    }
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
<script>
    document.getElementById('close-modal').addEventListener('click', function() {
    // Trigger the Bootstrap modal method to close the modal
    $('#errorModal').modal('hide');
});
</script>
<script>
            function removeNonNumericCharacters(inputString) {
              return inputString.replace(/[^\d.-]/g, '');
            }
</script>

</html>