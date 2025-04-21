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

  <main class="container-fluid d-flex flex-lg-row flex-column p-0">
    <?php include("../reusables/admin-sidebar.php"); ?>
    
    <div class="col-12 col-lg-10 p-2 p-lg-4">
      <h4 class="mb-4 fw-bold">Archives</h4>
      <div class="table-responsive">
        <table id="example" class="table table-responsive">
          <thead>
            <tr>
              <th>Request ID</th>
              <th>Client</th>
              <th>Document Type</th>
              <th>Date Requested</th>
              <th>Status</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </main>
  <script>
    $(document).ready(function(){
      $('#example').DataTable({
          ajax: 'api/get-all.php?table=v2_requests&where=["Rejected", "Completed", "Cancelled"]',
          columns: [
            {
            data: 'request_id',
            title: 'Request ID',
            render: function(data, type, row) {
              return '<a href="view.php?request_id=' + data + '">' + data + '</a>';
            }
          },
            { data: 'name', title: 'Client' },
            {
          data: 'document_to_request',
          title: 'Document Type',
          render: function (data) {
            try {
              let parsedData = JSON.parse(data);
              return Array.isArray(parsedData) ? parsedData.join(', ') : data;
            } catch (e) {
              return data;
            }
          }
        },
        {
          data: 'date_created',
          title: 'Date Requested',
          render: function (data) {
            return moment(data).format("MMMM D, YYYY h:mmA");
          }
        },
            { data: 'status', title: 'Status' }
          ]
      });
    })
  </script>
</body>
</html>