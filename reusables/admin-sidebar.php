<style>
  @media (max-width: 992px) {
    .sidebar {
      display: none !important;
    }
  }

  .sidebar>a {
    padding: 1rem;
    cursor: pointer;
    color: #fff !important;
    font-size: 18px;
    text-decoration: none;
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 0.75rem;
  }

  .sidebar>a:hover {
    background-color: #073000;
  }

  .sidebar {
    display: flex;
    flex-direction: column;
    background-color: <?php echo $CONTENT['sidebar_color']; ?>;
    position: relative;
    top: 0;
    left: 0;
  }

  .sidebar>img {
    margin-top: 2rem;
  }

  .sidebar>h6 {
    color: #fff;
    margin-top: 1rem;
    text-align: center;
  }

  .avatar {
    padding: 1rem;
    background-color: #fff;
    margin-top: 2rem;
  }

  #sidebar {
    min-width: 250px;
    max-width: 250px;
    min-height: 100vh;
    background-color: #343a40;
    color: white;
  }

  #sidebar .nav-link {
    color: white;
  }

  #sidebar .nav-link:hover {
    background-color: #495057;
  }

  /* Toggle button on mobile */
  #sidebarToggle {
    display: none;
  }

  #mobile-header {
    background-color: #073000;
    color: #fff;
  }

  .navbar-brand {
    display: flex;
    flex-direction: row;
    gap: 0.5rem;
    color: #fff;
  }

  .nav-link {
    color: #fff;
    font-size: 18px;
    padding: 1rem;
    display: flex;
    flex-direction: row;
    gap: 1rem;
    align-items: center;
  }

  .navbar-toggler {
    border-radius: 4px;
    background-color: #fff;
    font-size: 12px;
  }

  @media (max-width: 768px) {
    .sidebar {
      display: none;
    }

    #sidebar {
      display: none;
    }

    #sidebar.collapse.show {
      display: block;
    }

    #sidebarToggle {
      display: inline;
    }
  }
</style>

<nav class="navbar d-lg-none" id="mobile-header">
  <div class="container-fluid">
    <a class="navbar-brand" href="request.php">BPC E-Registrar 2024</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMenu" aria-controls="mobileMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mobileMenu">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php
        if ($_SESSION['staffrole'] === "Admin") {
          echo "
      <a class='nav-link' href='request.php'>
        <i class='fa-regular fa-file'></i>
        <span>REQUEST</span>
      </a>
      <a class='nav-link' href='staffs.php'>
        <i class='fa-regular fa-user'></i>
        <span>STAFF</span>
      </a>
      <a class='nav-link' href='students.php'>
        <i class='fa-regular fa-user'></i>
        <span>STUDENT</span>
      </a>
      <a class='nav-link' href='content_management.php'>
        <i class='fa-solid fa-gear'></i>
        <span>CONTENT MANAGEMENT</span>
      </a>
      ";
        }
        ?>

        <?php
        if ($_SESSION['staffrole'] === "Staff") {
          echo "
          <a class='nav-link' href='request.php'>
            <i class='fa-regular fa-file'></i>
            <span>Document Request</span>
          </a>
          <a class='nav-link' href='processing.php'>
            <i class='fa-regular fa-file'></i>
            <span>In-Process</span>
          </a>
          <a class='nav-link' href='for-release.php'>
            <i class='fa-regular fa-file'></i>
            <span>For Release</span>
          </a>
          <a class='nav-link' href='archive.php'>
            <i class='fa-regular fa-file'></i>
            <span>Archive</span>
          </a>
          <a class='nav-link' href='supported_documents.php'>
            <i class='fa-solid fa-gear'></i>
            <span>Document Types</span>
          </a>
          ";
        }
        ?>

        <?php
        if ($_SESSION['staffrole'] === "Cashier") {
          echo "
        <a class='nav-link' href='cashier.php'>
          <i class='fa-regular fa-file'></i>
          <span>CASHIER</span>
        </a>
      ";
        }
        ?>
        <a class="nav-link logoutButton">
          <i class="fa-solid fa-power-off mr-4"></i>
          <span>Log Out</span>
        </a>
      </ul>
    </div>
  </div>
</nav>

<div class="sidebar col-2">
  <img src="../<?php echo $CONTENT['logo_url']; ?>" width="100px" height="100px" class="align-self-center">
  <div class="row p-4">
    <h4 class="text-white"><?php echo $_SESSION['staffname']; ?></h4>
    <h6 class="text-white"><?php echo $_SESSION['staffrole']; ?></h6>
  </div>
  <h2 class="mt-4 mb-4 align-self-center text-white ">Menu</h2>
  <?php
  if ($_SESSION['staffrole'] === "Admin") {
    echo "
      <a href='request.php'>
        <i class='fa-regular fa-file'></i>
        <span>Requests</span>
      </a>
      <a href='staffs.php'>
        <i class='fa-regular fa-user'></i>
        <span>Staffs</span>
      </a>
      <a href='students.php'>
        <i class='fa-regular fa-user'></i>
        <span>Students</span>
      </a>
      <a href='content_management.php'>
        <i class='fa-solid fa-gear'></i>
        <span>CMS</span>
      </a>
      ";
  }
  ?>

  <?php
  if ($_SESSION['staffrole'] === "Staff") {
    echo "
      <a href='request.php'>
        <i class='fa-regular fa-file'></i>
        <span>Requests</span>
      </a>
      <a href='processing.php'>
        <i class='fa-regular fa-file'></i>
        <span>In-Processing</span>
      </a>
      <a href='for-release.php'>
        <i class='fa-regular fa-file'></i>
        <span>For Release</span>
      </a>
      <a href='archive.php'>
        <i class='fa-regular fa-file'></i>
        <span>Archive</span>
      </a>
      <a href='sections.php'>
        <i class='fa-solid fa-gear'></i>
        <span>Sections</span>
      </a>
      <a href='supported_documents.php'>
        <i class='fa-solid fa-gear'></i>
        <span>Document Types</span>
      </a>
      ";
  }
  ?>

  <?php
  if ($_SESSION['staffrole'] === "Cashier") {
    echo "
        <a href='cashier.php'>
          <i class='fa-regular fa-file'></i>
          <span>Cashier</span>
        </a>
        <a href='payment-logs.php'>
          <i class='fa-solid fa-receipt'></i>
          <span>Payment Logs</span>
        </a>
      ";
  }
  ?>

  <!-- <a href="reports.php">
    <i class="fa-regular fa-file"></i>
    <span>Reports</span>
  </a> -->
  <!-- <a href="notifications.php">
    <i class="fa-regular fa-bell"></i>
    <span>Notifications</span>
  </a> -->
  <a class="logoutButton">
    <i class="fa-solid fa-power-off"></i>
    <span>Log Out</span>
  </a>

  <h6><?php echo $CONTENT['system_name']; ?></h6>
</div>

<script>
  $(".logoutButton").on("click", function() {
    Swal.fire({
      title: "Are you sure you want to logout?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, logout"
    }).then((result) => {

      if (result.isConfirmed) {
        $.ajax({
          type: "get",
          url: "api/logout.php",
          success: response => {
            location.href = "../index.php"
          }
        })
      }
    });
  })
</script>