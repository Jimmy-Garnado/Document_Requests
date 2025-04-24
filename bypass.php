<?php
  session_start();

  $_SESSION['stuid'] = "MA21010509";
  $_SESSION['clientid'] = 5;

  header("Location: v2-request.php");
?>