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
  <div class="modal fade" id="reasonmodal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="fw-semibold mb-2">SELECT REASON</h5>
        </div>
        <form id="rejectForm">
          <div class="modal-body">
              <div class="form-group d-flex flex-column 4">
                <input type="hidden" name="request_id" /> 
                <select name="reason" class="form-control">
                  <option value="Invalid Information">Invalid Information</option>
                  <option value="Student didn't exist">Student didn't exist</option>
                  <option value="Unsettled Balance">Unsettled Balance</option>
                </select>
              </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-danger">Confirm Rejection</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <main class="container-fluid d-flex flex-row p-0">
    <?php include("../reusables/admin-sidebar.php"); ?>
    
    <div class="col-10 p-4">
      <h4 class="mb-4 fw-bold">REQUEST</h4>
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
  </main>
  <script>
    $(document).on("click", '.approveButton', function(){
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
              requestid : target
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

    $("#rejectForm").on("submit", function(e){
      e.preventDefault();

      let request_id = $("input[name='request_id']").val();
      let reason = $("select[name='reason']").val();

      Swal.fire({
        title: "Confirm Reject",
        text: "Are you sure you want to reject this request?",
        icon: "info",
        showCancelButton: false,
        confirmButtonText: "Reject Request"
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            type: 'post',
            url: 'api/post_reject_request.php',
            data: {
              requestid : request_id,
              reason: reason
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

    $(document).on("click", '.rejectButton', function(){
      let target = $(this).attr('data-target');
      $("input[name='request_id']").val(target)
      
    })

    $(document).ready(function(){
      $('#example').DataTable({
        ajax: 'api/get_all_request.php',
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
                <button class='btn btn-sm btn-success approveButton' data-target='${data}'>Approve</button>
                <button data-bs-toggle="modal" data-bs-target="#reasonmodal" class='btn btn-sm btn-danger rejectButton' data-target='${data}'>Reject</button>
              `;
            }
          }
        ]
      });
    })
  </script>
</body>
</html>