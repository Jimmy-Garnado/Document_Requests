<?php
  if(!isset($_GET['request_id'])){
    header("location: request.php");
  }else {
    include("api/connection.php");

    $requestID = $_GET['request_id'];

    $select = $conn -> query("SELECT * FROM requests WHERE request_id='$requestID' LIMIT 1");
    $row = $select -> fetch_assoc();

    $conn -> close();
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Viewing Request - BPC E-Registrar</title>
  <?php include("reusables/client-static-loader.php"); ?>
</head>
<body>
  
  <style>
    main {
      min-height: 100vh;
    }

    section > div {
      background-color: #fff;
      padding: 2rem 4rem;
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    @media (max-width: 992px) {
      section > div {
        padding: 1rem;
      }

      .submitrequest-desktop {
        display: none;
      }
    }

  </style>

  <main class="container-fluid d-flex flex-lg-row flex-column p-0">
    <?php include("reusables/client-sidebar.php"); ?>
    <section class="col-12 col-lg-10">
      <div>
        <h5>Document: <?php echo $row['document_type']; ?></h5>
        <h5>Client: <?php echo $row['client_name']; ?></h5>
        <h5>Status: <?php echo $row['status']; ?></h5>
        <?php 
          if($row['status'] === "Rejected"){
            echo "<h5>Reason: ".$row['reject_reason']."</h5>";
          }
        ?>
        <h5>Payment Status: <?php 
          if($row['payment_status'] == false){
            echo "<span class='badge bg-danger'>UNPAID</span>"; 
          }else {
            echo "<span class='badge bg-success'>PAID</span>"; 
          }
        ?></h5>
        <h5>Price: <?php echo $row['price']; ?> PHP</h5>
        <h5>Date Requested: <?php echo date('M d, Y', strtotime($row['request_date'])) ; ?></h5>
        <hr />
        <h3>Student Information</h3>
        <div class="row">
          <div class="col-12 col-md-6 col-lg-3">
            <label for="exampleInputPassword1" class="form-label">Student Number</label>
            <input type="text" class="form-control" placeholder="04-0001-2627" value="<?php echo $row['student_number']; ?>" readonly>
          </div>
          <div class="col-12 col-md-6 col-lg-3">
            <label for="exampleInputPassword1" class="form-label">Program</label>
            <select class="form-select" aria-label="Default select example">
              <option selected><?php echo $row['program_degree']; ?></option>
            </select>
          </div>
          <div class="col-12 col-md-6 col-lg-3">
            <label for="exampleInputPassword1" class="form-label">Year Graduated</label>
            <select class="form-select" aria-label="Default select example">
              <option selected><?php echo $row['year_graduated']; ?></option>
            </select>
          </div>
        </div>
        <h3 class="mt-4">Address & Contact Information</h3>
        <div class="row">
          <div class="col-12 col-md-6 col-lg-3">
            <label for="exampleInputPassword1" class="form-label">Email Address</label>
            <input type="text" class="form-control" placeholder="j.delacruz@gmail.com" value="<?php echo $row['client_email']; ?>" readonly>
          </div>
          <div class="col-12 col-md-6 col-lg-3">
            <label for="exampleInputPassword1" class="form-label">Contact Number</label>
            <input type="text" class="form-control" placeholder="09762220955" value="<?php echo $row['client_contact_number1']; ?>" readonly>
          </div>
          <div class="col-12 col-md-6 col-lg-3">
            <label for="exampleInputPassword1" class="form-label">Alternate Contact Number</label>
            <input type="text" class="form-control" placeholder="09304696712" value="<?php echo $row['client_contact_number2']; ?>" readonly>
          </div>
        </div>
        <div class="row">
          <div class="col-12 col-md-6 col-lg-3">
            <label for="exampleInputPassword1" class="form-label">Street Name</label>
            <input type="text" class="form-control" placeholder="Nakpil St" value="<?php echo $row['street_name']; ?>" readonly>
          </div>
          <div class="col-12 col-md-6 col-lg-3">
            <label for="exampleInputPassword1" class="form-label">Barangay</label>
            <input type="text" class="form-control" placeholder="Isidro" value="<?php echo $row['barangay']; ?>" readonly>
          </div>
          <div class="col-12 col-md-6 col-lg-3">
            <label for="exampleInputPassword1" class="form-label">Municipality/City</label>
            <input type="text" class="form-control" placeholder="Pampanga" value="<?php echo $row['city']; ?>" readonly>
          </div>
        </div>
        <h3 class="mt-4">Request Details</h3>
        <div class="row">
          <div class="col-12 col-md-6 col-lg-3">
            <label for="exampleInputPassword1" class="form-label">Document Type</label>
            <select class="form-select">
              <option selected><?php echo $row['document_type']; ?></option>
            </select>
          </div>
          <div class="col-12 col-md-6 col-lg-3">
            <label for="exampleInputPassword1" class="form-label">Academic Year</label>
            <select class="form-select" aria-label="Default select example">
              <option selected><?php echo $row['academic_year']; ?></option>
            </select>
          </div>
          <div class="col-12 col-md-6 col-lg-3">
            <label for="exampleInputPassword1" class="form-label">Purpose</label>
            <input type="text" class="form-control" placeholder="For employment" value="<?php echo $row['purpose']; ?>" readonly>
          </div>
        </div>
      </div>
    </section>
  </main>
  <script>
    
  </script>
</body>
</html>