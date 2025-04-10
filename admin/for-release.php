<?php
session_start();
include_once("api/connection.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>For Releasing Requests - <?php echo $CONTENT['system_name']; ?></title>
  <?php include("static-loader.php"); ?>

  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js'></script>
  <!-- <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/main.min.css' rel='stylesheet' /> -->
  <style>
    .fc-col-header-cell-cushion {
      text-decoration: none !important;
      font-weight: 600 !important;
      color: #000 !important;
    }

    .fc-day-disabled {
      background-color: rgb(212, 212, 212) !important;
      pointer-events: none;
      opacity: 0.25;
    }
  </style>
</head>

<body>
  <main class="container-fluid d-flex flex-lg-row flex-column p-0">
    <?php include("../reusables/admin-sidebar.php"); ?>
    <div class="col-12 col-lg-10 p-2 p-lg-4">
      <h4 class="mb-4 fw-bold">For Release</h4>
      <div class="table-responsive">
        <table id="example" class="display table" style="width:100%">
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

    $(document).on("click", ".releaseButton", function () {
      let requestId = $(this).data("request-id");

      $("#request_id").val(requestId);

      $("#releaseModal").modal("toggle");
    });

    $("#releaseForm").on("submit", function (event) {
      event.preventDefault();

      var formdata = new FormData(this);

      $.ajax({
        type: "post",
        url: "api/schedule_release.php",
        data: formdata,
        processData: false, // Don't process data, because FormData will handle it
        contentType: false, // Don't set content type, because FormData will handle it
        beforeSend: () => {
          $("#releaseModal").modal('toggle')
          toggleLoadingModal()
        },
        success: function (response) {
          const json = JSON.parse(response);

          if (json.status === "success") {
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: json.message,
            }).then(() => {
              location.reload();
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: jsonResponse.message || 'An error occurred while scheduling the release.',
            });
          }

          toggleLoadingModal()
        }
      })
    })

    $(document).ready(function () {
      $('#example').DataTable({
        ajax: 'api/get_all_for_release.php',
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