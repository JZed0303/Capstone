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
   $query = "SELECT cm.email, cm.name as member_name, md.month, md.amount, ci.receipt, cm.type, cm.member_id as id, md.month, md.year, md.transac_id
   FROM club_members_tbl AS cm
   LEFT JOIN monthly_dues_tbl AS md ON cm.member_id = md.member_id
   LEFT JOIN cashinflow_tbl AS ci ON md.transac_id = ci.id  
      GROUP BY email;
   WHERE cm.email = '$email'";
   
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
    }td{
       border:none!important;
      */
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
    }       .gauge-container {
             display: flex;
             justify-content: center;
             align-items: center;
             height: 300px;
         }
    
    
</style>
</style>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>My Monthly Dues</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description"> <!-- css content -->
    <link rel="stylesheet" href="assets/fontawesome-free-6.3.0-web/css/all.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/budgetManagement.css"> <!-- DATA TABLES  cs-->
    <link rel="stylesheet" href="assets/datatable/css/datatables.min.css">
    <link rel="stylesheet" href="assets/datatable/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/datatable/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="assets/css/cashinflow/clubdues.css">
    <link rel="stylesheet" href="assets/datatable/js/cdn.datatables.net_buttons_2.4.1_css_buttons.dataTables.min.css"> <!-- media Print -->
    <!-- DATA TABLE JS -->
    <script src="assets/datatable/js/jquery-3.6.0.min.js"></script>
    <script src="assets/datatable/css/bootstrap.bundle.min.js"></script>
    <script src="assets/datatable/js/datatables.min.js"></script>
    <script src="assets/datatable/js/vfs_fonts.js"></script> <!-- DATA TABLES DATE FILTER -->
    <script src="assets/datatable/js/moment.min.js"></script>
    <script src="assets/datatable/js/clubDues.js"></script> <!-- general -->
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/moment.min.js"></script>
    <script src="assets/js/select2.min.js"></script> <!-- <script src="assets/js/script.js"></script> -->
    <!-- fixed header -->
    <!-- Google Web Fonts -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet"> <!-- general -->
</head> <!-- START BODY -->

<body>
    <div class="container-fluid position-relative d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-primary position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-white" style="width: 3rem; height: 3rem;" role="status"> <span class="sr-only">Loading...</span> </div>
        </div> <!-- Spinner End -->
        <!-- Sidebar Start --> <?php 
         include 'template/all_sidebar.php';
         
         ?>
        <!-- Sidebar End -->
        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start --> <?php include 'template/navbar.php'?> <?php      include 'template/modal.php'?>
            <!-- Navbar End -->
            <!-- Sale & Revenue Start -->
            <div class="container-fluid wrapper p-2">
                <div class="row g-2 mx-1 p-4 mb-3">
                    <div class="col-6 ">
                        <div class="row"> 
                            <div class="dataTable_wrapper income col-12 pt-3 px-0 pb-0">
                                <div class="bg-white rounded h-100  mb-0 pb-0">
                                    <div class="table-responsive">
                                        <table id="" class="table table-hover bg-white shadow-lg no-vertical-borders" style="border-radius: 10px 10px 0 0!important;">
                                            <thead>
                                                <tr>
                                                    <th>Month</th>
                                                    <th>Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody> <?php
                                                                $currentYear = date('Y'); // Get the current year
                                                                $currentMonth = date('n'); // Get the current month (numeric representation)

                                                                // If the current month is December, set the start month to January of the next year, else set it to July of the current year
                                                                $startMonth = ($currentMonth == 12) ? 1 : 7;
                                                                $startYear = ($currentMonth == 12) ? $currentYear + 1 : $currentYear;

                                                                $monthNames = [
                                                                    "January", "February", "March", "April", "May", "June",
                                                                    "July", "August", "September", "October", "November", "December"
                                                                ];

                                                                $totalAmount = 0; // Initialize total amount


                                                                $maxAmount = ($duesData[0]['type'] == 'student') ? 1200 : 2400;

                                                                // Calculate the total paid amount
                                                                $totalPaidAmount = 0;
                                                                foreach ($duesData as $data) {
                                                                    $totalPaidAmount += $data['amount'];
                                                                }

                                                                // Calculate the percentage of the total paid amount relative to the maximum allowed amount
                                                                $percentage = ($totalPaidAmount / $maxAmount) * 100;

                                                                // Assuming $duesData contains data for only one member
                                                                $memberData = $duesData[0];

                                                                for ($i = $startMonth; $i < $startMonth + 12; $i++) {
                                                                    $monthTotalPayment = 0; // Initialize total payment for this month

                                                                    // Calculate total payment for this month
                                                                    if ($memberData['month'] == $i) {
                                                                        $monthTotalPayment = $memberData['amount'];
                                                                    }

                                                                    // Add the monthly total payment to the total amount
                                                                    $totalAmount += $monthTotalPayment;

                                                                    // Output member name, month, amount, total payment, amount due, and balance vertically
                                                                    echo '<tr>';
                                                                    echo '<td>' . $monthNames[($i - 1) % 12] . ' ' . $startYear . '</td>';
                                                                    echo '<td>' . formatValuePeso($monthTotalPayment) . '</td>';
                                                                    echo '</tr>';

                                                                    // Increment the year when the loop reaches January
                                                                    if ($i % 12 == 0) {
                                                                        $startYear++;
                                                                    }
                                                                }

                                                                // Display total amount under the "Amount" column
                                                                echo '<tr>';
                                                                echo '<td><strong>Total</strong></td>';
                                                                echo '<td><strong>' . formatValuePeso($totalAmount) . '</strong></td>';
                                                                echo '</tr>';
                                                                    ?>                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-2">
                    <div class="gauge-container">
                        <canvas id="gaugeChart" width="200" height="200"></canvas>
                      
                </div>
                    </div>
                    <div class="col-12  ">
                         <canvas id="lineChart" width="100" height="40"></canvas> 
                        </div>
                </div>
            </div>
            <div class="col-5 shadow-lg rounded-3 px-md-4 ">
            
            </div> 


