<?php
session_start();
include_once("api/connection.php");

if ($_SESSION['staffrole'] != "Cashier") {
  header("location: api/logout.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Logs - <?php echo $CONTENT['system_name']; ?></title>
  <?php include("static-loader.php"); ?>
</head>

<body>
  <main class="container-fluid d-flex flex-row p-0">
    <?php include("../reusables/admin-sidebar.php"); ?>

    <div class="col-10 p-4">
      <h4 class="mb-4 fw-bold">Payment Logs</h4>
      <table id="example" class="display table" style="width:100%">
        <thead>
          <tr>
            <th>Request ID</th>
            <th>Client</th>
            <th>Date Requested</th>
            <th>Amount</th>
            <th>Status</th>
          </tr>
        </thead>
      </table>
    </div>
  </main>

  <script>
    $(document).ready(function() {
      $('#example').DataTable({
        ajax: 'api/get_all_payment_logs.php',
        columns: [{
            data: 'request_id',
            title: 'Request ID',
            render: function(data, type, row) {
              return '<a href="view.php?request_id=' + data + '">' + data + '</a>';
            }
          },
          {
            data: 'client'
          },
          {
            data: 'payment_date'
          },
          {
            data: 'amount'
          },
          {
            data: 'status'
          },
        ]
      });
    });
  </script>
</body>

</html>