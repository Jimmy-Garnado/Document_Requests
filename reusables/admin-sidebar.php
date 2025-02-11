<style>
  .sidebar > a {
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

  .sidebar > a:hover {
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

  .sidebar > img {
    margin-top: 2rem;
  }

  .sidebar > h6 {
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
<nav id="sidebar" class="collapse">
  <div class="p-4">
    <h4>Sidebar Menu</h4>
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link" href="#">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">About</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Services</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Contact</a>
      </li>
    </ul>
  </div>
</nav>

<div class="sidebar col-2">
  <img src="../<?php echo $CONTENT['logo_url']; ?>" width="100px" height="100px" class="align-self-center">
  <div class="row p-4">
    <h4 class="text-white"><?php echo $_SESSION['staffname'];?></h4>
    <h6 class="text-white"><?php echo $_SESSION['staffrole'];?></h6>
  </div>
  <h2 class="mt-4 mb-4 align-self-center text-white ">Menu</h2>
  <?php
    if($_SESSION['staffrole'] === "Admin"){
      echo "
      <a href='request.php'>
        <i class='fa-regular fa-file'></i>
        <span>REQUEST</span>
      </a>
      <a href='staffs.php'>
        <i class='fa-regular fa-user'></i>
        <span>STAFF</span>
      </a>
      <a href='students.php'>
        <i class='fa-regular fa-user'></i>
        <span>STUDENT</span>
      </a>
      <a href='content_management.php'>
        <i class='fa-solid fa-gear'></i>
        <span>CONTENT MANAGEMENT</span>
      </a>
      ";
    }
  ?>

  <?php
    if($_SESSION['staffrole'] === "Staff"){
      echo "
      <a href='request.php'>
        <i class='fa-regular fa-file'></i>
        <span>REQUEST</span>
      </a>
      <a href='processing.php'>
        <i class='fa-regular fa-file'></i>
        <span>IN-PROCESSING</span>
      </a>
      <a href='archive.php'>
        <i class='fa-regular fa-file'></i>
        <span>ARCHIVES</span>
      </a>
      <a href='supported_documents.php'>
        <i class='fa-solid fa-gear'></i>
        <span>SUPPORTED DOCUMENTS</span>
      </a>
      ";
    }
  ?>


  

  <?php
    if($_SESSION['staffrole'] === "Cashier"){
      echo "
        <a href='cashier.php'>
          <i class='fa-regular fa-file'></i>
          <span>CASHIER</span>
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
    <span>LOG OUT</span>
  </a>

  <h6><?php echo $CONTENT['system_name']; ?></h6>
</div>

<script>
  $(".logoutButton").on("click", function(){
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
            location.href = "../index.html"
          }
        })
      }
    });
  })
</script>