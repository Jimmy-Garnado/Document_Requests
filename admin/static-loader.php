<?php

  if (!isset($_SESSION['staffid']) && !isset($_SESSION['staffrole'])) {
    session_destroy();
    header("location: index.html");
    exit();
  }
?>

<script src="../asset/jquery.js"></script>
<script src="../asset/bootstrap/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="../asset/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="../asset/css/main-style.css">
<link rel="stylesheet" href="css/style.css">
<script src="../asset/sweetalert.js"></script>
<script src="../asset/chart.js"></script>

<!-- For DataTable -->
<link rel="stylesheet" href="../asset/datatables/dataTables.bootstrap5.min.css">
<script src="../asset/datatables/dataTables.bootstrap5.min.js"></script>
<script src="../asset/datatables/dataTables.min.js"></script>

<script>
  function toggleLoadingModal() {
    if ($('#loadingModal').hasClass('show')) {
      $('#loadingModal').modal('hide');
    } else {
      $('#loadingModal').modal({
        backdrop: 'static', 
        keyboard: false 
      }).modal('show');
    }
  }
</script>

<body>
  <!-- Loading Spinner -->
  <div class="modal fade" id="loadingModal" tabindex="-1" aria-labelledby="loadingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body text-center p-4">
          <div class="spinner-border text-primary m-4" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>

          <h6>Loading, please wait...</h6>
        </div>
      </div>
    </div>
  </div>
</body>