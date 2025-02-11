<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - BPC E-Registrar</title>
  <?php include("static-loader.php"); ?>
</head>
<body>
  <style>
    .chart-container {
      width: 50%;
      margin: 0 auto;
    }
  </style>

  <main class="container-fluid d-flex flex-row p-0">
    <?php include("../reusables/admin-sidebar.php"); ?>
    
    <div class="col-10 p-4">
      <h2 class="mb-4">Welcome to BPC E-Registrar 2024</h2>
      <div class="row">
        <div class="col-6 p-2">
          <div class="card">
            <div class="card-header">
              <div class="d-flex flex-row justify-content-between">
                <h4>Total Request</h4>
                <a href="request.php" class="btn btn-link btn-sm">See More</a>
              </div>
            </div>
            <div class="card-body">
              <table id="requests" class="display table" style="width:100%">
                <thead>
                  <tr>
                    <th>Request ID</th>
                    <th>Document Type</th>
                    <th>Date Requested</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
        <div class="col-6 p-2">
          <div class="card">
            <div class="card-header">
              <div class="d-flex flex-row justify-content-between">
                <h4>Total Archives</h4>
                <a href="request.php" class="btn btn-link btn-sm">See More</a>
              </div>
            </div>
            <div class="card-body">
              <table id="archives" class="display table" style="width:100%">
                <thead>
                  <tr>
                    <th>Request ID</th>
                    <th>Document Type</th>
                    <th>Date Requested</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
      <h4 class="mb-4 mt-4">Statistics</h4>
      <div class="row">
        <div class="col-4">
          <h5>Per Status</h5>
          <canvas id="requestchart"></canvas>
        </div>
        <div class="col-4">
          <h5>Per Document Type</h5>
          <canvas id="documentchart"></canvas>
        </div>
      </div>
    </div>
  </main>

  <script>
    $(document).ready(function(){
      // Initialize the Pie Chart
      const requestchart = new Chart(document.getElementById('requestchart').getContext('2d'), {
        type: 'pie',
          data: {
            labels: ['Pending', 'In-Process', 'Cancelled', 'Rejected', 'Completed'],
            datasets: [{
              data: [20, 20, 20, 20, 20],
              backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
              hoverBackgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
            }]
          },
          options: {
            responsive: true,
            plugins: {
              legend: {
                position: 'bottom',
              }
            }
          }
      });

      const archivechart = new Chart( document.getElementById('documentchart').getContext('2d'), {
        type: 'pie',
          data: {
            labels: ['Transcript of Records', 'Good Moral', 'Diploma', 'Student ID', 'Copy Of Grades'],
            datasets: [{
              data: [20, 20, 20, 20, 20],
              backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
              hoverBackgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
            }]
          },
          options: {
            responsive: true,
            plugins: {
              legend: {
                position: 'bottom',
              }
            }
          }
      });
    })

    $('#requests').DataTable({
      ajax: 'api/get_all_request.php',
      columns: [
        { data: 'request_id', title: 'Request ID' },
        { data: 'client_name', title: 'Client' },
        { data: 'document_type', title: 'Document Type' },
      ]
    });

    $('#archives').DataTable({
      ajax: 'api/get_all_archived.php',
      columns: [
        { data: 'request_id', title: 'Request ID' },
        { data: 'client_name', title: 'Client' },
        { data: 'document_type', title: 'Document Type' },
      ]
    });
  </script>
</body>
</html>