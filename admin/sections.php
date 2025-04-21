<?php
  session_start();
  include("api/connection.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sections - BPC E-Registrar</title>
  <?php include("static-loader.php"); ?>
</head>
<body>
  <div class="modal fade" id="createdocumentmodal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-bold">Add New Section</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="AddSectionForm">
          <div class="modal-body">
            <div class="form-group">
              <label class="form-label fw-semibold">Section Name/Code</label>
              <input type="text" name="name" class="form-control" required placeholder="Good Moral"/>
            </div>
          </div>
          
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Add Section</button>
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
      <h4 class="mb-4 fw-bold">Section List</h4>
      <button data-bs-toggle="modal" data-bs-target="#createdocumentmodal" class="btn btn-primary">Add Section</button>
      </div>
      <table id="example" class="table table-responsive">
        <thead>
          <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>
    </div>
  </main>
  <script>
    $("#AddSectionForm").submit(function(e){
      e.preventDefault();

      var formdata = new FormData(this)

      $.ajax({
        type: 'post',
        url: 'api/post_add_section.php',
        data: formdata,
        contentType: false,
        processData: false, 
        success: response => {
          if (response.trim() === "ok") { 
            Swal.fire({
              title: 'New Section Added!',
              text: 'Section list updated',
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
            url: 'api/post_delete_section.php',
            data: {
              section_id: data_target
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

    $(document).ready(function(){
      $('#example').DataTable({
          ajax: 'api/get_all_sections.php',
          columns: [
            { data: 'id', title: 'Id' },
            { data: 'name', title: 'Name' },
            { data: 'id', title: 'Action', render: function(data){
              return `
                <button data-target='${data}' id='removedocumentbtn' class='btn btn-danger btn-sm'>Remove</button>
              `
            } },
          ]
      });
    })
  </script>
</body>
</html>