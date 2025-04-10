<?php
session_start();
include_once("api/connection.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Request - <?php echo $CONTENT['system_name']; ?></title>
  <?php include("static-loader.php"); ?>
</head>

<body>
  <main class="container-fluid d-flex flex-lg-row flex-column p-0">
    <?php include("../reusables/admin-sidebar.php"); ?>

    <section class="col-12 col-lg-10 p-2 p-lg-4">
      <h4 class="mb-4 fw-bold">REQUEST</h4>
      <div class='table-responsive'>
        <table id="example" class="display table">
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

    </section>
  </main>
  <script>
    $(document).on("click", '.approveButton', function() {
      let target = $(this).attr('data-target');

      Swal.fire({
        title: "Confirm Approval",
        text: "Are you sure you want to approve this request?",
        icon: "info",
        showCancelButton: false,
        confirmButtonText: "Approve Request"
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            type: 'post',
            url: 'api/post_approve_request.php',
            data: {
              requestid: target
            },
            success: response => {
              let json = JSON.parse(response)

              Swal.fire({
                title: json.message,
                text: json.description,
                icon: json.status,
                showCancelButton: false,
                confirmButtonText: "Reload List"
              }).then((result) => {
                if (result.isConfirmed) {
                  location.href = 'request.php'
                }
              });
            }
          })
        }
      });
    })

    $(document).ready(function() {
      $('#example').DataTable({
        ajax: 'api/get_all_request.php',
        columns: [{
            data: 'request_id',
            title: 'Request ID',
            render: function(data, type, row) {
              return '<a href="view.php?request_id=' + data + '">' + data + '</a>';
            }
          },
          {
            data: 'client_name',
            title: 'Client'
          },
          {
            data: 'document_type',
            title: 'Document Type',
            render: function(data) {
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
            render: function(data) {
              return moment(data).format("MMMM D, YYYY h:mmA");
            }
          },
          {
            data: 'status',
            title: 'Status'
          }
        ]
      });
    })
  </script>
</body>

</html>