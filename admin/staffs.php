<?php
session_start();
include_once("api/connection.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff - <?php echo $CONTENT['system_name']; ?></title>
  <?php include("static-loader.php"); ?>
</head>

<body>
  <div class="modal fade" id="createstaffmodal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Staff (Single)</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="addsinglestaffform">
          <div class="modal-body">
            <div class="mb-3">
              <label for="addstaffname" class="form-label fw-bold">Name</label>
              <input type="text" name="addstaffname" class="form-control" id="staffname" placeholder="Juan Dela Cruz"
                required>
            </div>
            <div class="mb-3">
              <label for="addstaffrole" class="form-label fw-bold">Role</label>
              <select class="form-select" name="addstaffrole" id="addstaffrole" aria-label="Default select example"
                required>
                <option value="">Select Role</option>
                <option value="Admin">Admin</option>
                <option value="Staff">Staff</option>
                <option value="Cashier">Cashier</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="addstaffposition" class="form-label fw-bold">Position</label>
              <select class="form-select" name="addstaffposition" id="addstaffposition"
                aria-label="Default select example" required disabled>
                <option value="">Select Position</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">Add Staff</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="createbatchstaffmodal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-bold">ADD STAFF (BATCH)</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="addbatchstaffform">
          <div class="modal-body">
            <div class="mb-3">
              <a class="btn btn-primary btn-sm" href="spreadsheets/template_for_batch_staff.xlsx" download>Download
                Template</a>
            </div>
            <div class="mb-3">
              <label for="batchstaff" class="form-label fw-bold">Upload file</label>
              <input type="file" name="batchstaff" class="form-control" id="batchstaff" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">RUN BATCH</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="staffmodal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Staff</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="editstaffform">
          <div class="modal-body" id="staffmodalbody">
            <!-- Populated by API -->
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Update</button>
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
          <h4 class="mb-4 fw-bold">STAFF MANAGEMENT</h4>

          <table id="staffs" class="table table-responsive">
            <thead>
              <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Role</th>
                <th>Username</th>
                <th>Password</th>
                <th>Action</th>
              </tr>
            </thead>
          </table>
        </div>
        <div class="col-3 card">
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                <div class="d-flex flex-column gap-2">
                  <h6 class="mb-2 fw-bold">MANAGE</h6>
                  <button type="button" data-bs-toggle="modal" data-bs-target="#createstaffmodal"
                    class="btn btn-primary">Single Entry</button>
                  <button type="button" data-bs-toggle="modal" data-bs-target="#createbatchstaffmodal"
                    class="btn btn-primary">Batch</button>
                </div>
              </div>
            </div>
            <div class="row mt-4">
              <div class="col-12">
                <div class="d-flex flex-column gap-2">
                  <h6 class="mb-2 fw-bold">DISTRIBUTION OF ROLES</h6>
                  <canvas id="staff_role_distribution"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <script>
    $('#editstaffform').submit(function (e) {
      e.preventDefault();

      const formData = new FormData(this);

      $.ajax({
        url: 'api/post_edit_staff.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
          if (response.trim() === "ok") {
            Swal.fire({
              title: 'Staff member updated successfully!',
              text: 'The staff member information has been updated.',
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
            }).then((result) => {
                if (result.isConfirmed) {
                  location.reload();
                }
              });
            }
        },
        error: function (xhr, status, error) {
          Swal.fire({
            title: 'An error occurred',
            text: 'There was an issue submitting the form. Please try again.',
            icon: 'error',
            confirmButtonText: 'OK'
          });
        }
      });
    })

    $("#addbatchstaffform").submit(function (e) {
      e.preventDefault();

      const formData = new FormData(this);

      $.ajax({
        url: 'api/post_batch_staff.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
          if (response.trim() === "ok") {
            Swal.fire({
              title: 'Members Added Successfully',
              text: 'New staff members has been added.',
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
        },
        error: function (xhr, status, error) {
          Swal.fire({
            title: 'An error occurred',
            text: 'There was an issue submitting the form. Please try again.',
            icon: 'error',
            confirmButtonText: 'OK'
          });
        }
      });
    })

    $('#addsinglestaffform').submit(function (e) {
      e.preventDefault();

      const formData = new FormData(this);

      $.ajax({
        url: 'api/post_add_single_staff.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
          if (response.trim() === "ok") {
            Swal.fire({
              title: 'Staff member added successfully!',
              text: 'The new staff member has been added.',
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
        },
        error: function (xhr, status, error) {
          Swal.fire({
            title: 'An error occurred',
            text: 'There was an issue submitting the form. Please try again.',
            icon: 'error',
            confirmButtonText: 'OK'
          });
        }
      });
    })

    const positionOptions = {
      Admin: ["Admin I", "Admin II"],
      Staff: ["Staff I", "Staff II", "Staff III", "Head Staff I", "Head Staff II"],
      Cashier: ["Cashier I", "Cashier II", "Senior Cashier I"]
    };

    $(document).on("change", "#staffrole", function () {
      const selectedRole = $(this).val();
      const $positionSelect = $('#staffposition');

      $positionSelect.empty().append('<option value="" selected>Select Position</option>');

      if (selectedRole) {
        $positionSelect.prop('disabled', false);
        positionOptions[selectedRole].forEach(position => {
          $positionSelect.append(new Option(position, position));
        });
      } else {
        $positionSelect.prop('disabled', true);
      }
    })

    $(document).ready(function () {
      $('#addstaffrole').change(function () {
        const selectedRole = $(this).val();
        const $positionSelect = $('#addstaffposition');

        $positionSelect.empty().append('<option value="" selected>Select Position</option>');

        if (selectedRole) {
          $positionSelect.prop('disabled', false);
          positionOptions[selectedRole].forEach(position => {
            $positionSelect.append(new Option(position, position));
          });
        } else {
          $positionSelect.prop('disabled', true);
        }
      });
    });

    $(document).on("click", "#deletestaffbtn", function (e) {
      let staffid = $(this).attr('data-target')
      Swal.fire({
        title: "Do you want to delete the staff",
        icon: "info",
        showDenyButton: false,
        showCancelButton: true,
        confirmButtonText: "Yes, Delete It",
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            type: 'post',
            url: 'api/post_delete_staff.php',
            data: {
              staffid: staffid
            },
            success: response => {
              if (response === 'ok') {
                alert('Staff deleted!')
                location.reload();
              }
            }
          })
        }
      });
    })

    $(document).on("click", "#editstaffbtn", function (e) {
      let staffid = $(this).attr('data-target');

      $.ajax({
        type: 'get',
        url: 'api/get_staff_detail.php',
        data: {
          staffid: staffid
        },
        success: response => {
          $("#staffmodalbody").html(response)
          $("#staffmodal").modal("show")
        }
      })
    })

    $(document).ready(function () {
      $('#staffs').DataTable({
        ajax: 'api/get-all.php?table=staff',
        columns: [
          { data: 'name', title: 'Name' },
          { data: 'position', title: 'Position' },
          { data: 'role', title: 'Role' },
          { data: 'username', title: 'Username' },
          {
            data: 'password',
            title: 'Password',
            render: function (data, type, row) {
              return '*'.repeat(data.length);
            }
          },
          {
            data: 'id',
            title: 'Action',
            render: function (data, type, row) {
              const role = row.role;

              let buttons = "";

              if (role !== 'Admin') {
                buttons += `
                  <button id='editstaffbtn' class='btn btn-sm btn-primary mr-4' data-target='${data}'>Edit</button>
                  <button id='deletestaffbtn' class='btn btn-sm btn-danger' data-target='${data}'>Delete</button>
                `;
              }

              return buttons;
            }
          }

        ]
      });

      $.ajax({
        type: 'get',
        url: 'api/chart/staff_role_distribution.php',
        success: response => {
          console.log(response)
          new Chart(document.getElementById('staff_role_distribution').getContext('2d'), {
            type: 'pie',
            data: {
              labels: response.role,
              datasets: [{
                data: response.count,
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
          })
        }
      })
    })


  </script>
</body>

</html>