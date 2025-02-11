<?php
  include("connection.php");

  $document_id = $_GET['document_id'];

  $select = $conn -> query("SELECT * FROM supported_documents WHERE id=$document_id LIMIT 1");

  while($row = $select -> fetch_assoc()){
    echo "
      <input type='hidden' name='eid' value='$document_id' />
      <div class='form-group mb-2'>
        <label class='form-label fw-semibold'>Document Name</label>
        <input type='text' name='ename' value='".$row['name']."' class='form-control' required /> 
      </div>
      <div class='form-group mb-2'>
        <label class='form-label fw-semibold'>Document Name</label>
        <select name='eprocessing_time' class='form-control'>
          <option value='1' ".($row['processing_time'] == "1" ? "selected" : "").">1</option>
          <option value='2' ".($row['processing_time'] == "2" ? "selected" : "").">2</option>
          <option value='3' ".($row['processing_time'] == "3" ? "selected" : "").">3</option>
          <option value='4' ".($row['processing_time'] == "4" ? "selected" : "").">4</option>
          <option value='5' ".($row['processing_time'] == "5" ? "selected" : "").">5</option>
          <option value='6' ".($row['processing_time'] == "6" ? "selected" : "").">6</option>
          <option value='7' ".($row['processing_time'] == "7" ? "selected" : "").">7</option>
        </select>
      </div>
      <div class='form-group mb-2'>
        <label class='form-label fw-semibold'>Price</label>
        <input type='number' name='eprice' value='".$row['price']."' class='form-control' required /> 
      </div>
      <div class='form-group mb-2'>
        <label class='form-label fw-semibold'>Is Active</label>
        <select name='eisactive' class='form-control'>
          <option value='1' ".($row['is_active'] == "1" ? "selected" : "").">True</option>
          <option value='0' ".($row['is_active'] == "0" ? "selected" : "").">False</option>
        </select>
      </div>
    ";
  }

  $conn -> close();
?>