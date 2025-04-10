<?php
  session_start();
  include_once("api/connection.php");

  if($_SESSION['staffrole'] != "Cashier"){
    header("location: api/logout.php");
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cashier - <?php echo $CONTENT['system_name']; ?></title>
  <?php include("static-loader.php"); ?>
</head>
<body>
  <main class="container-fluid d-flex flex-row p-0">
    <?php include("../reusables/admin-sidebar.php"); ?>
    
    <div class="col-10 p-4">
      <h4 class="mb-4 fw-bold">Cashier</h4>
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
    $(document).on("click", '.pay-button', function(){
      let target = $(this).attr('data-target');
      Swal.fire({
        title: "Confirm Payment",
        // text: "Are you sure you want to approve this request?",
        icon: "info",
        showCancelButton: false,
        confirmButtonText: "Confirm Payment"
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            type: 'post',
            url: 'api/post_update_request_payment.php',
            data: {
              requestid : target
            },
            success: response => {
              if(response === "ok"){
                location.href = 'cashier.php'
              }
            }
          })
        }
      });
    })

    $(document).ready(function(){
      $('#example').DataTable({
        ajax: 'api/get_all_cashier_request.php',
        columns: [
          {
            data: 'request_id',
            title: 'Request ID',
            render: function(data, type, row) {
              return '<a href="view.php?request_id=' + data + '">' + data + '</a>';
            }
          },
          { data: 'client_name', title: 'Client' },
          {
            data: 'date_created',
            title: 'Date Requested',
            render: function(data) {
              return moment(data).format("MMMM D, YYYY h:mmA");
            }
          },
          { data: 'price', title: 'Amount' },
          { data: 'payment_status', title: 'Status', render: function(data, type, row){
            if(data == 0){
              return `
                <span class='badge bg-danger'>UNPAID</span>
              `
            }else {
              return `
                <span class='badge bg-success'>PAID</span>
              `
            }
          } }
        ]
      });
    })
  </script>
</body>
</html>