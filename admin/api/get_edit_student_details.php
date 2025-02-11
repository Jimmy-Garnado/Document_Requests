<?php
  include("connection.php");

  $stuid = $_GET['stuid'];
  
  $getstudent = $conn -> query("SELECT * FROM users WHERE id=$stuid LIMIT 1");
  $row = $getstudent -> fetch_assoc();

  echo "
    <input type='hidden' value='".$row['stuid']."' name='e_stuid' />
    <div class='mb-3'>
      <label class='form-label fw-bold'>Student ID</label>
      <input type='text' class='form-control' value='".$row['stuid']."' disabled>
    </div>
    <div class='mb-3'>
      <label class='form-label fw-bold'>Name</label>
      <input type='text' name='e_stuname' class='form-control' value='".$row['stuname']."' required>
    </div>
    <div class='mb-3'>
      <label class='form-label fw-bold'>Email</label>
      <input type='email' name='e_stuemail' class='form-control' value='".$row['stuemail']."' disabled>
    </div>
    <div class='mb-3'>
      <label class='form-label fw-bold'>Password</label>
      <input type='text' name='e_stupassword' class='form-control' value='".$row['stupassword']."' required>
    </div>
  ";

  $conn -> close();
?>