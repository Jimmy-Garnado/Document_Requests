<?php
  session_start();
  include_once("api/connection.php");

  if($_SESSION['staffrole'] != "Admin"){
    header("location: dashboard.php");
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Students - <?php echo $CONTENT['system_name']; ?></title>
  <?php include("static-loader.php"); ?>
</head>
<body>
  <div class="modal fade" id="createstudentmodal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">ADD STUDENT</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="singlestudentform">
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label fw-bold">Student ID</label>
              <input type="text" name="a_stuid" class="form-control" placeholder="00001243" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-bold">Name</label>
              <input type="text" name="a_stuname" class="form-control" placeholder="Juan Dela Cruz" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-bold">Email</label>
              <input type="email" name="a_stuemail" class="form-control" placeholder="juandelacruz@gmail.com" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">ADD STUDENT</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="editstudentodal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Student</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="edistudentform">
          <div class="modal-body">

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">SAVE CHANGES</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="createbatchstudentmodal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-bold">IMPORT STUDENT BATCH</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="batchstudentform">
          <div class="modal-body">
            <div class="mb-3">
              <a class="btn btn-primary btn-sm" href="spreadsheets/template_for_batch_student.xlsx" download>Download Template</a>
            </div>
            <div class="mb-3">
              <label for="batchstaff" class="form-label fw-bold">Upload file</label>
              <input type="file" name="batchstudent" class="form-control" id="batchstaff" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">RUN IMPORT</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <main class="container-fluid d-flex flex-row p-0">
    <?php include("../reusables/admin-sidebar.php"); ?>
    
    <div class="col-10 p-4">
      <div class="row">
        <div class="col-9">
          <h4 class="fw-bold mb-4">STUDENT MANAGEMENT</h4>
          <table id="students" class="table table-responsive">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Password</th>
                <th>Is Deleted?</th>
                <th>Action</th>
              </tr>
            </thead>
          </table>
        </div>
        <div class="col-3 card">
          <div class="card-body">
            <div class="row">
              <div class="col-12 d-flex flex-column gap-2">
                <h6 class="mb-2 fw-bold">MANAGE</h6>
                <button type="button" data-bs-toggle="modal" data-bs-target="#createstudentmodal" class="btn btn-primary">Single</button>
                <button type="button" data-bs-toggle="modal" data-bs-target="#createbatchstudentmodal" class="btn btn-primary">Import</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <script>
    function editStudent(stuid){
      $.ajax({
        type: 'get',
        url: 'api/get_edit_student_details.php',
        data: {
          stuid : stuid
        },
        success: response => {
          $("#editstudentodal").modal("toggle")

          $("#edistudentform > .modal-body").html(response)
        }
      })
    }

    function deleteStudent(stuid){


      Swal.fire({
        title: "Do you want to delete the student",
        icon: "info",
        showDenyButton: false,
        showCancelButton: true,
        confirmButtonText: "Yes, Delete It",
        denyButtonText: `Don't save`
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            type: 'post',
            url: 'api/post_delete_student.php',
            data: {
              id : stuid
            },
            success: response => {
              if(response === 'ok'){
                alert('Student deleted!')
                location.reload();
              }
            }
          })
        }
      });
    }

    $("#edistudentform").submit(function(event){
      $("#editstudentodal").modal('toggle')
      toggleLoadingModal();

      event.preventDefault();

      const formData = new FormData(this);

      $.ajax({
        url: 'api/post_edit_student.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
          toggleLoadingModal();

          if (response.trim() === "ok") { 
            Swal.fire({
              title: 'Student Updated successfully!',
              text: 'Student information has been updated.',
              icon: 'success',
              confirmButtonText: 'Refresh Table'
            }).then((result) => {
              if (result.isConfirmed) {
                location.reload();
              }
            });
          } else {
            toggleLoadingModal();

            $("#editstudentodal").modal('toggle')

            Swal.fire({
              title: 'Error',
              text: response,
              icon: 'error',
              confirmButtonText: 'OK'
            });
          }
        },
        error: function(xhr, status, error) {
          Swal.fire({
            title: 'An error occurred',
            text: 'There was an issue submitting the form. Please try again.',
            icon: 'error',
            confirmButtonText: 'OK'
          });
        }
      });
    })

    $("#batchstudentform").submit(function(event){
      $("#createbatchstudentmodal").modal('toggle')
      toggleLoadingModal();
      event.preventDefault();

      var formData = new FormData(this);

      $.ajax({
        url: 'api/post_batch_student.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
          toggleLoadingModal();

          if (response.trim() === "ok") { 
            Swal.fire({
              title: 'Students List Added Successfully',
              text: 'New students has been added.',
              icon: 'success',
              confirmButtonText: 'Refresh Table'
            }).then((result) => {
              if (result.isConfirmed) {
                location.reload();
              }
            });
          } else {
            $("#createbatchstudentmodal").modal('toggle')

            Swal.fire({
              title: 'Error',
              text: response,
              icon: 'error',
              confirmButtonText: 'OK'
            });
          }
        },
        error: function(xhr, status, error) {
          Swal.fire({
            title: 'An error occurred',
            text: 'There was an issue submitting the form. Please try again.',
            icon: 'error',
            confirmButtonText: 'OK'
          });
        }
      });
    })
    
    $(document).on('submit', "#singlestudentform", function(event){
      $("#createstudentmodal").modal("toggle")
      toggleLoadingModal();
      event.preventDefault();

      var formData = new FormData(this)

      $.ajax({
        type: 'POST',
        url: 'api/post_add_single_student.php',
        data: formData,
        processData: false,
        contentType: false,
        success: response => {
          toggleLoadingModal();
          if (response.trim() === "ok") { 
            Swal.fire({
              title: 'Student Added Successfully!',
              icon: 'success',
              confirmButtonText: 'Refresh Table'
            }).then((result) => {
              if (result.isConfirmed) {
                location.reload();
              }
            });
          } else {
            toggleLoadingModal()
            $("#createstudentmodal").modal("toggle")
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

    $(document).ready(function(){
      $('#students').DataTable({
          ajax: 'api/get-all.php?table=users',
          columns: [
            { data: 'stuid', title: 'ID' },
            { data: 'stuname', title: 'Name' },
            { data: 'stuemail', title: 'Email' },
            { data: 'stupassword', title: 'Password' },
            { data: 'is_deleted', title: 'Is Deleted?', render: function(data){
              if(data == false){
                return `<span class='badge text-bg-success'>Active</span>`
              }else {
                return `<span class='badge text-bg-danger'>Inactive</span>`
              }
            } },
            { 
              data: 'id',
              title: 'Action',
              render: function(data) {
                return `
                  <button onclick="editStudent(${data})" class='btn btn-sm btn-primary mr-4'>Edit</button>
                  <button onclick="deleteStudent(${data})" class='btn btn-sm btn-danger'>Delete</button>
                `;
              }
            }
          ]
      });
    })
  </script>
</body>
</html>