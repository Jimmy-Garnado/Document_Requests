<?php
  session_start();
  include_once("api/connection.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>In-Processing Requests - <?php echo $CONTENT['system_name']; ?></title>
  <?php include("static-loader.php"); ?>
</head>
<body>
  <main class="container-fluid d-flex flex-lg-row flex-column p-0">
    <?php include("../reusables/admin-sidebar.php"); ?>
    
    <div class="col-12 col-lg-10 p-2 p-lg-4">
      <h4 class="mb-4 fw-bold">IN-PROCESSING</h4>
      <div class="table-responsive">
        <table id="example" class="display table" style="width:100%">
          <thead>
            <tr>
              <th>Request ID</th>
              <th>Client</th>
              <th>Document Type</th>
              <th>Date Requested</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
        </table>
      </div>
      
    </div>
  </main>
  <script>
    $(document).on("click", '.releaseButton', function(){
      let target = $(this).attr('data-target');
      Swal.fire({
        title: "Complete Request",
        text: "Are you sure you want to complete and release this request?",
        icon: "info",
        showCancelButton: false,
        confirmButtonText: "Complete Request"
      }).then((result) => {
        if (result.isConfirmed) {
          toggleLoadingModal()
          $.ajax({
            type: 'post',
            url: 'api/post_complete_request.php',
            data: {
              requestid : target
            },
            success: response => {
              let json = JSON.parse(response)
              toggleLoadingModal()

              Swal.fire({
                title: json.message,
                text: json.description,
                icon: json.status,
                showCancelButton: false,
                confirmButtonText: "Reload List"
              }).then((result) => {
                if (result.isConfirmed) {
                  location.href = 'processing.php'
                }
              });
            }
          })
        }
      });
    })

    $(document).ready(function(){
      $('#example').DataTable({
        ajax: 'api/get_all_processing.php',
        columns: [
          { data: 'request_id', title: 'Request ID' },
          { data: 'client_name', title: 'Client' },
          { data: 'document_type', title: 'Document Type' },
          { data: 'request_date', title: 'Date Requested' },
          { data: 'status', title: 'Status' },
          { 
            data: 'request_id',
            title: 'Action',
            render: function(data, type, row) {
              if(row.payment_status === "0"){
                return `
                  <a class='btn btn-sm btn-primary' href='view.php?request_id=${row.request_id}'>View</a>
                  <button class='btn btn-sm btn-danger' disabled>UNPAID</button>
                `;
              }else {
                return `
                  <a class='btn btn-sm btn-primary' href='view.php?request_id=${row.request_id}'>View</a>
                  <button class='btn btn-sm btn-success releaseButton' data-target='${row.request_id}'>Release</button>
                `;
              }
            }
          }
        ]
      });
    })
  </script>
</body>
</html>