<?php
include("api/connection.php");
session_start();

$clientid = $_SESSION['clientid'];

$select = $conn->query("SELECT * FROM users WHERE id=$clientid LIMIT 1");
$row = $select->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile - BPC E-Registrar</title>
  <?php include('reusables/client-static-loader.php'); ?>
</head>

<body>

  <style>
    main {
      min-height: 100vh;
    }

    .avatar {
      display: flex;
      flex-direction: row;
      gap: 1rem;
    }

    .avatar>img {
      width: 250px;
      height: 250px;
      object-fit: cover;
    }
  </style>

  <main class="container-fluid d-flex flex-lg-row flex-column p-0">
    <?php include("reusables/client-sidebar.php"); ?>

    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="changePasswordForm">
            <div class="modal-body">
              <div class="mb-3">
                <label for="currentPassword" class="form-label">Current Password</label>
                <input type="password" class="form-control" id="currentPassword" name="currentPassword" required />
              </div>
              <div class="mb-3">
                <label for="newPassword" class="form-label">New Password</label>
                <input type="password" class="form-control" id="newPassword" name="newPassword" required />
              </div>
              <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required />
              </div>
              <p id="changePasswordMsg" class="text-danger"></p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" type="submit" class="btn btn-primary">Update Password</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade" id="changePictureModal" tabindex="-1" aria-labelledby="changePictureModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form id="changePictureForm" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title" id="changePictureModalLabel">Change Profile Picture</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
              <img id="picturePreview" src="<?php echo ($row['image_url'] === 'none') ? '/images/default.png' : "." . $row['image_url']; ?>" alt="Preview" class="img-thumbnail mb-3" style="width: 150px; height: 150px; object-fit: cover;" />
              <input type="file" name="profile_picture" id="profilePicture" class="form-control" accept="image/*" required />
              <div id="pictureMsg" class="text-danger mt-2 fw-semibold"></div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Save Picture</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-10 p-4">
      <div class="d-flex flex-row align-items-center justify-content-end mb-4 gap-2">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#changePictureModal">
          Change Picture
        </button>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
          Change Password
        </button>
        <button class="btn btn-success" id="saveChangesButton" disabled>Save Changes</button>
      </div>

      <form id="profileForm">
        <div class="row">
          <div class="col-12 col-lg-6">
            <div class="card mb-4">
              <div class="card-header">
                <h4 class="fw-bold mb-0">Student Details</h4>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-12 col-md-4 d-flex justify-content-center align-items-center">
                    <img src="<?php echo ($row['image_url'] === 'none') ? '/images/default.png' : "." . $row['image_url']; ?>" alt="Profile Picture" class="img-fluid" style="width: 200px; height: 200px; object-fit: cover;" />
                  </div>
                  <div class="col-12 col-md-8">
                    <div class="form-group mb-2">
                      <label class="form-label mb-0 fw-semibold">Student Name</label>
                      <input name="stuname" type="text" class="mt-2 form-control" value="<?php echo $row['stuname']; ?>" readonly />
                    </div>
                    <div class="form-group mb-2">
                      <label class="form-label mb-0 fw-semibold">Student ID</label>
                      <input name="stuid" type="text" class="mt-2 form-control" value="<?php echo $row['stuid']; ?>" readonly />
                    </div>
                    <div class="form-group mb-2">
                      <label class="form-label mb-0 fw-semibold">Student Email</label>
                      <input name="stuemail" type="text" class="mt-2 form-control" value="<?php echo $row['stuemail']; ?>" readonly />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="card mb-4">
              <div class="card-header">
                <h4 class="fw-bold mb-0">Contact Details</h4>
              </div>
              <div class="card-body">
                <div class="form-group mb-2">
                  <label class="form-label mb-0 fw-semibold">Sex</label>
                  <select name="sex" class="mt-2 form-select" required>
                    <option value="Male" <?php echo ($row['sex'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo ($row['sex'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                  </select>
                </div>
                <div class="form-group mb-2">
                  <label class="form-label mb-0 fw-semibold">Birthday</label>
                  <input type="date" name="birthday" class="mt-2 form-control"  value="<?php echo $row['birthday']; ?>" required />
                </div>
                <div class="form-group mb-2">
                  <label class="form-label mb-0 fw-semibold">Contact Number</label>
                  <input type="text" name="contact_number" class="mt-2 form-control" value="<?php echo ($row['contact_number'] === 'none') ? '' : $row['contact_number']; ?>" required />
                </div>
                <div class="form-group mb-2">
                  <label class="form-label mb-0 fw-semibold">Email</label>
                  <input type="text" name="email" class="mt-2 form-control" value="<?php echo ($row['email'] === 'none') ? '' : $row['email']; ?>" required />
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-lg-6">
            <div class="card">
              <div class="card-header">
                <h4 class="fw-bold mb-0">Address Details</h4>
              </div>
              <div class="card-body">
                <div class="form-group mb-2">
                  <label class="form-label mb-0 fw-semibold">Street</label>
                  <input type="text" name="street" class="mt-2 form-control" value="<?php echo ($row['street'] === 'none') ? '' : $row['street']; ?>" required />
                </div>
                <div class="form-group mb-2">
                  <label class="form-label mb-0 fw-semibold">Barangay</label>
                  <input type="text" name="barangay" class="mt-2 form-control" value="<?php echo ($row['barangay'] === 'none') ? '' : $row['barangay']; ?>" required />
                </div>
                <div class="form-group mb-2">
                  <label class="form-label mb-0 fw-semibold">City</label>
                  <input type="text" name="city" class="mt-2 form-control" value="<?php echo ($row['city'] === 'none') ? '' : $row['city']; ?>" required />
                </div>
                <div class="form-group mb-2">
                  <label class="form-label mb-0 fw-semibold">Province</label>
                  <input type="text" name="province" class="mt-2 form-control" value="<?php echo ($row['province'] === 'none') ? '' : $row['province']; ?>" required />
                </div>
              </div>
            </div>
            <div class="card mt-4">
              <div class="card-header">
                <h4 class="fw-bold mb-0">Transaction History</h4>
              </div>
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th>Transaction ID</th>
                      <th>Amount</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $transactions = $conn->query("SELECT * FROM v2_requests WHERE student_id='" . $row['stuid'] . "' ORDER BY date_created DESC");
                    while ($transaction = $transactions->fetch_assoc()) {
                      echo "<tr>
                      <td>" . date("M j, Y", strtotime($transaction['date_created'])) . "</td>
                        <td><a href='view.php?r={$transaction['request_id']}'>{$transaction['request_id']}</a></td>
                        <td>" . number_format($transaction['total_price'], 2, '.', ',') . "</td>
                        <td>{$transaction['status']}</td>
                      </tr>";
                    }
                    $conn->close();
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </main>
  <script>
    // Preview selected image
    $('#profilePicture').on('change', function() {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          $('#picturePreview').attr('src', e.target.result);
        };
        reader.readAsDataURL(file);
      }
    });

    // Submit via AJAX
    $('#changePictureForm').on('submit', function(e) {
      e.preventDefault();
      const formData = new FormData(this);

      $.ajax({
        url: 'api/change_picture.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          if (response === 'success') {
            $('#pictureMsg').removeClass('text-danger').addClass('text-success').text('Picture updated!');
            setTimeout(() => {
              $('#changePictureModal').modal('hide');
              $('#pictureMsg').text('').removeClass('text-success');
            }, 1000);

            setTimeout(() => {
              alert('Picture updated!');
              location.reload();
            }, 1500);

          } else {
            $('#pictureMsg').text(response);
          }
        },
        error: function() {
          $('#pictureMsg').text('Upload failed. Try again.');
        }
      });
    });

    $('#changePasswordForm').on('submit', function(e) {
      e.preventDefault();

      const currentPassword = $('#currentPassword').val();
      const newPassword = $('#newPassword').val();
      const confirmPassword = $('#confirmPassword').val();

      if (newPassword !== confirmPassword) {
        $('#changePasswordMsg').text('New passwords do not match.');
        return;
      }

      $.ajax({
        url: 'api/change_password.php',
        type: 'POST',
        data: {
          currentPassword,
          newPassword
        },
        success: function(response) {
          if (response === 'success') {
            $('#changePasswordMsg').removeClass('text-danger').addClass('text-success').text('Password changed successfully!');

            setTimeout(() => {
              $('#changePasswordModal').modal('hide');
              $('#changePasswordForm')[0].reset();
              $('#changePasswordMsg').text('').removeClass('text-success');

            }, 2000);

          } else {
            $('#changePasswordMsg').text(response);
          }
        },
        error: function() {
          $('#changePasswordMsg').text('An error occurred. Please try again.');
        }
      });
    });

    // Enable the button when any input changes
    $('#profileForm input, #profileForm select').on('input change', function() {
      $('#saveChangesButton').prop('disabled', false);
    });

    $('#saveChangesButton').on('click', function() {
      $('#profileForm').submit();
    });

    $("#profileForm").submit(function(e) {
      e.preventDefault()

      let formdata = new FormData(this)

      $.ajax({
        type: 'post',
        url: 'api/post_edit_profile.php',
        data: formdata,
        processData: false,
        contentType: false,
        success: response => {
          if (response === "ok") {
            alert("Profile updated successfully!")

            location.reload();
          }
        }
      })
    })
  </script>
</body>

</html>