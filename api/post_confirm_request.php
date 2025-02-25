<?php
  session_start();
  include("connection.php");

  $stuid = $_SESSION['stuid'];

  $select = $conn -> query("SELECT * FROM users WHERE stuid='$stuid' LIMIT 1");
  $user = $select -> fetch_assoc();

  $document_type = $_POST['document_type'];
  echo "
    <h4 class='fw-bold mb-4'>Request Details</h4>
    <div class='row'>
      <div class='col-4'>
        <label class='fw-semibold'>Request Date</label>
      </div>
      <div class='col-8'>".$_POST['request_date']."</div>
    </div>
    <div class='row'>
      <div class='col-4'>
        <label class='fw-semibold'>Student Number</label>
      </div>
      <div class='col-8'>$stuid</div>
    </div>
    <div class='row'>
      <div class='col-4'>
        <label class='fw-semibold'>Program</label>
      </div>
      <div class='col-8'>".$_POST['program']."</div>
    </div>
    <div class='row'>
      <div class='col-4'>
        <label class='fw-semibold'>Year Graduated</label>
      </div>
      <div class='col-8'>".$_POST['year_graduated']."</div>
    </div>
    <div class='row'>
      <div class='col-4'>
        <label class='fw-semibold'>Document Type</label>
      </div>
    </div>
    <div class='row'>
    ";
    foreach($document_type as $doc_type ) {
      echo "
      <div class='col-2'>
          <input class='form-check-input' type='checkbox' value='".$doc_type."' checked disabled />
      
      </div>
      <div class='col-10'>
          <label class='form-check-label'>".$doc_type."</label>
      
      </div>
       ";
    }
    echo "
    </div>
    <div class='row'>
      <div class='col-4'>
        <label class='fw-semibold'>Academic Year</label>
      </div>
      <div class='col-8'>".$_POST['academic_year']."</div>
    </div>
    <div class='row'>
      <div class='col-4'>
        <label class='fw-semibold'>Purpose</label>
      </div>
      <div class='col-8'>".$_POST['purpose']."</div>
    </div>
    <h4 class='fw-bold mb-4 mt-4'>Contact And Address Details</h4>
    <div class='row'>
      <div class='col-4'>
        <label class='fw-semibold'>Contact Number</label>
      </div>
      <div class='col-6'>".$user['contact_number']."</div>
      <div class='col-2'>
        <a href='profile.php' class='btn btn-sm btn-link'>Edit</a>
      </div>
    </div>
    <div class='row'>
      <div class='col-4'>
        <label class='fw-semibold'>Email</label>
      </div>
      <div class='col-6'>".$user['email']."</div>
      <div class='col-2'>
        <a href='profile.php' class='btn btn-sm btn-link'>Edit</a>
      </div>
    </div>
    <div class='row'>
      <div class='col-4'>
        <label class='fw-semibold'>Address</label>
      </div>
      <div class='col-6'>".$user['street'].", ".$user['barangay'].", ".$user['city'].", ".$user['province']."</div>
      <div class='col-2'>
        <a href='profile.php' class='btn btn-sm btn-link'>Edit</a>
      </div>
    </div>
  ";
?>