<?php
  include("connection.php");
  session_start();

  $clientid = $_SESSION['stuid'];

  $select_all = $conn -> query("SELECT * FROM requests WHERE client_id='$clientid'");

  if($select_all -> num_rows > 0){
    while($row = $select_all -> fetch_assoc()){
      if($row['status'] !== "Pending"){
        $cancelbutton = "
          <a class='btn btn-primary btn-sm mr-4' href='view.php?request_id=".$row['request_id']."'>View</a>
        ";
      }else {
        $cancelbutton = "
          <a class='btn btn-primary btn-sm mr-4' href='view.php?request_id=".$row['request_id']."'>View</a>
          <button data-target='".$row['id']."' class='cancelButton btn btn-danger btn-sm'>Cancel</button>
        ";
      }
      
      echo "
        <tr>
          <td class='align-middle'>".$row['request_id']."</td>
          <td class='align-middle'>".implode(", ", json_decode($row['document_type'], true))."</td>
          <td class='align-middle'>".date("F j, Y h:iA", strtotime($row['date_created']))."</td>
          <td class='align-middle'>".$row['status']."</td>
          <td class='align-middle'>
            $cancelbutton
          </td>
        </tr>
      ";
    }
  }else {
    echo "
      <tr>
        <td class='align-middle'>No available request to show.</td>
      </tr>
    ";
  }
?>