</body> <!-- END BODY -->
<!-- Template Javascript -->
<script src="js/main.js"></script>
<script src="js/cdn.jsdelivr.net_npm_bootstrap@5.0.0_dist_js_bootstrap.bundle.min.js"></script> <?php
$chartData = array();
for ($i = $startMonth; $i < $startMonth + 12; $i++) {
    $monthTotalPayment = 0; // Initialize total payment for this month

    // Calculate total payment for this month
    if ($memberData['month'] == $i) {
        $monthTotalPayment = $memberData['amount'];
    }

    // Prepare data for the chart
    $chartData[] = array(
        "month" => $monthNames[($i - 1) % 12] . ' ' . $startYear,
        "amount" => $monthTotalPayment
    );

    // Increment the year when the loop reaches January
    if ($i % 12 == 0) {
        $startYear++;
    }
}
?> <script>
    $(document).ready(function () {
    
      var chartData = <?php echo json_encode($chartData); ?>;
    
    // Extract labels (months) and data (payment amounts) from the chart data
    var labels = chartData.map(function(item) {
      return item.month;
    });
    
    var data = chartData.map(function(item) {
      return item.amount;
    });
    
    // Get the canvas element and create a line chart
    var ctx = document.getElementById('lineChart').getContext('2d');
    var lineChart = new Chart(ctx, {
      type: 'line',
      data: {
          labels: labels,
          datasets: [{
              label: 'Monthly Payment',
              data: data,
              borderColor: 'rgb(75, 192, 192)', // Line color
              tension: 0.1 // Line curve tension (0 for straight lines)
          }]
      },
      options: {
          scales: {
              y: {
                  beginAtZero: true
              }
          }
      }
    });
    
    // Get the canvas element and context
    var ctx = document.getElementById('gaugeChart').getContext('2d');
    
    // Calculate the remaining percentage to fill the gauge
    var remainingPercentage = 100 - <?php echo $percentage; ?>;
    
    // Data for the gauge chart
    var data = {
      datasets: [{
          data: [<?php echo $percentage; ?>, remainingPercentage],
          backgroundColor: [
              'rgba(255, 223, 0, 0.2)', // Gold color with opacity for the blurred background
              'rgba(255, 223, 0, 1)' // Solid gold color for the filled portion
          ],
          borderColor: [
              'rgba(255, 223, 0, 1)', // Solid gold color for the border
              'rgba(255, 255, 255, 0)' // Transparent color for the border of the empty portion
          ],
          borderWidth: 2
      }]
    };
    
    // Options for the gauge chart
    var options = {
      circumference: Math.PI,
      rotation: -Math.PI,
      cutoutPercentage: 85,
      tooltips: {
          enabled: false
      }
    };
    
    // Create the gauge chart
    var gaugeChart = new Chart(ctx, {
      type: 'doughnut',
      data: data,
      options: options
    });
    
    
    });
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
<script src="lib/chart/chart.min.js"></script>

</html>