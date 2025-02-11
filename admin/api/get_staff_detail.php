<?php
  include("connection.php");

  $staffid = $_GET['staffid'];

  $getstaff = $conn -> query("SELECT * FROM staff WHERE id=$staffid");

  while($row = $getstaff -> fetch_assoc()){
    echo "
      <input type='hidden' value='".$row['id']."' name='staffid' required>
      <div class='mb-3'>
        <label for='staffname' class='form-label'>Name</label>
        <input type='text' value='".$row['name']."' class='form-control' name='staffname' id='staffname' placeholder='Juan Dela Cruz' required>
      </div>

      <div class='mb-3'>
        <label for='staffrole' class='form-label'>Position</label>
        <select class='form-select' name='staffrole' id='staffrole' required>
          <option value='Admin' ".(($row['role'] == 'Admin') ? 'selected' : '').">Admin</option>
          <option value='Staff' ".(($row['role'] == 'Staff') ? 'selected' : '').">Staff</option>
          <option value='Cashier' ".(($row['role'] == 'Cashier') ? 'selected' : '').">Cashier</option>
        </select>
      </div>

      <div class='mb-3'>
        <label for='staffposition' class='form-label'>Role</label>
        <select class='form-select staffposition' name='staffposition' id='staffposition' required>
          <option value=''>Select Position</option>
        </select>
      </div>

      <div class='mb-3'>
        <label for='staffusername' class='form-label'>Username</label>
        <input type='text' value='".$row['username']."' class='form-control' name='staffusername' id='staffusername' placeholder='jdelacruz1' required>
      </div>

      <div class='mb-3'>
        <label for='staffpassword' class='form-label'>Password</label>
        <input type='text' value='".$row['password']."' class='form-control' name='staffpassword' id='staffpassword' placeholder='12345' required>
      </div>
    ";
  }

  $conn -> close();
?>