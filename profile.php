<?php
  include("api/connection.php");
  session_start();

  $clientid = $_SESSION['clientid'];

  $select = $conn -> query("SELECT * FROM users WHERE id=$clientid LIMIT 1");
  $row = $select -> fetch_assoc();

  $conn -> close();
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

    .avatar > img {
      width: 250px;
      height: 250px;
      object-fit: cover;
    }
  </style>

  <main class="container-fluid d-flex flex-lg-row flex-column p-0">
    <?php include("reusables/client-sidebar.php"); ?>

    <div class="col-12 col-lg-10 p-4">
    <form id="profileform">

      <div class="d-flex flex-row justify-content-between align-items-center">
        <h4 class="fw-bold">PROFILE</h4>
        <button class="btn btn-success">Update Profile</button>
      </div>
      <hr />
      <div class="row">
        <div class="col-12 col-lg-4 d-flex flex-column gap-2">
          <h5 class="fw-bold mb-4">Image</h5>
          <img class="img-fluid" src="<?php echo $row['image_url']; ?>" />
          <div class="avatarform">
            <input type="file" accept="image/*" class='form-control' />
          </div>
        </div>
        <div class="col-12 col-lg-4">
          <h5 class="fw-bold mb-4">Account Details</h5>
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
            <input name="stuememail" type="text" class="mt-2 form-control" value="<?php echo $row['stuemail']; ?>" readonly />
          </div>
          <div class="form-group mb-2">
            <label class="form-label mb-0 fw-semibold">Password</label>
            <input name="stupassword" type="text" class="mt-2  form-control" value="<?php echo $row['stupassword']; ?>" required />
          </div>
        </div>
        <div class="col-12 col-lg-4">
          <h5 class="fw-bold mb-4">Contact Details</h5>
          <div class="form-group mb-2">
            <label class="form-label mb-0 fw-semibold">Contact Number</label>
            <input type="text" name="contact_number" class="mt-2 form-control" value="<?php echo ($row['contact_number'] === 'none') ? '' : $row['contact_number']; ?>" required />
          </div>
          <div class="form-group mb-4">
            <label class="form-label mb-0 fw-semibold">Email</label>
            <input type="text" name="email"class="mt-2  form-control" value="<?php echo ($row['email'] === 'none') ? '' : $row['email']; ?>" required />
          </div>
          <h5 class="fw-bold mb-4">Address Details</h5>
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
            <input type="text" name="city" class="mt-2 form-control"value="<?php echo ($row['city'] === 'none') ? '' : $row['city']; ?>" required />
          </div>
          <div class="form-group mb-2">
            <label class="form-label mb-0 fw-semibold">Province</label>
            <input type="text" name="province" class="mt-2 form-control" value="<?php echo ($row['province'] === 'none') ? '' : $row['province']; ?>" required />
          </div>
        </div>
      </div>
      <div>
      </div>
    </form>

    </div>

  </main>
  <script>
    $("#profileform").submit(function(e){
      e.preventDefault()

      let formdata = new FormData(this)

      $.ajax({
        type: 'post',
        url: 'api/post_edit_profile.php',
        data: formdata,
        processData: false,
        contentType: false,
        success: response => {
          if(response === "ok"){
            location.reload();
          }
        }
      })
    })
  </script>
</body>
</html>