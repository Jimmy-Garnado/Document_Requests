<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Request - BPC E-Registrar</title>
  <script src="asset/jquery.js"></script>
  <script src="asset/bootstrap/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="asset/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="asset/css/main-style.css">
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

  </style>

  <main class="container-fluid d-flex flex-row p-0">
    <?php include("reusables/client-sidebar.php"); ?>
    <section class="col-10">
      <div>
        <div style="display: flex; flex-direction: row; justify-content: space-between; align-items:center;">
          <h1>Request Fill Out Form</h1>
          <button class="btn btn-primary btn-lg">Proceed To Review & Payment</button>
        </div>
        <hr />
        <h3>Student Information</h3>
        <div class="row">
          <div class="col-3">
            <label for="exampleInputPassword1" class="form-label">Student Number</label>
            <input type="text" class="form-control" placeholder="04-0001-2627">
          </div>
        </div>
        <div class="row">
          <div class="col-3">
            <label for="exampleInputPassword1" class="form-label">Last Name</label>
            <input type="text" class="form-control" placeholder="Dela Cruz">
          </div>
          <div class="col-3">
            <label for="exampleInputPassword1" class="form-label">First Name</label>
            <input type="text" class="form-control" placeholder="Juan Mark">
          </div>
          <div class="col-3">
            <label for="exampleInputPassword1" class="form-label">Last Name</label>
            <input type="text" class="form-control" placeholder="Marcos">
          </div>
          <div class="col-3">
            <label for="exampleInputPassword1" class="form-label">Extension Name</label>
            <select class="form-select" aria-label="Default select example">
              <option selected>None</option>
              <option value="jr">Jr</option>
              <option value="sr">Sr</option>
              <option value="ii">II</option>
              <option value="iii">III</option>
              <option value="iv">IV</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-3">
            <label for="exampleInputPassword1" class="form-label">Program</label>
            <select class="form-select" aria-label="Default select example">
              <option value="bsis">BSIS</option>
              <option value="bsom">BSOM</option>
              <option value="bsais">BSAIS</option>
              <option value="btvted">BTVTEd</option>
              <option value="act">ACT</option>
            </select>
          </div>
          <div class="col-3">
            <label for="exampleInputPassword1" class="form-label">Year Graduated</label>
            <select class="form-select" aria-label="Default select example">
              <option value="2015">2015</option>
              <option value="2016">2016</option>
              <option value="2017">2017</option>
              <option value="2018">2018</option>
              <option value="2019">2019</option>
              <option value="2020">2020</option>
              <option value="2021">2021</option>
              <option value="2022">2022</option>
              <option value="2023">2023</option>
              <option value="2024">2024</option>
            </select>
          </div>
        </div>
        <h3 class="mt-4">Address & Contact Information</h3>
        <div class="row">
          <div class="col-3">
            <label for="exampleInputPassword1" class="form-label">Email Address</label>
            <input type="text" class="form-control" placeholder="j.delacruz@gmail.com">
          </div>
          <div class="col-3">
            <label for="exampleInputPassword1" class="form-label">Contact Number</label>
            <input type="text" class="form-control" placeholder="09762220955">
          </div>
          <div class="col-3">
            <label for="exampleInputPassword1" class="form-label">Alternate Contact Number</label>
            <input type="text" class="form-control" placeholder="09304696712">
          </div>
        </div>
        <div class="row">
          <div class="col-3">
            <label for="exampleInputPassword1" class="form-label">Block/House/Building Number</label>
            <input type="text" class="form-control" placeholder="Building 37">
          </div>
          <div class="col-3">
            <label for="exampleInputPassword1" class="form-label">Street Name</label>
            <input type="text" class="form-control" placeholder="Nakpil St">
          </div>
          <div class="col-3">
            <label for="exampleInputPassword1" class="form-label">Barangay</label>
            <input type="text" class="form-control" placeholder="Isidro">
          </div>
          <div class="col-3">
            <label for="exampleInputPassword1" class="form-label">Municipality/City</label>
            <input type="text" class="form-control" placeholder="Pampanga">
          </div>
        </div>
        <h3 class="mt-4">Request Details</h3>
        <div class="row">
          <div class="col-3">
            <label for="exampleInputPassword1" class="form-label">Document Type</label>
            <select class="form-select">
              <option value="tor">Transcript Of Records</option>
              <option value="good_moral">Good Moral</option>
              <option value="diploma">Diploma</option>
              <option value="c_graduation">Certificate Of Graduation</option>
              <option value="c_enrollment">Certificate Of Enrollment</option>
              <option value="c_grades">Certificate Of Grades</option>
            </select>
          </div>
          <div class="col-3">
            <label for="exampleInputPassword1" class="form-label">Academic Year</label>
            <select class="form-select" aria-label="Default select example">
              <option value="2015">2015</option>
              <option value="2016">2016</option>
              <option value="2017">2017</option>
              <option value="2018">2018</option>
              <option value="2019">2019</option>
              <option value="2020">2020</option>
              <option value="2021">2021</option>
              <option value="2022">2022</option>
              <option value="2023">2023</option>
              <option value="2024">2024</option>
            </select>
          </div>
          <div class="col-3">
            <label for="exampleInputPassword1" class="form-label">Purpose</label>
            <input type="text" class="form-control" placeholder="For employment">
          </div>
        </div>
      </div>
    </section>
  </main>
  <script>
    
  </script>
</body>
</html>