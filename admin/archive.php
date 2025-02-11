<?php
  session_start();
  include("api/connection.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Archived Requests - BPC E-Registrar</title>
  <?php include("static-loader.php"); ?>
</head>
<body>

  <main class="container-fluid d-flex flex-row p-0">
    <?php include("../reusables/admin-sidebar.php"); ?>
    
    <div class="col-12 col-lg-10 p-4">
      <h4 class="mb-4 fw-bold">ARCHIVES</h4>
      <table id="example" class="table table-responsive">
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
  </main>
  <script>
    $(document).ready(function(){
      $('#example').DataTable({
          ajax: 'api/get_all_archived.php',
          columns: [
            { data: 'request_id', title: 'Request ID' },
            { data: 'client_name', title: 'Client' },
            { data: 'document_type', title: 'Document Type' },
            { data: 'request_date', title: 'Date Requested' },
            { data: 'status', title: 'Status' },
            { 
              data: 'request_id',
              title: 'Action',
              render: function(data) {
                return `
                  <a class='btn btn-sm btn-primary' href='view.php?request_id=${data}'>View</a>
                `;
              }
            }
          ]
      });
    })
  </script>
</body>
</html>