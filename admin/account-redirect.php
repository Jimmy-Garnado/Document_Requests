<?php
  session_start();

  switch($_SESSION['staffrole']){
    case "Admin":
      header("location: staffs.php");
      break;
    case "Staff":
      header("location: request.php");
      break;
    case "Cashier":
      header("location: cashier.php");
      break;
    default: 
      header("location: api/logout.php");
      break;
  }
?>