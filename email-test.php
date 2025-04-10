<?php
  include_once("module/emailer.php");

  $email_body = "
    <p>This is subject body</p>
  ";

  SendEmail("bokzgacilo@gmail.com", "This is a test",  $email_body)
?>