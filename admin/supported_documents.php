<?php
session_start();
include("api/connection.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Supported Documents - BPC E-Registrar</title>
  <?php include("static-loader.php"); ?>
</head>

<body>
  <div class="modal fade" id="createdocumentmodal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-bold">Add New Document Type</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="createdocumentform">
          <div class="modal-body">
            <div class="form-group mb-4">
              <label class="form-label fw-semibold">Document Name</label>
              <input type="text" name="name" class="form-control" required placeholder="Good Moral" />
            </div>
            <div class="form-group mb-4">
              <label class="form-label fw-semibold">Processing Time in Days</label>
              <select class="form-control" name="processing_time" required>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label fw-semibold">Price</label>
              <input type="number" name="price" value="100" min="0" class="form-control" required />
            </div>
          </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Add Document Type</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="editdocumentmodal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-bold">Edit Document</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="editdocumentform">
          <div class="modal-body">

          </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Update Document</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <main class="container-fluid d-flex flex-row p-0">
    <?php include("../reusables/admin-sidebar.php"); ?>

    <div class="col-12 col-lg-10 p-4">
      <div class="d-flex flex-row justify-content-between align-items-center">
        <h4 class="mb-4 fw-bold">Supported Document Type</h4>
        <button data-bs-toggle="modal" data-bs-target="#createdocumentmodal" class="btn btn-primary">Add Document
          Type</button>
      </div>
      <table id="example" class="table table-responsive">
        <thead>
          <tr>
            <th>Document ID</th>
            <th>Document Name</th>
            <th>Processing Time (Days)</th>
            <th>Price</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>
    </div>
  </main>
  <script>
    $("#createdocumentform").submit(function (e) {
      e.preventDefault();

      var formdata = new FormData(this)

      $.ajax({
        type: 'post',
        url: 'api/post_add_document_type.php',
        data: formdata,
        contentType: false,
        processData: false,
        success: response => {
          if (response.trim() === "ok") {
            Swal.fire({
              title: 'New Document Type Added!',
              text: 'The supported documents was updated',
              icon: 'success',
              confirmButtonText: 'Reload Page'
            }).then((result) => {
              if (result.isConfirmed) {
                location.reload();
              }
            });
          } else {
            Swal.fire({
              title: 'Error',
              text: response,
              icon: 'error',
              confirmButtonText: 'OK'
            });
          }
        }
      })
    })

    $("#editdocumentform").submit(function (e) {
      e.preventDefault();

      var formdata = new FormData(this)

      $.ajax({
        type: 'post',
        url: 'api/post_edit_document_type.php',
        data: formdata,
        contentType: false,
        processData: false,
        success: response => {
          if (response.trim() === "ok") {
            Swal.fire({
              title: 'Document Type Updated!',
              text: 'The supported documents was updated',
              icon: 'success',
              confirmButtonText: 'Reload Page'
            }).then((result) => {
              if (result.isConfirmed) {
                location.reload();
              }
            });
          } else {
            Swal.fire({
              title: 'Error',
              text: response,
              icon: 'error',
              confirmButtonText: 'OK'
            });
          }
        }
      })
    })

    $(document).on("click", "#removedocumentbtn", function () {
      let data_target = $(this).attr('data-target')

      Swal.fire({
        title: 'Are you sure?',
        text: "Do you really want to delete this document?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            type: 'POST',
            url: 'api/post_delete_document_type.php',
            data: {
              document_id: data_target
            },
            success: response => {
              Swal.fire(
                'Deleted!',
                'The document has been deleted.',
                'success'
              ).then(() => {
                location.reload(); // Optional: reload the page or update the UI
              })
            },
            error: () => {
              Swal.fire(
                'Error!',
                'There was a problem deleting the document.',
                'error'
              )
            }
          });
        }
      });
    });

    $(document).on("click", "#editdocumentbtn", function () {
      let data_target = $(this).attr('data-target')

      $.ajax({
        type: 'get',
        url: 'api/get_edit_document_details.php',
        data: {
          document_id: data_target
        },
        success: response => {
          $("#editdocumentmodal").modal("toggle")
          $("#editdocumentform > .modal-body").html(response)
        }
      })
    })

    $(document).ready(function () {
      $('#example').DataTable({
        ajax: 'api/get_all_supported_documents.php',
        columns: [
          { data: 'id', title: 'Document ID' },
          { data: 'name', title: 'Document Name' },
          { data: 'processing_time', title: 'Processing Time (Days)' },
          { data: 'price', title: 'Price' },
          {
            data: 'is_active', title: 'Is Active?', render: function (data, type, row) {
              console.log(row.is_active)
              if (row.is_active === "1") {
                return `
                  <span class='badge text-bg-success'>ACTIVE</span>
                `
              } else {
                return `
                  <span class='badge text-bg-danger'>INACTIVE</span>
                `
              }
            }
          },
          {
            data: 'id', title: 'Action', render: function (data) {
              return `
                <button data-target='${data}' id='editdocumentbtn' class='btn btn-warning btn-sm'>Edit</button>
                <button data-target='${data}' id='removedocumentbtn' class='btn btn-danger btn-sm'>Remove</button>
              `
            }
          },
        ]
      });
    })
  </script>
</body>

</html>